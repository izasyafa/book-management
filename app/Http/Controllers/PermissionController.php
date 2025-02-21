<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    public static function index()
    {
        return view('admin/permission');
    }

    public static function getPermissions()
    {
        $permission = Permission::select(['id', 'name']);

        return DataTables::of($permission)
            ->make(true);
    }

    public static function store(Request $request)
    {
        $validateData = $request->validate([
            'permissionName' => 'required|string|max:255',
        ]);

        $permission = Permission::create([
            'name' => $validateData['permissionName'],
        ]);

        return response()->json([
            'status' => '201',
            'message' => 'Permission berhasil ditambahkan!',
            'data' => $permission
        ]);
    }

    public static function edit(Request $request)
    {
        $permission = Permission::where('id', $request->id)->first();

        if ($permission) {
            return response()->json([
                'status' => '200',
                'data' => $permission
            ]);
        }
    }

    public static function update(Request $request)
    {
        $validateData = $request->validate([
            'nameUpdate' => 'required|string|max:255',
        ]);

        $permission = Permission::findOrFail($request->permissionId);

        // Update field lainnya
        $permission->name = $validateData['nameUpdate'];

        // Simpan perubahan
        $permission->save();

        if ($permission) {
            return response()->json([
                'status' => '200',
                'message' => 'permission berhasil diupdate',
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
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return response()->json([
            'success' => true,
            'message' => 'kategori berhasil dihapus!'
        ]);
    }
}
