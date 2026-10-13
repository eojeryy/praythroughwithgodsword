@extends('layout.admin')
@section('title', ucfirst($type) . ' Details')
@section('body_class', 'admin-workspace')
@section('content')
@php
  $libraries = ['category' => 'categories', 'testament' => 'testaments', 'book' => 'books', 'chapter' => 'chapters', 'verse' => 'verses'];
@endphp
<main class="admin-dashboard-main">
  <section class="admin-dashboard-card">
    <div class="admin-dashboard-intro">
      <span class="admin-auth-eyebrow">{{ ucfirst($type) }} details</span>
      <h1>{{ $title }}</h1>
      <p>Review this record and manage its linked content.</p>
    </div>
    <div class="admin-dashboard-layout">
      @include('admin.partials.sidebar')
      <div class="admin-dashboard-content">
        <section class="admin-dashboard-panel">
          <div class="admin-dashboard-panel-head admin-dashboard-panel-head-inline">
            <a href="{{ route('admin.list-' . $libraries[$type]) }}" class="admin-back-link">← Back to {{ $libraries[$type] }}</a>
            @include('admin.partials.record-actions', ['type' => $type, 'record' => $record])
          </div>
          <dl class="admin-detail-facts">
            @foreach ($facts as $label => $value)
              <div><dt>{{ $label }}</dt><dd>{{ $value }}</dd></div>
            @endforeach
            <div><dt>Created</dt><dd>{{ $record->created_at?->format('M d, Y') ?? '—' }}</dd></div>
            <div><dt>Last updated</dt><dd>{{ $record->updated_at?->format('M d, Y') ?? '—' }}</dd></div>
          </dl>
          @if (filled($description)) <div class="admin-detail-description">{{ $description }}</div> @endif
          @if ($publicUrl) <a href="{{ $publicUrl }}" class="secondary-button">Preview on website <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i></a> @endif
        </section>
        @if ($children)
          <section class="admin-dashboard-panel">
            <div class="admin-dashboard-panel-head admin-dashboard-panel-head-inline">
              <div><span class="admin-dashboard-panel-tag">Linked content</span><h2>{{ $childLabel }} <small>({{ $children->total() }})</small></h2></div>
              <a href="{{ route('admin.upload-' . $childType, [$type . '_id' => $record->id]) }}" class="admin-dashboard-action-link">Add {{ $childType }}</a>
            </div>
            @if ($children->isEmpty())
              <div class="admin-dashboard-empty-state"><h3>No {{ strtolower($childLabel) }} yet</h3><p>Add content to start building this part of the library.</p></div>
            @else
              <div class="admin-dashboard-table-wrap">
                <table class="admin-dashboard-table">
                  <thead><tr><th>{{ $childType === 'verse' ? 'Reference / topic' : 'Name' }}</th><th>Details</th><th>Actions</th></tr></thead>
                  <tbody>
                    @foreach ($children as $child)
                      <tr>
                        <td><strong>{{ $child->name ?? ($childType === 'chapter' ? 'Chapter ' . $child->chapter_number : $child->prayer_topic) }}</strong>@if($childType === 'verse')<div>{{ $child->chapter?->book?->name ?? $child->chapter?->book_name }} {{ $child->chapter?->chapter_number }}:{{ $child->verse_number }}</div>@endif</td>
                        <td>@if($childType === 'book') {{ $child->chapters_count }} chapters @elseif($childType === 'chapter') {{ $child->verses_count }} verses @else {{ \Illuminate\Support\Str::limit(html_entity_decode(strip_tags($child->verse_text), ENT_QUOTES | ENT_HTML5, 'UTF-8'), 140) }} @endif</td>
                        <td>@include('admin.partials.record-actions', ['type' => $childType, 'record' => $child])</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @endif
            @include('admin.partials.pagination', ['records' => $children])
          </section>
        @endif
      </div>
    </div>
  </section>
</main>
@endsection
