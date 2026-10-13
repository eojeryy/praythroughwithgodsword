@extends('layout.admin')

@section('title', 'Change Password')
@section('body_class', 'admin-workspace')

@section('content')
<main class="admin-dashboard-main">
  <section class="admin-dashboard-card">
    <div class="admin-dashboard-intro">
      <span class="admin-auth-eyebrow">Security</span>
      <h1>Change Password</h1>
      <p>Update the admin password regularly to keep dashboard access secure.</p>
    </div>

    <div class="admin-dashboard-layout">
      @include('admin.partials.sidebar')

      <div class="admin-dashboard-content">
        <section class="admin-dashboard-panel">
          <div class="admin-dashboard-panel-head">
            <span class="admin-dashboard-panel-tag">Password form</span>
            <h2>Update your password</h2>
          </div>
          <form action="{{ route('admin.change-password.update') }}" method="POST" class="admin-auth-form">
            @csrf
            @method('PUT')
            <label class="admin-auth-field">
              <span>Current Password</span>
              <input type="password" name="current_password" autocomplete="current-password" required placeholder="Enter current password" />
            </label>
            <label class="admin-auth-field">
              <span>New Password</span>
              <input type="password" name="password" autocomplete="new-password" minlength="8" required placeholder="Enter new password" />
            </label>
            <label class="admin-auth-field">
              <span>Confirm New Password</span>
              <input type="password" name="password_confirmation" autocomplete="new-password" minlength="8" required placeholder="Confirm new password" />
            </label>
            <button type="submit" class="primary-button admin-auth-submit">Change Password</button>
          </form>
        </section>
      </div>
    </div>
  </section>
</main>
@endsection
