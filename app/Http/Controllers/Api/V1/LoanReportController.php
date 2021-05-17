<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Module;
use Util;

use DB;
use App\Affiliate;
use App\City;
use App\User;
use App\Loan;
use App\LoanState;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ArchivoPrimarioExport;
use App\Exports\SheetExportPayment;
use App\Exports\MultipleSheetExportPayment;
use App\Exports\MultipleSheetExportPaymentMora;
use Illuminate\Support\Facades\Storage;
//use App\Exports\SheetExportPayment;

class LoanReportController extends Controller
{
  /** @group Reportes de Prestamos
   * Reporte de prestamos desembolsados 
   * Reporte de prestamos desembolsados o vigentes 
   * @queryParam initial_date Fecha inicial. Example: 2021-01-01
   * @queryParam final_date Fecha Final. Example: 2021-05-01
   * @authenticated
   * @responseFile responses/report_loans/loan_desembolsado.200.json
   */

  public function report_loan_vigent(Request $request){
    // aumenta el tiempo máximo de ejecución de este script a 150 min:
    ini_set('max_execution_time', 9000);
    // aumentar el tamaño de memoria permitido de este script:
    ini_set('memory_limit', '960M');

    $order_loan = 'Desc';
    $initial_date = request('initial_date') ?? '';
    $final_date = request('final_date') ?? '';
    $state_vigente='Vigente';
    $conditions = [];
    if ($initial_date != '') {
      array_push($conditions, array('loans.disbursement_date', '>=', "%{$initial_date}%"));
    }
    if ($final_date != '') {
      array_push($conditions, array('loans.disbursement_date', '<=', "%{$final_date}%"));
    }
    
    array_push($conditions, array('loan_states.name', 'ilike', "%{$state_vigente}%"));

       $list_loan = DB::table('loans')
               ->join('procedure_modalities','loans.procedure_modality_id', '=', 'procedure_modalities.id')
               ->join('procedure_types','procedure_modalities.procedure_type_id', '=', 'procedure_types.id')
               ->join('loan_states','loans.state_id', '=', 'loan_states.id')
               ->join('cities','loans.city_id', '=', 'cities.id')
               ->join('loan_affiliates','loans.id', '=', 'loan_affiliates.loan_id')
               ->join('affiliates','loan_affiliates.affiliate_id', '=', 'affiliates.id')
               ->leftjoin('spouses','affiliates.id', '=', 'spouses.affiliate_id')
               ->join('affiliate_states','affiliates.affiliate_state_id', '=', 'affiliate_states.id')
               ->join('affiliate_state_types','affiliate_states.affiliate_state_type_id', '=', 'affiliate_state_types.id')
               ->leftjoin('pension_entities','affiliates.pension_entity_id', '=', 'pension_entities.id')
               ->leftjoin('loan_destinies','loans.destiny_id', '=', 'loan_destinies.id')
               ->whereNull('loans.deleted_at')
               ->where($conditions)
               ->select('loans.id as id_loan','loans.code as code_loan','affiliates.id as id_affiliate','affiliates.identity_card as identity_card_affiliate',
               'affiliates.registration as registration_affiliate','affiliates.last_name as last_name_affiliate','affiliates.mothers_last_name as mothers_last_name_affiliate',
               'affiliates.first_name as first_name_affiliate','affiliates.second_name as second_name_affiliate','affiliates.surname_husband as surname_husband_affiliate',
               'procedure_modalities.name as sub_modality_loan','procedure_types.second_name as modality_loan','loans.amount_approved as amount_approved_loan',
               'affiliate_state_types.name as state_type_affiliate','affiliate_states.name as state_affiliate','loan_affiliates.quota_treat as quota_loan','loan_states.name as state_loan',
               'loan_affiliates.guarantor as guarantor_loan_affiliate','pension_entities.name as pension_entity_affiliate','loans.disbursement_date as disbursement_date_loan',
               'loans.request_date as request_date_loan','cities.name as name_city','spouses.registration as registration_spouse','loans.num_accounting_voucher as loan_accounting',
               'loans.parent_reason as parent_reason_loan','loans.amount_approved as amount_disbursement',DB::raw("(loans.amount_approved - loans.refinancing_balance) as amount_disbursement_liquido"),
               'loans.loan_term as term_loan','loan_destinies.name as name_destinity_loan')
               //->where('affiliates.identity_card','LIKE'.'%'.$request->identity_card.'%')
               ->orderBy('loans.code', $order_loan)
               ->get();
 
               foreach ($list_loan as $loan) {
                 $padron = Loan::where('id', $loan->id_loan)->first();
                 $loan->balance_loan=$padron->balance;
               }
               $File="ListadoPrestamosDesembolsados";
               $data=array(
                   array( "NRO DE PRÉSTAMO", "FECHA DE SOLICITUD", "FECHA DESEMBOLSO",
                   "REGIONAL","TIPO","PRODUCTO",
                   "CEDULA DE IDENTIDAD","MATRICULA","MATRICULA CÓNYUGUE",
                   "PRIMER NOMBRE","SEGUNDO NOMBRE","PATERNO","MATERNO","APELLIDO CASADA",
                   "NRO CBTE CONTABLE","SALDO ACTUAL","AMPLIACIÓN","MONTO DESEMBOLSADO","LIQUIDO DESEMBOLSADO",
                   "PLAZO","ESTÁDO PRÉSTAMO","DESTINO CREDITO" )
               );
               foreach ($list_loan as $row){
                   array_push($data, array(
                      // $row->id_loan,
                       $row->code_loan,//NRO DE PRESTAMO
                       $row->request_date_loan,
                       $row->disbursement_date_loan,
                       $row->name_city,
                       $row->state_type_affiliate,
                       $row->modality_loan,
                       $row->identity_card_affiliate,
                       $row->registration_affiliate,//matrifcula 
                       $row->registration_spouse, //matricula esposa

                       $row->first_name_affiliate,
                       $row->second_name_affiliate,
                       $row->last_name_affiliate,
                       $row->mothers_last_name_affiliate,
                       $row->surname_husband_affiliate,

                       $row->loan_accounting,
                       $row->balance_loan,
                       $row->parent_reason_loan,//ampliacion
                       $row->amount_disbursement,//monto desembolsado
                       $row->amount_disbursement_liquido,//liquido desembolsado
                       $row->term_loan,//plazo
                       $row->state_loan,//estado del prestamo

                       $row->name_destinity_loan
                   ));
               }
               $export = new ArchivoPrimarioExport($data);
               return Excel::download($export, $File.'.csv');
   }

