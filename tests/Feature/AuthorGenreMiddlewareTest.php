<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Author;
use App\Models\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthorGenreMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test public can access Read All and Show for Genres.
     */
    public function test_public_can_access_read_all_and_show_genres(): void
    {
        $genre = Genre::create([
            'name' => 'Fiction',
            'description' => 'Fiction books'
        ]);

        // Read all without authentication
        $response = $this->getJson('/api/genres');
        $response->assertStatus(200);

        // Show detail without authentication
        $response = $this->getJson('/api/genres/' . $genre->id);
        $response->assertStatus(200);
    }

    /**
     * Test public can access Read All and Show for Authors.
     */
    public function test_public_can_access_read_all_and_show_authors(): void
    {
        $author = Author::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'bio' => 'A famous writer'
        ]);

        // Read all without authentication
        $response = $this->getJson('/api/authors');
        $response->assertStatus(200);

        // Show detail without authentication
        $response = $this->getJson('/api/authors/' . $author->id);
        $response->assertStatus(200);
    }

    /**
     * Test unauthenticated users cannot Create, Update, Destroy.
     */
    public function test_unauthenticated_user_cannot_modify_genres_or_authors(): void
    {
        // Create Genre without token
        $response = $this->postJson('/api/genres', ['name' => 'Novel']);
        $response->assertStatus(401);

        // Create Author without token
        $response = $this->postJson('/api/authors', ['name' => 'Author A']);
        $response->assertStatus(401);
    }

    /**
     * Test regular user (non-admin) cannot Create, Update, Destroy (Forbidden 403).
     */
    public function test_regular_user_cannot_modify_genres_or_authors(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $genre = Genre::create(['name' => 'Drama']);
        $author = Author::create(['name' => 'Jane']);

        // Attempt Create as regular user
        $response = $this->actingAs($user)->postJson('/api/genres', ['name' => 'Action']);
        $response->assertStatus(403);

        // Attempt Update as regular user
        $response = $this->actingAs($user)->putJson('/api/genres/' . $genre->id, ['name' => 'Action Updated']);
        $response->assertStatus(403);

        // Attempt Delete as regular user
        $response = $this->actingAs($user)->deleteJson('/api/genres/' . $genre->id);
        $response->assertStatus(403);

        // Attempt Create Author as regular user
        $response = $this->actingAs($user)->postJson('/api/authors', ['name' => 'New Author']);
        $response->assertStatus(403);
    }

    /**
     * Test admin can Create, Update, Destroy.
     */
    public function test_admin_can_modify_genres_and_authors(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Create Genre as admin
        $response = $this->actingAs($admin)->postJson('/api/genres', [
            'name' => 'Sci-Fi',
            'description' => 'Science Fiction'
        ]);
        $response->assertStatus(201);
        $genreId = $response->json('data.id');

        // Update Genre as admin
        $response = $this->actingAs($admin)->putJson('/api/genres/' . $genreId, [
            'name' => 'Sci-Fi & Fantasy'
        ]);
        $response->assertStatus(200);

        // Delete Genre as admin
        $response = $this->actingAs($admin)->deleteJson('/api/genres/' . $genreId);
        $response->assertStatus(200);

        // Create Author as admin
        $response = $this->actingAs($admin)->postJson('/api/authors', [
            'name' => 'Arthur Conan Doyle',
            'email' => 'arthur@example.com',
            'bio' => 'Creator of Sherlock Holmes'
        ]);
        $response->assertStatus(201);
        $authorId = $response->json('data.id');

        // Update Author as admin
        $response = $this->actingAs($admin)->putJson('/api/authors/' . $authorId, [
            'name' => 'Sir Arthur Conan Doyle'
        ]);
        $response->assertStatus(200);

        // Delete Author as admin
        $response = $this->actingAs($admin)->deleteJson('/api/authors/' . $authorId);
        $response->assertStatus(200);
    }
}

