<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportPaymentGroupedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_payment_groupeds', function (Blueprint $table) {
            $table->unsignedBigInteger('import_payment_id')->unsigned();
            $table->foreign('import_payment_id')->references('id')->on('import_payments');
            $table->unsignedBigInteger('payment_grouped_id')->unsigned();
            $table->foreign('payment_grouped_id')->references('id')->on('payment_groupeds');
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
        Schema::dropIfExists('import_payment_groupeds');
    }
}
