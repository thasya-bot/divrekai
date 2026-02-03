<?php

namespace App\Http\Controllers\AdminPIC;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $limit  = $request->limit ?? 10;

        $users = User::with('role')
            ->when($search, function ($q) use ($search) {
                $q->where('username', 'like', "%$search%");
            })
            ->paginate($limit);

        return view('admin_pic.users.index', compact('users'));
    
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin_pic.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required|min:6',
            'role_id'  => 'required'
        ]);

        $rolePimpinan = Role::where('username', 'pimpinan')->first();

        if ($rolePimpinan && $request->role_id == $rolePimpinan->id) {
            $exists = User::where('role_id', $rolePimpinan->id)->exists();

            if ($exists) {
                return back()
                    ->withErrors(['role_id' => 'Pimpinan hanya boleh satu orang'])
                    ->withInput();
            }
        }

        User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role_id'  => $request->role_id
        ]);

        return redirect()->route('admin.pic.users.index')
            ->with('success', 'Pengguna berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin_pic.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'nullable|min:6',
        ]);

        $data = [
            'username' => $request->username,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()
            ->route('admin.pic.users.index')
            ->with('success', 'Data pengguna berhasil diperbarui');
    }

    }