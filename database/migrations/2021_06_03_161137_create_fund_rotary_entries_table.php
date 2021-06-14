<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFundRotaryEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fund_rotary_entries', function (Blueprint $table) {
            $table->id();
            $table->string('code_entry')->unique();
            $table->float('amount',10,2); //mondo ingresado
            $table->float('balance',10,2); //saldo
            $table->float('balance_previous',10,2); //saldo anterior al monto ingresado
            $table->dateTime('date_entry_amount');
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
        Schema::dropIfExists('fund_rotary_entries');
    }
}
