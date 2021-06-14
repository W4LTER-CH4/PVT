<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OutputsFundRotatorieForm extends FormRequest
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
        $rules = [
            'loan_id'=>'integer|exists:loans,id|required',
            'fund_rotary_entry_id'=>'integer|exists:fund_rotary_entries,id|required',
            'description' => 'nullable','string','min:3',
            'user_id'=>'integer|exists:users,id|required',
            'role_id'=>'integer|exists:roles,id|required',
        ];
        return $rules;
    }
}
