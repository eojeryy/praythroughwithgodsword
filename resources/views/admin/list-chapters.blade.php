@extends('layout.app')

@section('title', 'List Chapters')
@section('body_class', 'admin-auth-body')

@section('content')
@php
  $selectedTestament = $testaments->firstWhere('id', (int) request('testament_id'));
  $selectedBook = $books->firstWhere('id', (int) request('book_id'));
  $activeFilters = array_filter([
    'testament' => $selectedTestament?->name ?? request('testament_id'),
    'book' => $selectedBook?->name ?? request('book_id'),
    'chapter' => request('chapter_number'),
  ], fn ($value) => filled($value));
  $coveredBooks = $chapters->pluck('book.name')->filter()->unique()->count();
  $coveredTestaments = $chapters->pluck('book.testament.name')->filter()->unique()->count();
  $latestChapterDate = optional($chapters->max('created_at'))?->format('M d, Y');
  $highestChapterNumber = $chapters->max('chapter_number');
@endphp
<main class="admin-dashboard-main">
  <section class="admin-dashboard-card admin-chapters-page">
    <div class="admin-dashboard-intro">
      <span class="admin-auth-eyebrow">Scripture content</span>
      <h1>List Chapters</h1>
      <p>Review every saved chapter entry in one focused workspace with quicker scanning, clearer references, and cleaner actions.</p>
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
        <section class="admin-dashboard-panel admin-dashboard-overview-panel admin-chapters-hero-panel">
          <div class="admin-dashboard-panel-head admin-dashboard-panel-head-inline admin-chapters-hero-head">
            <div class="admin-chapters-hero-copy">
              <span class="admin-dashboard-panel-tag">Chapter overview</span>
              <h2>Chapter library at a glance</h2>
              <p>Track the chapter records currently in view, confirm coverage by book and testament, and jump straight into content management.</p>
              <div class="admin-chapters-hero-pills" aria-label="Chapter workspace highlights">
                <span class="admin-chapters-hero-pill"><i class="fa-solid fa-layer-group"></i> Structured chapter index</span>
                <span class="admin-chapters-hero-pill"><i class="fa-solid fa-filter-circle-dollar"></i> Faster filtering flow</span>
                <span class="admin-chapters-hero-pill"><i class="fa-solid fa-book-bookmark"></i> Clear scripture context</span>
              </div>
            </div>
            <div class="admin-dashboard-record-actions admin-chapters-hero-actions">
              <a href="{{ route('admin.upload-book') }}" class="admin-dashboard-action-link">Create Book</a>
              <a href="{{ route('admin.upload-chapter') }}" class="admin-dashboard-action-link">Create Chapter</a>
            </div>
          </div>

          <div class="admin-chapters-hero-grid">
            <div class="admin-dashboard-stats-grid admin-chapters-stats-grid">
              <article class="admin-dashboard-stat-card">
                <span class="admin-dashboard-stat-label">Showing</span>
                <strong>{{ $chapters->count() }}</strong>
                <p>{{ $chapters->count() === $totalChapters ? 'All chapter records are visible.' : 'Filtered from ' . $totalChapters . ' total chapters.' }}</p>
              </article>
              <article class="admin-dashboard-stat-card">
                <span class="admin-dashboard-stat-label">Books covered</span>
                <strong>{{ $coveredBooks }}</strong>
                <p>Distinct books represented in the current result set.</p>
              </article>
              <article class="admin-dashboard-stat-card">
                <span class="admin-dashboard-stat-label">Testaments covered</span>
                <strong>{{ $coveredTestaments }}</strong>
                <p>Unique testament groups included in the current result set.</p>
              </article>
            </div>

            <aside class="admin-chapters-highlight-card" aria-label="Visible chapter summary">
              <span class="admin-chapters-highlight-label">Visible scope</span>
              <div class="admin-chapters-highlight-stack">
                <strong>{{ $selectedBook?->name ?? 'All Books' }}</strong>
                <span>{{ $selectedTestament?->name ?? 'Across every testament' }}</span>
              </div>
              <dl class="admin-chapters-highlight-meta">
                <div>
                  <dt>Highest chapter</dt>
                  <dd>{{ $highestChapterNumber ? 'Chapter ' . $highestChapterNumber : 'None yet' }}</dd>
                </div>
                <div>
                  <dt>Latest added</dt>
                  <dd>{{ $latestChapterDate ?? 'No records' }}</dd>
                </div>
              </dl>
            </aside>
          </div>
        </section>

        <section class="admin-dashboard-panel admin-chapters-filter-panel">
          <div class="admin-dashboard-panel-head admin-dashboard-panel-head-inline admin-chapters-filter-head">
            <div class="admin-chapters-filter-copy">
              <span class="admin-dashboard-panel-tag">Filter chapters</span>
              <h2>Find the exact chapter records you need</h2>
              <p>Narrow the workspace by testament, book, or chapter number, then reset in one click when you want the full library again.</p>
            </div>
            <div class="admin-chapters-filter-side">
              <div class="admin-chapters-filter-tip">
                <span class="admin-chapters-filter-tip-label">Quick tip</span>
                <strong>Start broad, then narrow down</strong>
                <span>Pick a testament first for the cleanest book list mentally.</span>
              </div>
              @if ($activeFilters)
                <a href="{{ route('admin.list-chapters') }}" class="secondary-button admin-dashboard-filter-reset">Clear All</a>
              @endif
            </div>
          </div>

          <form action="{{ route('admin.list-chapters') }}" method="GET" class="admin-auth-form admin-dashboard-filter-form">
            <label class="admin-auth-field">
              <span>Testament</span>
              <select name="testament_id">
                <option value="">All testaments</option>
                @foreach ($testaments as $testament)
                  <option value="{{ $testament->id }}" @selected(request('testament_id') == $testament->id)>{{ $testament->name }}</option>
                @endforeach
              </select>
            </label>

            <label class="admin-auth-field">
              <span>Book</span>
              <select name="book_id">
                <option value="">All books</option>
                @foreach ($books as $book)
                  <option value="{{ $book->id }}" @selected(request('book_id') == $book->id)>{{ $book->name }}</option>
                @endforeach
              </select>
            </label>

            <label class="admin-auth-field">
              <span>Chapter Number</span>
              <input type="number" name="chapter_number" value="{{ request('chapter_number') }}" min="1" placeholder="e.g. 23" />
            </label>

            <div class="admin-dashboard-record-actions">
              <button type="submit" class="primary-button admin-auth-submit">Filter</button>
              <a href="{{ route('admin.list-chapters') }}" class="secondary-button">Reset</a>
            </div>
          </form>

          @if ($activeFilters)
            <div class="admin-dashboard-filter-summary">
              <span class="admin-dashboard-filter-summary-label">Active filters</span>
              <div class="admin-dashboard-filter-chips">
                @foreach ($activeFilters as $label => $value)
                  <span class="admin-dashboard-filter-chip">{{ ucfirst($label) }}: {{ $value }}</span>
                @endforeach
              </div>
            </div>
          @endif
        </section>

        <section class="admin-dashboard-panel admin-chapters-table-panel">
          <div class="admin-dashboard-panel-head admin-dashboard-panel-head-inline">
            <div>
              <span class="admin-dashboard-panel-tag">Saved chapters</span>
              <h2>Current chapter records</h2>
              <p>Each row keeps the scripture reference, placement, and management actions grouped for faster review.</p>
            </div>
            <div class="admin-dashboard-table-meta">
              <span>{{ $chapters->count() }} result{{ $chapters->count() === 1 ? '' : 's' }}</span>
            </div>
          </div>

          @if ($chapters->isEmpty())
            <div class="admin-dashboard-empty-state">
              <i class="fa-solid fa-book-open-reader"></i>
              <h3>No chapters matched this view</h3>
              <p>{{ $activeFilters ? 'Try adjusting your filters or clear them to see more chapter records.' : 'No chapters have been uploaded yet.' }}</p>
            </div>
          @else
            <div class="admin-dashboard-table-wrap">
              <table class="admin-dashboard-table">
                <thead>
                  <tr>
                    <th>Ref</th>
                    <th>Scripture</th>
                    <th>Placement</th>
                    <th>Created</th>
                    <th>Manage</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($chapters as $index => $chapter)
                    <tr>
                      <td>
                        <span class="admin-chapters-row-index">
                          {{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}
                        </span>
                      </td>
                      <td>
                        <div class="admin-dashboard-reference-stack admin-chapters-reference-stack">
                          <strong>{{ $chapter->book?->name ?? $chapter->book_name }}</strong>
                          <span>{{ $chapter->book?->testament?->name ?? 'Scripture' }}</span>
                        </div>
                      </td>
                      <td>
                        <div class="admin-chapters-placement-cell">
                          <span class="admin-dashboard-verse-badge">Chapter {{ $chapter->chapter_number }}</span>
                          <span class="admin-chapters-placement-note">Book order anchor</span>
                        </div>
                      </td>
                      <td>
                        <div class="admin-chapters-date-stack">
                          <strong>{{ $chapter->created_at?->format('M d, Y') }}</strong>
                          <span>{{ $chapter->created_at?->diffForHumans() }}</span>
                        </div>
                      </td>
                      <td>
                        <div class="admin-dashboard-table-actions">
                          <a href="{{ route('admin.edit-chapter', $chapter) }}" class="secondary-button">Edit</a>
                          <a href="{{ route('admin.delete-chapter', $chapter) }}" class="admin-dashboard-delete-link">Delete</a>
                        </div>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </section>
      </div>
    </div>
  </section>
</main>
@endsection
