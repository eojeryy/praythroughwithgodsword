@extends('layout.app')

@section('title', 'List Books')
@section('body_class', 'admin-auth-body')

@section('content')
@php
  $activeFilters = array_filter([
    'testament' => request('testament_id'),
    'book' => request('book_name'),
    'chapters' => request('chapter_count'),
  ], fn ($value) => filled($value));
  $coveredTestaments = $books->pluck('testament.name')->filter()->unique()->count();
  $totalChaptersAcrossResults = $books->sum('chapters_count');
@endphp
<main class="admin-dashboard-main">
  <section class="admin-dashboard-card">
    <div class="admin-dashboard-intro">
      <span class="admin-auth-eyebrow">Scripture content</span>
      <h1>List Books</h1>
      <p>Review saved books and manage their edit or delete pages.</p>
    </div>

    @if (session('status'))
      <div class="form-alert form-alert-success">
        <i class="fa-solid fa-circle-check"></i>
        <span>{{ session('status') }}</span>
      </div>
    @endif

    @if ($errors->any())
      <div class="form-alert form-alert-error">
        <i class="fa-solid fa-circle-exclamation"></i>
        <span>{{ $errors->first() }}</span>
      </div>
    @endif

    <div class="admin-dashboard-layout">
      @include('admin.partials.sidebar')

      <div class="admin-dashboard-content">
        <section class="admin-dashboard-panel admin-dashboard-overview-panel">
          <div class="admin-dashboard-panel-head admin-dashboard-panel-head-inline">
            <div>
              <span class="admin-dashboard-panel-tag">Book overview</span>
              <h2>Quick snapshot</h2>
            </div>
            <a href="{{ route('admin.upload-book') }}" class="admin-dashboard-action-link">Create Book</a>
          </div>

          <div class="admin-dashboard-stats-grid">
            <article class="admin-dashboard-stat-card">
              <span class="admin-dashboard-stat-label">Showing</span>
              <strong>{{ $books->count() }}</strong>
              <p>{{ $books->count() === $totalBooks ? 'All book records are visible.' : 'Filtered from ' . $totalBooks . ' total books.' }}</p>
            </article>
            <article class="admin-dashboard-stat-card">
              <span class="admin-dashboard-stat-label">Testaments covered</span>
              <strong>{{ $coveredTestaments }}</strong>
              <p>Distinct testament groups represented in the current result set.</p>
            </article>
            <article class="admin-dashboard-stat-card">
              <span class="admin-dashboard-stat-label">Attached chapters</span>
              <strong>{{ $totalChaptersAcrossResults }}</strong>
              <p>Total chapter records connected to the books in this view.</p>
            </article>
          </div>
        </section>

        <section class="admin-dashboard-panel">
          <div class="admin-dashboard-panel-head admin-dashboard-panel-head-inline">
            <div>
              <span class="admin-dashboard-panel-tag">Filter books</span>
              <h2>Find the exact book records you need</h2>
            </div>
            @if ($activeFilters)
              <a href="{{ route('admin.list-books') }}" class="secondary-button admin-dashboard-filter-reset">Clear All</a>
            @endif
          </div>

          <form action="{{ route('admin.list-books') }}" method="GET" class="admin-auth-form admin-dashboard-filter-form">
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
              <span>Book Name</span>
              <input type="text" name="book_name" value="{{ request('book_name') }}" placeholder="Search by book name" />
            </label>

            <label class="admin-auth-field">
              <span>Chapter Count</span>
              <input type="number" name="chapter_count" value="{{ request('chapter_count') }}" min="0" placeholder="e.g. 5" />
            </label>

            <div class="admin-dashboard-record-actions">
              <button type="submit" class="primary-button admin-auth-submit">Filter</button>
              <a href="{{ route('admin.list-books') }}" class="secondary-button">Reset</a>
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

        <section class="admin-dashboard-panel">
          <div class="admin-dashboard-panel-head admin-dashboard-panel-head-inline">
            <div>
              <span class="admin-dashboard-panel-tag">Saved books</span>
              <h2>Current book records</h2>
            </div>
            <div class="admin-dashboard-table-meta">
              <span>{{ $books->count() }} result{{ $books->count() === 1 ? '' : 's' }}</span>
            </div>
          </div>

          @if ($books->isEmpty())
            <div class="admin-dashboard-empty-state">
              <i class="fa-solid fa-book"></i>
              <h3>No books matched this view</h3>
              <p>{{ $activeFilters ? 'Try adjusting your filters or clear them to see more book records.' : 'No books have been uploaded yet.' }}</p>
            </div>
          @else
            <div class="admin-dashboard-table-wrap">
              <table class="admin-dashboard-table">
                <thead>
                  <tr>
                    <th>S/N</th>
                    <th>Testament</th>
                    <th>Book Name</th>
                    <th>Chapters</th>
                    <th>Created</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($books as $index => $book)
                    <tr>
                      <td>{{ $index + 1 }}</td>
                      <td><span class="admin-dashboard-verse-badge">{{ $book->testament?->name ?? 'No testament' }}</span></td>
                      <td>
                        <div class="admin-dashboard-reference-stack">
                          <strong>{{ $book->name }}</strong>
                          <span>{{ $book->chapters_count }} chapter{{ $book->chapters_count === 1 ? '' : 's' }} linked</span>
                        </div>
                      </td>
                      <td>{{ $book->chapters_count }}</td>
                      <td>{{ $book->created_at?->format('M d, Y') }}</td>
                      <td>
                        <div class="admin-dashboard-table-actions">
                          <a href="{{ route('admin.edit-book', $book) }}" class="secondary-button">Edit</a>
                          <a href="{{ route('admin.delete-book', $book) }}" class="admin-dashboard-delete-link">Delete</a>
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
