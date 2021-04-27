<?php

use Illuminate\Database\Seeder;
use App\Module;
use App\ObservationType;

class ObservationTypeSeeder extends Seeder
{
    /**
     * Observaciones para la devolución de un prestamo.
     *
     * @return void
     */
    public function run()
    {
        $observation_type = [
            [ 'name' => 'Observado-Evaluación de monto',
                'description' => 'Subsanable',
            ],[ 
                'name' => 'Observado-Documentos Incompletos',
                'description' => 'Subsanable',
            ],[ 
                'name' => 'Observado-Documentos observados',
                'description' => 'Subsanable',
            ],[  
                'name' => 'Observado-Formulario observado',
                'description' => 'Subsanable',
            ],   
        ];
        foreach ($observation_type as $observation) {
            $module = Module::whereName('prestamos')->first();
            ObservationType::firstOrCreate([
                'name' => $observation['name'],
                'description' => $observation['description'],
                'module_id' => $module->id,
                'type' => 'T',
                'shortened' => $module->name
            ]);
        }       
    }
}
