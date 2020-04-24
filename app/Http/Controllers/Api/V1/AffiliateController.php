<?php

namespace App\Http\Controllers\Api\V1;

use \Waavi\Sanitizer\Sanitizer;
use Util;
use App\Affiliate;
use App\RecordType;
use App\User;
use App\Category;
use App\Degree;
use App\City;
use App\Hierarchy;
use App\AffiliateState;
use App\AffiliateStateType;
use App\Spouse;
use App\Contribution;
use App\Unit;
use App\Loan;
use App\LoanGlobalParameter;
use App\ProcedureType;
use App\Http\Resources\AffiliateResource;
use App\Http\Requests\AffiliateForm;
use App\Http\Requests\AffiliateFingerprintForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Events\FingerprintSavedEvent;
use Illuminate\Support\Facades\Storage;
use Carbon\CarbonImmutable;

/** @group Afiliados
* Datos de los afiliados y métodos para obtener y establecer sus relaciones
*/
class AffiliateController extends Controller
{
    /**
    * Lista de afiliados
    * Devuelve el listado con los datos paginados
    * @queryParam search Parámetro de búsqueda. Example: TORRE
    * @queryParam sortBy Vector de ordenamiento. Example: [last_name]
    * @queryParam sortDesc Vector de orden descendente(true) o ascendente(false). Example: [0]
    * @queryParam per_page Número de datos por página. Example: 8
    * @queryParam page Número de página. Example: 1
    * @authenticated
    * @responseFile responses/affiliate/index.200.json
    */
    public function index(Request $request)
    {
        $data = Util::search_sort(new Affiliate(), $request);
        return AffiliateResource::collection($data);
    }

    /**
    * Nuevo afiliado
    * Inserta nuevo afiliado
    * @bodyParam first_name string required Primer nombre. Example: JUAN
    * @bodyParam last_name string required Apellido paterno. Example: PINTO
    * @bodyParam gender string required Género (M,F). Example: M
    * @bodyParam birth_date date required Fecha de nacimiento (AÑO-MES-DÍA). Example: 1980-05-02
    * @bodyParam city_birth_id integer required ID de ciudad de nacimiento. Example: 10
    * @bodyParam city_identity_card_id integer required ID de ciudad del CI. Example: 4
    * @bodyParam civil_status string required Estado civil (S,C,D,V). Example: C
    * @bodyParam identity_card string required Carnet de identidad. Example: 165134-1L
    * @bodyParam affiliate_state_id integer ID de estado de afiliado. Example: 2
    * @bodyParam degree_id integer ID del grado policial. Example: 4
    * @bodyParam unit_id integer ID de unidad de destino. Example: 7
    * @bodyParam category_id integer ID de categoría. Example: 9
    * @bodyParam pension_entity_id integer ID de entidad de pensiones. Example: 1
    * @bodyParam registration string Matrícula. Example: 870914VBW
    * @bodyParam type string Tipo de destino (Comando, Batallón). Example: Comando
    * @bodyParam second_name string Segundo nombre. Example: ROBERTO
    * @bodyParam mothers_last_name string Apellido materno. Example: ROJAS
    * @bodyParam surname_husband string Apellido de casada. Example: PAREDES
    * @bodyParam date_entry date Fecha de ingreso a la policía. Example: 1980-05-20
    * @bodyParam date_death date Fecha de fallecimiento. Example: 2018-09-21
    * @bodyParam death_certificate_number string Número de certificado de defunción. Example: 180923-ATR
    * @bodyParam reason_death string Causa de fallecimiento. Example: Embolia
    * @bodyParam date_derelict date Fecha de baja de la policía. Example: 2017-12-30
    * @bodyParam reason_derelict string Causa de baja de la policía. Example: Proceso administrativo
    * @bodyParam due_date date Fecha de vencimiento del CI. Example: 2018-01-05
    * @bodyParam is_duedate_undefined boolean Si la fecha de vencimiento de CI es indefinido . Example: 0
    * @bodyParam change_date date Fecha de cambio. Example: 2015-02-03
    * @bodyParam phone_number integer Número de teléfono fijo. Example: 2254101
    * @bodyParam cell_phone_number array Números de celular. Example: [76543210,65432101]
    * @bodyParam afp boolean Si el afiliado aporta a AFP(1) o SENASIR(0). Example: 1
    * @bodyParam nua integer Número NUA. Example: 26271503
    * @bodyParam item integer Número de ítem policial. Example: 32706
    * @bodyParam service_years integer Años de servicio. Example: 6
    * @bodyParam service_months integer Meses de servicio. Example: 4
    * @bodyParam affiliate_registration_number integer Número único de registro de afiliado. Example: 10512
    * @bodyParam file_code string Código de folder de afiliado. Example: AFW-12
    * @authenticated
    * @responseFile responses/affiliate/store.200.json
    */
    public function store(AffiliateForm $request)
    {
        return Affiliate::create($request->all());
    }

