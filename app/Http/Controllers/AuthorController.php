<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthorController extends Controller
{
    /**
     * Read all authors.
     */
    public function index()
    {
        $authors = Author::all();

        return response()->json([
            'success' => true,
            'message' => 'Get all authors successfully',
            'data'    => $authors
        ], 200);
    }

    /**
     * Create a new author.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'photo' => 'nullable|string|max:255',
            'bio'   => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors()
            ], 422);
        }

        $author = Author::create($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Author created successfully',
            'data'    => $author
        ], 201);
    }

    /**
     * Display the specified author.
     */
    public function show($id)
    {
        $author = Author::find($id);

        if (!$author) {
            return response()->json([
                'success' => false,
                'message' => 'Author not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Get author detail successfully',
            'data'    => $author
        ], 200);
    }

    /**
     * Update the specified author.
     */
    public function update(Request $request, $id)
    {
        $author = Author::find($id);

        if (!$author) {
            return response()->json([
                'success' => false,
                'message' => 'Author not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name'  => 'sometimes|required|string|max:255',
            'email' => 'nullable|email|max:255',
            'photo' => 'nullable|string|max:255',
            'bio'   => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors()
            ], 422);
        }

        $author->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Author updated successfully',
            'data'    => $author
        ], 200);
    }

    /**
     * Remove the specified author.
     */
    public function destroy($id)
    {
        $author = Author::find($id);

        if (!$author) {
            return response()->json([
                'success' => false,
                'message' => 'Author not found'
            ], 404);
        }

        try {
            $author->delete();

            return response()->json([
                'success' => true,
                'message' => 'Author deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete author. It may be linked to existing books.'
            ], 409);
        }
    }
}
