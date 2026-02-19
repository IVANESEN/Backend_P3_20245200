<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Http\Resources\BookResource;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $books = Book::query()
            ->when($request->titulo, fn($q) => $q->where('titulo', 'like', "%{$request->titulo}%"))
            ->when($request->isbn, fn($q) => $q->where('isbn', $request->isbn))
            ->when($request->has('status'), fn($q) => $q->where('estado', $request->status))
            ->get();

        return BookResource::collection($books);
    }
}
