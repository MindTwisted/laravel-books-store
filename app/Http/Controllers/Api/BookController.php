<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBook;
use App\Http\Requests\StoreBookAuthors;
use App\Http\Requests\StoreBookGenres;
use App\Http\Requests\StoreBookImage;
use App\Http\Requests\UpdateBook;
use App\Book;

class BookController extends Controller
{
    /**
     * Get all books
     */
    public function index(Request $request): JsonResponse
    {
        $authorId = $request->input('author_id');
        $genreId = $request->input('genre_id');
        $title = $request->input('title');

        $books = Book
            ::whereHas('authors', function($query) use ($authorId) {
                if ($authorId)
                {
                    $query->where('authors.id', $authorId);
                }
            })
            ->whereHas('genres', function($query) use ($genreId) {
                if ($genreId)
                {
                    $query->where('genres.id', $genreId);
                }
            })
            ->where('title', 'like', "%$title%")
            ->with(['authors', 'genres'])
            ->get();

        return response()->json([
            'data' => compact('books')
        ]);
    }

    /**
     * Get book by id
     */
    public function show($id): JsonResponse
    {
        $book = Book::with(['authors', 'genres'])->where('id', $id)->firstOrFail();

        return response()->json([
            'data' => compact('book')
        ]);
    }

    /**
     * Store new book
     */
    public function store(StoreBook $request): JsonResponse
    {
        $book = Book::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'discount' => $request->input('discount')
        ]);

        return response()->json([
            'message' => "Book was successfully added.",
            'data' => compact('book')
        ]);
    }

    /**
     * Create book-author relation
     */
    public function storeAuthors(Book $book, StoreBookAuthors $request): JsonResponse
    {
        $book->authors()->sync($request->input('authors'));

        return response()->json([
            'message' => "Authors for book #{$book->id} was successfully updated."
        ]);
    }

    /**
     * Create book-genre relation
     */
    public function storeGenres(Book $book, StoreBookGenres $request): JsonResponse
    {
        $book->genres()->sync($request->input('genres'));

        return response()->json([
            'message' => "Genres for book #{$book->id} was successfully updated."
        ]);
    }

    /**
     * Store image to book
     */
    public function storeImage(Book $book, StoreBookImage $request): JsonResponse
    {
        $path = $request->file('image')->store('public/images/books');

        $book->image_path = $path;
        $book->save();

        return response()->json([
            'message' => "Image for book #{$book->id} was successfully updated.",
            'data' => [
                'image_path' => $path
            ]
        ]);
    }

    /**
     * Update book
     */
    public function update(Book $book, UpdateBook $request): JsonResponse
    {
        $book->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'discount' => $request->input('discount')
        ]);

        return response()->json([
            'message' => "Book was successfully updated.",
            'data' => compact('book')
        ]);
    }

    /**
     * Delete book
     */
    public function destroy(Book $book): JsonResponse
    {
        $book->delete();

        return response()->json([
            'message' => "Book was successfully deleted."
        ]);
    }
}
