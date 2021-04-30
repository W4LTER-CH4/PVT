<?php

use Illuminate\Database\Seeder;
use App\ProcedureType;
use App\LoanDestiny;

class LoanDestinyProcedureTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {    
        $destinies = [
            'Préstamo Anticipo' => [
                ['LIBRE DISPONIBILIDAD'],
                ['SALUD'],           
            ],
        ];$this->create($destinies); 
        $destinies = [   
            'Préstamo a Corto Plazo' => [
                ['CONSUMO'],
                ['SALUD'],  
            ],
        ];
        $this->create($destinies);

        $destinies = [   
            'Refinanciamiento Préstamo a Corto Plazo' => [
                ['CONSUMO'],
                ['SALUD'],  
            ],
        ];
        $this->create($destinies);

        $destinies = [    
            'Préstamo a Largo Plazo' => [
                ['AMPLIACIÓN DE VIVIENDA'],
                ['REFACCIÓN DE VIVIENDA'],
                ['REMODELACIÓN DE VIVIENDA'],  
                ['OTROS VIVIENDA'], 
                ['COMERCIO'], 
                ['COMPRA DE AUTOMÓVIL'], 
                ['SERVICIO DE INFRAESTRUCTURA'],
                ['SALUD'],                    
            ],
        ];
        $this->create($destinies);

        $destinies = [    
            'Refinanciamiento Préstamo a Largo Plazo' => [
                ['AMPLIACIÓN DE VIVIENDA'],
                ['REFACCIÓN DE VIVIENDA'],
                ['REMODELACIÓN DE VIVIENDA'],  
                ['OTROS VIVIENDA'], 
                ['COMERCIO'], 
                ['COMPRA DE AUTOMÓVIL'], 
                ['SERVICIO DE INFRAESTRUCTURA'],
                ['SALUD'],                    
            ],
        ];
        $this->create($destinies);

        $destinies = [ 
            'Préstamo Hipotecario' => [
                ['INVERSIÓN'],
                ['REFACCIÓN Y REMODELACIÓN DE VIVIENDA'],     
            ]
        ];
        $this->create($destinies);    
        
        $destinies = [ 
            'Refinanciamiento Préstamo Hipotecario' => [
                ['INVERSIÓN'],
                ['REFACCIÓN Y REMODELACIÓN DE VIVIENDA'],     
            ]
        ];
        $this->create($destinies);   
    }
    
    public function create($destinies){
        foreach ($destinies as $procedure_type => $destiny) {
            $procedure = ProcedureType::whereName($procedure_type)->first();      
            foreach($destiny as $loan_destiny ){
                $dest []= LoanDestiny::whereName($loan_destiny)->first()->id;       
                $procedure->loan_destinies()->sync($dest);        
            }   
        }
    }
}
