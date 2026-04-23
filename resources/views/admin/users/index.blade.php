@extends('layouts.app', ['title' => 'Access Control | Tailor'])

@section('content')
    <div class="d-flex justify-content-end mb-4">
        <a href="{{ route('admin.users.create') }}" class="btn btn-tailor rounded-4 px-4 py-3">Create New Tailor and Manager</a>
    </div>

    <div class="card-tailor rounded-4 p-4 p-lg-5">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-4">
            <div>
                <h2 class="fw-bold mb-1">Access Control</h2>
                <p class="text-secondary mb-0">Manage all system accounts, roles, and profile updates from one place.</p>
            </div>

            <form method="GET" action="{{ route('admin.users.index') }}" class="w-100" id="access-control-search-form" style="max-width: 360px;">
                <!-- <label for="search" class="form-label">Search</label> -->
                <input type="text" id="search" name="search" value="{{ $filters['search'] }}" class="form-control rounded-4" placeholder="Search by name, email, or role">
            </form>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created</th>
                        <th>Manage</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td class="text-capitalize">{{ $user->role }}</td>
                            <td>{{ $user->created_at?->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-dark rounded-4 px-3 py-2">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-secondary">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        (() => {
            const form = document.getElementById('access-control-search-form');
            const searchInput = document.getElementById('search');

            if (!form) {
                return;
            }

            let timeoutId;

            searchInput?.addEventListener('input', () => {
                window.clearTimeout(timeoutId);
                timeoutId = window.setTimeout(() => form.submit(), 300);
            });
        })();
    </script>
@endsection
