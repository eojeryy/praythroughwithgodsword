@extends('layout.admin')
@section('title', 'Admin Overview')
@section('body_class', 'admin-workspace')
@section('content')
@php
  $libraries = [
    ['label' => 'Categories', 'plural' => 'categories', 'type' => 'category', 'count' => $totalCategories, 'icon' => 'fa-folder-tree', 'description' => 'Organize prayers by theme.'],
    ['label' => 'Testaments', 'plural' => 'testaments', 'type' => 'testament', 'count' => $totalTestaments, 'icon' => 'fa-book-bible', 'description' => 'Manage scripture groupings.'],
    ['label' => 'Books', 'plural' => 'books', 'type' => 'book', 'count' => $totalBooks, 'icon' => 'fa-book', 'description' => 'Build your book collection.'],
    ['label' => 'Chapters', 'plural' => 'chapters', 'type' => 'chapter', 'count' => $totalChapters, 'icon' => 'fa-book-open-reader', 'description' => 'Arrange chapters and verses.'],
    ['label' => 'Verses', 'plural' => 'verses', 'type' => 'verse', 'count' => $totalVerses, 'icon' => 'fa-quote-left', 'description' => 'Review scripture and prayer topics.'],
  ];
@endphp
<main class="admin-dashboard-main">
  <section class="admin-dashboard-card">
    <div class="admin-dashboard-intro admin-library-intro"><div><span class="admin-auth-eyebrow">Overview</span><h1>Welcome, {{ auth()->user()->name }}.</h1><p>Care for your prayer community’s scripture library.</p></div><a href="{{ route('admin.upload-verse') }}" class="primary-button"><i class="fa-solid fa-plus" aria-hidden="true"></i> Add verse</a></div>
    <div class="admin-dashboard-layout">
      @include('admin.partials.sidebar')
      <div class="admin-dashboard-content">
        <section class="admin-overview-banner"><div><span>YOUR SCRIPTURE WORKSPACE</span><h2>A growing library.<br />A daily source of prayer.</h2><p>Review your content, keep references accurate, and make every prayer easier to find.</p></div><i class="fa-solid fa-book-open" aria-hidden="true"></i></section>
        <section class="admin-overview-totals" aria-label="Content totals">
          @foreach ($libraries as $library)
            <a href="{{ route('admin.list-' . $library['plural']) }}" class="admin-dashboard-stat-card"><i class="fa-solid {{ $library['icon'] }}" aria-hidden="true"></i><span class="admin-dashboard-stat-label">{{ $library['label'] }}</span><strong>{{ number_format($library['count']) }}</strong><p>Open library →</p></a>
          @endforeach
        </section>
        <section class="admin-dashboard-panel">
          <div class="admin-dashboard-panel-head admin-dashboard-panel-head-inline"><div><span class="admin-dashboard-panel-tag">Recently added</span><h2>Latest verse records</h2></div><a href="{{ route('admin.list-verses') }}" class="admin-back-link">View all verses →</a></div>
          @if ($recentVerses->isEmpty())
            <div class="admin-dashboard-empty-state"><i class="fa-solid fa-book-open" aria-hidden="true"></i><h3>Start your scripture library</h3><p>Add a testament, book, and chapter, then create your first verse.</p><a href="{{ route('admin.upload-testament') }}" class="primary-button">Add testament</a></div>
          @else
            <div class="admin-dashboard-table-wrap"><table class="admin-dashboard-table"><thead><tr><th scope="col">Reference</th><th scope="col">Prayer topic</th><th scope="col">Category</th><th scope="col">Actions</th></tr></thead><tbody>
              @foreach($recentVerses as $verse)
                <tr><td><a class="admin-record-title" href="{{ route('admin.show-verse', $verse) }}">{{ $verse->chapter?->book?->name ?? $verse->chapter?->book_name }} {{ $verse->chapter?->chapter_number }}:{{ $verse->verse_number }}</a><small class="admin-record-subtitle">{{ $verse->created_at?->format('M d, Y') }}</small></td><td>{{ $verse->prayer_topic }}</td><td>{{ $verse->category?->name ?? 'Uncategorized' }}</td><td>@include('admin.partials.record-actions', ['type' => 'verse', 'record' => $verse])</td></tr>
              @endforeach
            </tbody></table></div>
          @endif
        </section>
        <section class="admin-dashboard-panel">
          <div class="admin-dashboard-panel-head"><span class="admin-dashboard-panel-tag">Manage content</span><h2>Choose a library</h2></div>
          <div class="admin-library-grid">
            @foreach($libraries as $library)
              <article class="admin-library-card"><i class="fa-solid {{ $library['icon'] }}" aria-hidden="true"></i><h3>{{ $library['label'] }}</h3><p>{{ $library['description'] }}</p><div><a href="{{ route('admin.list-' . $library['plural']) }}">View library →</a><a href="{{ route('admin.upload-' . $library['type']) }}">+ Add {{ $library['type'] }}</a></div></article>
            @endforeach
          </div>
        </section>
      </div>
    </div>
  </section>
</main>
@endsection
