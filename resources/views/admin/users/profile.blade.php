@extends('layouts.app', ['title' => 'View Profile | Tailor'])

@section('content')
    <style>
        .profile-page {
            display: grid;
            gap: 0.9rem;
        }

        .profile-shell {
            width: 100%;
            max-width: none;
            margin: 0;
            display: grid;
            gap: 0.9rem;
        }

        .profile-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.8rem;
            padding: 1rem 1.15rem;
        }

        .profile-kicker {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #8a6c34;
            font-size: 0.66rem;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            margin-bottom: 0.15rem;
        }

        .profile-kicker::before {
            content: "";
            width: 1.8rem;
            height: 1px;
            background: linear-gradient(90deg, #d2b26d, transparent);
        }

        .profile-title {
            margin: 0;
            font-size: clamp(1.2rem, 2.1vw, 1.6rem);
            line-height: 1.08;
        }

        .profile-copy {
            margin: 0.2rem 0 0;
            color: var(--tailor-muted);
            font-size: 0.82rem;
        }

        .profile-head .btn {
            min-height: 2.45rem;
            padding: 0.5rem 1rem !important;
            border-radius: 0.8rem !important;
            font-size: 0.8rem;
        }

        .profile-card {
            padding: 1.15rem;
        }

        .profile-top {
            display: grid;
            grid-template-columns: auto minmax(0, 1fr);
            gap: 0.9rem;
            align-items: center;
            margin-bottom: 0.9rem;
        }

        .profile-avatar {
            width: 68px;
            height: 68px;
            border-radius: 50%;
            background: #111111;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .profile-avatar img {
            width: 74%;
            height: 74%;
            object-fit: contain;
        }

        .profile-name {
            margin: 0;
            font-size: 1.05rem;
        }

        .profile-role {
            margin-top: 0.12rem;
            color: var(--tailor-muted);
            font-size: 0.82rem;
            text-transform: capitalize;
        }

        .profile-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.8rem;
        }

        .profile-info {
            padding: 0.85rem 0.95rem;
            border: 1px solid rgba(200, 155, 44, 0.18);
            border-radius: 0.8rem;
            background: #ffffff;
        }

        .profile-label {
            display: block;
            margin-bottom: 0.28rem;
            color: #7c7367;
            font-size: 0.66rem;
            font-weight: 800;
            letter-spacing: 0.13em;
            text-transform: uppercase;
        }

        .profile-value {
            color: #111111;
            font-size: 0.9rem;
            font-weight: 600;
            line-height: 1.4;
            word-break: break-word;
        }

        .profile-modal {
            position: fixed;
            inset: 0;
            z-index: 1050;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .profile-modal.is-open {
            display: flex;
        }

        .profile-modal-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(12, 10, 8, 0.52);
            backdrop-filter: blur(4px);
        }

        .profile-modal-dialog {
            position: relative;
            width: min(100%, 760px);
            padding: 0.95rem;
            border-radius: 1rem;
            background: #ffffff;
            border: 1px solid rgba(200, 155, 44, 0.16);
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.18);
        }

        .profile-modal-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 0.8rem;
            margin-bottom: 0.8rem;
        }

        .profile-modal-title {
            margin: 0;
            font-size: 1.05rem;
        }

        .profile-modal-copy {
            margin: 0.2rem 0 0;
            color: var(--tailor-muted);
            font-size: 0.82rem;
        }

        .profile-modal-close {
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

        .profile-edit-form {
            display: grid;
            gap: 0.85rem;
        }

        .profile-edit-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.8rem;
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
        }

        .profile-edit-submit .small {
            font-size: 0.64rem;
            letter-spacing: 0.12em;
        }

        .profile-edit-submit .fw-semibold {
            font-size: 0.88rem;
        }

        @media (max-width: 767.98px) {
            .profile-head,
            .profile-top,
            .profile-grid,
            .profile-edit-grid,
            .profile-edit-submit {
                grid-template-columns: 1fr;
                flex-direction: column;
                align-items: flex-start;
            }

            .profile-head .btn,
            .profile-edit-submit .btn,
            .profile-modal-dialog {
                width: 100%;
            }
        }
    </style>

    <div class="profile-page">
        <div class="profile-shell">
            <section class="card-tailor profile-head">
                <div>
                    <div class="profile-kicker">View Profile</div>
                    <h2 class="profile-title">Your account details.</h2>
                    <p class="profile-copy">Review your basic information and current access level.</p>
                </div>
                <button type="button" class="btn btn-outline-dark" data-open-profile-edit-modal>Edit Profile</button>
            </section>

            <section class="card-tailor profile-card">
                <div class="profile-top">
                    <div class="profile-avatar" aria-hidden="true">
                        <img src="{{ asset('images/tailor-logo-sidebar.png') }}" alt="Al Handaam Gents Tailoring">
                    </div>
                    <div>
                        <h3 class="profile-name">{{ $profileUser->name }}</h3>
                        <div class="profile-role">{{ $profileUser->role }}</div>
                    </div>
                </div>

                <div class="profile-grid">
                    <div class="profile-info">
                        <span class="profile-label">Full Name</span>
                        <div class="profile-value">{{ $profileUser->name }}</div>
                    </div>
                    <div class="profile-info">
                        <span class="profile-label">Email</span>
                        <div class="profile-value">{{ $profileUser->email }}</div>
                    </div>
                    <div class="profile-info">
                        <span class="profile-label">Role</span>
                        <div class="profile-value">{{ ucfirst($profileUser->role) }}</div>
                    </div>
                    <div class="profile-info">
                        <span class="profile-label">Joined</span>
                        <div class="profile-value">{{ $profileUser->created_at?->format('d M Y') ?? 'N/A' }}</div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <div class="profile-modal" id="profile-edit-modal" aria-hidden="true">
        <div class="profile-modal-backdrop" data-close-profile-edit-modal></div>
        <div class="profile-modal-dialog">
            <div class="profile-modal-head">
                <div>
                    <h3 class="profile-modal-title">Edit Profile</h3>
                    <p class="profile-modal-copy">Update your personal account details without leaving this page.</p>
                </div>
                <button type="button" class="profile-modal-close" aria-label="Close edit profile modal" data-close-profile-edit-modal>&times;</button>
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
                        <div class="fw-semibold">Save your personal details here.</div>
                    </div>
                    <button type="submit" class="btn btn-tailor">Update Profile</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        (() => {
            const modal = document.getElementById('profile-edit-modal');
            const openButton = document.querySelector('[data-open-profile-edit-modal]');
            const closeButtons = document.querySelectorAll('[data-close-profile-edit-modal]');
            const shouldOpenOnLoad = @json($errors->any());

            if (!modal) {
                return;
            }

            const openModal = () => {
                modal.classList.add('is-open');
                modal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            };

            const closeModal = () => {
                modal.classList.remove('is-open');
                modal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
            };

            openButton?.addEventListener('click', openModal);
            closeButtons.forEach((button) => button.addEventListener('click', closeModal));

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && modal.classList.contains('is-open')) {
                    closeModal();
                }
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

            if (shouldOpenOnLoad) {
                openModal();
            }
        })();
    </script>
@endsection
