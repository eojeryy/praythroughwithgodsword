<div class="admin-rich-text" data-rich-text-editor>
  <div class="admin-rich-text-toolbar" role="toolbar" aria-label="Text formatting">
    <button type="button" class="admin-rich-text-button" data-command="bold"><strong>B</strong></button>
    <button type="button" class="admin-rich-text-button" data-command="italic"><em>I</em></button>
    <button type="button" class="admin-rich-text-button" data-command="underline"><u>U</u></button>
    <button type="button" class="admin-rich-text-button" data-command="insertUnorderedList">
      <i class="fa-solid fa-list-ul"></i>
    </button>
    <button type="button" class="admin-rich-text-button" data-command="insertOrderedList">
      <i class="fa-solid fa-list-ol"></i>
    </button>
    <button type="button" class="admin-rich-text-button" data-command="formatBlock" data-value="p">P</button>
  </div>

  <div
    class="admin-rich-text-editor"
    contenteditable="true"
    data-rich-text-surface
    data-placeholder="{{ $placeholder ?? 'Write here...' }}"
  ></div>

  <textarea name="{{ $name }}" class="admin-rich-text-input" hidden>{{ $value }}</textarea>
</div>
