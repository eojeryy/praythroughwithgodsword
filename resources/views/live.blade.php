@extends('layout.app')

@section('title', 'Live Prayer Room | PrayThroughWithGodsword.com')

@section('body_class', 'live-page')

@section('content')
<main class="live-main">
  <section class="live-banner">
    <div class="live-banner-overlay">
      <p class="eyebrow">Live prayer session</p>
      <h1>Night of Breakthrough and Open Doors</h1>
      <p class="live-banner-text">Join believers in real time as we pray around today’s theme, share scripture-backed prayer points, and stand together in faith.</p>

      <div class="live-banner-meta">
        <div class="live-meta-card">
          <span class="live-meta-label">Theme</span>
          <strong>Breakthrough Prayer Watch</strong>
        </div>
        <div class="live-meta-card">
          <span class="live-meta-label">Host</span>
          <strong>PTWG Prayer Team</strong>
        </div>
        <div class="live-meta-card">
          <span class="live-meta-label">Status</span>
          <strong>Streaming now</strong>
        </div>
      </div>
    </div>
  </section>

  <section class="live-summary-strip">
    <article class="live-summary-card">
      <span class="live-summary-label">Prayer wall entries</span>
      <strong>{{ $liveCommentCount }}</strong>
      <p>Visible encouragement and faith-filled member comments.</p>
    </article>
    <article class="live-summary-card">
      <span class="live-summary-label">Active members</span>
      <strong>{{ $activeMemberCount }}</strong>
      <p>Believers currently engaging this live prayer atmosphere.</p>
    </article>
    <article class="live-summary-card">
      <span class="live-summary-label">Latest voice</span>
      <strong>{{ $latestComment?->user?->name ?? 'Prayer Wall' }}</strong>
      <p>{{ $latestComment ? 'Most recent comment added ' . $latestComment->created_at?->diffForHumans() . '.' : 'Be the first member to share a prayer comment.' }}</p>
    </article>
  </section>

  <section class="live-layout" id="live-chat">
    <div class="live-info-card">
      <div class="section-heading live-heading">
        <p class="section-tag">Prayer room</p>
        <h2 class="section-caption">Registered members and their prayer comments.</h2>
      </div>

      <div class="live-stats">
        <div class="live-stat-pill">
          <i class="fa-solid fa-user-group"></i>
          <div class="live-stat-copy">
            <strong>{{ $activeMemberCount }} members joined</strong>
            <span>Active worshippers in the room</span>
          </div>
        </div>
        <div class="live-stat-pill">
          <i class="fa-solid fa-circle-dot"></i>
          <div class="live-stat-copy">
            <strong>Live comments rolling</strong>
            <span>Fresh prayer responses in real time</span>
          </div>
        </div>
      </div>

      <div class="live-guides">
        <div class="live-guide-item">
          <span class="live-guide-tag">Prayer focus</span>
          <strong>Open doors and divine favor</strong>
          <p>Declare open doors, divine favor, and strength for every waiting season.</p>
        </div>
        <div class="live-guide-item">
          <span class="live-guide-tag">Featured verses</span>
          <strong>Isaiah 45, Psalm 24, Revelation 3:8, Luke 1</strong>
          <p>Isaiah 45, Psalm 24, Revelation 3:8, and Luke 1 for confidence and expectation.</p>
        </div>
      </div>

      <div class="live-room-promise">
        <div class="live-room-promise-icon">
          <i class="fa-solid fa-hands-praying"></i>
        </div>
        <div class="live-room-promise-copy">
          <strong>Every member prayer comment becomes part of the testimony wall.</strong>
          <p>Share a focused prayer point, thanksgiving, or faith confession and let other believers stand with you in agreement.</p>
        </div>
      </div>
    </div>

    <div class="live-chat-card">
      <div class="live-chat-header">
        <div>
          <p class="section-tag">Live comments</p>
          <h3>Prayer testimony wall</h3>
          <p class="live-chat-subtitle">Fresh prayer responses from members appear here as they are posted.</p>
        </div>
        <span class="chat-status"><span class="live-dot"></span> Live</span>
      </div>

      @if (session('status'))
        <div class="form-alert form-alert-success live-inline-alert">
          <i class="fa-solid fa-circle-check"></i>
          <span>{{ session('status') }}</span>
        </div>
      @endif

      <div class="live-comments-window">
        <div class="live-comments-track live-comments-track-static">
          @forelse ($liveComments as $comment)
            @php
              $initials = collect(explode(' ', $comment->user->name))
                ->filter()
                ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
                ->take(2)
                ->implode('');
            @endphp
            <article class="live-comment">
              <div class="live-comment-avatar">{{ $initials ?: 'MM' }}</div>
              <div class="live-comment-body">
                <strong>{{ $comment->user->name }}</strong>
                <span class="live-comment-meta">{{ $comment->created_at?->diffForHumans() }}</span>
                <p>{{ $comment->body }}</p>
              </div>
            </article>
          @empty
            <article class="live-comment live-comment-empty">
              <div class="live-comment-avatar">PW</div>
              <div class="live-comment-body">
                <strong>Prayer Wall</strong>
                <p>No member prayer comments yet. Be the first to share one.</p>
              </div>
            </article>
          @endforelse
        </div>
      </div>

      <div class="live-comment-form-card">
        <div class="live-comment-form-heading">
          <p class="section-tag">Join the prayer</p>
          <h4>Share your prayer comment</h4>
          <p class="live-comment-form-subtitle">Keep it short, sincere, and scripture-centered so the wall stays uplifting and easy to follow.</p>
        </div>

        @auth
          @if (! auth()->user()->is_admin)
            @if ($errors->any())
              <div class="form-alert form-alert-error live-inline-alert">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>{{ $errors->first() }}</span>
              </div>
            @endif

            <form action="{{ route('live.comment.store') }}" method="POST" class="live-comment-form">
              @csrf
              <div class="live-comment-input-wrap">
                <div class="live-comment-user-icon">
                  <i class="fa-solid fa-user"></i>
                </div>
                <label class="live-field live-comment-field">
                  <textarea name="body" rows="3" placeholder="Add a prayer comment...">{{ old('body') }}</textarea>
                  <button type="submit" class="live-comment-submit" aria-label="Send comment">
                    <i class="fa-solid fa-paper-plane"></i>
                  </button>
                </label>
              </div>

              <div class="live-form-actions">
                <p>Your registered member profile will be used when this comment is posted.</p>
                <span class="live-form-tip">Encouragement, thanksgiving, and prayer requests are welcome.</span>
              </div>
            </form>
          @else
            <div class="form-alert form-alert-error live-inline-alert">
              <i class="fa-solid fa-user-shield"></i>
              <span>Admin accounts cannot post on the member prayer testimony wall.</span>
            </div>
          @endif
        @else
          <div class="form-alert form-alert-error live-inline-alert">
            <i class="fa-solid fa-right-to-bracket"></i>
            <span>Sign in as a member to comment on the live prayer wall.</span>
          </div>
          <div class="live-member-actions">
            <a href="{{ route('member.signin') }}" class="secondary-button">Member Sign In</a>
            <a href="{{ route('member.signup') }}" class="member-cta-button">Become a Member</a>
          </div>
        @endauth
      </div>
    </div>
  </section>
</main>
@endsection
