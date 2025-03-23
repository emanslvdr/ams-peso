<x-guest-layout>
    <div class="container {{ !empty($showRegister) ? 'active' : '' }}">
        <!-- Session Status -->
<x-auth-session-status class="mb-4" :status="session('status')" />

<div class="form-box login">
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <a href="/" class="home">AMS</a>

       
        <!-- Email Address -->
        <div class="input-box">
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                placeholder="Email">
            <x-input-error :messages="$errors->get('email')" class="error-message" />
            @if (!$errors->has('email'))
                <i class='bx bxs-user'></i>
            @endif
        </div>

        <!-- Password -->
        <div class="input-box">
            <input id="password" type="password" name="password" required placeholder="Password"
                autocomplete="current-password">
            <x-input-error :messages="$errors->get('password')" class="error-message" />
            @if (!$errors->has('password'))
                <i class='bx bxs-lock-alt'></i>
            @endif
        </div>

        <!-- Forgot Password Link -->
        <div class="forgot-link mt-2">
            @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
            @endif
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn mt-4">
            {{ __('Login') }}
        </button>
    </form>
</div>

<div class="form-box register">
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <h1>Create account</h1>

        <!-- Name -->
        <div class="input-box">
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="Name" autocomplete="name">
            <x-input-error :messages="$errors->get('name')" class="error-message" />
            @if (!$errors->has('name'))
                <i class='bx bxs-user'></i>
            @endif
        </div>

        <!-- Email Address -->
        <div class="input-box">
            <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="Email" autocomplete="username">
            <x-input-error :messages="$errors->get('email')" class="error-message" />
            @if (!$errors->has('email'))
                <i class='bx bxs-envelope'></i>
            @endif
        </div>

        <!-- Password -->
        <div class="input-box">
            <input id="password" type="password" name="password" required placeholder="Password" autocomplete="new-password">
            <x-input-error :messages="$errors->get('password')" class="error-message" />
            @if (!$errors->has('password'))
                <i class='bx bxs-lock-alt'></i>
            @endif
        </div>
       
            
        <!-- Confirm Password -->
        <div class="input-box">
            <input id="password_confirmation" type="password" name="password_confirmation" required placeholder="Confirm password" autocomplete="new-password">
            <x-input-error :messages="$errors->get('password_confirmation')" class="error-message" />
        </div>
        

        <!-- Submit Button -->
        <button type="submit" class="btn mt-4">
            {{ __('Register') }}
        </button>
    </form>
</div>


        <div class="toggle-box">
            <div class="toggle-panel toggle-left">
                <h1>Hello, Welcome!</h1>
                <p>Don't have an account?</p>
                <button class="btn register-btn">Register</button>
            </div>
            <div class="toggle-panel toggle-right">
                <a href="/" class="home2">AMS</a>
                <p>Already have an account?</p>
                <button class="btn login-btn">Login</button>
            </div>
        </div>
    </div>
</x-guest-layout>
