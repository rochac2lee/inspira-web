<?php

namespace App\Http\Resources\Api\Agenda;

use App\Models\FrAgendaAutorizacoesAutorizadas;
use Illuminate\Http\Resources\Json\JsonResource;

class AutorizacaoResource extends JsonResource
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
           'id'             => $this->id,
           'titulo'         => $this->titulo,
           'descricao'      => $this->descricao,
           'imagem'         => $this->link_imagem,
           'escola'         => $this->escola->titulo,
           'turma_id'       => $this->turma_id,
           'aluno_id'       => $this->aluno_id,
           'aluno_nome'     => $this->aluno_nome_completo=='' ? $this->aluno_name : $this->aluno_nome_completo,
           'aluno_avatar'   => $this->avatar($this->avatar_social, $this->img_perfil),
           'publicado_em'   => $this->updated_at->format('d/m/Y H:i:s'),
           'proprietario_nome' => $this->usuario->nome,
           'proprietario_avatar' =>$this->usuario->avatar,

       ] ;
        $resp = $this->autorizado();

       return array_merge($vet, $resp);
    }

    private function avatar($social, $perfil){
        if($social!=''){
            return $social;
        }
        elseif($perfil!=''){
            return config('app.cdn').'/storage/uploads/usuarios/perfil/'.$perfil;
        }else{
            return config('app.cdn').'/fr/imagens/avatar-user.png';
        }
    }

    private function autorizado(){
        $autorizado = FrAgendaAutorizacoesAutorizadas::where('turma_id', $this->turma_id)
            ->where('aluno_id', $this->aluno_id)
            ->where('autorizacao_id', $this->id)
            ->with('responsavel')
            ->first();
        $retorno =  [
            'autorizado' => null,
            'autorizado_em' => null,
            'autorizado_por_nome' => null,
            'autorizado_por_avatar' => null,
        ];

        if($autorizado){
            $retorno =  [
                'autorizado' => (boolean)$autorizado->autorizado,
                'autorizado_em' => $autorizado->updated_at->format('d/m/Y H:i:s'),
                'autorizado_por_nome' => $autorizado->responsavel->nome,
                'autorizado_por_avatar' => $autorizado->responsavel->avatar,
            ];
        }
        return $retorno;
    }

}
