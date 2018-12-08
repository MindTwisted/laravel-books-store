<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAuthor;
use App\Http\Requests\UpdateAuthor;
use App\Author;

class AuthorController extends Controller
{
    /**
     * Get all authors
     */
    public function index() 
    {
        $authors = Author::all();

        return response()->json([
            'data' => compact('authors')
        ]);
    }

    /**
     * Get author by id
     */
    public function show(Author $author)
    {
        return response()->json([
            'data' => compact('author')
        ]);
    }

    /**
     * Store new author
     */
    public function store(StoreAuthor $request)
    {
        $author = Author::create([
            'name' => $request->get('name')
        ]);

        return response()->json([
            'message' => "Author was successfully added.",
            'data' => compact('author')
        ]);
    }

    /**
     * Update author
     */
    public function update($id, UpdateAuthor $request)
    {
        $author = Author::findOrFail($id);
        $author->update([
            'name' => $request->get('name')
        ]);

        return response()->json([
            'message' => "Author was successfully updated.",
            'data' => compact('author')
        ]);
    }

    /**
     * Delete author
     */
    public function destroy(Author $author)
    {
        $author->delete();

        return response()->json([
            'message' => "Author was successfully deleted."
        ]);
    }
}
