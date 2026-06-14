@extends('layout.app')

@section('title', 'Delete Verse')
@section('body_class', 'admin-auth-body')

@section('content')
<main class="admin-dashboard-main">
  <section class="admin-dashboard-card">
    <div class="admin-dashboard-intro">
      <span class="admin-auth-eyebrow">Scripture content</span>
      <h1>Delete Verse</h1>
      <p>Confirm removal of this verse on a separate page before it is deleted.</p>
    </div>

    <div class="admin-dashboard-layout">
      @include('admin.partials.sidebar')

      <div class="admin-dashboard-content">
        <section class="admin-dashboard-panel">
          <div class="admin-dashboard-panel-head">
            <span class="admin-dashboard-panel-tag">Delete confirmation</span>
            <h2>{{ $verse->chapter?->book?->name ?? $verse->chapter?->book_name }} {{ $verse->chapter?->chapter_number }}:{{ $verse->verse_number }}</h2>
          </div>
          <div class="admin-rich-text-preview">{!! $verse->verse_text !!}</div>
          <p class="admin-dashboard-warning">This action will permanently remove the verse.</p>
          <div class="admin-dashboard-record-actions">
            <form action="{{ route('admin.upload-verse.destroy', $verse) }}" method="POST" class="admin-dashboard-inline-form">
              @csrf
              @method('DELETE')
              <button type="submit" class="admin-dashboard-delete-button">Confirm Delete</button>
            </form>
            <a href="{{ route('admin.list-verses') }}" class="secondary-button">Cancel</a>
          </div>
        </section>
      </div>
    </div>
  </section>
</main>
@endsection
