<?php
namespace App\Services\Fr;
use App\Models\Altitude\TrilhasMatriculasUsuario;
use App\Models\FrAvaliacaoLogAtividade;
use App\Models\FrAvaliacaoLogGeral;
use App\Models\FrAvaliacaoPlacar;
use App\Models\FrQuestao;
use App\Models\Trilha;
use Carbon\Carbon;
use DB;
use App\Models\FrAvaliacao;

class AvaliacaoService {

	public function minhasAvaliacoes($request)
	{
		$ret = FrAvaliacao::with(['disciplina', 'usuario', 'turmas']);
        if(auth()->user()->permissao == 'Z')
        {
            $ret->where('fr_avaliacao.instituicao_id',1);
            if($request->input('ead')!=1)
            {
                $ret->where('eh_ead',0);
            }else{
                $ret->where('eh_ead',1);
            }
        }
        else {
            $ret->where('fr_avaliacao.eh_ead',0);
            if($request->input('biblioteca')!=1)
            {
                $ret->where('fr_avaliacao.user_id',auth()->user()->id)
                    ->where('fr_avaliacao.escola_id',auth()->user()->escola_id);
            }
            else
            {
                $ret->Where('instituicao_id',1)
                    ->where('publicado',1);
            }
        }

        if($request->input('titulo'))
        {
            $ret = $ret->where('titulo','like','%'.$request->input('titulo').'%');
        }
        if($request->input('disciplina'))
        {
            $ret = $ret->where('disciplina_id',$request->input('disciplina'));
        }
        if($request->input('tipo'))
        {
            $ret = $ret->where('tipo',$request->input('tipo'));
        }
        if($request->input('aplicacao'))
        {
            $ret = $ret->where('aplicacao',$request->input('aplicacao'));
        }
        $ret->orderBy('id','desc');
		return $ret->paginate(20);
	}

