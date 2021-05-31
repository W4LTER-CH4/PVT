<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>PLATAFORMA VIRTUAL ADMINISTRATIVA - MUSERPOL </title>
    <link rel="stylesheet" href="{{ public_path("/css/report-print.min.css") }}" media="all"/>
</head>
@include('partials.header', $header)
<body>
<div class="block">
    <div class="font-semibold leading-tight text-center m-b-10 text-xs">
    @if($loan->parent_loan_id != null)
        ADENDA AL CONTRATO DE PRÉSTAMO POR REPROGRAMACIÓN AL CONTRATO <font style="text-transform: uppercase;">{{ $loan->parent_loan->code }}</font>
    @else
    ADENDA AL CONTRATO DE PRÉSTAMO POR REPROGRAMACIÓN AL CONTRATO <font style="text-transform: uppercase;">{{ $loan->data_loan->code }} SISMU</font>
    @endif
        <div> {{ $title }}</div>
    </div>
</div>
<div class="block text-justify">
    <div>
    @if($loan->parent_loan_id != null)
        Conste por la presente Adenda al Contrato de préstamo de {{ $loan->parent_loan->code }},  por modificación en la situación del PRESTATARIO en la cual adjunto documentos, que al solo reconocimiento de firmas y rubricas ante autoridad competente sera elevado a instrumento Publico, por lo que las partes que intervienen lo suscriben al tenor de las siguientes clausulas y condiciones:
    @else
        Conste por la presente Adenda al Contrato de préstamo de {{ $loan->data_loan->code }},  por modificación en la situación del PRESTATARIO en la cual adjunto documentos, que al solo reconocimiento de firmas y rubricas ante autoridad competente sera elevado a instrumento Publico, por lo que las partes que intervienen lo suscriben al tenor de las siguientes clausulas y condiciones:
    @endif
    </div>
    <div>
    <b>PRIMERA.- (DE LAS PARTES):</b> Intervienen en el presente contrato, por una parte como acreedor la Mutual de Servicios al Policía (MUSERPOL), representada legalmente por el {{ $employees[0]['position'] }} Cnl. {{ $employees[0]['name'] }} con C.I. {{ $employees[0]['identity_card'] }} y su {{ $employees[1]['position'] }} Lic. {{ $employees[1]['name'] }} con C.I. {{ $employees[1]['identity_card'] }}, que para fines de este contrato en adelante se denominará MUSERPOL con domicilio en la Z. Sopocachi, Av. 6 de Agosto Nº 2354 y por otra parte como

        @if (count($lenders) == 1)
        @php ($lender = $lenders[0]->disbursable)
        @php ($male_female = Util::male_female($lender->gender))
        <span>
            DEUDOR{{ $lender->gender == 'M' ? '' : 'A' }} {{ $lender->full_name }}, con C.I. {{ $lender->identity_card_ext }}, {{ $lender->civil_status_gender }}, mayor de edad, hábil por derecho, natural de {{ $lender->city_birth->name }}, vecin{{ $male_female }} de {{ $lender->address->cityName() }} y con domicilio especial en {{ $lender->address->full_address }}, en adelante denominad{{ $male_female }} PRESTATARIO.
        </span>
        @endif
    </div>
    <div>
    @if($loan->parent_loan_id != null)
        <b>SEGUNDA.- (DEL ANTECEDENTE):</b> Mediante contrato de préstamo N° {{ $loan->parent_loan->code }} de fecha {{ Carbon::parse($loan->parent_loan->disbursement_date)->isoFormat('LL') }}, suscrito entre MUSERPOL y el PRESTATARIO, se otorgo un préstamo por la suma de {{ Util::money_format($loan->parent_loan->amount_approved) }} (<span class="uppercase">{{ Util::money_format($loan->parent_loan->amount_approved, true) }}</span> Bolivianos), con garantía de todos sus bienes habidos y por haber, así como la garantía personal, 
        <?php $cont = 0; $concat_guarantor = "";
            foreach($guarantors as $guarantor){
                $male_female_guarantor = Util::male_female($guarantor->gender);
                $cont ++; $concat_guarantor = "(garante Nº ".$cont.")";

            ?>
            <span>
            {{ $guarantor->gender == 'M' ? 'el Sr.' : 'la Sra' }} {{ $guarantor->full_name }}, con C.I. {{ $guarantor->identity_card_ext }}, {{ $guarantor->civil_status_gender }}, mayor de edad, hábil por derecho, natural de {{ $guarantor->city_birth->name }}, vecin{{ $male_female_guarantor }} de {{ $guarantor->address->cityName() }} y con domicilio especial en {{ $guarantor->address->full_address }} {{ $concat_guarantor }}
            </span>
        <?php } ?>
        programados a {{ $loan->parent_loan->loan_term}} meses de pago y cumplimiento de obligación, con una amortización mensual de Bs. {{ Util::money_format($loan->parent_loan->estimated_quota) }} (<span class="uppercase">{{ Util::money_format($loan->parent_loan->estimated_quota, true) }}</span> Bolivianos). 
    @else
    <b>SEGUNDA.- (DEL ANTECEDENTE):</b> Mediante contrato de préstamo N° {{ $loan->data_loan->code }} SISMU suscrito entre MUSERPOL y el PRESTATARIO, se otorgo un préstamo por la suma de {{ Util::money_format($loan->data_loan->amount_approved) }} (<span class="uppercase">{{ Util::money_format($loan->data_loan->amount_approved, true) }}</span> Bolivianos), con garantía de todos sus bienes habidos y por haber, así como la garantía personal 
        <?php $cont = 0; $concat_guarantor = "";
            foreach($guarantors as $guarantor){
                $male_female_guarantor = Util::male_female($guarantor->gender);
                $cont ++; $concat_guarantor = "(garante Nº ".$cont.")";

            ?>
            <span>
            {{ $guarantor->gender == 'M' ? 'el Sr.' : 'la Sra' }} {{ $guarantor->full_name }}, con C.I. {{ $guarantor->identity_card_ext }}, {{ $guarantor->civil_status_gender }}, mayor de edad, hábil por derecho, natural de {{ $guarantor->city_birth->name }}, vecin{{ $male_female_guarantor }} de {{ $guarantor->address->cityName() }} y con domicilio especial en {{ $guarantor->address->full_address }} {{ $concat_guarantor }} ,
            </span>
        <?php } ?>
        ; programados a {{ $loan->data_loan->loan_term}} meses de pago y cumplimiento de obligación, con una amortización mensual de Bs. {{ Util::money_format($loan->data_loan->estimated_quota) }} (<span class="uppercase">{{ Util::money_format($loan->data_loan->estimated_quota, true) }}</span> Bolivianos).
    @endif
    </div>
    <div>
        <b>TERCERA.- (DEL OBJETO):</b>  El objeto del presente es la adenda modificatoria del contrato señalado en los antecedentes de la cláusula segunda, a solicitud escrita del PRESTATARIO en fecha {{ Carbon::parse($loan->request_date)->isoFormat('LL') }}, la cual se encuentra respaldada por los documentos adjuntados en la solicitud, y en estricta sujeción con los previsto en el art. 22 del Reglamento de Prestamos, se suscribe la presente Adenda bajo las siguientes modificaciones y condiciones:
        @if($loan->parent_loan_id != null)
        <br><b>3.1.- Se modifica la cláusula (Plazo)</b>.-  El plazo para el pago total de la deuda establecida de {{ $loan->parent_loan->loan_term}} es reprogramado y modificado a {{ $loan->loan_term}}
        @else
        <br><b>3.1.- Se modifica la cláusula (Plazo)</b>.-  El plazo para el pago total de la deuda establecida de {{ $loan->data_loan->loan_term}} es reprogramado y modificado a {{ $loan->loan_term}}
        @endif
        <br><b>3.2.- Se modifica la cláusula (Cuota de Amortización)</b>.-  La amortización del pago a capital e intereses mensual y constantes que el prestatario efectuara a partir de la fecha de la suscripción de la presente adenda es de Bs. {{ Util::money_format($loan->estimated_quota) }} (<span class="uppercase">{{ Util::money_format($loan->estimated_quota, true) }}</span> Bolivianos). 
        <br><b>3.3.- Se modifica la cláusula (De la Garantía)</b>.-  El PRESTATARIO garantizara el pago de lo adeudado con todos sus bienes, derechos y acciones habidas y por haber, presentes y futuros, ademas de todos los beneficios que otorga la MUSERPOL.
    </div>
    <div>
        <b>CUARTA.- (DE LAS CONDICIONES Y CLAUSULAS ACORDADAS):</b> En cuanto a las demás clausulas y condiciones establecidas en el contrato de préstamos señalados en la cláusula segunda de la presente adenda, se mantienen y son de cumplimiento obligatorio para el PRESTATARIO y MUSERPOL, no admitiendo por tanto ningún tipo de sobre entendimiento, conclusiones e interpretaciones contrarias, siendo la presente de unica y exclusiva modificación los puntos 3.1, 3.2, 3.3 señalados en la cláusula precedente.
    </div>
    <div>
        <b>QUINTA.- (DE LA CONFORMIDAD Y ACEPTACIÓN):</b> Por una parte en calidad de acreedora la MUSERPOL, representada por su {{ $employees[0]['position'] }} Cnl. {{ $employees[0]['name'] }} y su {{ $employees[1]['position'] }} Lic. {{ $employees[1]['name'] }} y por otra parte en calidad de
        @if (count($lenders) == 1)
        <span>
            DEUDOR{{ $lender->gender == 'M' ? '' : 'A' }} {{ $lender->full_name }} de generales ya señaladas como PRESTATARIO;
            <?php $cont = 0; $concat_guarantor = "";
            foreach($guarantors as $guarantor){
                $male_female_guarantor = Util::male_female($guarantor->gender);
                $cont ++; $concat_guarantor = "(garante Nº ".$cont.")";

            ?>
            <span>
            {{ $guarantor->gender == 'M' ? 'el Sr.' : 'la Sra' }} {{ $guarantor->full_name }}, con C.I. {{ $guarantor->identity_card_ext }}, {{ $guarantor->civil_status_gender }}, mayor de edad, hábil por derecho, natural de {{ $guarantor->city_birth->name }}, vecin{{ $male_female_guarantor }} de {{ $guarantor->address->cityName() }} y con domicilio especial en {{ $guarantor->address->full_address }} {{ $concat_guarantor }} ,
            </span>
        <?php } ?>
            ; garantes solidarios, mancomunados e indivisibles, damos nuestra plena conformidad con todas y cada una de las cláusulas precedentes, obligándolos a su fiel y estricto cumplimiento. En señal de lo cual suscribimos el presente contrato de préstamo de dinero en manifestación de nuestra libre y espontánea voluntad y sin que medie vicio de consentimiento alguno.
        </span>
        @endif
    </div><br><br>
    <div class="text-center">
        <p class="center">
        La Paz, {{ Carbon::parse($loan->request_date)->isoFormat('LL') }}
        </p>
    </div>
