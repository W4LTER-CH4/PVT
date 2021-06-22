<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Util;
use App\User;
use App\Affiliate;
use App\FundRotatory;
use App\FundRotatoryOutput;
use App\Http\Requests\FundRotatoryForm;
use Illuminate\Support\Facades\Auth;

/** @group Fondo Rotatorio Anticipos
* Fondo rotatorio para anticipos
*/

class FundRotatoryController extends Controller
{
    /**
    * Listar los Fondos rotatorios
    * Devuelve el listado de los fondos rotatorios de anticipos
    * @queryParam search Parámetro de búsqueda. Example: 2020
    * @queryParam sortBy Vector de ordenamiento. Example: []
    * @queryParam sortDesc Vector de orden descendente(true) o ascendente(false). Example: [false]
    * @queryParam per_page Número de datos por página. Example: 8
    * @queryParam page Número de página. Example: 1
    * @authenticated
    * @responseFile responses/fund_rotary_entry/index.200.json
     */
    public function index(Request $request)
    {
        return Util::search_sort(new FundRotatory(), $request);
    }

    /**
     * Nuevo registro de fondo Rotatorio
     * Inserta nuevo fondo rotatorio
     * @bodyParam check_number numeric required Numero del Cheque del fondo rotatorio. Example: 112
     * @bodyParam amount numeric required Monto de ingreso del fondo rotatoio. Example: 50000
     * @bodyParam date_check_delivery date required Fecha de ingreso del fondo o asignacion. Example: 2021-06-01
     * @bodyParam role_id numeric required Rol con el cual se realizo el registro. Example: 92
     * @bodyParam description string  Descripcion del registro de del fondo Rotatorio. Example: Ingres
     * @authenticated
     * @responseFile responses/fund_rotary_entry/store.200.json
     */
    public function store(FundRotatoryForm $request)
    {
        $fundRotatory = new FundRotatory;
        $fundRotatory->user_id = Auth::id();
        $code_entry = count(FundRotatory::all());
        $code_entry = $code_entry+1;
        $fundRotatory->code_entry ="FR-".$code_entry;
        $fundRotatory->check_number = $request->input('check_number');
        $fundRotatory->amount = $request->input('amount');
        $fundRotatory->date_check_delivery = $request->input('date_check_delivery');
        $fundRotatory->description = $request->input('description');
        $fundRotatory->role_id = $request->input('role_id');
        if($fundRotatory->last == null){
            $fundRotatory->balance_previous= 0;
            $fundRotatory->balance = $request->input('amount');
        }else{
            $balance_previous= $fundRotatory->last->balance;
            $fundRotatory->balance_previous = $balance_previous;
            $fundRotatory->balance = $request->input('amount')+$fundRotatory->last->balance;
        }
        return FundRotatory::create($fundRotatory->toArray());
    }

    /**
     * Detalle del fondo rotatorio
     * Devuelve el detalle de un fondo rotatorio
     * @urlParam id required ID del Fondo Rotatorio. Example: 1
     * @responseFile responses/fund_rotary_entry/show.200.json
     * @response
     */
    public function show($id)
    {
        $fundRotatory = FundRotatory::find($id);
        return $fundRotatory;
    }

    /**
     * Actualizar informacion del fondo rotatorio
     * Actualizar datos del fondo rotatorio
     * @urlParam fund_rotatory_id ID del fondo rotatorio. Example: 1
     * @bodyParam amount numeric Monto de ingreso del fondo rotatoio. Example: 50000
     * @bodyParam date_check_delivery date Fecha de ingreso del fondo o asignacion. Example: 2021/06/01
     * @bodyParam role_id numeric Rol con el cual se realizo el registro. Example: 92
     * @bodyParam balance numeric Saldo del fondo rotatorio. Example: 10000
     * @bodyParam balance_previous numeric Saldo del fondo rotatorio anterior. Example: 200
     * @authenticated
     * @responseFile responses/aid_contribution/update.200.json
     */
    public function update(FundRotatoryForm $request,$fundRotatory)
    {
        $fundRotatory = FundRotatory::find($fundRotatory);
        $fundRotatory->fill($request->all());
        $fundRotatory->save();
        return  $fundRotatory;
    }

    /**
     * Eliminar fondo rotatorio
     * Eliminar datos del fondo rotatorio
     * @urlParam fund_rotatory_id ID del fondo rotatorio. Example: 1
     * @authenticated
     * @responseFile responses/fund_rotary_entry/destroy.200.json
     */
    public function destroy($fundRotatory)
    {
        $fundRotatory = FundRotatory::find($fundRotatory);
        $fundRotatory->delete();
        return $fundRotatory;
    }
     /**
    * Listado de ingresos y salidas del fondo rotatorio
    * Devuelve un listado de fondo rotatorio y sus salidas
    * @queryParam per_page Número de datos por página. Example: 8
    * @queryParam page Número de página. Example: 1
    * @authenticated
    * @responseFile responses/fund_rotary_entry/get_fund_rotatori_entry_output.200.json
    */
    public function get_fund_rotatori_entry_output(Request $request)
    {  
        $pagination_rows = request('per_page') ?? 10;
        $fundRotatories =  FundRotatory::orderBy('id')->paginate($pagination_rows);    
        foreach($fundRotatories as $fundRotatory){
                $fundRotatory->fund_rotatory_outputs->sortBy('id');
            foreach($fundRotatory->fund_rotatory_outputs as $loan_outputs){ 
                $loan = $loan_outputs->loan;
                $loan->affiliate=Affiliate::find($loan->disbursable_id);
                $loan->procedure_type = $loan->modality->procedure_type;
            } 
        } 
        $fundRotatories = array('data'=>$fundRotatories);
        return $fundRotatories;
    }
}
