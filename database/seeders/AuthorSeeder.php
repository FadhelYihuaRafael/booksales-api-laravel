<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Author;

class AuthorSeeder extends Seeder
{
    public function run(): void
    {
        Author::create([
            'name' => 'Adi Pratama',
            'email' => 'adi@example.com',
            'bio' => 'Penulis buku fiksi dan petualangan.'
        ]);

        Author::create([
            'name' => 'Bunga Lestari',
            'email' => 'bunga@example.com',
            'bio' => 'Penulis cerita romantis.'
        ]);

        Author::create([
            'name' => 'Citra Maharani',
            'email' => 'citra@example.com',
            'bio' => 'Penulis cerita fantasi.'
        ]);

        Author::create([
            'name' => 'Dimas Saputra',
            'email' => 'dimas@example.com',
            'bio' => 'Penulis buku pendidikan.'
        ]);

        Author::create([
            'name' => 'Eka Putri',
            'email' => 'eka@example.com',
            'bio' => 'Penulis cerita kehidupan.'
        ]);
    }
}