@extends('layout.admin')

@section('title', 'Edit Book')
@section('body_class', 'admin-workspace')

@section('content')
<main class="admin-dashboard-main">
  <section class="admin-dashboard-card">
    <div class="admin-dashboard-intro">
      <span class="admin-auth-eyebrow">Scripture content</span>
      <h1>Edit Book</h1>
      <p>Update the selected book on its own page.</p>
    </div>

    <div class="admin-dashboard-layout">
      @include('admin.partials.sidebar')

      <div class="admin-dashboard-content">
        <section class="admin-dashboard-panel">
          <div class="admin-dashboard-panel-head">
            <span class="admin-dashboard-panel-tag">Editing</span>
            <h2>{{ $book->name }}</h2>
          </div>
          <form action="{{ route('admin.upload-book.update', $book) }}" method="POST" class="admin-auth-form">
            @csrf
            @method('PUT')
            <label class="admin-auth-field">
              <span>Testament</span>
              <select name="testament_id" required>
                <option value="">Select a testament</option>
                @foreach ($testaments as $testament)
                  <option value="{{ $testament->id }}" @selected(old('testament_id', $book->testament_id) == $testament->id)>{{ $testament->name }}</option>
                @endforeach
              </select>
            </label>
            <label class="admin-auth-field">
              <span>Book Name</span>
              <input type="text" name="book_name" required value="{{ old('book_name', $book->name) }}" />
            </label>
            <div class="admin-dashboard-record-actions">
              <a href="{{ route('admin.show-book', $book) }}" class="secondary-button">View record</a>
              <button type="submit" class="primary-button admin-auth-submit">Save Changes</button>
              <a href="{{ route('admin.list-books') }}" class="secondary-button">Back to List</a>
            </div>
          </form>
        </section>
      </div>
    </div>
  </section>
</main>
@endsection
