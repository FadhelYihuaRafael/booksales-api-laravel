<?php

namespace App\Models;

class Genre
{
    private $genres = [
        [
            'id' => 1,
            'name' => 'Fiksi',
            'description' => 'Cerita berdasarkan imajinasi penulis.'
        ],
        [
            'id' => 2,
            'name' => 'Fantasi',
            'description' => 'Cerita yang mengandung unsur imajinasi dan keajaiban.'
        ],
        [
            'id' => 3,
            'name' => 'Misteri',
            'description' => 'Cerita yang berisi teka-teki atau rahasia.'
        ],
        [
            'id' => 4,
            'name' => 'Romansa',
            'description' => 'Cerita yang berfokus pada hubungan percintaan.'
        ],
        [
            'id' => 5,
            'name' => 'Sejarah',
            'description' => 'Cerita yang berkaitan dengan peristiwa masa lalu.'
        ],
    ];

    public function getGenres()
    {
        return $this->genres;
    }
}