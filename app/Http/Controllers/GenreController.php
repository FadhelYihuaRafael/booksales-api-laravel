<?php

namespace App\Http\Controllers;

use App\Models\Genre;

class GenreController extends Controller
{
    public function index()
    {
        $genreModel = new Genre();

        $genres = $genreModel->getGenres();

        return view('genres.index', [
            'genres' => $genres
        ]);
    }
}