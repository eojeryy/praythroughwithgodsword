@extends('layout.admin')

@section('title', 'Delete Testament')
@section('body_class', 'admin-workspace')

@section('content')
<main class="admin-dashboard-main">
  <section class="admin-dashboard-card">
    <div class="admin-dashboard-intro">
      <span class="admin-auth-eyebrow">Bible structure</span>
      <h1>Delete Testament</h1>
      <p>Confirm removal of this testament on a separate page before it is deleted from the database.</p>
    </div>

    <div class="admin-dashboard-layout">
      @include('admin.partials.sidebar')

      <div class="admin-dashboard-content">
        <section class="admin-dashboard-panel">
          <div class="admin-dashboard-panel-head">
            <span class="admin-dashboard-panel-tag">Delete confirmation</span>
            <h2>{{ $testament->name }}</h2>
          </div>
          <p>{{ $testament->summary ?: 'No summary added yet.' }}</p>
          <p class="admin-dashboard-warning">This action will permanently remove the testament. Testaments with linked books cannot be deleted; move or delete those books first.</p>
          @if ($testament->books_count > 0)
            <p>This testament has {{ $testament->books_count }} linked books. <a href="{{ route('admin.list-books', ['testament_id' => $testament->id]) }}">Manage linked books</a>.</p>
          @endif
          <div class="admin-dashboard-record-actions">
            <form action="{{ route('admin.upload-testament.destroy', $testament) }}" method="POST" class="admin-dashboard-inline-form">
              @csrf
              @method('DELETE')
              <button type="submit" class="admin-dashboard-delete-button" @disabled($testament->books_count > 0)>Confirm Delete</button>
            </form>
            <a href="{{ route('admin.list-testaments') }}" class="secondary-button">Cancel</a>
          </div>
        </section>
      </div>
    </div>
  </section>
</main>
@endsection
