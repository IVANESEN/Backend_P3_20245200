<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Http\Resources\BookResource;
use Illuminate\Http\Request;

class BookController extends Controller
{
   
    public function index(Request $request)
    {
        
        $query = Book::query();

        
        if ($request->has('titulo')) {
            $query->where('title', 'like', '%' . $request->titulo . '%');
        }

        
        if ($request->has('isbn')) {
            $query->where('isbn', $request->isbn);
        }

        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

     
        return BookResource::collection($query->get());
    }
}