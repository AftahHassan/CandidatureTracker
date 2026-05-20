<x-guest-layout>
<div class="auth-layout">

    {{-- ─── LEFT PANEL ─────────────────────────────────────── --}}
    <div class="auth-left">
        <div class="auth-left-content">
            <h1>Welcome Back to Your Career Hub</h1>
            <p>Pick up right where you left off. Your applications, interviews, and opportunities are waiting for you.</p>

            <div class="auth-feature">
                <div class="auth-feature-icon">🎯</div>
                <div class="auth-feature-text">
                    <h4>Smart Tracking</h4>
                    <p>Never miss a follow-up or interview again.</p>
                </div>
            </div>

            <div class="auth-feature">
                <div class="auth-feature-icon">🗂</div>
                <div class="auth-feature-text">
                    <h4>All in one place</h4>
                    <p>Companies, positions, notes and documents centralized.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ─── RIGHT PANEL ─────────────────────────────────────── --}}
    <div class="auth-right">
        <div class="auth-box">

            <div class="auth-logo">
                <div class="auth-logo-icon">💼</div>
                <span class="auth-logo-name">CandidatureTracker</span>
            </div>

            <h2>Sign in to your account</h2>
            <p class="auth-sub">Enter your credentials to continue.</p>

            {{-- Session errors --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    ❌ {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div class="form-group">
                    <label class="form-label" for="email">Email address</label>
                    <div class="input-icon-wrap">
                        <span class="input-icon">✉</span>
                        <input id="email" type="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="name@company.com"
                               value="{{ old('email') }}" required autofocus>
                    </div>
                    @error('email')
                        <div class="form-error">⚠ {{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="form-group">
                    <label class="form-label" for="password">
                        Password
                        <a href="{{ route('password.request') }}"
                           style="float:right; font-size:12px; color:var(--primary); font-weight:400;">
                           Forgot password?
                        </a>
                    </label>
                    <div class="input-icon-wrap">
                        <span class="input-icon">🔒</span>
                        <input id="password" type="password" name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="••••••••" required>
                    </div>
                    @error('password')
                        <div class="form-error">⚠ {{ $message }}</div>
                    @enderror
                </div>

                {{-- Remember me --}}
                <div class="checkbox-group">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember me for 30 days</label>
                </div>

                <button type="submit" class="btn btn-primary btn-full btn-lg">
                    Sign In
                </button>
            </form>

            <div class="auth-divider">or continue with</div>

            <div class="auth-social-row">
                <button class="auth-social-btn">🔵 Google</button>
                <button class="auth-social-btn">🔷 LinkedIn</button>
            </div>

            <div class="auth-footer">
                Don't have an account? <a href="{{ route('register') }}">Sign up free</a>
            </div>

        </div>
    </div>

</div>
</x-guest-layout>