</div>
<div class = "m-t-100">
    <div>
        @if (count($guarantors) == 1)
        @php ($guarantor = $guarantors[0])
        <table>
            <tr>
                <td  with = "50%">
                @include('partials.signature_box', [
                    'full_name' => $lender->full_name,
                    'identity_card' => $lender->identity_card_ext,
                    'position' => 'PRESTATARIO'
                ])
                </td>
                <td  with = "50%">
                @include('partials.signature_box', [
                    'full_name' => $guarantor->full_name,
                    'identity_card' => $guarantor->identity_card_ext,
                    'position' => 'GARANTE'
                ])
                </td>
            </tr>
        </table>
        @endif
    </div>
    @if (count($guarantors) == 2)
    <div>
        @include('partials.signature_box', [
            'full_name' => $lender->full_name,
            'identity_card' => $lender->identity_card_ext,
            'position' => 'PRESTATARIO'
        ])
    </div>
    <div class = "no-page-break">
        <div class = "m-t-100">
            <table>
                <tr>
                    @foreach ($guarantors as $guarantor)
                    <td with = "50%">
                        @include('partials.signature_box', [
                            'full_name' => $guarantor->full_name,
                            'identity_card' => $guarantor->identity_card_ext,
                            'position' => 'GARANTE'
                        ])
                    @endforeach
                    </td>
                </tr>
            </table>
        </div>
    </div>
    @endif
    <div class="m-t-75">
        <table>
            <tr>
                @foreach ($employees as $key => $employee)
                <td width="50%">
                    @include('partials.signature_box', [
                        'full_name' => $employee['name'],
                        'identity_card' => $employee['identity_card'],
                        'position' => $employee['position'],
                        'employee' => true
                    ])
                @endforeach
                </td>
            </tr>
        </table>
    </div>
</div>
</body>
</html>
