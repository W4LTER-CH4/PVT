<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\ProcedureModality;

class LoanIntervalTerm implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(ProcedureModality $procedure_modality)
    {
        $this->procedure_modality = $procedure_modality;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
         if($value >= $this->procedure_modality->loan_modality_parameter->minimum_term_modality && $value <= $this->procedure_modality->loan_modality_parameter->maximum_term_modality){
            return true;
         }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El plazo no corresponde con la modalidad'.' '.$this->procedure_modality->name;
    }
}
