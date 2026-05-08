@extends('layouts.app', ['title' => 'Edit Profile | Tailor'])

@section('content')
    <style>
        .profile-edit-page {
            max-width: 920px;
            margin: 0 auto;
            display: grid;
            gap: 0.9rem;
        }

        .profile-edit-shell {
            padding: 0.9rem;
        }

        .profile-edit-header {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 0.75rem;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .profile-edit-kicker {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            color: #8a6c34;
            font-size: 0.66rem;
            letter-spacing: 0.15em;
            text-transform: uppercase;
        }

        .profile-edit-kicker::before {
            content: "";
            width: 2rem;
            height: 1px;
            background: linear-gradient(90deg, #d2b26d, transparent);
        }

        .profile-edit-title {
            margin: 0.25rem 0 0.2rem;
            font-size: clamp(1.25rem, 2.2vw, 1.7rem);
            line-height: 1.08;
        }

        .profile-edit-copy {
            margin: 0;
            color: var(--tailor-muted);
            font-size: 0.82rem;
            max-width: 620px;
        }

        .profile-edit-form {
            display: grid;
            gap: 0.85rem;
        }

        .profile-edit-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.8rem;
            align-items: stretch;
        }

        .profile-edit-grid > div {
            min-width: 0;
        }

        .profile-edit-card {
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-height: 100%;
            padding: 0.85rem 0.95rem;
            border-radius: 0.8rem;
            background: #ffffff;
            border: 1px solid rgba(200, 155, 44, 0.14);
        }

        .profile-edit-card h3 {
            font-size: 0.96rem;
            margin-bottom: 0.2rem;
        }

        .profile-edit-form .form-label {
            margin-bottom: 0.35rem;
            font-size: 0.68rem;
            letter-spacing: 0.14em;
        }

        .profile-edit-form .form-control {
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

        .profile-edit-submit {
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

        .profile-edit-submit .btn {
            margin-left: auto;
            min-height: 2.45rem;
            padding: 0.5rem 1rem !important;
            font-size: 0.82rem;
        }

        .profile-edit-submit .small {
            font-size: 0.64rem;
            letter-spacing: 0.12em;
        }

        .profile-edit-submit .fw-semibold {
            font-size: 0.88rem;
        }

        .profile-edit-header .btn {
            min-height: 2.45rem;
            padding: 0.5rem 1rem !important;
            border-radius: 0.8rem !important;
            font-size: 0.8rem;
        }

        @media (max-width: 767.98px) {
            .profile-edit-header,
            .profile-edit-grid,
            .profile-edit-submit {
                grid-template-columns: 1fr;
                flex-direction: column;
                align-items: flex-start;
            }

            .profile-edit-submit .btn,
            .profile-edit-header .btn {
                width: 100%;
                margin-left: 0;
            }
        }
    </style>

    <div class="profile-edit-page">
        <section class="card-tailor profile-edit-shell">
            <div class="profile-edit-header">
                <div>
                    <div class="profile-edit-kicker">Edit Profile</div>
                    <h2 class="profile-edit-title">Update your personal account details.</h2>
                    <p class="profile-edit-copy">Change your name, email, and password here without going into Access Control.</p>
                </div>
                <a href="{{ route('admin.users.profile') }}" class="btn btn-outline-dark">Back to Profile</a>
            </div>

            <form action="{{ route('admin.users.profile.update') }}" method="POST" class="profile-edit-form">
                @csrf
                @method('PATCH')

                <div class="profile-edit-grid">
                    <div>
                        <label for="name" class="form-label">Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $profileUser->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $profileUser->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="profile-edit-card">
                        <h3>Current Role</h3>
                        <p class="mb-0 text-secondary">{{ ucfirst($profileUser->role) }}</p>
                    </div>

                    <div class="profile-edit-card">
                        <h3>Member Since</h3>
                        <p class="mb-0 text-secondary">{{ $profileUser->created_at?->format('d M Y') ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label for="password" class="form-label">New Password</label>
                        <div class="password-field-wrap">
                            <input type="password" id="password" name="password" class="form-control pe-5 @error('password') is-invalid @enderror" placeholder="Leave blank to keep current password">
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
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <div class="password-field-wrap">
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control pe-5" placeholder="Repeat new password">
                            <button type="button" class="password-toggle-btn" data-password-toggle="password_confirmation" aria-label="Show password confirmation">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="profile-edit-submit">
                    <div>
                        <div class="small text-uppercase">Profile Update</div>
                        <div class="fw-semibold">Save your personal details separately from Access Control.</div>
                    </div>
                    <button type="submit" class="btn btn-tailor">Update Profile</button>
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
