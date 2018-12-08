<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGenre;
use App\Http\Requests\UpdateGenre;
use App\Genre;

class GenreController extends Controller
{
    /**
     * Get all genres
     */
    public function index() 
    {
        $genres = Genre::all();

        return response()->json([
            'data' => compact('genres')
        ]);
    }

    /**
     * Get genre by id
     */
    public function show(Genre $genre)
    {
        return response()->json([
            'data' => compact('genre')
        ]);
    }

    /**
     * Store new genre
     */
    public function store(StoreGenre $request)
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
    public function update($id, UpdateGenre $request)
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
    public function destroy(Genre $genre)
    {
        $genre->delete();

        return response()->json([
            'message' => "Genre was successfully deleted."
        ]);
    }
}
