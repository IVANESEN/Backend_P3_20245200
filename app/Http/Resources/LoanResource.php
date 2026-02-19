<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoanResource extends JsonResource
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
            'nombre_solicitante' => $this->nombre_solicitante,
            'fecha_prestamo' => $this->created_at,
            'fecha_devolucion' => $this->fecha_devolucion,
            'libro' => new BookResource($this->whenLoaded('book')),
            'status' => $this->fecha_devolucion ? 'Devuelto' : 'Activo',
        ];
    }
}
