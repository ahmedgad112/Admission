<?php

namespace App\Http\Requests\Task;

use App\Models\Task;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTaskAttachmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        $task = $this->route('task');

        return $task instanceof Task && ($this->user()?->can('comment', $task) ?? false);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $mimes = implode(',', config('attendance.allowed_attachment_mimes', []));

        return [
            'file' => [
                'required',
                'file',
                'max:'.(int) config('attendance.attachment_max_kilobytes', 10240),
                'mimes:'.$mimes,
            ],
        ];
    }
}
