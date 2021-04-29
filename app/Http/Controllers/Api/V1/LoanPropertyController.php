<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\LoanProperty;
use App\Http\Requests\LoanPropertyForm;
use Illuminate\Http\Request;
use Util;

/** @group Bien inmueble
* Datos de los bienes inmuebles de los trámites de préstamos
*/
class LoanPropertyController extends Controller
{
    /**
    * Lista del detalle de un bien inmueble
    * Devuelve el listado con los datos paginados
    * @queryParam search Parámetro de búsqueda. Example: los olivos
    * @queryParam sortDesc Vector de orden descendente(true) o ascendente(false). Example: true
    * @queryParam per_page Número de datos por página. Example: 1
    * @queryParam page Número de página. Example: 1
    * @authenticated
    * @responseFile responses/loan_property/index.200.json
    */
    public function index(Request $request)
    {
        return Util::search_sort(new LoanProperty(), $request);
    }

    /**
    * Nueva detalle de un bien inmueble
    * Inserta nuevo detalle de un bien inmueble
    * @bodyParam land_lot_number alpha_dash required Numero de lote de terreno. Example: Nº0004
    * @bodyParam neighborhood_unit string required Unidad Vecinal. Example: 00000
    * @bodyParam location string required Ubicación. Example:Urbanización los olivos
    * @bodyParam surface string required Superficie. Example: 000.00
    * @bodyParam measurement string required Unidad de medida superficie. Example: mts
    * @bodyParam cadastral_code integer required Codigo Catastral. Example: 123
    * @bodyParam limit string required Colindancias. Example: Norte NSC al sud el lote NSC y al oeste NSC
    * @bodyParam public_deed_number string Número de escritura publica. Example: Nro. 000/2004
    * @bodyParam lawyer string required Notaria de fe publica. Example: Dra. Maria fernandez
    * @bodyParam registration_number string required Número de matricula computarizada. Example: 2.00.1.01.0000000
    * @bodyParam real_folio_number string required Número de asiento del folio real. Example: A2
    * @bodyParam public_deed_date date required Fecha de escritura Publica. Example: 2020-09-23
    * @bodyParam net_realizable_value float required Valor Neto Realizable. Example: 600000.6
    * @bodyParam commercial_value float  Valor Comercial. Example: 600000.6
    * @bodyParam rescue_value float  Valor rescarte. Example: 500000.6
    * @bodyParam real_city_id integer required Ciudad de registro en derechos reales. Example: 1
    * @authenticated
    * @responseFile responses/loan_property/store.200.json
    */
    public function store(LoanPropertyForm $request)
    {
        return LoanProperty::create($request->all());
    }

    /**
    * Detalle del bien inmueble
    * Devuelve el detalle de un bien inmueble mediante su ID
    * @urlParam loan_property required ID de bien inmueble. Example: 12
    * @authenticated
    * @responseFile responses/loan_property/show.200.json
    */
    public function show(LoanProperty $loanProperty)
    {
        return $loanProperty;
    }

    /**
    * Actualizar Bien inmueble
    * Actualizar datos principales del Bien inmueble
    * @urlParam loan_property required ID del bien inmueble. Example:13
    * @bodyParam land_lot_number alpha_dash Numero de lote de terreno. Example: Nº0004
    * @bodyParam neighborhood_unit string Unidad Vecinal. Example: 00000
    * @bodyParam location string Ubicación. Example: Urbanización los olivos mercedes
    * @bodyParam surface string Superficie. Example: 000.00
    * @bodyParam measurement string Unidad de medida superficie. Example: mts
    * @bodyParam cadastral_code integer Codigo Catastral. Example: 123
    * @bodyParam limit string Colindancias. Example: Norte NSC al sud el lote NSC y al oeste NSC
    * @bodyParam public_deed_number string Número de escritura publica. Example: Nro. 000/2004
    * @bodyParam lawyer string Notaria de fe publica. Example: Dra. Maria fernandez
    * @bodyParam registration_number string Número de matricula computarizada. Example: 2.00.1.01.0000000
    * @bodyParam real_folio_number string Número de asiento del folio real. Example: A2
    * @bodyParam public_deed_date date Fecha de escritura Publica. Example: 2020-09-23
    * @bodyParam net_realizable_value float Valor Neto Realizable. Example: 600000.6
    * @bodyParam commercial_value float  Valor Comercial. Example: 600000.6
    * @bodyParam rescue_value float  Valor rescarte. Example: 500000.6
    * @bodyParam real_city_id integer Ciudad de registro en derechos reales. Example: 1
    * @authenticated
    * @responseFile responses/loan_property/update.200.json
    */
    public function update(LoanPropertyForm $request, LoanProperty $loanProperty)
    {
        $loanProperty->fill($request->all());
        $loanProperty->save();
        return  $loanProperty;
    }

    /**
    * Eliminar una Bien inmueble
    * @urlParam loan_property required ID del bien inmueble. Example: 13
    * @authenticated
    * @responseFile responses/loan_property/destroy.200.json
    */
    public function destroy(LoanProperty $loanProperty)
    {
        $loanProperty->delete();
        return $loanProperty;
    }
}
