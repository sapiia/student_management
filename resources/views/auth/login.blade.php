<x-layouts.app title="Login">
    <section class="auth-shell">
        <div class="auth-heading">
            <div class="auth-presented">
                <span>presented by</span>
                <span class="auth-logo-mark">SM</span>
            </div>
            <h1>Login to your account</h1>
            <p>Access your school workspace for classes, attendance, student records, and performance reports.</p>
        </div>

        <div class="auth-grid">
            <form class="auth-form" method="POST" action="{{ route('login.store') }}">
                @csrf
                <label class="sr-only" for="email">Email</label>
                <input id="email" class="auth-input" type="email" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>

                <label class="sr-only" for="password">Password</label>
                <input id="password" class="auth-input" type="password" name="password" placeholder="Password" required>

                <button class="auth-submit" type="submit">
                    <span>Login to your account</span>
                    <span aria-hidden="true">-></span>
                </button>

                <a class="auth-inline-link auth-forgot" href="#">Forgot Password?</a>
            </form>

            <div class="auth-divider" aria-hidden="true">/</div>

            <div class="auth-socials" aria-label="Social sign in options">
                <button class="auth-social-btn" type="button">
                    <span class="auth-social-icon">G</span>
                    <span>Sign in with Google</span>
                </button>
                <button class="auth-social-btn" type="button">
                    <span class="auth-social-icon">f</span>
                    <span>Sign in with Facebook</span>
                </button>
            </div>
        </div>

        <a class="auth-inline-link auth-bottom-link" href="{{ route('register') }}">Create an account?</a>
    </section>
</x-layouts.app>
