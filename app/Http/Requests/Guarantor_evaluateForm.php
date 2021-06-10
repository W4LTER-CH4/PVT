<?php

namespace App\Http\Requests;

use App\ProcedureModality;
use App\Rules\LoanIntervalAmount;
use App\Rules\LoanIntervalTerm;


use Illuminate\Foundation\Http\FormRequest;

class Guarantor_evaluateForm extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->procedure_modality_id) {
            $procedure_modality = ProcedureModality::findOrFail($this->procedure_modality_id);
        }else{
            return[
                'procedure_modality_id' => ['required','exists:procedure_modalities,id']
            ];
        }
        return[
            'affiliate_id'=> ['required', 'integer', 'exists:affiliates,id'],
            'quota_calculated_total_lender'=> ['required'],
            'remake_evaluation'=> ['boolean'],
            'remake_loan_id'=>['integer', 'nullable', 'exists:loans,id'],
            'contributions' => ['array', 'required'],
            'contributions.*.payable_liquid' => ['required', 'numeric', 'min:0'],
            'contributions.*.position_bonus' => ['required', 'numeric', 'min:0'],
            'contributions.*.border_bonus' => ['required', 'numeric', 'min:0'],
            'contributions.*.public_security_bonus' => ['required', 'numeric', 'min:0'],
            'contributions.*.east_bonus' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages()
    {
        return [
            'affiliate_id.required' => 'El id de afiliado es requerido',
            'quota_calculated_total_lender.required' => 'la cuota del titular es requerida',
            'contributions.required' => 'El garante es requerido',
            'contributions.*.payable_liquid.required' => 'El liquido pagable es requerido',
            'contributions.*.position_bonus.required' => 'El bono cargo es requerido',
            'contributions.*.border_bonus.required' => 'El bono frontera es requerido',
            'contributions.*.public_security_bonus.required' => 'El bono Seguridad Ciudadana es requerido',
            'contributions.*.east_bonus.required' => 'El bono oriente es requerido',
        ];
    }
}
