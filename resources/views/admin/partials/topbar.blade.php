<header class="site-header admin-topbar" id="site-header">
  <a href="{{ route('admin.dashboard') }}" class="admin-topbar-brand"><img src="{{ asset('images/ptwg-header-logo.png') }}" width="48" height="48" alt="PTWG" /><span>Content workspace<small>Administration</small></span></a>
  <div class="admin-topbar-actions"><a href="{{ route('home') }}">View website <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i></a><span class="admin-topbar-user">{{ auth()->user()->name }}</span><form action="{{ route('admin.logout') }}" method="POST">@csrf<button type="submit">Sign out</button></form></div>
</header>
