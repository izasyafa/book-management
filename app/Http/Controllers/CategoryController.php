<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    public static function index()
    {
        return view('admin/category');
    }

    public static function getCategories()
    {
        $categories = Category::select(['id', 'name']);

        return DataTables::of($categories)
            ->make(true);
    }

    public static function store(Request $request)
    {
        $validateData = $request->validate([
            'categoryName' => 'required|string|max:255',
        ]);

        $category = Category::create([
            'name' => $validateData['categoryName'],
        ]);

        return response()->json([
            'status' => '201',
            'message' => 'Buku berhasil ditambahkan!',
            'data' => $category
        ]);
    }

    public static function edit(Request $request)
    {
        $category = Category::where('id', $request->id)->first();

        if ($category) {
            return response()->json([
                'status' => '200',
                'data' => $category
            ]);
        }
    }

    public static function update(Request $request)
    {
        $validateData = $request->validate([
            'nameUpdate' => 'required|string|max:255',
        ]);

        $category = Category::findOrFail($request->categoryId);

        // Update field lainnya
        $category->name = $validateData['nameUpdate'];

        // Simpan perubahan
        $category->save();

        if ($category) {
            return response()->json([
                'status' => '200',
                'message' => 'category berhasil diupdate',
            ]);
        } else {
            return response()->json([
                'status' => '500',
                'message' => 'Internal server error',
            ]);
        }
    }

    public static function destroy($id) 
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'kategori berhasil dihapus!'
        ]);
    }
}
