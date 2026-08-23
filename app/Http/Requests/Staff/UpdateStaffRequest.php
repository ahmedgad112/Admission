<?php

namespace App\Http\Requests\Staff;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStaffRequest extends FormRequest
{
    use ValidatesStaffMember;

    public function authorize(): bool
    {
        $staff = $this->route('user');

        return $staff instanceof User && ($this->user()?->can('update', $staff) ?? false);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return $this->staffMemberRules(updating: true);
    }
}
