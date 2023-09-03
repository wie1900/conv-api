<?php

namespace Conv\App\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class ConvRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules()
    {
        return [
            'number' => array(
                'required',
                'max:30',
                'regex:/^(\d{1,30})$|^\d{0,30}(\.\d{1,2})$/'
            )
        ];

    }

    public function failedValidation(Validator $validator)
    {
        // throw new HttpResponseException(response()->json([
        //     'success'   => false,
        //     'message'   => 'Validation errors',
        //     'data'      => $validator->errors()
        // ]));

        throw new HttpResponseException(response()->json([
            'errors'   => $validator->errors()
        ]));
    }

    public function messages()
    {
        return [
            'number.required' => 'Number is required',
            'number.max' => 'Number is max 30',
            'number.regex' => 'Only numbers and optionally: point + 2 decimals!'
        ];
    }
}
