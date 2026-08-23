<?php

namespace App\Http\Requests\Branch;

trait ValidatesBranchLocation
{
    /**
     * @return array<string, mixed>
     */
    protected function locationRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'radius_meters' => ['nullable', 'integer', 'min:20', 'max:20000'],
        ];
    }
}
