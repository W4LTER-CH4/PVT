<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSismuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sismus', function (Blueprint $table) {
            $table->bigIncrements('id');// id unico
            $table->string('code');//codigo
            $table->float('amount_approved',8,2); // monto aprobado
            $table->unsignedSmallInteger('loan_term'); // plazo del prestamo en meses
            $table->float('balance',8,2); // saldo
            $table->float('estimated_quota',8,2); // cuota
            $table->date('date_cut_refinancing')->nullable(); //fecha de corte del sismu
            $table->dateTime('disbursement_date')->nullable(); //fecha de desembolso de prestamo sismu
            $table->unsignedBigInteger('loan_id');  //id del prestamo 
            $table->foreign('loan_id')->references('id')->on('loans');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sismus');
    }
}
