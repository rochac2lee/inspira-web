<?php
namespace App\Services\Fr\AvaliacaoEAD;
use App\Models\Altitude\TrilhasMatriculasUsuario;
use App\Models\Ead\AvaliacaoPlacarHistorico;
use App\Models\FrTurma;
use App\Models\Ead\Avaliacao;
use App\Models\Ead\AvaliacaoLogAtividade;
use App\Models\Ead\AvaliacaoLogGeral;
use App\Models\Ead\AvaliacaoPlacar;
use App\Models\Trilha;
use App\Models\User;
use App\Models\UserPermissao;
use App\Services\Fr\TrilhasService;
use Carbon\Carbon;
use DB;

class AvaliacaoService {

	public function minhasAvaliacoes($request)
	{
		$ret = Avaliacao::with(['usuario'])->withTrashed();

        if($request->input('titulo'))
        {
            $ret = $ret->where('titulo','like','%'.$request->input('titulo').'%');
        }

        $ret->orderBy('id','desc');
		return $ret->paginate(20);
	}


	public function addAvaliacao($dados)
	{
		DB::beginTransaction();
        try
        {
        	$dados['user_id'] = auth()->user()->id;
        	$dados['qtd_questao'] = count($dados['questao']);
			$avaliacao = new Avaliacao($dados);
	        $avaliacao->save();
	        $questoes = [];
	        $ordem = 1;
	        foreach($dados['questao'] as $q)
	        {

	        	$questao = [];
	        	$questao['ead_questao_id'] = $q;
	        	$questao['ordem'] = $ordem;
	        	$questoes[] = $questao;
	        	$ordem++;
	        }
            $this->adicionarQuestaoAvaliacao($avaliacao,$questoes);
            DB::commit();

	        return true;
	    }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
	}

