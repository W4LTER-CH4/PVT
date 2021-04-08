<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Record;
use Util;

/** @group Historial
*/
class RecordController extends Controller
{
    /**
    * Registros de actividad
    * Devuelve el listado con los datos paginados
    * @queryParam user_id Filtro por id de usuario. Example: 122
    * @queryParam loan_id Filtro por id de préstamo. Example: 2
    * @queryParam search Parámetro de búsqueda. Example: Datos Personales
    * @queryParam sortBy Vector de ordenamiento. Example: [created_at]
    * @queryParam sortDesc Vector de orden descendente(true) o ascendente(false). Example: [false]
    * @queryParam per_page Número de datos por página. Example: 8
    * @queryParam page Número de página. Example: 1
    * @authenticated
    * @responseFile responses/record/index.200.json
    */
    public function index(Request $request)
    {
        $filter = [];
        if ($request->has('user_id')) {
            $filter['user_id'] = $request->user_id;
        }
        if ($request->has('loan_id')) {
            $filter['recordable_id'] = $request->loan_id;
            $filter['recordable_type'] = "loans";
        }
        return Util::search_sort(new Record(), $request, $filter);
    }
    /**
    * Registros de Cobros
    * Devuelve el listado con los datos paginados
    * @queryParam user_id Filtro por id de usuario. Example: 95
    * @queryParam loan_id required Filtro por id de préstamo. Example: 2
    * @queryParam search Parámetro de búsqueda. Example: Datos de Pago
    * @queryParam sortBy Vector de ordenamiento. Example: [created_at]
    * @queryParam sortDesc Vector de orden descendente(true) o ascendente(false). Example: [false]
    * @queryParam per_page Número de datos por página. Example: 8
    * @queryParam page Número de página. Example: 1
    * @authenticated
    * @responseFile responses/record/get_record_loan_payment.200.json
    */
    public function record_loan_payment(Request $request)
    {
        $filter = [];
        if ($request->has('user_id')) {
            $filter['user_id'] = $request->user_id;
        }
        if ($request->has('loan_id')) {
            $filter['recordable_id'] = $request->loan_id;
            $filter['recordable_type'] = "loan_payments";
        }
        return Util::search_sort(new Record(), $request, $filter);
    }
}