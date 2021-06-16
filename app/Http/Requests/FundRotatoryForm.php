<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FundRotatoryForm extends FormRequest
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
            'check_number' => 'numeric',
            'date_check_delivery' => 'date_format:"Y-m-d',
            'amount' => 'numeric',
            'role_id' => 'integer',//hasta aqui requerido
            'balance' =>'numeric|nullable',
            'balance_previous' =>'numeric|nullable',
            'user_id' =>'integer|nullable',
            'description' => 'string|nullable'
        ];
        switch ($this->method()) {
            case 'POST': {
                foreach (array_slice($rules, 0, 4) as $key => $rule) {
                    $rules[$key] = implode('|', ['required', $rule]);
                }
                return $rules;
            }
            //case 'GET':
            case 'PUT':
            case 'PATCH':{
                return $rules;
            }
        }
    }
}
