<?php

namespace App\Http\Resources\Api\Agenda;

use Illuminate\Http\Resources\Json\JsonResource;

class CanaisAtendimentoResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
       $vet = [
           'id'         => $this->id,
           'nome'       => $this->nome,
           'cargo'      => $this->cargo,
           'email'      => $this->email,
           'telefone'   => $this->telefone,
           'imagem'     => $this->link_imagem,
           'escola'     => $this->escola,
           'telefone_eh_zap' => (boolean)$this->telefone_eh_zap,
           'publicado_em'   => $this->updated_at->format('d/m/Y H:i:s'),

       ] ;

       return $vet;
    }
}
