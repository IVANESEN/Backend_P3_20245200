<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();


        if ($request->has('trashed') && $request->trashed == 'true') {
            $query->onlyTrashed();
        }

        if ($request->has('username')) {
            $query->where('username', 'like', '%' . $request->username . '%');
        }
        if ($request->has('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'lastname' => 'required|string',
            'username' => 'required|string|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'dui' => 'nullable|string|max:10',
            'phone_number' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'hiring_date' => 'nullable|date',
        ]);

        $validated['password'] = bcrypt('password123');
        $user = User::create($validated);

        return response()->json($user, 201);
    }

    public function show($id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['message' => 'Usuario no encontrado'], 404);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['message' => 'Usuario no encontrado'], 404);

        $isPut = $request->isMethod('put');
        $rules = [
            'name' => [$isPut ? 'required' : 'sometimes', 'string'],
            'lastname' => [$isPut ? 'required' : 'sometimes', 'string'],
            'username' => [$isPut ? 'required' : 'sometimes', 'string', Rule::unique('users')->ignore($user->id)],
            'email' => [$isPut ? 'required' : 'sometimes', 'email', Rule::unique('users')->ignore($user->id)],
            'dui' => ['nullable', 'string', 'max:10'],
            'phone_number' => ['nullable', 'string'],
            'birth_date' => ['nullable', 'date'],
            'hiring_date' => ['nullable', 'date'],
        ];

        $validated = $request->validate($rules);
        $user->update($validated);

        return response()->json($user);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['error' => 'Usuario no encontrado'], 404);

        $user->delete();

        return response()->json(['message' => 'El usuario ha sido eliminado correctamente.']);
    }


    public function restore($id)
    {

        $user = User::withTrashed()->find($id);

        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        if ($user->trashed()) {
            $user->restore();
            return response()->json(['message' => 'Usuario restaurado correctamente.']);
        }

        return response()->json(['message' => 'El usuario no estaba eliminado.']);
    }
}
