<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ArchivoPrimarioExport;
use Carbon;
use Illuminate\Support\Facades\Log;
use App\Period;

/** @group Importacion de datos C o S
* Importacion de datos Comando  o Senasir
*/

class ImportationController extends Controller
{

   /**
    * Agrupar montos de afiliados
    * Agrupar montos de afiliadoso
    * @queryParam origin required Tipo de importacion C (Comando general) o S (Senasir). Example: C
    * @queryParam period required id_del periodo . Example: 1
    * @authenticated
    * @responseFile responses/importation/importation_agroup.200.json
     */

 public function agruped_payments(Request $request){
    $request->validate([
        'origin'=>'required|string',
        'period'=>'required|exists:periods,id'
    ]);

    DB::beginTransaction();
    try {

    $data = array();
    $count_affiliate = 0;
    $count_no_exist = 0;
    $validado = false;
    $origin = $request->origin; $period = $request->period;//entradas
        if($origin == 'C'){
            $query = "  SELECT import_command_payments.identity_card as identity_card, sum(amount) as amount
                        FROM import_command_payments
                        where import_command_payments.period_id = '$period'
                        group by import_command_payments.identity_card";

            $payment_agroups = DB::select($query);

            foreach($payment_agroups as $payment_agroup){
                $affiliate_id = $this->serch_id($payment_agroup->identity_card);
                if($affiliate_id != 0){
                    DB::table("command_payment_groups")
                    ->insert([
                      "affiliate_id" => $affiliate_id,
                      "period_id" => $period,
                      "identity_card" =>$payment_agroup->identity_card,
                      "amount" => $payment_agroup->amount,
                      "amount_balance" => $payment_agroup->amount,
                      "created_at" =>Carbon::now(),
                      "updated_at" =>Carbon::now(),
                    ]);
                   Log::info('Registro agrupado de affiliado con Id: '.$affiliate_id);
                  $count_affiliate++;
                }else{

                    $data_loan =  $payment_agroup;
                    array_push($data, $data_loan);
                    $count_no_exist++;
                }
             }

        }else{
            if($origin == 'S'){

            $query = "  SELECT import_senasir_payments.registration as registration, import_senasir_payments.registration_dh as registration_dh, sum(amount) as amount
                        FROM import_senasir_payments
                        where import_senasir_payments.period_id = '$period'
                        group by import_senasir_payments.registration, import_senasir_payments.registration_dh";

            $payment_agroups = DB::select($query);

            foreach($payment_agroups as $payment_agroup){
                $affiliate_id = $this->serch_id($payment_agroup->registration);
                if($affiliate_id != 0){
                    DB::table("senasir_payment_groups")
                    ->insert([
                      "affiliate_id" => $affiliate_id,
                      "period_id" => $period,
                      "registration" =>$payment_agroup->registration,
                      "registration_dh" =>$payment_agroup->registration_dh,
                      "amount" => $payment_agroup->amount,
                      "amount_balance" => $payment_agroup->amount,
                      "created_at" =>Carbon::now(),
                      "updated_at" =>Carbon::now(),
                    ]);
                  $count_affiliate++;

                }else{
                    $data_loan =  $payment_agroup;
                    array_push($data, $data_loan);
                    $count_no_exist++;
                }
            }

            }else{
                abort(409, 'Incorrecto! Debe enviar C(Comando General) ó S(Senasir)');
            }
        }

        //verificar exixtencia de afiliados
            if($count_no_exist > 0){
                DB::rollback();
                $delete_copy = $this->delete_copy_payments($period,$origin);
                Log::info('Cantidad de registros no existentes: '.$count_no_exist);


               $data_cabeceraC=array(array("NRO de CARNET", "MONTO TOTAL"));
               $data_cabeceraS=array(array("MATRÍCULA", "MATRÍCULA D_H", "MONTO TOTAL"));

               foreach ($data as $row){
                   if($origin == 'C'){
                        array_push($data_cabeceraC, array($row->identity_card, $row->amount));
                   }else{
                        array_push($data_cabeceraS, array($row->registration, $row->registration_dh, $row->amount));
                   }
               }
               $last_period = Period::find($period);
               $last_date = Carbon::parse($last_period->year.'-'.$last_period->month)->toDateString();
               if($origin == 'C'){
                $export = new ArchivoPrimarioExport($data_cabeceraC);
                $file_name = $origin.'_'.$last_date.'.xls';
                $base_path = 'errorValidacion_Command/'.'Command_'.$last_date;
                Excel::store($export,$base_path.'/'.$file_name, 'ftp');
               }else{
                $export = new ArchivoPrimarioExport($data_cabeceraS);
                $file_name = $origin.'_'.$last_date.'.xls';
                $base_path = 'errorValidacion_Senasir/'.'Senasir_'.$last_date;
                Excel::store($export,$base_path.'/'.$file_name, 'ftp');
               }
               $count_affiliate = 0;
                return  response()->json(['message' =>'Validación de datos incorrecto!','validated_agroup'=>$validado,'count_affilites'=>$count_affiliate]);
            }else{
                if($count_affiliate > 0){
                    $validado =true;
                    DB::commit();
                    return  response()->json(['message' =>'Validación de datos realizado con exito!','validated_agroup'=>$validado,'count_affilites'=>$count_affiliate]);

                }else{
                    //DB::commit();
                    $delete_copy = $this->delete_copy_payments($period,$origin);
                    return  response()->json(['message' =>'Validación de datos incorrecto! no se encontraron datos por agrupar','validated_agroup'=>$validado,'count_affilites'=>$count_affiliate]);
                }
            }
        } catch (\Exception $e) {
            DB::rollback();
           //throw $e;
           return ['message' => $e->getMessage()];
        }
   }

