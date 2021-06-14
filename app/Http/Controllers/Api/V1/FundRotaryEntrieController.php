<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Util;
use App\User;
use App\FundRotaryEntry;
use App\Http\Requests\FundRotaryEntryForm;
use Illuminate\Support\Facades\Auth;

/** @group Fondo Rotatorio Anticipos
* Fondo rotatorio para anticipos
*/

class FundRotaryEntrieController extends Controller
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
        return Util::search_sort(new FundRotaryEntry(), $request);
    }

    /**
     * Nuevo registro de fondo Rotatorio
     * Inserta nuevo fondo rotatorio
     * @bodyParam code_entry string required Código del fondo rotatorio. Example: 06/2021
     * @bodyParam amount numeric required Monto de ingreso del fondo rotatoio. Example: 50000
     * @bodyParam date_entry_amount date required Fecha de ingreso del fondo o asignacion. Example: 2021/06/01
     * @bodyParam role_id numeric required Rol con el cual se realizo el registro. Example: 92
     * @authenticated
     * @responseFile responses/fund_rotary_entry/store.200.json
     */
    public function store(FundRotaryEntryForm $request)
    {
        $fundRotaryEntry = new FundRotaryEntry;
        $fundRotaryEntry->user_id = Auth::id();

        $fundRotaryEntry->code_entry = $request->input('code_entry');
        $fundRotaryEntry->amount = $request->input('amount');
        $fundRotaryEntry->date_entry_amount = $request->input('date_entry_amount');
        $fundRotaryEntry->role_id = $request->input('role_id');

        $fundRotaryEntry->balance = $request->input('amount');

        if($fundRotaryEntry->last == null)
            $fundRotaryEntry->balance_previous= 0;
        else{
            $balance_previous= $fundRotaryEntry->last->balance;
            $fundRotaryEntry->balance_previous= $balance_previous;
        }

        return FundRotaryEntry::create($fundRotaryEntry->toArray());

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
        $fundRotaryEntry = FundRotaryEntry::find($id);
        return $fundRotaryEntry;
    }

    /**
     * Actualizar informacion del fondo rotatorio
     * Actualizar datos del fondo rotatorio
     * @urlParam fund_rotatory_id ID del fondo rotatorio. Example: 1
     * @bodyParam code_entry string Código del fondo rotatorio. Example: 06/2021
     * @bodyParam amount numeric Monto de ingreso del fondo rotatoio. Example: 50000
     * @bodyParam date_entry_amount date Fecha de ingreso del fondo o asignacion. Example: 2021/06/01
     * @bodyParam role_id numeric Rol con el cual se realizo el registro. Example: 92
     * @bodyParam balance numeric Saldo del fondo rotatorio. Example: 10000
     * @bodyParam balance_previous numeric Saldo del fondo rotatorio anterior. Example: 200
     * @authenticated
     * @responseFile responses/aid_contribution/update.200.json
     */
    public function update(FundRotaryEntryForm $request,$fundRotaryEntry)
    {
        $fundRotaryEntry = FundRotaryEntry::find($fundRotaryEntry);
        $fundRotaryEntry->fill($request->all());
        $fundRotaryEntry->save();
        return  $fundRotaryEntry;
    }

    /**
     * Eliminar fondo rotatorio
     * Eliminar datos del fondo rotatorio
     * @urlParam fund_rotatory_id ID del fondo rotatorio. Example: 1
     * @authenticated
     * @responseFile responses/fund_rotary_entry/destroy.200.json
     */
    public function destroy($fundRotaryEntry)
    {
        $fundRotaryEntry = FundRotaryEntry::find($fundRotaryEntry);
        $fundRotaryEntry->delete();
        return $fundRotaryEntry;
    }
}
