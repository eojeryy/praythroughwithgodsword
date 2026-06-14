@extends('layout.app')

@section('title', 'Delete Chapter')
@section('body_class', 'admin-auth-body')

@section('content')
<main class="admin-dashboard-main">
  <section class="admin-dashboard-card">
    <div class="admin-dashboard-intro">
      <span class="admin-auth-eyebrow">Scripture content</span>
      <h1>Delete Chapter</h1>
      <p>Confirm removal of this chapter on a separate page before it is deleted.</p>
    </div>

    <div class="admin-dashboard-layout">
      @include('admin.partials.sidebar')

      <div class="admin-dashboard-content">
        <section class="admin-dashboard-panel">
          <div class="admin-dashboard-panel-head">
            <span class="admin-dashboard-panel-tag">Delete confirmation</span>
            <h2>{{ $chapter->book?->name ?? $chapter->book_name }} Chapter {{ $chapter->chapter_number }}</h2>
          </div>
          <p class="admin-dashboard-warning">This action will permanently remove the chapter.</p>
          <div class="admin-dashboard-record-actions">
            <form action="{{ route('admin.upload-chapter.destroy', $chapter) }}" method="POST" class="admin-dashboard-inline-form">
              @csrf
              @method('DELETE')
              <button type="submit" class="admin-dashboard-delete-button">Confirm Delete</button>
            </form>
            <a href="{{ route('admin.list-chapters') }}" class="secondary-button">Cancel</a>
          </div>
        </section>
      </div>
    </div>
  </section>
</main>
@endsection