   /** @group Reportes de Prestamos
   * Reporte de prestamos del estado de cartera 
   * Reporte de prestamos del estado de cartera (vigente - cancelado)
   * @queryParam initial_date Fecha inicial. Example: 2021-01-01
   * @queryParam final_date Fecha Final. Example: 2021-05-01
   * @authenticated
   * @responseFile responses/report_loans/loan_desembolsado.200.json
   */

  public function report_loan_state_cartera(Request $request){
    // aumenta el tiempo máximo de ejecución de este script a 150 min:
    ini_set('max_execution_time', 9000);
    // aumentar el tamaño de memoria permitido de este script:
    ini_set('memory_limit', '960M');

    $order_loan = 'Desc';
    $initial_date = request('initial_date') ?? '';
    $final_date = request('final_date') ?? '';
    $state_vigente='Vigente';
    $state_liquidado='Liquidado';
    $conditions = [];
    $conditions_liq = [];
    if ($initial_date != '') {
      array_push($conditions, array('loans.disbursement_date', '>=', "%{$initial_date}%"));
      array_push($conditions_liq, array('loans.disbursement_date', '>=', "%{$initial_date}%"));
    }
    if ($final_date != '') {
      array_push($conditions, array('loans.disbursement_date', '<=', "%{$final_date}%"));
      array_push($conditions_liq, array('loans.disbursement_date', '<=', "%{$final_date}%"));
    }
    
    array_push($conditions, array('loan_states.name', 'ilike', "%{$state_vigente}%"));
    array_push($conditions_liq, array('loan_states.name', 'ilike', "%{$state_liquidado}%"));

       $list_loan = DB::table('loans')
               ->join('procedure_modalities','loans.procedure_modality_id', '=', 'procedure_modalities.id')
               ->join('procedure_types','procedure_modalities.procedure_type_id', '=', 'procedure_types.id')
               ->join('loan_states','loans.state_id', '=', 'loan_states.id')
               ->join('cities','loans.city_id', '=', 'cities.id')
               ->join('loan_affiliates','loans.id', '=', 'loan_affiliates.loan_id')
               ->join('affiliates','loan_affiliates.affiliate_id', '=', 'affiliates.id')
               ->leftjoin('spouses','affiliates.id', '=', 'spouses.affiliate_id')
               ->join('affiliate_states','affiliates.affiliate_state_id', '=', 'affiliate_states.id')
               ->join('affiliate_state_types','affiliate_states.affiliate_state_type_id', '=', 'affiliate_state_types.id')
               ->leftjoin('pension_entities','affiliates.pension_entity_id', '=', 'pension_entities.id')
               ->leftjoin('loan_destinies','loans.destiny_id', '=', 'loan_destinies.id')
               ->whereNull('loans.deleted_at')
               ->where($conditions)
               ->select('loans.id as id_loan','loans.code as code_loan','affiliates.id as id_affiliate','affiliates.identity_card as identity_card_affiliate',
               'affiliates.registration as registration_affiliate','affiliates.last_name as last_name_affiliate','affiliates.mothers_last_name as mothers_last_name_affiliate',
               'affiliates.first_name as first_name_affiliate','affiliates.second_name as second_name_affiliate','affiliates.surname_husband as surname_husband_affiliate',
               'procedure_modalities.name as sub_modality_loan','procedure_types.second_name as modality_loan','loans.amount_approved as amount_approved_loan',
               'affiliate_state_types.name as state_type_affiliate','affiliate_states.name as state_affiliate','loan_affiliates.quota_treat as quota_loan','loan_states.name as state_loan',
               'loan_affiliates.guarantor as guarantor_loan_affiliate','pension_entities.name as pension_entity_affiliate','loans.disbursement_date as disbursement_date_loan',
               'loans.request_date as request_date_loan','cities.name as name_city','spouses.registration as registration_spouse','loans.num_accounting_voucher as loan_accounting',
               'loans.parent_reason as parent_reason_loan','loans.amount_approved as amount_disbursement',DB::raw("(loans.amount_approved - loans.refinancing_balance) as amount_disbursement_liquido"),
               'loans.loan_term as term_loan','loan_destinies.name as name_destinity_loan')
               //->where('affiliates.identity_card','LIKE'.'%'.$request->identity_card.'%')
               ->orderBy('loans.code', $order_loan)
               ->get();
 
               foreach ($list_loan as $loan) {
                 $padron = Loan::where('id', $loan->id_loan)->first();
                 $loan->balance_loan=$padron->balance;
                 //$loan->record = $padron->records;
               }
               //return $list_loan;
               $File="ListadoPrestamosDesembolsados";
               $data=array(
                   array( "NRO DE PRÉSTAMO", "FECHA DE SOLICITUD", "FECHA DESEMBOLSO",
                   "REGIONAL","TIPO","PRODUCTO",
                   "CEDULA DE IDENTIDAD","MATRICULA","MATRICULA CÓNYUGUE",
                   "PRIMER NOMBRE","SEGUNDO NOMBRE","PATERNO","MATERNO","APELLIDO CASADA",
                   "NRO CBTE CONTABLE","SALDO ACTUAL","AMPLIACIÓN","MONTO DESEMBOLSADO","LIQUIDO DESEMBOLSADO",
                   "PLAZO","ESTÁDO PRÉSTAMO","DESTINO CREDITO" )
               );
               foreach ($list_loan as $row){
                   array_push($data, array(
                      // $row->id_loan,
                       $row->code_loan,//NRO DE PRESTAMO
                       $row->request_date_loan,
                       $row->disbursement_date_loan,
                       $row->name_city,
                       $row->state_type_affiliate,
                       $row->modality_loan,
                       $row->identity_card_affiliate,
                       $row->registration_affiliate,//matrifcula 
                       $row->registration_spouse, //matricula esposa

                       $row->first_name_affiliate,
                       $row->second_name_affiliate,
                       $row->last_name_affiliate,
                       $row->mothers_last_name_affiliate,
                       $row->surname_husband_affiliate,

                       $row->loan_accounting,
                       $row->balance_loan,
                       $row->parent_reason_loan,//ampliacion
                       $row->amount_disbursement,//monto desembolsado
                       $row->amount_disbursement_liquido,//liquido desembolsado
                       $row->term_loan,//plazo
                       $row->state_loan,//estado del prestamo

                       $row->name_destinity_loan
                   ));
               }

               //liquidacion
               $list_loan_liq = DB::table('loans')
               ->join('procedure_modalities','loans.procedure_modality_id', '=', 'procedure_modalities.id')
               ->join('procedure_types','procedure_modalities.procedure_type_id', '=', 'procedure_types.id')
               ->join('loan_states','loans.state_id', '=', 'loan_states.id')
               ->join('cities','loans.city_id', '=', 'cities.id')
               ->join('loan_affiliates','loans.id', '=', 'loan_affiliates.loan_id')
               ->join('affiliates','loan_affiliates.affiliate_id', '=', 'affiliates.id')
               ->leftjoin('spouses','affiliates.id', '=', 'spouses.affiliate_id')
               ->join('affiliate_states','affiliates.affiliate_state_id', '=', 'affiliate_states.id')
               ->join('affiliate_state_types','affiliate_states.affiliate_state_type_id', '=', 'affiliate_state_types.id')
               ->leftjoin('pension_entities','affiliates.pension_entity_id', '=', 'pension_entities.id')
               ->leftjoin('loan_destinies','loans.destiny_id', '=', 'loan_destinies.id')
               ->whereNull('loans.deleted_at')
               ->where($conditions_liq)
               ->select('loans.id as id_loan','loans.code as code_loan','affiliates.id as id_affiliate','affiliates.identity_card as identity_card_affiliate',
               'affiliates.registration as registration_affiliate','affiliates.last_name as last_name_affiliate','affiliates.mothers_last_name as mothers_last_name_affiliate',
               'affiliates.first_name as first_name_affiliate','affiliates.second_name as second_name_affiliate','affiliates.surname_husband as surname_husband_affiliate',
               'procedure_modalities.name as sub_modality_loan','procedure_types.second_name as modality_loan','loans.amount_approved as amount_approved_loan',
               'affiliate_state_types.name as state_type_affiliate','affiliate_states.name as state_affiliate','loan_affiliates.quota_treat as quota_loan','loan_states.name as state_loan',
               'loan_affiliates.guarantor as guarantor_loan_affiliate','pension_entities.name as pension_entity_affiliate','loans.disbursement_date as disbursement_date_loan',
               'loans.request_date as request_date_loan','cities.name as name_city','spouses.registration as registration_spouse','loans.num_accounting_voucher as loan_accounting',
               'loans.parent_reason as parent_reason_loan','loans.amount_approved as amount_disbursement',DB::raw("(loans.amount_approved - loans.refinancing_balance) as amount_disbursement_liquido"),
               'loans.loan_term as term_loan','loan_destinies.name as name_destinity_loan')
               //->where('affiliates.identity_card','LIKE'.'%'.$request->identity_card.'%')
               ->orderBy('loans.code', $order_loan)
               ->get();
 
               foreach ($list_loan_liq as $loan) {
                 $padron = Loan::where('id', $loan->id_loan)->first();
                 $loan->balance_loan=$padron->balance;
                 $loan->record = $padron->record;
               }
               //return $list_loan_liq;
               $File="ListadoPrestamosVigenteLiquidado";
               $data_liq=array(
                   array( "NRO DE PRÉSTAMO", "FECHA DE SOLICITUD", "FECHA DESEMBOLSO",
                   "REGIONAL","TIPO","PRODUCTO",
                   "CEDULA DE IDENTIDAD","MATRICULA","MATRICULA CÓNYUGUE",
                   "PRIMER NOMBRE","SEGUNDO NOMBRE","PATERNO","MATERNO","APELLIDO CASADA",
                   "NRO CBTE CONTABLE","SALDO ACTUAL","AMPLIACIÓN","MONTO DESEMBOLSADO","LIQUIDO DESEMBOLSADO",
                   "PLAZO","ESTÁDO PRÉSTAMO","DESTINO CREDITO" )
               );
               foreach ($list_loan_liq as $row){
                   array_push($data_liq, array(
                      // $row->id_loan,
                       $row->code_loan,//NRO DE PRESTAMO
                       $row->request_date_loan,
                       $row->disbursement_date_loan,
                       $row->name_city,
                       $row->state_type_affiliate,
                       $row->modality_loan,
                       $row->identity_card_affiliate,
                       $row->registration_affiliate,//matrifcula 
                       $row->registration_spouse, //matricula esposa

                       $row->first_name_affiliate,
                       $row->second_name_affiliate,
                       $row->last_name_affiliate,
                       $row->mothers_last_name_affiliate,
                       $row->surname_husband_affiliate,

                       $row->loan_accounting,
                       $row->balance_loan,
                       $row->parent_reason_loan,//ampliacion
                       $row->amount_disbursement,//monto desembolsado
                       $row->amount_disbursement_liquido,//liquido desembolsado
                       $row->term_loan,//plazo
                       $row->state_loan,//estado del prestamo

                       $row->name_destinity_loan
                   ));
               }

               $export = new MultipleSheetExportPayment($data, $data_liq,'PRE-VIGENTE','PRE-LIQUIDADO');
               return Excel::download($export, $File.'.xlsx');
   }

