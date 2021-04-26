<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Contribution;
use App\Affiliate;

use Illuminate\Support\Facades\Auth;
use App\Helpers\Util;

/** @group Contribuciones de afiliados
* Contribuciones de los afiliados
*/

class ContributionController extends Controller
{
    public static function append_data(Contribution $contribution)
    {
        $contribution->breakdown = $contribution->breakdown;
        return $contribution;
    }
    /**
    * Lista de contribuciones
    * Devuelve el listado con los datos paginados
    * @queryParam search Parámetro de búsqueda. Example: 2020-02-03
    * @queryParam sortBy Vector de ordenamiento. Example: []
    * @queryParam sortDesc Vector de orden descendente(true) o ascendente(false). Example: [false]
    * @queryParam per_page Número de datos por página. Example: 8
    * @queryParam page Número de página. Example: 1
    * @authenticated
    * @responseFile responses/contributions/contributions.200.json
    */
    public function index(Request $request)
    {
        $data=Util::search_sort(new Contribution(), $request);
        $data->getCollection()->transform(function ($contribution) {
            return self::append_data($contribution, true);
        });
        return $data;
    }
    
    /**
    * Lista de contribuciones del affiliado
    * Devuelve el listado con los datos paginados del afiliado
    * @urlParam affiliate required ID de afiliado. Example: 26606
    * @queryParam search Parámetro de búsqueda. Example: 2020-02-03
    * @queryParam sortBy Vector de ordenamiento. Example: []
    * @queryParam sortDesc Vector de orden descendente(true) o ascendente(false). Example: [false]
    * @queryParam per_page Número de datos por página. Example: 8
    * @queryParam page Número de página. Example: 1
    * @authenticated
    * @responseFile responses/contributions/contributions_affiliates.200.json
    */

    public function get_all_contribution_affiliate(Request $request,Affiliate $affiliate)
    {
        $filters = [
            'affiliate_id' => $affiliate->id
        ];
        $data=Util::search_sort(new Contribution(), $request, $filters);
        $data->getCollection()->transform(function ($contribution) {
            return self::append_data($contribution, true);
        });
        return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