    public function publicar($dados)
    {
        DB::beginTransaction();
        try
        {
            $avaliacao = Avaliacao::with(['questao'])->find($dados['id']);

            if($avaliacao->publicado != 1)
            {
                    $perguntas = serialize($avaliacao->questao);

                    unset($avaliacao->questao);
                    unset($avaliacao->peso_questao);
                    $avaliacao->update(['publicado'=> 1, 'perguntas'=> $perguntas]);

                    DB::commit();
                    return true;
            }
            else{
                return 'Não é possível publicar. A avaliação já está publicada.';
            }
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

	private function adicionarQuestaoAvaliacao($avaliacao, $questoes){
        $questoesAdd = [];
        foreach ($questoes as $q) {

            $questoesAdd[] = $q;

        }
        $avaliacao->questao()->attach($questoesAdd);
    }

	public function updateAvaliacao($id, $dados)
	{
		DB::beginTransaction();
        try
        {

        	$questoes = [];
	        $ordem = 1;
	        foreach($dados['questao'] as $q)
	        {
	        	$questao = [];
	        	$questao['ead_questao_id'] = $q;
	        	$questao['ordem'] = $ordem;
	        	$questao['peso'] = $dados['peso_questao'][$q];
	        	$questoes[] = $questao;
	        	$ordem++;
	        }
            unset($dados['questao']);
        	$avaliacao = $this->getAvaliacao($id);
            $dados['qtd_questao'] = $ordem-1;
        	unset($avaliacao->questao);
        	unset($avaliacao->peso_questao);
	        $avaliacao->update($dados);
	        $avaliacao->questao()->detach();
	        $avaliacao->questao()->sync($questoes);
	        DB::commit();

	        return true;
	    }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
	}

    public function duplicar($avaliacaoId)
    {

        $user = auth()->user();
        DB::beginTransaction();
        try
        {
            $avaliacaoOriginal = Avaliacao::with('questao')
                                    ->withTrashed()
                                    ->find($avaliacaoId);
            $avaliacao = $avaliacaoOriginal->replicate();
            $avaliacao->save();

            $titulo = 'Cópia '.$avaliacao->titulo;

            $avaliacao->update([
                'titulo' => $titulo,
                'publicado'=>0,
                'user_id'=>$user->id,
            ]);
            $avaliacao->restore();
            $ordem = 0;
            $questoes=[];
            foreach ($avaliacaoOriginal->questao as $q) {
                $questao = [];

                $questao['ead_questao_id'] = $q->id;
                $questao['ordem'] = $ordem;
                $questao['peso'] = $q->pivot->peso;
                $questoes[] = $questao;
                $ordem++;

            }
            $this->adicionarQuestaoAvaliacao($avaliacao,$questoes);



            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            dd($e);
            return false;
        }
    }

	public function excluirAvaliacao($idAvaliacao)
	{
		DB::beginTransaction();
        try
        {
        	$avaliacao = Avaliacao::where('publicado',0)->find($idAvaliacao);
	        $avaliacao->questao()->detach();
	        $avaliacao->forcedelete();
	        DB::commit();
	        return true;
	    }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
	}

    public function cancelarAvaliacao($idAvaliacao)
    {
        DB::beginTransaction();
        try
        {
            $avaliacao = Avaliacao::where('publicado',1)->find($idAvaliacao);
            $avaliacao->delete();
            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

	public function getAvaliacao($idAvaliacao)
	{
        try
        {
        	$avaliacao = Avaliacao::with('questao')->find($idAvaliacao);

            $questao = [];
            $peso_questao = [];
        	foreach($avaliacao->questao as $a)
        	{
        		$questao[] = $a->id;
                $peso_questao[$a->id] = $a->pivot->peso;
        	}
        	$avaliacao->questao = $questao;
            $avaliacao->peso_questao = $peso_questao;


        	return $avaliacao;
	    }
        catch (\Exception $e){
            return false;
        }
	}

    public function avaliacoesAlunos($request)
    {
        $cicloEtapa = FrTurma::join('fr_turma_aluno', function($join){
                $join->on('fr_turma_aluno.turma_id', 'fr_turmas.id');
                $join->on('fr_turma_aluno.aluno_id', DB::raw(auth()->user()->id));
            })
            ->where('escola_id',auth()->user()->escola_id)
            ->get()->pluck('ciclo_etapa_id')->toArray();
        if(count($cicloEtapa) == 0){
            return [];
        }

        $ret = Avaliacao::whereIn('cicloetapa_id', $cicloEtapa)
            ->with(['disciplina','usuario',
                'placar'=>function($q){
                    $q->where('user_id',auth()->user()->id);
                },
                'logAtividade'=>function($q){
                    $q->where('user_id',auth()->user()->id);
                },
                'logGeral'=>function($q){
                    $q->where('user_id',auth()->user()->id);
                }]
        )
            ->where('publicado', 1)

            ->whereHas('permissao', function ($q){
                $q->where(function($query){
                    $query->orWhere('instituicao_id',0)
                    ->orWhere('instituicao_id',auth()->user()->instituicao_id);
                })->where(function($query){
                    $query->orWhere('escola_id',0)
                        ->orWhere('escola_id',auth()->user()->escola_id);
                });
            });

        if($request->input('finalizado')!=1)
        {
            $ret->where('data_hora_inicial', '<=', date('Y-m-d H:i:s') )
                ->where('data_hora_final', '>=',date('Y-m-d H:i:s') )
                ->doesntHave('logGeral','and',function($q){
                    $q->where('user_id',auth()->user()->id);
                })
                ->doesntHave('logAtividade','and',function($q){
                    $q->where('user_id',auth()->user()->id);
                })
                ->doesntHave('placar','and',function($q){
                    $q->where('user_id',auth()->user()->id);
                });
        }
        else{
            $ret->where(function($query){
                $query->orWhere('data_hora_final', '<=',date('Y-m-d H:i:s') )
                    ->orWhereHas('logGeral',function($q){
                        $q->where('user_id',auth()->user()->id);
                    })
                    ->orWhereHas('logAtividade',function($q){
                        $q->where('user_id',auth()->user()->id);
                    })
                    ->orWhereHas('placar',function($q){
                        $q->where('user_id',auth()->user()->id);
                    });
            });
        }

        if($request->input('texto'))
        {
            $ret = $ret->where('titulo','like','%'.$request->input('texto').'%');
        }
        $ret->orderBy('id', 'desc');
        return $ret->paginate(15);
    }


    public function getAvaliacaoAlunos($idAvaliacao, $idTrilha, $realizar = null)
    {
        try {
            $trilhaService = new TrilhasService();
            $estatisticaTrilha = $trilhaService->getEstatistica( [$idTrilha] );
            $matricula = TrilhasMatriculasUsuario::where('user_id',auth()->user()->id)->where('trilha_id', $idTrilha)->first();
            if($matricula->tentativas_avaliacao>=3 || $matricula->porcentagem_acerto >=70 ){
                return false;
            }
            if(!isset($estatisticaTrilha[$idTrilha]) || $estatisticaTrilha[$idTrilha]['total'] != $estatisticaTrilha[$idTrilha]['feito']){
                return false;
            }
            $avaliacao = Avaliacao::with([ 'usuario'])
                ->where('publicado', 1);

            unset($avaliacao->perguntas);
            if ($realizar) {
                $avaliacao = $avaliacao->doesntHave('logGeral','and',function($q){
                    $q->where('user_id',auth()->user()->id);
                })
                    ->doesntHave('logAtividade','and',function($q){
                        $q->where('user_id',auth()->user()->id);
                    })
                    ->doesntHave('logGeral','and',function($q){
                        $q->where('user_id',auth()->user()->id);
                    });
            }
            $avaliacao = $avaliacao->find($idAvaliacao);
            if($avaliacao){
                $perguntas = unserialize($avaliacao->perguntas);
                $perguntas = $perguntas->random($avaliacao->quantidade_minima_questoes);

                return ['avaliacao'=>$avaliacao, 'questoes'=>$perguntas];
            }else{
                return false;
            }
        }
        catch (\Exception $e){
            return false;
        }
    }

    public function addLogGeral($request){
        try {

            $dados = (array) $request['obj'];
            $dados['user_id'] = auth()->user()->id;
            $dados['ead_avaliacao_id'] = $request['avaliacao_id'];
            if(isset($dados['ordem_questao'])){
                $dados['ordem_questao'] = serialize($dados['ordem_questao']);
            }
            $dados['ead_questao_id'] = $dados['questao_id'];
            $log = new AvaliacaoLogGeral($dados);
            $log->save();
            return true;
        }
        catch (\Exception $e){
            return false;
        }
    }

    public function addLogAtividade($request){
        try {
            $dados = (array) $request['obj'];
            $dados['user_id'] = auth()->user()->id;
            $dados['ead_avaliacao_id'] = $request['avaliacao_id'];
            $dados['ead_questao_id'] = $dados['questao_id'];
            $dados['instituicao_id'] = auth()->user()->instituicao_id;
            $dados['escola_id'] = auth()->user()->escola_id;
            $usuario = User::find(auth()->user()->id);
            $turmas = $usuario->turmaDeAlunos;
            $permissao = UserPermissao::where('user_permissao.escola_id', $dados['escola_id'])->where('user_permissao.instituicao_id',$dados['instituicao_id'])->where('user_permissao.user_id', $dados['user_id'])->where('user_permissao.permissao','A')->first();
            if($permissao){
                $matricula = $permissao->matricula;
            }else{
                $matricula = $usuario->matricula;
            }
            $dados['matricula'] = $matricula;
            if(count($turmas)==1){
                $dados['turma_id'] = $turmas[0]->id;
            }else{
                $turmaid= '';
                $i =0 ;
                foreach ($turmas as $t){
                    if($i>0){
                        $turmaid .= '-';
                    }
                    $turmaid .= $t->id;
                    $i++;
                }
                $dados['turma_id'] = $turmaid;
            }
            $log = AvaliacaoLogAtividade::where('ead_avaliacao_id', $dados['ead_avaliacao_id'])->where('user_id', $dados['user_id'])->where('ead_questao_id', $dados['ead_questao_id'])->first();
            if($log){
                $log->update($dados);
            }else{
                $log = new AvaliacaoLogAtividade($dados);
                $log->save();
            }
            return true;
        }
        catch (\Exception $e){
            return false;
        }
    }

    public function finalizar($request){
        try {
            $dados['user_id'] = auth()->user()->id;
            $dados['ead_avaliacao_id'] = $request['avaliacao_id'];
            $dados['ead_questao_id'] = $request['questao_ativa'];
            $dados['tempo_ativo'] = $request['tempo'];
            $dados['iniciou_ativo'] = $request['data_hora'];
            $dados['finalizado'] = 1;
            $log = new AvaliacaoLogGeral($dados);
            $log->save();
            $this->totalizarPlacar(auth()->user()->id,$request['avaliacao_id'],$request);
            return true;
        }
        catch (\Exception $e){
            return false;
        }
    }

    public function confereTotalizados($idAvaliacao, $tempoMaximo){
        $dados = AvaliacaoLogGeral::where('ead_avaliacao_id',$idAvaliacao)
            ->doesntHave('placar','and',function($q) use($idAvaliacao){
                $q->where('ead_avaliacao_id',$idAvaliacao)
                    ->where('user_id','ead_avaliacao_log_geral.user_id');
            })
            ->groupBy('user_id')
            ->selectRaw('max(created_at) as tempo, user_id')
            ->get();
        foreach ($dados as $d){
            $ultima = new Carbon($d->tempo);
            $agora = Carbon::now();
            $diferenca = $ultima->diffInMinutes($agora);
            if($diferenca > $tempoMaximo){
                $this->totalizarPlacar($d->user_id,$idAvaliacao,null);
            }
        }
    }

    public function totalizarPlacar($userId,$avaliacaoId,$request =null){
        DB::beginTransaction();
        try {
            $log = AvaliacaoLogAtividade::where('user_id',$userId)->where('ead_avaliacao_id',$avaliacaoId)->get();
            if(!$log){
                $dados = [
                    'user_id' => 0,
                    'ead_avaliacao_id' => 0,
                    'porcentagem_acerto' => 0,
                    'porcentagem_erro' => 0,
                    'porcentagem_acerto_peso' => 0,
                    'porcentagem_erro_peso' => 0,
                    'qtd_em_branco' => 0,
                    'qtd_acerto' => 0,
                    'qtd_erro' => 0,
                    'peso_total_acerto' => 0,
                    'peso_total_erro' => 0,
                    'qtd_questao_para_avaliar' => 0,
                    'qtd_questoes_total' => 0,
                    'qtd_questoes_respondida' => 0,
                    'tempo_ativo' => 0,
                    'tempo_inativo' => 0,
                    'tempo_total' => 0,
                ];
            }
            else {
                $avaliacao = Avaliacao::find($avaliacaoId);
                $questoes = unserialize($avaliacao->perguntas);

                /// padroniza a ordem das questões
                $ordemQuestao = [];
                $ordemQuestaoRand = [];
                $logGeral = AvaliacaoLogGeral::where('user_id',$userId)->where('ead_avaliacao_id',$avaliacaoId)->whereNotNull('ordem_questao')->first();
                if(isset($logGeral->ordem_questao) && $logGeral->ordem_questao != ''){
                    $ordemQ = unserialize($logGeral->ordem_questao);
                    foreach ($ordemQ as $o){
                        $ordemQuestaoRand[$o] = $o;
                    }
                }
                foreach ($questoes as $q){
                    if(!isset($ordemQuestaoRand[$q->id])){
                        continue;
                    }
                    $ordemQuestao[$q->id] = $q;
                }

                //// totalizadores
                $l = [];
                foreach ($log as $lo) {
                    $l[$lo->ead_questao_id] = $lo;
                }
                $totalQuestoes = count($ordemQ);
                $totalCorrigidas = 0;
                $totalCorretas = 0;
                $totalParaAvaliar = 0;
                $totalEmBranco = 0;
                $totalPeso = 0;
                $totalPesoCorreta = 0;
                $totalPesoErro = 0;
                $tempo_ativo = 0;
                $tempo_inativo = 0;
                $tempo_total = 0;
                foreach ($questoes as $q){
                    if(!isset($ordemQuestao[$q->id])){
                        continue;
                    }
                    $ordemQuestao[$q->id]->peso = $q->pivot->peso;
                    if(isset($l[$q->id])) {
                        $ordemQuestao[$q->id]->resposta = $l[$q->id]->resposta;
                    }else{
                        $ordemQuestao[$q->id]->resposta = '';
                    }
                    if($request!= null){
                        if(isset($request['qInativo'.$q->id])){
                            $tempo_ativo += $request['qAtivo'.$q->id];
                            $tempo_inativo += $request['qInativo'.$q->id];
                            $tempo_total += $request['qInativo'.$q->id] + $request['qAtivo'.$q->id];
                            $ordemQuestao[$q->id]->tempo_ativo = $request['qAtivo'.$q->id];
                            $ordemQuestao[$q->id]->tempo_inativo = $request['qInativo'.$q->id];
                        }
                    }
                    else{
                        $tempoLog = AvaliacaoLogAtividade::where('user_id',$userId)->where('ead_avaliacao_id',$avaliacaoId)->where('ead_questao_id',$q->id)->first();

                        if($tempoLog) {

                            $tempo_ativo += $tempoLog->tempo_ativo;
                            $tempo_inativo += $tempoLog->tempo_inativo;
                            $tempo_total += $tempoLog->tempo_ativo + $tempoLog->tempo_inativo;
                            $ordemQuestao[$q->id]->tempo_ativo = $tempoLog->tempo_ativo;
                            $ordemQuestao[$q->id]->tempo_inativo = $tempoLog->tempo_inativo;
                        }
                    }
                    if($q->tipo == 'o'){
                        if(isset($l[$q->id]) && $l[$q->id]->resposta == $q->correta){
                            $totalCorretas++;
                            $totalPesoCorreta += $q->pivot->peso;
                            $ordemQuestao[$q->id]->eh_correto = 1;
                            $ordemQuestao[$q->id]->peso_avaliado = $q->pivot->peso;
                        }else{
                            $totalPesoErro += $q->pivot->peso;
                            $ordemQuestao[$q->id]->eh_correto = 0;
                            $ordemQuestao[$q->id]->peso_avaliado = 0;
                        }
                        if(!isset($l[$q->id]) ){
                            $totalEmBranco++;
                            $ordemQuestao[$q->id]->peso_avaliado = '';
                        }
                        $totalPeso += $q->pivot->peso;
                        $totalCorrigidas ++;

                    }
                    else{
                        if(isset($l[$q->id]) && $l[$q->id]->corrigida == 1){ //// corrigida como correto
                            $totalCorretas++;
                            $totalPeso += $q->pivot->peso;
                            $totalPesoCorreta += $q->pivot->peso;
                            $totalCorrigidas ++;
                            $ordemQuestao[$q->id]->eh_correto = 1;
                            $ordemQuestao[$q->id]->peso_avaliado = $q->pivot->peso;
                        }elseif(!isset($l[$q->id]) || $l[$q->id]->corrigida == 2){ //// corrigida como incorreto
                            $totalPeso += $q->pivot->peso;
                            $totalPesoErro += $q->pivot->peso;
                            $totalCorrigidas ++;
                            $ordemQuestao[$q->id]->eh_correto = 0;
                            $ordemQuestao[$q->id]->peso_avaliado = 0;
                        }elseif(isset($l[$q->id]) ){
                            $ordemQuestao[$q->id]->eh_correto = '';
                            $ordemQuestao[$q->id]->peso_avaliado = '';
                            $totalPeso += $q->pivot->peso;
                            $totalParaAvaliar++;
                        }
                        if(!isset($l[$q->id]) ){
                            $totalEmBranco++;
                            $ordemQuestao[$q->id]->eh_correto = 0;
                            $ordemQuestao[$q->id]->peso_avaliado = 0;
                        }
                    }
                }
                $dados = [
                    'user_id' => $userId,
                    'ead_avaliacao_id' => $avaliacaoId,
                    'porcentagem_acerto' => 0,
                    'porcentagem_erro' => 100,
                    'porcentagem_acerto_peso' => 0,
                    'porcentagem_erro_peso' => 100,
                    'qtd_em_branco' => $totalEmBranco,
                    'qtd_acerto' => $totalCorretas,
                    'qtd_erro' => $totalCorrigidas - $totalCorretas,
                    'peso_total' => $totalPeso,
                    'peso_total_acerto' => $totalPesoCorreta,
                    'peso_total_erro' => $totalPesoErro,
                    'qtd_questao_para_avaliar' => $totalParaAvaliar,
                    'qtd_questoes_total' => $totalQuestoes,
                    'qtd_questoes_respondida' => ($totalCorrigidas+$totalParaAvaliar) - $totalEmBranco,
                    'tempo_ativo' => gmdate("H:i:s", $tempo_ativo),
                    'tempo_inativo' => gmdate("H:i:s", $tempo_inativo),
                    'tempo_total' => gmdate("H:i:s", $tempo_total),
                ];

                if($totalCorrigidas != 0)
                {
                    $dados['porcentagem_acerto'] = (int)(($totalCorretas*100)/($avaliacao->quantidade_minima_questoes));
                    $dados['porcentagem_erro'] = 100 - ((int)(($totalCorretas*100)/($avaliacao->quantidade_minima_questoes)));
                }
                if($totalPeso != 0)
                {
                    $dados['porcentagem_acerto_peso'] = (int)(($totalPesoCorreta*100)/($totalPeso));
                    $dados['porcentagem_erro_peso'] = 100 - ((int)(($totalPesoCorreta*100)/( $totalPeso)));
                }

            }
            $dados['questoes'] = serialize($ordemQuestao);

            $placarHistorico = new AvaliacaoPlacarHistorico($dados);
            $placarHistorico->save();
            $matricula = TrilhasMatriculasUsuario::where('user_id',auth()->user()->id, $request['trilha_id'])->first();
            if($matricula->tentativas_avaliacao>=3 || $dados['porcentagem_acerto'] >=70){
                $placar = new AvaliacaoPlacar($dados);
                $placar->save();
            }

            AvaliacaoLogAtividade::where('user_id',$userId)->where('ead_avaliacao_id',$avaliacaoId)->delete();
            AvaliacaoLogGeral::where('user_id',$userId)->where('ead_avaliacao_id',$avaliacaoId)->delete();
            $chave = '';
            if($dados['porcentagem_acerto'] >=70)
            {
                $chave =  auth()->user()->id.'-'.\Str::random(4).'-'.\Str::random(4).'-'.\Str::random(4).'-'.\Str::random(4).'-'.$request['trilha_id'];
            }
            $dadosMatricula = [
                'chave_validacao' =>  $chave,
            ];

            if($matricula->porcentagem_acerto<=$dados['porcentagem_acerto']){
                $dadosMatricula = array_merge($dadosMatricula,[
                    'porcentagem_acerto' => $dados['porcentagem_acerto'],
                    'nota' => $dados['peso_total_acerto'],
                ]);
            }

            TrilhasMatriculasUsuario::where('user_id',auth()->user()->id)->where('trilha_id',$request['trilha_id'])->update($dadosMatricula);

            DB::commit();
            return true;
        }
        catch (\Exception $e){
            DB::rollback();
            dd($e);
            return false;
        }
    }
    public function getResultadoAluno($avaliacaoId, $trilhaId){
        $avaliacao = Avaliacao::with([ 'usuario'])
            ->where('publicado', 1)
            ->where(function($query){
                $query->orWhereHas('logGeral',function($q){
                    $q->where('user_id',auth()->user()->id);
                })
                    ->orWhereHas('logAtividade',function($q){
                        $q->where('user_id',auth()->user()->id);
                    })
                    ->orWhereHas('placar',function($q){
                        $q->where('user_id',auth()->user()->id);
                    })
                    ->orWhereHas('placarHistorico',function($q){
                        $q->where('user_id',auth()->user()->id);
                    });

            })
            ->find($avaliacaoId);
        if($avaliacao){
            $placar = AvaliacaoPlacarHistorico::where('user_id',auth()->user()->id)->where('ead_avaliacao_id',$avaliacaoId)->orderBy('id','desc')->first();
            if(!$placar){
                $this->totalizarPlacar(auth()->user()->id,$avaliacaoId, ['trilha_id'=>$trilhaId]);
                $placar = AvaliacaoPlacarHistorico::where('user_id',auth()->user()->id)->where('ead_avaliacao_id',$avaliacaoId)->orderBy('id','desc')->first();

            }
            return [
                'avaliacao' => $avaliacao,
                'placar' => $placar,
            ];
        }else{
            return false;
        }
    }

    public function relatorio($avaliacaoId){
        try {
            $avaliacao = Avaliacao::where('publicado',1)->find($avaliacaoId);
            $placar =  AvaliacaoPlacar::where('ead_avaliacao_id',$avaliacao->id)
                ->with('usuario')
                ->with('instituicao')
                ->with('escola')
                ->paginate(50);
            $retorno = $this->preparaRelatorioAdm($placar,$avaliacao);
            $retorno['placar'] = $placar;
            $retorno['avaliacao'] = $avaliacao;
            return $retorno;
        }
        catch (\Exception $e){
            return false;
        }
    }

    private function preparaRelatorioAdm($placar, $avaliacao){
        /// disciplinas
        if($avaliacao->disciplina_id == 14){
            $disciplina=1;
        }elseif($avaliacao->disciplina_id == 12){
            $disciplina=2;
        }elseif($avaliacao->disciplina_id == 4){
            $disciplina=3;
        }else{
            $disciplina = 'não definida no código do ead';
        }
        //// questoes
        $perguntas = unserialize($avaliacao->perguntas);
        $ordemPerguntas = [];
        foreach($perguntas as $p){
            $ordemPerguntas[$p->id] = '';
        }
        $questoesAlunos = [];
        $turmaAlunos = [];
        $letras = $this->letrasQuestao();
        foreach ($placar as $p){
            $questoesAlunos[$p->user_id] = $ordemPerguntas;
            $questoes = unserialize($p->questoes);
            foreach($questoes as $q){
                $questoesAlunos[$p->user_id][$q->id] = $letras[$q->resposta];
            }

            $turma = explode('-',$p->turma_id);
            $turma = FrTurma::whereIn('id',$turma)
                        ->with('cicloEtapa')
                        ->get();
            $nomeTurma = '';
            $cicloEtapa = '';
            $cicloEtapaId = '';
            $turno = '';
            $i =0;
            $cicloEtapaAno =  $this->cicloEtapaAno();
            foreach($turma as $t){
                if($i>0){
                    $nomeTurma .= ' - ';
                    $cicloEtapa .= ' - ';
                    $cicloEtapaId .= ' - ';
                    $turno .= ' - ';
                }
                $nomeTurma .= $t->titulo;
                $cicloEtapa .= $t->cicloEtapa->ciclo_etapa;
                $cicloEtapaId .= @$cicloEtapaAno[$t->ciclo_etapa_id];
                $turno .= $t->turno;
                $i++;
            }
            $turmaAlunos[$p->user_id]['turma'] = $nomeTurma;
            $turmaAlunos[$p->user_id]['ano'] = $cicloEtapa;
            $turmaAlunos[$p->user_id]['ciclo_etapa_id'] = $cicloEtapaId;
            $turmaAlunos[$p->user_id]['turno'] = $turno;
        }

        return [
            'ordemPerguntas' => $ordemPerguntas,
            'questoesAlunos' => $questoesAlunos,
            'disciplina' => $disciplina,
            'turmaAlunos'=>$turmaAlunos,
        ];
    }

    private function letrasQuestao(){
        return [
            '1' => 'A',
            '2' => 'B',
            '3' => 'C',
            '4' => 'D',
            '5' => 'E',
            '6' => 'F',
            '7' => 'G',
        ];
    }

    private function cicloEtapaAno(){
        return [
            4 => 1,
            5 => 2,
            6 => 3,
            7 => 4,
            8 => 5,
            9 => 1,
            10 => 2,
            11 => 3,
            12 => 4,
            13 => 5,
            14 => 6,
            15 => 7,
            16 => 8,
            17 => 9,
            18 => 1,
            19 => 2,
            20 => 3,
        ];
    }

    public function relatorioAdmDownload($dados, $questoesAlunos,$turmaAlunos,$disciplina){

        $csv=[];
        $ret = [];
        $ret[] = 'form_id';
        $ret[] = 'avaliacao';
        $ret[] = 'disciplina';
        $ret[] = 'instituicao/municipio';
        $ret[] = 'escola';
        $ret[] = 'ano';
        $ret[] = 'turma';
        $ret[] = 'turno';
        $ret[] = 'matricula';
        $ret[] = 'nome';
        $ret[] = 'd_nasc';
        $ret[] = 'idade';
        $ret[] = 'sexo';
        $i =1;
        foreach($questoesAlunos[$dados[0]->usuario->id] as $p){
            $ret[]='Item_'.$i;
            $i++;
        }
        $csv[]=$ret;
        foreach($dados as $d){
            $ret = [];
            $ret[] = $d->usuario->id;
            $ret[] = $d->ead_avaliacao_id;
            $ret[] = $disciplina;
            if($d->instituicao->instituicao_tipo_id==2){
                $ret[] = $d->escola->cidade;
            }
            else{
                $ret[] = $d->instituicao->titulo;
            }
            $ret[] = $d->escola->titulo;
            $ret[] = $turmaAlunos[$d->usuario->id]['ciclo_etapa_id']; /// fazer trocar o ano para apenas numeros
            $ret[] = $turmaAlunos[$d->usuario->id]['turma'];
            $ret[] = $turmaAlunos[$d->usuario->id]['turno'];
            $ret[] = $d->usuario->matricula;
            $ret[] = $d->usuario->nome_completo;
            if($d->usuario->data_nascimento!=''){
                $ret[]=$d->usuario->data_nascimento->format('d/m/Y');
            }else{
                $ret[]='';
            }
            if($d->usuario->data_nascimento!='') {
                $ret[] = \Carbon\Carbon::parse($d->usuario->data_nascimento)->age;
            }else {
                $ret[]='';
            }
            $ret[] = $d->usuario->genero;
            foreach($questoesAlunos[$d->usuario->id] as $p){
                $ret[]=$p;
            }
            $csv[]=$ret;
        }

        $callback = function() use($csv) {
            $file = fopen('php://output', 'w');

            foreach ($csv as $fields) {
                fputcsv($file, $fields, ';');
            }

            fclose($file);
        };
        return $callback;
    }

    public function certificado($id){
        try{
            $certificado = TrilhasMatriculasUsuario::where('chave_validacao',$id)
                ->whereNotNull('chave_validacao')
                ->with('usuario')
                ->with(['trilha'=>function($query){
                    $query->with('cursos');
                }]);
            if(auth()->user()->permissao != 'Z'){
                $certificado->where('user_id',auth()->user()->id);
            }
            return $certificado->first();
        }
        catch (\Exception $e){
            return false;
        }
    }

    public function listaQuestaoRevisao($idAvaliacao){
        $ava = Avaliacao::where('publicado',1)->find($idAvaliacao);
        return unserialize($ava->perguntas);
    }

    public function getQuestaoRevisao($idAvaliacao,$idQuestao){
        $ava = Avaliacao::where('publicado',1)->find($idAvaliacao);
        $questoes = unserialize($ava->perguntas);
        return $questoes->find($idQuestao);
    }

    public function updateQuestoesRevisao($idAvaliacao, $idQuestao, $dados){
        DB::beginTransaction();
        try
        {
            $questaoService = new QuestaoService();
            $ava = Avaliacao::where('publicado',1)->find($idAvaliacao);
            $questoes = unserialize($ava->perguntas);
            $dados = $questaoService->arrumaNaoSeAplica($dados);
            $questoes->find($idQuestao)->update($dados);

            $dadosQuestao = serialize($questoes);
            $ava->update(['perguntas'=>$dadosQuestao]);

            DB::commit();
            $this->recalculaPlacar($idAvaliacao,$questoes);
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            dd($e);
            return false;
        }
    }

    private function recalculaPlacar($idAvaliacao,$questoes){
        $avaliacaoPlacar = AvaliacaoPlacar::where('ead_avaliacao_id',$idAvaliacao)->get();
        foreach($avaliacaoPlacar as $a){
            $this->recalculaPlacarFila($a,$questoes);
        }
        $avaliacaoPlacarLog = AvaliacaoPlacarHistorico::where('ead_avaliacao_id',$idAvaliacao)->get();
        foreach($avaliacaoPlacarLog as $a){
            $this->recalculaPlacarFila($a,$questoes);
        }
    }

    public function recalculaPlacarFila($placar, $questoes){
        $questoesPlacar = unserialize($placar->questoes);
        foreach($questoesPlacar as $key => $qPlacar){
            $qNova = $questoes->find($qPlacar->id);
            $qPlacar->disciplina    = $qNova->disciplina;
            $qPlacar->dificuldade   = $qNova->dificuldade;
            $qPlacar->tipo          = $qNova->tipo;
            $qPlacar->pergunta      = $qNova->pergunta;
            $qPlacar->alternativa_1 = $qNova->alternativa_1;
            $qPlacar->alternativa_2 = $qNova->alternativa_2;
            $qPlacar->alternativa_3 = $qNova->alternativa_3;
            $qPlacar->alternativa_4 = $qNova->alternativa_4;
            $qPlacar->alternativa_5 = $qNova->alternativa_5;
            $qPlacar->alternativa_6 = $qNova->alternativa_6;
            $qPlacar->alternativa_7 = $qNova->alternativa_7;
            $qPlacar->qtd_alternativa = $qNova->qtd_alternativa;
            $qPlacar->correta       = $qNova->correta;
            $qPlacar->com_linhas    = $qNova->com_linhas;
            $qPlacar->qtd_linhas    = $qNova->qtd_linhas;
            $qPlacar->resolucao     = $qNova->resolucao;
            $qPlacar->link_video_resolucao = $qNova->link_video_resolucao;
            $qPlacar->bncc_id       = $qNova->bncc_id;
            $qPlacar->cicloetapa_id = $qNova->cicloetapa_id;
            $qPlacar->unidade_tematica = $qNova->unidade_tematica;
            $qPlacar->suporte_id    = $qNova->suporte_id;
            $qPlacar->formato_id    = $qNova->formato_id;
            $qPlacar->palavras_chave = $qNova->palavras_chave;
            $qPlacar->fonte         = $qNova->fonte;
            $qPlacar->tema_id       = $qNova->tema_id;

            $qPlacar->eh_correto = 0;
            $qPlacar->peso_avaliado = 0;
            if($qPlacar->resposta == $qPlacar->correta){
                $qPlacar->eh_correto = 1;
                $qPlacar->peso_avaliado = $qPlacar->peso;
            }
        }
        $placar->update([
            'questoes' => serialize($questoesPlacar),
        ]);

        $this->totalizaPlacarFila($placar);
    }

    public function totalizaPlacarFila($placar){
        $dados = [
            'porcentagem_acerto' => 0,
            'porcentagem_erro' => 0,
            'porcentagem_acerto_peso' => 0,
            'porcentagem_erro_peso' => 0,
            'qtd_acerto' => 0,
            'qtd_erro' => 0,
            'peso_total_acerto' => 0,
            'peso_total_erro' => 0,
        ];

        $avaliacao = Avaliacao::find($placar->ead_avaliacao_id);


        $questoesPlacar = unserialize($placar->questoes);
        foreach($questoesPlacar as $qPlacar){
            if($qPlacar->resposta == $qPlacar->correta){
                $dados['qtd_acerto']++;
                $dados['peso_total_acerto'] += $qPlacar->peso;
            }else{
                $dados['qtd_erro']++;
                $dados['peso_total_erro'] += $qPlacar->peso;
            }

        }
        if($dados['qtd_acerto']>0 && $avaliacao->quantidade_minima_questoes > 0) {
            $dados['porcentagem_acerto'] = ($dados['qtd_acerto'] * 100) / $avaliacao->quantidade_minima_questoes;
        }
        if($dados['qtd_erro']>0 && $avaliacao->quantidade_minima_questoes > 0) {
            $dados['porcentagem_erro'] = ($dados['qtd_erro'] * 100) / $avaliacao->quantidade_minima_questoes;
        }
        if($dados['peso_total_acerto']>0 && ($dados['peso_total_acerto'] + $dados['peso_total_erro'])>0) {
            $dados['porcentagem_acerto_peso'] = ($dados['peso_total_acerto'] * 100) / ($dados['peso_total_acerto'] + $dados['peso_total_erro']);
        }
        if($dados['peso_total_erro']>0 && ($dados['peso_total_acerto'] + $dados['peso_total_erro'])>0) {
            $dados['porcentagem_erro_peso'] = ($dados['peso_total_erro'] * 100) / ($dados['peso_total_acerto'] + $dados['peso_total_erro']);
        }
        $placar->update($dados);

        $trilha = Trilha::where('avaliacao_id', $placar->ead_avaliacao_id)->get();
        $vetTrilhaId = $trilha->pluck('id');

        $matricula = TrilhasMatriculasUsuario::where('user_id',$placar->user_id)->whereIn('trilha_id',$vetTrilhaId)->get();

        foreach ($matricula as $m){
            $dadosMatricula = [];
            if($m->porcentagem_acerto < $dados['porcentagem_acerto']){
                $dadosMatricula = [
                    'porcentagem_acerto' => $dados['porcentagem_acerto'],
                    'nota'               => $dados['peso_total_acerto'],
                ];
                if($m->porcentagem_acerto < 70 && $dados['porcentagem_acerto'] >= 70){
                    $dadosMatricula['chave_validacao'] = $placar->user_id.'-'.\Str::random(4).'-'.\Str::random(4).'-'.\Str::random(4).'-'.\Str::random(4).'-'.$m->trilha_id;
                }
            }
            if(count($dadosMatricula)>0){
                TrilhasMatriculasUsuario::where('user_id',$placar->user_id)->where('trilha_id',$m->trilha_id)->update($dadosMatricula);
            }
        }
    }

}
