<?php

namespace App\Http\Controllers;

use App\Imports\BookImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelFormat;

class BookImportController extends Controller
{
    public function import(Request $request)
    {
        if (!$request->hasFile('importBook')) {
            return response()->json(['success' => false, 'message' => 'File tidak dikirim ke server.']);
        }

        $request->validate([
            'importBook' => 'required|mimes:csv,txt,xlsx|max:2048',
        ]);

        try {
            $file = $request->file('importBook');
    
            Excel::import(new BookImport, $file);
    
            return response()->json(['success' => true, 'message' => 'Data berhasil diimport!']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengimport data: ' . $e->getMessage()
            ], 500);
        }
    }
}
