@extends('layout.app')

@section('title', 'List Testaments')
@section('body_class', 'admin-auth-body')

@section('content')
@php
  $activeFilters = array_filter([
    'name' => request('testament_name'),
    'summary' => request('summary'),
    'books' => request('book_count'),
  ], fn ($value) => filled($value));
  $totalBooksAcrossResults = $testaments->sum('books_count');
  $withSummaries = $testaments->filter(fn ($testament) => filled($testament->summary))->count();
@endphp
<main class="admin-dashboard-main">
  <section class="admin-dashboard-card">
    <div class="admin-dashboard-intro">
      <span class="admin-auth-eyebrow">Bible structure</span>
      <h1>List Testaments</h1>
      <p>Review every saved testament entry in one place.</p>
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
        <section class="admin-dashboard-panel admin-dashboard-overview-panel">
          <div class="admin-dashboard-panel-head admin-dashboard-panel-head-inline">
            <div>
              <span class="admin-dashboard-panel-tag">Testament overview</span>
              <h2>Quick snapshot</h2>
            </div>
            <a href="{{ route('admin.upload-testament') }}" class="admin-dashboard-action-link">Create Testament</a>
          </div>

          <div class="admin-dashboard-stats-grid">
            <article class="admin-dashboard-stat-card">
              <span class="admin-dashboard-stat-label">Showing</span>
              <strong>{{ $testaments->count() }}</strong>
              <p>{{ $testaments->count() === $totalTestaments ? 'All testament records are visible.' : 'Filtered from ' . $totalTestaments . ' total testaments.' }}</p>
            </article>
            <article class="admin-dashboard-stat-card">
              <span class="admin-dashboard-stat-label">Linked books</span>
              <strong>{{ $totalBooksAcrossResults }}</strong>
              <p>Total books currently attached to the testaments in this view.</p>
            </article>
            <article class="admin-dashboard-stat-card">
              <span class="admin-dashboard-stat-label">With summaries</span>
              <strong>{{ $withSummaries }}</strong>
              <p>Testaments in this result set that already have a summary.</p>
            </article>
          </div>
        </section>

        <section class="admin-dashboard-panel">
          <div class="admin-dashboard-panel-head admin-dashboard-panel-head-inline">
            <div>
              <span class="admin-dashboard-panel-tag">Filter testaments</span>
              <h2>Find the exact testament records you need</h2>
            </div>
            @if ($activeFilters)
              <a href="{{ route('admin.list-testaments') }}" class="secondary-button admin-dashboard-filter-reset">Clear All</a>
            @endif
          </div>

          <form action="{{ route('admin.list-testaments') }}" method="GET" class="admin-auth-form admin-dashboard-filter-form">
            <label class="admin-auth-field">
              <span>Testament Name</span>
              <input type="text" name="testament_name" value="{{ request('testament_name') }}" placeholder="Search by testament name" />
            </label>

            <label class="admin-auth-field">
              <span>Summary Text</span>
              <input type="text" name="summary" value="{{ request('summary') }}" placeholder="Search inside summary" />
            </label>

            <label class="admin-auth-field">
              <span>Book Count</span>
              <input type="number" name="book_count" value="{{ request('book_count') }}" min="0" placeholder="e.g. 39" />
            </label>

            <div class="admin-dashboard-record-actions">
              <button type="submit" class="primary-button admin-auth-submit">Filter</button>
              <a href="{{ route('admin.list-testaments') }}" class="secondary-button">Reset</a>
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
              <span class="admin-dashboard-panel-tag">Saved testaments</span>
              <h2>Current testament records</h2>
            </div>
            <div class="admin-dashboard-table-meta">
              <span>{{ $testaments->count() }} result{{ $testaments->count() === 1 ? '' : 's' }}</span>
            </div>
          </div>

          @if ($testaments->isEmpty())
            <div class="admin-dashboard-empty-state">
              <i class="fa-solid fa-book-bible"></i>
              <h3>No testaments matched this view</h3>
              <p>{{ $activeFilters ? 'Try adjusting your filters or clear them to see more testament records.' : 'No testaments have been uploaded yet.' }}</p>
            </div>
          @else
            <div class="admin-dashboard-table-wrap">
              <table class="admin-dashboard-table">
                <thead>
                  <tr>
                    <th>S/N</th>
                    <th>Testament Name</th>
                    <th>Summary</th>
                    <th>Books</th>
                    <th>Created</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($testaments as $index => $testament)
                    <tr>
                      <td>{{ $index + 1 }}</td>
                      <td>
                        <div class="admin-dashboard-reference-stack">
                          <strong>{{ $testament->name }}</strong>
                          <span>{{ $testament->books_count }} linked book{{ $testament->books_count === 1 ? '' : 's' }}</span>
                        </div>
                      </td>
                      <td>{{ $testament->summary ?: 'No summary added yet.' }}</td>
                      <td><span class="admin-dashboard-verse-badge">{{ $testament->books_count }} book{{ $testament->books_count === 1 ? '' : 's' }}</span></td>
                      <td>{{ $testament->created_at?->format('M d, Y') }}</td>
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
