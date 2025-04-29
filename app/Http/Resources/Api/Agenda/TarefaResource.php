<?php

namespace App\Http\Resources\Api\Agenda;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TarefaResource extends JsonResource
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
            'data' => $this->data_entrega->format('Y-m-d'),
            'tarefas' => $this->dadosTarefa($this->tarefaData),
        ];
    }

    private function dadosTarefa($tarefa){
        $retorno = [];
        $now = Carbon::now();
        foreach($tarefa as $t) {
            $publicado = new Carbon($t->updated_at);
            $dif = $publicado->diff($now)->days;
            if($dif>100){
                $dif = '100+';
            }
            $vet = [
                'id' => $t->id,
                'titulo' => $t->titulo,
                'descricao' => $t->descricao,
                'data_entrega' => $t->data_entrega->format('d/m/Y'),
                'disciplina' => $t->disciplina->titulo,
                'escola' => $t->escola->titulo,
                'nome_arquivo' => $t->nome_arquivo_original,
                'url_arquivo' => $t->link_arquivo,
                'publicado_em' => $t->updated_at->format('d/m/Y H:i:s'),
                'diferenca_publicado' => (string) $dif,
                'proprietario_nome' => $t->professor->nome,
                'proprietario_avatar' => $t->professor->avatar,
            ];
            $retorno[] = $vet;
        }
        return $retorno;
    }
}
