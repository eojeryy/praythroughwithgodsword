<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\LivePrayerController;
use App\Http\Controllers\MemberAuthController;
use App\Models\Book;
use App\Models\Chapter;
use App\Models\Category;
use App\Models\Verse;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $buildTopicsForTestament = function (string $keyword) {
        return Verse::with(['chapter.book.testament', 'category'])
            ->whereHas('chapter.book.testament', function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%');
            })
            ->get()
            ->filter(fn ($verse) => filled(trim((string) $verse->prayer_topic)))
            ->groupBy(fn ($verse) => trim((string) $verse->prayer_topic))
            ->map(function ($group, $topic) {
                $first = $group->first();

                return (object) [
                    'topic' => $topic,
                    'category' => $first?->category?->name,
                    'books_count' => $group->pluck('chapter.book.name')->filter()->unique()->count(),
                    'verses_count' => $group->count(),
                ];
            })
            ->sortBy('topic', SORT_NATURAL | SORT_FLAG_CASE)
            ->values();
    };

    $buildDistinctChaptersForTestament = function (string $keyword) {
        return Chapter::with('book.testament')
            ->whereHas('book.testament', function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%');
            })
            ->orderBy('book_name')
            ->orderBy('chapter_number')
            ->get()
            ->unique('book_id')
            ->take(12)
            ->values();
    };

    return view('index', [
        'categories' => Category::latest()->get(),
        'newTestamentChapters' => $buildDistinctChaptersForTestament('new'),
        'oldTestamentChapters' => $buildDistinctChaptersForTestament('old'),
        'newTestamentTopics' => $buildTopicsForTestament('new'),
        'oldTestamentTopics' => $buildTopicsForTestament('old'),
    ]);
})->name('home');

Route::get('/chapters/{chapter}', function (Chapter $chapter) {
    return view('chapter', [
        'chapter' => $chapter->load([
            'book.testament',
            'verses' => fn ($query) => $query->with('category')->orderBy('id'),
        ]),
    ]);
})->name('chapters.show');

Route::get('/books/{book}/chapters', function (Book $book) {
    return view('book-chapters', [
        'book' => $book->load([
            'testament',
            'chapters' => fn ($query) => $query->with('verses')->orderBy('chapter_number'),
        ]),
    ]);
})->name('books.chapters');

Route::get('/categories/{category}/topics', function (Category $category) {
    $category->load([
        'verses' => fn ($query) => $query->with('chapter.book.testament')->orderBy('prayer_topic')->orderBy('id'),
    ]);

    $topics = $category->verses
        ->filter(fn ($verse) => filled(trim((string) $verse->prayer_topic)))
        ->groupBy(fn ($verse) => trim((string) $verse->prayer_topic))
        ->map(function ($group, $topic) {
            $first = $group->first();

            return (object) [
                'topic' => $topic,
                'verses_count' => $group->count(),
                'books_count' => $group->pluck('chapter.book.name')->filter()->unique()->count(),
                'chapters_count' => $group->pluck('chapter_id')->filter()->unique()->count(),
                'first_reference' => $first?->chapter?->book?->name
                    ? $first->chapter->book->name . ' ' . $first->chapter->chapter_number . ':' . $first->verse_number
                    : null,
            ];
        })
        ->sortBy('topic', SORT_NATURAL | SORT_FLAG_CASE)
        ->values();

    return view('category-topics', [
        'category' => $category,
        'topics' => $topics,
    ]);
})->name('categories.topics');

Route::get('/categories/{category}/topics/{topic}', function (Category $category, string $topic) {
    $decodedTopic = urldecode($topic);

    $verses = Verse::with(['chapter.book.testament', 'category'])
        ->where('category_id', $category->id)
        ->where('prayer_topic', $decodedTopic)
        ->orderBy('chapter_id')
        ->orderBy('id')
        ->get();

    abort_if($verses->isEmpty(), 404);

    return view('topic-detail', [
        'category' => $category,
        'topic' => $decodedTopic,
        'verses' => $verses,
        'backUrl' => route('categories.topics', $category),
        'backLabel' => 'Back to Topics',
        'sectionTag' => $category->name,
    ]);
})->name('categories.topic-detail');

Route::get('/testaments/{scope}/topics/{topic}', function (string $scope, string $topic) {
    abort_unless(in_array($scope, ['new', 'old'], true), 404);

    $decodedTopic = urldecode($topic);
    $scopeLabel = $scope === 'new' ? 'New Testament' : 'Old Testament';

    $verses = Verse::with(['chapter.book.testament', 'category'])
        ->where('prayer_topic', $decodedTopic)
        ->whereHas('chapter.book.testament', function ($query) use ($scope) {
            $query->where('name', 'like', '%' . $scope . '%');
        })
        ->orderBy('chapter_id')
        ->orderBy('id')
        ->get();

    abort_if($verses->isEmpty(), 404);

    return view('topic-detail', [
        'category' => null,
        'topic' => $decodedTopic,
        'verses' => $verses,
        'backUrl' => route('home') . '#testament-topics',
        'backLabel' => 'Back to Testament Topics',
        'sectionTag' => $scopeLabel,
    ]);
})->name('testaments.topic-detail');

