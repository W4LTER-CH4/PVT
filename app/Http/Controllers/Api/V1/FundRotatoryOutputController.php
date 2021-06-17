<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use App\FundRotatoryOutput;
use App\Affiliate;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Util;
use Carbon\CarbonImmutable;
use Carbon;
use App\Loan;
use App\Http\Requests\FundRotatoryOutputForm;

/** @group Salida del fondo rotatorio
* Datos del fondo rotatorio 
*/
class FundRotatoryOutputController extends Controller
{
    public static function append_data(FundRotatoryOutput $outputsFundRotatorie)
    {
        $outputsFundRotatorie->loan = $outputsFundRotatorie->loan;
        //$outputsFundRotatorie->fund_rotary_entry = $outputsFundRotatorie->fund_rotary_entry;
        return $outputsFundRotatorie;
    }


    /**
    * Lista de Registro de salidas fondo rotatorio
    * Devuelve el listado con los datos paginados
    * @queryParam role_id integer Ver fondos del rol, si es 0 se muestra la lista completa. Example: 73
    * @queryParam loan_id integer ID del tramite de préstamo. Example 1
    * @queryParam user_id integer ID del usuario de préstamo. Example 1
    * @queryParam search Parámetro de búsqueda. Example: 2000
    * @queryParam sortBy Vector de ordenamiento. Example: []
    * @queryParam sortDesc Vector de orden descendente(true) o ascendente(false). Example: [true]
    * @queryParam per_page Número de datos por página. Example: 8
    * @queryParam page Número de página. Example: 1
    * @authenticated
    * @responseFile responses/outputs_fund_rotatorie/index.200.json
     */
    public function index(Request $request){
        $filters = [];
        $relations = [];
        if (!$request->has('role_id')) {
            if (Auth::user()->can('show-all-loan') || Auth::user()->can('show-all-payment-loan')) {
                $request->role_id = 0;
            } else {
                $role = Auth::user()->roles()->whereHas('module', function($query) {
                    return $query->whereName('prestamos');
                })->orderBy('name')->first();
                if ($role) {
                    $request->role_id = $role->id;
                } else {
                    abort(403);
                }
            }
        } else {
            $request->role_id = (integer)$request->role_id;
            if (($request->role_id == 0 && !Auth::user()->can('show-all-loan') && !Auth::user()->can('show-all-payment-loan')) || ($request->role_id != 0 && !Auth::user()->roles->pluck('id')->contains($request->role_id))) {
                abort(403);
            }
        }
        if ($request->role_id != 0) {
            $filters = [
                'role_id' => $request->role_id
            ];
        }
        if ($request->has('loan_id')) {
            $relations['loan'] = [
                'loan_id' => $request->loan_id
            ];
        }
        if ($request->has('user_id')) {
            $relations['user'] = [
                'user_id' => $request->user_id
            ];
        }
        $data = Util::search_sort(new FundRotatoryOutput(), $request, $filters, $relations);
        $data->getCollection()->transform(function ($outputsFund) {
            return self::append_data($outputsFund, true);
        });
        return $data;
    }

    /**
    * Detalle de salida de un fondo rotatorio
    * Devuelve el detalle de un registro de fondo rotatorio mediante su ID
    * @urlParam output_fund_rotatorie required ID de registro de pago. Example: 1
    * @authenticated
    * @responseFile responses/outputs_fund_rotatorie/show.200.json
    */
    public function show($FundRotatoryOutput)
    {
        return $this::append_data(FundRotatoryOutput::findOrFail($FundRotatoryOutput));
        //return self::append_data($FundRotatoryOutput);
    }

    /**
    * Edita el Registro del fondo rotatorio.
    * @urlParam id required ID del registro realizado. Example: 1
	* @bodyParam description string Texto de descripción. Example: pago de anticipo
    * @bodyParam loan_id integer numero del prestamo perteneciente al fondo rotatorio. Example: 1
    * @bodyParam fund_rotary_entry_id integer ID del fondo rotatorio ingresado. Example: 1
    * @bodyParam user_id integer ID del usuario que registro. Example: 70
    * @bodyParam role_id integer role con el que el registro fue creado. Example: 90
    * @authenticated
    * @responseFile responses/outputs_fund_rotatorie/update.200.json
    */
    public function update(FundRotatoryOutputForm $request,$fundRotatory_id)
    {
        $fundRotatory = FundRotatoryOutput::findOrFail($fundRotatory_id);
        $fundRotatory->fill($request->all());
        $fundRotatory->save();
        return  $fundRotatory;
    }

