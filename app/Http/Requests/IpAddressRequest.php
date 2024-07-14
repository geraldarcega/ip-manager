<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IpAddressRequest extends FormRequest
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
        $rules = [];
        $rules['label'] = ['max:255'];
        if ($this->isMethod('post')) {
            $rules['ip_address'] = ['required', 'ip'];
        }

        return $rules;
    }
}
