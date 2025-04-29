<?php

namespace App\Services\Api;

use App\Models\User;
use App\Services\Fr\UsuarioService;

class AgendaService
{
    public function listaPermissao(){
        $this->usuarioService = new UsuarioService();
        $dados = $this->usuarioService->listaPermissoes();
        $retorno = [];
        if(count($dados)>0) {
            if(isset($dados[0])) {
                foreach ($dados[0] as $d) {
                    if(isset($d->permissao) && $d->permissao == 'I'){
                        $escolas = isset($d->escola_id) ? $d->escola_id : [];
                        foreach($escolas as $e){
                            $d->escola_id = $e['id'];
                            $ret = $this->getVetPermissao($d);
                            $retorno[] = $ret;
                        }
                    }else{
                        $ret = $this->getVetPermissao($d);
                        $retorno[] = $ret;
                    }


                }
            }
            if(isset($dados[1])) {
                foreach ($dados[1] as $escola) {
                    foreach ($escola as $d) {
                        $retorno[] = $this->getVetPermissao($d);
                    }
                }
            }

        }else{
            $retorno[] = [
                'instituicao_id'        => isset(auth('api')->user()->instituicao_id) ? auth('api')->user()->instituicao_id : null,
                'instituicao_titulo'    => isset(auth('api')->user()->instituicao->titulo) ? auth('api')->user()->instituicao->titulo : null,
                'escola_id'             => isset(auth('api')->user()->escola_id) ? auth('api')->user()->escola_id : null,
                'escola_titulo'         => isset(auth('api')->user()->escola->titulo) ? auth('api')->user()->escola->titulo : null,
                'permissao'             => isset(auth('api')->user()->permissao) ? auth('api')->user()->permissao : null,
                'estilo_agenda_titulo'  => null,
                'estilo_agenda_cor'     => null,
                'estilo_agenda_imagem'  => null,
            ];
        }
        return $retorno;
    }

    private function getVetPermissao($d){
        $ret = [
            'id' => isset($d->id) ? $d->id : null,
            'instituicao_id' => isset($d->instituicao_id) ? $d->instituicao_id : null,
            'instituicao_titulo' => isset($d->instituicao->titulo) ? $d->instituicao->titulo : null,
            'escola_id' => isset($d->escola_id) ? $d->escola_id : null,
            'escola_titulo' => isset($d->escola->titulo) ? $d->escola->titulo : null,
            'permissao' => isset($d->permissao) ? $d->permissao : null,
            'estilo_agenda_titulo' => isset($d->instituicao->estiloAgenda->titulo_inicial) ? $d->instituicao->estiloAgenda->titulo_inicial : null,
            'estilo_agenda_cor' => isset($d->instituicao->estiloAgenda->cor_primaria) ? $d->instituicao->estiloAgenda->cor_primaria : null,
            'estilo_agenda_imagem' => isset($d->instituicao->estiloAgenda->link_imagem) ? $d->instituicao->estiloAgenda->link_imagem : null,
        ];

        if(isset($d->permissao) && $d->permissao == 'R'){
            $ret['alunos'] = $this->trataAlunos($d->vetAlunos);
        }
        return $ret;
    }

    private function trataAlunos($vetAlunos){
        $ret = [];
        foreach($vetAlunos  as $key => $value){
            $aluno = User::find($key);
            $ret[] = [
                'id' => $key,
                'nome' => $aluno->name,
                'nome_completo' => $aluno->nome_completo,
                'avatar' => $aluno->avatar,
            ];
        }
        return $ret;
    }

}