    /**
    * Anular Registro de fondo
    * @urlParam outputs_fund_rotatories required ID del registro. Example: 1
    * @authenticated
    * @responseFile responses/outputs_fund_rotatorie/destroy.200.json
    */
    public function destroy($fundRotatory_id)
    {
        $fundRotatory = FundRotatoryOutput::findOrFail($fundRotatory_id);
        $fundRotatory->delete();
        return $fundRotatory;
    }


    /**
    * Nuevo registro de fondo rotatorio
    * Inserta el registro del fondo rotatorio
    * @bodyParam description string Texto de descripción. Example: pago de anticipo
    * @bodyParam loan_id integer numero del prestamo perteneciente al fondo rotatorio. Example: 1
    * @bodyParam fund_rotary_entry_id integer ID del condo de retiro ingresado. Example: 1
    * @bodyParam user_id integer ID del usuario que registro. Example: 70
    * @bodyParam role_id integer role con el que el registro fue creado. Example: 90
    * @authenticated
    * @responseFile responses/outputs_fund_rotatorie/store.200.json
    */
    public function store(FundRotatoryOutputForm $request)
    {
        return FundRotatoryOutput::create($request->all());
    }

    /**
    * Impresión del Registro de fondo rotatorio
    * Devuelve un pdf del Pago acorde a un ID de registro de fondo rotatorio
    * @urlParam outputs_fund_rotatorie required ID del pago. Example: 1
    * @queryParam copies Número de copias del documento. Example: 2
    * @authenticated
    * @responseFile responses/outputs_fund_rotatorie/print_fund_rotatorie.200.json
    */
    public function print_fund_rotary(Request $request,$loan_id, $standalone = true)
    {   $ouputs_fund_rotatorie = FundRotatoryOutput::whereLoanId($loan_id)->first();
        $loan = Loan::findOrFail($ouputs_fund_rotatorie->loan_id);   
        $affiliate = Affiliate::findOrFail($loan->disbursable_id);
        $lenders = [];
        $lenders[] = LoanController::verify_spouse_disbursable($affiliate)->disbursable;
        $persons = collect([]);  
        $persons->push([
            'id' => $affiliate->id,
            'full_name' => implode(' ', [$affiliate->title, $affiliate->full_name]),
            'identity_card' => $affiliate->identity_card_ext,
            'position' => 'RECIBIDIDO POR'
        ]);      
        $data = [
            'header' => [
                'direction' => 'DIRECCIÓN DE ESTRATEGIAS SOCIALES E INVERSIONES',
                'unity' => 'UNIDAD DE INVERSIÓN EN PRÉSTAMOS',
                'table' => [
                    ['Código', $ouputs_fund_rotatorie->code],
                    ['Fecha', Carbon::now()->format('d/m/Y')],
                    ['Hora', Carbon::now()->format('h:m:s a')],
                    ['Usuario', Auth::user()->username]
                ]
            ],
            'title' => 'RECIBO DE PAGO',
            'ouputs_fund_rotatorie' => $ouputs_fund_rotatorie,
            'loan' => $loan,
            'signers' => $persons,
            'lenders' => collect($lenders)
        ];
        $information = $this->get_information_loan($ouputs_fund_rotatorie);
        $file_name = implode('_', ['ouputs_fund_rotatorie', $ouputs_fund_rotatorie->code]) . '.pdf';
        $view = view()->make('loan.forms.disbursement_receipt_form')->with($data)->render();
        if ($standalone) return Util::pdf_to_base64([$view], $file_name, $information, 'letter', $request->copies ?? 1);
        return $view; 
    }

    public function get_information_loan(FundRotatoryOutput $ouputs_fund_rotatorie)
    {
        $file_name='';
            $loan = Loan::findOrFail($ouputs_fund_rotatorie->loan_id);
            $lend='';
            foreach ($loan->lenders as $lender) {
                $lenders[] = LoanController::verify_spouse_disbursable($lender);
            }
            foreach ($loan->lenders as $lender) {
                $lend = $lend.'*'.' ' . $lender->first_name .' '. $lender->second_name .' '. $lender->last_name.' '. $lender->mothers_last_name;
            }
            
            $loan_affiliates= $loan->loan_affiliates[0]->first_name;
            $file_name =implode(' ', ['Información:',$ouputs_fund_rotatorie->code,$loan->code,$loan->modality->name,$lend]);
        return $file_name;
    }
}
