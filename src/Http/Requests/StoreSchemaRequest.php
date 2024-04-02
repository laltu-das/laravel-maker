<?php

namespace Laltu\LaravelMaker\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSchemaRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'model_name' => ['required'],
            'fields' => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
