@extends('layout.app')

@section('title', 'PrayThroughWithGodsword.com')

@section('body_class', 'home-page')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/home.css') }}" />
@endpush

@section('content')
@php
  $categoryIcons = ['📖', '🙏', '🎶', '🤝', '🕊️', '🛡️', '❤️', '🌟'];
  $chapterIcons = ['❤️', '🕊️', '🛡️', '🌟', '🔥', '🌿', '📜', '⚔️'];
@endphp
<main class="home-main">
  <section class="home-hero" aria-labelledby="home-title">
    <div class="home-welcome">
      <p class="eyebrow"><span aria-hidden="true">✦</span> Rooted in scripture. Strengthened in prayer.</p>
      <h1 id="home-title">Let God’s word<br />guide your <em>prayers.</em></h1>
      <p class="home-intro">Find words for what’s on your heart. Explore Bible-based prayer topics, reflect on scripture, and make space for God in your everyday life.</p>
      <div class="home-hero-actions">
        <a href="#classified-prayer" class="primary-button">Find a prayer <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
        <a href="{{ route('live') }}" class="home-text-link">Join live prayer <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i></a>
      </div>
      <p class="home-welcome-note"><i class="fa-solid fa-book-open" aria-hidden="true"></i> A quiet place to read, reflect, and pray.</p>
    </div>
    <aside class="home-scripture" aria-labelledby="scripture-heading">
      <div class="home-scripture-top"><span id="scripture-heading">A moment in the Word</span><i class="fa-solid fa-book-bible" aria-hidden="true"></i></div>
      <span class="home-quote-mark" aria-hidden="true">“</span>
      <blockquote>Thy word is a lamp unto my feet, and a light unto my path.</blockquote>
      <p class="home-scripture-reference">Psalm 119:105 <span>King James Version</span></p>
      <div class="home-reflection"><span>Pause &amp; pray</span><p>Lord, guide my steps today. Help me hear your word and walk in your light.</p></div>
      <a href="#specific-prayer" class="home-scripture-link">Explore the Bible <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
    </aside>
  </section>

  <section class="home-paths" aria-label="Ways to start praying">
    <a href="#specific-prayer" class="home-path"><span class="home-path-icon"><i class="fa-solid fa-book-open" aria-hidden="true"></i></span><span><strong>Start with scripture</strong><small>Explore prayers by Bible book</small></span><i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
    <a href="#testament-topics" class="home-path"><span class="home-path-icon"><i class="fa-solid fa-hands-praying" aria-hidden="true"></i></span><span><strong>Pray through a topic</strong><small>Find guidance for your season</small></span><i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
    <a href="{{ route('live') }}" class="home-path"><span class="home-path-icon"><i class="fa-solid fa-users" aria-hidden="true"></i></span><span><strong>Pray together</strong><small>Visit our live prayer room</small></span><i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
  </section>

  <section class="home-finder" id="prayer-finder" aria-labelledby="finder-title">
    <div><p class="section-tag">Find your starting point</p><h2 id="finder-title">What’s on your heart?</h2><p>Search the books, prayer topics, and categories below.</p></div>
    <form class="home-search" action="{{ route('home') }}#prayer-finder" method="GET" role="search">
      <label for="home-prayer-search" class="home-sr-only">Search books, prayer topics, and categories</label>
      <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
      <input id="home-prayer-search" name="q" type="search" placeholder="Try peace, family, or Psalms…" value="{{ is_string(request('q')) ? request('q') : '' }}" data-home-search autocomplete="off" />
      <button type="submit">Search</button>
    </form>
    <div class="home-search-results" data-home-results hidden>
      <div class="home-search-summary"><p data-home-search-status role="status" aria-live="polite"></p><button type="button" data-home-search-clear>Clear search</button></div>
      <div class="home-results-grid" data-home-search-list></div>
      <p data-home-search-empty hidden>No matches found. Try a different word, or browse the library below.</p>
    </div>
  </section>

  <section class="content-section" id="specific-prayer">
    <div class="section-heading">
      <p class="section-tag">The scripture library</p>
      <h2 class="section-caption">Begin with a book of the Bible.</h2>
    </div>

    <div class="testimony-tabs" role="tablist" aria-label="Testament tabs">
      <button class="testimony-tab is-active" type="button" role="tab" aria-selected="true" aria-controls="new-testimony-panel" data-target="new-testimony-panel">
        New Testament
      </button>
      <button class="testimony-tab" type="button" role="tab" aria-selected="false" aria-controls="old-testimony-panel" data-target="old-testimony-panel">
        Old Testament
      </button>
    </div>

    <div class="testimony-panel is-active" id="new-testimony-panel" role="tabpanel">
      <section class="word-row-section">
        @forelse ($newTestamentChapters as $chapter)
          <a href="{{ route('books.chapters', $chapter->book) }}" class="word-item word-item-link" data-home-search-item data-search-kind="Bible book" data-search-title="{{ $chapter->book?->name ?? $chapter->book_name }}">
            <span class="icon">{{ $chapterIcons[$loop->index % count($chapterIcons)] }}</span>
            <div class="word-copy">
              <span class="word-label">{{ $chapter->book?->name ?? $chapter->book_name }}</span>
              <span class="word-meta">Explore chapters <span aria-hidden="true">→</span></span>
            </div>
          </a>
        @empty
          <div class="word-item">
            <span class="icon">📘</span>
            <div class="word-copy">
              <span class="word-label">No New Testament chapters yet</span>
              <span class="word-meta">Scripture prayers will appear here as the library grows.</span>
            </div>
          </div>
        @endforelse
      </section>
    </div>

    <div class="testimony-panel" id="old-testimony-panel" role="tabpanel" hidden>
      <section class="word-row-section">
        @forelse ($oldTestamentChapters as $chapter)
          <a href="{{ route('books.chapters', $chapter->book) }}" class="word-item word-item-link" data-home-search-item data-search-kind="Bible book" data-search-title="{{ $chapter->book?->name ?? $chapter->book_name }}">
            <span class="icon">{{ $chapterIcons[$loop->index % count($chapterIcons)] }}</span>
            <div class="word-copy">
              <span class="word-label">{{ $chapter->book?->name ?? $chapter->book_name }}</span>
              <span class="word-meta">Explore chapters <span aria-hidden="true">→</span></span>
            </div>
          </a>
        @empty
          <div class="word-item">
            <span class="icon">📜</span>
            <div class="word-copy">
              <span class="word-label">No Old Testament chapters yet</span>
              <span class="word-meta">Scripture prayers will appear here as the library grows.</span>
            </div>
          </div>
        @endforelse
      </section>
    </div>
  </section>

  <section class="content-section topic-browser-section" id="testament-topics">
    <div class="section-heading">
      <p class="section-tag">Prayer Topics</p>
      <h2 class="section-caption">Find a prayer for your season.</h2>
    </div>

    <div class="topic-browser-tabs" role="tablist" aria-label="Prayer topic testament tabs">
      <button class="topic-browser-tab is-active" type="button" role="tab" aria-selected="true" aria-controls="new-topic-panel" data-topic-target="new-topic-panel">
        New Testament
      </button>
      <button class="topic-browser-tab" type="button" role="tab" aria-selected="false" aria-controls="old-topic-panel" data-topic-target="old-topic-panel">
        Old Testament
      </button>
    </div>

    <div class="topic-browser-panel is-active" id="new-topic-panel" role="tabpanel">
      <div class="topic-browser-grid">
        @forelse ($newTestamentTopics as $topic)
          <a href="{{ route('testaments.topic-detail', ['scope' => 'new', 'topic' => urlencode($topic->topic)]) }}" class="topic-browser-card topic-browser-card-link" data-home-search-item data-search-kind="Prayer topic" data-search-title="{{ $topic->topic }}">
            <div class="topic-browser-card-head">
              <span class="topic-browser-badge">New Testament</span>
              @if ($topic->category)
                <span class="topic-browser-category">{{ $topic->category }}</span>
              @endif
            </div>
            <h3>{{ $topic->topic }}</h3>
            <p>{{ $topic->verses_count }} scripture{{ $topic->verses_count === 1 ? '' : 's' }} across {{ $topic->books_count }} book{{ $topic->books_count === 1 ? '' : 's' }}.</p>
          </a>
        @empty
          <article class="topic-browser-empty">
            <i class="fa-solid fa-book-bible"></i>
            <div>
              <strong>No New Testament topics yet</strong>
              <p>Explore the books above while we prepare more scripture-based prayer topics.</p>
            </div>
          </article>
        @endforelse
      </div>
    </div>

    <div class="topic-browser-panel" id="old-topic-panel" role="tabpanel" hidden>
      <div class="topic-browser-grid">
        @forelse ($oldTestamentTopics as $topic)
          <a href="{{ route('testaments.topic-detail', ['scope' => 'old', 'topic' => urlencode($topic->topic)]) }}" class="topic-browser-card topic-browser-card-link" data-home-search-item data-search-kind="Prayer topic" data-search-title="{{ $topic->topic }}">
            <div class="topic-browser-card-head">
              <span class="topic-browser-badge">Old Testament</span>
              @if ($topic->category)
                <span class="topic-browser-category">{{ $topic->category }}</span>
              @endif
            </div>
            <h3>{{ $topic->topic }}</h3>
            <p>{{ $topic->verses_count }} scripture{{ $topic->verses_count === 1 ? '' : 's' }} across {{ $topic->books_count }} book{{ $topic->books_count === 1 ? '' : 's' }}.</p>
          </a>
        @empty
          <article class="topic-browser-empty">
            <i class="fa-solid fa-scroll"></i>
            <div>
              <strong>No Old Testament topics yet</strong>
              <p>Explore the books above while we prepare more scripture-based prayer topics.</p>
            </div>
          </article>
        @endforelse
      </div>
    </div>
  </section>

  <section class="content-section" id="classified-prayer">
    <div class="section-heading">
      <p class="section-tag">Pray with purpose</p>
      <h2 class="section-caption">Bring what matters to you into prayer.</h2>
    </div>

    <div class="feature-container">
      @forelse ($categories as $category)
        <a href="{{ route('categories.topics', $category) }}" class="feature-item feature-item-link" data-home-search-item data-search-kind="Prayer category" data-search-title="{{ $category->name }}">
          <div class="icon">{{ $categoryIcons[$loop->index % count($categoryIcons)] }}</div>
          <div class="feature-copy">
            <div class="label">{{ $category->name }}</div>
            <p>{{ $category->description ?: 'Scripture-guided prayer support prepared for this category.' }}</p>
          </div>
        </a>
      @empty
        <div class="feature-item">
          <div class="icon">📖</div>
          <div class="feature-copy">
            <div class="label">No categories yet</div>
            <p>We’re preparing prayer categories for you. You can explore the scripture library in the meantime.</p>
          </div>
        </div>
      @endforelse
    </div>
  </section>
  <section class="home-invitation" aria-labelledby="invitation-title">
    <div><p class="section-tag">A daily rhythm of prayer</p><h2 id="invitation-title">Read. Reflect. Pray.</h2><p>Choose a scripture, take a moment with its meaning, and make it your prayer. Start where you are, and return whenever you need encouragement.</p></div>
    @guest
      <a href="{{ route('member.signup') }}" class="primary-button">Become a member <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
    @else
      <a href="{{ route('live') }}" class="primary-button">Visit the prayer room <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
    @endguest
  </section>
</main>
@endsection
