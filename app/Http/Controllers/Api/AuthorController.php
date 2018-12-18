<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAuthor;
use App\Http\Requests\UpdateAuthor;
use App\Author;

class AuthorController extends Controller
{
    /**
     * Get all authors
     */
    public function index(Request $request): JsonResponse
    {
        $offset = $request->input('offset', 0);

        $authors = Author::offset($offset)->limit(50)->get();

        return response()->json([
            'data' => compact('authors')
        ]);
    }

    /**
     * Get author by id
     */
    public function show(Author $author): JsonResponse
    {
        return response()->json([
            'data' => compact('author')
        ]);
    }

    /**
     * Get books by author
     */
    public function showBooks(Author $author): JsonResponse
    {
        $books = $author->books()->get();

        return response()->json([
            'data' => compact('books')
        ]);
    }

    /**
     * Store new author
     */
    public function store(StoreAuthor $request): JsonResponse
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
    public function update($id, UpdateAuthor $request): JsonResponse
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
    public function destroy(Author $author): JsonResponse
    {
        $author->delete();

        return response()->json([
            'message' => "Author was successfully deleted."
        ]);
    }
}
