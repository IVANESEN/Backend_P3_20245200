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
Una vez que hayas completado la estructura de la base de datos y las relaciones, el siguiente paso es sincronizar tu trabajo con tu compañero y preparar el terreno para los endpoints. Como eres el Integrante 1, tu prioridad ahora es la integridad de los datos y la configuración de las rutas base.

Aquí tienes los pasos finales de tu parte:

1. Verificación de la Base de Datos
Antes de subir tu código, asegúrate de que todo funciona exactamente como pide el examen:
+1

Ejecuta: php artisan migrate:fresh --seed.

Entra a tu gestor de base de datos (o usa php artisan tinker) y verifica:

Que existan exactamente 100 registros en la tabla books.
+2

Que los primeros 10 sean los clásicos del CSV (ej: "Don Quijote", "100 años de soledad").
+2

Que el campo status sea booleano.
+1

Que ningún libro tenga más available_copies que total_copies.

2. Definición de Rutas (Archivo routes/api.php)
Deja las rutas listas para que tu compañero (Integrante 2) solo tenga que programar la lógica en los controladores:
+3

PHP
use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;
use Illuminate\Support\Facades\Route;

// Endpoint para el catálogo (Integrante 2 lo completará)
Route::get('/books', [BookController::class, 'index']);

// Endpoint para realizar un préstamo
Route::post('/loans', [LoanController::class, 'store']);

// Endpoint para devoluciones (usando el loan_id)
Route::post('/returns/{loan_id}', [LoanController::class, 'returnBook']);

// Endpoint opcional para el historial (Punto Extra)
Route::get('/loans/history', [LoanController::class, 'history']);
3. Sincronización en GitHub
Para que tu compañero pueda empezar con los controladores, debes subir tus cambios:

Bash
git add .
git commit -m "Feat: Database structure, seeds, and API routes defined"
git push origin main
4. Apoyo en el Bloque B (Opcional pero recomendado)
Si terminas antes de tiempo, puedes ayudar al Integrante 2 creando los API Resources, que son obligatorios para el mapeo de datos:

Ejecuta: php artisan make:resource BookResource.

En app/Http/Resources/BookResource.php, define el retorno exacto solicitado:

PHP
public function toArray($request): array
{
    return [
        'titulo' => $this->title,
        'descripcion' => $this->description,
        'isbn' => $this->isbn,
        'copias_totales' => $this->total_copies,
        'copias_disponibles' => $this->available_copies,
        'estado' => (bool)$this->status,
    ];
}

¿Te gustaría que te ayude a redactar el FormRequest para que tu compañero tenga la validación de los préstamos lista? Recuerda que deben entregar el repositorio antes de las 9:15 AM.
+3
