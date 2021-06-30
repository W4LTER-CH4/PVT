<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanPaymentCopyCommandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_payment_copy_commands', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('period_id')->unsigned();
            $table->foreign('period_id')->references('id')->on('loan_payment_periods');
            $table->string('identity_card');
            $table->float('amount',10,2);
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
        Schema::dropIfExists('loan_payment_copy_commands');
    }
}
