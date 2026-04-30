<?php

namespace App\Http\Requests\Admin;

use App\Models\Option;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreOptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('create', [Option::class, $this->route('poll')]);
    }

    public function rules(): array
    {
        return [
            'text' => ['required', 'string', 'min:1', 'max:500'],
            'order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
