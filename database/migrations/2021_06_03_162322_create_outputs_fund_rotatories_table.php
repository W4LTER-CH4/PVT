<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutputsFundRotatoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outputs_fund_rotatories', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->unsignedBigInteger('loan_id')->unsigned();
            $table->foreign('loan_id')->references('id')->on('loans');
            $table->unsignedBigInteger('fund_rotary_entry_id');  // id usuario
            $table->foreign('fund_rotary_entry_id')->references('id')->on('fund_rotary_entries');
            $table->text('description')->nullable();
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
        Schema::dropIfExists('outputs_fund_rotatories');
    }
}
