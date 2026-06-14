@extends('layout.app')

@section('title', 'Admin Dashboard')
@section('body_class', 'admin-auth-body')

@section('content')
<main class="admin-dashboard-main">
  <section class="admin-dashboard-card admin-home-dashboard-page">
    <div class="admin-dashboard-intro">
      <span class="admin-auth-eyebrow">Admin dashboard</span>
      <h1>Welcome, {{ auth()->user()->name }}.</h1>
      <p>Manage prayer content, monitor scripture library growth, and jump into the right workflow from one clearer control center.</p>
    </div>

    @if (session('status'))
      <div class="form-alert form-alert-success">
        <i class="fa-solid fa-circle-check"></i>
        <span>{{ session('status') }}</span>
      </div>
    @endif

    <div class="admin-dashboard-layout">
      @include('admin.partials.sidebar')

      <div class="admin-dashboard-content">
        <section class="admin-dashboard-panel admin-dashboard-overview-panel admin-home-dashboard-hero">
          <div class="admin-dashboard-panel-head admin-dashboard-panel-head-inline admin-home-dashboard-hero-head">
            <div class="admin-home-dashboard-hero-copy">
              <span class="admin-dashboard-panel-tag">Overview</span>
              <h2>Your scripture workspace at a glance</h2>
              <p>See how the content library is growing, spot the latest additions, and move into the next admin task without digging around.</p>
              <div class="admin-home-dashboard-hero-pills" aria-label="Dashboard highlights">
                <span class="admin-home-dashboard-hero-pill"><i class="fa-solid fa-gauge-high"></i> Live library totals</span>
                <span class="admin-home-dashboard-hero-pill"><i class="fa-solid fa-bolt"></i> Faster action routing</span>
                <span class="admin-home-dashboard-hero-pill"><i class="fa-solid fa-book-journal-whills"></i> Content-focused workflow</span>
              </div>
            </div>
            <div class="admin-dashboard-record-actions admin-home-dashboard-hero-actions">
              <a href="{{ route('admin.upload-verse') }}" class="admin-dashboard-action-link">Create Verse</a>
              <a href="{{ route('admin.list-verses') }}" class="admin-dashboard-action-link">Open Verse Library</a>
            </div>
          </div>

          <div class="admin-home-dashboard-hero-grid">
            <div class="admin-dashboard-stats-grid admin-home-dashboard-stats-grid">
              <article class="admin-dashboard-stat-card">
                <span class="admin-dashboard-stat-label">Categories</span>
                <strong>{{ $totalCategories }}</strong>
                <p>Prayer themes available for organizing scripture entries.</p>
              </article>
              <article class="admin-dashboard-stat-card">
                <span class="admin-dashboard-stat-label">Books</span>
                <strong>{{ $totalBooks }}</strong>
                <p>Saved books currently mapped across your testament library.</p>
              </article>
              <article class="admin-dashboard-stat-card">
                <span class="admin-dashboard-stat-label">Verses</span>
                <strong>{{ $totalVerses }}</strong>
                <p>Total verse records available for prayer-driven discovery.</p>
              </article>
            </div>

            <aside class="admin-home-dashboard-spotlight-card" aria-label="Latest content spotlight">
              <span class="admin-home-dashboard-spotlight-label">Latest activity</span>
              <div class="admin-home-dashboard-spotlight-stack">
                <strong>{{ $latestVerse?->prayer_topic ?? 'No verse entries yet' }}</strong>
                <span>
                  @if ($latestVerse)
                    {{ $latestVerse->chapter?->book?->name ?? $latestVerse->chapter?->book_name }} {{ $latestVerse->chapter?->chapter_number }}:{{ $latestVerse->verse_number }}
                  @else
                    Start by creating the first verse entry.
                  @endif
                </span>
              </div>
              <dl class="admin-home-dashboard-spotlight-meta">
                <div>
                  <dt>Latest book</dt>
                  <dd>{{ $latestBook?->name ?? 'No books yet' }}</dd>
                </div>
                <div>
                  <dt>Latest category</dt>
                  <dd>{{ $latestCategory?->name ?? 'No categories yet' }}</dd>
                </div>
              </dl>
            </aside>
          </div>
        </section>

        <section class="admin-dashboard-panel admin-home-dashboard-workspaces">
          <div class="admin-dashboard-panel-head">
            <span class="admin-dashboard-panel-tag">Quick actions</span>
            <h2>Choose where to work</h2>
            <p>Jump directly into the part of the content system you want to expand or review next.</p>
          </div>
          <div class="admin-home-dashboard-workspace-grid">
            <a href="{{ route('admin.upload-category') }}" class="admin-home-dashboard-workspace-card">
              <i class="fa-solid fa-folder-tree"></i>
              <strong>Categories</strong>
              <span>Shape prayer themes and topic groupings.</span>
            </a>
            <a href="{{ route('admin.upload-testament') }}" class="admin-home-dashboard-workspace-card">
              <i class="fa-solid fa-book-bible"></i>
              <strong>Testaments</strong>
              <span>Organize the top-level scripture structure.</span>
            </a>
            <a href="{{ route('admin.upload-book') }}" class="admin-home-dashboard-workspace-card">
              <i class="fa-solid fa-book"></i>
              <strong>Books</strong>
              <span>Add and manage individual books.</span>
            </a>
            <a href="{{ route('admin.upload-chapter') }}" class="admin-home-dashboard-workspace-card">
              <i class="fa-solid fa-book-open-reader"></i>
              <strong>Chapters</strong>
              <span>Create the chapter framework for verse entries.</span>
            </a>
            <a href="{{ route('admin.upload-verse') }}" class="admin-home-dashboard-workspace-card">
              <i class="fa-solid fa-quote-left"></i>
              <strong>Verses</strong>
              <span>Write and attach scripture content to chapters.</span>
            </a>
            <a href="{{ route('admin.change-password') }}" class="admin-home-dashboard-workspace-card">
              <i class="fa-solid fa-key"></i>
              <strong>Security</strong>
              <span>Update your admin password and account access.</span>
            </a>
          </div>
        </section>

        <section class="admin-dashboard-panel admin-home-dashboard-library-panel">
          <div class="admin-dashboard-panel-head">
            <span class="admin-dashboard-panel-tag">Library status</span>
            <h2>Scripture content coverage</h2>
          </div>
          <div class="admin-home-dashboard-library-grid">
            <article class="admin-home-dashboard-library-card">
              <span>Testaments</span>
              <strong>{{ $totalTestaments }}</strong>
              <p>Top-level scripture groups available in the library.</p>
            </article>
            <article class="admin-home-dashboard-library-card">
              <span>Chapters</span>
              <strong>{{ $totalChapters }}</strong>
              <p>Chapter records currently prepared for verse assignment.</p>
            </article>
            <article class="admin-home-dashboard-library-card">
              <span>Verse records</span>
              <strong>{{ $totalVerses }}</strong>
              <p>Scripture entries ready for review, editing, or reuse.</p>
            </article>
          </div>
        </section>
      </div>
    </div>
  </section>
</main>
@endsection
