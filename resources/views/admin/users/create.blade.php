@extends('layouts.app', ['title' => 'Create Account | Tailor'])

@section('content')
    <style>
        .account-page {
            display: grid;
            gap: 1rem;
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

        .account-shell {
            max-width: 1020px;
            margin: 0 auto;
            padding: 0.9rem;
        }

        .account-header {
            display: grid;
            grid-template-columns: minmax(0, 1.25fr) auto;
            gap: 0.75rem;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .account-kicker {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            color: #8a6c34;
            font-size: 0.66rem;
            letter-spacing: 0.15em;
            text-transform: uppercase;
        }

        .account-kicker::before {
            content: "";
            width: 2rem;
            height: 1px;
            background: linear-gradient(90deg, #d2b26d, transparent);
        }

        .account-title {
            margin: 0.25rem 0 0.2rem;
            font-size: clamp(1.25rem, 2.2vw, 1.7rem);
            line-height: 1.08;
        }

        .account-copy {
            margin: 0;
            color: var(--tailor-muted);
            max-width: 700px;
            font-size: 0.82rem;
        }

        .account-form {
            display: grid;
            gap: 0.85rem;
        }

        .account-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.8rem;
            align-items: stretch;
        }

        .account-grid > div {
            min-width: 0;
        }

        .account-card {
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-height: 100%;
            padding: 0.85rem 0.95rem;
            border-radius: 0.8rem;
            background: #ffffff;
            border: 1px solid rgba(17, 17, 17, 0.08);
        }

        .account-card h3 {
            font-size: 0.96rem;
            margin-bottom: 0.2rem;
        }

        .account-form .form-label {
            margin-bottom: 0.35rem;
            font-size: 0.68rem;
            letter-spacing: 0.14em;
        }

        .account-form .form-control,
        .account-form .form-select {
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

        .account-submit {
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

        .account-submit .btn {
            margin-left: auto;
            min-height: 2.45rem;
            padding: 0.5rem 1rem !important;
            font-size: 0.82rem;
        }

        .account-submit .small {
            font-size: 0.64rem;
            letter-spacing: 0.12em;
        }

        .account-submit .fw-semibold {
            font-size: 0.88rem;
        }

        .account-header .btn {
            min-height: 2.45rem;
            padding: 0.5rem 1rem !important;
            border-radius: 0.8rem !important;
            font-size: 0.8rem;
        }

        @media (max-width: 991.98px) {
            .account-header,
            .account-grid,
            .account-submit {
                grid-template-columns: 1fr;
                flex-direction: column;
                align-items: flex-start;
            }

            .account-submit .btn,
            .account-header .btn {
                width: 100%;
                margin-left: 0;
            }
        }
    </style>

    <div class="account-page">
        @if (session('created_account_credentials'))
            @php($createdAccount = session('created_account_credentials'))
            <div class="credentials-panel">
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

        <section class="card-tailor account-shell">
            <div class="account-header">
                <div>
                    <div class="account-kicker">Create Account</div>
                    <h2 class="account-title">Add a new team member.</h2>
                    <p class="account-copy">Create a new admin, manager, or user from Access Control.</p>
                </div>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-dark px-4 py-3">Back to Access Control</a>
            </div>

            <form action="{{ route('admin.users.store') }}" method="POST" class="account-form">
                @csrf

                <div class="account-grid">
                    <div>
                        <label for="name" class="form-label">Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="role" class="form-label">Role</label>
                        <select id="role" name="role" class="form-select @error('role') is-invalid @enderror" required>
                            @foreach ($roles as $role)
                                <option value="{{ $role }}" @selected(old('role', \App\Models\User::ROLE_USER) === $role)>{{ ucfirst($role) }}</option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="form-label">Password</label>
                        <div class="password-field-wrap">
                            <input type="password" id="password" name="password" class="form-control pe-5 @error('password') is-invalid @enderror" required>
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

                    <div>
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <div class="password-field-wrap">
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control pe-5" required>
                            <button type="button" class="password-toggle-btn" data-password-toggle="password_confirmation" aria-label="Show password confirmation">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="account-card">
                        <h3>Account Access</h3>
                        <p class="text-secondary mb-0">Select the role first, then save the account. The generated credentials panel above still appears only once after creation.</p>
                    </div>
                </div>

                <div class="account-submit">
                    <div>
                        <div class="small text-uppercase">Security Note</div>
                        <div class="fw-semibold">Credentials remain generated and submitted exactly as before.</div>
                    </div>
                    <button type="submit" class="btn btn-tailor px-4 py-3">Create Account</button>
                </div>
            </form>
        </section>
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
