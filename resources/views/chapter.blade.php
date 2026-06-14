@extends('layout.app')

@section('title', ($chapter->book?->name ?? $chapter->book_name) . ' ' . $chapter->chapter_number)

@section('content')
@php
  $verseCount = $chapter->verses->count();
  $chapterTopics = $chapter->verses->pluck('prayer_topic')->filter()->unique()->values();
  $topicCount = $chapterTopics->count();
@endphp
<main>
  <section class="content-section chapter-page-section">
    <div class="chapter-page-shell">
      <section class="chapter-intro-card">
        <div class="chapter-intro-copy">
          <p class="section-tag">{{ $chapter->book?->testament?->name ?? 'Scripture' }}</p>
          <h1 class="section-caption">{{ $chapter->book?->name ?? $chapter->book_name }} {{ $chapter->chapter_number }}</h1>
          <p class="hero-text">Read through the full chapter and move into prayer from the word.</p>
        </div>

        <div class="chapter-intro-meta">
          <div class="chapter-meta-chip">
            <strong>{{ $verseCount }}</strong>
            <span>{{ $verseCount === 1 ? 'Verse' : 'Verses' }}</span>
          </div>
          <div class="chapter-meta-chip">
            <strong>{{ $topicCount }}</strong>
            <span>{{ $topicCount === 1 ? 'Topic' : 'Topics' }}</span>
          </div>
          <div class="chapter-meta-chip">
            <strong>{{ $chapter->chapter_number }}</strong>
            <span>Chapter</span>
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
            <p class="section-tag">Chapter Reading</p>
            <h2>Verse-by-verse prayer guide</h2>
          </div>
          <span class="chapter-reading-note">Follow each verse slowly and prayerfully.</span>
        </div>

        @if ($chapterTopics->isNotEmpty())
          <section class="chapter-topic-filter" data-chapter-topic-filter>
            <div class="chapter-topic-filter-head">
              <div>
                <p class="section-tag">Uploaded Topics</p>
                <h3>Select a prayer topic related to this chapter</h3>
              </div>
              <span class="chapter-topic-filter-note">Only topics uploaded under {{ $chapter->book?->name ?? $chapter->book_name }} {{ $chapter->chapter_number }} appear here.</span>
            </div>

            <div class="chapter-topic-chip-row" role="tablist" aria-label="Chapter prayer topics">
              <button type="button" class="chapter-topic-chip is-active" data-topic-filter="all" aria-pressed="true">All topics</button>
              @foreach ($chapterTopics as $topic)
                <button type="button" class="chapter-topic-chip" data-topic-filter="{{ $topic }}" aria-pressed="false">{{ $topic }}</button>
              @endforeach
            </div>
          </section>
        @endif

      @forelse ($chapter->verses as $verse)
        @if ($loop->first)
          <ol class="chapter-verse-list" data-chapter-topic-results>
        @endif
        <li class="chapter-verse-item" data-topic-item="{{ filled($verse->prayer_topic) ? $verse->prayer_topic : 'untitled' }}">
          <article class="chapter-verse-card">
            <div class="chapter-verse-head">
              <span class="icon chapter-verse-icon">📖</span>
              <div class="chapter-verse-heading-copy">
                @if (filled($verse->prayer_topic))
                  <h3 class="chapter-verse-topic">{{ $verse->prayer_topic }}</h3>
                @endif
                <div class="chapter-verse-meta-row">
                  <span class="word-label">Verse {{ $verse->verse_number }}</span>
                  @if ($verse->category)
                    <span class="chapter-verse-category">{{ $verse->category->name }}</span>
                  @endif
                </div>
              </div>
            </div>
            <div class="chapter-verse-body">{!! $verse->verse_text !!}</div>
          </article>
        </li>
        @if ($loop->last)
          </ol>
        @endif
      @empty
        <article class="word-item">
          <span class="icon">📘</span>
          <div class="word-copy">
            <span class="word-label">No verses added yet</span>
            <span class="word-meta">This chapter is visible on the home page, but its verses have not been uploaded yet.</span>
          </div>
        </article>
      @endforelse
      </section>
    </div>
  </section>
</main>
@endsection
