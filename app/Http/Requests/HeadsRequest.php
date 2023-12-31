<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class HeadsRequest extends FormRequest
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
            'name' => 'required|alpha',
            'color' => 'required|string',
            'typeoftransaction_id' => 'required|numeric',
        ];

        $update=[
            
            'name' => 'required|alpha',
            'color' => 'required|string',
            'typeoftransaction_id' => 'required|numeric',

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
    public function messages()
    {
        return [
            'color.required' => 'Please fill color field',
           
        ];
    }
    
}
?>