Route::get('/live', [LivePrayerController::class, 'index'])->name('live');

Route::middleware('guest')->prefix('member')->name('member.')->group(function (): void {
    Route::get('/signup', [MemberAuthController::class, 'showSignup'])->name('signup');
    Route::post('/signup', [MemberAuthController::class, 'signup'])->name('signup.store');
    Route::get('/signin', [MemberAuthController::class, 'showSignin'])->name('signin');
    Route::post('/signin', [MemberAuthController::class, 'signin'])->name('signin.store');
});

Route::middleware('auth')->post('/member/logout', [MemberAuthController::class, 'logout'])->name('member.logout');
Route::middleware('auth')->post('/live/comment', [LivePrayerController::class, 'storeComment'])->name('live.comment.store');

Route::middleware('guest')->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/signup', [AdminAuthController::class, 'showSignup'])->name('signup');
    Route::post('/signup', [AdminAuthController::class, 'signup'])->name('signup.store');
    Route::get('/signin', [AdminAuthController::class, 'showSignin'])->name('signin');
    Route::post('/signin', [AdminAuthController::class, 'signin'])->name('signin.store');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/dashboard', [AdminAuthController::class, 'dashboard'])->name('dashboard');
    Route::get('/upload-category', [AdminAuthController::class, 'uploadCategory'])->name('upload-category');
    Route::get('/categories', [AdminAuthController::class, 'listCategories'])->name('list-categories');
    Route::get('/categories/{category}/edit', [AdminAuthController::class, 'editCategory'])->name('edit-category');
    Route::get('/categories/{category}/delete', [AdminAuthController::class, 'deleteCategory'])->name('delete-category');
    Route::post('/upload-category', [AdminAuthController::class, 'storeCategory'])->name('upload-category.store');
    Route::put('/upload-category/{category}', [AdminAuthController::class, 'updateCategory'])->name('upload-category.update');
    Route::delete('/upload-category/{category}', [AdminAuthController::class, 'destroyCategory'])->name('upload-category.destroy');
    Route::get('/upload-testament', [AdminAuthController::class, 'uploadTestament'])->name('upload-testament');
    Route::get('/testaments', [AdminAuthController::class, 'listTestaments'])->name('list-testaments');
    Route::post('/upload-testament', [AdminAuthController::class, 'storeTestament'])->name('upload-testament.store');
    Route::get('/upload-book', [AdminAuthController::class, 'uploadBook'])->name('upload-book');
    Route::get('/books', [AdminAuthController::class, 'listBooks'])->name('list-books');
    Route::get('/books/{book}/edit', [AdminAuthController::class, 'editBook'])->name('edit-book');
    Route::get('/books/{book}/delete', [AdminAuthController::class, 'deleteBook'])->name('delete-book');
    Route::post('/upload-book', [AdminAuthController::class, 'storeBook'])->name('upload-book.store');
    Route::put('/upload-book/{book}', [AdminAuthController::class, 'updateBook'])->name('upload-book.update');
    Route::delete('/upload-book/{book}', [AdminAuthController::class, 'destroyBook'])->name('upload-book.destroy');
    Route::get('/upload-chapter', [AdminAuthController::class, 'uploadChapter'])->name('upload-chapter');
    Route::get('/chapters', [AdminAuthController::class, 'listChapters'])->name('list-chapters');
    Route::get('/chapters/{chapter}/edit', [AdminAuthController::class, 'editChapter'])->name('edit-chapter');
    Route::get('/chapters/{chapter}/delete', [AdminAuthController::class, 'deleteChapter'])->name('delete-chapter');
    Route::post('/upload-chapter', [AdminAuthController::class, 'storeChapter'])->name('upload-chapter.store');
    Route::put('/upload-chapter/{chapter}', [AdminAuthController::class, 'updateChapter'])->name('upload-chapter.update');
    Route::delete('/upload-chapter/{chapter}', [AdminAuthController::class, 'destroyChapter'])->name('upload-chapter.destroy');
    Route::get('/upload-verse', [AdminAuthController::class, 'uploadVerse'])->name('upload-verse');
    Route::get('/verses', [AdminAuthController::class, 'listVerses'])->name('list-verses');
    Route::get('/verses/{verse}/edit', [AdminAuthController::class, 'editVerse'])->name('edit-verse');
    Route::get('/verses/{verse}/delete', [AdminAuthController::class, 'deleteVerse'])->name('delete-verse');
    Route::post('/upload-verse', [AdminAuthController::class, 'storeVerse'])->name('upload-verse.store');
    Route::put('/upload-verse/{verse}', [AdminAuthController::class, 'updateVerse'])->name('upload-verse.update');
    Route::delete('/upload-verse/{verse}', [AdminAuthController::class, 'destroyVerse'])->name('upload-verse.destroy');
    Route::get('/change-password', [AdminAuthController::class, 'changePassword'])->name('change-password');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});
