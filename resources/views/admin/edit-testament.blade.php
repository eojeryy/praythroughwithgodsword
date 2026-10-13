@extends('layout.admin')

@section('title', 'Edit Testament')
@section('body_class', 'admin-workspace')

@section('content')
<main class="admin-dashboard-main">
  <section class="admin-dashboard-card">
    <div class="admin-dashboard-intro">
      <span class="admin-auth-eyebrow">Bible structure</span>
      <h1>Edit Testament</h1>
      <p>Update the selected testament on its own page so changes are focused and easy to review.</p>
    </div>

    <div class="admin-dashboard-layout">
      @include('admin.partials.sidebar')

      <div class="admin-dashboard-content">
        <section class="admin-dashboard-panel">
          <div class="admin-dashboard-panel-head">
            <span class="admin-dashboard-panel-tag">Editing</span>
            <h2>{{ $testament->name }}</h2>
          </div>
          <form action="{{ route('admin.upload-testament.update', $testament) }}" method="POST" class="admin-auth-form">
            @csrf
            @method('PUT')
            <label class="admin-auth-field">
              <span>Testament Name</span>
              <input type="text" name="testament_name" required value="{{ old('testament_name', $testament->name) }}" />
            </label>
            <label class="admin-auth-field">
              <span>Summary</span>
              <textarea name="testament_summary" rows="3" maxlength="1000" placeholder="Short summary for this testament">{{ old('testament_summary', $testament->summary) }}</textarea>
            </label>
            <div class="admin-dashboard-record-actions">
              <a href="{{ route('admin.show-testament', $testament) }}" class="secondary-button">View record</a>
              <button type="submit" class="primary-button admin-auth-submit">Save Changes</button>
              <a href="{{ route('admin.list-testaments') }}" class="secondary-button">Back to List</a>
            </div>
          </form>
        </section>
      </div>
    </div>
  </section>
</main>
@endsection
