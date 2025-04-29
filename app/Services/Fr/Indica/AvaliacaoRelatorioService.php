<?php
namespace App\Services\Fr\Indica;
use App\Models\FrAvaliacao;
use App\Models\FrAvaliacaoPlacar;
use DB;


class AvaliacaoRelatorioService {



    public function relatorio($tipo,$id,$perguntas){

        if($tipo=='g'){
            return $this->relatorioVisaoGeral($id,$perguntas);
        }elseif($tipo=='m'){
            return $this->relatorioAlunoParaCorrigir($id,$perguntas);
        }else{
            return $this->relatorioAluno($id);
        }
    }

    private function relatorioAluno($id){
        return FrAvaliacaoPlacar::join('users','users.id','user_id')
            ->where('avaliacao_id',$id)
            ->orderBy('name')
            ->orderBy('nome_completo')
            ->selectRaw('users.name, users.nome_completo, fr_avaliacao_placar.*')
            ->get();
    }

    private function relatorioVisaoGeral($id, $perguntas){
        $dados = $this->relatorioAluno($id);
        $ordemPerguntas = [];
        $alunos = [];

        foreach ($perguntas as $d) {
            $ordemPerguntas['P'.$d->id]='';
            $totalPerguntas['P'.$d->id]=0;
            $qtdPerguntas['P'.$d->id]=0;
            $totalizadorPerguntas['P'.$d->id]['valor']=0;
            $totalizadorPerguntas['P'.$d->id]['id']=$d->id;
            $totalizadorPerguntas['P'.$d->id]['tipo']=$d->tipo;
        }
        foreach ($dados as $d){
            $alunos[$d->user_id]['user_id']             = $d->user_id;
            $alunos[$d->user_id]['nome_completo']       = $d->nome_completo;
            $alunos[$d->user_id]['name']                = $d->name;
            $alunos[$d->user_id]['porcentagem_acerto']  = $d->porcentagem_acerto;
            $alunos[$d->user_id]['pontuacao']           = $d->peso_total_acerto;
            $alunos[$d->user_id]['tempo']               = $d->tempo_total;
            $alunos[$d->user_id]['perguntas']           = $ordemPerguntas;
            $resposta = unserialize($d->questoes);
            foreach($resposta as $p){
                $alunos[$d->user_id]['perguntas']['P'.$p->id]=[$p->eh_correto,gmdate('H:i:s',$p->tempo_ativo), gmdate('H:i:s',$p->tempo_inativo),$p->tipo,$p->id,$p->peso_avaliado,$p->peso];
                if($p->eh_correto == 1) {
                    $totalPerguntas['P' . $p->id] += $p->eh_correto;
                }
                $qtdPerguntas['P'.$p->id]++;
            }
        }
        foreach($ordemPerguntas as $key => $value)
        {
            $totalizadorPerguntas[$key]['valor'] = 0;
            if($totalPerguntas[$key]>0 && $qtdPerguntas[$key]>0)
            {
                $totalizadorPerguntas[$key]['valor'] = (100*$totalPerguntas[$key])/$qtdPerguntas[$key];
            }
        }
        return [
            'lista'         => $alunos,
            'totalizador'   => $totalizadorPerguntas,
        ];
    }

    private function relatorioAlunoParaCorrigir($id, $perguntas){
        $dados = $this->relatorioVisaoGeral($id, $perguntas);
        $ordem = $dados['totalizador'];
        foreach($ordem as $k =>$o){
            $ordem[$k] = [];
        }
        $dados = $dados['lista'];
        $retorno = [];
        foreach ($dados as $d)
        {
            $ordemRetorno = $ordem;
            $tem =0;
            foreach ($d['perguntas'] as $k => $p){
                if($p[3] == 'o' || $p[0]===0 || $p[0]===1 ){
                  unset($dados[$d['user_id']]['perguntas'][$k]);
                }else{
                    $tem = 1;
                    $ordemRetorno[$k] = $dados[$d['user_id']]['perguntas'][$k];
                }
            }
            if($tem == 1){
                $dados[$d['user_id']]['perguntas'] = $ordemRetorno;
                $retorno[]=$dados[$d['user_id']];
            }
        }
        return $retorno;
    }

    public function getQuestaoAlunoCorrecao($dados){
        $avaliacao = FrAvaliacao::where('fr_avaliacao.user_id',auth()->user()->id)
                                ->where('fr_avaliacao.escola_id',auth()->user()->escola_id)
                                ->where('aplicacao','o')
                                ->where('publicado',1)
                                ->find($dados['avaliacao_id']);
        if($avaliacao){
            $placar = FrAvaliacaoPlacar::where('user_id',$dados['user_id'])->where('avaliacao_id',$dados['avaliacao_id'])->first();
            $questoes = unserialize($placar->questoes);
            return $questoes[$dados['questao_id']];
        }
        return false;
    }

    public function getTileLine($idAvaliacao, $idAluno){
        $dados  =FrAvaliacaoPlacar::where('user_id',$idAluno)->where('avaliacao_id',$idAvaliacao)->first();
        if($dados && $dados->time_line != ''){
            return unserialize($dados->time_line);
        }
        else{
            return null;
        }
    }
}
