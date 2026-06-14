@extends('layout.app')

@section('title', 'Upload Chapter')
@section('body_class', 'admin-auth-body')

@section('content')
<main class="admin-dashboard-main">
  <section class="admin-dashboard-card">
    <div class="admin-dashboard-intro">
      <span class="admin-auth-eyebrow">Scripture content</span>
      <h1>Upload Chapter</h1>
      <p>Prepare chapter records that can later be attached to books, verses, or prayer references.</p>
    </div>

    <div class="admin-dashboard-layout">
      @include('admin.partials.sidebar')

      <div class="admin-dashboard-content">
        <section class="admin-dashboard-panel">
          <div class="admin-dashboard-panel-head admin-dashboard-panel-head-inline">
            <div>
              <span class="admin-dashboard-panel-tag">Chapter form</span>
              <h2>New chapter entry</h2>
            </div>
            <div class="admin-dashboard-record-actions">
              <a href="{{ route('admin.upload-book') }}" class="admin-dashboard-action-link">Create Book</a>
              <a href="{{ route('admin.list-chapters') }}" class="admin-dashboard-action-link">List Chapters</a>
            </div>
          </div>

          @if ($errors->any())
            <div class="form-alert form-alert-error">
              <i class="fa-solid fa-circle-exclamation"></i>
              <span>{{ $errors->first() }}</span>
            </div>
          @endif

          <form action="{{ route('admin.upload-chapter.store') }}" method="POST" class="admin-auth-form">
            @csrf
            <label class="admin-auth-field">
              <span>Book</span>
              <select name="book_id">
                <option value="">Select a book</option>
                @foreach ($books as $book)
                  <option value="{{ $book->id }}" @selected(old('book_id') == $book->id)>{{ $book->name }}</option>
                @endforeach
              </select>
            </label>
            <label class="admin-auth-field">
              <span>Chapter Number</span>
              <input type="number" name="chapter_number" value="{{ old('chapter_number') }}" min="1" placeholder="e.g. 23" />
            </label>
            <button type="submit" class="primary-button admin-auth-submit">Upload Chapter</button>
          </form>
        </section>
      </div>
    </div>
  </section>
</main>
@endsection
