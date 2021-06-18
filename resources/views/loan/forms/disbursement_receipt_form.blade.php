<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>PLATAFORMA VIRTUAL ADMINISTRATIVA - MUSERPOL RECIBO </title>
    <link rel="stylesheet" href="{{ public_path("/css/report-print.min.css") }}" media="all"/>
</head>
<style>
    .scissors-rule {
      display: block;
      text-align: center;
      overflow: hidden;
      white-space: nowrap;
      margin-top: 6px;
      margin-bottom: 17px;
    }
    .scissors-rule > span {
      position: relative;
      display: inline-block;
    }
    .scissors-rule > span:before,
    .scissors-rule > span:after {
      content: "";
      position: absolute;
      top: 50%;
      width: 9999px;
      height: 1px;
      background: white;
      border-top: 1px dashed black;
    }
    .scissors-rule > span:before {
      right: 100%;
      margin-right: 5px;
    }
    .scissors-rule > span:after {
      left: 100%;
      margin-left: 5px;
    }
  </style>
<body style="border: 0; border-radius: 0;">
@for($it = 0; $it<3; $it++)
    @php ($plural = count($lenders) > 1)
    @php ($n = 1)
    @include('partials.header_treasury', $header)
    <div>
        <table style="font-size:14px;" class="my-5">
            <tr>
                <td class="w-25">
                    &nbsp;
                </td>
                <td class="w-50">
                    &nbsp;
                    <div class="font-semibold leading-tight text-center m-b-10 text-Base">{{ $title }}</div>
                </td>
                <td class="font-semibold w-25">
                Monto en Bs.
                    <div class="font-semibold leading-tight rounded-full border text-center text-base">Bs. {{  Util::money_format($loan->amount_approved)}} </div>
                </td>
            </tr>
            <tr>
                <td class="w-25">
                </td>
                <td class="w-50">      
                </td>
                <td class="font-semibold w-25">
                Contrato Nro. 
                    <div class="font-semibold leading-tight rounded-full border text-center text-base">{{$loan->code}} </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="block">
        <table style="font-size:12px;" class="table-info border w-100 my-5 ">
            <tr>
                <td class="font-semibold  w-30">
                CONCEPTO:
                </td>
                <td class="uppercase w-70" >
                    {{$loan->modality->name}}
                </td>
            </tr>
            <tr>
                <td class="font-semibold w-30">
                CANTIDAD:
                </td>
                <td class="uppercase w-70" >
                {{ Util::money_format($loan->amount_approved, true)}}
                </td>
            </tr>
        </table>
    </div>
    <div class="no-page-break">
        <table style="font-size:10px;">
            <tbody>
            <tr height="25px" class="">              
            <td class="font-semibold leading-tight text-center w-50">        
                @php ($cont = 0)  
                @foreach ($signers->chunk(1) as $chunk)
                    @foreach ($chunk as $person)
                            @include('partials.signature_box', $person)
                        @php ($cont ++)
                    @endforeach
                @endforeach
                </td>   
                <td class="font-semibold leading-tight text-center w-50">
                @php($user = Auth::user())
                        @include('partials.signature_box', [
                            'full_name' => $user->full_name,
                            'position' => $user->position,
                            'employee' => true
                        ])
                ENTREGADO POR
                </td>
            </tr>     
            </tbody>
        </table>
    </div>
    <div class="m-b-1">
            ***Esta liquidación no es válida sin el Refrendo y Sello de Tesorería***</div>
    </div>
    <br>
    @if($it != 2)     
     <div class="scissors-rule">
        <span>&#9986;</span>
      </div>
      @endif
 @endfor
</body>

</html>
