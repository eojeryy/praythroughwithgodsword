@extends('layout.app')

@section('title', $topic)

@section('content')
@php
  $verseCount = $verses->count();
  $bookCount = $verses->pluck('chapter.book.name')->filter()->unique()->count();
  $chapterCount = $verses->pluck('chapter_id')->filter()->unique()->count();
  $sectionTag = $sectionTag ?? ($category?->name ?? 'Prayer Topic');
  $backUrl = $backUrl ?? route('home');
  $backLabel = $backLabel ?? 'Back';
@endphp
<main>
  <section class="content-section chapter-page-section">
    <div class="chapter-page-shell">
      <section class="chapter-intro-card topic-detail-intro-card">
        <div class="chapter-intro-copy">
          <p class="section-tag">{{ $sectionTag }}</p>
          <h1 class="section-caption">{{ $topic }}</h1>
          <p class="hero-text">Read the uploaded verse details and scripture references currently stored under this topic.</p>
        </div>

        <div class="chapter-intro-meta">
          <div class="chapter-meta-chip">
            <strong>{{ $verseCount }}</strong>
            <span>{{ $verseCount === 1 ? 'Verse Record' : 'Verse Records' }}</span>
          </div>
          <div class="chapter-meta-chip">
            <strong>{{ $bookCount }}</strong>
            <span>{{ $bookCount === 1 ? 'Book' : 'Books' }}</span>
          </div>
          <div class="chapter-meta-chip">
            <strong>{{ $chapterCount }}</strong>
            <span>{{ $chapterCount === 1 ? 'Chapter' : 'Chapters' }}</span>
          </div>
        </div>

        <div class="hero-actions chapter-actions">
          <a href="{{ $backUrl }}" class="secondary-button chapter-back-button">{{ $backLabel }}</a>
          <a href="{{ route('live') }}" class="live-button">Join Live Prayer</a>
        </div>
      </section>

      <section class="chapter-verse-section">
        <div class="chapter-section-head">
          <div>
            <p class="section-tag">Topic Details</p>
            <h2>Uploaded verses under {{ $topic }}</h2>
          </div>
          <span class="chapter-reading-note">Open any chapter reference to read the full chapter view.</span>
        </div>

        <div class="topic-detail-list">
          @foreach ($verses as $verse)
            @if ($verse->chapter)
              <a href="{{ route('chapters.show', $verse->chapter) }}" class="topic-detail-card topic-detail-card-link">
                <div class="topic-detail-card-head">
                  <div class="topic-detail-reference">
                    <strong>{{ $verse->chapter?->book?->name ?? $verse->chapter?->book_name }} {{ $verse->chapter?->chapter_number }}:{{ $verse->verse_number }}</strong>
                    <span>{{ $verse->chapter?->book?->testament?->name ?? 'Scripture' }}</span>
                  </div>
                  <span class="topic-detail-link">Open Chapter</span>
                </div>
                <div class="topic-detail-body">{!! $verse->verse_text !!}</div>
              </a>
            @else
              <article class="topic-detail-card">
                <div class="topic-detail-card-head">
                  <div class="topic-detail-reference">
                    <strong>{{ $verse->chapter?->book?->name ?? $verse->chapter?->book_name }} {{ $verse->chapter?->chapter_number }}:{{ $verse->verse_number }}</strong>
                    <span>{{ $verse->chapter?->book?->testament?->name ?? 'Scripture' }}</span>
                  </div>
                </div>
                <div class="topic-detail-body">{!! $verse->verse_text !!}</div>
              </article>
            @endif
          @endforeach
        </div>
      </section>
    </div>
  </section>
</main>
@endsection
