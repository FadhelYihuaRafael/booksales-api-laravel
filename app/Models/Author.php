<?php

namespace App\Models;

class Author
{
    private $authors = [
        [
            'id' => 1,
            'name' => 'Andrea Hirata',
            'country' => 'Indonesia'
        ],
        [
            'id' => 2,
            'name' => 'Tere Liye',
            'country' => 'Indonesia'
        ],
        [
            'id' => 3,
            'name' => 'Dee Lestari',
            'country' => 'Indonesia'
        ],
        [
            'id' => 4,
            'name' => 'J.K. Rowling',
            'country' => 'Inggris'
        ],
        [
            'id' => 5,
            'name' => 'George Orwell',
            'country' => 'Inggris'
        ],
    ];

    public function getAuthors()
    {
        return $this->authors;
    }
}
