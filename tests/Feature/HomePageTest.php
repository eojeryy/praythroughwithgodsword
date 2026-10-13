<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\Chapter;
use Illuminate\Http\Request;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    public function test_home_page_renders_searchable_library_and_real_destinations(): void
    {
        $category = (new Category)->forceFill(['id' => 1, 'name' => 'Peace', 'description' => 'Prayer for a peaceful heart.']);
        $book = (new Book)->forceFill(['id' => 2, 'name' => 'Psalms']);
        $chapter = (new Chapter)->forceFill(['id' => 3, 'book_name' => 'Psalms', 'chapter_number' => 119]);
        $chapter->setRelation('book', $book);
        $topic = (object) ['topic' => 'Peace & hope', 'category' => 'Peace', 'verses_count' => 2, 'books_count' => 1];

        $html = view('index', $this->library([
            'categories' => collect([$category]),
            'oldTestamentChapters' => collect([$chapter]),
            'oldTestamentTopics' => collect([$topic]),
        ]))->render();

        $this->assertStringContainsString('css/home.css', $html);
        $this->assertStringContainsString('data-search-title="Psalms"', $html);
        $this->assertStringContainsString('data-search-title="Peace &amp; hope"', $html);
        $this->assertStringContainsString('data-search-title="Peace"', $html);
        $this->assertStringContainsString(route('books.chapters', $book), $html);
        $this->assertStringContainsString(route('categories.topics', $category), $html);
        $this->assertStringContainsString(route('live'), $html);
        $this->assertStringNotContainsString('16+', $html);
        $this->assertStringNotContainsString('banner-text', $html);
    }

    public function test_home_page_has_helpful_empty_states_and_search_accessibility(): void
    {
        $html = view('index', $this->library())->render();

        $this->assertStringContainsString('No New Testament chapters yet', $html);
        $this->assertStringContainsString('No Old Testament topics yet', $html);
        $this->assertStringContainsString('for="home-prayer-search"', $html);
        $this->assertStringContainsString('data-home-search-status role="status" aria-live="polite"', $html);
        $this->assertStringContainsString('data-home-search-clear', $html);
    }

    public function test_search_query_is_escaped_and_array_queries_are_ignored(): void
    {
        $this->app->instance('request', Request::create('/?q=%3Cscript%3Ealert(1)%3C%2Fscript%3E'));
        $html = view('index', $this->library())->render();
        $this->assertStringContainsString('value="&lt;script&gt;alert(1)&lt;/script&gt;"', $html);
        $this->assertStringNotContainsString('<script>alert(1)</script>', $html);

        $this->app->instance('request', Request::create('/?q[]=peace'));
        $html = view('index', $this->library())->render();
        $this->assertStringContainsString('value="" data-home-search', $html);
    }

    private function library(array $overrides = []): array
    {
        return array_merge([
            'categories' => collect(),
            'newTestamentChapters' => collect(),
            'oldTestamentChapters' => collect(),
            'newTestamentTopics' => collect(),
            'oldTestamentTopics' => collect(),
        ], $overrides);
    }
}
