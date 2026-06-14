@extends('layout.app')

@section('title', 'Admin Sign In')
@section('body_class', 'admin-auth-body')

@section('content')
<main class="admin-auth-main">
  <section class="admin-auth-shell">
    <div class="admin-auth-copy">
      <span class="admin-auth-eyebrow">Admin access</span>
      <h1>Sign in to your admin space.</h1>
      <p>Use your admin email and password to continue into the protected dashboard area for the ministry platform.</p>

      <div class="admin-auth-points">
        <div class="admin-auth-point">
          <i class="fa-solid fa-lock"></i>
          <span>Only accounts marked as admins can complete sign-in</span>
        </div>
        <div class="admin-auth-point">
          <i class="fa-solid fa-repeat"></i>
          <span>Session-based authentication with token regeneration</span>
        </div>
        <div class="admin-auth-point">
          <i class="fa-solid fa-house"></i>
          <span>Quick route back to the public website anytime</span>
        </div>
      </div>
    </div>

    <div class="admin-auth-card">
      <div class="admin-auth-card-head">
        <h2>Admin Sign In</h2>
        <p>Welcome back. Enter your details below.</p>
      </div>

      @if (session('status'))
        <div class="form-alert form-alert-success">
          <i class="fa-solid fa-circle-check"></i>
          <span>{{ session('status') }}</span>
        </div>
      @endif

      @if ($errors->any())
        <div class="form-alert form-alert-error">
          <i class="fa-solid fa-circle-exclamation"></i>
          <span>{{ $errors->first() }}</span>
        </div>
      @endif

      <form action="{{ route('admin.signin.store') }}" method="POST" class="admin-auth-form">
        @csrf

        <label class="admin-auth-field">
          <span>Email address</span>
          <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required />
        </label>

        <label class="admin-auth-field">
          <span>Password</span>
          <input type="password" name="password" placeholder="Enter your password" required />
        </label>

        <label class="admin-auth-check">
          <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }} />
          <span>Keep me signed in on this device</span>
        </label>

        <button type="submit" class="primary-button admin-auth-submit">Sign In</button>
      </form>

      <p class="admin-auth-switch">Need an admin account? <a href="{{ route('admin.signup') }}">Create one here</a>.</p>
    </div>
  </section>
</main>
@endsection
