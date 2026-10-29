<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GenreController extends Controller
{
    /**
     * Read all genres.
     */
    public function index()
    {
        $genres = Genre::all();

        return response()->json([
            'success' => true,
            'message' => 'Get all genres successfully',
            'data'    => $genres
        ], 200);
    }

    /**
     * Create a new genre.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors()
            ], 422);
        }

        $genre = Genre::create($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Genre created successfully',
            'data'    => $genre
        ], 201);
    }
}
