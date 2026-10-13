@php
  $navigation = [
    ['label' => 'Overview', 'route' => 'admin.dashboard', 'icon' => 'fa-table-columns', 'patterns' => ['admin.dashboard']],
    ['label' => 'Categories', 'route' => 'admin.list-categories', 'icon' => 'fa-folder-tree', 'patterns' => ['admin.*category*', 'admin.list-categories']],
    ['label' => 'Testaments', 'route' => 'admin.list-testaments', 'icon' => 'fa-book-bible', 'patterns' => ['admin.*testament*']],
    ['label' => 'Books', 'route' => 'admin.list-books', 'icon' => 'fa-book', 'patterns' => ['admin.*book*']],
    ['label' => 'Chapters', 'route' => 'admin.list-chapters', 'icon' => 'fa-book-open-reader', 'patterns' => ['admin.*chapter*']],
    ['label' => 'Verses', 'route' => 'admin.list-verses', 'icon' => 'fa-quote-left', 'patterns' => ['admin.*verse*']],
    ['label' => 'Account security', 'route' => 'admin.change-password', 'icon' => 'fa-key', 'patterns' => ['admin.change-password*']],
  ];
@endphp
<aside class="admin-dashboard-sidebar" aria-label="Administration">
  <div class="admin-dashboard-sidebar-head"><span class="admin-sidebar-caption">Workspace</span><h2>Scripture library</h2></div>
  <nav class="admin-dashboard-nav" aria-label="Admin navigation">
    @foreach ($navigation as $item)
      @php $active = request()->routeIs(...$item['patterns']); @endphp
      <a href="{{ route($item['route']) }}" class="{{ $active ? 'is-active' : '' }}" @if($active) aria-current="page" @endif><i class="fa-solid {{ $item['icon'] }}" aria-hidden="true"></i><span>{{ $item['label'] }}</span></a>
    @endforeach
  </nav>
  <div class="admin-sidebar-create"><a href="{{ route('admin.upload-verse') }}" class="primary-button"><i class="fa-solid fa-plus" aria-hidden="true"></i> Add verse</a><p>Build the library, one prayer at a time.</p></div>
  <div class="admin-dashboard-sidebar-meta"><strong>{{ auth()->user()->name }}</strong><span>{{ auth()->user()->email }}</span><small>Administrator</small></div>
</aside>
