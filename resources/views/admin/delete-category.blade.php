@extends('layout.app')

@section('title', 'Delete Category')
@section('body_class', 'admin-auth-body')

@section('content')
<main class="admin-dashboard-main">
  <section class="admin-dashboard-card">
    <div class="admin-dashboard-intro">
      <span class="admin-auth-eyebrow">Content setup</span>
      <h1>Delete Category</h1>
      <p>Confirm removal of this category on a separate page before it is deleted from the database.</p>
    </div>

    <div class="admin-dashboard-layout">
      @include('admin.partials.sidebar')

      <div class="admin-dashboard-content">
        <section class="admin-dashboard-panel">
          <div class="admin-dashboard-panel-head">
            <span class="admin-dashboard-panel-tag">Delete confirmation</span>
            <h2>{{ $category->name }}</h2>
          </div>
          <p>{{ $category->description ?: 'No description added yet.' }}</p>
          <p class="admin-dashboard-warning">This action will permanently remove the category.</p>
          <div class="admin-dashboard-record-actions">
            <form action="{{ route('admin.upload-category.destroy', $category) }}" method="POST" class="admin-dashboard-inline-form">
              @csrf
              @method('DELETE')
              <button type="submit" class="admin-dashboard-delete-button">Confirm Delete</button>
            </form>
            <a href="{{ route('admin.list-categories') }}" class="secondary-button">Cancel</a>
          </div>
        </section>
      </div>
    </div>
  </section>
</main>
@endsection
