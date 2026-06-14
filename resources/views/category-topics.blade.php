@extends('layout.app')

@section('title', ($category->name ?? 'Category') . ' Topics')

@section('content')
@php
  $topicCount = $topics->count();
  $verseCount = $category->verses->count();
  $bookCount = $category->verses->pluck('chapter.book.name')->filter()->unique()->count();
@endphp
<main>
  <section class="content-section chapter-page-section">
    <div class="chapter-page-shell">
      <section class="chapter-intro-card category-topics-intro-card">
        <div class="chapter-intro-copy">
          <p class="section-tag">Prayer Category</p>
          <h1 class="section-caption">{{ $category->name }}</h1>
          <p class="hero-text">{{ $category->description ?: 'Browse all uploaded prayer topics currently organized under this category.' }}</p>
        </div>

        <div class="chapter-intro-meta">
          <div class="chapter-meta-chip">
            <strong>{{ $topicCount }}</strong>
            <span>{{ $topicCount === 1 ? 'Topic' : 'Topics' }}</span>
          </div>
          <div class="chapter-meta-chip">
            <strong>{{ $verseCount }}</strong>
            <span>{{ $verseCount === 1 ? 'Verse' : 'Verses' }}</span>
          </div>
          <div class="chapter-meta-chip">
            <strong>{{ $bookCount }}</strong>
            <span>{{ $bookCount === 1 ? 'Book' : 'Books' }}</span>
          </div>
        </div>

        <div class="hero-actions chapter-actions">
          <a href="{{ route('home') }}#classified-prayer" class="secondary-button chapter-back-button">Back to Categories</a>
          <a href="{{ route('live') }}" class="live-button">Join Live Prayer</a>
        </div>
      </section>

      <section class="chapter-verse-section">
        <div class="chapter-section-head">
          <div>
            <p class="section-tag">Uploaded Topics</p>
            <h2>Topics listed under {{ $category->name }}</h2>
          </div>
          <span class="chapter-reading-note">These topics come only from uploaded verses in this category.</span>
        </div>

        @if ($topics->isNotEmpty())
          <div class="category-topics-grid">
            @foreach ($topics as $topic)
              <a href="{{ route('categories.topic-detail', ['category' => $category, 'topic' => urlencode($topic->topic)]) }}" class="category-topic-card category-topic-card-link">
                <div class="category-topic-card-head">
                  <span class="category-topic-badge">{{ $category->name }}</span>
                  <span class="category-topic-meta">{{ $topic->verses_count }} verse{{ $topic->verses_count === 1 ? '' : 's' }}</span>
                </div>
                <strong>{{ $topic->topic }}</strong>
                <p>{{ $topic->chapters_count }} chapter{{ $topic->chapters_count === 1 ? '' : 's' }} across {{ $topic->books_count }} book{{ $topic->books_count === 1 ? '' : 's' }}.</p>
                @if ($topic->first_reference)
                  <span class="category-topic-reference">First reference: {{ $topic->first_reference }}</span>
                @endif
              </a>
            @endforeach
          </div>
        @else
          <article class="word-item">
            <span class="icon">📖</span>
            <div class="word-copy">
              <span class="word-label">No uploaded topics yet</span>
              <span class="word-meta">Verse topics added under this category will appear here automatically.</span>
            </div>
          </article>
        @endif
      </section>
    </div>
  </section>
</main>
@endsection
