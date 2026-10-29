<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $table = 'authors';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'email',
        'photo',
        'bio',
    ];

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
