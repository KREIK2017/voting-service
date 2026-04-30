<?php

namespace App\Http\Requests\Admin;

use App\Models\Poll;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StorePollRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('create', Poll::class);
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'allow_multiple' => $this->boolean('allow_multiple'),
        ]);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'is_active' => ['boolean'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after:starts_at'],
            'allow_multiple' => ['boolean'],
        ];
    }
}
