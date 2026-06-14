@extends('layout.app')

@section('title', 'List Verses')
@section('body_class', 'admin-auth-body')

@section('content')
@php
  $selectedTestament = $testaments->firstWhere('id', (int) request('testament_id'));
  $selectedBook = $books->firstWhere('id', (int) request('book_id'));
  $selectedChapter = $chapters->firstWhere('id', (int) request('chapter_id'));
  $selectedCategory = $categories->firstWhere('id', (int) request('category_id'));
  $activeFilters = array_filter([
    'testament' => $selectedTestament?->name ?? request('testament_id'),
    'book' => $selectedBook?->name ?? request('book_id'),
    'chapter' => $selectedChapter ? (($selectedChapter->book?->name ?? $selectedChapter->book_name) . ' ' . $selectedChapter->chapter_number) : request('chapter_id'),
    'category' => $selectedCategory?->name ?? request('category_id'),
    'topic' => request('prayer_topic'),
    'verse' => request('verse_number'),
    'search' => request('search'),
  ], fn ($value) => filled($value));
  $coveredBooks = $verses->pluck('chapter.book.name')->filter()->unique()->count();
  $coveredChapters = $verses->pluck('chapter_id')->filter()->unique()->count();
  $latestVerseDate = optional($verses->max('created_at'))?->format('M d, Y');
  $coveredCategories = $verses->pluck('category.name')->filter()->unique()->count();
