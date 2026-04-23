@extends('layouts.app', ['title' => 'Create Account | Tailor'])

@section('content')
    <style>
        .credentials-panel {
            background: linear-gradient(180deg, rgba(255, 252, 247, 0.98), rgba(247, 240, 228, 0.96));
            border: 1px solid rgba(197, 150, 47, 0.18);
            box-shadow:
                inset 0 1px 0 rgba(255, 255, 255, 0.85),
                0 16px 36px rgba(94, 74, 28, 0.08);
        }

        .credentials-code {
            font-family: Consolas, Monaco, monospace;
            font-size: 0.92rem;
            background: rgba(255, 255, 255, 0.72);
            border: 1px solid rgba(197, 150, 47, 0.14);
            border-radius: 1rem;
            padding: 1rem 1.1rem;
        }

        .password-field-wrap {
            position: relative;
        }

        .password-toggle-btn {
            position: absolute;
            right: 0.9rem;
            top: 50%;
            transform: translateY(-50%);
            border: 0;
            background: transparent;
            color: #6b5b3c;
            padding: 0;
            width: 1.75rem;
            height: 1.75rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .password-toggle-btn svg {
            width: 1rem;
            height: 1rem;
        }
    </style>

    @if (session('created_account_credentials'))
        @php($createdAccount = session('created_account_credentials'))
        <div class="credentials-panel rounded-4 p-4 mb-4">
            <h2 class="fw-bold mb-1">New Account Credentials</h2>
            <p class="text-secondary mb-3">These credentials are shown once after account creation.</p>
            <div class="credentials-code">
                <div><strong>Name:</strong> {{ $createdAccount['name'] }}</div>
                <div><strong>Email:</strong> {{ $createdAccount['email'] }}</div>
                <div><strong>Role:</strong> {{ ucfirst($createdAccount['role']) }}</div>
                <div><strong>Password:</strong> {{ $createdAccount['password'] }}</div>
            </div>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-xl-8">
            <div class="card-tailor rounded-4 p-4 p-lg-5">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                    <div>
                        <h2 class="fw-bold mb-1">Create Account</h2>
                        <p class="text-secondary mb-0">Create a new admin, manager, or user from Access Control.</p>
                    </div>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-dark rounded-4 px-4">Back to Access Control</a>
                </div>

                <form action="{{ route('admin.users.store') }}" method="POST" class="row g-4">
                    @csrf

                    <div class="col-md-6">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control rounded-4 @error('name') is-invalid @enderror" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control rounded-4 @error('email') is-invalid @enderror" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="role" class="form-label">Role</label>
                        <select id="role" name="role" class="form-select rounded-4 @error('role') is-invalid @enderror" required>
                            @foreach ($roles as $role)
                                <option value="{{ $role }}" @selected(old('role', \App\Models\User::ROLE_USER) === $role)>{{ ucfirst($role) }}</option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <div class="surface-muted p-3 h-100 d-flex align-items-center">
                            <div>
                                <div class="small text-uppercase">Account Access</div>
                                <div class="fw-semibold">Admin, Manager, or User</div>
                                <div class="text-secondary">Select the role before saving.</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="password" class="form-label">Password</label>
                        <div class="password-field-wrap">
                            <input type="password" id="password" name="password" class="form-control rounded-4 pe-5 @error('password') is-invalid @enderror" required>
                            <button type="button" class="password-toggle-btn" data-password-toggle="password" aria-label="Show password">
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

                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <div class="password-field-wrap">
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control rounded-4 pe-5" required>
                            <button type="button" class="password-toggle-btn" data-password-toggle="password_confirmation" aria-label="Show password confirmation">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-tailor rounded-4 px-4">Create Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        (() => {
            document.querySelectorAll('[data-password-toggle]').forEach((button) => {
                button.addEventListener('click', () => {
                    const input = document.getElementById(button.dataset.passwordToggle);

                    if (!input) {
                        return;
                    }

                    input.type = input.type === 'password' ? 'text' : 'password';
                });
            });
        })();
    </script>
@endsection
