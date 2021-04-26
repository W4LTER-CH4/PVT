<?php

use Illuminate\Database\Seeder;
use App\AmortizationType;

class AmortizationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $amortization_types = [
            ['name' => 'Depósito Bancario'],
            ['name' => 'Efectivo'],
            ['name' => 'Descuento automático'],
            ['name' => 'Descuento por retenciones'],
            ['name' => 'Contabilidad']
        ];
        foreach ($amortization_types as $amortization_type) {
            AmortizationType::firstOrCreate($amortization_type);
        }
    }
}
