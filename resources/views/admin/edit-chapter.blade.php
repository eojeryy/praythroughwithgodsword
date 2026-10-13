@extends('layout.admin')

@section('title', 'Edit Chapter')
@section('body_class', 'admin-workspace')

@section('content')
<main class="admin-dashboard-main">
  <section class="admin-dashboard-card">
    <div class="admin-dashboard-intro">
      <span class="admin-auth-eyebrow">Scripture content</span>
      <h1>Edit Chapter</h1>
      <p>Update the selected chapter on its own page.</p>
    </div>

    <div class="admin-dashboard-layout">
      @include('admin.partials.sidebar')

      <div class="admin-dashboard-content">
        <section class="admin-dashboard-panel">
          <div class="admin-dashboard-panel-head">
            <span class="admin-dashboard-panel-tag">Editing</span>
            <h2>{{ $chapter->book?->name ?? $chapter->book_name }} Chapter {{ $chapter->chapter_number }}</h2>
          </div>
          <form action="{{ route('admin.upload-chapter.update', $chapter) }}" method="POST" class="admin-auth-form">
            @csrf
            @method('PUT')
            <label class="admin-auth-field">
              <span>Book</span>
              <select name="book_id" required>
                <option value="">Select a book</option>
                @foreach ($books as $book)
                  <option value="{{ $book->id }}" @selected(old('book_id', $chapter->book_id) == $book->id)>{{ $book->name }}</option>
                @endforeach
              </select>
            </label>
            <label class="admin-auth-field">
              <span>Chapter Number</span>
              <input type="number" name="chapter_number" required value="{{ old('chapter_number', $chapter->chapter_number) }}" min="1" />
            </label>
            <div class="admin-dashboard-record-actions">
              <a href="{{ route('admin.show-chapter', $chapter) }}" class="secondary-button">View record</a>
              <button type="submit" class="primary-button admin-auth-submit">Save Changes</button>
              <a href="{{ route('admin.list-chapters') }}" class="secondary-button">Back to List</a>
            </div>
          </form>
        </section>
      </div>
    </div>
  </section>
</main>
@endsection
