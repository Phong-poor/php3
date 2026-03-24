<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    
    public function index()
    {
        $users = User::select('id', 'name', 'email', 'phone', 'role', 'status', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($users);
    }

    
    public function show($id)
    {
        $user = User::select('id', 'name', 'email', 'phone', 'role', 'status', 'created_at')
            ->findOrFail($id);

        return response()->json($user);
    }

    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone'    => 'nullable|string|max:20',
            'role'     => 'nullable|in:admin,support,user',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone'    => $validated['phone'] ?? null,
            'role'     => $validated['role'] ?? 'user',
            'status'   => 'active',
        ]);

        return response()->json([
            'message' => 'Tạo người dùng thành công',
            'user'    => $user->only(['id', 'name', 'email', 'phone', 'role', 'status', 'created_at']),
        ], 201);
    }

    
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'     => 'sometimes|required|string|max:255',
            'email'    => ['sometimes', 'required', 'email', Rule::unique('users', 'email')->ignore($id)],
            'phone'    => 'nullable|string|max:20',
            'role'     => 'nullable|in:admin,support,user',
            'status'   => 'nullable|in:active,locked',
            'password' => 'nullable|string|min:8',
        ]);

        if (isset($validated['name']))   $user->name   = $validated['name'];
        if (isset($validated['email']))  $user->email  = $validated['email'];
        if (isset($validated['phone']))  $user->phone  = $validated['phone'];
        if (isset($validated['role']))   $user->role   = $validated['role'];
        if (isset($validated['status'])) $user->status = $validated['status'];
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return response()->json([
            'message' => 'Cập nhật thành công',
            'user'    => $user->only(['id', 'name', 'email', 'phone', 'role', 'status', 'created_at']),
        ]);
    }

    /**
     * DELETE /api/users/{id}
     * Xóa user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'Xóa người dùng thành công']);
    }
}