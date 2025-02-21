<?php

namespace App\Http\Controllers;

use App\Models\Publisher;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PublisherController extends Controller
{
    public static function index()
    {
        return view('admin/publisher');
    }

    public static function getPublishers()
    {
        $publishers = Publisher::select(['id', 'name']);

        return DataTables::of($publishers)
            ->make(true);
    }

    public static function store(Request $request)
    {
        $validateData = $request->validate([
            'publisherName' => 'required|string|max:255',
        ]);

        $publisher = Publisher::create([
            'name' => $validateData['publisherName'],
        ]);

        return response()->json([
            'status' => '201',
            'message' => 'Buku berhasil ditambahkan!',
            'data' => $publisher
        ]);
    }

    public static function edit(Request $request)
    {
        $publisher = Publisher::where('id', $request->id)->first();

        if ($publisher) {
            return response()->json([
                'status' => '200',
                'data' => $publisher
            ]);
        }
    }

    public static function update(Request $request)
    {
        $validateData = $request->validate([
            'nameUpdate' => 'required|string|max:255',
        ]);

        $publisher = Publisher::findOrFail($request->publisherId);

        // Update field lainnya
        $publisher->name = $validateData['nameUpdate'];

        // Simpan perubahan
        $publisher->save();

        if ($publisher) {
            return response()->json([
                'status' => '200',
                'message' => 'publisher berhasil diupdate',
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
        $publisher = Publisher::findOrFail($id);
        $publisher->delete();

        return response()->json([
            'success' => true,
            'message' => 'kategori berhasil dihapus!'
        ]);
    }
}
