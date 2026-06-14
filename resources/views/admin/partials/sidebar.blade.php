<aside class="admin-dashboard-sidebar" aria-label="Admin tools">
  <div class="admin-dashboard-sidebar-head">
    <h2>Admin Menu</h2>
    <p>Quick access to your main dashboard actions.</p>
  </div>

  <nav class="admin-dashboard-nav">
    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">
      <i class="fa-solid fa-table-columns"></i>
      <span>Dashboard</span>
    </a>
    <a href="{{ route('admin.upload-category') }}" class="{{ request()->routeIs('admin.upload-category') ? 'is-active' : '' }}">
      <i class="fa-solid fa-folder-tree"></i>
      <span>Upload Category</span>
    </a>
    <a href="{{ route('admin.upload-testament') }}" class="{{ request()->routeIs('admin.upload-testament', 'admin.list-testaments') ? 'is-active' : '' }}">
      <i class="fa-solid fa-book-bible"></i>
      <span>Upload Testament</span>
    </a>
    <a href="{{ route('admin.upload-book') }}" class="{{ request()->routeIs('admin.upload-book', 'admin.list-books', 'admin.edit-book', 'admin.delete-book') ? 'is-active' : '' }}">
      <i class="fa-solid fa-book"></i>
      <span>Upload Book</span>
    </a>
    <a href="{{ route('admin.upload-chapter') }}" class="{{ request()->routeIs('admin.upload-chapter', 'admin.list-chapters', 'admin.edit-chapter', 'admin.delete-chapter') ? 'is-active' : '' }}">
      <i class="fa-solid fa-book-open-reader"></i>
      <span>Upload Chapter</span>
    </a>
    <a href="{{ route('admin.upload-verse') }}" class="{{ request()->routeIs('admin.upload-verse', 'admin.list-verses', 'admin.edit-verse', 'admin.delete-verse') ? 'is-active' : '' }}">
      <i class="fa-solid fa-quote-left"></i>
      <span>Upload Verse</span>
    </a>
    <a href="{{ route('admin.change-password') }}" class="{{ request()->routeIs('admin.change-password') ? 'is-active' : '' }}">
      <i class="fa-solid fa-key"></i>
      <span>Change Password</span>
    </a>
  </nav>

  <div class="admin-dashboard-sidebar-meta">
    <p><strong>Name:</strong> {{ auth()->user()->name }}</p>
    <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
    <p><strong>Role:</strong> Administrator</p>
  </div>

  <div class="hero-actions">
    <a href="{{ route('home') }}" class="secondary-button">View Website</a>
    <form action="{{ route('admin.logout') }}" method="POST" class="header-inline-form">
      @csrf
      <button type="submit" class="primary-button admin-auth-submit">Sign Out</button>
    </form>
  </div>
</aside>
