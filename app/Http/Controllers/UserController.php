<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public static function index()
    {
        $roles = Role::all();

        return view('admin/user', compact('roles'));
    }

    public static function getUsers()
    {
        $users = User::with('roles')->get();

        return DataTables::of($users)
            ->addColumn('role', function ($user) {
                return $user->roles->isNotEmpty()
                    ? implode(', ', $user->roles->pluck('name')->toArray())
                    : 'Tidak Ada Role';
            })
            ->make(true);
    }

    public static function store(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|string|min:8',
            'role' => 'required|integer|exists:roles,id'
        ]);

        $user = User::create([
            'name' => $validateData['name'],
            'email' => $validateData['email'],
            'password' => Hash::make($validateData['password'])
        ]);

        $role = Role::find($validateData['role']);

        if ($role) {
            $user->assignRole($role->name);
        }

        return response()->json([
            'status' => '201',
            'message' => 'Buku berhasil ditambahkan!',
            'data' => $user
        ]);
    }

    public static function edit(Request $request)
    {
        $user = User::with('roles')->where('id', $request->id)->first();

        if ($user) {
            return response()->json([
                'status' => '200',
                'data' => $user
            ]);
        }
    }

    public static function update(Request $request)
    {
        $validateData = $request->validate([
            'nameUpdate' => 'required|string|max:255',
            'emailUpdate' => 'required|email',
            'passwordUpdate' => 'nullable|string|min:8',
            'roleUpdate' => 'required|integer|exists:roles,id'
        ]);

        $user = User::findOrFail($request->userId);

        $user->name = $validateData['nameUpdate'];
        $user->email = $validateData['emailUpdate'];

        // Validasi password
        if (!empty($validateData['passwordUpdate'])) {
            $user->password = Hash::make($validateData['passwordUpdate']);
        }

        $user->save();

        $role = Role::find($validateData['roleUpdate']);
        if ($role) {
            $user->syncRoles([$role->name]);
        }

        return response()->json([
            'status' => '200',
            'message' => 'publisher berhasil diupdate',
        ]);
    }

    public static function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'kategori berhasil dihapus!'
        ]);
    }
}
