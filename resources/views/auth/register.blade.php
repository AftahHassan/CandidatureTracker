<x-guest-layout>
<div class="auth-layout">

    {{-- ─── LEFT PANEL ─────────────────────────────────────── --}}
    <div class="auth-left">
        <div class="auth-left-content">
            <h1>Accelerate Your Career Journey</h1>
            <p>Join thousands of professionals using CandidatureTracker to streamline their job search and land their dream roles.</p>

            <div class="auth-feature">
                <div class="auth-feature-icon">📊</div>
                <div class="auth-feature-text">
                    <h4>Visual Pipeline</h4>
                    <p>Manage applications in a clean Kanban interface.</p>
                </div>
            </div>

            <div class="auth-feature">
                <div class="auth-feature-icon">📈</div>
                <div class="auth-feature-text">
                    <h4>Success Analytics</h4>
                    <p>Track your interview conversion rates and progress.</p>
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

            <h2>Create your account</h2>
            <p class="auth-sub">Start managing your applications today.</p>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- Name --}}
                <div class="form-group">
                    <label class="form-label" for="name">Full Name</label>
                    <div class="input-icon-wrap">
                        <span class="input-icon">👤</span>
                        <input id="name" type="text" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="John Doe"
                               value="{{ old('name') }}" required autofocus>
                    </div>
                    @error('name')
                        <div class="form-error">⚠ {{ $message }}</div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="form-group">
                    <label class="form-label" for="email">Work Email</label>
                    <div class="input-icon-wrap">
                        <span class="input-icon">✉</span>
                        <input id="email" type="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="name@company.com"
                               value="{{ old('email') }}" required>
                    </div>
                    @error('email')
                        <div class="form-error">⚠ {{ $message }}</div>
                    @enderror
                </div>

                {{-- Passwords --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="password">Password</label>
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

                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">Confirm Password</label>
                        <div class="input-icon-wrap">
                            <span class="input-icon">🔁</span>
                            <input id="password_confirmation" type="password"
                                   name="password_confirmation"
                                   class="form-control"
                                   placeholder="••••••••" required>
                        </div>
                    </div>
                </div>

                {{-- Terms --}}
                <div class="checkbox-group">
                    <input type="checkbox" id="terms" required>
                    <label for="terms">
                        I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-full btn-lg">
                    Create Account
                </button>
            </form>

            <div class="auth-divider">or sign up with</div>

            <div class="auth-social-row">
                <button class="auth-social-btn">🔵 Google</button>
                <button class="auth-social-btn">🔷 LinkedIn</button>
            </div>

            <div class="auth-footer">
                Already have an account? <a href="{{ route('login') }}">Log in</a>
            </div>

        </div>
    </div>

</div>
</x-guest-layout>