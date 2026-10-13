<div class="admin-pagination">
  <p>{{ $records->firstItem() ?? 0 }}–{{ $records->lastItem() ?? 0 }} of {{ $records->total() }} matching records</p>
  @if ($records->hasPages())
    <nav aria-label="Pagination">
      @if ($records->onFirstPage()) <span aria-disabled="true">Previous</span>
      @else <a href="{{ $records->previousPageUrl() }}" rel="prev">Previous</a> @endif
      @foreach ($records->getUrlRange(max(1, $records->currentPage() - 2), min($records->lastPage(), $records->currentPage() + 2)) as $page => $url)
        <a href="{{ $url }}" @if($page === $records->currentPage()) aria-current="page" @endif aria-label="Page {{ $page }}">{{ $page }}</a>
      @endforeach
      @if ($records->hasMorePages()) <a href="{{ $records->nextPageUrl() }}" rel="next">Next</a>
      @else <span aria-disabled="true">Next</span> @endif
    </nav>
  @endif
</div>
