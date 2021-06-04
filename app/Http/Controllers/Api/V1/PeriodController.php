<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Util;
use App\User;
use App\Period;
use App\Http\Requests\PeriodForm;

/** @group Periodos de cobros 
* Periodos de cobros para la importación
*/

class PeriodController extends Controller
{
    /**
    * Listar periodos
    * Devuelve el listado de los roles disponibles en el sistema
    * @queryParam search Parámetro de búsqueda. Example: 2020
    * @queryParam sortBy Vector de ordenamiento. Example: []
    * @queryParam sortDesc Vector de orden descendente(true) o ascendente(false). Example: [false]
    * @queryParam per_page Número de datos por página. Example: 8
    * @queryParam page Número de página. Example: 1
    * @authenticated
    * @responseFile responses/periods/index.200.json
     */
    public function index(Request $request)
    {
        return Util::search_sort(new Period(), $request);
    }

    /**
     * Nuevo registro de periodo
     * Inserta el periodo 
     * @bodyParam year numeric required Año del periodo. Example: 2021
     * @bodyParam month numeric required mes de la boleta es requerido. Example: 2
     * @bodyParam amount_conciliation numeric Monto de conciliacion. Example: 1255.5
     * @bodyParam description string Descripcion del periodo. Example: Periodo de descripción
     * @authenticated
     * @responseFile responses/periods/store.200.json
     */
    public function store(PeriodForm $request)
    {
        return Period::create($request->all());
    }

    /**
     * Detalle de los periodos
     * Devuelve el detalle de un periodo 
     * @urlParam id required ID del periodo. Example: 1
     * @responseFile responses/periods/show.200.json
     * @response
     */
    public function show($id)
    {
        $period = Period::find($id);
        return $period;
    }

    /**
     * Actualizar contribucion del sector pasivo
     * Actualizar datos principales destino de préstamo
     * @urlParam aid_contribution ID de destino de Préstamo. Example: 591292
     * @bodyParam affiliate_id integer ID del afiliado es requerido. Example: 10528
     * @bodyParam month_year date mes de la boleta es requerido. Example: 2020-02-01
     * @bodyParam rent numeric  Monto de la renta es requerido. Example: 1255.5
     * @bodyParam dignity_rent numeric  Monto de la renta dignidad es requerido. Example: 300
     * @authenticated
     * @responseFile responses/aid_contribution/update.200.json
     */
    public function update(PeriodForm $request,Period $period)
    {
        $period->fill($request->all());
        $period->save();
        return  $period;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Period $period)
    {
        $period->delete();
        return $period;
    }

    /**
     * Listar los meses de un año
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function get_list_month(Request $request)
    {
        $request->validate([
            'year' => 'required|exists:periods,year',
            'state' => 'integer'
        ]);
        $period = Period::where('year',$request->year)->get();
        return $period;
    }

     /**
     * Listar los años registrados
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function get_list_year(Request $request)
    {
        $period = Period::select('year')->distinct()->get();
        return $period;
    }    
    
}
