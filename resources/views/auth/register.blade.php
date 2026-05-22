<x-layouts.app title="Sign up">
    <section class="auth-shell">
        <div class="auth-heading">
            <div class="auth-presented">
                <span>presented by</span>
                <span class="auth-logo-mark">SM</span>
            </div>
            <h1>Create your account</h1>
            <p>Choose your role and join the student management workspace in just a few details.</p>
        </div>

        <div class="auth-grid auth-grid-register">
            <form class="auth-form auth-form-wide" method="POST" action="{{ route('register.store') }}">
                @csrf
                <div class="auth-field-grid">
                    <div>
                        <label class="sr-only" for="name">Name</label>
                        <input id="name" class="auth-input" type="text" name="name" value="{{ old('name') }}" placeholder="Name" required>
                    </div>
                    <div>
                        <label class="sr-only" for="email">Email</label>
                        <input id="email" class="auth-input" type="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
                    </div>
                </div>

                <div class="auth-field-grid">
                    <div>
                        <label class="sr-only" for="role">Role</label>
                        <select id="role" class="auth-input" name="role" required>
                            @foreach(['student' => 'Student', 'teacher' => 'Teacher', 'admin' => 'Admin'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('role', 'student') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="sr-only" for="school_class_id">Class for student accounts</label>
                        <select id="school_class_id" class="auth-input" name="school_class_id">
                            <option value="">Choose class after signup</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" @selected(old('school_class_id') == $class->id)>{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="auth-field-grid">
                    <div>
                        <label class="sr-only" for="password">Password</label>
                        <input id="password" class="auth-input" type="password" name="password" placeholder="Password" required>
                    </div>
                    <div>
                        <label class="sr-only" for="password_confirmation">Confirm password</label>
                        <input id="password_confirmation" class="auth-input" type="password" name="password_confirmation" placeholder="Confirm password" required>
                    </div>
                </div>

                <button class="auth-submit" type="submit">
                    <span>Create your account</span>
                    <span aria-hidden="true">-></span>
                </button>
            </form>

            <div class="auth-divider" aria-hidden="true">/</div>

            <div class="auth-socials" aria-label="Social sign up options">
                <button class="auth-social-btn" type="button">
                    <span class="auth-social-icon">G</span>
                    <span>Sign up with Google</span>
                </button>
                <button class="auth-social-btn" type="button">
                    <span class="auth-social-icon">f</span>
                    <span>Sign up with Facebook</span>
                </button>
            </div>
        </div>

        <a class="auth-inline-link auth-bottom-link" href="{{ route('login') }}">Already have an account?</a>
    </section>
</x-layouts.app>
