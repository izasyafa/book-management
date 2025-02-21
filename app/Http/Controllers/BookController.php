<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class BookController extends Controller
{
    public function index()
    {

        $categories = Category::all();
        $publishers = Publisher::all();
        $user = auth()->user();

        return view('admin/book', compact('categories', 'publishers', 'user'));
    }

    public function store(Request $request)
    {
        $validateData = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|integer|exists:category,id',
            'writer' => 'required|integer|exists:users,id',
            'publisher' => 'required|integer|exists:publisher,id',
            'coverBook' => 'required|image|mimes:jpg,jpeg,png|max:5120'
        ]);

        if ($request->hasFile('coverBook')) {
            $coverBook = $request->file('coverBook')->store('cover-book', 'public');
        }

        $book = Book::create([
            'title' => $validateData['title'],
            'category_id' => $validateData['category'],
            'user_id' => $validateData['writer'],
            'publisher_id' => $validateData['publisher'],
            'cover_book' => $coverBook,
        ]);

        $book->load(['category', 'publisher', 'user']);

        return response()->json([
            'status' => '201',
            'message' => 'Buku berhasil ditambahkan!',
            'book' => $book
        ]);
    }

    public function getBooks(Request $request)
    {
        if ($request->ajax()) {

            $books = Book::with(['category', 'user', 'publisher']);

            return DataTables::of($books)
                ->addColumn('cover_book', function ($book) {
                    if ($book->cover_book) {
                        $imageUrl = asset('storage/' . $book->cover_book);
                        return '<img src="' . $imageUrl . '" width="50" height="70" style="border-radius: 5px;">';
                    }
                    return 'No Image';
                })
                ->addColumn('category_name', function ($book) {
                    return $book->category ? $book->category->name : 'Tidak Ada Kategori';
                })
                ->addColumn('publisher_name', function ($book) {
                    return $book->publisher ? $book->publisher->name : 'Tidak Ada Penerbit';
                })
                ->addColumn('user_name', function ($book) {
                    return $book->user ? $book->user->name : 'Tidak Ada Penulis';
                })
                ->filter(function ($instence) use ($request) {
                    if (!empty($request->category)) {
                        $instence->where('category_id', $request->category);
                    }
                })
                ->rawColumns(['cover_book'])
                ->make(true);
        }
    }

    public function edit(Request $request)
    {
        $book = Book::with(['category', 'publisher', 'user'])->where('id', $request->id)->first();

        if ($book) {
            return response()->json([
                'status' => '200',
                'data' => $book
            ]);
        }
    }


    public function update(Request $request)
    {
        $validateData = $request->validate([
            'titleUpdate' => 'required|string|max:255',
            'categoryUpdate' => 'required|integer|exists:category,id',
            'publisherUpdate' => 'required|integer|exists:publisher,id',
            'coverBookUpdate' => 'nullable|image|mimes:jpg,jpeg,png|max:5120'
        ]);

        $book = Book::findOrFail($request->bookId);

        if ($request->hasFile('coverBookUpdate')) {
            if ($book->cover_book) {
                Storage::disk('public')->delete($book->cover_book);
            }

            $path = $request->file('coverBookUpdate')->store('cover-book', 'public');
            $book->cover_book = $path;
        }

        $book->title = $validateData['titleUpdate'];
        $book->category_id = $validateData['categoryUpdate'];
        $book->publisher_id = $validateData['publisherUpdate'];

        // Simpan perubahan
        $book->save();

        if ($book) {
            return response()->json([
                'status' => '200',
                'message' => 'Buku berhasil diupdate',
            ]);
        } else {
            return response()->json([
                'status' => '500',
                'message' => 'Internal server error',
            ]);
        }
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil dihapus!'
        ]);
    }

    public function tableWriter()
    {
        $writers = Book::with('user')->select('user_id', DB::raw('count(*) as count'))
            ->groupBy('user_id')
            ->orderByDesc('count')
            ->take(10)
            ->get();

        return view('dashboard', compact('writers'));
    }
}
