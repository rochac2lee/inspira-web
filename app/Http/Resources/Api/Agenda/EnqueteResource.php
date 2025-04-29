<?php

namespace App\Http\Resources\Api\Agenda;

use App\Models\FrAgendaEnqueteRespostas;
use Illuminate\Http\Resources\Json\JsonResource;

class EnqueteResource extends JsonResource
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
           'id'                 => $this->id,
           'pergunta'           => $this->pergunta,
           'alternativa_1'      => $this->alternativa_1,
           'alternativa_2'      => $this->alternativa_2,
           'alternativa_3'      => $this->alternativa_3,
           'alternativa_4'      => $this->alternativa_4,
           'alternativa_5'      => $this->alternativa_5,
           'alternativa_6'      => $this->alternativa_6,
           'alternativa_7'      => $this->alternativa_7,
           'qtd_alternativa'    => $this->qtd_alternativa,
           'imagem'             => $this->link_imagem,
           'turma_id'       => $this->turma_id,
           'aluno_id'       => $this->aluno_id,
           'aluno_nome'     => $this->aluno_nome_completo=='' ? $this->aluno_name : $this->aluno_nome_completo,
           'aluno_avatar'   => $this->avatar($this->avatar_social, $this->img_perfil),
           'publicado_em'       => $this->updated_at->format('d/m/Y H:i:s'),
           'proprietario_nome'  => $this->usuario->nome,
           'proprietario_avatar' =>$this->usuario->avatar,
       ] ;

        $resp = $this->resposta();

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

    private function resposta(){
        $resposta = FrAgendaEnqueteRespostas::where('turma_id', $this->turma_id)
            ->where('aluno_id', $this->aluno_id)
            ->where('enquete_id', $this->id)
            ->with('responsavel')
            ->first();
        $retorno =  [
            'resposta' => null,
            'respondido_por_nome' => null,
            'respondido_por_avatar' => null,
        ];

        if($resposta){
            $retorno =  [
                'resposta' => $resposta->resposta,
                'respondido_por_nome' => $resposta->responsavel->nome,
                'respondido_por_avatar' => $resposta->responsavel->avatar,
            ];
        }
        return $retorno;
    }
}