   /** @group Reportes de Prestamos
   * Reporte de prestamos en mora 
   * Reporte de prestamos prestamos en mora PARCIAL TOTAL MORA
   * @authenticated
   * @responseFile responses/report_loans/loan_desembolsado.200.json
   */

  public function report_loans_mora(Request $request){
    $state = LoanState::whereName('Vigente')->first();

    $loans=Loan::where('state_id',$state->id)->get();
    $loans_mora_total = collect();
    $loans_mora_parcial = collect();
    $loans_mora = collect();

    //mora
    foreach($loans as $loan){
      if($loan->defaulted && count($loan->payments) > 0){
        $loan->guarantor = $loan->guarantor;
        $loan->lenders = $loan->lenders;
        $loan->personal_references=$loan->personal_references;
        $loans_mora->push($loan);
      }
    }

    //mora total
    foreach($loans as $loan){
      if($loan->defaulted && count($loan->payments)== 0){
        $loan->guarantor = $loan->guarantors;
        $loan->lenders = $loan->lenders;
        $loan->personal_references=$loan->personal_references;

        $loans_mora_total->push($loan);
      }
    }

    //mora parcial
    foreach($loans as $loan){
      if($loan->getdelay_parcial() && !$loan->defaulted ){
        $loan->guarantor = $loan->guarantors;
        $loan->lenders = $loan->lenders;
        $loan->personal_references=$loan->personal_references;

        $loans_mora_parcial->push($loan);
      }
    }

    $File="PrestamosMora";
        $data=array(
            array("MATRICULA","CI","NOMBRE COMPLETO","NRO DE CEL","NRO DE CEL.2","PTMO","FECHA DESEMBOLSO",
            "NRO DE CUOTAS","TAZA ANUAL","CUOTA MENSUAL","SALDO","TIPO","PRODUCTO","TIEMPO MORA","NOM. PERSONAL REFERENCE","DIRECCIÓN")
        );
        foreach ($loans_mora_total as $row){
            array_push($data, array(
               $row->lenders[0]->affiliate_registration_number,
               $row->lenders[0]->identity_card,
              // $row->lenders[0],
               $row->lenders[0]->first_name,
               $row->lenders[0]->last_name,
               $row->lenders[0]->phone_number,
               $row->lenders[0]->cell_phone_number,
               $row->code,
               $row->disbursement_date,
               $row->loan_term,
               $row->estimated_quota,
               $row->balance,
               $row->lenders[0]->affiliate_state->affiliate_state_type->name,
               $row->modality->procedure_type->second_name,

               $row->getdelay()->penal,
              // $row->getdelay()->interest_accumulated,
               
               $row->personal_references[0]->last_name,
               $row->personal_references[0]->address,
            ));
        }
        //prestamomora parcial
        $File="PrestamosMoraParcial";
        $data_mora_parcial=array(
            array("MATRICULA","CI","NOMBRE COMPLETO","NRO DE CEL","NRO DE CEL.2","PTMO","FECHA DESEMBOLSO",
            "NRO DE CUOTAS","TAZA ANUAL","CUOTA MENSUAL","SALDO","TIPO","PRODUCTO","TIEMPO MORA","NOM. PERSONAL REFERENCE","DIRECCIÓN")
        );
        foreach ($loans_mora_parcial as $row){
            array_push($data_mora_parcial, array(
               $row->lenders[0]->affiliate_registration_number,
               $row->lenders[0]->identity_card,
              // $row->lenders[0],
               $row->lenders[0]->first_name,
               $row->lenders[0]->last_name,
               $row->lenders[0]->phone_number,
               $row->lenders[0]->cell_phone_number,
               $row->code,
               $row->disbursement_date,
               $row->loan_term,
               $row->estimated_quota,
               $row->balance,
               $row->lenders[0]->affiliate_state->affiliate_state_type->name,
               $row->modality->procedure_type->second_name,

               $row->getdelay()->penal,
              // $row->getdelay()->interest_accumulated,
               
               $row->personal_references[0]->last_name,
               $row->personal_references[0]->address,
            ));
        }
        //prestamomora 
        $File="PrestamosMora";
        $data_mora=array(
            array("MATRICULA","CI","NOMBRE COMPLETO","NRO DE CEL","NRO DE CEL.2","PTMO","FECHA DESEMBOLSO",
            "NRO DE CUOTAS","TAZA ANUAL","CUOTA MENSUAL","SALDO","TIPO","PRODUCTO","TIEMPO MORA","NOM. PERSONAL REFERENCE","DIRECCIÓN")
        );
        foreach ($loans_mora as $row){
            array_push($data_mora, array(
               $row->lenders[0]->affiliate_registration_number,
               $row->lenders[0]->identity_card,
              // $row->lenders[0],
               $row->lenders[0]->first_name,
               $row->lenders[0]->last_name,
               $row->lenders[0]->phone_number,
               $row->lenders[0]->cell_phone_number,
               $row->code,
               $row->disbursement_date,
               $row->loan_term,
               $row->estimated_quota,
               $row->balance,
               $row->lenders[0]->affiliate_state->affiliate_state_type->name,
               $row->modality->procedure_type->second_name,

               $row->getdelay()->penal,
              // $row->getdelay()->interest_accumulated,
               
               $row->personal_references[0]->last_name,
               $row->personal_references[0]->address,
            ));
        }

        //$export = new MultipleSheetExportPaymentMora($data,$data_mora_parcial,$data_mora,'MORA TOTAL','MORA PARCIAL','MORA');
        $export = new MultipleSheetExportPaymentMora($data,$data_mora_parcial,$data_mora);
        return Excel::download($export, $File.'.xlsx');
  }

}
