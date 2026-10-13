@extends('layout.app')

@section('title', 'Member Sign In')
@section('body_class', 'member-signin-page')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/member-signin.css') }}?v={{ filemtime(public_path('css/member-signin.css')) }}" />
@endpush

@push('scripts')
  <script src="{{ asset('js/member-signin.js') }}?v={{ filemtime(public_path('js/member-signin.js')) }}"></script>
@endpush

@section('content')
<main class="member-signin-main">
  <a href="{{ route('home') }}" class="member-back-link"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Back to home</a>
  <section class="member-signin-shell" aria-labelledby="member-welcome">
    <div class="member-welcome-panel">
      <span class="member-eyebrow"><span aria-hidden="true"></span> A moment for your faith</span>
      <div class="member-welcome-copy">
        <h1 id="member-welcome">Welcome back.<br /><em>Let’s keep praying.</em></h1>
        <p>Make room for God’s Word in your day. Return to scripture, find a prayer for this season, and stay connected in faith.</p>
      </div>
      <ul class="member-benefits">
        <li><i class="fa-solid fa-book-bible" aria-hidden="true"></i><div><strong>Find direction in scripture</strong><span>Explore prayers rooted in God’s Word.</span></div></li>
        <li><i class="fa-solid fa-hands-praying" aria-hidden="true"></i><div><strong>Bring every season to God</strong><span>Find prayer topics for what’s on your heart.</span></div></li>
        <li><i class="fa-solid fa-people-group" aria-hidden="true"></i><div><strong>Join in prayer</strong><span>Stay connected through the live prayer room.</span></div></li>
      </ul>
      <blockquote class="member-scripture">
        <p>“Pray without ceasing.”</p>
        <cite>1 Thessalonians 5:17 · KJV</cite>
      </blockquote>
    </div>

    <div class="member-signin-card">
      <div class="member-form-heading">
        <span class="member-form-eyebrow">Your prayer journey continues</span>
        <h2>Member sign in</h2>
        <p>Welcome back to your prayer community.</p>
      </div>

      @if ($errors->any())
        <div class="form-alert form-alert-error" role="alert">
          <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
          <span>{{ $errors->first() }}</span>
        </div>
      @endif

      <form action="{{ route('member.signin.store') }}" method="POST" class="member-signin-form">
        @csrf

        <div class="member-field">
          <label for="member-phone">Phone number</label>
          <div class="member-input-wrap">
            <i class="fa-solid fa-mobile-screen-button" aria-hidden="true"></i>
            <input id="member-phone" type="tel" name="phone" value="{{ old('phone') }}" autocomplete="username" inputmode="tel" placeholder="Enter your phone number" aria-describedby="member-phone-help @error('phone') member-phone-error @enderror" @error('phone') aria-invalid="true" @enderror required />
          </div>
          <small id="member-phone-help">Use the phone number you registered with.</small>
          @error('phone') <small id="member-phone-error" class="member-field-error">{{ $message }}</small> @enderror
        </div>

        <div class="member-field">
          <label for="member-password">Password</label>
          <div class="member-input-wrap member-password-wrap">
            <i class="fa-solid fa-lock" aria-hidden="true"></i>
            <input id="member-password" type="password" name="password" autocomplete="current-password" placeholder="Enter your password" @error('password') aria-invalid="true" aria-describedby="member-password-error" @enderror required />
            <button type="button" class="member-password-toggle" aria-label="Show password" aria-controls="member-password" aria-pressed="false" hidden><i class="fa-regular fa-eye" aria-hidden="true"></i></button>
          </div>
          @error('password') <small id="member-password-error" class="member-field-error">{{ $message }}</small> @enderror
        </div>

        <label class="member-remember">
          <input type="checkbox" name="remember" value="1" @checked(old('remember')) />
          <span>Keep me signed in</span>
        </label>
        <button type="submit" class="member-signin-submit">Sign in <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></button>
      </form>

      <div class="member-signup-prompt">
        <p>New to our community?</p>
        <a href="{{ route('member.signup') }}">Become a member <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
      </div>
    </div>
  </section>
  <p class="member-signin-note">A little time in prayer. A stronger foundation for your day.</p>
</main>
@endsection
