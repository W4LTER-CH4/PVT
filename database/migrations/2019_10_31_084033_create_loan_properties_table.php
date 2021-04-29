<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_properties', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('land_lot_number');//numero de lote de terreno
            $table->string('neighborhood_unit');//unidad vecinal
            $table->string('location');//urbanización
            $table->float('surface',10,2);//superficie
            $table->string('measurement');//unidad de medida superficie
            $table->unsignedBigInteger('cadastral_code');//codigo catastral
            $table->string('limit');//colindancias
            $table->string('public_deed_number');//número de escritura publica
            $table->string('lawyer');//Notaria de fe publica
            $table->string('registration_number');//número de matricula computarizada
            $table->string('real_folio_number');//número de asiento del folio real
            $table->string('public_deed_date');//fecha de escritura publica
            $table->float('net_realizable_value',10,2);//valor neto realizable
            $table->float('commercial_value',10,2)->nullable(); //valor comercial
            $table->float('rescue_value',10,2)->nullable(); //valor rescate
            $table->unsignedBigInteger('real_city_id');// ciudad de registro en derechos reales
            $table->foreign('real_city_id')->references('id')->on('cities');
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
        Schema::dropIfExists('loan_properties');
    }
}