   //buscar id y ci
   public function serch_id($ci){
       //$ci= '1700723';
       $ci=$ci;

        $query = "  SELECT affiliates.id as id
                    FROM affiliates
                    where affiliates.identity_card = '$ci'
                    or affiliates.registration = '$ci'";

        $query_spouse = "  SELECT spouses.affiliate_id as affiliate_id
                    FROM spouses
                    where spouses.identity_card = '$ci'
                    or spouses.registration = '$ci'";
        $affiliate =DB::select($query);
        if($affiliate){
            return $affiliate[0]->id;
        }
        else{
            $spouse = DB::select($query_spouse);
            if($spouse){
                return $spouse[0]->affiliate_id;
            }else
                return 0; //no existe el ci
        }
    }

     //importacion de cobros 
     public function importation_payment(Request $request){
        $origin = 'C';$period =1;
        $query = "  SELECT *
                    FROM payment_groupeds
                    where payment_groupeds.origin = '$origin'
                    and payment_groupeds.period_id = '$period'";

       $payment_agroups = DB::select($query);
       foreach($payment_agroups as $payment_agroup){
        return $payment_agroup->id;
       }
        return $payment_agroups;
    }

    //prestamos por  afiliado
    public function loan_lenders($id_affiliate){

        $query = " SELECT loans.*
                    FROM loans
                    join loan_affiliates ON loan_affiliates.loan_id = loans.id
                    join affiliates ON affiliates.id = loan_affiliates.affiliate_id
                    join loan_states ON loan_states.id = loans.state_id
                    where loan_affiliates.affiliate_id = '$id_affiliate'
                    and  loan_affiliates.guarantor = false
                    and  loan_states.name = 'Vigente'
                    and  loans.guarantor_amortizing = false
                    order by loans.disbursement_date desc";

        $loan_lenders = DB::select($query);
        return $loan_lenders;
    }
    //prestamos por  guarantor
    public function loan_guarantors($id_affiliate){
        $query = " SELECT loans.*
                    FROM loans
                    join loan_affiliates ON loan_affiliates.loan_id = loans.id
                    join affiliates ON affiliates.id = loan_affiliates.affiliate_id
                    join loan_states ON loan_states.id = loans.state_id
                    where loan_affiliates.affiliate_id = '$id_affiliate'
                    and  loan_affiliates.guarantor = true
                    and  loan_states.name = 'Vigente'
                    and  loans.guarantor_amortizing = true
                    order by loans.disbursement_date desc";

       $loan_guarantors= DB::select($query);
        return $loan_guarantors;
    }

    public function copy_payments(request $request)
    {
        $file = Storage::disk('ftp')->get($request->location."/".$request->file_name);
        Storage::disk('public')->put($request->file_name, $file);
        //return Storage::disk('public')->path($request->file_name);
        //return Storage::disk('public')->getDriver()->getAdapter()->getPathPrefix();
        DB::beginTransaction();
        try{
            if(Period::whereId($request->period_id)->first()){
                $drop = "drop table if exists payments_aux";
                $drop = DB::select($drop);
                if($request->type == 'C'){
                    $temporary = "create temporary table payments_aux(period_id integer, identity_card varchar, amount float)";
                    $temporary = DB::select($temporary);

                    $copy = "copy payments_aux(identity_card, amount)
                            FROM '/home/richard/Documentos/trabajo/PVT/storage/app/public/".$request->file_name."'
                            WITH DELIMITER ':' CSV header;";
                    $copy = DB::select($copy);
                    Storage::disk('public')->delete($request->file_name);

                    $update = "update payments_aux
                                set period_id = $request->period_id";
                    $update = DB::select($update);

                    $update2 = "update payments_aux
                                set identity_card = REPLACE(LTRIM(REPLACE(identity_card,'0',' ')),' ','0')";
                    $update2 = DB::select($update2);

                    $insert = "INSERT INTO import_command_payments(period_id, identity_card, amount)
                                SELECT period_id, identity_card, amount FROM payments_aux;";
                    $insert = DB::select($insert);
                    DB::commit();

                    $drop = "drop table if exists payments_aux";
                    $drop = DB::select($drop);

                    $consult = "select count(*) from import_command_payments where period_id = $request->period_id";
                    $consult = DB::select($consult);
                    return $consult['count'];
                }
                else{
                    if($request->type == 'S'){
                        $temporary = "create temporary table payments_aux(period_id integer, registration varchar, registration_dh varchar, amount float)";
                        $temporary = DB::select($temporary);

                        $copy = "copy payments_aux(registration, registration_dh, amount)
                                FROM '/home/richard/Documentos/trabajo/PVT/storage/app/public/".$request->file_name."'
                                WITH DELIMITER ':' CSV header;";
                        $copy = DB::select($copy);
                        Storage::disk('public')->delete($request->file_name);

                        $update = "update payments_aux
                                    set period_id = $request->period_id";
                        $update = DB::select($update);

                        $insert = "INSERT INTO import_senasir_payments(period_id, registration, registration_dh, amount)
                                    SELECT period_id, registration, registration_dh, amount FROM payments_aux;";
                        $insert = DB::select($insert);
                        DB::commit();

                        $drop = "drop table if exists payments_aux";
                        $drop = DB::select($drop);

                        $consult = "select count(*) from import_senasir_payments where period_id = $request->period_id";
                        $consult = DB::select($consult);
                        return $consult;
                        
                    }
                    else{
                        return "tipo inexistente";
                    }
                }
            }else{
                return "periodo inexistente";
            }
        }catch(Exception $e){
            DB::rollback();
            return $e;
        }
    }

