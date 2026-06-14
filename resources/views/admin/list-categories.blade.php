@extends('layout.app')

@section('title', 'List Categories')
@section('body_class', 'admin-auth-body')

@section('content')
@php
  $activeFilters = array_filter([
    'name' => request('category_name'),
    'description' => request('description'),
  ], fn ($value) => filled($value));
  $withDescriptions = $categories->filter(fn ($category) => filled($category->description))->count();
@endphp
<main class="admin-dashboard-main">
  <section class="admin-dashboard-card">
    <div class="admin-dashboard-intro">
      <span class="admin-auth-eyebrow">Content setup</span>
      <h1>List Categories</h1>
      <p>Review every saved prayer category and choose whether to edit or delete it on its own page.</p>
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
              <span class="admin-dashboard-panel-tag">Category overview</span>
              <h2>Quick snapshot</h2>
            </div>
            <a href="{{ route('admin.upload-category') }}" class="admin-dashboard-action-link">Create Category</a>
          </div>

          <div class="admin-dashboard-stats-grid">
            <article class="admin-dashboard-stat-card">
              <span class="admin-dashboard-stat-label">Showing</span>
              <strong>{{ $categories->count() }}</strong>
              <p>{{ $categories->count() === $totalCategories ? 'All category records are visible.' : 'Filtered from ' . $totalCategories . ' total categories.' }}</p>
            </article>
            <article class="admin-dashboard-stat-card">
              <span class="admin-dashboard-stat-label">With descriptions</span>
              <strong>{{ $withDescriptions }}</strong>
              <p>Categories in this result set that already have a description.</p>
            </article>
            <article class="admin-dashboard-stat-card">
              <span class="admin-dashboard-stat-label">Need details</span>
              <strong>{{ $categories->count() - $withDescriptions }}</strong>
              <p>Categories in this view that still need a fuller description.</p>
            </article>
          </div>
        </section>

        <section class="admin-dashboard-panel">
          <div class="admin-dashboard-panel-head admin-dashboard-panel-head-inline">
            <div>
              <span class="admin-dashboard-panel-tag">Filter categories</span>
              <h2>Find the exact category records you need</h2>
            </div>
            @if ($activeFilters)
              <a href="{{ route('admin.list-categories') }}" class="secondary-button admin-dashboard-filter-reset">Clear All</a>
            @endif
          </div>

          <form action="{{ route('admin.list-categories') }}" method="GET" class="admin-auth-form admin-dashboard-filter-form">
            <label class="admin-auth-field">
              <span>Category Name</span>
              <input type="text" name="category_name" value="{{ request('category_name') }}" placeholder="Search by category name" />
            </label>

            <label class="admin-auth-field">
              <span>Description Text</span>
              <input type="text" name="description" value="{{ request('description') }}" placeholder="Search inside description" />
            </label>

            <div class="admin-dashboard-record-actions">
              <button type="submit" class="primary-button admin-auth-submit">Filter</button>
              <a href="{{ route('admin.list-categories') }}" class="secondary-button">Reset</a>
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
              <span class="admin-dashboard-panel-tag">Saved categories</span>
              <h2>Current prayer categories</h2>
            </div>
            <div class="admin-dashboard-table-meta">
              <span>{{ $categories->count() }} result{{ $categories->count() === 1 ? '' : 's' }}</span>
            </div>
          </div>

          @if ($categories->isEmpty())
            <div class="admin-dashboard-empty-state">
              <i class="fa-solid fa-folder-tree"></i>
              <h3>No categories matched this view</h3>
              <p>{{ $activeFilters ? 'Try adjusting your filters or clear them to see more category records.' : 'No categories have been uploaded yet.' }}</p>
            </div>
          @else
            <div class="admin-dashboard-table-wrap">
              <table class="admin-dashboard-table">
                <thead>
                  <tr>
                    <th>S/N</th>
                    <th>Category Name</th>
                    <th>Description</th>
                    <th>Created</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($categories as $index => $category)
                    <tr>
                      <td>{{ $index + 1 }}</td>
                      <td>
                        <div class="admin-dashboard-reference-stack">
                          <strong>{{ $category->name }}</strong>
                          <span>{{ filled($category->description) ? 'Description available' : 'Needs description' }}</span>
                        </div>
                      </td>
                      <td>{{ $category->description ?: 'No description added yet.' }}</td>
                      <td>{{ $category->created_at?->format('M d, Y') }}</td>
                      <td>
                        <div class="admin-dashboard-table-actions">
                          <a href="{{ route('admin.edit-category', $category) }}" class="secondary-button">Edit</a>
                          <a href="{{ route('admin.delete-category', $category) }}" class="admin-dashboard-delete-link">Delete</a>
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
