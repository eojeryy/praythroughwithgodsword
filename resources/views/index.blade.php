@extends('layout.app')

@section('title', 'PrayThroughWithGodsword.com')

@section('content')
@php
  $categoryIcons = ['📖', '🙏', '🎶', '🤝', '🕊️', '🛡️', '❤️', '🌟'];
  $chapterIcons = ['❤️', '🕊️', '🛡️', '🌟', '🔥', '🌿', '📜', '⚔️'];
@endphp
<main>
  <section class="hero-section">
    <div class="hero-copy">
      <p class="eyebrow">Daily scripture-led prayer guide</p>
      <h1>Pray with clarity, scripture, and purpose every day.</h1>
      <h3 id="banner-text"></h3>
      <p class="hero-text">Find focused prayer points, anchor your confessions in Bible verses, and move quickly into the area where you need spiritual support.</p>

      <div class="hero-actions">
        <a href="#specific-prayer" class="primary-button">Start praying</a>
        <a href="#classified-prayer" class="secondary-button">Browse categories</a>
        @guest
          <a href="{{ route('member.signup') }}" class="member-cta-button">Become a Member</a>
        @else
          @if (! auth()->user()->is_admin)
            <span class="member-cta-button member-cta-static">Member Access Active</span>
          @endif
        @endguest
      </div>

      <div class="hero-metrics">
        <div class="metric-card">
          <strong>16+</strong>
          <span>Verse prompts</span>
        </div>
        <div class="metric-card">
          <strong>4</strong>
          <span>Prayer tracks</span>
        </div>
        <div class="metric-card">
          <strong>Live</strong>
          <span>Quick access</span>
        </div>
      </div>
    </div>

    <aside class="hero-panel">
      <div class="hero-panel-card">
        <span class="panel-label">Today’s focus</span>
        <h2>Strength for the journey</h2>
        <p>Move from searching to praying faster with curated scriptures and simple entry points.</p>

        <div class="panel-list">
          @forelse ($categories->take(4) as $category)
            <div class="panel-item">
              <i class="fa-solid fa-heart"></i>
              <span>{{ $category->name }}</span>
            </div>
          @empty
            <div class="panel-item">
              <i class="fa-solid fa-heart"></i>
              <span>No prayer categories yet</span>
            </div>
          @endforelse
        </div>
      </div>
    </aside>
  </section>

  @guest
    <section class="member-join-strip">
      <div class="member-join-card">
        <div class="member-join-copy">
          <p class="section-tag">Member Access</p>
          <h2>Become a member and grow with us.</h2>
          <p>Create your member account with your phone number so you can come back quickly and keep praying with scripture.</p>
        </div>
        <a href="{{ route('member.signup') }}" class="member-cta-button member-join-button">Become a Member</a>
      </div>
    </section>
  @endguest

  <section class="content-section" id="specific-prayer">
    <div class="section-heading">
      <p class="section-tag">Specific Prayer</p>
      <h2 class="section-caption">Search by verse and go straight to the word.</h2>
    </div>

    <div class="prayer-search-container">
      <i class="fa-solid fa-magnifying-glass search-icon" aria-hidden="true"></i>
      <input type="text" placeholder="Search for prayer according to Bible verse" aria-label="Search for prayer according to Bible verse" />
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
          <a href="{{ route('books.chapters', $chapter->book) }}" class="word-item word-item-link">
            <span class="icon">{{ $chapterIcons[$loop->index % count($chapterIcons)] }}</span>
            <div class="word-copy">
              <span class="word-label">{{ $chapter->book?->name ?? $chapter->book_name }}</span>
            </div>
          </a>
        @empty
          <div class="word-item">
            <span class="icon">📘</span>
            <div class="word-copy">
              <span class="word-label">No New Testament chapters yet</span>
              <span class="word-meta">Add books and chapters from the admin dashboard</span>
            </div>
          </div>
        @endforelse
      </section>
    </div>

    <div class="testimony-panel" id="old-testimony-panel" role="tabpanel" hidden>
      <section class="word-row-section">
        @forelse ($oldTestamentChapters as $chapter)
          <a href="{{ route('books.chapters', $chapter->book) }}" class="word-item word-item-link">
            <span class="icon">{{ $chapterIcons[$loop->index % count($chapterIcons)] }}</span>
            <div class="word-copy">
              <span class="word-label">{{ $chapter->book?->name ?? $chapter->book_name }}</span>
            </div>
          </a>
        @empty
          <div class="word-item">
            <span class="icon">📜</span>
            <div class="word-copy">
              <span class="word-label">No Old Testament chapters yet</span>
              <span class="word-meta">Add books and chapters from the admin dashboard</span>
            </div>
          </div>
        @endforelse
      </section>
    </div>
  </section>

  <section class="content-section topic-browser-section" id="testament-topics">
    <div class="section-heading">
      <p class="section-tag">Prayer Topics</p>
      <h2 class="section-caption">Browse uploaded prayer topics by testament.</h2>
    </div>

    <div class="topic-browser-tabs" role="tablist" aria-label="Prayer topic testament tabs">
      <button class="topic-browser-tab is-active" type="button" role="tab" aria-selected="true" aria-controls="new-topic-panel" data-topic-target="new-topic-panel">
        New Testament Topics
      </button>
      <button class="topic-browser-tab" type="button" role="tab" aria-selected="false" aria-controls="old-topic-panel" data-topic-target="old-topic-panel">
        Old Testament Topics
      </button>
    </div>

    <div class="topic-browser-panel is-active" id="new-topic-panel" role="tabpanel">
      <div class="topic-browser-grid">
        @forelse ($newTestamentTopics as $topic)
          <a href="{{ route('testaments.topic-detail', ['scope' => 'new', 'topic' => urlencode($topic->topic)]) }}" class="topic-browser-card topic-browser-card-link">
            <div class="topic-browser-card-head">
              <span class="topic-browser-badge">New Testament</span>
              @if ($topic->category)
                <span class="topic-browser-category">{{ $topic->category }}</span>
              @endif
            </div>
            <h3>{{ $topic->topic }}</h3>
            <p>{{ $topic->verses_count }} verse record{{ $topic->verses_count === 1 ? '' : 's' }} across {{ $topic->books_count }} book{{ $topic->books_count === 1 ? '' : 's' }}.</p>
          </a>
        @empty
          <article class="topic-browser-empty">
            <i class="fa-solid fa-book-bible"></i>
            <div>
              <strong>No New Testament topics yet</strong>
              <p>Uploaded verse topics from New Testament chapters will appear here automatically.</p>
            </div>
          </article>
        @endforelse
      </div>
    </div>

    <div class="topic-browser-panel" id="old-topic-panel" role="tabpanel" hidden>
      <div class="topic-browser-grid">
        @forelse ($oldTestamentTopics as $topic)
          <a href="{{ route('testaments.topic-detail', ['scope' => 'old', 'topic' => urlencode($topic->topic)]) }}" class="topic-browser-card topic-browser-card-link">
            <div class="topic-browser-card-head">
              <span class="topic-browser-badge">Old Testament</span>
              @if ($topic->category)
                <span class="topic-browser-category">{{ $topic->category }}</span>
              @endif
            </div>
            <h3>{{ $topic->topic }}</h3>
            <p>{{ $topic->verses_count }} verse record{{ $topic->verses_count === 1 ? '' : 's' }} across {{ $topic->books_count }} book{{ $topic->books_count === 1 ? '' : 's' }}.</p>
          </a>
        @empty
          <article class="topic-browser-empty">
            <i class="fa-solid fa-scroll"></i>
            <div>
              <strong>No Old Testament topics yet</strong>
              <p>Uploaded verse topics from Old Testament chapters will appear here automatically.</p>
            </div>
          </article>
        @endforelse
      </div>
    </div>
  </section>

  <section class="content-section" id="classified-prayer">
    <div class="section-heading">
      <p class="section-tag">Classified Prayer</p>
      <h2 class="section-caption">Choose a category that matches your current need.</h2>
    </div>

    <div class="feature-container">
      @forelse ($categories as $category)
        <a href="{{ route('categories.topics', $category) }}" class="feature-item feature-item-link">
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
            <p>Prayer categories added from the admin dashboard will appear here automatically.</p>
          </div>
        </div>
      @endforelse
    </div>
  </section>
</main>
@endsection