    public function avaliacoesAlunos($request)
    {
        $ret = FrAvaliacao::with(['disciplina','usuario',
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
                            ->where('fr_avaliacao.escola_id',auth()->user()->escola_id)
                            ->where('aplicacao', 'o')
                            ->where('publicado', 1)
                            ->whereHas('turmas', function ($q){
                                $q->join('fr_turma_aluno', 'fr_turmas.id', 'fr_turma_aluno.turma_id')
                                ->where('fr_turma_aluno.aluno_id',auth()->user()->id);
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

	public function addAvaliacao($dados)
	{
		DB::beginTransaction();
        try
        {
            if(auth()->user()->permissao != 'Z'){
                $dados['eh_ead'] = 0;
            }
        	$dados['user_id'] = auth()->user()->id;
        	$dados['escola_id'] = auth()->user()->escola_id;
        	$dados['instituicao_id'] = auth()->user()->instituicao_id;
        	$dados['qtd_questao'] = count($dados['questao']);
			$avaliacao = new FrAvaliacao($dados);
	        $avaliacao->save();
	        $questoes = [];
	        $ordem = 1;
            $pesoQuestao = $dados['peso_questao'];
	        foreach($dados['questao'] as $q)
	        {
                $peso = 0;
                if(isset($pesoQuestao[$q])){
                    $peso = $pesoQuestao[$q];
                }
	        	$questao = [];
	        	$questao['questao_id'] = $q;
	        	$questao['ordem'] = $ordem;
	        	$questao['peso'] = $peso;
	        	$questoes[] = $questao;
	        	$ordem++;
	        }
            $this->adicionarQuestaoAvaliacao($avaliacao,$questoes);

            DB::commit();

            if (isset($dados['ativo']) && $dados['ativo'] == 1){
                $ativar = [
                    'id' => $avaliacao->id,
                    'turmas' => @$dados['turmas'],
                    'tipo' => (isset($dados['tipoPublicar'])) ? $dados['tipoPublicar'] : null ,
                ];
                $this->publicar($ativar);
            }

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
            $avaliacao = FrAvaliacao::with(['questao']);
            if(auth()->user()->permissao == 'Z'){
                $avaliacao = $avaliacao->where('instituicao_id',1);
            }
            else{
                $avaliacao = $avaliacao->where('user_id',auth()->user()->id)
                    ->where('escola_id',auth()->user()->escola_id);
            }
            $avaliacao = $avaliacao->find($dados['id']);

            if($avaliacao->publicado != 1)
            {
                if(auth()->user()->instituicao_id == 1 || ($avaliacao->data_hora_inicial->gte(date('Y-m-d H:i:s')) ) ){

                    $perguntas = serialize($avaliacao->questao);

                    unset($avaliacao->questao);
                    unset($avaliacao->peso_questao);
                    $avaliacao->update(['publicado'=> 1, 'perguntas'=> $perguntas]);
                    if(auth()->user()->permissao == 'P' || (auth()->user()->permissao == 'C' && $dados['tipo'] == 2) ){
                        $avaliacao->turmas()->attach($dados['turmas']);
                    }
                    DB::commit();
                    return true;
                }
                else{
                    return 'Não é possível publicar. A data inicial da avaliação já passou.';
                }
            }
            else{
                return 'Não é possível publicar. A avaliação já está publicada.';
            }
        }
        catch (\Exception $e)
        {
            DB::rollback();
            dd($e);
            return false;
        }
    }

	private function adicionarQuestaoAvaliacao($avaliacao, $questoes){
        $questoesAdd = [];
        $questaoService = new QuestaoService();
        foreach ($questoes as $q) {
            $questaoUser = null;
            $questaoOriginal = null;
            if(auth()->user()->instituicao_id != 1) {
                $questaoUser = FrQuestao::where('user_id', auth()->user()->id)
                    ->where('instituicao_id', auth()->user()->instituicao_id)
                    ->where('escola_id', auth()->user()->escola_id)
                    ->where(function($query) use($q){
                        $query->orWhere('id_original', $q['questao_id'])
                                ->orWhere('id', $q['questao_id']);
                    })
                    ->first();
                $questaoOriginal = FrQuestao::find($q['questao_id']);
            }

            if($questaoUser && $questaoUser->created_at->gte($questaoOriginal->updated_at)){
                $q['questao_id'] = $questaoUser->id;
            }
            elseif(auth()->user()->instituicao_id != 1){
                    $id = $questaoService->duplicarQuestao($q['questao_id']);
                    $q['questao_id'] = $id;
            }
            $questoesAdd[] = $q;

        }
        $avaliacao->questao()->attach($questoesAdd);
    }

	public function updateAvaliacao($id, $dados)
	{
		DB::beginTransaction();
        try
        {
            if(auth()->user()->permissao != 'Z'){
                $dados['eh_ead'] = 0;
            }else{
                if(!isset($dados['eh_ead']) || $dados['eh_ead']!=1){
                    $dados['eh_ead'] = 0;
                }
            }
        	$questoes = [];
            $pesoQuestao = $dados['peso_questao'];
	        $ordem = 1;
	        foreach($dados['questao'] as $q)
	        {
                $peso = 0;
                if(isset($pesoQuestao[$q])){
                    $peso = $pesoQuestao[$q];
                }
	        	$questao = [];
	        	$questao['questao_id'] = $q;
	        	$questao['ordem'] = $ordem;
                $questao['peso'] = $peso;
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
	        $avaliacao->questao()->attach($questoes);
	        DB::commit();


            if (isset($dados['ativo']) && $dados['ativo'] == 1){
                $ativar = [
                    'id' => $avaliacao->id,
                    'turmas' => @$dados['turmas'],
                    'tipo' => (isset($dados['tipoPublicar'])) ? $dados['tipoPublicar'] : null ,
                ];
                $this->publicar($ativar);
            }
	        return true;
	    }
        catch (\Exception $e)
        {
            DB::rollback();
            dd($e);
            return false;
        }
	}

    public function duplicar($avaliacaoId)
    {

        $user = auth()->user();
        DB::beginTransaction();
        try
        {
            $avaliacaoOriginal = FrAvaliacao::with('questao');
            if(auth()->user()->permissao == 'Z'){
                $avaliacaoOriginal = $avaliacaoOriginal->where('instituicao_id',1);
            }
            else{
                $avaliacaoOriginal = $avaliacaoOriginal->where(function($query){
                                    $query->where(function($q){
                                        $q->where('user_id',auth()->user()->id)
                                            ->where('escola_id',auth()->user()->escola_id);
                                    })
                                    ->orWhere('instituicao_id',1);
                                });

            }
            $avaliacaoOriginal = $avaliacaoOriginal->find($avaliacaoId);
            $avaliacao = $avaliacaoOriginal->replicate();
            $avaliacao->save();

            $titulo = 'Cópia '.$avaliacao->titulo;

            $avaliacao->update([
                'titulo' => $titulo,
                'publicado'=>0,
                'user_id'=>$user->id,
                'escola_id'=>$user->escola_id,
                'instituicao_id'=>$user->instituicao_id,
            ]);
            $ordem = 0;
            $questoes=[];
            foreach ($avaliacaoOriginal->questao as $q) {
                $questao = [];

                $questao['questao_id'] = $q->id;
                $questao['peso'] = $q->pivot->peso;
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
	public function excluirAvaliacao($idAvaliacao)
	{
		DB::beginTransaction();
        try
        {
        	if(auth()->user()->permissao == 'Z'){
                $avaliacao = FrAvaliacao::where('publicado',0)->find($idAvaliacao);
            }
            else{
                $avaliacao = FrAvaliacao::where('user_id',auth()->user()->id)->where('publicado',0)->find($idAvaliacao);
            }
            $avaliacao->questao()->detach();
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
        	$avaliacao = FrAvaliacao::with('questao');
        	if(auth()->user()->permissao == 'Z'){
                $avaliacao = $avaliacao->where('instituicao_id',1);
            }
        	else{
                $avaliacao = $avaliacao->where('user_id',auth()->user()->id)
                                ->where('escola_id',auth()->user()->escola_id);
            }
            $avaliacao = $avaliacao->find($idAvaliacao);

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

    public function getAvaliacaoAlunos($idAvaliacao, $realizar = null)
    {
        try {
            $avaliacao = FrAvaliacao::with(['disciplina', 'usuario'])
                ->where('fr_avaliacao.escola_id', auth()->user()->escola_id)
                ->where('aplicacao', 'o')
                ->where('publicado', 1)
                ->where('data_hora_inicial', '<=', date('Y-m-d H:i:s'))
                ->where('data_hora_final', '>=', date('Y-m-d H:i:s'));


            unset($avaliacao->perguntas);
            if ($realizar) {
                $avaliacao = $avaliacao->doesntHave('logGeral','and',function($q){
                                            $q->where('user_id',auth()->user()->id);
                                        })
                                        ->doesntHave('logAtividade','and',function($q){
                                            $q->where('user_id',auth()->user()->id);
                                        })
                                        ->doesntHave('placar','and',function($q){
                                            $q->where('user_id',auth()->user()->id);
                                        });
            }
            $avaliacao = $avaliacao->find($idAvaliacao);
            if($avaliacao){
                return ['avaliacao'=>$avaliacao, 'questoes'=>unserialize($avaliacao->perguntas)];
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
            $dados['avaliacao_id'] = $request['avaliacao_id'];
            if(isset($dados['ordem_questao'])){
                $dados['ordem_questao'] = serialize($dados['ordem_questao']);
            }
            $log = new FrAvaliacaoLogGeral($dados);
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
            $dados['avaliacao_id'] = $request['avaliacao_id'];
            $log = FrAvaliacaoLogAtividade::where('avaliacao_id', $dados['avaliacao_id'])->where('user_id', $dados['user_id'])->where('questao_id', $dados['questao_id'])->first();
            if($log){
                $log->update($dados);
            }else{
                $log = new FrAvaliacaoLogAtividade($dados);
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
            $dados['avaliacao_id'] = $request['avaliacao_id'];
            $dados['questao_id'] = $request['questao_ativa'];
            $dados['tempo_ativo'] = $request['tempo'];
            $dados['iniciou_ativo'] = $request['data_hora'];
            $dados['finalizado'] = 1;
            $log = new FrAvaliacaoLogGeral($dados);
            $log->save();
            $this->totalizarPlacar(auth()->user()->id,$request['avaliacao_id'],$request);
            return true;
        }
        catch (\Exception $e){
            return false;
        }
    }

    public function confereTotalizados($idAvaliacao, $tempoMaximo){
        $dados = FrAvaliacaoLogGeral::where('avaliacao_id',$idAvaliacao)
                            ->doesntHave('placar','and',function($q) use($idAvaliacao){
                                $q->where('avaliacao_id',$idAvaliacao)
                                    ->where('user_id','fr_avaliacao_log_geral.user_id');
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
            $log = FrAvaliacaoLogAtividade::where('user_id',$userId)->where('avaliacao_id',$avaliacaoId)->get();
            if(!$log){
                $dados = [
                    'user_id' => 0,
                    'avaliacao_id' => 0,
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
                $avaliacao = FrAvaliacao::find($avaliacaoId);
                $questoes = unserialize($avaliacao->perguntas);

                /// padroniza a ordem das questões
                $ordemQuestao = [];
                $logGeral = FrAvaliacaoLogGeral::where('user_id',$userId)->where('avaliacao_id',$avaliacaoId)->whereNotNull('ordem_questao')->first();
                if(isset($logGeral->ordem_questao) && $logGeral->ordem_questao != ''){
                    $ordemQ = unserialize($logGeral->ordem_questao);
                    foreach ($ordemQ as $o){
                        $ordemQuestao[$o] = '';
                    }
                }
                foreach ($questoes as $q){
                    $ordemQuestao[$q->id] = $q;
                }
                /// cria o obj da time line
               // $timeLine = $this->criarTimeLine($userId,$avaliacaoId,$ordemQuestao);

                //// totalizadores
                $l = [];
                foreach ($log as $lo) {
                    $l[$lo->questao_id] = $lo;
                }
                $totalQuestoes = count($questoes);
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
                        $tempoLog = FrAvaliacaoLogAtividade::where('user_id',$userId)->where('avaliacao_id',$avaliacaoId)->where('questao_id',$q->id)->first();
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
                    'avaliacao_id' => $avaliacaoId,
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
                    //'time_line' => serialize($timeLine),
                    ];

                if($totalCorrigidas != 0)
                {
                    $dados['porcentagem_acerto'] = (int)(($totalCorretas*100)/($totalCorrigidas));
                    $dados['porcentagem_erro'] = 100 - ((int)(($totalCorretas*100)/($totalCorrigidas)));
                }
                if($totalPeso != 0)
                {
                    $dados['porcentagem_acerto_peso'] = (int)(($totalPesoCorreta*100)/($totalPeso));
                    $dados['porcentagem_erro_peso'] = 100 - ((int)(($totalPesoCorreta*100)/( $totalPeso)));
                }

            }
            $dados['questoes'] = serialize($ordemQuestao);

            ///FrAvaliacaoPlacar::where('user_id',$userId)->where('avaliacao_id',$avaliacaoId)->delete();
            $placar = new FrAvaliacaoPlacar($dados);
            $placar->save();

            FrAvaliacaoLogAtividade::where('user_id',$userId)->where('avaliacao_id',$avaliacaoId)->delete();
            FrAvaliacaoLogGeral::where('user_id',$userId)->where('avaliacao_id',$avaliacaoId)->delete();
            DB::commit();
            return true;
        }
        catch (\Exception $e){
            DB::rollback();
            return false;
        }
    }

    public function getResultadoAluno($avaliacaoId){
        $avaliacao = FrAvaliacao::with(['disciplina', 'usuario'])
            ->where('fr_avaliacao.escola_id', auth()->user()->escola_id)
            ->where('aplicacao', 'o')
            ->where('publicado', 1)
            ->where('data_hora_liberacao_resultado', '<=', date('Y-m-d H:i:s'))
            ->where(function($query){
                $query->orWhereHas('logGeral',function($q){
                    $q->where('user_id',auth()->user()->id);
                })
                ->orWhereHas('logAtividade',function($q){
                    $q->where('user_id',auth()->user()->id);
                })
                ->orWhereHas('placar',function($q){
                    $q->where('user_id',auth()->user()->id);
                });
            })
            ->find($avaliacaoId);
        if($avaliacao){
            $placar = FrAvaliacaoPlacar::where('user_id',auth()->user()->id)->where('avaliacao_id',$avaliacaoId)->first();

            if(!$placar){
                $this->totalizarPlacar(auth()->user()->id,$avaliacaoId);
                $placar = FrAvaliacaoPlacar::where('user_id',auth()->user()->id)->where('avaliacao_id',$avaliacaoId)->first();
            }
            return [
                'avaliacao' => $avaliacao,
                'placar' => $placar,
            ];
        }else{
            return false;
        }
    }

    public function salvarCorrecaoPergunta($dados){
        $avaliacao = FrAvaliacao::where('fr_avaliacao.user_id',auth()->user()->id)
            ->where('fr_avaliacao.escola_id',auth()->user()->escola_id)
            ->where('aplicacao','o')
            ->where('publicado',1)
            ->find($dados['avaliacao_id']);
        if($avaliacao){
            $placar = FrAvaliacaoPlacar::where('user_id',$dados['user_id'])->where('avaliacao_id',$dados['avaliacao_id'])->first();
            $questoes = unserialize($placar->questoes);
            $porcentagemAcerto = $placar->porcentagem_acerto;
            $porcentagemErro = $placar->porcentagem_erro;
            $porcentagemAcertoPeso = $placar->porcentagem_acerto_peso;
            $porcentagemErroPeso = $placar->porcentagem_erro_peso;
            $qtdAcerto = $placar->qtd_acerto;
            $qtdErro = $placar->qtd_erro;
            $qtdPesoAcertoTotal = $placar->peso_total_acerto;
            $qtdPesoErroTotal = $placar->peso_total_erro;
            $qtdParaAvaliavar = $placar->qtd_questao_para_avaliar;
            if($dados['avaliacao'] == 1){ /// adequada
                $questoes[$dados['questao_id']]->peso_avaliado = $questoes[$dados['questao_id']]->peso;
                $questoes[$dados['questao_id']]->eh_correto = 1;
                $questoes[$dados['questao_id']]->feedback = $dados['feedback'];
                $questoes[$dados['questao_id']]->corrigida = 1;
                $qtdAcerto ++;
                $qtdPesoAcertoTotal += $questoes[$dados['questao_id']]->peso;

            }elseif($dados['avaliacao'] == 2){ /// parcialmente adequada
                $questoes[$dados['questao_id']]->peso_avaliado = $dados['peso_parcial'];
                $questoes[$dados['questao_id']]->eh_correto = 1;
                $questoes[$dados['questao_id']]->feedback = $dados['feedback'];
                $questoes[$dados['questao_id']]->corrigida = 1;
                $qtdAcerto ++;
                $qtdPesoAcertoTotal += $dados['peso_parcial'];
            }else{ /// não adequada
                $questoes[$dados['questao_id']]->peso_avaliado = 0;
                $questoes[$dados['questao_id']]->eh_correto = 0;
                $questoes[$dados['questao_id']]->feedback = $dados['feedback'];
                $questoes[$dados['questao_id']]->corrigida = 2;
                $qtdErro++;
                $qtdPesoErroTotal += $questoes[$dados['questao_id']]->peso;
            }
            $qtdParaAvaliavar--;

            $porcentagemAcerto = (int)(($qtdAcerto*100)/($qtdAcerto+$qtdErro));
            $porcentagemErro = 100 - $porcentagemAcerto;

            $porcentagemAcertoPeso = 0;
            $porcentagemErroPeso = 0;
            if($avaliacao->tipo_peso != ''){
                $porcentagemAcertoPeso = (int)(($qtdPesoAcertoTotal*100)/($placar->peso_total));
                $porcentagemErroPeso = 100 - $porcentagemAcertoPeso;
            }


            $dados = [
                'questoes'              => serialize($questoes),
                'porcentagem_acerto'    => $porcentagemAcerto,
                'porcentagem_erro'      => $porcentagemErro,
                'porcentagem_acerto_peso' => $porcentagemAcertoPeso,
                'porcentagem_erro_peso' => $porcentagemErroPeso,
                'qtd_acerto'            => $qtdAcerto,
                'qtd_erro'              => $qtdErro,
                'peso_total_acerto'     => $qtdPesoAcertoTotal,
                'peso_total_erro'       => $qtdPesoErroTotal,
                'qtd_questao_para_avaliar' => $qtdParaAvaliavar,
            ];
            return $placar->update($dados);
        }
    }
/*
    public function criarTimeLine($userId,$avaliacaoId,$ordemQuestao){
	    $dados = FrAvaliacaoLogGeral::where('user_id',$userId)
                    ->where('avaliacao_id',$avaliacaoId)
                    ->orderBy('id')
                    ->get()->toArray();
	    $vetQtdResposta = [];
	    $retorno = [];
	    $i=0;
	    $finalizou = 0;
	    foreach($dados as $d){
            $perguntaOriginal =  strip_tags($ordemQuestao[$d['questao_id']]->pergunta);
            $pergunta = substr($perguntaOriginal,0,60);
            if(strlen($perguntaOriginal)>60){
                $pergunta .='...';
            }

            $tempoAtivo = 0;
            $tempoInativo = 0;
            if(isset($dados[$i+1])){
                $tempoAtivo = $dados[$i+1]['tempo_ativo'];
                $tempoInativo = $dados[$i+1]['tempo_inativo'];
            }
            if($d['iniciou_ativo'] != '' || $d['iniciou_inativo'] != ''){
                if($d['iniciou_ativo'] != ''){
                    $dataHora = new Carbon($d['iniciou_ativo']);
                    if($tempoAtivo == ''){
                        $tempoAtivo = 0;
                    }
                }
                else{
                    $dataHora = new Carbon($d['iniciou_inativo']);
                    if($tempoInativo == ''){
                        $tempoInativo = 0;
                    }
                }
            }else{
                $dataHora = new Carbon($d['created_at']);
            }

            $dataHora = $dataHora->format('d/m/Y H:i:s');
            $dataHora = str_replace(' ','<br>',$dataHora);

            $qtdResposta = '';
            if($d['resposta']!=''){
                if(isset($vetQtdResposta[$d['questao_id']])){
                    $vetQtdResposta[$d['questao_id']]++;
                }else{
                    $vetQtdResposta[$d['questao_id']] = 0;
                }
                $qtdResposta = $vetQtdResposta[$d['questao_id']];
            }
            if($finalizou==0) {
                $retorno[] = [
                    'data_hora' => $dataHora,
                    'questao_id' => $d['questao_id'],
                    'questao_tipo' => $ordemQuestao[$d['questao_id']]->tipo,
                    'questao_titulo' => $pergunta,
                    'resposta' => $d['resposta'],
                    'qtd_resposta' => $qtdResposta,
                    'tempo_ativo' => gmdate("H:i:s", $tempoAtivo),
                    'tempo_inativo' => gmdate("H:i:s", $tempoInativo),
                    'iniciou_ativo' => $d['iniciou_ativo'],
                    'iniciou_inativo' => $d['iniciou_inativo'],
                    'finalizado' => $d['finalizado'],
                ];
            }
            if($d['finalizado'] == 1)
            {
                $finalizou = 1;
            }
            $i++;
        }
	    if($finalizou == 0){
	        $retorno[] = ['sem_finalizar'=>1];
        }
	    return $retorno;
    }
*/
}
