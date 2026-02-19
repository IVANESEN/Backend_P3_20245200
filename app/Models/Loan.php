<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'nombre_solicitante',
        'book_id',
        'fecha_devolucion',
    ];

    protected $casts = [
        'fecha_devolucion' => 'datetime',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