    /**
    * Detalle de afiliado
    * Devuelve el detalle de un afiliado mediante su ID
    * @urlParam affiliate required ID de afiliado. Example: 54
    * @authenticated
    * @responseFile responses/affiliate/show.200.json
    */
    public function show(Affiliate $affiliate)
    {
        $affiliate->with_category = true;
        return new AffiliateResource($affiliate);
    }

    /**
    * Actualizar afiliado
    * Actualizar datos personales de afiliado
    * @urlParam affiliate required ID de afiliado. Example: 54
    * @bodyParam first_name string Primer nombre. Example: JUAN
    * @bodyParam last_name string Apellido paterno. Example: PINTO
    * @bodyParam gender string Género (M,F). Example: M
    * @bodyParam birth_date date Fecha de nacimiento (AÑO-MES-DÍA). Example: 1980-05-02
    * @bodyParam city_birth_id integer ID de ciudad de nacimiento. Example: 10
    * @bodyParam city_identity_card_id integer ID de ciudad del CI. Example: 4
    * @bodyParam civil_status string Estado civil (S,C,D,V). Example: C
    * @bodyParam identity_card string Carnet de identidad. Example: 165134-1L
    * @bodyParam affiliate_state_id integer ID de estado de afiliado. Example: 2
    * @bodyParam degree_id integer ID del grado policial. Example: 4
    * @bodyParam unit_id integer ID de unidad de destino. Example: 7
    * @bodyParam category_id integer ID de categoría. Example: 9
    * @bodyParam pension_entity_id integer ID de entidad de pensiones. Example: 1
    * @bodyParam registration string Matrícula. Example: 870914VBW
    * @bodyParam type string Tipo de destino (Comando, Batallón). Example: Comando
    * @bodyParam second_name string Segundo nombre. Example: ROBERTO
    * @bodyParam mothers_last_name string Apellido materno. Example: ROJAS
    * @bodyParam surname_husband string Apellido de casada. Example: PAREDES
    * @bodyParam date_entry date Fecha de ingreso a la policía. Example: 1980-05-20
    * @bodyParam date_death date Fecha de fallecimiento. Example: 2018-09-21
    * @bodyParam death_certificate_number date Fecha de certificado de defunción. Example: 2018-09-23
    * @bodyParam reason_death string Causa de fallecimiento. Example: Embolia
    * @bodyParam date_derelict date Fecha de baja de la policía. Example: 2017-12-30
    * @bodyParam reason_derelict string Causa de baja de la policía. Example: Proceso administrativo
    * @bodyParam due_date date Fecha de vencimiento del CI. Example: 2018-01-05
    * @bodyParam is_duedate_undefined boolean Si la fecha de vencimiento de CI es indefinido . Example: 0
    * @bodyParam change_date date Fecha de cambio. Example: 2015-02-03
    * @bodyParam phone_number integer Número de teléfono fijo. Example: 2254101
    * @bodyParam cell_phone_number array Números de celular. Example: [76543210,65432101]
    * @bodyParam afp boolean Si el afiliado aporta a AFP(1) o SENASIR(0). Example: 1
    * @bodyParam nua integer Número NUA. Example: 26271503
    * @bodyParam item integer Número de ítem policial. Example: 32706
    * @bodyParam service_years integer Años de servicio. Example: 6
    * @bodyParam service_months integer Meses de servicio. Example: 4
    * @bodyParam affiliate_registration_number integer Número único de registro de afiliado. Example: 10512
    * @bodyParam file_code string Código de folder de afiliado. Example: AFW-12
    * @authenticated
    * @responseFile responses/affiliate/update.200.json
    */
    public function update(AffiliateForm $request, Affiliate $affiliate)
    {
        if (!Auth::user()->can('update-affiliate-primary')) {
            $update = $request->except('first_name', 'second_name', 'last_name', 'mothers_last_name', 'surname_husband', 'identity_card');
        } else {
            $update = $request->all();
        }
        $affiliate->fill($update);
        $affiliate->save();
        return  $affiliate;
    }

