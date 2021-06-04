<?php

use Illuminate\Database\Seeder;
use App\LoanPaymentCategorie;

class LoanPaymentCategorieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['name'=>'Refinanciamiento','type_register'=>'USUARIO','shortened'=>'REFI-U', 'is_valid'=>true,'description'=>"Cobro para Refinanciamiento de préstamo, reg por Usuario"],
            ['name'=>'Reprogramacion','type_register'=>'USUARIO','shortened'=>'REPRO-U', 'is_valid'=>true,'description'=>"Cobro para Reprogramación de préstamo, reg por Usuario"],
            ['name'=>'Regular','type_register'=>'USUARIO','shortened'=>'REGULAR-U', 'is_valid'=>true,'description'=>"Cobro Regular de préstamo, reg por Usuario"],
            ['name'=>'Regular','type_register'=>'SISTEMA','shortened'=>'REGULAR-S', 'is_valid'=>true,'description'=>"Cobro Regular de préstamo reg por sistema"]
        ];
        foreach ($categories as $categorie) {
            LoanPaymentCategorie::firstOrCreate($categorie);
        }
    }
}
