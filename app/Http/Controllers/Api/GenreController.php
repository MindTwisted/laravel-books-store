<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGenre;
use App\Http\Requests\UpdateGenre;
use App\Genre;

class GenreController extends Controller
{
    /**
     * Get all genres
     */
    public function index(): JsonResponse
    {
        $genres = Genre::all();

        return response()->json([
            'data' => compact('genres')
        ]);
    }

    /**
     * Get genre by id
     */
    public function show(Genre $genre): JsonResponse
    {
        return response()->json([
            'data' => compact('genre')
        ]);
    }

    /**
     * Get books by genre
     */
    public function showBooks(Genre $genre): JsonResponse
    {
        $books = $genre->books()->get();

        return response()->json([
            'data' => compact('books')
        ]);
    }

    /**
     * Store new genre
     */
    public function store(StoreGenre $request): JsonResponse
    {
        $genre = Genre::create([
            'name' => $request->get('name')
        ]);

        return response()->json([
            'message' => "Genre was successfully added.",
            'data' => compact('genre')
        ]);
    }

    /**
     * Update genre
     */
    public function update($id, UpdateGenre $request): JsonResponse
    {
        $genre = Genre::findOrFail($id);
        $genre->update([
            'name' => $request->get('name')
        ]);

        return response()->json([
            'message' => "Genre was successfully updated.",
            'data' => compact('genre')
        ]);
    }

    /**
     * Delete genre
     */
    public function destroy(Genre $genre): JsonResponse
    {
        $genre->delete();

        return response()->json([
            'message' => "Genre was successfully deleted."
        ]);
    }
}
