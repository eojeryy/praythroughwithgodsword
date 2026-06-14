@extends('layout.app')

@section('title', 'Edit Verse')
@section('body_class', 'admin-auth-body')

@section('content')
<main class="admin-dashboard-main">
  <section class="admin-dashboard-card">
    <div class="admin-dashboard-intro">
      <span class="admin-auth-eyebrow">Scripture content</span>
      <h1>Edit Verse</h1>
      <p>Update the selected verse on its own page.</p>
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
            <h2>{{ $verse->chapter?->book?->name ?? $verse->chapter?->book_name }} {{ $verse->chapter?->chapter_number }}:{{ $verse->verse_number }}</h2>
          </div>
          <form action="{{ route('admin.upload-verse.update', $verse) }}" method="POST" class="admin-auth-form">
            @csrf
            @method('PUT')
            <label class="admin-auth-field">
              <span>Chapter</span>
              <select name="chapter_id">
                <option value="">Select a chapter</option>
                @foreach ($chapters as $chapter)
                  <option value="{{ $chapter->id }}" @selected(old('chapter_id', $verse->chapter_id) == $chapter->id)>
                    {{ $chapter->book?->name ?? $chapter->book_name }} - Chapter {{ $chapter->chapter_number }}
                  </option>
                @endforeach
              </select>
            </label>
            <label class="admin-auth-field">
              <span>Category</span>
              <select name="category_id">
                <option value="">Select a category</option>
                @foreach ($categories as $category)
                  <option value="{{ $category->id }}" @selected(old('category_id', $verse->category_id) == $category->id)>{{ $category->name }}</option>
                @endforeach
              </select>
            </label>
            <label class="admin-auth-field">
              <span>Prayer Topic</span>
              <input type="text" name="prayer_topic" value="{{ old('prayer_topic', $verse->prayer_topic) }}" placeholder="e.g. Divine help and direction" />
            </label>
            <label class="admin-auth-field">
              <span>Verse Number</span>
              <input type="text" name="verse_number" value="{{ old('verse_number', $verse->verse_number) }}" inputmode="text" placeholder="e.g. 1, 2-10, 2:8" />
            </label>
            <label class="admin-auth-field">
              <span>Verse Text</span>
              @include('admin.partials.rich-text-editor', [
                'name' => 'verse_text',
                'value' => old('verse_text', $verse->verse_text),
                'placeholder' => 'Enter the verse text here',
              ])
            </label>
            <div class="admin-dashboard-record-actions">
              <button type="submit" class="primary-button admin-auth-submit">Save Changes</button>
              <a href="{{ route('admin.list-verses') }}" class="secondary-button">Back to List</a>
            </div>
          </form>
        </section>
      </div>
    </div>
  </section>
</main>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-rich-text-editor]').forEach(function (wrapper) {
      const editor = wrapper.querySelector('[data-rich-text-surface]');
      const input = wrapper.querySelector('.admin-rich-text-input');
      const buttons = wrapper.querySelectorAll('[data-command]');

      const sync = function () {
        input.value = editor.innerHTML;
      };

      editor.innerHTML = input.value || '';

      buttons.forEach(function (button) {
        button.addEventListener('click', function () {
          const command = button.dataset.command;
          const value = button.dataset.value || null;

          editor.focus();
          document.execCommand(command, false, value);
          sync();
        });
      });

      editor.addEventListener('input', sync);

      const form = wrapper.closest('form');
      if (form) {
        form.addEventListener('submit', sync);
      }
    });
  });
</script>
@endsection
