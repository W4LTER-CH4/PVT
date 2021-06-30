<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanPaymentGroupCommandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_payment_group_commands', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('affiliate_id')->unsigned();
            $table->foreign('affiliate_id')->references('id')->on('affiliates');
            $table->unsignedBigInteger('period_id')->unsigned();
            $table->foreign('period_id')->references('id')->on('loan_payment_periods');

        	$table->unique(['affiliate_id', 'period_id']);
            $table->string('identity_card');
            $table->float('amount',10,2);
            $table->float('amount_balance',10,2);
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
        Schema::dropIfExists('loan_payment_group_commands');
    }
}