    /**
    * Eliminar afiliado
    * Elimina un afiliado solo en caso de que no hubiese iniciado ningún trámite
    * @urlParam affiliate required ID de afiliado. Example: 54
    * @authenticated
    * @responseFile responses/affiliate/destroy.200.json
    */
    public function destroy(Affiliate $affiliate)
    {
        $affiliate->delete();
        return $affiliate;
    }

    /** @group Biométrico
    * Finalizar captura de huellas
    * Finaliza la captura de huellas en el dispositivo biométrico y envía un mensaje con el estado mediante sockets en el canal: fingerprint; el ejemplo de socket es el del código 201
    * @urlParam affiliate required ID de afiliado. Example: 2
    * @queryParam user_id required ID de usuario que realizó la captura. Example: 23
    * @queryParam success required Estado de la captura, 1 para exitoso y 0 para error. Example: 1
    * @responseFile 200 responses/affiliate/fingerprint_saved.200.json
    * @responseFile 201 responses/affiliate/fingerprint_saved.201.json
    */
    public function fingerprint_saved(AffiliateFingerprintForm $request, Affiliate $affiliate)
    {
        $user = User::findOrFail($request->user_id);
        if ($user->active) {
            event(new FingerprintSavedEvent($affiliate, $user, $request->success));
            return response()->json([
                'message' => 'Message broadcasted'
            ], 200);
        } else {
            abort(401);
        }
    }

    /** @group Biométrico
    * Registrar huellas
    * Inicia la captura de huellas en el dispositivo biométrico, la respuesta es enviada también mediante sockets en el canal: record; dicha difusión contiene la misma información que la respuesta de ejemplo
    * @urlParam affiliate required ID de afiliado. Example: 2
    * @responseFile responses/affiliate/update_fingerprint.200.json
    */
    public function update_fingerprint(Affiliate $affiliate)
    {
        $record_type = RecordType::whereName('datos-personales')->first();
        if ($record_type) {
            $affiliate->records()->create([
                'user_id' => Auth::user()->id,
                'record_type_id' => $record_type->id,
                'action' => 'inició la captura de huellas'
            ]);
            return $affiliate->records()->latest()->first();
        }
        abort(404);
    }

    /** @group Biométrico
    * Imagen perfil afiliado
    * Devuelve el listado con los nombres de los archivos de imagen, el contenido en base64 y el formato
    * @urlParam affiliate required ID de afiliado. Example: 2
    * @responseFile responses/affiliate/get_profile_images.200.json
    */
    public function get_profile_images(Request $request, $affiliate)
    {
        $files = [];
        try {
            $base_path = 'picture/';
            $fingerprint_files = ['_perfil.jpg'];
            foreach ($fingerprint_files as $file) {
                if (Storage::disk('ftp')->exists($base_path . $affiliate . $file)) {
                    array_push($files, [
                        'name' => $affiliate . $file,
                        'content' => base64_encode(Storage::disk('ftp')->get($base_path . $affiliate . $file)),
                        'format' => Storage::disk('ftp')->mimeType($base_path . $affiliate . $file)
                    ]);
                }
            }
        } catch (\Exception $e) {}
        return $files;
    }

