@extends('layout.admin')

@section('title', 'Add Category')
@section('body_class', 'admin-workspace')

@section('content')
<main class="admin-dashboard-main">
  <section class="admin-dashboard-card">
    <div class="admin-dashboard-intro">
      <span class="admin-auth-eyebrow">Content setup</span>
      <h1>Add Category</h1>
      <p>Create or organize prayer categories so visitors can find the right topic quickly.</p>
    </div>

    <div class="admin-dashboard-layout">
      @include('admin.partials.sidebar')

      <div class="admin-dashboard-content">
        <section class="admin-dashboard-panel">
          <div class="admin-dashboard-panel-head admin-dashboard-panel-head-inline">
            <div>
              <span class="admin-dashboard-panel-tag">Category form</span>
              <h2>New prayer category</h2>
            </div>
            <a href="{{ route('admin.list-categories') }}" class="admin-dashboard-action-link">List Categories</a>
          </div>

          <form action="{{ route('admin.upload-category.store') }}" method="POST" class="admin-auth-form">
            @csrf
            <label class="admin-auth-field">
              <span>Category Name</span>
              <input type="text" name="category_name" required value="{{ old('category_name') }}" placeholder="e.g. Healing Prayer" />
            </label>
            <label class="admin-auth-field">
              <span>Description</span>
              <textarea name="category_description" rows="5" placeholder="Short description for this category">{{ old('category_description') }}</textarea>
            </label>
            <div class="admin-dashboard-record-actions"><button type="submit" class="primary-button admin-auth-submit">Add Category</button>
              <a href="{{ route('admin.list-categories') }}" class="secondary-button">Cancel</a>
            </div>
          </form>
        </section>
      </div>
    </div>
  </section>
</main>
@endsection
