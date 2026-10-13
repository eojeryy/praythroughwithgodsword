@extends('layout.admin')

@section('title', 'Add Testament')
@section('body_class', 'admin-workspace')

@section('content')
<main class="admin-dashboard-main">
  <section class="admin-dashboard-card">
    <div class="admin-dashboard-intro">
      <span class="admin-auth-eyebrow">Bible structure</span>
      <h1>Add Testament</h1>
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

          <form action="{{ route('admin.upload-testament.store') }}" method="POST" class="admin-auth-form">
            @csrf
            <label class="admin-auth-field">
              <span>Testament Name</span>
              <input type="text" name="testament_name" required value="{{ old('testament_name') }}" placeholder="e.g. Old Testament" />
            </label>
            <label class="admin-auth-field">
              <span>Summary</span>
              <textarea name="testament_summary" rows="3" maxlength="1000" placeholder="Brief note about this testament">{{ old('testament_summary') }}</textarea>
            </label>
            <div class="admin-dashboard-record-actions"><button type="submit" class="primary-button admin-auth-submit">Add Testament</button>
              <a href="{{ route('admin.list-testaments') }}" class="secondary-button">Cancel</a>
            </div>
          </form>
        </section>
      </div>
    </div>
  </section>
</main>
@endsection
