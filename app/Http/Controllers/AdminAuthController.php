<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Chapter;
use App\Models\Category;
use App\Models\Testament;
use App\Models\User;
use App\Models\Verse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    private const MAX_ADMIN_ACCOUNTS = 2;

    public function showSignup(): View
    {
        return view('admin.auth.signup', [
            'adminLimitReached' => User::where('is_admin', true)->count() >= self::MAX_ADMIN_ACCOUNTS,
            'adminSlotsRemaining' => max(self::MAX_ADMIN_ACCOUNTS - User::where('is_admin', true)->count(), 0),
        ]);
    }

    public function signup(Request $request): RedirectResponse
    {
        if (User::where('is_admin', true)->count() >= self::MAX_ADMIN_ACCOUNTS) {
            return back()
                ->withErrors([
                    'email' => 'Only 2 admin accounts are allowed for this platform.',
                ])
                ->onlyInput('name', 'email');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $admin = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'is_admin' => true,
        ]);

        Auth::login($admin);
        $request->session()->regenerate();

        return redirect()
            ->route('admin.dashboard')
            ->with('status', 'Admin account created successfully.');
    }

    public function showSignin(): View
    {
        return view('admin.auth.signin');
    }

    public function signin(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors([
                    'email' => 'The provided credentials do not match our records.',
                ])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        if (! $request->user()?->is_admin) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors([
                    'email' => 'This account does not have admin access.',
                ])
                ->onlyInput('email');
        }

        return redirect()
            ->route('admin.dashboard')
            ->with('status', 'Welcome back, admin.');
    }

    public function dashboard(): View
    {
        return view('admin.dashboard', [
            'totalCategories' => Category::count(),
            'totalTestaments' => Testament::count(),
            'totalBooks' => Book::count(),
            'totalChapters' => Chapter::count(),
            'totalVerses' => Verse::count(),
            'latestCategory' => Category::latest()->first(),
            'latestBook' => Book::with('testament')->latest()->first(),
            'latestVerse' => Verse::with('chapter.book')->latest()->first(),
        ]);
    }

    public function uploadCategory(): View
    {
        return view('admin.upload-category');
    }

    public function listCategories(Request $request): View
    {
        $categories = Category::query()
            ->when($request->filled('category_name'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->string('category_name')->trim() . '%');
            })
            ->when($request->filled('description'), function ($query) use ($request) {
                $query->where('description', 'like', '%' . $request->string('description')->trim() . '%');
            })
            ->latest()
            ->get();

        return view('admin.list-categories', [
            'categories' => $categories,
            'totalCategories' => Category::count(),
        ]);
    }

    public function editCategory(Category $category): View
    {
        return view('admin.edit-category', [
            'category' => $category,
        ]);
    }

    public function deleteCategory(Category $category): View
    {
        return view('admin.delete-category', [
            'category' => $category,
        ]);
    }

    public function storeCategory(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'category_description' => ['nullable', 'string', 'max:1000'],
        ]);

        Category::create([
            'name' => $validated['category_name'],
            'description' => $validated['category_description'] ?? null,
        ]);

        return redirect()
            ->route('admin.list-categories')
            ->with('status', 'Category uploaded successfully.');
    }

    public function updateCategory(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'category_name' => ['required', 'string', 'max:255', 'unique:categories,name,' . $category->id],
            'category_description' => ['nullable', 'string', 'max:1000'],
        ]);

        $category->update([
            'name' => $validated['category_name'],
            'description' => $validated['category_description'] ?? null,
        ]);

        return redirect()
            ->route('admin.list-categories')
            ->with('status', 'Category updated successfully.');
    }

    public function destroyCategory(Category $category): RedirectResponse
    {
        $category->delete();

        return redirect()
            ->route('admin.list-categories')
            ->with('status', 'Category deleted successfully.');
    }

    public function uploadTestament(): View
    {
        return view('admin.upload-testament');
    }

    public function listTestaments(Request $request): View
    {
        $testaments = Testament::withCount('books')
            ->when($request->filled('testament_name'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->string('testament_name')->trim() . '%');
            })
            ->when($request->filled('summary'), function ($query) use ($request) {
                $query->where('summary', 'like', '%' . $request->string('summary')->trim() . '%');
            })
            ->when($request->filled('book_count'), function ($query) use ($request) {
                $query->has('books', '=', $request->integer('book_count'));
            })
            ->latest()
            ->get();

        return view('admin.list-testaments', [
            'testaments' => $testaments,
            'totalTestaments' => Testament::count(),
        ]);
    }

    public function storeTestament(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'testament_name' => ['required', 'string', 'max:255', 'unique:testaments,name'],
            'testament_summary' => ['nullable', 'string', 'max:1000'],
        ]);

        Testament::create([
            'name' => $validated['testament_name'],
            'summary' => $validated['testament_summary'] ?? null,
        ]);

        return redirect()
            ->route('admin.list-testaments')
            ->with('status', 'Testament uploaded successfully.');
    }

    public function uploadChapter(): View
    {
        return view('admin.upload-chapter', [
            'books' => Book::orderBy('name')->get(),
        ]);
    }

    public function uploadBook(): View
    {
        return view('admin.upload-book', [
            'testaments' => Testament::orderBy('name')->get(),
        ]);
    }

    public function listBooks(Request $request): View
    {
        $books = Book::with(['testament'])
            ->withCount('chapters')
            ->when($request->filled('testament_id'), function ($query) use ($request) {
                $query->where('testament_id', $request->integer('testament_id'));
            })
            ->when($request->filled('book_name'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->string('book_name')->trim() . '%');
            })
            ->when($request->filled('chapter_count'), function ($query) use ($request) {
                $query->has('chapters', '=', $request->integer('chapter_count'));
            })
            ->latest()
            ->get();

        return view('admin.list-books', [
            'books' => $books,
            'totalBooks' => Book::count(),
            'testaments' => Testament::orderBy('name')->get(),
        ]);
    }

    public function editBook(Book $book): View
    {
        return view('admin.edit-book', [
            'book' => $book,
            'testaments' => Testament::orderBy('name')->get(),
        ]);
    }

    public function deleteBook(Book $book): View
    {
        return view('admin.delete-book', [
            'book' => $book,
        ]);
    }

    public function storeBook(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'testament_id' => ['required', 'exists:testaments,id'],
            'book_name' => ['required', 'string', 'max:255', 'unique:books,name'],
        ]);

        Book::create([
            'testament_id' => $validated['testament_id'],
            'name' => $validated['book_name'],
        ]);

        return redirect()
            ->route('admin.list-books')
            ->with('status', 'Book uploaded successfully.');
    }

    public function updateBook(Request $request, Book $book): RedirectResponse
    {
        $validated = $request->validate([
            'testament_id' => ['required', 'exists:testaments,id'],
            'book_name' => ['required', 'string', 'max:255', 'unique:books,name,' . $book->id],
        ]);

        $book->update([
            'testament_id' => $validated['testament_id'],
            'name' => $validated['book_name'],
        ]);

        Chapter::where('book_id', $book->id)->update([
            'book_name' => $validated['book_name'],
        ]);

        return redirect()
            ->route('admin.list-books')
            ->with('status', 'Book updated successfully.');
    }

    public function destroyBook(Book $book): RedirectResponse
    {
        if ($book->chapters()->exists()) {
            return redirect()
                ->route('admin.list-books')
                ->withErrors([
                    'book' => 'This book cannot be deleted because it already has chapters.',
                ]);
        }

        $book->delete();

        return redirect()
            ->route('admin.list-books')
            ->with('status', 'Book deleted successfully.');
    }

    public function listChapters(Request $request): View
    {
        $chapters = Chapter::with('book.testament')
            ->when($request->filled('testament_id'), function ($query) use ($request) {
                $query->whereHas('book', function ($bookQuery) use ($request) {
                    $bookQuery->where('testament_id', $request->integer('testament_id'));
                });
            })
            ->when($request->filled('book_id'), function ($query) use ($request) {
                $query->where('book_id', $request->integer('book_id'));
            })
            ->when($request->filled('chapter_number'), function ($query) use ($request) {
                $query->where('chapter_number', $request->integer('chapter_number'));
            })
            ->latest()
            ->get();

        return view('admin.list-chapters', [
            'chapters' => $chapters,
            'totalChapters' => Chapter::count(),
            'testaments' => Testament::orderBy('name')->get(),
            'books' => Book::with('testament')->orderBy('name')->get(),
        ]);
    }

    public function editChapter(Chapter $chapter): View
    {
        return view('admin.edit-chapter', [
            'chapter' => $chapter->load('book'),
            'books' => Book::orderBy('name')->get(),
        ]);
    }

    public function deleteChapter(Chapter $chapter): View
    {
        return view('admin.delete-chapter', [
            'chapter' => $chapter->load('book'),
        ]);
    }

    public function storeChapter(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'book_id' => ['required', 'exists:books,id'],
                'chapter_number' => [
                    'required',
                    'integer',
                    'min:1',
                    Rule::unique('chapters')->where(function ($query) use ($request) {
                        return $query->where('book_id', $request->integer('book_id'));
                    }),
                ],
            ],
            [
                'chapter_number.unique' => 'This chapter number already exists for the selected book.',
            ]
        );

        $book = Book::findOrFail($validated['book_id']);

        Chapter::create([
            'book_id' => $book->id,
            'book_name' => $book->name,
            'chapter_number' => $validated['chapter_number'],
        ]);

        return redirect()
            ->route('admin.list-chapters')
            ->with('status', 'Chapter uploaded successfully.');
    }

    public function updateChapter(Request $request, Chapter $chapter): RedirectResponse
    {
        $validated = $request->validate(
            [
                'book_id' => ['required', 'exists:books,id'],
                'chapter_number' => [
                    'required',
                    'integer',
                    'min:1',
                    Rule::unique('chapters')
                        ->ignore($chapter->id)
                        ->where(function ($query) use ($request) {
                            return $query->where('book_id', $request->integer('book_id'));
                        }),
                ],
            ],
            [
                'chapter_number.unique' => 'This chapter number already exists for the selected book.',
            ]
        );

        $book = Book::findOrFail($validated['book_id']);

        $chapter->update([
            'book_id' => $book->id,
            'book_name' => $book->name,
            'chapter_number' => $validated['chapter_number'],
        ]);

        return redirect()
            ->route('admin.list-chapters')
            ->with('status', 'Chapter updated successfully.');
    }

    public function destroyChapter(Chapter $chapter): RedirectResponse
    {
        $chapter->delete();

        return redirect()
            ->route('admin.list-chapters')
            ->with('status', 'Chapter deleted successfully.');
    }

    public function uploadVerse(): View
    {
        return view('admin.upload-verse', [
            'chapters' => Chapter::with('book.testament')->orderBy('book_name')->orderBy('chapter_number')->get(),
            'books' => Book::with('testament')->orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get(),
            'testaments' => Testament::orderBy('name')->get(),
        ]);
    }

    public function listVerses(Request $request): View
    {
        $verses = Verse::with(['chapter.book.testament', 'category'])
            ->when($request->filled('testament_id'), function ($query) use ($request) {
                $query->whereHas('chapter.book', function ($bookQuery) use ($request) {
                    $bookQuery->where('testament_id', $request->integer('testament_id'));
                });
            })
            ->when($request->filled('book_id'), function ($query) use ($request) {
                $query->whereHas('chapter', function ($chapterQuery) use ($request) {
                    $chapterQuery->where('book_id', $request->integer('book_id'));
                });
            })
            ->when($request->filled('chapter_id'), function ($query) use ($request) {
                $query->where('chapter_id', $request->integer('chapter_id'));
            })
            ->when($request->filled('category_id'), function ($query) use ($request) {
                $query->where('category_id', $request->integer('category_id'));
            })
            ->when($request->filled('verse_number'), function ($query) use ($request) {
                $query->where('verse_number', trim((string) $request->input('verse_number')));
            })
            ->when($request->filled('prayer_topic'), function ($query) use ($request) {
                $query->where('prayer_topic', 'like', '%' . $request->string('prayer_topic')->trim() . '%');
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('verse_text', 'like', '%' . $request->string('search')->trim() . '%');
            })
            ->latest()
            ->get();

        return view('admin.list-verses', [
            'verses' => $verses,
            'totalVerses' => Verse::count(),
            'testaments' => Testament::orderBy('name')->get(),
            'books' => Book::orderBy('name')->get(),
            'chapters' => Chapter::with('book')->orderBy('book_name')->orderBy('chapter_number')->get(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function editVerse(Verse $verse): View
    {
        return view('admin.edit-verse', [
            'verse' => $verse->load(['chapter.book', 'category']),
            'chapters' => Chapter::with('book')->orderBy('book_name')->orderBy('chapter_number')->get(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function deleteVerse(Verse $verse): View
    {
        return view('admin.delete-verse', [
            'verse' => $verse->load('chapter.book'),
        ]);
    }

    public function storeVerse(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'prayer_topic' => ['required', 'string', 'max:255'],
            'entries' => ['required', 'array', 'min:1'],
            'entries.*.chapter_id' => ['required', 'exists:chapters,id', 'distinct'],
            'entries.*.verse_number' => ['required', 'string', 'max:255'],
            'entries.*.verse_text' => ['required', 'string'],
        ]);

        $prayerTopic = trim($validated['prayer_topic']);

        foreach ($validated['entries'] as $entry) {
            Verse::create([
                'chapter_id' => $entry['chapter_id'],
                'category_id' => $validated['category_id'],
                'prayer_topic' => $prayerTopic,
                'verse_number' => trim($entry['verse_number']),
                'verse_text' => $entry['verse_text'],
            ]);
        }

        return redirect()
            ->route('admin.list-verses')
            ->with('status', count($validated['entries']) === 1 ? 'Verse uploaded successfully.' : count($validated['entries']) . ' verses uploaded successfully.');
    }

    public function updateVerse(Request $request, Verse $verse): RedirectResponse
    {
        $validated = $request->validate([
            'chapter_id' => ['required', 'exists:chapters,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'prayer_topic' => ['required', 'string', 'max:255'],
            'verse_number' => ['required', 'string', 'max:255'],
            'verse_text' => ['required', 'string'],
        ]);

        $validated['prayer_topic'] = trim($validated['prayer_topic']);
        $validated['verse_number'] = trim($validated['verse_number']);

        $verse->update($validated);

        return redirect()
            ->route('admin.list-verses')
            ->with('status', 'Verse updated successfully.');
    }

    public function destroyVerse(Verse $verse): RedirectResponse
    {
        $verse->delete();

        return redirect()
            ->route('admin.list-verses')
            ->with('status', 'Verse deleted successfully.');
    }

    public function changePassword(): View
    {
        return view('admin.change-password');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('admin.signin')
            ->with('status', 'You have been signed out.');
    }
}
