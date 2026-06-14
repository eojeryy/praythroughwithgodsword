@extends('layout.app')

@section('title', 'Admin Sign Up')
@section('body_class', 'admin-auth-body')

@section('content')
<main class="admin-auth-main">
  <section class="admin-auth-shell">
    <div class="admin-auth-copy">
      <span class="admin-auth-eyebrow">Admin onboarding</span>
      <h1>Create your admin account.</h1>
      <p>Set up a secure admin profile to manage the PrayThroughWithGodsword.com platform and return here any time to sign in.</p>

      <div class="admin-auth-points">
        <div class="admin-auth-point">
          <i class="fa-solid fa-shield-halved"></i>
          <span>Protected admin-only dashboard route</span>
        </div>
        <div class="admin-auth-point">
          <i class="fa-solid fa-user-lock"></i>
          <span>Password hashing handled by Laravel automatically</span>
        </div>
        <div class="admin-auth-point">
          <i class="fa-solid fa-right-to-bracket"></i>
          <span>Instant sign-in after successful registration</span>
        </div>
      </div>
    </div>

    <div class="admin-auth-card">
      <div class="admin-auth-card-head">
        <h2>Admin Sign Up</h2>
        <p>
          @if ($adminLimitReached)
            The maximum of 2 admin accounts has already been reached.
          @else
            Fill in your details to create an admin account. {{ $adminSlotsRemaining }} admin slot{{ $adminSlotsRemaining === 1 ? '' : 's' }} remaining.
          @endif
        </p>
      </div>

      @if ($errors->any())
        <div class="form-alert form-alert-error">
          <i class="fa-solid fa-circle-exclamation"></i>
          <span>{{ $errors->first() }}</span>
        </div>
      @endif

      @if ($adminLimitReached)
        <div class="form-alert form-alert-error">
          <i class="fa-solid fa-user-shield"></i>
          <span>Admin registration is closed because the super admin account limit has been reached.</span>
        </div>
      @else
        <form action="{{ route('admin.signup.store') }}" method="POST" class="admin-auth-form">
          @csrf

          <label class="admin-auth-field">
            <span>Full name</span>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter your full name" required />
          </label>

          <label class="admin-auth-field">
            <span>Email address</span>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required />
          </label>

          <label class="admin-auth-field">
            <span>Password</span>
            <input type="password" name="password" placeholder="Create a password" required />
          </label>

          <label class="admin-auth-field">
            <span>Confirm password</span>
            <input type="password" name="password_confirmation" placeholder="Repeat your password" required />
          </label>

          <button type="submit" class="primary-button admin-auth-submit">Create Admin Account</button>
        </form>
      @endif

      <p class="admin-auth-switch">Already have an admin account? <a href="{{ route('admin.signin') }}">Sign in here</a>.</p>
    </div>
  </section>
</main>
@endsection