    public function delete_copy_payments($period, $origin)
    {
        DB::beginTransaction();
        try{
            if(Period::whereId($period)->first() && $origin == 'C' || Period::whereId($period)->first() && $origin == 'S')
            {
                $count = 0;
                if($origin == 'C'){
                    $query = "delete
                                from import_command_payments
                                where period_id = $period";
                    $query = DB::select($query);
                    DB::commit();
                    return true;
                }
                if($origin == 'S'){
                    $query = "delete
                                from import_senasir_payments
                                where period_id = $period";
                    $query = DB::select($query);
                    DB::commit();
                    return true;
                }
            }
            else
                return false;
        }
        catch (Exception $e)
        {
            DB::rollback();
            return $e;
        }
    }
    /**
    * Cargado del archivo csv de Pagos 
    * Realiza el copiado del archivo por ftp.
	* @bodyParam file file required Archivo de importación. Example: file.csv
    * @bodyParam state enum required Tipo importacion Comando(C) o Senasir(S). Example: 1
    * @authenticated
    * @responseFile responses/loan_payment/upload_file_payment.200.json
    */
    public function upload_file_payment(Request $request){
        $request->validate([
            'file' => 'required',
            'state'=> 'string|in:C,S',       
         ]);
        try {
            $extencion= strtolower($request->file->getClientOriginalExtension()); 
            if($extencion == "csv"){
                $result=[];
                $period_state =false;
                $last_period = Period::orderBy('id')->get()->last();
                $last_date = Carbon::parse($last_period->year.'-'.$last_period->month)->toDateString();
                if($request->state == "C"){
                    $origin = "comando_".$last_period->year;
                    $period_state = $last_period->import_command;
                }else{
                    $origin = "senasir_".$last_period->year;
                    $period_state = $last_period->import_senasir;
                }
                if($period_state == false){
                    $file_name = $last_date.'.csv';
                    $base_path = 'contribución/'.$origin;    
                    $file_path = Storage::disk('ftp')->putFileAs($base_path,$request->file,$file_name);
                    $request['period_id'] = $last_period->id;
                    $request['location'] = $base_path;
                    $request['type'] = $request->state;
                    $request['file_name'] = $file_name;
                    $result['message'] = $this->copy_payments($request);
                    return $result;
                }else{
                    $result['message'] = "No se puede ralizar el cargado del archivo ya que se realizo el registro de pago";  
                }
            }else {
                $result['message'] = "El tipo de archivo requerido es .csv";
            }
            return $result;
        }
        catch (\Exception $e) {
            return $e;
        }
    }

    /**
    * Descargar archivo de error validacion de C o S
    * Descargar error validacion de C o S
    * @queryParam origin required Tipo de importacion C (Comando general) o S (Senasir). Example: C
    * @queryParam period required id_del periodo . Example: 1
    * @authenticated
    * @responseFile responses/importation/validation_group.200.json
     */

    public function upload_fail_validated_group(Request $request){

        $request->validate([
            'origin'=>'required|string',
            'period'=>'required|exists:periods,id'
        ]);

        $origin=$request->origin;
        $last_period = Period::find($request->period);
        $last_date = Carbon::parse($last_period->year.'-'.$last_period->month)->toDateString();

        if($origin == 'C'){
            $file_name = $origin.'_'.$last_date.'.xls';
            $base_path = 'errorValidacion_Command/'.'Command_'.$last_date;
        }else{
            $file_name = $origin.'_'.$last_date.'.xls';
            $base_path = 'errorValidacion_Senasir/'.'Senasir_'.$last_date;
        }

        if(Storage::disk('ftp')->has($base_path.'/'.$file_name)){
            return $file = Storage::disk('ftp')->download($base_path.'/'.$file_name);
        }else{
            return abort(403, 'No existe archivo de falla para mostrar');
        }
    }

}
