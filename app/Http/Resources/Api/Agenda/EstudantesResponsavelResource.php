<?php

namespace App\Http\Resources\Api\Agenda;

use Illuminate\Http\Resources\Json\JsonResource;

class EstudantesResponsavelResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
       return [
           'id'             => $this->id,
           'nome'           => $this->name,
           'nome_completo'  => $this->nome,
           'avatar'         => $this->avatar,
       ] ;
    }

}
