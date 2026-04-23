<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()?->canManageUsers(), 403, 'You are not authorized to access this section.');

        $search = trim((string) request('search', ''));

        return view('admin.users.index', [
            'users' => User::query()
                ->when($search !== '', function ($query) use ($search) {
                    $query->where(function ($searchQuery) use ($search) {
                        $searchQuery
                            ->where('name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%')
                            ->orWhere('role', 'like', '%' . $search . '%');
                    });
                })
                ->latest()
                ->get(),
            'roles' => User::roles(),
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    public function create(): View
    {
        abort_unless(auth()->user()?->canManageUsers(), 403, 'You are not authorized to access this section.');

        return view('admin.users.create', [
            'roles' => User::roles(),
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $plainPassword = $validated['password'];

        $user = User::create($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'User created successfully.')
            ->with('created_account_credentials', [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'password' => $plainPassword,
            ]);
    }

    public function edit(User $user): View
    {
        abort_unless(auth()->user()?->canManageUsers(), 403, 'You are not authorized to access this section.');

        return view('admin.users.edit', [
            'managedUser' => $user,
            'roles' => User::roles(),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();
        $plainPassword = $validated['password'] ?? null;

        if ($plainPassword === null || $plainPassword === '') {
            unset($validated['password']);
        }

        $user->update($validated);

        $redirect = redirect()
            ->route('admin.users.index')
            ->with('status', 'Account updated successfully.');

        if ($plainPassword) {
            $redirect->with('created_account_credentials', [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'password' => $plainPassword,
            ]);
        }

        return $redirect;
    }
}
