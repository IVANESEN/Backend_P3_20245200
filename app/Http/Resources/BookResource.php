<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'titulo' => $this->titulo,
            'descripcion' => $this->descripcion,
            'isbn' => $this->isbn,
            'copias_totales' => $this->copias_totales,
            'copias_disponibles' => $this->copias_disponibles,
            'estado' => (bool) $this->estado,
        ];
    }
}
