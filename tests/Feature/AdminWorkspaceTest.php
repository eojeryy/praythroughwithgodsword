<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\Chapter;
use App\Models\Testament;
use App\Models\User;
use App\Models\Verse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private Testament $testament;

    private Book $book;

    private Chapter $chapter;

    private Category $category;

    private Verse $verse;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['is_admin' => true, 'password' => 'password123']);
        $this->testament = Testament::create(['name' => 'New Testament', 'summary' => 'The gospel and the early church.']);
        $this->book = Book::create(['name' => 'Matthew', 'testament_id' => $this->testament->id]);
        $this->chapter = Chapter::create(['book_id' => $this->book->id, 'book_name' => $this->book->name, 'chapter_number' => 1]);
        $this->category = Category::create(['name' => 'Faith', 'description' => 'Prayers for faith.']);
        $this->verse = Verse::create(['chapter_id' => $this->chapter->id, 'category_id' => $this->category->id, 'prayer_topic' => 'Trust in God', 'verse_number' => '1', 'verse_text' => 'Scripture text.']);
    }

    public function test_admin_can_open_every_workspace_and_record_workflow(): void
    {
        $this->actingAs($this->admin);
        $this->get(route('admin.dashboard'))->assertOk()->assertSee('Latest verse records');
        foreach (['category' => $this->category, 'testament' => $this->testament, 'book' => $this->book, 'chapter' => $this->chapter, 'verse' => $this->verse] as $type => $record) {
            $plural = $type === 'category' ? 'categories' : $type.'s';
            $this->get(route('admin.list-'.$plural))->assertOk()->assertSee(route('admin.show-'.$type, $record), false);
            foreach (['show', 'edit', 'delete'] as $action) {
                $this->get(route('admin.'.$action.'-'.$type, $record))->assertOk();
            }
            $this->get(route('admin.upload-'.$type))->assertOk();
            $this->assertDatabaseHas($record->getTable(), ['id' => $record->id]);
        }
        $this->get(route('admin.change-password'))->assertOk();
    }

    public function test_guests_and_members_cannot_manage_records(): void
    {
        $this->get(route('admin.show-testament', $this->testament))->assertRedirect();
        $member = User::factory()->create(['is_admin' => false]);
        $this->actingAs($member);
        foreach (['category' => $this->category, 'testament' => $this->testament, 'book' => $this->book, 'chapter' => $this->chapter, 'verse' => $this->verse] as $type => $record) {
            $this->get(route('admin.show-'.$type, $record))->assertForbidden();
            $this->put(route('admin.upload-'.$type.'.update', $record), [])->assertForbidden();
            $this->delete(route('admin.upload-'.$type.'.destroy', $record))->assertForbidden();
        }
        $this->put(route('admin.change-password.update'), [])->assertForbidden();
    }

    public function test_admin_can_create_edit_and_delete_an_empty_testament(): void
    {
        $this->actingAs($this->admin)->post(route('admin.upload-testament.store'), ['testament_name' => 'Old Testament', 'testament_summary' => 'Old summary.'])->assertRedirect(route('admin.list-testaments'));
        $record = Testament::where('name', 'Old Testament')->firstOrFail();
        $this->put(route('admin.upload-testament.update', $record), ['testament_name' => 'Old Testament updated', 'testament_summary' => 'Updated summary.'])->assertRedirect(route('admin.list-testaments'));
        $this->assertDatabaseHas('testaments', ['id' => $record->id, 'name' => 'Old Testament updated', 'summary' => 'Updated summary.']);
        $this->delete(route('admin.upload-testament.destroy', $record))->assertRedirect(route('admin.list-testaments'));
        $this->assertDatabaseMissing('testaments', ['id' => $record->id]);
    }

    public function test_testament_edits_reject_duplicate_names(): void
    {
        Testament::create(['name' => 'Old Testament']);
        $this->actingAs($this->admin)->put(route('admin.upload-testament.update', $this->testament), ['testament_name' => 'Old Testament'])->assertSessionHasErrors('testament_name');
        $this->assertDatabaseHas('testaments', ['id' => $this->testament->id, 'name' => 'New Testament']);
    }

    public function test_parent_records_with_children_cannot_be_deleted(): void
    {
        $this->actingAs($this->admin);
        $this->delete(route('admin.upload-testament.destroy', $this->testament))->assertSessionHasErrors('testament');
        $this->delete(route('admin.upload-book.destroy', $this->book))->assertSessionHasErrors('book');
        $this->assertDatabaseHas('testaments', ['id' => $this->testament->id]);
        $this->assertDatabaseHas('books', ['id' => $this->book->id]);
    }

    public function test_deleting_a_category_keeps_its_verses(): void
    {
        $this->actingAs($this->admin)->delete(route('admin.upload-category.destroy', $this->category))->assertRedirect(route('admin.list-categories'));
        $this->assertDatabaseMissing('categories', ['id' => $this->category->id]);
        $this->assertDatabaseHas('verses', ['id' => $this->verse->id, 'category_id' => null]);
    }

    public function test_chapter_confirmation_explains_cascade_and_delete_removes_linked_verses(): void
    {
        $this->actingAs($this->admin)->get(route('admin.delete-chapter', $this->chapter))->assertOk()->assertSee('1 linked verse records');
        $this->delete(route('admin.upload-chapter.destroy', $this->chapter))->assertRedirect(route('admin.list-chapters'));
        $this->assertDatabaseMissing('chapters', ['id' => $this->chapter->id]);
        $this->assertDatabaseMissing('verses', ['id' => $this->verse->id]);
    }

    public function test_record_edits_update_the_library_and_keep_references_in_sync(): void
    {
        $this->actingAs($this->admin);
        $this->put(route('admin.upload-book.update', $this->book), ['testament_id' => $this->testament->id, 'book_name' => 'Matthew updated'])->assertSessionHasNoErrors();
        $this->assertDatabaseHas('chapters', ['id' => $this->chapter->id, 'book_name' => 'Matthew updated']);
        $this->put(route('admin.upload-category.update', $this->category), ['category_name' => 'Hope', 'category_description' => 'Hope in prayer.'])->assertSessionHasNoErrors();
        $this->put(route('admin.upload-chapter.update', $this->chapter), ['book_id' => $this->book->id, 'chapter_number' => 2])->assertSessionHasNoErrors();
        $this->put(route('admin.upload-verse.update', $this->verse), ['chapter_id' => $this->chapter->id, 'category_id' => $this->category->id, 'prayer_topic' => 'Hope in God', 'verse_number' => '2-3', 'verse_text' => 'Updated scripture.'])->assertSessionHasNoErrors();
        $this->get(route('admin.show-verse', $this->verse))->assertOk()->assertSee('Matthew updated 2:2-3')->assertSee('Hope in God')->assertSee('Updated scripture.');
        $this->delete(route('admin.upload-verse.destroy', $this->verse))->assertRedirect(route('admin.list-verses'));
        $this->assertDatabaseMissing('verses', ['id' => $this->verse->id]);
    }

    public function test_library_filters_persist_in_pagination_and_totals_cover_all_matches(): void
    {
        for ($index = 1; $index <= 25; $index++) {
            Category::create(['name' => 'Prayer theme '.$index]);
        }
        $response = $this->actingAs($this->admin)->get(route('admin.list-categories', ['category_name' => 'Prayer theme']));
        $response->assertOk()->assertViewHas('categories', fn ($records) => $records->total() === 25 && $records->count() === 20);
        $response->assertSee('category_name=Prayer%20theme', false)->assertSee('page=2', false);
        $this->get(route('admin.list-categories', ['category_name' => 'Prayer theme', 'page' => 2]))->assertOk()->assertViewHas('categories', fn ($records) => $records->count() === 5);
        $this->get(route('admin.list-categories', ['category_name' => 'Missing']))->assertOk()->assertSee('No matching records');
    }

    public function test_password_update_requires_current_password_and_confirmation(): void
    {
        $this->actingAs($this->admin);
        $url = route('admin.change-password.update');
        $this->put($url, ['current_password' => 'wrong', 'password' => 'newpassword123', 'password_confirmation' => 'newpassword123'])->assertSessionHasErrors('current_password');
        $this->put($url, ['current_password' => 'password123', 'password' => 'newpassword123', 'password_confirmation' => 'mismatch'])->assertSessionHasErrors('password');
        $this->assertTrue(Hash::check('password123', $this->admin->fresh()->password));
        $this->put($url, ['current_password' => 'password123', 'password' => 'newpassword123', 'password_confirmation' => 'newpassword123'])->assertRedirect(route('admin.change-password'))->assertSessionHasNoErrors();
        $this->assertTrue(Hash::check('newpassword123', $this->admin->fresh()->password));
    }
}
