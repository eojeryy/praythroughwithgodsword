@extends('layout.app')

@section('title', 'Delete Book')
@section('body_class', 'admin-auth-body')

@section('content')
<main class="admin-dashboard-main">
  <section class="admin-dashboard-card">
    <div class="admin-dashboard-intro">
      <span class="admin-auth-eyebrow">Scripture content</span>
      <h1>Delete Book</h1>
      <p>Confirm removal of this book on a separate page before it is deleted.</p>
    </div>

    <div class="admin-dashboard-layout">
      @include('admin.partials.sidebar')

      <div class="admin-dashboard-content">
        <section class="admin-dashboard-panel">
          <div class="admin-dashboard-panel-head">
            <span class="admin-dashboard-panel-tag">Delete confirmation</span>
            <h2>{{ $book->name }}</h2>
          </div>
          <p class="admin-dashboard-warning">You can only delete this book if no chapters are attached to it.</p>
          <div class="admin-dashboard-record-actions">
            <form action="{{ route('admin.upload-book.destroy', $book) }}" method="POST" class="admin-dashboard-inline-form">
              @csrf
              @method('DELETE')
              <button type="submit" class="admin-dashboard-delete-button">Confirm Delete</button>
            </form>
            <a href="{{ route('admin.list-books') }}" class="secondary-button">Cancel</a>
          </div>
        </section>
      </div>
    </div>
  </section>
</main>
@endsection
