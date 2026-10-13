@extends('layout.admin')

@section('title', 'Edit Category')
@section('body_class', 'admin-workspace')

@section('content')
<main class="admin-dashboard-main">
  <section class="admin-dashboard-card">
    <div class="admin-dashboard-intro">
      <span class="admin-auth-eyebrow">Content setup</span>
      <h1>Edit Category</h1>
      <p>Update the selected category on its own page so changes are focused and easy to review.</p>
    </div>

    <div class="admin-dashboard-layout">
      @include('admin.partials.sidebar')

      <div class="admin-dashboard-content">
        <section class="admin-dashboard-panel">
          <div class="admin-dashboard-panel-head">
            <span class="admin-dashboard-panel-tag">Editing</span>
            <h2>{{ $category->name }}</h2>
          </div>
          <form action="{{ route('admin.upload-category.update', $category) }}" method="POST" class="admin-auth-form">
            @csrf
            @method('PUT')
            <label class="admin-auth-field">
              <span>Category Name</span>
              <input type="text" name="category_name" required value="{{ old('category_name', $category->name) }}" />
            </label>
            <label class="admin-auth-field">
              <span>Description</span>
              <textarea name="category_description" rows="3" maxlength="1000" placeholder="Short description for this category">{{ old('category_description', $category->description) }}</textarea>
            </label>
            <div class="admin-dashboard-record-actions">
              <a href="{{ route('admin.show-category', $category) }}" class="secondary-button">View record</a>
              <button type="submit" class="primary-button admin-auth-submit">Save Changes</button>
              <a href="{{ route('admin.list-categories') }}" class="secondary-button">Back to List</a>
            </div>
          </form>
        </section>
      </div>
    </div>
  </section>
</main>
@endsection