@endphp
<main class="admin-dashboard-main">
  <section class="admin-dashboard-card admin-verses-library-page">
    <div class="admin-dashboard-intro">
      <span class="admin-auth-eyebrow">Scripture content</span>
      <h1>List Verses</h1>
      <p>Review saved verses and manage their edit or delete pages.</p>
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
        <section class="admin-dashboard-panel admin-dashboard-overview-panel admin-verses-library-hero">
          <div class="admin-dashboard-panel-head admin-dashboard-panel-head-inline admin-verses-library-hero-head">
            <div class="admin-verses-library-hero-copy">
              <span class="admin-dashboard-panel-tag">Verse overview</span>
              <h2>Verse library with faster review and cleaner control</h2>
              <p>Scan prayer topics, trace each record back to its scripture reference, and move from filtering to editing without losing context.</p>
              <div class="admin-verses-library-hero-pills" aria-label="Verse library highlights">
                <span class="admin-verses-library-hero-pill"><i class="fa-solid fa-book-bible"></i> Scripture-first indexing</span>
                <span class="admin-verses-library-hero-pill"><i class="fa-solid fa-tags"></i> Topic and category grouping</span>
                <span class="admin-verses-library-hero-pill"><i class="fa-solid fa-pen-to-square"></i> Faster edit workflow</span>
              </div>
            </div>
            <div class="admin-dashboard-record-actions admin-verses-library-hero-actions">
              <a href="{{ route('admin.upload-verse') }}" class="admin-dashboard-action-link">Create Verse</a>
              <a href="{{ route('admin.upload-chapter') }}" class="admin-dashboard-action-link">Create Chapter</a>
            </div>
          </div>

          <div class="admin-verses-library-hero-grid">
            <div class="admin-dashboard-stats-grid admin-verses-library-stats-grid">
              <article class="admin-dashboard-stat-card">
                <span class="admin-dashboard-stat-label">Showing</span>
                <strong>{{ $verses->count() }}</strong>
                <p>{{ $verses->count() === $totalVerses ? 'All verse records are visible.' : 'Filtered from ' . $totalVerses . ' total verses.' }}</p>
              </article>
              <article class="admin-dashboard-stat-card">
                <span class="admin-dashboard-stat-label">Books covered</span>
                <strong>{{ $coveredBooks }}</strong>
                <p>Distinct books represented in the current result set.</p>
              </article>
              <article class="admin-dashboard-stat-card">
                <span class="admin-dashboard-stat-label">Chapters covered</span>
                <strong>{{ $coveredChapters }}</strong>
                <p>Unique chapter entries included in the current result set.</p>
              </article>
            </div>

            <aside class="admin-verses-library-scope-card" aria-label="Current verse library scope">
              <span class="admin-verses-library-scope-label">Current scope</span>
              <div class="admin-verses-library-scope-stack">
                <strong>{{ $selectedBook?->name ?? $selectedTestament?->name ?? 'All verse records' }}</strong>
                <span>{{ $selectedCategory?->name ?? 'Across every category' }}</span>
              </div>
              <dl class="admin-verses-library-scope-meta">
                <div>
                  <dt>Categories</dt>
                  <dd>{{ $coveredCategories }}</dd>
                </div>
                <div>
                  <dt>Latest added</dt>
                  <dd>{{ $latestVerseDate ?? 'No records' }}</dd>
                </div>
              </dl>
            </aside>
          </div>
        </section>

        <section class="admin-dashboard-panel admin-verses-library-filter-panel">
          <div class="admin-dashboard-panel-head admin-dashboard-panel-head-inline admin-verses-library-filter-head">
            <div class="admin-verses-library-filter-copy">
              <span class="admin-dashboard-panel-tag">Filter verses</span>
              <h2>Find the exact verse records you need</h2>
              <p>Filter by scripture location, category, prayer topic, or verse content to get to the exact entry you want to manage.</p>
            </div>
            <div class="admin-verses-library-filter-side">
              <div class="admin-verses-library-filter-tip">
                <span class="admin-verses-library-filter-tip-label">Recommended flow</span>
                <strong>Start with scripture, then narrow by meaning</strong>
                <span>Choose testament, book, and chapter first, then refine with topic or text search.</span>
              </div>
              @if ($activeFilters)
                <a href="{{ route('admin.list-verses') }}" class="secondary-button admin-dashboard-filter-reset">Clear All</a>
              @endif
            </div>
          </div>

          <form action="{{ route('admin.list-verses') }}" method="GET" class="admin-auth-form admin-dashboard-filter-form">
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
              <span>Chapter</span>
              <select name="chapter_id">
                <option value="">All chapters</option>
                @foreach ($chapters as $chapter)
                  <option value="{{ $chapter->id }}" @selected(request('chapter_id') == $chapter->id)>
                    {{ $chapter->book?->name ?? $chapter->book_name }} - Chapter {{ $chapter->chapter_number }}
                  </option>
                @endforeach
              </select>
            </label>

            <label class="admin-auth-field">
              <span>Category</span>
              <select name="category_id">
                <option value="">All categories</option>
                @foreach ($categories as $category)
                  <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
                @endforeach
              </select>
            </label>

            <label class="admin-auth-field">
              <span>Prayer Topic</span>
              <input type="text" name="prayer_topic" value="{{ request('prayer_topic') }}" placeholder="Search prayer topic" />
            </label>

            <label class="admin-auth-field">
              <span>Verse Number</span>
              <input type="text" name="verse_number" value="{{ request('verse_number') }}" inputmode="text" placeholder="e.g. 1, 2-10, 2:8" />
            </label>

            <label class="admin-auth-field admin-dashboard-filter-search">
              <span>Search Text</span>
              <input type="text" name="search" value="{{ request('search') }}" placeholder="Search verse text" />
            </label>

            <div class="admin-dashboard-record-actions">
              <button type="submit" class="primary-button admin-auth-submit">Filter</button>
              <a href="{{ route('admin.list-verses') }}" class="secondary-button">Reset</a>
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

        <section class="admin-dashboard-panel admin-verses-library-table-panel">
          <div class="admin-dashboard-panel-head admin-dashboard-panel-head-inline">
            <div>
              <span class="admin-dashboard-panel-tag">Saved verses</span>
              <h2>Current verse records</h2>
              <p>Each row keeps the scripture reference, classification, and verse preview together for quicker decision-making.</p>
            </div>
            <div class="admin-dashboard-table-meta">
              <span>{{ $verses->count() }} result{{ $verses->count() === 1 ? '' : 's' }}</span>
            </div>
          </div>

          @if ($verses->isEmpty())
            <div class="admin-dashboard-empty-state">
              <i class="fa-solid fa-magnifying-glass"></i>
              <h3>No verses matched this view</h3>
              <p>{{ $activeFilters ? 'Try adjusting your filters or clear them to see more verse records.' : 'No verses have been uploaded yet.' }}</p>
            </div>
          @else
            <div class="admin-dashboard-table-wrap">
              <table class="admin-dashboard-table">
                <thead>
                  <tr>
                    <th>Ref</th>
                    <th>Scripture</th>
                    <th>Classification</th>
                    <th>Verse</th>
                    <th>Preview</th>
                    <th>Manage</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($verses as $index => $verse)
                    <tr>
                      <td><span class="admin-verses-library-row-index">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span></td>
                      <td>
                        <div class="admin-dashboard-reference-stack admin-verses-library-reference-stack">
                          <strong>{{ $verse->chapter?->book?->name ?? $verse->chapter?->book_name }}</strong>
                          <span>{{ $verse->chapter?->book?->testament?->name ?? 'Scripture' }} • Chapter {{ $verse->chapter?->chapter_number }}</span>
                        </div>
                      </td>
                      <td>
                        <div class="admin-verses-library-classification">
                          <span class="admin-dashboard-verse-badge">{{ $verse->category?->name ?? 'Uncategorized' }}</span>
                          <strong>{{ $verse->prayer_topic }}</strong>
                        </div>
                      </td>
                      <td>
                        <div class="admin-verses-library-verse-cell">
                          <span class="admin-dashboard-verse-badge">Verse {{ $verse->verse_number }}</span>
                          <span class="admin-verses-library-date-note">{{ $verse->created_at?->format('M d, Y') }}</span>
                        </div>
                      </td>
                      <td>
                        <div class="admin-dashboard-verse-excerpt admin-verses-library-excerpt">
                          {{ \Illuminate\Support\Str::limit(strip_tags($verse->verse_text), 150) }}
                        </div>
                      </td>
                      <td>
                        <div class="admin-dashboard-table-actions">
                          <a href="{{ route('admin.edit-verse', $verse) }}" class="secondary-button">Edit</a>
                          <a href="{{ route('admin.delete-verse', $verse) }}" class="admin-dashboard-delete-link">Delete</a>
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
