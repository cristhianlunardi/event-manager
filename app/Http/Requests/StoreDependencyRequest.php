<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDependencyRequest extends FormRequest
{
    /**
     * @var mixed
     */
    private $data;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'data' => 'required',
            'data.*.key' => 'required|unique:dependencies',
            'data.*.name' => 'required'
        ];
    }
}
