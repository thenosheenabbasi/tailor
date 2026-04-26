@extends('layouts.app', ['title' => 'Access Control | Tailor'])

@section('content')
    <style>
        .access-shell {
            border-radius: 1rem;
            padding: 0.85rem 0.4rem 0.4rem;
            background: #ffffff;
            border: 1px solid rgba(193, 153, 80, 0.16);
        }

        .access-search {
            max-width: 430px !important;
            margin-left: auto;
        }

        .access-search .form-control {
            min-height: 48px;
            padding-top: 0.6rem;
            padding-bottom: 0.6rem;
            border-radius: 4px !important;
        }

        .access-table-wrap {
            overflow-x: auto;
            padding-bottom: 0.2rem;
            scrollbar-width: thin;
            scrollbar-color: rgba(197, 150, 47, 0.65) rgba(197, 150, 47, 0.08);
        }

        .access-table-wrap::-webkit-scrollbar {
            height: 10px;
        }

        .access-table-wrap::-webkit-scrollbar-track {
            background: rgba(197, 150, 47, 0.08);
            border-radius: 999px;
        }

        .access-table-wrap::-webkit-scrollbar-thumb {
            background: linear-gradient(90deg, rgba(215, 167, 44, 0.95), rgba(184, 135, 25, 0.95));
            border-radius: 999px;
        }

        .access-table {
            margin-bottom: 0;
            min-width: 920px;
            border-collapse: separate;
            border-spacing: 0;
        }

        .access-table thead th {
            color: #ffffff;
            font-size: 0.84rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 700;
            background: #111111;
            border-bottom: 1px solid rgba(0, 0, 0, 0.14);
            white-space: nowrap;
            padding: 0.95rem 1rem;
            vertical-align: middle;
        }

        .access-table thead th:first-child {
            border-top-left-radius: 0.85rem;
        }

        .access-table thead th:last-child {
            border-top-right-radius: 0.85rem;
        }

        .access-table tbody td {
            color: #222222;
            padding: 1.05rem 1rem;
            border-bottom: 1px solid rgba(201, 166, 101, 0.22);
            vertical-align: middle;
            background: rgba(255, 252, 245, 0.94);
        }

        .access-table tbody tr:hover td {
            background: #fffaf1;
        }

        .access-table tbody td:nth-child(3),
        .access-table tbody td:nth-child(4) {
            white-space: nowrap;
        }

        .access-edit-btn {
            min-width: 76px;
            min-height: 40px;
            border-radius: 10px !important;
            border-color: rgba(200, 155, 44, 0.26) !important;
            background: #ffffff;
        }
    </style>

    <div class="d-flex justify-content-end mb-4">
        <a href="{{ route('admin.users.create') }}" class="btn btn-tailor rounded-4 px-4 py-3">Create New Tailor and Manager</a>
    </div>

    <div class="card-tailor rounded-4 p-4 p-lg-5 access-shell">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-4">
            <div>
                <h2 class="fw-bold mb-1">Access Control</h2>
                <p class="text-secondary mb-0">Manage all system accounts, roles, and profile updates from one place.</p>
            </div>

            <form method="GET" action="{{ route('admin.users.index') }}" class="w-100 access-search" id="access-control-search-form">
                <!-- <label for="search" class="form-label">Search</label> -->
                <input type="text" id="search" name="search" value="{{ $filters['search'] }}" class="form-control" placeholder="Search by name, email, or role">
            </form>
        </div>

        <div class="table-responsive access-table-wrap">
            <table class="table align-middle mb-0 access-table">
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
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-dark px-3 py-2 access-edit-btn">Edit</a>
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
