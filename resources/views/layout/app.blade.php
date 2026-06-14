<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>@yield('title', 'PrayThroughWithGodsword.com')</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
</head>
<body class="@yield('body_class')">
@php
  $isLivePage = request()->routeIs('live');
  $isAdminArea = request()->routeIs('admin.*');
@endphp

<header class="site-header" id="site-header">
  <div class="header-shell">
    <a href="{{ route('home') }}" class="brand">
      <span class="brand-mark">P</span>
      <span class="brand-copy">
        <strong>PTWG</strong>
        <small>PrayThroughWithGodsword.com</small>
      </span>
    </a>

    <button
      type="button"
      class="header-menu-toggle"
      id="header-menu-toggle"
      aria-expanded="false"
      aria-controls="header-menu"
      aria-label="Open menu"
    >
      <span></span>
      <span></span>
      <span></span>
    </button>

    <div class="header-menu" id="header-menu">
      <nav class="nav-center" aria-label="Main navigation">
        <ul>
          <li><a href="{{ route('home') }}">Home</a></li>
          <li><a href="{{ $isLivePage ? route('home') . '#specific-prayer' : '#specific-prayer' }}">Category</a></li>
          <li><a href="{{ $isLivePage ? route('home') . '#classified-prayer' : '#classified-prayer' }}">Explore</a></li>
          <li><a href="{{ $isLivePage ? '#live-chat' : '#contact' }}">{{ $isLivePage ? 'Live Chat' : 'Contact' }}</a></li>
        </ul>
      </nav>

      <div class="header-actions">
        @if ($isLivePage)
          <a href="{{ route('home') }}" class="secondary-button header-link-button">Back Home</a>
        @elseif ($isAdminArea)
          @auth
            @if (auth()->user()->is_admin)
              <a href="{{ route('admin.dashboard') }}" class="secondary-button header-link-button">Dashboard</a>
              <form action="{{ route('admin.logout') }}" method="POST" class="header-inline-form">
                @csrf
                <button type="submit" class="live-button header-button-reset">Sign out</button>
              </form>
            @endif
          @else
            <a href="{{ route('admin.signin') }}" class="secondary-button header-link-button">Admin Sign In</a>
            <a href="{{ route('admin.signup') }}" class="live-button">Admin Sign Up</a>
          @endauth
        @else
          <div class="search-container">
            <i class="fa-solid fa-magnifying-glass search-icon" aria-hidden="true"></i>
            <input type="text" placeholder="Search prayers, verses..." aria-label="Search prayers and verses" />
          </div>

          @auth
            @if (auth()->user()->is_admin)
              <a href="{{ route('admin.dashboard') }}" class="secondary-button header-link-button">Admin</a>
              <form action="{{ route('admin.logout') }}" method="POST" class="header-inline-form">
                @csrf
                <button type="submit" class="live-button header-button-reset">Sign Out</button>
              </form>
            @else
              <span class="member-chip">{{ auth()->user()->name }}</span>
              <form action="{{ route('member.logout') }}" method="POST" class="header-inline-form">
                @csrf
                <button type="submit" class="secondary-button header-button-reset">Member Sign Out</button>
              </form>
            @endif
          @else
            <a href="{{ route('member.signin') }}" class="secondary-button header-link-button">Member Sign In</a>
            <a href="{{ route('member.signup') }}" class="member-cta-button">Become a Member</a>
          @endauth
        @endif

        <a href="{{ route('live') }}" class="live-button">
          <span class="live-dot"></span>
          Live
        </a>
      </div>
    </div>
  </div>
</header>

@yield('content')

<footer class="site-footer" id="contact">
  <div class="footer-top">
    <div class="footer-brand">
      <a href="{{ route('home') }}" class="footer-logo">
        <span class="footer-logo-mark">P</span>
        <span class="footer-logo-copy">
          <strong>PTWG</strong>
          <small>PrayThroughWithGodsword.com</small>
        </span>
      </a>
      <p>Daily scripture-led prayer support designed to help you move from searching to praying with confidence and direction.</p>
      <div class="footer-socials">
        <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
        <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
        <a href="#" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
      </div>
    </div>

    <div class="footer-item">
      <h4>Explore</h4>
      <div class="footer-links">
        <a href="{{ route('home') }}#specific-prayer">Specific Prayer</a>
        <a href="{{ route('home') }}#classified-prayer">Prayer Categories</a>
        <a href="{{ route('live') }}">Live Prayer Access</a>
      </div>
    </div>

    <div class="footer-item">
      <h4>Support</h4>
      <div class="footer-links">
        <a href="#">Prayer Requests</a>
        <a href="#">Daily Devotion</a>
        <a href="#">Ministry Contact</a>
        @auth
          @if (auth()->user()->is_admin)
            <a href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
          @else
            <form action="{{ route('member.logout') }}" method="POST" class="footer-inline-form">
              @csrf
              <button type="submit" class="footer-inline-button">Member Sign Out</button>
            </form>
          @endif
        @else
          <a href="{{ route('member.signin') }}">Member Sign In</a>
          <a href="{{ route('member.signup') }}">Become a Member</a>
          <a href="{{ route('admin.signin') }}">Admin Sign In</a>
        @endauth
      </div>
    </div>

    <div class="footer-item">
      <h4>Contact</h4>
      <div class="footer-contact">
        <p><i class="fa-regular fa-envelope"></i> info@PraythroughwithGodsword.com</p>
        <p><i class="fa-solid fa-phone"></i> +234 816 038 9006</p>
        <p><i class="fa-solid fa-location-dot"></i> Worship online from anywhere</p>
      </div>
    </div>
  </div>

  <div class="footer-bottom">
    <p>© 2026 PrayThroughWithGodsword.com. Built to help believers pray with scripture daily.</p>
    <div class="footer-bottom-links">
      <a href="#">Privacy</a>
      <a href="#">Terms</a>
      <a href="#">Support</a>
    </div>
  </div>
</footer>

<script src="{{ asset('js/ptgw.js') }}"></script>
</body>
</html>
