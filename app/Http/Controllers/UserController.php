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
/*

4. Configurar el Seeder (Carga Manual + Automática)
En database/seeders/DatabaseSeeder.php, debes integrar los 10 clásicos del CSV y los 90 del Factory.

PHP
public function run(): void
{
    // 1. Carga Manual (10 clásicos)
    $csvFile = database_path('data/books_classics.csv');
    if (file_exists($csvFile)) {
        $handle = fopen($csvFile, 'r');
        fgetcsv($handle); // Saltar encabezado
        while (($data = fgetcsv($handle)) !== FALSE) {
            \App\Models\Book::create([
                'title' => $data[0],
                'description' => $data[1],
                'isbn' => $data[2],
                'total_copies' => (int)$data[3],
                'available_copies' => (int)$data[4],
                'status' => strtolower($data[5]) === 'disponible',
            ]);
        }
        fclose($handle);
    }

    // 2. Carga Automática (90 libros restantes) [cite: 24]
    \App\Models\Book::factory(90)->create();
}
5. Definir Relaciones Eloquent (Para el Punto Extra)
En el modelo app/Models/Book.php, añade la relación con préstamos:
+1

PHP
public function loans() {
    return $this->hasMany(Loan::class);
}
Y en app/Models/Loan.php:

PHP
public function book() {
    return $this->belongsTo(Book::class);
}

Siguiente paso recomendado: Una vez que termines esto, corre php artisan migrate --seed y confirma que la tabla tenga exactamente 100 registros. ¿Quieres que te ayude a verificar el código del Seeder para leer el CSV?
