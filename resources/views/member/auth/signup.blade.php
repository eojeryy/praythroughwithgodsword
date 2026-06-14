@extends('layout.app')

@section('title', 'Become a Member')
@section('body_class', 'admin-auth-body')

@section('content')
<main class="admin-auth-main">
  <section class="admin-auth-shell">
    <div class="admin-auth-copy">
      <span class="admin-auth-eyebrow">Member community</span>
      <h1>Become a member.</h1>
      <p>Join the Pray Through With God’s Word community with your phone number and password so you can return easily any time.</p>

      <div class="admin-auth-points">
        <div class="admin-auth-point">
          <i class="fa-solid fa-user-plus"></i>
          <span>Fast registration with phone number login</span>
        </div>
        <div class="admin-auth-point">
          <i class="fa-solid fa-earth-africa"></i>
          <span>Your country is detected automatically from your connection</span>
        </div>
        <div class="admin-auth-point">
          <i class="fa-solid fa-lock"></i>
          <span>Passwords are securely hashed by Laravel</span>
        </div>
      </div>
    </div>

    <div class="admin-auth-card">
      <div class="admin-auth-card-head">
        <h2>Become a Member</h2>
        <p>Enter your details below to create your member account.</p>
      </div>

      @if ($errors->any())
        <div class="form-alert form-alert-error">
          <i class="fa-solid fa-circle-exclamation"></i>
          <span>{{ $errors->first() }}</span>
        </div>
      @endif

      <form action="{{ route('member.signup.store') }}" method="POST" class="admin-auth-form">
        @csrf

        <label class="admin-auth-field">
          <span>Full name</span>
          <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter your full name" required />
        </label>

        <label class="admin-auth-field">
          <span>Phone number</span>
          <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="Enter your phone number" required />
        </label>

        <label class="admin-auth-field">
          <span>Password</span>
          <input type="password" name="password" placeholder="Create a password" required />
        </label>

        <label class="admin-auth-field">
          <span>Confirm password</span>
          <input type="password" name="password_confirmation" placeholder="Repeat your password" required />
        </label>

        <button type="submit" class="primary-button admin-auth-submit">Become a Member</button>
      </form>

      <p class="admin-auth-switch">Already a member? <a href="{{ route('member.signin') }}">Sign in here</a>.</p>
    </div>
  </section>
</main>
@endsection
