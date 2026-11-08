<?php

namespace App\Http\Requests;

class UpdateBukuRequest extends StoreBukuRequest
{
    public function rules(): array
    {
        return collect(parent::rules())
            ->map(fn (array $rules) => array_map(
                fn ($rule) => $rule === 'required' ? 'sometimes' : $rule,
                $rules
            ))
            ->all();
    }
}