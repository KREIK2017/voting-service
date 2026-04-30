<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateOptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('update', $this->route('option'));
    }

    public function rules(): array
    {
        return [
            'text' => ['required', 'string', 'min:1', 'max:500'],
            'order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