    /** @group Biométrico
    * Imagen huellas afiliado
    * Devuelve el listado con los nombres de los archivos de imagen, el contenido en base64 y el formato
    * @urlParam affiliate required ID de afiliado. Example: 2
    * @responseFile responses/affiliate/get_fingerprint_images.200.json]
    */
    public function get_fingerprint_images($affiliate)
    {
        $files = [];
        try {
            $base_path = 'picture/';
            $fingerprint_files = ['_left_four.png', '_right_four.png', '_thumbs.png'];
            foreach ($fingerprint_files as $file) {
                if (Storage::disk('ftp')->exists($base_path . $affiliate . $file)) {
                    array_push($files, [
                        'name' => $affiliate . $file,
                        'content' => base64_encode(Storage::disk('ftp')->get($base_path . $affiliate . $file)),
                        'format' => Storage::disk('ftp')->mimeType($base_path . $affiliate . $file)
                    ]);
                }
            }
        } catch (\Exception $e) {}
        return $files;
    }

    /** @group Biométrico
    * Actualizar imagen perfil afiliado
    * Actualiza la imagen de perfil de afiliado capturada por una cámara en formato base64
    * @urlParam affiliate required ID de afiliado. Example: 2
    * @bodyParam image string required Imágen jpeg. Example: data:image/jpeg;base64,154AF...
    * @responseFile responses/affiliate/picture_save.200.json]
    */
    public function picture_save(Request $request, Affiliate $affiliate)
    {
        $request->validate([
            'image' => 'required|string'
        ]);
    $base_path = 'picture/';
    $code = $affiliate->id;
    $image = $request->image;   
    $image = str_replace('data:image/jpeg;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = $code.'_perfil.'.'jpg';
        try {
            Storage::disk('ftp')->put($base_path.$imageName, base64_decode($image));
            return response()->json([
                'message' => 'Fotografía actualizada'
            ], 200);
        } catch (\Exception $e) {
            abort(500, 'Error en la conexión con el servidor FTP');
        }
    }

    /**
    * Cónyugue
    * Devuelve los datos del o la cónyugue en caso de que el afiliado hubiese fallecido
    * @urlParam affiliate required ID de afiliado. Example: 12
    * @authenticated
    * @responseFile responses/affiliate/get_spouse.200.json
    */
    public function get_spouse(Affiliate $affiliate) {
        return response()->json($affiliate->spouse);
    }

    /**
    * Obtener direcciones
    * Devuelve la lista de direcciones del afiliado
    * @urlParam affiliate required ID de afiliado. Example: 1
    * @authenticated
    * @responseFile responses/affiliate/get_addresses.200.json
    */
    public function get_addresses(Affiliate $affiliate) {
        return $affiliate->addresses;
    }

    /**
    * Actualizar direcciones
    * Actualiza el listado de direcciones de un afiliado
    * @urlParam affiliate required ID de afiliado. Example: 12
    * @queryParam addresses required Lista de IDs de direcciones. Example: [12,17]
    * @authenticated
    * @responseFile responses/affiliate/update_addresses.200.json
    */
    public function update_addresses(Request $request, Affiliate $affiliate) {
        $request->validate([
            'addresses' => 'required|array|min:1',
            'addresses.*' => 'exists:addresses,id'
        ]);
        return $affiliate->addresses()->sync($request->addresses);
    }

    /**
    * Boletas de pago
    * Devuelve el listado de las boletas de pago de un afiliado, si se envía el ID de ciudad además devuelve un booleano para identificar si la petición contiene las últimas boletas y la diferencia de meses que se utilizó para la operación
    * @urlParam affiliate required ID de afiliado. Example: 1
    * @queryParam city_id ID de la ciudad de solicitud. Example: 4
    * @queryParam sortBy Vector de ordenamiento. Example: [month_year]
    * @queryParam sortDesc Vector de orden descendente(true) o ascendente(false). Example: [1]
    * @queryParam per_page Número de datos por página. Example: 3
    * @queryParam page Número de página. Example: 1
    * @authenticated
    * @responseFile responses/affiliate/get_contributions.200.json
    */
    public function get_contributions(Request $request, Affiliate $affiliate)
    {
        $filters = [
            'affiliate_id' => $affiliate->id
        ];
        $contributions = Util::search_sort(new Contribution(), $request, $filters);
        if ($request->has('city_id')) {
            $is_latest = false;
            $city = City::findOrFail($request->city_id);
            $offset_day = LoanGlobalParameter::latest()->first()->offset_ballot_day;
            $now = CarbonImmutable::now();
            if ($now->day <= $offset_day || $city->name != 'LA PAZ') {
                $before_month = 2;
            } else {
                $before_month = 1;
            }
            $current_ticket = CarbonImmutable::parse($contributions[0]->month_year);
            if ($now->startOfMonth()->diffInMonths($current_ticket->startOfMonth()) <= $before_month) {
                foreach ($contributions as $i => $ticket) {
                    $is_latest = true;
                    if ($ticket != $contributions->last()) {
                        $current_ticket = CarbonImmutable::parse($ticket->month_year);
                        $next_ticket = CarbonImmutable::parse($contributions[$i+1]->month_year);
                        if ($current_ticket->startOfMonth()->diffInMonths($next_ticket->startOfMonth()) !== 1) {
                            $is_latest = false;
                            break;
                        }
                    }
                }
            } else {
                $is_latest = false;
            }
            $contributions = collect([
                'valid' => $is_latest,
                'diff_months' => $before_month
            ])->merge($contributions);
        }
        return $contributions;
    }

    /** @group Préstamos
    * Préstamos por afiliado
    * Devuelve la lista de préstamos o garantías del afiliado
    * @urlParam affiliate required ID de afiliado. Example: 12
    * @queryParam guarantor required Préstamos para el afiliado como garante(1) o como titular(0). Example: 1
    * @queryParam state ID de state_id para filtrar por estado de préstamos. Example: 3
    * @authenticated
    * @responseFile responses/affiliate/get_loans.200.json
    */
    public function get_loans(Request $request, Affiliate $affiliate)
    {
        $request->validate([
            'guarantor' => 'required|boolean',
            'state' => 'integer'
        ]);
        $type = $request->boolean('guarantor') ? 'guarantors' : 'lenders';
        $relations[$type] = [
            'affiliate_id' => $affiliate->id
        ];
        if ($request->has('state')) {
            $relations['state'] = [
                'id' => $request->state
            ];
        }
        $data = Util::search_sort(new Loan(), $request, [], $relations, ['id']);
        foreach ($data as $loan) {
            $loan->balance = $loan->balance;
            $loan->estimated_quota = $loan->estimated_quota;
            $loan->defaulted = $loan->defaulted;
            $loan->lenders = $loan->lenders;
            $loan->guarantors = $loan->guarantors;
        }
        return $data;
    }

    /**
    * Estado
    * Devuelve el estado policial del afiliado
    * @urlParam affiliate required ID de afiliado. Example: 5
    * @authenticated
    * @responseFile responses/affiliate/get_state.200.json
    */
    public function get_state(Affiliate $affiliate)
    {
        if ($affiliate->affiliate_state) $affiliate->affiliate_state->affiliate_state_type;
        return $affiliate->affiliate_state;
    }

    /** @group Préstamos
    * Modalidad por afiliado
    * Devuelve la modalidad de trámite evaluando al afiliado y el tipo de trámite
    * @urlParam affiliate required ID de afiliado. Example: 5
    * @queryParam procedure_type_id required ID de tipo de trámite. Example: 9
    * @queryParam external_discount Booleano de descuento en otras entidades, 1 para si y 0 para no. Example: 1
    * @authenticated
    * @responseFile responses/affiliate/get_loan_modality.200.json
    */
    public function get_loan_modality(Request $request, Affiliate $affiliate) {
        $request->validate([
            'procedure_type_id' => 'required|integer|exists:procedure_types,id',
            'external_discount' => 'boolean|nullable',
        ]);
        if(!$affiliate->affiliate_state) abort(404, 'Debe actualizar el estado del afiliado');
        $modality = ProcedureType::findOrFail($request->procedure_type_id);
        return Loan::get_modality($modality->name, $affiliate);
    }
}