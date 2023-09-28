<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class TransactionsRequest extends FormRequest
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
        return [
            'amount' => 'required|numeric',
            'head_id' => 'required|integer',
            'bank_id' => 'required|integer',
            'typeoftransaction_id' => 'required|integer',
            'date' => 'required|date',

        ];
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
