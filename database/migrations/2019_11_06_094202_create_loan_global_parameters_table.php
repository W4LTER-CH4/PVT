<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanGlobalParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_global_parameters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedTinyInteger('offset_ballot_day'); //dia de desface de boletas
            $table->unsignedTinyInteger('offset_interest_day'); //dia de desface de interes por desembolso
            $table->unsignedSmallInteger('livelihood_amount')->nullable();// cantidad de sustento-->inf. estadistica
            $table->unsignedTinyInteger('min_service_years'); // minimo años de servicio
            $table->unsignedTinyInteger('min_service_years_adm'); // minimo años de servicio adm policial
            $table->unsignedTinyInteger('max_guarantor_active'); // maximo de garantias para el sector activo
            $table->unsignedTinyInteger('max_guarantor_passive'); // maximo de garantias para el sector pasivo
            $table->unsignedTinyInteger('date_delete_payment'); // Días pasados para la eliminación del payment
            $table->unsignedTinyInteger('max_loans_active'); // Maximo de préstamos vigentes
            $table->unsignedTinyInteger('max_loans_process'); // Maximo de préstamos en proceso
            $table->unsignedTinyInteger('days_current_interest'); // Dias de interes corriente del mes  
            $table->unsignedTinyInteger('grace_period'); // periodo de gracia para cobro de penalidad
            $table->unsignedTinyInteger('consecutive_manual_payment'); // cantidad de pagos manuales consecutivos para alerta 
            $table->unsignedTinyInteger('max_months_go_back'); // maximo de meses atras de boletas
            $table->unsignedTinyInteger('min_percentage_paid'); // min de porcentage de pago a capital para Refi
            $table->unsignedTinyInteger('min_remaining_installments'); // min de cuotas restantes para refi
            $table->unsignedTinyInteger('min_amount_fund_rotary'); // monto minimos del fondo rotatorio
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_global_parameters');
    }
}
