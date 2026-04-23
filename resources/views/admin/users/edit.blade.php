@extends('layouts.app', ['title' => 'Edit Account | Tailor'])

@section('content')
    <style>
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

    <div class="row justify-content-center">
        <div class="col-xl-8">
            <div class="card-tailor rounded-4 p-4 p-lg-5">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                    <div>
                        <h2 class="fw-bold mb-1">Manage Account</h2>
                        <p class="text-secondary mb-0">Update profile details or set a new password from Access Control.</p>
                    </div>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-dark rounded-4 px-4">Back to Access Control</a>
                </div>

                <form action="{{ route('admin.users.update', $managedUser) }}" method="POST" class="row g-4">
                    @csrf
                    @method('PATCH')

                    <div class="col-md-6">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $managedUser->name) }}" class="form-control rounded-4 @error('name') is-invalid @enderror" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $managedUser->email) }}" class="form-control rounded-4 @error('email') is-invalid @enderror" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="role" class="form-label">Role</label>
                        <select id="role" name="role" class="form-select rounded-4 @error('role') is-invalid @enderror" required>
                            @foreach ($roles as $role)
                                <option value="{{ $role }}" @selected(old('role', $managedUser->role) === $role)>{{ ucfirst($role) }}</option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <div class="surface-muted p-3 h-100 d-flex align-items-center">
                            <div>
                                <div class="small text-uppercase">Current Account</div>
                                <div class="fw-semibold">{{ $managedUser->name }}</div>
                                <div class="text-secondary">{{ $managedUser->email }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="password" class="form-label">New Password</label>
                        <div class="password-field-wrap">
                            <input type="password" id="password" name="password" class="form-control rounded-4 pe-5 @error('password') is-invalid @enderror" placeholder="Leave blank to keep current password">
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
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <div class="password-field-wrap">
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control rounded-4 pe-5" placeholder="Repeat new password">
                            <button type="button" class="password-toggle-btn" data-password-toggle="password_confirmation" aria-label="Show password confirmation">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-tailor rounded-4 px-4">Update Account</button>
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
