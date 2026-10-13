@php
  $listUrl = route('admin.list-' . $plural);
  $activeFilters = collect($filterFields)->filter(fn ($field) => filled(request($field['name'])));
@endphp
<main class="admin-dashboard-main">
  <section class="admin-dashboard-card">
    <div class="admin-dashboard-intro admin-library-intro">
      <div><span class="admin-auth-eyebrow">Scripture library</span><h1>{{ ucfirst($plural) }}</h1><p>Browse, review, and manage your {{ $plural }}.</p></div>
      <a href="{{ route('admin.upload-' . $type) }}" class="primary-button"><i class="fa-solid fa-plus" aria-hidden="true"></i> Add {{ $type }}</a>
    </div>
    <div class="admin-dashboard-layout">
      @include('admin.partials.sidebar')
      <div class="admin-dashboard-content">
        <section class="admin-dashboard-stats-grid" aria-label="Library totals">
          <article class="admin-dashboard-stat-card"><span class="admin-dashboard-stat-label">Total {{ $plural }}</span><strong>{{ number_format($total) }}</strong><p>Across the entire library</p></article>
          <article class="admin-dashboard-stat-card"><span class="admin-dashboard-stat-label">Matching records</span><strong>{{ number_format($records->total()) }}</strong><p>{{ $activeFilters->isEmpty() ? 'All records' : 'With your current filters' }}</p></article>
          <article class="admin-dashboard-stat-card"><span class="admin-dashboard-stat-label">On this page</span><strong>{{ $records->count() }}</strong><p>Page {{ $records->currentPage() }} of {{ $records->lastPage() }}</p></article>
        </section>
        <section class="admin-dashboard-panel">
          <div class="admin-dashboard-panel-head admin-dashboard-panel-head-inline"><h2>Find {{ $plural }}</h2>@if($activeFilters->isNotEmpty())<a href="{{ $listUrl }}" class="admin-back-link">Clear filters</a>@endif</div>
          <form action="{{ $listUrl }}" method="GET" class="admin-auth-form admin-dashboard-filter-form">
            @foreach ($filterFields as $field)
              <label class="admin-auth-field">
                <span>{{ $field['label'] }}</span>
                @if (isset($field['options']))
                  <select name="{{ $field['name'] }}"><option value="">All {{ strtolower($field['label']) }}s</option>@foreach($field['options'] as $value => $label)<option value="{{ $value }}" @selected((string) request($field['name']) === (string) $value)>{{ $label }}</option>@endforeach</select>
                @else
                  <input type="{{ $field['type'] ?? 'text' }}" name="{{ $field['name'] }}" value="{{ request($field['name']) }}" placeholder="{{ $field['label'] }}" @if(($field['type'] ?? '') === 'number') min="{{ $field['min'] ?? 0 }}" @endif />
                @endif
              </label>
            @endforeach
            <div class="admin-dashboard-record-actions"><button type="submit" class="primary-button admin-auth-submit"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i> Apply filters</button><a href="{{ $listUrl }}" class="secondary-button">Reset</a></div>
          </form>
          @if($activeFilters->isNotEmpty())<div class="admin-dashboard-filter-chips">@foreach($activeFilters as $field)<span class="admin-dashboard-filter-chip">{{ $field['label'] }}: {{ isset($field['options']) ? ($field['options'][request($field['name'])] ?? request($field['name'])) : request($field['name']) }}</span>@endforeach</div>@endif
        </section>
        <section class="admin-dashboard-panel">
          <div class="admin-dashboard-panel-head admin-dashboard-panel-head-inline"><h2>{{ ucfirst($plural) }} library</h2><span class="admin-dashboard-table-meta">{{ $records->total() }} records</span></div>
          @if ($records->isEmpty())
            <div class="admin-dashboard-empty-state"><i class="fa-regular fa-folder-open" aria-hidden="true"></i><h3>{{ $activeFilters->isEmpty() ? 'No ' . $plural . ' yet' : 'No matching records' }}</h3><p>{{ $activeFilters->isEmpty() ? 'Add your first ' . $type . ' to start this library.' : 'Try another search or clear your filters.' }}</p><a href="{{ $activeFilters->isEmpty() ? route('admin.upload-' . $type) : $listUrl }}" class="secondary-button">{{ $activeFilters->isEmpty() ? 'Add ' . $type : 'Clear filters' }}</a></div>
          @else
            <div class="admin-dashboard-table-wrap">
              <table class="admin-dashboard-table">
                <caption class="admin-sr-only">{{ ucfirst($plural) }} with view, edit, and delete actions</caption>
                <thead><tr><th scope="col">#</th><th scope="col">{{ $type === 'verse' || $type === 'chapter' ? 'Reference' : 'Name' }}</th><th scope="col">Details</th><th scope="col">Created</th><th scope="col">Actions</th></tr></thead>
                <tbody>
                  @foreach ($records as $index => $record)
                    <tr>
                      <td>{{ $records->firstItem() + $index }}</td>
                      <td><a class="admin-record-title" href="{{ route('admin.show-' . $type, $record) }}">@if($type === 'verse'){{ $record->chapter?->book?->name ?? $record->chapter?->book_name }} {{ $record->chapter?->chapter_number }}:{{ $record->verse_number }}@elseif($type === 'chapter'){{ $record->book?->name ?? $record->book_name }} {{ $record->chapter_number }}@else{{ $record->name }}@endif</a>@if($type === 'verse')<small class="admin-record-subtitle">{{ $record->prayer_topic }}</small>@endif</td>
                      <td class="admin-record-details">
                        @switch($type)
                          @case('category') <span>{{ $record->verses_count }} linked verses</span><small>{{ \Illuminate\Support\Str::limit($record->description ?: 'No description', 100) }}</small> @break
                          @case('testament') <span>{{ $record->books_count }} linked books</span><small>{{ \Illuminate\Support\Str::limit($record->summary ?: 'No summary', 100) }}</small> @break
                          @case('book') <span>{{ $record->testament?->name ?? 'No testament' }}</span><small>{{ $record->chapters_count }} chapters</small> @break
                          @case('chapter') <span>{{ $record->book?->testament?->name ?? 'No testament' }}</span><small>{{ $record->verses_count }} verses</small> @break
                          @case('verse') <span>{{ $record->category?->name ?? 'Uncategorized' }}</span><small>{{ \Illuminate\Support\Str::limit(html_entity_decode(strip_tags($record->verse_text), ENT_QUOTES | ENT_HTML5, 'UTF-8'), 100) }}</small> @break
                        @endswitch
                      </td>
                      <td class="admin-record-date">{{ $record->created_at?->format('M d, Y') }}</td>
                      <td>@include('admin.partials.record-actions', ['type' => $type, 'record' => $record])</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
          @include('admin.partials.pagination', ['records' => $records])
        </section>
      </div>
    </div>
  </section>
</main>
