<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Author;
use App\Models\Book;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    private $admin;
    private $customer;
    private $book;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->customer = User::factory()->create(['role' => 'customer']);

        $author = Author::create([
            'name'  => 'Pramoedya Ananta Toer',
            'email' => 'pram@example.com',
            'bio'   => 'Sastrawan Indonesia'
        ]);

        $this->book = Book::create([
            'title'          => 'Bumi Manusia',
            'description'    => 'Kisah Minke',
            'published_year' => 1980,
            'author_id'      => $author->id,
        ]);
    }

    /**
     * Unauthenticated user cannot access any transaction endpoint.
     */
    public function test_unauthenticated_cannot_access_transactions(): void
    {
        $this->getJson('/api/transactions')->assertStatus(401);
        $this->postJson('/api/transactions', [])->assertStatus(401);
        $this->getJson('/api/transactions/1')->assertStatus(401);
        $this->putJson('/api/transactions/1', [])->assertStatus(401);
        $this->deleteJson('/api/transactions/1')->assertStatus(401);
    }

    /**
     * Customer can Create, Show, and Update transaction.
     * Response loads relationships (customer and book).
     */
    public function test_customer_can_create_show_update_transaction(): void
    {
        // 1. Create (Customer)
        $response = $this->actingAs($this->customer)->postJson('/api/transactions', [
            'book_id'      => $this->book->id,
            'quantity'     => 2,
            'total_amount' => 150000,
        ]);

        $response->assertStatus(201)
                 ->assertJsonPath('success', true)
                 ->assertJsonStructure([
                     'data' => [
                         'id',
                         'order_number',
                         'customer_id',
                         'book_id',
                         'quantity',
                         'total_amount',
                         'customer' => ['id', 'name', 'email'],
                         'book'     => ['id', 'title'],
                     ]
                 ]);

        $transactionId = $response->json('data.id');

        // 2. Show (Customer)
        $showResponse = $this->actingAs($this->customer)->getJson('/api/transactions/' . $transactionId);
        $showResponse->assertStatus(200)
                     ->assertJsonPath('data.id', $transactionId)
                     ->assertJsonPath('data.book.title', 'Bumi Manusia');

        // 3. Update (Customer)
        $updateResponse = $this->actingAs($this->customer)->putJson('/api/transactions/' . $transactionId, [
            'quantity'     => 3,
            'total_amount' => 225000,
            'status'       => 'paid',
        ]);
        $updateResponse->assertStatus(200)
                       ->assertJsonPath('data.quantity', 3)
                       ->assertJsonPath('data.status', 'paid');
    }

    /**
     * Customer cannot Read All or Destroy transactions.
     */
    public function test_customer_cannot_read_all_or_destroy_transaction(): void
    {
        $transaction = Transaction::create([
            'order_number' => 'TRX-TEST-001',
            'customer_id'  => $this->customer->id,
            'book_id'      => $this->book->id,
            'quantity'     => 1,
            'total_amount' => 75000,
            'status'       => 'pending',
        ]);

        // Customer cannot Read All (403 Forbidden)
        $this->actingAs($this->customer)->getJson('/api/transactions')->assertStatus(403);

        // Customer cannot Destroy (403 Forbidden)
        $this->actingAs($this->customer)->deleteJson('/api/transactions/' . $transaction->id)->assertStatus(403);
    }

    /**
     * Admin can Read All and Destroy transactions.
     */
    public function test_admin_can_read_all_and_destroy_transaction(): void
    {
        $transaction = Transaction::create([
            'order_number' => 'TRX-TEST-002',
            'customer_id'  => $this->customer->id,
            'book_id'      => $this->book->id,
            'quantity'     => 1,
            'total_amount' => 75000,
            'status'       => 'pending',
        ]);

        // Admin Read All
        $response = $this->actingAs($this->admin)->getJson('/api/transactions');
        $response->assertStatus(200)
                 ->assertJsonPath('success', true)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => [
                             'id',
                             'customer',
                             'book',
                         ]
                     ]
                 ]);

        // Admin Destroy
        $deleteResponse = $this->actingAs($this->admin)->deleteJson('/api/transactions/' . $transaction->id);
        $deleteResponse->assertStatus(200)
                       ->assertJsonPath('message', 'Transaction deleted successfully');

        $this->assertDatabaseMissing('transactions', ['id' => $transaction->id]);
    }

    /**
     * Admin cannot Create transaction (Customer only).
     */
    public function test_admin_cannot_create_transaction(): void
    {
        $response = $this->actingAs($this->admin)->postJson('/api/transactions', [
            'book_id'      => $this->book->id,
            'quantity'     => 1,
            'total_amount' => 75000,
        ]);

        $response->assertStatus(403);
    }
}

