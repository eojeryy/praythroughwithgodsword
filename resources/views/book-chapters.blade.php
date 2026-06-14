@extends('layout.app')

@section('title', ($book->name ?? 'Book') . ' Chapters')

@section('content')
@php
  $chapterCount = $book->chapters->count();
  $topicCount = $book->chapters->flatMap->verses->pluck('prayer_topic')->filter()->unique()->count();
@endphp
<main>
  <section class="content-section chapter-page-section">
    <div class="chapter-page-shell">
      <section class="chapter-intro-card book-chapters-intro-card">
        <div class="chapter-intro-copy">
          <p class="section-tag">{{ $book->testament?->name ?? 'Scripture' }}</p>
          <h1 class="section-caption">{{ $book->name }}</h1>
          <p class="hero-text">Browse every uploaded chapter for this book and open the exact chapter you want to read and pray through.</p>
        </div>

        <div class="chapter-intro-meta">
          <div class="chapter-meta-chip">
            <strong>{{ $chapterCount }}</strong>
            <span>{{ $chapterCount === 1 ? 'Chapter' : 'Chapters' }}</span>
          </div>
          <div class="chapter-meta-chip">
            <strong>{{ $topicCount }}</strong>
            <span>{{ $topicCount === 1 ? 'Topic' : 'Topics' }}</span>
          </div>
          <div class="chapter-meta-chip">
            <strong>{{ $book->chapters->flatMap->verses->count() }}</strong>
            <span>{{ $book->chapters->flatMap->verses->count() === 1 ? 'Verse' : 'Verses' }}</span>
          </div>
        </div>

        <div class="hero-actions chapter-actions">
          <a href="{{ route('home') }}#specific-prayer" class="secondary-button chapter-back-button">Back to Home</a>
          <a href="{{ route('live') }}" class="live-button">Join Live Prayer</a>
        </div>
      </section>

      <section class="chapter-verse-section">
        <div class="chapter-section-head">
          <div>
            <p class="section-tag">Uploaded Chapters</p>
            <h2>Choose a chapter from {{ $book->name }}</h2>
          </div>
          <span class="chapter-reading-note">Only uploaded chapters for this book appear here.</span>
        </div>

        @if ($book->chapters->isNotEmpty())
          <div class="book-chapters-grid">
            @foreach ($book->chapters as $chapter)
              <a href="{{ route('chapters.show', $chapter) }}" class="book-chapters-card">
                <div class="book-chapters-card-head">
                  <span class="book-chapters-badge">Chapter {{ $chapter->chapter_number }}</span>
                  <span class="book-chapters-meta">{{ $chapter->verses->count() }} verse{{ $chapter->verses->count() === 1 ? '' : 's' }}</span>
                </div>
                <strong>{{ $book->name }} {{ $chapter->chapter_number }}</strong>
                <p>
                  {{ $chapter->verses->pluck('prayer_topic')->filter()->unique()->count() }}
                  topic{{ $chapter->verses->pluck('prayer_topic')->filter()->unique()->count() === 1 ? '' : 's' }}
                  available in this uploaded chapter.
                </p>
              </a>
            @endforeach
          </div>
        @else
          <article class="word-item">
            <span class="icon">📘</span>
            <div class="word-copy">
              <span class="word-label">No uploaded chapters yet</span>
              <span class="word-meta">This book exists, but no chapters have been uploaded for it yet.</span>
            </div>
          </article>
        @endif
      </section>
    </div>
  </section>
</main>
@endsection
