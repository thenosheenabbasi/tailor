@extends('layouts.app', ['title' => 'Access Control | Tailor'])

@section('content')
    <style>
        .access-page {
            display: grid;
            gap: 1rem;
            min-width: 0;
        }

        .credentials-panel {
            padding: 0.9rem;
            background: #ffffff;
            border: 1px solid rgba(17, 17, 17, 0.08);
            color: #111111;
            box-shadow: 0 18px 36px rgba(17, 17, 17, 0.14);
        }

        .credentials-panel h2,
        .credentials-panel .text-secondary,
        .credentials-panel strong {
            color: #111111 !important;
        }

        .credentials-code {
            font-family: Consolas, Monaco, monospace;
            font-size: 0.84rem;
            background: #ffffff;
            border: 1px solid rgba(17, 17, 17, 0.08);
            border-radius: 0.9rem;
            padding: 0.85rem 0.95rem;
            color: #111111;
        }

        .access-top-action {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 0.15rem;
        }

        .access-top-action .btn {
            min-width: 180px;
        }

        .access-shell {
            padding: 1rem;
            max-width: 100%;
            overflow: hidden;
            background: #ffffff;
            border-color: rgba(17, 17, 17, 0.08);
            box-shadow: 0 10px 24px rgba(17, 17, 17, 0.08);
        }

        .access-toolbar {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(280px, 380px);
            gap: 0.85rem;
            align-items: end;
            margin-bottom: 1rem;
        }

        .access-toolbar-title {
            font-size: 0.9rem;
            margin-bottom: 0.18rem;
        }

        .access-toolbar .text-secondary {
            color: #a99e8f !important;
        }

        .access-search .form-control {
            min-height: 2.5rem;
            padding-left: 1rem;
            background: #ffffff;
            border-color: rgba(17, 17, 17, 0.12);
            color: var(--tailor-text);
            box-shadow: none;
            border-radius: 0.7rem !important;
        }

        .access-search .form-control::placeholder {
            color: #8f8577;
        }

        .access-search .form-control:focus {
            background: #ffffff;
            border-color: rgba(17, 17, 17, 0.28);
            box-shadow: 0 0 0 0.18rem rgba(17, 17, 17, 0.08);
        }

        .access-table-wrap {
            overflow-x: auto;
            overflow-y: hidden;
            padding-bottom: 0.25rem;
            scrollbar-width: thin;
            scrollbar-color: rgba(181, 139, 59, 0.7) rgba(181, 139, 59, 0.08);
            max-width: 100%;
        }

        .access-table-wrap::-webkit-scrollbar {
            height: 10px;
        }

        .access-table-wrap::-webkit-scrollbar-track {
            background: rgba(181, 139, 59, 0.08);
            border-radius: 999px;
        }

        .access-table-wrap::-webkit-scrollbar-thumb {
            background: linear-gradient(90deg, #d2b26d, #b58b3b);
            border-radius: 999px;
        }

        .access-table {
            min-width: 920px;
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
            color: var(--tailor-text);
        }

        .access-table thead th {
            padding: 0.82rem 0.95rem;
            color: #ffffff;
            background: linear-gradient(180deg, #1a1a1a 0%, #111111 100%);
            border-bottom: 1px solid rgba(215, 154, 30, 0.2);
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .access-table thead th:first-child {
            border-top-left-radius: 1rem;
        }

        .access-table thead th:last-child {
            border-top-right-radius: 1rem;
        }

        .access-table tbody td {
            padding: 0.95rem 1rem;
            color: var(--tailor-text);
            background: #ffffff;
            border-bottom: 1px solid rgba(17, 17, 17, 0.08);
            vertical-align: middle;
            font-size: 0.86rem;
        }

        .access-table tbody td:last-child {
            text-align: center;
        }

        .access-table tbody tr:nth-child(even) td {
            background: #fafafa;
        }

        .access-table tbody tr:hover td {
            background: #f3f3f3;
        }

        .access-table tbody tr:hover td:first-child {
            box-shadow: inset 3px 0 0 #111111;
        }

        .access-role {
            display: inline-flex;
            align-items: center;
            padding: 0.42rem 0.8rem;
            border-radius: 0.45rem;
            background: #f5f5f5;
            border: 1px solid rgba(17, 17, 17, 0.08);
            font-size: 0.78rem;
            text-transform: capitalize;
            color: #111111;
        }

        .access-edit-btn {
            width: 3.15rem !important;
            min-width: 3.15rem !important;
            height: 3.15rem !important;
            min-height: 3.15rem !important;
            padding: 0 !important;
            font-size: 0.82rem;
            color: #111111 !important;
            border: 1px solid rgba(17, 17, 17, 0.14) !important;
            background: transparent !important;
            border-radius: 0.9rem !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: none !important;
            line-height: 1 !important;
            opacity: 1 !important;
        }

        .access-edit-btn:hover,
        .access-edit-btn:focus {
            color: #111111 !important;
            background: rgba(17, 17, 17, 0.03) !important;
            border: 1px solid rgba(17, 17, 17, 0.18) !important;
            box-shadow: none !important;
        }

        .access-edit-btn svg {
            width: 2rem !important;
            height: 2rem !important;
            display: block;
            color: #000000 !important;
            stroke: #000000 !important;
            fill: none !important;
            stroke-width: 2.7;
            stroke-linecap: round;
            stroke-linejoin: round;
            opacity: 1 !important;
            visibility: visible !important;
        }

        .access-table tbody .text-secondary {
            color: #9c9182 !important;
        }

        .access-role.admin {
            background: transparent;
            border-color: rgba(17, 17, 17, 0.14);
            color: #111111;
        }

        .access-role.manager {
            background: #efefef;
            border-color: rgba(17, 17, 17, 0.14);
            color: #111111;
        }

        .access-role.user {
            background: #f7f7f7;
            border-color: rgba(17, 17, 17, 0.08);
            color: #111111;
        }

        .access-modal {
            position: fixed;
            inset: 0;
            z-index: 1050;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .access-modal.is-open {
            display: flex;
        }

        .access-modal-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(12, 10, 8, 0.52);
            backdrop-filter: blur(4px);
        }

        .access-modal-dialog {
            position: relative;
            width: min(100%, 860px);
            padding: 0.95rem;
            border-radius: 1rem;
            background: #ffffff;
            border: 1px solid rgba(200, 155, 44, 0.16);
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.18);
        }

        .access-modal-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 0.8rem;
            margin-bottom: 0.8rem;
        }

        .access-modal-title {
            margin: 0;
            font-size: 1.05rem;
        }

        .access-modal-copy {
            margin: 0.2rem 0 0;
            color: var(--tailor-muted);
            font-size: 0.82rem;
        }

        .access-modal-close {
            width: 2.2rem;
            height: 2.2rem;
            border: 0;
            border-radius: 999px;
            background: transparent;
            color: #40362b;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .account-modal-form {
            display: grid;
            gap: 0.85rem;
        }

        .account-modal-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.8rem;
            align-items: stretch;
        }

        .account-modal-grid > div {
            min-width: 0;
        }

        .account-modal-card {
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-height: 100%;
            padding: 0.85rem 0.95rem;
            border-radius: 0.8rem;
            background: #ffffff;
            border: 1px solid rgba(17, 17, 17, 0.08);
        }

        .account-modal-card h3 {
            font-size: 0.96rem;
            margin-bottom: 0.2rem;
        }

        .account-modal-form .form-label {
            margin-bottom: 0.35rem;
            font-size: 0.68rem;
            letter-spacing: 0.14em;
        }

        .account-modal-form .form-control,
        .account-modal-form .form-select {
            min-height: 2.7rem;
            padding: 0.62rem 0.82rem;
            font-size: 0.88rem;
            border-radius: 0.8rem !important;
        }

        .password-field-wrap {
            position: relative;
        }

        .password-toggle-btn {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            width: 1.65rem;
            height: 1.65rem;
            padding: 0;
            border: 0;
            background: transparent;
            color: #444444;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .password-toggle-btn svg {
            width: 0.9rem;
            height: 0.9rem;
        }

        .account-modal-submit {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.8rem;
            padding: 0.85rem 0.95rem;
            border-radius: 0.8rem;
            background: #ffffff;
            border: 1px solid rgba(17, 17, 17, 0.08);
        }

        .account-modal-submit .btn {
            margin-left: auto;
        }

        .account-modal-submit .small {
            font-size: 0.64rem;
            letter-spacing: 0.12em;
        }

        .account-modal-submit .fw-semibold {
            font-size: 0.88rem;
        }

        @media (max-width: 991.98px) {
            .access-toolbar {
                grid-template-columns: 1fr;
            }

            .account-modal-grid,
            .account-modal-submit {
                grid-template-columns: 1fr;
                flex-direction: column;
                align-items: flex-start;
            }

            .account-modal-submit .btn,
            .access-modal-dialog {
                width: 100%;
            }
        }

        @media (max-width: 575.98px) {
            .access-page {
                gap: 0.72rem;
            }

            .credentials-panel,
            .access-shell {
                padding: 0.82rem;
                border-radius: 0.85rem;
            }

            .access-top-action .btn {
                width: 100%;
                min-width: 0;
            }

            .access-toolbar {
                gap: 0.62rem;
                margin-bottom: 0.72rem;
            }

            .access-toolbar-title {
                font-size: 0.86rem;
            }

            .access-toolbar .text-secondary {
                font-size: 0.76rem;
                line-height: 1.45;
            }

            .access-table-wrap {
                overflow: visible;
                padding-bottom: 0;
            }

            .access-table,
            .access-table thead,
            .access-table tbody,
            .access-table tr,
            .access-table td {
                display: block;
                width: 100%;
                min-width: 0;
            }

            .access-table thead {
                position: absolute;
                width: 1px;
                height: 1px;
                padding: 0;
                margin: -1px;
                overflow: hidden;
                clip: rect(0, 0, 0, 0);
                white-space: nowrap;
                border: 0;
            }

            .access-table tbody {
                display: grid;
                gap: 0.62rem;
            }

            .access-table tbody tr {
                padding: 0.72rem;
                border: 1px solid rgba(17, 17, 17, 0.08);
                border-radius: 0.78rem;
                background: #ffffff;
                box-shadow: 0 8px 18px rgba(17, 17, 17, 0.045);
            }

            .access-table tbody td,
            .access-table tbody tr:nth-child(even) td,
            .access-table tbody tr:hover td {
                display: grid;
                grid-template-columns: minmax(74px, 0.38fr) minmax(0, 1fr);
                align-items: center;
                gap: 0.55rem;
                padding: 0.34rem 0;
                border-bottom: 0;
                background: transparent !important;
                font-size: 0.8rem;
                line-height: 1.35;
                word-break: break-word;
            }

            .access-table tbody td::before {
                color: rgba(17, 17, 17, 0.52);
                font-size: 0.66rem;
                font-weight: 800;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            .access-table tbody td:nth-child(1)::before { content: "Name"; }
            .access-table tbody td:nth-child(2)::before { content: "Email"; }
            .access-table tbody td:nth-child(3)::before { content: "Role"; }
            .access-table tbody td:nth-child(4)::before { content: "Created"; }
            .access-table tbody td:nth-child(5)::before { content: "Manage"; }

            .access-table tbody td.text-center {
                display: block;
                padding: 1rem 0.25rem;
            }

            .access-table tbody td.text-center::before {
                content: none;
            }

            .access-edit-btn {
                width: 40px;
                height: 40px;
                border-radius: 0.72rem !important;
            }

            .access-modal-dialog {
                width: min(100%, calc(100vw - 1rem));
                border-radius: 1rem;
            }
        }

        @media (max-width: 380px) {
            .access-table tbody td,
            .access-table tbody tr:nth-child(even) td,
            .access-table tbody tr:hover td {
                grid-template-columns: 1fr;
                gap: 0.15rem;
            }
        }
    </style>

    <div class="access-page">
        @if (session('created_account_credentials'))
            @php($createdAccount = session('created_account_credentials'))
            <div class="credentials-panel">
                <h2 class="fw-bold mb-1">New Account Credentials</h2>
                <p class="text-secondary mb-3">These credentials are shown once after account creation or password update.</p>
                <div class="credentials-code">
                    <div><strong>Name:</strong> {{ $createdAccount['name'] }}</div>
                    <div><strong>Email:</strong> {{ $createdAccount['email'] }}</div>
                    <div><strong>Role:</strong> {{ ucfirst($createdAccount['role']) }}</div>
                    <div><strong>Password:</strong> {{ $createdAccount['password'] }}</div>
                </div>
            </div>
        @endif

        <div class="access-top-action">
            <button type="button" class="btn btn-tailor px-4" data-open-access-modal="create-user-modal">Add New Account</button>
        </div>

        <section class="card-tailor access-shell">
            <div class="access-toolbar">
                <div>
                    <h3 class="access-toolbar-title">Account Directory</h3>
                    <p class="text-secondary mb-0">Review all accounts, roles, and creation dates.</p>
                </div>

                <form method="GET" action="{{ route('admin.users.index') }}" class="access-search" id="access-control-search-form">
                    <input type="text" id="search" name="search" value="{{ $filters['search'] }}" class="form-control" placeholder="Search by name, email, or role">
                </form>
            </div>

            <div class="table-responsive access-table-wrap">
                <table class="table align-middle access-table">
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
                                <td class="fw-semibold">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td><span class="access-role {{ $user->role }}">{{ $user->role }}</span></td>
                                <td>{{ $user->created_at?->format('d M Y') }}</td>
                                <td>
                                    <button
                                        type="button"
                                        class="btn btn-outline-dark access-edit-btn"
                                        data-open-access-modal="edit-user-modal"
                                        data-user-id="{{ $user->id }}"
                                        data-user-name="{{ $user->name }}"
                                        data-user-email="{{ $user->email }}"
                                        data-user-role="{{ $user->role }}"
                                        aria-label="Edit {{ $user->name }}"
                                        title="Edit {{ $user->name }}"
                                    >
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M3 17.25V21h3.75L19.81 7.94l-3.75-3.75L3 17.25Z"></path>
                                            <path d="m14.06 4.19 3.75 3.75"></path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-secondary">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <div class="access-modal" id="create-user-modal" aria-hidden="true">
        <div class="access-modal-backdrop" data-close-access-modal></div>
        <div class="access-modal-dialog">
            <div class="access-modal-head">
                <div>
                    <h3 class="access-modal-title">Add New Account</h3>
                    <p class="access-modal-copy">Create a new admin, manager, or user from Access Control.</p>
                </div>
                <button type="button" class="access-modal-close" aria-label="Close create account modal" data-close-access-modal>&times;</button>
            </div>

            <form action="{{ route('admin.users.store') }}" method="POST" class="account-modal-form">
                @csrf
                <input type="hidden" name="form_mode" value="create">

                <div class="account-modal-grid">
                    <div>
                        <label for="create_name" class="form-label">Name</label>
                        <input type="text" id="create_name" name="name" value="{{ old('form_mode') === 'create' ? old('name') : '' }}" class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="create_email" class="form-label">Email</label>
                        <input type="email" id="create_email" name="email" value="{{ old('form_mode') === 'create' ? old('email') : '' }}" class="form-control @error('email') is-invalid @enderror" required>
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="create_role" class="form-label">Role</label>
                        <select id="create_role" name="role" class="form-select @error('role') is-invalid @enderror" required>
                            @foreach ($roles as $role)
                                <option value="{{ $role }}" @selected((old('form_mode') === 'create' ? old('role', \App\Models\User::ROLE_USER) : \App\Models\User::ROLE_USER) === $role)>{{ ucfirst($role) }}</option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="account-modal-card">
                        <h3>Account Access</h3>
                        <p class="text-secondary mb-0">Select the role first, then save the account. Credentials will still be shown once after creation.</p>
                    </div>

                    <div>
                        <label for="create_password" class="form-label">Password</label>
                        <div class="password-field-wrap">
                            <input type="password" id="create_password" name="password" class="form-control pe-5 @error('password') is-invalid @enderror" required>
                            <button type="button" class="password-toggle-btn" data-password-toggle="create_password" aria-label="Show password">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="create_password_confirmation" class="form-label">Confirm Password</label>
                        <div class="password-field-wrap">
                            <input type="password" id="create_password_confirmation" name="password_confirmation" class="form-control pe-5" required>
                            <button type="button" class="password-toggle-btn" data-password-toggle="create_password_confirmation" aria-label="Show password confirmation">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="account-modal-submit">
                    <div>
                        <div class="small text-uppercase">Security Note</div>
                        <div class="fw-semibold">Credentials remain generated and submitted exactly as before.</div>
                    </div>
                    <button type="submit" class="btn btn-tailor">Create Account</button>
                </div>
            </form>
        </div>
    </div>

    <div class="access-modal" id="edit-user-modal" aria-hidden="true">
        <div class="access-modal-backdrop" data-close-access-modal></div>
        <div class="access-modal-dialog">
            <div class="access-modal-head">
                <div>
                    <h3 class="access-modal-title">Edit Account</h3>
                    <p class="access-modal-copy">Edit basic account information, role assignment, and password access from one place.</p>
                </div>
                <button type="button" class="access-modal-close" aria-label="Close edit account modal" data-close-access-modal>&times;</button>
            </div>

            <form id="edit-user-form" action="{{ route('admin.users.update', old('edit_user_id', $users->first()?->id ?? 1)) }}" method="POST" class="account-modal-form">
                @csrf
                @method('PATCH')
                <input type="hidden" name="form_mode" value="edit">
                <input type="hidden" id="edit_user_id" name="edit_user_id" value="{{ old('edit_user_id') }}">

                <div class="account-modal-grid">
                    <div>
                        <label for="edit_name" class="form-label">Name</label>
                        <input type="text" id="edit_name" name="name" value="{{ old('form_mode') === 'edit' ? old('name') : '' }}" class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="email" id="edit_email" name="email" value="{{ old('form_mode') === 'edit' ? old('email') : '' }}" class="form-control @error('email') is-invalid @enderror" required>
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="edit_role" class="form-label">Role</label>
                        <select id="edit_role" name="role" class="form-select @error('role') is-invalid @enderror" required>
                            @foreach ($roles as $role)
                                <option value="{{ $role }}" @selected(old('form_mode') === 'edit' && old('role') === $role)>{{ ucfirst($role) }}</option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="account-modal-card">
                        <h3>Current Account</h3>
                        <p class="mb-1 fw-semibold" id="edit_current_name">{{ old('form_mode') === 'edit' ? old('name') : '' }}</p>
                        <p class="text-secondary mb-0" id="edit_current_email">{{ old('form_mode') === 'edit' ? old('email') : '' }}</p>
                    </div>

                    <div>
                        <label for="edit_password" class="form-label">New Password</label>
                        <div class="password-field-wrap">
                            <input type="password" id="edit_password" name="password" class="form-control pe-5 @error('password') is-invalid @enderror" placeholder="Leave blank to keep current password">
                            <button type="button" class="password-toggle-btn" data-password-toggle="edit_password" aria-label="Show password">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="edit_password_confirmation" class="form-label">Confirm New Password</label>
                        <div class="password-field-wrap">
                            <input type="password" id="edit_password_confirmation" name="password_confirmation" class="form-control pe-5" placeholder="Repeat new password">
                            <button type="button" class="password-toggle-btn" data-password-toggle="edit_password_confirmation" aria-label="Show password confirmation">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="account-modal-submit">
                    <div>
                        <div class="small text-uppercase">Update Summary</div>
                        <div class="fw-semibold">Save updated account details and optional password changes.</div>
                    </div>
                    <button type="submit" class="btn btn-tailor">Update Account</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        (() => {
            const form = document.getElementById('access-control-search-form');
            const searchInput = document.getElementById('search');
            const createModal = document.getElementById('create-user-modal');
            const editModal = document.getElementById('edit-user-modal');
            const openButtons = document.querySelectorAll('[data-open-access-modal]');
            const closeButtons = document.querySelectorAll('[data-close-access-modal]');
            const editForm = document.getElementById('edit-user-form');
            const editName = document.getElementById('edit_name');
            const editEmail = document.getElementById('edit_email');
            const editRole = document.getElementById('edit_role');
            const editUserId = document.getElementById('edit_user_id');
            const editCurrentName = document.getElementById('edit_current_name');
            const editCurrentEmail = document.getElementById('edit_current_email');
            const shouldOpenCreate = @json(old('form_mode') === 'create' && $errors->any());
            const shouldOpenEdit = @json(old('form_mode') === 'edit' && $errors->any());

            if (!form) {
                return;
            }

            let timeoutId;

            searchInput?.addEventListener('input', () => {
                window.clearTimeout(timeoutId);
                timeoutId = window.setTimeout(() => form.submit(), 300);
            });

            const openModal = (modal) => {
                if (!modal) {
                    return;
                }

                modal.classList.add('is-open');
                modal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            };

            const closeModal = (modal) => {
                if (!modal) {
                    return;
                }

                modal.classList.remove('is-open');
                modal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
            };

            const setEditFormData = ({ userId, userName, userEmail, userRole }) => {
                if (!editForm) {
                    return;
                }

                const actionTemplate = @json(url('/admin/users'));
                editForm.action = `${actionTemplate}/${userId}`;
                if (editUserId) editUserId.value = userId ?? '';
                if (editName) editName.value = userName ?? '';
                if (editEmail) editEmail.value = userEmail ?? '';
                if (editRole) editRole.value = userRole ?? 'user';
                if (editCurrentName) editCurrentName.textContent = userName ?? '';
                if (editCurrentEmail) editCurrentEmail.textContent = userEmail ?? '';
            };

            openButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    const modalId = button.dataset.openAccessModal;
                    const modal = document.getElementById(modalId);

                    if (modalId === 'edit-user-modal') {
                        setEditFormData({
                            userId: button.dataset.userId,
                            userName: button.dataset.userName,
                            userEmail: button.dataset.userEmail,
                            userRole: button.dataset.userRole,
                        });
                    }

                    openModal(modal);
                });
            });

            closeButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    closeModal(button.closest('.access-modal'));
                });
            });

            document.addEventListener('keydown', (event) => {
                if (event.key !== 'Escape') {
                    return;
                }

                closeModal(createModal);
                closeModal(editModal);
            });

            document.querySelectorAll('[data-password-toggle]').forEach((button) => {
                button.addEventListener('click', () => {
                    const input = document.getElementById(button.dataset.passwordToggle);

                    if (!input) {
                        return;
                    }

                    input.type = input.type === 'password' ? 'text' : 'password';
                });
            });

            if (shouldOpenCreate) {
                openModal(createModal);
            }

            if (shouldOpenEdit) {
                setEditFormData({
                    userId: @json(old('edit_user_id')),
                    userName: @json(old('name')),
                    userEmail: @json(old('email')),
                    userRole: @json(old('role')),
                });
                openModal(editModal);
            }
        })();
    </script>
@endsection
