<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Chapter;
use App\Models\Testament;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_sign_up_and_is_redirected_to_dashboard(): void
    {
        $response = $this->post('/admin/signup', [
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/admin/dashboard');

        $this->assertDatabaseHas('users', [
            'email' => 'admin@example.com',
            'is_admin' => true,
        ]);

        $this->assertAuthenticated();
    }

    public function test_admin_can_sign_in(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'password123',
            'is_admin' => true,
        ]);

        $response = $this->post('/admin/signin', [
            'email' => $admin->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($admin);
    }

    public function test_non_admin_cannot_sign_in_through_admin_portal(): void
    {
        User::factory()->create([
            'email' => 'member@example.com',
            'password' => 'password123',
            'is_admin' => false,
        ]);

        $response = $this->from('/admin/signin')->post('/admin/signin', [
            'email' => 'member@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/admin/signin');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_admin_cannot_create_the_same_chapter_number_twice_for_one_book(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $testament = Testament::create([
            'name' => 'Old Testament',
            'summary' => 'Old Testament summary',
        ]);

        $book = Book::create([
            'testament_id' => $testament->id,
            'name' => 'Genesis',
        ]);

        Chapter::create([
            'book_id' => $book->id,
            'book_name' => $book->name,
            'chapter_number' => 1,
        ]);

        $response = $this
            ->actingAs($admin)
            ->from('/admin/upload-chapter')
            ->post('/admin/upload-chapter', [
                'book_id' => $book->id,
                'chapter_number' => 1,
            ]);

        $response->assertRedirect('/admin/upload-chapter');
        $response->assertSessionHasErrors('chapter_number');
        $this->assertDatabaseCount('chapters', 1);
    }

    public function test_admin_cannot_update_a_chapter_into_a_duplicate_number_for_the_same_book(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $testament = Testament::create([
            'name' => 'New Testament',
            'summary' => 'New Testament summary',
        ]);

        $book = Book::create([
            'testament_id' => $testament->id,
            'name' => 'Matthew',
        ]);

        $chapterOne = Chapter::create([
            'book_id' => $book->id,
            'book_name' => $book->name,
            'chapter_number' => 1,
        ]);

        $chapterTwo = Chapter::create([
            'book_id' => $book->id,
            'book_name' => $book->name,
            'chapter_number' => 2,
        ]);

        $response = $this
            ->actingAs($admin)
            ->from("/admin/chapters/{$chapterTwo->id}/edit")
            ->put("/admin/upload-chapter/{$chapterTwo->id}", [
                'book_id' => $book->id,
                'chapter_number' => 1,
            ]);

        $response->assertRedirect("/admin/chapters/{$chapterTwo->id}/edit");
        $response->assertSessionHasErrors('chapter_number');

        $this->assertDatabaseHas('chapters', [
            'id' => $chapterOne->id,
            'chapter_number' => 1,
        ]);

        $this->assertDatabaseHas('chapters', [
            'id' => $chapterTwo->id,
            'chapter_number' => 2,
        ]);
    }
}
