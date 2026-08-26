<?php

namespace App\Http\Requests\Task;

use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Exists;

trait ValidatesTaskAssignees
{
    protected function visibleAssigneeRule(): Exists
    {
        $actor = $this->user();

        return Rule::exists('users', 'id')->where(
            function (Builder $query) use ($actor): void {
                if (! $actor instanceof User) {
                    $query->whereRaw('1 = 0');

                    return;
                }

                match ($actor->recordScope()) {
                    'all' => null,
                    'branch' => $query->where('branch_id', $actor->branch_id),
                    'team' => $query->where('department_id', $actor->department_id),
                    default => $query->where('id', $actor->id),
                };
            },
        );
    }
}
