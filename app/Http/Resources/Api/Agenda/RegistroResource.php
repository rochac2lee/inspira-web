<?php

namespace App\Http\Resources\Api\Agenda;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class RegistroResource extends JsonResource
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
            'data' => $this->data,
            'registros' => $this->dadosRegistro($this->registroData),
        ];
    }

    private function dadosRegistro($registro){
        $retorno = [];

        foreach($registro as $t) {
            $publicado = new Carbon($t->cadastrado_em);
            $vet = [
                'id'        => $t->registro_id,
                'titulo'    => $t->titulo,
                'imagem'    => $this->linkImage($t->imagem, $t->instituicao_id),
                'texto'     => $t->texto,
                'marcado'   => (boolean)$t->marcado,
                'hora'      => $publicado->format('H:i'),

            ];
            $retorno[] = $vet;
        }
        return $retorno;
    }

    private function linkImage($image, $instituicaoId)
    {
        if($image != ''){
            return config('app.cdn').'/storage/agenda/registro/rotinas/'.$instituicaoId.'/'.$image;
        }else{
            return null;
        }
    }
}
