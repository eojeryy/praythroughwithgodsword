@extends('layout.app')

@section('title', 'Upload Verse')
@section('body_class', 'admin-auth-body')

@section('content')
@php
  $entryDrafts = collect(old('entries', []))->values();
  $selectedChapterIds = $entryDrafts
    ->pluck('chapter_id')
    ->filter()
    ->map(fn ($value) => (int) $value)
    ->all();
  $selectedChapters = $chapters->whereIn('id', $selectedChapterIds)->values();
  $selectedTestament = $selectedChapters->pluck('book.testament')->filter()->first();
  $selectedCategory = $categories->firstWhere('id', (int) old('category_id'));
  $chapterBooksCovered = $chapters->pluck('book.name')->filter()->unique()->count();
  $chapterNumbers = $chapters->pluck('chapter_number')->filter()->unique()->sort()->values();
  $selectedFilterTestaments = collect(old('filter_testament_ids', $selectedTestament ? [$selectedTestament->id] : []))
    ->map(fn ($value) => (string) $value)
    ->all();
  $selectedFilterBooks = collect(old('filter_book_ids', $selectedChapters->pluck('book_id')->all()))
    ->map(fn ($value) => (string) $value)
    ->all();
  $selectedFilterChapterNumbers = collect(old('filter_chapter_numbers', $selectedChapters->pluck('chapter_number')->all()))
    ->map(fn ($value) => (string) $value)
    ->all();
