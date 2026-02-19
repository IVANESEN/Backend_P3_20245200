<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Loan;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\LoanResource;
use App\Http\Requests\StoreLoanRequest;

class LoanController extends Controller
{
    public function store(StoreLoanRequest $request)
    {
        $book = Book::findOrFail($request->book_id);

        if ($book->copias_disponibles <= 0) {
            return response()->json(['error' => 'No hay copias disponibles'], 422);
        }

        return DB::transaction(function () use ($request, $book) {
            $loan = Loan::create([
                'nombre_solicitante' => $request->nombre_solicitante,
                'book_id' => $book->id,
            ]);

            $book->decrement('copias_disponibles');
            
            if ($book->copias_disponibles == 0) {
                $book->update(['estado' => false]);
            }

            return response()->json(new LoanResource($loan), 201);
        });
    }

    public function returnBook($id)
    {
        $loan = Loan::findOrFail($id);

        if ($loan->fecha_devolucion) {
            return response()->json(['error' => 'El libro ya fue devuelto'], 422);
        }

        DB::transaction(function () use ($loan) {
            $loan->update(['fecha_devolucion' => now()]);
            
            $book = $loan->book;
            $book->increment('copias_disponibles');
            
            if (!$book->estado) {
                $book->update(['estado' => true]);
            }
        });

        return response()->json(['message' => 'Devolución exitosa'], 200);
    }

    // Extra: history with Eloquent relations
    public function history()
    {
        $loans = Loan::with('book')->get();
        return LoanResource::collection($loans);
    }
}
