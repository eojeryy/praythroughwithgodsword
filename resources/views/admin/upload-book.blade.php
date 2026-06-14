@extends('layout.app')

@section('title', 'Upload Book')
@section('body_class', 'admin-auth-body')

@section('content')
<main class="admin-dashboard-main">
  <section class="admin-dashboard-card">
    <div class="admin-dashboard-intro">
      <span class="admin-auth-eyebrow">Scripture content</span>
      <h1>Upload Book</h1>
      <p>Create books first, then assign chapters to them from the chapter page.</p>
    </div>

    <div class="admin-dashboard-layout">
      @include('admin.partials.sidebar')

      <div class="admin-dashboard-content">
        <section class="admin-dashboard-panel">
          <div class="admin-dashboard-panel-head admin-dashboard-panel-head-inline">
            <div>
              <span class="admin-dashboard-panel-tag">Book form</span>
              <h2>New book entry</h2>
            </div>
            <a href="{{ route('admin.list-books') }}" class="admin-dashboard-action-link">List Books</a>
          </div>

          @if ($errors->any())
            <div class="form-alert form-alert-error">
              <i class="fa-solid fa-circle-exclamation"></i>
              <span>{{ $errors->first() }}</span>
            </div>
          @endif

          <form action="{{ route('admin.upload-book.store') }}" method="POST" class="admin-auth-form">
            @csrf
            <label class="admin-auth-field">
              <span>Testament</span>
              <select name="testament_id">
                <option value="">Select a testament</option>
                @foreach ($testaments as $testament)
                  <option value="{{ $testament->id }}" @selected(old('testament_id') == $testament->id)>{{ $testament->name }}</option>
                @endforeach
              </select>
            </label>
            <label class="admin-auth-field">
              <span>Book Name</span>
              <input type="text" name="book_name" value="{{ old('book_name') }}" placeholder="e.g. Psalms" />
            </label>
            <button type="submit" class="primary-button admin-auth-submit">Upload Book</button>
          </form>
        </section>
      </div>
    </div>
  </section>
</main>
@endsection