@endphp
<main class="admin-dashboard-main">
  <section class="admin-dashboard-card admin-verse-compose-page">
    <div class="admin-dashboard-intro">
      <span class="admin-auth-eyebrow">Scripture content</span>
      <h1>Upload Verse</h1>
      <p>Create verse records with clearer scripture context, organized prayer metadata, and a more comfortable writing flow.</p>
    </div>

    <div class="admin-dashboard-layout">
      @include('admin.partials.sidebar')

      <div class="admin-dashboard-content">
        <section class="admin-dashboard-panel admin-dashboard-overview-panel admin-verse-compose-hero">
          <div class="admin-dashboard-panel-head admin-dashboard-panel-head-inline admin-verse-compose-hero-head">
            <div class="admin-verse-compose-hero-copy">
              <span class="admin-dashboard-panel-tag">Verse composer</span>
              <h2>Build a verse entry with the right reference and prayer focus</h2>
              <p>Choose the destination chapter, connect the right category, and write formatted verse content in one focused workspace.</p>
              <div class="admin-verse-compose-hero-pills" aria-label="Verse composer highlights">
                <span class="admin-verse-compose-hero-pill"><i class="fa-solid fa-book-open"></i> Linked to saved chapters</span>
                <span class="admin-verse-compose-hero-pill"><i class="fa-solid fa-list-check"></i> Organized by category</span>
                <span class="admin-verse-compose-hero-pill"><i class="fa-solid fa-pen-nib"></i> Rich-text ready</span>
              </div>
            </div>
            <div class="admin-dashboard-record-actions admin-verse-compose-hero-actions">
              <a href="{{ route('admin.upload-chapter') }}" class="admin-dashboard-action-link">Create Chapter</a>
              <a href="{{ route('admin.list-verses') }}" class="admin-dashboard-action-link">List Verses</a>
            </div>
          </div>

          <div class="admin-verse-compose-hero-grid">
            <div class="admin-dashboard-stats-grid admin-verse-compose-stats-grid">
              <article class="admin-dashboard-stat-card">
                <span class="admin-dashboard-stat-label">Available chapters</span>
                <strong>{{ $chapters->count() }}</strong>
                <p>Saved chapter records ready to receive verse entries.</p>
              </article>
              <article class="admin-dashboard-stat-card">
                <span class="admin-dashboard-stat-label">Books covered</span>
                <strong>{{ $chapterBooksCovered }}</strong>
                <p>Distinct books represented by the current chapter library.</p>
              </article>
              <article class="admin-dashboard-stat-card">
                <span class="admin-dashboard-stat-label">Categories</span>
                <strong>{{ $categories->count() }}</strong>
                <p>Prayer categories available for organizing the verse topic.</p>
              </article>
            </div>

            <aside class="admin-verse-compose-context-card" aria-label="Current verse draft context">
              <span class="admin-verse-compose-context-label">Draft context</span>
              <div class="admin-verse-compose-context-stack">
                <strong>{{ $selectedChapters->count() ? $selectedChapters->count() . ' chapter entr' . ($selectedChapters->count() === 1 ? 'y' : 'ies') . ' prepared' : 'No chapter selected yet' }}</strong>
                <span>
                  {{ $selectedChapters->count() ? 'Each selected chapter will create its own verse record.' : 'Choose one or more chapters to start building verse entries.' }}
                </span>
              </div>
              <dl class="admin-verse-compose-context-meta">
                <div>
                  <dt>Testament</dt>
                  <dd>{{ $selectedTestament?->name ?? 'Not selected' }}</dd>
                </div>
                <div>
                  <dt>Category</dt>
                  <dd>{{ $selectedCategory?->name ?? 'Not selected' }}</dd>
                </div>
                <div>
                  <dt>Verse number</dt>
                  <dd>{{ old('verse_number') ?: 'Not entered' }}</dd>
                </div>
              </dl>
            </aside>
          </div>
        </section>

        <section class="admin-dashboard-panel admin-verse-compose-form-panel">
          <div class="admin-dashboard-panel-head admin-dashboard-panel-head-inline admin-verse-compose-form-head">
            <div class="admin-verse-compose-form-copy">
              <span class="admin-dashboard-panel-tag">Verse form</span>
              <h2>New verse entry</h2>
              <p>Start with the scripture reference, then add the prayer topic and the verse text below.</p>
            </div>
            <div class="admin-verse-compose-form-tip">
              <span class="admin-verse-compose-form-tip-label">Writing flow</span>
              <strong>Reference first, content second</strong>
              <span>That keeps each verse attached to the correct chapter before you format the text.</span>
            </div>
          </div>

          @if ($errors->any())
            <div class="form-alert form-alert-error">
              <i class="fa-solid fa-circle-exclamation"></i>
              <span>{{ $errors->first() }}</span>
            </div>
          @endif

          <form action="{{ route('admin.upload-verse.store') }}" method="POST" class="admin-auth-form admin-verse-compose-form">
            @csrf
            <section class="admin-verse-compose-block">
              <div class="admin-verse-compose-block-head">
                <span class="admin-verse-compose-block-tag">Reference</span>
                <h3>Locate the verse correctly</h3>
              </div>
              <div class="admin-verse-compose-grid">
                <div class="admin-verse-compose-filter-assist">
                  <div class="admin-verse-compose-filter-assist-head">
                    <span class="admin-verse-compose-block-tag">Filter assistant</span>
                    <h4>Browse chapters across multiple books before choosing one</h4>
                    <p>Select any combination of testaments, books, or chapter numbers to narrow the final chapter list.</p>
                  </div>
                  <div class="admin-verse-compose-filter-grid">
                    <label class="admin-auth-field admin-verse-compose-field">
                      <span>Testaments</span>
                      <select name="filter_testament_ids[]" multiple size="4" data-filter-testaments>
                        @foreach ($testaments as $testament)
                          <option value="{{ $testament->id }}" @selected(in_array((string) $testament->id, $selectedFilterTestaments, true))>
                            {{ $testament->name }}
                          </option>
                        @endforeach
                      </select>
                      <small class="admin-verse-compose-field-hint">You can select more than one testament.</small>
                    </label>
                    <label class="admin-auth-field admin-verse-compose-field">
                      <span>Books</span>
                      <select name="filter_book_ids[]" multiple size="6" data-filter-books>
                        @foreach ($books as $book)
                          <option
                            value="{{ $book->id }}"
                            data-testament-id="{{ $book->testament?->id }}"
                            @selected(in_array((string) $book->id, $selectedFilterBooks, true))
                          >
                            {{ $book->name }}
                          </option>
                        @endforeach
                      </select>
                      <small class="admin-verse-compose-field-hint">Use this to mix books from one or many testaments.</small>
                    </label>
                    <label class="admin-auth-field admin-verse-compose-field">
                      <span>Chapter numbers</span>
                      <select name="filter_chapter_numbers[]" multiple size="6" data-filter-chapter-numbers>
                        @foreach ($chapterNumbers as $chapterNumber)
                          <option value="{{ $chapterNumber }}" @selected(in_array((string) $chapterNumber, $selectedFilterChapterNumbers, true))>
                            Chapter {{ $chapterNumber }}
                          </option>
                        @endforeach
                      </select>
                      <small class="admin-verse-compose-field-hint">Helpful when you want matching chapter numbers across different books.</small>
                    </label>
                  </div>
                </div>
                <label class="admin-auth-field admin-verse-compose-field">
                  <span>Add Chapter Entry</span>
                  <select data-chapter-select>
                    <option value="">Select a chapter to add</option>
                    @foreach ($chapters as $chapter)
                      <option
                        value="{{ $chapter->id }}"
                        data-testament-id="{{ $chapter->book?->testament?->id }}"
                        data-book-id="{{ $chapter->book?->id }}"
                        data-chapter-number="{{ $chapter->chapter_number }}"
                        data-chapter-label="{{ trim(($chapter->book?->name ?? $chapter->book_name) . ' - Chapter ' . $chapter->chapter_number) }}"
                      >
                        {{ $chapter->book?->name ?? $chapter->book_name }}
                        @if ($chapter->book?->testament?->name)
                          ({{ $chapter->book->testament->name }})
                        @endif
                        - Chapter {{ $chapter->chapter_number }}
                      </option>
                    @endforeach
                  </select>
                  <small class="admin-verse-compose-field-hint">Each selection adds a new verse-entry card below. You can add more than one and remove any card with the close icon.</small>
                </label>
                <label class="admin-auth-field admin-verse-compose-field">
                  <span>Category</span>
                  <select name="category_id">
                    <option value="">Select a category</option>
                    @foreach ($categories as $category)
                      <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                    @endforeach
                  </select>
                  <small class="admin-verse-compose-field-hint">Pick the prayer category that best matches the verse focus.</small>
                </label>
                <label class="admin-auth-field admin-verse-compose-field">
                  <span>Prayer Topic</span>
                  <input type="text" name="prayer_topic" value="{{ old('prayer_topic') }}" placeholder="e.g. Divine help and direction" />
                </label>
              </div>
            </section>

            <section class="admin-verse-compose-block admin-verse-compose-editor-block">
              <div class="admin-verse-compose-block-head">
                <span class="admin-verse-compose-block-tag">Content</span>
                <h3>Write and format each selected chapter entry</h3>
                <p>Every card below becomes one verse record when you submit the form.</p>
              </div>
              <div class="admin-verse-compose-entry-list" data-verse-entry-list>
                @forelse ($entryDrafts as $index => $entry)
                  @php
                    $entryChapter = $chapters->firstWhere('id', (int) data_get($entry, 'chapter_id'));
                  @endphp
                  @if ($entryChapter)
                    <article class="admin-verse-compose-entry-card" data-entry-card data-chapter-id="{{ $entryChapter->id }}">
                      <input type="hidden" name="entries[{{ $index }}][chapter_id]" value="{{ $entryChapter->id }}" data-entry-chapter-id>
                      <div class="admin-verse-compose-entry-card-head">
                        <div class="admin-verse-compose-entry-card-copy">
                          <span class="admin-verse-compose-block-tag">Chapter entry</span>
                          <h4>{{ $entryChapter->book?->name ?? $entryChapter->book_name }} - Chapter {{ $entryChapter->chapter_number }}</h4>
                        </div>
                        <button type="button" class="admin-verse-compose-remove-button" data-remove-entry aria-label="Remove chapter entry">
                          <i class="fa-solid fa-xmark"></i>
                        </button>
                      </div>
                      <div class="admin-verse-compose-entry-fields">
                        <label class="admin-auth-field admin-verse-compose-field">
                          <span>Verse Number(s)</span>
                          <input
                            type="text"
                            name="entries[{{ $index }}][verse_number]"
                            value="{{ data_get($entry, 'verse_number') }}"
                            inputmode="text"
                            placeholder="e.g. 1, 2, 5-8"
                          />
                          <small class="admin-verse-compose-field-hint">Use commas or ranges if this chapter entry covers more than one verse number.</small>
                        </label>
                        <label class="admin-auth-field admin-verse-compose-field admin-verse-compose-editor-field">
                          <span>Verse Text</span>
                          @include('admin.partials.rich-text-editor', [
                            'name' => 'entries[' . $index . '][verse_text]',
                            'value' => data_get($entry, 'verse_text'),
                            'placeholder' => 'Enter the verse text for this chapter here',
                          ])
                        </label>
                      </div>
                    </article>
                  @endif
                @empty
                  <div class="admin-verse-compose-empty-state" data-entry-empty-state>
                    <i class="fa-solid fa-book-open-reader"></i>
                    <div>
                      <strong>No chapter entries yet</strong>
                      <p>Select a chapter above to create the first verse-entry card.</p>
                    </div>
                  </div>
                @endforelse
              </div>
            </section>

            <div class="admin-dashboard-record-actions admin-verse-compose-submit-row">
              <button type="submit" class="primary-button admin-auth-submit">Upload Verse</button>
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
    const testamentFilter = document.querySelector('[data-filter-testaments]');
    const bookFilter = document.querySelector('[data-filter-books]');
    const chapterNumberFilter = document.querySelector('[data-filter-chapter-numbers]');
    const chapterSelect = document.querySelector('[data-chapter-select]');
    const entryList = document.querySelector('[data-verse-entry-list]');
    const contextTitle = document.querySelector('.admin-verse-compose-context-stack strong');
    const contextSubtitle = document.querySelector('.admin-verse-compose-context-stack span');
    const contextVerseNumber = document.querySelector('.admin-verse-compose-context-meta dd:last-child');

    const updateContextSummary = function () {
      if (!entryList || !contextTitle || !contextSubtitle) {
        return;
      }

      const cards = entryList.querySelectorAll('[data-entry-card]');
      if (cards.length) {
        contextTitle.textContent = cards.length + ' chapter entr' + (cards.length === 1 ? 'y prepared' : 'ies prepared');
        contextSubtitle.textContent = 'Each selected chapter will create its own verse record.';
      } else {
        contextTitle.textContent = 'No chapter selected yet';
        contextSubtitle.textContent = 'Choose one or more chapters to start building verse entries.';
      }

      if (contextVerseNumber) {
        contextVerseNumber.textContent = cards.length ? cards.length + ' pending entr' + (cards.length === 1 ? 'y' : 'ies') : 'Not entered';
      }
    };

    const syncEmptyState = function () {
      if (!entryList) {
        return;
      }

      const cards = entryList.querySelectorAll('[data-entry-card]');
      let emptyState = entryList.querySelector('[data-entry-empty-state]');

      if (!cards.length && !emptyState) {
        emptyState = document.createElement('div');
        emptyState.className = 'admin-verse-compose-empty-state';
        emptyState.setAttribute('data-entry-empty-state', '');
        emptyState.innerHTML = '<i class="fa-solid fa-book-open-reader"></i><div><strong>No chapter entries yet</strong><p>Select a chapter above to create the first verse-entry card.</p></div>';
        entryList.appendChild(emptyState);
      }

      if (cards.length && emptyState) {
        emptyState.remove();
      }

      updateContextSummary();
    };

    const renumberEntryCards = function () {
      if (!entryList) {
        return;
      }

      entryList.querySelectorAll('[data-entry-card]').forEach(function (card, index) {
        const chapterInput = card.querySelector('[data-entry-chapter-id]');
        const verseNumberInput = card.querySelector('input[name*="[verse_number]"]');
        const verseTextInput = card.querySelector('.admin-rich-text-input');

        if (chapterInput) {
          chapterInput.name = 'entries[' + index + '][chapter_id]';
        }

        if (verseNumberInput) {
          verseNumberInput.name = 'entries[' + index + '][verse_number]';
        }

        if (verseTextInput) {
          verseTextInput.name = 'entries[' + index + '][verse_text]';
        }
      });
    };

    const initRichTextEditor = function (wrapper) {
      if (!wrapper || wrapper.dataset.richTextInitialized === 'true') {
        return;
      }

      const editor = wrapper.querySelector('[data-rich-text-surface]');
      const input = wrapper.querySelector('.admin-rich-text-input');
      const buttons = wrapper.querySelectorAll('[data-command]');

      if (!editor || !input) {
        return;
      }

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

      wrapper.dataset.richTextInitialized = 'true';
    };

    const createEntryCard = function (option) {
      if (!entryList || !option) {
        return;
      }

      const currentIndex = entryList.querySelectorAll('[data-entry-card]').length;
      const article = document.createElement('article');
      article.className = 'admin-verse-compose-entry-card';
      article.setAttribute('data-entry-card', '');
      article.setAttribute('data-chapter-id', option.value);
      article.innerHTML =
        '<input type="hidden" name="entries[' + currentIndex + '][chapter_id]" value="' + option.value + '" data-entry-chapter-id>' +
        '<div class="admin-verse-compose-entry-card-head">' +
          '<div class="admin-verse-compose-entry-card-copy">' +
            '<span class="admin-verse-compose-block-tag">Chapter entry</span>' +
            '<h4>' + option.dataset.chapterLabel + '</h4>' +
          '</div>' +
          '<button type="button" class="admin-verse-compose-remove-button" data-remove-entry aria-label="Remove chapter entry">' +
            '<i class="fa-solid fa-xmark"></i>' +
          '</button>' +
        '</div>' +
        '<div class="admin-verse-compose-entry-fields">' +
          '<label class="admin-auth-field admin-verse-compose-field">' +
            '<span>Verse Number(s)</span>' +
            '<input type="text" name="entries[' + currentIndex + '][verse_number]" value="" inputmode="text" placeholder="e.g. 1, 2, 5-8" />' +
            '<small class="admin-verse-compose-field-hint">Use commas or ranges if this chapter entry covers more than one verse number.</small>' +
          '</label>' +
          '<label class="admin-auth-field admin-verse-compose-field admin-verse-compose-editor-field">' +
            '<span>Verse Text</span>' +
            '<div class="admin-rich-text" data-rich-text-editor>' +
              '<div class="admin-rich-text-toolbar" role="toolbar" aria-label="Text formatting">' +
                '<button type="button" class="admin-rich-text-button" data-command="bold"><strong>B</strong></button>' +
                '<button type="button" class="admin-rich-text-button" data-command="italic"><em>I</em></button>' +
                '<button type="button" class="admin-rich-text-button" data-command="underline"><u>U</u></button>' +
                '<button type="button" class="admin-rich-text-button" data-command="insertUnorderedList"><i class="fa-solid fa-list-ul"></i></button>' +
                '<button type="button" class="admin-rich-text-button" data-command="insertOrderedList"><i class="fa-solid fa-list-ol"></i></button>' +
                '<button type="button" class="admin-rich-text-button" data-command="formatBlock" data-value="p">P</button>' +
              '</div>' +
              '<div class="admin-rich-text-editor" contenteditable="true" data-rich-text-surface data-placeholder="Enter the verse text for this chapter here"></div>' +
              '<textarea name="entries[' + currentIndex + '][verse_text]" class="admin-rich-text-input" hidden></textarea>' +
            '</div>' +
          '</label>' +
        '</div>';

      entryList.appendChild(article);
      renumberEntryCards();
      initRichTextEditor(article.querySelector('[data-rich-text-editor]'));
      syncEmptyState();
    };

    const removeEntryCard = function (card) {
      if (!card) {
        return;
      }

      const chapterId = card.dataset.chapterId;
      const option = chapterSelect ? chapterSelect.querySelector('option[value="' + chapterId + '"]') : null;
      if (option) {
        option.disabled = false;
      }

      card.remove();
      renumberEntryCards();
      syncEmptyState();
    };

    if (testamentFilter && bookFilter && chapterNumberFilter && chapterSelect) {
      const getSelectedValues = function (select) {
        return Array.from(select.selectedOptions).map(function (option) {
          return option.value;
        });
      };

      const bookOptions = Array.from(bookFilter.querySelectorAll('option'));
      const chapterNumberOptions = Array.from(chapterNumberFilter.querySelectorAll('option'));
      const chapterOptions = Array.from(chapterSelect.querySelectorAll('option'));
      const placeholderOption = chapterOptions[0] || null;

      const syncBookFilterOptions = function () {
        const selectedTestamentIds = getSelectedValues(testamentFilter);
        const selectedBookIds = getSelectedValues(bookFilter);

        bookOptions.forEach(function (option) {
          const optionTestamentId = option.dataset.testamentId || '';
          const matchesTestament = selectedTestamentIds.length === 0 || selectedTestamentIds.includes(optionTestamentId);

          option.hidden = !matchesTestament;

          if (!matchesTestament && selectedBookIds.includes(option.value)) {
            option.selected = false;
          }
        });
      };

      const syncChapterNumberFilterOptions = function () {
        const selectedTestamentIds = getSelectedValues(testamentFilter);
        const selectedBookIds = getSelectedValues(bookFilter);
        const selectedChapterNumbers = getSelectedValues(chapterNumberFilter);
        const availableChapterNumbers = new Set();

        chapterOptions.slice(1).forEach(function (option) {
          const optionTestamentId = option.dataset.testamentId || '';
          const optionBookId = option.dataset.bookId || '';
          const matchesTestament = selectedTestamentIds.length === 0 || selectedTestamentIds.includes(optionTestamentId);
          const matchesBook = selectedBookIds.length === 0 || selectedBookIds.includes(optionBookId);

          if (matchesTestament && matchesBook) {
            availableChapterNumbers.add(option.dataset.chapterNumber || '');
          }
        });

        chapterNumberOptions.forEach(function (option) {
          const isAvailable = availableChapterNumbers.has(option.value);
          option.hidden = !isAvailable;

          if (!isAvailable && selectedChapterNumbers.includes(option.value)) {
            option.selected = false;
          }
        });
      };

      const filterChapters = function () {
        const selectedTestamentIds = getSelectedValues(testamentFilter);
        const selectedBookIds = getSelectedValues(bookFilter);
        const selectedChapterNumbers = getSelectedValues(chapterNumberFilter);
        const previousValue = chapterSelect.value;
        const activeChapterIds = entryList
          ? Array.from(entryList.querySelectorAll('[data-entry-card]')).map(function (card) {
              return card.dataset.chapterId;
            })
          : [];

        chapterSelect.innerHTML = '';

        if (placeholderOption) {
          chapterSelect.appendChild(placeholderOption);
        }

        chapterOptions.slice(1).forEach(function (option) {
          const optionTestamentId = option.dataset.testamentId || '';
          const optionBookId = option.dataset.bookId || '';
          const optionChapterNumber = option.dataset.chapterNumber || '';
          const matchesTestament = selectedTestamentIds.length === 0 || selectedTestamentIds.includes(optionTestamentId);
          const matchesBook = selectedBookIds.length === 0 || selectedBookIds.includes(optionBookId);
          const matchesChapterNumber = selectedChapterNumbers.length === 0 || selectedChapterNumbers.includes(optionChapterNumber);

          if (matchesTestament && matchesBook && matchesChapterNumber) {
            option.disabled = activeChapterIds.includes(option.value);
            chapterSelect.appendChild(option);
          }
        });

        const hasPreviousValue = Array.from(chapterSelect.options).some(function (option) {
          return option.value === previousValue;
        });

        chapterSelect.value = hasPreviousValue ? previousValue : '';
      };

      const syncAllFilters = function () {
        syncBookFilterOptions();
        syncChapterNumberFilterOptions();
        filterChapters();
      };

      testamentFilter.addEventListener('change', syncAllFilters);
      bookFilter.addEventListener('change', syncAllFilters);
      chapterNumberFilter.addEventListener('change', filterChapters);
      chapterSelect.addEventListener('change', function () {
        const selectedOption = chapterSelect.options[chapterSelect.selectedIndex];
        if (!selectedOption || !selectedOption.value) {
          return;
        }

        createEntryCard(selectedOption);
        selectedOption.disabled = true;
        chapterSelect.value = '';
        filterChapters();
      });
      syncAllFilters();
    }

    if (entryList) {
      entryList.addEventListener('click', function (event) {
        const removeButton = event.target.closest('[data-remove-entry]');
        if (!removeButton) {
          return;
        }

        removeEntryCard(removeButton.closest('[data-entry-card]'));
        if (testamentFilter && bookFilter && chapterNumberFilter && chapterSelect) {
          const changeEvent = new Event('change');
          chapterNumberFilter.dispatchEvent(changeEvent);
        }
      });
    }

    document.querySelectorAll('[data-rich-text-editor]').forEach(function (wrapper) {
      initRichTextEditor(wrapper);
    });

    syncEmptyState();
  });
</script>
@endsection
