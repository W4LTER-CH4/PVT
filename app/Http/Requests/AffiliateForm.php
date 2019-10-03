<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use App\Affiliate;
class AffiliateForm extends FormRequest
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
        $this->sanitize();

        return [
            'identity_card' => 'required|unique:affiliates',
            'city_identity_card_id' => 'required|min:1',
            'first_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
            'birth_date' => 'required',
            'city_birth_id' => 'required',
            'pension_entity_id' => 'required',
            'degree_id'=> 'required',
            'affiliate_state_id'=>'required',
            'phone_number'=>'integer|nullable',
            'cell_phone_number'=>'integer|nullable'
        ];
    }
    public function messages(){
        return [
        ];
    }
    public function sanitize(){
        $input = $this->all();
        if (array_key_exists('first_name', $input)) $input['first_name'] = mb_strtoupper($input['first_name']);
        if (array_key_exists('second_name', $input)) $input['second_name'] = mb_strtoupper($input['second_name']);
        if (array_key_exists('last_name', $input)) $input['last_name'] = mb_strtoupper($input['last_name']);
        if (array_key_exists('mothers_last_name', $input)) $input['mothers_last_name'] = mb_strtoupper($input['mothers_last_name']);
        if (array_key_exists('reason_death', $input)) $input['reason_death'] = mb_strtoupper($input['reason_death']);
        if (array_key_exists('identity_card', $input)) $input['identity_card'] = mb_strtoupper($input['identity_card']);
        $this->replace($input);
    } 
}





