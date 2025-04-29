<?php

namespace App\Http\Resources\Api\Agenda;

use App\Models\FrAgendaDocumentosRecebidos;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentoResource extends JsonResource
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
           'enviar'         => $this->arquivo == '' ? true : false,
           'titulo'         => $this->titulo,
           'descricao'      => $this->descricao,
           'nome_arquivo'   => $this->nome_arquivo_original,
           'url_arquivo'    => $this->link_arquivo,
           'escola'         => $this->escola->titulo,
           'escola_id'      => $this->escola_id,
           'instituicao_id' => $this->instituicao_id,
           'turma_id'       => $this->turma_id,
           'aluno_id'       => $this->aluno_id,
           'aluno_nome'     => $this->aluno_nome_completo == '' ? $this->aluno_name : $this->aluno_nome_completo,
           'aluno_avatar'   => $this->avatar($this->avatar_social, $this->img_perfil),
           'publicado_em'   => $this->updated_at->format('d/m/Y H:i:s'),
           'proprietario_nome' => $this->usuario->nome,
           'proprietario_avatar' =>$this->usuario->avatar,
           'recebidos' => $this->getRecebidos(),
       ] ;

       return $vet;
    }

    private function getRecebidos()
    {

        $vet = [];
        if (auth()->user()->permissao=='R') {
            $recebidos = FrAgendaDocumentosRecebidos::where('aluno_id', $this->aluno_id)
                ->where('turma_id', $this->turma_id)
                ->where('responsavel_id', auth()->user()->id)
                ->where('documento_id', $this->id)
                ->get();
            foreach ($recebidos as $r) {
                $vet[] = $r->link_arquivo;
            }
            return $vet;
        }else{
            return [];
        }


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
}
