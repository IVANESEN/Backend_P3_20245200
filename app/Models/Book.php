<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'titulo',
        'descripcion',
        'isbn',
        'copias_totales',
        'copias_disponibles',
        'estado',
    ];

    protected $casts = [
        'estado' => 'boolean',
        'copias_totales' => 'integer',
        'copias_disponibles' => 'integer',
    ];

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
