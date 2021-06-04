<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanPaymentCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_payment_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type_register', ['SISTEMA','USUARIO'])->nullable();//tipo de registro por el SISTEMA o usuario 
            $table->string('shortened')->nullable();
            $table->boolean('is_valid')->default(true);// por defecto 
            $table->unique(['name', 'type_register']);
            $table->string('description')->nullable();
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
        Schema::dropIfExists('loan_payment_categories');
    }
}
