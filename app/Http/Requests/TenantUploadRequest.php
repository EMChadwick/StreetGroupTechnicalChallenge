<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TenantUploadRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'csv_file' => [
                'required',
                'file',
                'mimes:csv',
                'max:10240', //  max size 10 MB
            ],
        ];
    }

    public function messages()
    {
        return [
            'csv_file.required' => 'Please select a CSV file to upload.',
            'csv_file.mimes' => 'Incorrect file format; please upload a .csv file',
            'csv_file.max' => 'The CSV file may not be greater than 10 MB.',
        ];
    }
}
