@extends('layout.app')

@section('title', 'Upload Testament')
@section('body_class', 'admin-auth-body')

@section('content')
<main class="admin-dashboard-main">
  <section class="admin-dashboard-card">
    <div class="admin-dashboard-intro">
      <span class="admin-auth-eyebrow">Bible structure</span>
      <h1>Upload Testament</h1>
      <p>Add a testament grouping to support Old Testament and New Testament content management.</p>
    </div>

    <div class="admin-dashboard-layout">
      @include('admin.partials.sidebar')

      <div class="admin-dashboard-content">
        <section class="admin-dashboard-panel">
          <div class="admin-dashboard-panel-head admin-dashboard-panel-head-inline">
            <div>
              <span class="admin-dashboard-panel-tag">Testament form</span>
              <h2>New testament entry</h2>
            </div>
            <a href="{{ route('admin.list-testaments') }}" class="admin-dashboard-action-link">List Testaments</a>
          </div>

          @if ($errors->any())
            <div class="form-alert form-alert-error">
              <i class="fa-solid fa-circle-exclamation"></i>
              <span>{{ $errors->first() }}</span>
            </div>
          @endif

          <form action="{{ route('admin.upload-testament.store') }}" method="POST" class="admin-auth-form">
            @csrf
            <label class="admin-auth-field">
              <span>Testament Name</span>
              <input type="text" name="testament_name" value="{{ old('testament_name') }}" placeholder="e.g. Old Testament" />
            </label>
            <label class="admin-auth-field">
              <span>Summary</span>
              <input type="text" name="testament_summary" value="{{ old('testament_summary') }}" placeholder="Brief note about this testament" />
            </label>
            <button type="submit" class="primary-button admin-auth-submit">Upload Testament</button>
          </form>
        </section>
      </div>
    </div>
  </section>
</main>
@endsection
