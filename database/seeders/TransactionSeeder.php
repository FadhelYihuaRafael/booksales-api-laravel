<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Book;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customer = User::where('email', 'customer@example.com')->first();
        $book = Book::first();

        if ($customer && $book) {
            Transaction::firstOrCreate(
                ['order_number' => 'TRX-20261102-0001'],
                [
                    'customer_id'  => $customer->id,
                    'book_id'      => $book->id,
                    'quantity'     => 2,
                    'total_amount' => 175000,
                    'status'       => 'paid',
                ]
            );

            Transaction::firstOrCreate(
                ['order_number' => 'TRX-20261102-0002'],
                [
                    'customer_id'  => $customer->id,
                    'book_id'      => $book->id,
                    'quantity'     => 1,
                    'total_amount' => 85000,
                    'status'       => 'pending',
                ]
            );
        }
    }
}

