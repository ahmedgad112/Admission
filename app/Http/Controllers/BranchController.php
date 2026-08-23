<?php

namespace App\Http\Controllers;

use App\Concerns\RespondsWithInertiaOrJson;
use App\Http\Requests\Branch\StoreBranchRequest;
use App\Http\Requests\Branch\UpdateBranchRequest;
use App\Models\Branch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BranchController extends Controller
{
    use RespondsWithInertiaOrJson;

    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Branch::class);

        $user = $request->user();
        abort_unless($user !== null, 403);

        $branches = Branch::query()
            ->when(! $user->isSuperAdmin(), fn ($query) => $query->whereKey($user->branch_id))
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return Inertia::render('branches/Index', [
            'branches' => $branches,
            'canCreate' => $user->can('create', Branch::class),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Branch::class);

        return Inertia::render('branches/Create');
    }

    public function store(StoreBranchRequest $request): JsonResponse|RedirectResponse
    {
        $branch = Branch::query()->create([
            ...$request->safe()->only(['name']),
            'latitude' => $request->validated('latitude') ?? 0,
            'longitude' => $request->validated('longitude') ?? 0,
            'radius_meters' => $request->validated('radius_meters') ?? 100,
        ]);

        return $this->flashRedirect($request, __('flash.branch.created'), route('branches.index'), [
            'branch' => $branch,
        ]);
    }

    public function edit(Branch $branch): Response
    {
        $this->authorize('update', $branch);

        return Inertia::render('branches/Edit', [
            'branch' => [
                'id' => $branch->id,
                'name' => $branch->name,
            ],
        ]);
    }

    public function update(UpdateBranchRequest $request, Branch $branch): JsonResponse|RedirectResponse
    {
        $branch->update($request->safe()->only(['name']));

        return $this->flashRedirect($request, __('flash.branch.updated'), route('branches.index'), [
            'branch' => $branch,
        ]);
    }

    public function destroy(Request $request, Branch $branch): JsonResponse|RedirectResponse
    {
        $this->authorize('delete', $branch);

        $branch->delete();

        return $this->flashRedirect($request, __('flash.branch.deleted'), route('branches.index'));
    }
}
