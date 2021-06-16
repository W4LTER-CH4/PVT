<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFundRotatoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fund_rotatories', function (Blueprint $table) {
            $table->id();
            $table->string('code_entry')->unique();
            $table->unsignedBigInteger('check_number'); 
            $table->dateTime('date_check_delivery'); 
            $table->float('amount',10,2); //mondo ingresado   
            $table->float('balance_previous',10,2); //saldo anterior al monto ingresado     
            $table->float('balance',10,2); //saldo
            $table->string('description')->nulable();
            $table->unsignedBigInteger('user_id');  // id usuario
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('role_id');  // id usuario
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
        Schema::dropIfExists('fund_rotatories');
    }
}
