<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BookApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::get();

        return response()->json([
            'success' => true,
            'data' => $books
        ], 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Melakukan validasi data
        $validated = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'category_id' => 'required',
            'user_id' => 'required',
            'publisher_id' => 'required',
            'cover_book' => 'nullable|url'
        ]);

        // Jika validasi gagal
        if ($validated->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validated->errors()
            ], 400);
        }

        $book = Book::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil ditambahkan!',
            'book' => $book
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $book = Book::find($id);

        return response()->json([
            'success' => true,
            'data' => $book
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'category_id' => 'required',
            'user_id' => 'required',
            'publisher_id' => 'required',
            'cover_book' => 'nullable|url'
        ]);

        // Jika validasi gagal
        if ($validated->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validated->errors()
            ], 400);
        }

        $book = Book::find($id);
        $book->title = $request->title;
        $book->category_id = $request->category_id;
        $book->user_id = $request->user_id;
        $book->publisher_id = $request->publisher_id;
        $book->cover_book = $request->cover_book;

        $book->save();

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil diperbarui!',
            'book' => $book
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $book = Book::find($id);
        $book->delete();

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil dihapus!',
        ], 200);
    }

    public function getByCategory()
    {
        $books = Book::with('category')->select('category_id', DB::raw('count(*) as count'))
            ->groupBy('category_id')
            ->get()
            ->map(function($book) {
                // Menambahkan nama kategori ke setiap item hasil query
                $book->category_name = $book->category->name; 
                return $book;
            });

        // Kembalikan response JSON
        return response()->json($books);
    }

    public function getByPublisher()
    {
        $books = Book::with('publisher')->select('publisher_id', DB::raw('count(*) as count'))
            ->groupBy('publisher_id')
            ->get()
            ->map(function($book) {
                // Menambahkan nama kategori ke setiap item hasil query
                $book->publisher_name = $book->publisher->name; 
                return $book;
            });

        // Kembalikan response JSON
        return response()->json($books);
    }
}
