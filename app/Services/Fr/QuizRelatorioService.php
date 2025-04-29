<?php
namespace App\Services\Fr;

use App\Models\FrQuizLogAtividade;
use App\Models\FrQuizPlacar;
use App\Models\FrQuizPerguntas;
use App\Models\FrQuizResposta;

class QuizRelatorioService {

    public function relatorio($tipo,$id){

        if($tipo=='g'){
            return $this->relatorioVisaoGeral($id);
        }elseif($tipo=='q'){
            //
        }else{
            return $this->relatorioAluno($id);
        }

    }
    private function relatorioAluno($id){
        return FrQuizPlacar::join('users','users.id','user_id')
                    ->where('quiz_id',$id)
                    ->orderBy('name')
                    ->orderBy('nome_completo')
                    ->selectRaw('users.name, users.nome_completo, fr_quiz_placar.*')
                    ->get();
    }

    public function naoFinalizados($id){
        return FrQuizLogAtividade::join('users','users.id','user_id')
            ->where('quiz_id',$id)
            ->orderBy('name')
            ->orderBy('nome_completo')
            ->groupBy('users.id')
            ->selectRaw('users.name, users.nome_completo')
            ->get();
    }

    private function relatorioVisaoGeral($id){
        $dados = $this->relatorioAluno($id);
        $perguntas = FrQuizPerguntas::where('quiz_id',$id)->orderBy('ordem')->get();
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

            $alunos[$d->user_id]['nome_completo']       = $d->nome_completo;
            $alunos[$d->user_id]['name']                = $d->name;
            $alunos[$d->user_id]['porcentagem_acerto']  = $d->porcentagem_acerto;
            $alunos[$d->user_id]['pontuacao']           = $d->pontuacao;
            $alunos[$d->user_id]['tempo']               = $d->tempo;
            $alunos[$d->user_id]['perguntas']           = $ordemPerguntas;
            $resposta = json_decode($d->quiz_respondido);
            foreach($resposta->perguntas as $p){
                $alunos[$d->user_id]['perguntas']['P'.$p->id]=[$p->log->eh_correta,gmdate('H:i:s',$p->log->tempo), $p->log->tempo, $p->log->resposta, $p->tipo];
                $totalPerguntas['P'.$p->id]+=$p->log->eh_correta;
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

    public function relatorioPergunta($request)
    {
        $pergunta = FrQuizPerguntas::find($request->input('p'));

        if($pergunta->tipo == 1 || $pergunta->tipo == 4){
            return $this->relatorioPerguntaTipo1($request);
        }
        elseif($pergunta->tipo == 3){
            return $this->relatorioPerguntaTipo3($request);
        }
        elseif($pergunta->tipo == 2){
            return $this->relatorioPerguntaTipo2($request);
        }
        return null;

    }

    private function relatorioPerguntaTipo1($request)
    {
        $totalizador['total'] = 0;
        $totalizador['tempo_total'] = 0;
        $totalizador['errado'] = 0;
        $totalizador['certo'] = 0;
        $totalizador['porcentagem_errado'] = 0;
        $totalizador['porcentagem_certo'] = 0;
        $totalizador['tempo_medio'] = 0;

        $resp = [];
        $respostas = FrQuizResposta::where('pergunta_id',$request->input('p'))->get();
        foreach($respostas as $r)
        {
            $resp[$r->id]['correta']    = $r->correta;
            $resp[$r->id]['marcado']    = 0;
            $resp[$r->id]['tempo']      = 0;
        }

        $dados = $this->relatorioVisaoGeral($request->input('q'));
        foreach($dados['lista'] as $d)
        {
            foreach($d['perguntas'] as $pergunta => $p)
            {
                if($pergunta == 'P'.$request->input('p')){

                    $totalizador['tipo'] = $p[4];
                    $totalizador['total']++;
                    $totalizador['tempo_total'] += $p[2];
                    if($p[0]==1){
                        $totalizador['certo']++;
                    }
                    else{
                        $totalizador['errado']++;
                    }
                    /// respostas
                    $resp[$p[3]]['marcado'] ++;
                    $resp[$p[3]]['tempo'] += $p[2];
                }
            }

        }

        if($totalizador['errado'] >0 && $totalizador['total']>0){
            $totalizador['porcentagem_errado']  = number_format(($totalizador['errado']*100)/$totalizador['total'],0,',','.');
        }
        if($totalizador['certo'] >0 && $totalizador['total']>0){
            $totalizador['porcentagem_certo']   = number_format(($totalizador['certo']*100)/$totalizador['total'],0,',','.');
        }
        if($totalizador['tempo_total'] >0 && $totalizador['total']>0) {
            $totalizador['tempo_medio'] = gmdate('H:i:s', (int)($totalizador['tempo_total'] / $totalizador['total']));
        }

        $totalizador['resposta'] = $resp;
        return $totalizador;
    }

    private function relatorioPerguntaTipo3($request)
    {
        $totalizador['total'] = 0;
        $totalizador['tempo_total'] = 0;
        $totalizador['errado'] = 0;
        $totalizador['certo'] = 0;
        $totalizador['porcentagem_errado'] = 0;
        $totalizador['porcentagem_certo'] = 0;
        $totalizador['tempo_medio'] = 0;

        $resp = [];
        $respostas = FrQuizResposta::where('pergunta_id',$request->input('p'))->get();
        $i=0;
        foreach($respostas as $r)
        {
            $resp[$i]['correta']    = 1;
            $resp[$i]['titulo']     = $r->titulo;
            $resp[$i]['marcado']    = 0;
            $resp[$i]['tempo']      = 0;
            $i++;
        }

        $dados = $this->relatorioVisaoGeral($request->input('q'));
        foreach($dados['lista'] as $d)
        {
            foreach($d['perguntas'] as $pergunta => $p)
            {
                if($pergunta == 'P'.$request->input('p')){
                    $totalizador['tipo'] = $p[4];
                    $totalizador['total']++;
                    $totalizador['tempo_total'] += $p[2];
                    if($p[0]==1){
                        $totalizador['certo']++;
                    }
                    else{
                        $totalizador['errado']++;
                    }
                    /// respostas
                    $temNoVetor=null;
                    for($i=0;$i<count($resp); $i++)
                    {
                        if( mb_strtolower($resp[$i]['titulo']) == mb_strtolower($p[3]))
                        {
                            $resp[$i]['titulo'] = $p[3];
                            $resp[$i]['marcado'] ++;
                            $resp[$i]['tempo'] += $p[2];
                            $temNoVetor = 1;
                        }
                    }
                    if($temNoVetor==null){
                        $resp[]=[
                            'titulo' => $p[3],
                            'correta' => 0,
                            'marcado' => 1,
                            'tempo' => $p[2],
                        ];
                    }
                }
            }

        }

        if($totalizador['errado'] >0 && $totalizador['total']>0){
            $totalizador['porcentagem_errado']  = number_format(($totalizador['errado']*100)/$totalizador['total'],0,',','.');
        }
        if($totalizador['certo'] >0 && $totalizador['total']>0){
            $totalizador['porcentagem_certo']   = number_format(($totalizador['certo']*100)/$totalizador['total'],0,',','.');
        }
        if($totalizador['tempo_total'] >0 && $totalizador['total']>0) {
            $totalizador['tempo_medio'] = gmdate('H:i:s', (int)($totalizador['tempo_total'] / $totalizador['total']));
        }

        $totalizador['resposta'] = $resp;
        return $totalizador;
    }

    private function relatorioPerguntaTipo2($request)
    {
        $totalizador['tipo'] = 2;
        $totalizador['total'] = 0;
        $totalizador['tempo_total'] = 0;
        $totalizador['errado'] = 0;
        $totalizador['certo'] = 0;
        $totalizador['porcentagem_errado'] = 0;
        $totalizador['porcentagem_certo'] = 0;
        $totalizador['tempo_medio'] = 0;

        $resp = [];
        $respostas = FrQuizResposta::where('pergunta_id',$request->input('p'))->orderBy('ordem_correta')->get();
        $fraseCorreta = '';
        $palavras = [];
        foreach($respostas as $r)
        {
            $palavras[$r->id] = $r->titulo;
            if($r->ordem_correta != null || $r->ordem_correta===0) {
                $fraseCorreta .= $r->titulo.' ' ;
            }
        }
        $resp[0]['correta']    = 1;
        $resp[0]['titulo']     = $fraseCorreta;
        $resp[0]['marcado']    = 0;
        $resp[0]['tempo']      = 0;

        $dados = $this->relatorioVisaoGeral($request->input('q'));
        foreach($dados['lista'] as $d)
        {
            foreach($d['perguntas'] as $pergunta => $p)
            {
                if($pergunta == 'P'.$request->input('p')){
                    $totalizador['total']++;
                    $totalizador['tempo_total'] += $p[2];
                    if($p[0]==1){
                        $totalizador['certo']++;
                    }
                    else{
                        $totalizador['errado']++;
                    }
                    /// respostas
                    $temNoVetor=null;
                    $vet = json_decode($p[3]);
                    $respostaUsuario='';
                    foreach($vet as $r){
                        $respostaUsuario .= $palavras[$r].' ';
                    }

                    for($i=0;$i<count($resp); $i++)
                    {
                        if( mb_strtolower($resp[$i]['titulo']) == mb_strtolower($respostaUsuario))
                        {
                            $resp[$i]['titulo'] = $respostaUsuario;
                            $resp[$i]['marcado'] ++;
                            $resp[$i]['tempo'] += $p[2];
                            $temNoVetor = 1;
                        }
                    }
                    if($temNoVetor==null){
                        $resp[]=[
                            'titulo' => $respostaUsuario,
                            'correta' => 0,
                            'marcado' => 1,
                            'tempo' => $p[2],
                        ];
                    }
                }
            }

        }

        if($totalizador['errado'] >0 && $totalizador['total']>0){
            $totalizador['porcentagem_errado']  = number_format(($totalizador['errado']*100)/$totalizador['total'],0,',','.');
        }
        if($totalizador['certo'] >0 && $totalizador['total']>0){
            $totalizador['porcentagem_certo']   = number_format(($totalizador['certo']*100)/$totalizador['total'],0,',','.');
        }
        if($totalizador['tempo_total'] >0 && $totalizador['total']>0) {
            $totalizador['tempo_medio'] = gmdate('H:i:s', (int)($totalizador['tempo_total'] / $totalizador['total']));
        }
        $totalizador['resposta'] = $resp;
        return $totalizador;
    }
}
