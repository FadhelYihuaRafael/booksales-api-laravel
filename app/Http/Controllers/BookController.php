<?php

namespace App\Http\Controllers;

use App\Models\Book;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::all();

        return response()->json([
            'success' => true,
            'message' => 'Get all books successfully',
            'data'    => $books
        ], 200);
    }
}