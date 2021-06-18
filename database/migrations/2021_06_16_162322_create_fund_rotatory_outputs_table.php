<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFundRotatoryOutputsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fund_rotatory_outputs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('code')->unique();
            $table->unsignedBigInteger('loan_id')->unsigned(); //id del prestamo
            $table->foreign('loan_id')->references('id')->on('loans');
            $table->unsignedBigInteger('fund_rotatory_id');  // id del fondo rotatorio 
            $table->foreign('fund_rotatory_id')->references('id')->on('fund_rotatories');
            $table->unsignedBigInteger('user_id');  // id usuario
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('role_id');  // id del rol de usuario
            $table->foreign('role_id')->references('id')->on('roles');
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
        Schema::dropIfExists('fund_rotatory_outputs');
    }
}
