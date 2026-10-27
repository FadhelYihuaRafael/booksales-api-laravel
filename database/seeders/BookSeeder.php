<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Author;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $author = Author::where('email', 'adi@example.com')
                        ->firstOrFail();

        Book::create([
            'title' => 'Jejak Senja',
            'description' => 'Kisah perjalanan mencari makna kehidupan.',
            'published_year' => 2021,
            'author_id' => $author->id,
        ]);

        $author = Author::where('email', 'bunga@example.com')
                        ->firstOrFail();

        Book::create([
            'title' => 'Langit di Ujung Kota',
            'description' => 'Cerita tentang persahabatan dan harapan.',
            'published_year' => 2022,
            'author_id' => $author->id,
        ]);

        $author = Author::where('email', 'citra@example.com')
                        ->firstOrFail();

        Book::create([
            'title' => 'Rahasia Hutan Biru',
            'description' => 'Petualangan di dunia penuh imajinasi.',
            'published_year' => 2023,
            'author_id' => $author->id,
        ]);

        $author = Author::where('email', 'dimas@example.com')
                        ->firstOrFail();

        Book::create([
            'title' => 'Belajar Sepanjang Waktu',
            'description' => 'Panduan sederhana untuk terus belajar.',
            'published_year' => 2024,
            'author_id' => $author->id,
        ]);

        $author = Author::where('email', 'eka@example.com')
                        ->firstOrFail();

        Book::create([
            'title' => 'Catatan Musim Hujan',
            'description' => 'Kumpulan cerita tentang kehidupan.',
            'published_year' => 2025,
            'author_id' => $author->id,
        ]);
    }
}