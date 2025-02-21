<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public static function index()
    {
        $permissions = Permission::all();

        return view('admin/role', compact('permissions'));
    }

    public static function getRoles()
    {
        $roles = Role::all();

        return DataTables::of($roles)
            ->make(true);
    }

    public static function store(Request $request)
    {
        $validateData = $request->validate([
            'roleName' => 'required|string|max:255',
        ]);

        $role = Role::create([
            'name' => $validateData['roleName'],
        ]);

        return response()->json([
            'status' => '201',
            'message' => 'Buku berhasil ditambahkan!',
            'data' => $role
        ]);
    }

    public function edit(Request $request)
    {
        $role = Role::findOrFail($request->id);
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return response()->json([
            'data' => $role,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions,
        ]);
    }



    public function update(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'roleId' => 'required|exists:roles,id',
            'nameUpdate' => 'required|string|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id' // Pastikan permission yang dikirim valid
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'status' => '400',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 400);
        }

        // Cari role berdasarkan ID
        $role = Role::find($request->roleId);
        if (!$role) {
            return response()->json([
                'status' => '404',
                'message' => 'Role tidak ditemukan'
            ], 404);
        }

        // Update nama role
        $role->name = $request->nameUpdate;
        $role->save();

        // Sync permissions
        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        } else {
            $role->permissions()->detach(); // Jika tidak ada permissions, hapus semua permissions yang terkait
        }

        // Response sukses
        return response()->json([
            'status' => '200',
            'message' => 'Role berhasil diupdate',
            'data' => $role
        ]);
    }

    public static function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json([
            'success' => true,
            'message' => 'kategori berhasil dihapus!'
        ]);
    }
}
