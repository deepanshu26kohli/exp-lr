<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class BanksRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $store = [
            'bank_name' => 'required|alpha',
            'holder_name' => 'required|alpha',
            'account_number' => 'required|numeric',
            'ifsc_code' => 'required|alpha_num',
            // 'ifbank' => 'required|boolean'
        ];

        $update=[
            
            'bank_name' => 'required|alpha',
            'holder_name' => 'required|alpha',
            'account_number' => 'required|numeric',
            'ifsc_code' => 'required|alpha_num',
            // 'ifbank' => 'required|boolean',
            'bank_balance' => 'required|numeric'
        ];

        if($this->method() == 'POST'){
          
            return $store;
        }
        else if($this->method() == 'PUT'){
            return $update;
        }
        
    }
    public function failedValidation(Validator $validator)

    {
                
                throw new HttpResponseException(response()->json([
                
                'success'  => false,
                
                'message'  => 'Please fill required fields properly',
                
                'data'   => $validator->errors()
                
                ])

    );
    
    }
}
