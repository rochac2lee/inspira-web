<?php

namespace App\Http\Resources\Api\Agenda;

use Illuminate\Http\Resources\Json\JsonResource;

class FamiliaResource extends JsonResource
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
           'titulo'     => $this->titulo,
           'descricao'  => $this->descricao,
           'video'      => $this->link_video_embed,
           'publicado_em'=> $this->updated_at->format('d/m/Y H:i:s'),
           'proprietario_nome' => $this->usuario->nome,
           'proprietario_avatar' =>$this->usuario->avatar,
       ] ;

       $img = [];
       foreach($this->imagens as $i){
           $i->setUserId($this->usuario->id);
           $i->setComunicadoId($this->id);
            $img[] = $i->link;
       }
       $vet['imagem'] = $img;

       return $vet;
    }

}
