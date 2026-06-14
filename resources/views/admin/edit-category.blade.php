@extends('layout.app')

@section('title', 'Edit Category')
@section('body_class', 'admin-auth-body')

@section('content')
<main class="admin-dashboard-main">
  <section class="admin-dashboard-card">
    <div class="admin-dashboard-intro">
      <span class="admin-auth-eyebrow">Content setup</span>
      <h1>Edit Category</h1>
      <p>Update the selected category on its own page so changes are focused and easy to review.</p>
    </div>

    @if ($errors->any())
      <div class="form-alert form-alert-error">
        <i class="fa-solid fa-circle-exclamation"></i>
        <span>{{ $errors->first() }}</span>
      </div>
    @endif

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
              <input type="text" name="category_name" value="{{ old('category_name', $category->name) }}" />
            </label>
            <label class="admin-auth-field">
              <span>Description</span>
              <input type="text" name="category_description" value="{{ old('category_description', $category->description) }}" placeholder="Short description for this category" />
            </label>
            <div class="admin-dashboard-record-actions">
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
