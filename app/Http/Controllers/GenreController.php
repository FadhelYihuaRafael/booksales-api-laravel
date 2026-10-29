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

    /**
     * Display the specified genre.
     */
    public function show($id)
    {
        $genre = Genre::find($id);

        if (!$genre) {
            return response()->json([
                'success' => false,
                'message' => 'Genre not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Get genre detail successfully',
            'data'    => $genre
        ], 200);
    }

    /**
     * Update the specified genre.
     */
    public function update(Request $request, $id)
    {
        $genre = Genre::find($id);

        if (!$genre) {
            return response()->json([
                'success' => false,
                'message' => 'Genre not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name'        => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors()
            ], 422);
        }

        $genre->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Genre updated successfully',
            'data'    => $genre
        ], 200);
    }

    /**
     * Remove the specified genre.
     */
    public function destroy($id)
    {
        $genre = Genre::find($id);

        if (!$genre) {
            return response()->json([
                'success' => false,
                'message' => 'Genre not found'
            ], 404);
        }

        try {
            $genre->delete();

            return response()->json([
                'success' => true,
                'message' => 'Genre deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete genre. It may be linked to existing books.'
            ], 409);
        }
    }
}
