@extends('layout.app')

@section('title', 'Member Sign In')
@section('body_class', 'admin-auth-body')

@section('content')
<main class="admin-auth-main">
  <section class="admin-auth-shell">
    <div class="admin-auth-copy">
      <span class="admin-auth-eyebrow">Member access</span>
      <h1>Welcome back.</h1>
      <p>Sign in with your phone number and password to continue praying, exploring scripture, and staying connected.</p>

      <div class="admin-auth-points">
        <div class="admin-auth-point">
          <i class="fa-solid fa-mobile-screen-button"></i>
          <span>Phone number based sign-in for quick access</span>
        </div>
        <div class="admin-auth-point">
          <i class="fa-solid fa-shield-halved"></i>
          <span>Separated from the admin portal for safer access control</span>
        </div>
        <div class="admin-auth-point">
          <i class="fa-solid fa-door-open"></i>
          <span>Instant return to the main platform after login</span>
        </div>
      </div>
    </div>

    <div class="admin-auth-card">
      <div class="admin-auth-card-head">
        <h2>Member Sign In</h2>
        <p>Enter your phone number and password to continue.</p>
      </div>

      @if ($errors->any())
        <div class="form-alert form-alert-error">
          <i class="fa-solid fa-circle-exclamation"></i>
          <span>{{ $errors->first() }}</span>
        </div>
      @endif

      <form action="{{ route('member.signin.store') }}" method="POST" class="admin-auth-form">
        @csrf

        <label class="admin-auth-field">
          <span>Phone number</span>
          <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="Enter your phone number" required />
        </label>

        <label class="admin-auth-field">
          <span>Password</span>
          <input type="password" name="password" placeholder="Enter your password" required />
        </label>

        <label class="admin-auth-check">
          <input type="checkbox" name="remember" value="1" />
          <span>Keep me signed in</span>
        </label>

        <button type="submit" class="primary-button admin-auth-submit">Sign In</button>
      </form>

      <p class="admin-auth-switch">Need an account? <a href="{{ route('member.signup') }}">Become a member</a>.</p>
    </div>
  </section>
</main>
@endsection
