<?php
namespace App\Services\Fr\Indica;
use App\Models\Altitude\IndicaInstituicaoEscola;
use App\Models\FrTurma;
use App\Models\Indica\Avaliacao;
use App\Models\Indica\AvaliacaoLogAtividade;
use App\Models\Indica\AvaliacaoLogGeral;
use App\Models\Indica\AvaliacaoPlacar;
use App\Models\Indica\AvaliacaoTempo;
use App\Models\Indica\AvaliacaoTentativas;
use App\Models\User;
use App\Models\UserPermissao;
use App\Services\Fr\Agenda\FamiliaService;
use Carbon\Carbon;
use DB;
use Str;

class AvaliacaoService {

	public function minhasAvaliacoes($request, $dados =[])
	{
		$ret = Avaliacao::with(['disciplina', 'usuario', 'cicloEtapa']);

        if($request->input('titulo'))
        {
            $ret = $ret->where('titulo','like','%'.$request->input('titulo').'%');
        }
        if($request->input('disciplina'))
        {
            $ret = $ret->where('disciplina_id',$request->input('disciplina'));
        }

        if($request->input('caderno'))
        {
            $ret = $ret->where('caderno',$request->input('caderno'));
        }

        if($request->input('cicloetapa_id'))
        {
            $ret = $ret->where('cicloetapa_id',$request->input('cicloetapa_id'));
        }
        if(isset($dados['filtroTipo']) && $dados['filtroTipo']){
            $publicado = $request->input('publicado');
            if($publicado == ''){
                $publicado = 1;
            }
            if($publicado != 2){
                $ret = $ret->where('publicado', $publicado);
            }else{
                $ret->onlyTrashed();
            }
        }
        else{
            $ret->withTrashed();
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
	        	$questao['indica_questao_id'] = $q;
	        	$questao['ordem'] = $ordem;
	        	$questoes[] = $questao;
	        	$ordem++;
	        }
            $this->adicionarQuestaoAvaliacao($avaliacao,$questoes);
            $escolas = $this->preparaEscolas($dados);
            $avaliacao->escolas()->attach($escolas);
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
                if( ($avaliacao->data_hora_inicial->gte(date('Y-m-d H:i:s')) ) ){

                    $perguntas = serialize($avaliacao->questao);

                    unset($avaliacao->questao);
                    unset($avaliacao->peso_questao);
                    $avaliacao->update(['publicado'=> 1, 'perguntas'=> $perguntas]);

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
	        	$questao['indica_questao_id'] = $q;
	        	$questao['ordem'] = $ordem;
	        	$questoes[] = $questao;
	        	$ordem++;
	        }
            unset($dados['questao']);
        	$avaliacao = $this->getAvaliacao($id);
            $dados['qtd_questao'] = $ordem-1;
        	unset($avaliacao->questao);
        	unset($avaliacao->escolas);
	        $avaliacao->update($dados);
	        $avaliacao->questao()->detach();
	        $avaliacao->questao()->sync($questoes);
            $avaliacao->escolas()->detach();
            $escolas = $this->preparaEscolas($dados);
            $avaliacao->escolas()->attach($escolas);
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
            $avaliacaoOriginal = Avaliacao::with('questao', 'permissao')
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

                $questao['indica_questao_id'] = $q->id;
                $questao['ordem'] = $ordem;
                $questoes[] = $questao;
                $ordem++;

            }
            $this->adicionarQuestaoAvaliacao($avaliacao,$questoes);


            $permissoes = [];
            foreach ($avaliacaoOriginal->permissao as $q) {
                $permissao =[];
                $permissao['indica_avaliacao_id'] = $avaliacao->id;
                $permissao['instituicao_id'] = $q['instituicao_id'];
                $permissao['escola_id'] = $q['escola_id'];
                $permissoes[] = $permissao;
            }
            $avaliacao->permissao()->insert($permissoes);
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
        	$avaliacao = Avaliacao::where('publicado',0)->find($idAvaliacao);
	        $avaliacao->questao()->detach();
            $avaliacao->escolas()->detach();
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

    public function permissaoAvaliacao($idAvaliacao)
    {
        DB::beginTransaction();
        try
        {
            $instituicao =[];
            $escola =[];
            $avaliacao = $this->getAvaliacao($idAvaliacao);
            foreach($avaliacao->escolas as $t) {
                $instituicao[] = $t->instituicao_id;
                $escola[$t->instituicao_id][] = $t->escola_id;
            }
            $familiaService = new FamiliaService();
            $dados = $familiaService->getEscolasTurmasSelecionados(['instituicao' => $instituicao, 'escola' => $escola]);
            $view = [
                'dados' => $dados,
                'biblioteca' => '',
                'sem_excluir' => true,
            ];
            return view('fr.agenda.familia.listaInstituicaoEscolaSelecionados',$view)->render();
        }
        catch (\Exception $e)
        {
            return 'Erro';
        }
    }

	public function getAvaliacao($idAvaliacao)
	{
        try
        {
        	$avaliacao = Avaliacao::with('questao')->find($idAvaliacao);

        	$questao = [];
        	foreach($avaliacao->questao as $a)
        	{
        		$questao[] = $a->id;
        	}
        	$avaliacao->questao = $questao;

            $escolas = IndicaInstituicaoEscola::where('indica_avaliacao_id',$idAvaliacao)->get();
            @$avaliacao->escolas = $escolas;

        	return $avaliacao;
	    }
        catch (\Exception $e){
            return false;
        }
	}

    private function preparaEscolas($dados){
        $escolas = [];
            foreach($dados['instituicao'] as $e){
                foreach($dados['escola'][$e] as $t){
                    $escola = [
                        'escola_id' => $t,
                        'instituicao_id' => $e,
                    ];
                    $escolas[] = $escola;
                }
            }
        return $escolas;
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
                ->doesntHave('placar','and',function($q){
                    $q->where('user_id',auth()->user()->id);
                });
        }
        else{
            $ret->where(function($query){
                $query->orWhere('data_hora_final', '<=',date('Y-m-d H:i:s') )
                    ->orWhereHas('placar',function($q){
                        $q->where('user_id',auth()->user()->id);
                    });
            })->with('logGeral');
        }

        if($request->input('texto'))
        {
            $ret = $ret->where('titulo','like','%'.$request->input('texto').'%');
        }
        $ret->orderBy('id', 'desc');
        return $ret->paginate(15);
    }


    public function getAvaliacaoAlunos($idAvaliacao, $realizar = null, $tentativas)
    {
        try {
            $avaliacao = Avaliacao::with(['disciplina', 'usuario'])
                ->where('publicado', 1)
                ->where('data_hora_inicial', '<=', date('Y-m-d H:i:s'))
                ->where('data_hora_final', '>=', date('Y-m-d H:i:s'))
                ->whereHas('permissao', function ($q){
                    $q->where(function($query){
                        $query->orWhere('instituicao_id',0)
                            ->orWhere('instituicao_id',auth()->user()->instituicao_id);
                    })->where(function($query){
                        $query->orWhere('escola_id',0)
                            ->orWhere('escola_id',auth()->user()->escola_id);
                    });
                })
            ->with(['logGeral'=>function($q){
                $q->where('user_id',auth()->user()->id);
            }]);

            unset($avaliacao->perguntas);
            if ($realizar) {
                $avaliacao = $avaliacao->doesntHave('placar','and',function($q){
                                            $q->where('user_id',auth()->user()->id);
                                        });
            }
            $avaliacao = $avaliacao->find($idAvaliacao);
            $retorno = ['avaliacao'=>$avaliacao, 'questoes'=>unserialize($avaliacao->perguntas), 'segundos'=>1, 'minutos'=>0, 'horas'=>0, 'resultado'=>[]];
            if($avaliacao){
                if ($tentativas) {
                    if(count($avaliacao->logGeral) > 0){

                        $tempo = $avaliacao->logGeral->sum('tempo_ativo');
                        $tempo += $avaliacao->logGeral->sum('tempo_inativo');
                        $retorno['horas'] = (int) ($tempo / 3600);
                        $resto = $tempo % 3600;
                        $retorno['minutos'] = (int) ($resto / 60);
                        $retorno['segundos'] = $resto % 60;

                        $resultado = AvaliacaoLogAtividade::where('user_id',auth()->user()->id)
                            ->where('indica_avaliacao_id', $avaliacao->id)
                            ->selectRaw('indica_questao_id, resposta')
                            ->get();
                        foreach($resultado as $r)
                        {
                            $retorno['resultado'][$r->indica_questao_id] = $r->resposta;
                        }
                    }
                    AvaliacaoTentativas::create([
                        'indica_avaliacao_id'   => $avaliacao->id,
                        'user_id'               => auth()->user()->id,
                        'instituicao_id'        => auth()->user()->instituicao_id,
                        'escola_id'             => auth()->user()->escola_id,
                        'iniciou'               => 1,
                    ]);

                    $ultimo = AvaliacaoLogGeral::orderBy('id','desc')
                        ->where('indica_avaliacao_id', $avaliacao->id)
                        ->where('user_id', auth()->user()->id)
                        ->first();
                    if($ultimo){
                        $tempo = $ultimo->created_at->diffInSeconds(Carbon::now());

                        $novo = $ultimo->replicate();
                        $novo->tempo_inativo = $tempo;
                        $novo->tempo_ativo = 0;
                        $novo->created_at = Carbon::now();
                        $novo->updated_at = Carbon::now();
                        $novo->save();
                    }
                }

                return $retorno;
            }else{
                return false;
            }
        }
        catch (\Exception $e){
            dd($e);
            return false;
        }
    }

    private function normalizaTentativa($avaliacao_id){
        $tentativas = AvaliacaoTentativas::where('indica_avaliacao_id',$avaliacao_id)
            ->where('user_id', auth()->user()->id)
            ->where('instituicao_id', auth()->user()->instituicao_id)
            ->where('escola_id', auth()->user()->escola_id)->get();
        $ultimaTentativa = $tentativas->last();
        if($ultimaTentativa->iniciou == 0){
            AvaliacaoTentativas::create([
                'indica_avaliacao_id'   => $avaliacao_id,
                'user_id'               => auth()->user()->id,
                'instituicao_id'        => auth()->user()->instituicao_id,
                'escola_id'             => auth()->user()->escola_id,
                'iniciou'               => 1,
            ]);
        }
    }

    public function addLogGeral($request){
        try {

            $dados = (array) $request['obj'];
            $dados['user_id'] = auth()->user()->id;
            $dados['indica_avaliacao_id'] = $request['avaliacao_id'];
            $log = AvaliacaoLogGeral::orderBy('id')
                ->where('indica_avaliacao_id', $dados['indica_avaliacao_id'])
                ->where('user_id', auth()->user()->id)
                ->first();
            if(!$log){
                $dados['ordem_questao'] = serialize($dados['ordem_questao']);
                $dados['token'] = auth()->user()->id.Str::random(15).$dados['indica_avaliacao_id'];
            }else{
                $dados['ordem_questao'] = ' ';
            }
            $log = new AvaliacaoLogGeral($dados);
            $log->save();

            $this->normalizaTentativa($request['avaliacao_id']);

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
            $dados['indica_avaliacao_id'] = $request['avaliacao_id'];
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
            $log = AvaliacaoLogAtividade::where('indica_avaliacao_id', $dados['indica_avaliacao_id'])->where('user_id', $dados['user_id'])->where('indica_questao_id', $dados['indica_questao_id'])->first();
            if($log){
                $log->update($dados);
            }else{
                $log = new AvaliacaoLogAtividade($dados);
                $log->save();
            }

            $this->normalizaTentativa($dados['indica_avaliacao_id']);
            return true;
        }
        catch (\Exception $e){
            return false;
        }
    }

    public function finalizar($request){

        try {
            $dados['user_id'] = auth()->user()->id;
            $dados['indica_avaliacao_id'] = $request['avaliacao_id'];
            $dados['indica_questao_id'] = $request['questao_ativa'];
            $dados['tempo_ativo'] = $request['tempo'];
            $dados['iniciou_ativo'] = $request['data_hora'];
            $dados['finalizado'] = 1;
            $log = new AvaliacaoLogGeral($dados);
            $log->save();
            AvaliacaoTentativas::create([
                'indica_avaliacao_id'   => $request['avaliacao_id'],
                'user_id'               => auth()->user()->id,
                'instituicao_id'        => auth()->user()->instituicao_id,
                'escola_id'             => auth()->user()->escola_id,
                'iniciou'               => 2,
            ]);
            $this->totalizarPlacar(auth()->user()->id,$request['avaliacao_id'],$request);

            return true;
        }
        catch (\Exception $e){
            return false;
        }
    }

    public function confereTotalizados($idAvaliacao, $tempoMaximo){
        $dados = AvaliacaoLogGeral::where('avaliacao_id',$idAvaliacao)
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
            $log = AvaliacaoLogAtividade::where('user_id',$userId)->where('indica_avaliacao_id',$avaliacaoId)->get();
            if(!$log){
                $dados = [
                    'user_id' => 0,
                    'avaliacao_id' => 0,
                    'porcentagem_acerto' => 0,
                    'porcentagem_erro' => 0,
                    'qtd_em_branco' => 0,
                    'qtd_acerto' => 0,
                    'qtd_erro' => 0,
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
                $logGeral = AvaliacaoLogGeral::where('user_id',$userId)->where('indica_avaliacao_id',$avaliacaoId)->whereNotNull('ordem_questao')->first();
                $token = $logGeral->token;
                if(isset($logGeral->ordem_questao) && $logGeral->ordem_questao != ''){
                    $ordemQ = unserialize($logGeral->ordem_questao);
                    foreach ($ordemQ as $o){
                        $ordemQuestao[$o] = '';
                    }
                }
                foreach ($questoes as $q){
                    $ordemQuestao[$q->id] = $q;
                }
                //// totalizadores
                $l = [];
                foreach ($log as $lo) {
                    $l[$lo->indica_questao_id] = $lo;
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
                        $tempoLog = AvaliacaoLogAtividade::where('user_id',$userId)->where('indica_avaliacao_id',$avaliacaoId)->where('indica_questao_id',$q->id)->first();
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
                            $ordemQuestao[$q->id]->eh_correto = 1;
                        }else{
                            $ordemQuestao[$q->id]->eh_correto = 0;
                        }
                        if(!isset($l[$q->id]) ){
                            $totalEmBranco++;
                        }
                        $totalCorrigidas ++;

                    }
                    else{
                        if(isset($l[$q->id]) && $l[$q->id]->corrigida == 1){ //// corrigida como correto
                            $totalCorretas++;
                            $totalCorrigidas ++;
                            $ordemQuestao[$q->id]->eh_correto = 1;
                        }elseif(!isset($l[$q->id]) || $l[$q->id]->corrigida == 2){ //// corrigida como incorreto
                            $totalCorrigidas ++;
                            $ordemQuestao[$q->id]->eh_correto = 0;
                        }elseif(isset($l[$q->id]) ){
                            $ordemQuestao[$q->id]->eh_correto = '';
                            $totalParaAvaliar++;
                        }
                        if(!isset($l[$q->id]) ){
                            $totalEmBranco++;
                            $ordemQuestao[$q->id]->eh_correto = 0;
                        }
                    }
                }
                $dados = [
                    'user_id' => $userId,
                    'indica_avaliacao_id' => $avaliacaoId,
                    'porcentagem_acerto' => 0,
                    'porcentagem_erro' => 100,
                    'qtd_em_branco' => $totalEmBranco,
                    'qtd_acerto' => $totalCorretas,
                    'qtd_erro' => $totalCorrigidas - $totalCorretas,
                    'qtd_questao_para_avaliar' => $totalParaAvaliar,
                    'qtd_questoes_total' => $totalQuestoes,
                    'qtd_questoes_respondida' => ($totalCorrigidas+$totalParaAvaliar) - $totalEmBranco,
                    'tempo_ativo' => gmdate("H:i:s", $tempo_ativo),
                    'tempo_inativo' => gmdate("H:i:s", $tempo_inativo),
                    'tempo_total' => gmdate("H:i:s", $tempo_total),
                    ];

                if($totalCorrigidas != 0)
                {
                    $dados['porcentagem_acerto'] = (int)(($totalCorretas*100)/($totalCorrigidas));
                    $dados['porcentagem_erro'] = 100 - ((int)(($totalCorretas*100)/($totalCorrigidas)));
                }
            }
            $dados['questoes'] = serialize($ordemQuestao);

            $logExtra = AvaliacaoLogAtividade::where('user_id',$userId)->where('indica_avaliacao_id',$avaliacaoId)->first();
            $dados['instituicao_id'] = $logExtra->instituicao_id;
            $dados['escola_id'] = $logExtra->escola_id;
            $dados['turma_id'] = $logExtra->turma_id;
            $dados['matricula'] = $logExtra->matricula;

            $tentativas = AvaliacaoTentativas::where('user_id',$userId)->where('indica_avaliacao_id',$avaliacaoId)->get();

            $dados['qtd_tentativas'] = $tentativas->where('iniciou',1)->count();
            $tempoFechado = 0;
            $tempoAberto = 0;
            $i =0;
            foreach ($tentativas as $t){
                if($t->iniciou == 1){
                    if(isset($tentativas[$i+1]) && $tentativas[$i+1]->iniciou == 0){
                        $tempoAberto += $t->created_at->diffInSeconds($tentativas[$i+1]->created_at);

                    }
                }else{
                    if(isset($tentativas[$i+1]) && ($tentativas[$i+1]->iniciou == 1 || $tentativas[$i+1]->iniciou == 2)){
                        $tempoFechado += $t->created_at->diffInSeconds($tentativas[$i+1]->created_at);
                    }
                }
                $i++;
            }

            $dados['tempo_janela_aberta']       = gmdate('H:i:s',$tempoAberto);
            $dados['tempo_janela_fechada']      = gmdate('H:i:s',$tempoFechado);
            $dados['tempo_total_tentativas']    = gmdate('H:i:s',$tempoAberto+$tempoFechado);
            $dados['token'] = $token;
            $placar = new AvaliacaoPlacar($dados);
            $placar->save();

            //AvaliacaoLogAtividade::where('user_id',$userId)->where('indica_avaliacao_id',$avaliacaoId)->delete();
            //AvaliacaoLogGeral::where('user_id',$userId)->where('indica_avaliacao_id',$avaliacaoId)->delete();
            DB::commit();
            return true;
        }
        catch (\Exception $e){
            DB::rollback();
            return false;
        }
    }

    public function getResultadoAluno($avaliacaoId){
        $avaliacao = Avaliacao::with(['disciplina', 'usuario'])
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
                });
            })
            ->find($avaliacaoId);
        if($avaliacao){
            $placar = AvaliacaoPlacar::where('user_id',auth()->user()->id)->where('indica_avaliacao_id',$avaliacaoId)->first();

            if(!$placar){
                $this->totalizarPlacar(auth()->user()->id,$avaliacaoId);
                $placar = AvaliacaoPlacar::where('user_id',auth()->user()->id)->where('indica_avaliacao_id',$avaliacaoId)->first();
            }
            return [
                'avaliacao' => $avaliacao,
                'placar' => $placar,
            ];
        }else{
            return false;
        }
    }

    public function relatorio($avaliacaoId, $paginado = null ){
        try {
            $avaliacao = Avaliacao::where('publicado', 1)
                ->with('permissao')
                ->find($avaliacaoId);

            $relatorio = User::with(['turmaDeAlunos'=>function($query) use($avaliacao){
                        $query->join('escolas','escolas.id', 'fr_turmas.escola_id');
                        $query->join('instituicao','instituicao.id', 'escolas.instituicao_id');
                        $query->leftJoin('user_permissao', function($join){
                            $join->on('user_permissao.user_id', DB::raw('fr_turma_aluno.aluno_id'));
                            $join->on('user_permissao.escola_id', DB::raw('escolas.id'));
                            $join->on('user_permissao.instituicao_id', DB::raw('escolas.instituicao_id'));
                            $join->on('user_permissao.permissao', DB::raw("'A'"));
                        });
                        $query->where('fr_turmas.ciclo_etapa_id', $avaliacao->cicloetapa_id);
                        $query->where(function($qq) use($avaliacao){
                            foreach($avaliacao->permissao as $p){
                                $qq->orWhere(function($q) use($p){
                                    if($p->instituicao_id != 0){
                                        $q->where('escolas.instituicao_id', $p->instituicao_id );
                                    }
                                    if($p->escola_id != 0){
                                        $q->where('fr_turmas.escola_id', $p->escola_id );
                                    }
                                });
                            }
                        });
                        $query->selectRaw('fr_turmas.*, escolas.titulo as escola, escolas.cidade as cidade, instituicao.titulo as instituicao, instituicao.instituicao_tipo_id, user_permissao.matricula');

                    }])
                    ->whereHas('turmaDeAlunos', function($query) use($avaliacao){
                        $query->join('escolas','escolas.id', 'fr_turmas.escola_id');
                        $query->where('fr_turmas.ciclo_etapa_id', $avaliacao->cicloetapa_id);
                        $query->where(function($qq) use($avaliacao){
                            foreach($avaliacao->permissao as $p){
                                $qq->orWhere(function($q) use($p){
                                    if($p->instituicao_id != 0){
                                        $q->where('escolas.instituicao_id', $p->instituicao_id );
                                    }
                                    if($p->escola_id != 0){
                                        $q->where('fr_turmas.escola_id', $p->escola_id );
                                    }
                                });
                            }
                        });
                    })
                    ->with(['placarIndica'=>function($q)use($avaliacao){
                        $q->where('indica_avaliacao_id',$avaliacao->id);
                    }])
                    ->with(['logAtividadeIndica'=>function($q)use($avaliacao){
                        $q->where('indica_avaliacao_id',$avaliacao->id);
                    }])
                    ->with(['logGeralIndica'=>function($q)use($avaliacao){
                        $q->where('indica_avaliacao_id',$avaliacao->id);
                    }])
                    ->with(['tentativasIndica'=>function($qq)use($avaliacao){
                        $qq->where('indica_avaliacao_id',$avaliacao->id);
                        $qq->where('iniciou',1);
                    }]);
            if($paginado){
                $relatorio = $relatorio->paginate(10);
            }else{
                $relatorio = $relatorio->get();
            }

            $retorno = $this->preparaRelatorioAdm($avaliacao, $relatorio);
            $retorno['relatorio'] = $relatorio;
            $retorno['avaliacao'] = $avaliacao;
            return $retorno;

        }
        catch (\Exception $e){
            dd($e);
            return false;
        }
    }


    /*
     * FUNCAO RELATORIO ANTIGO (_OLD)
     *
    public function relatorio($avaliacaoId){
        try {
            $avaliacao = Avaliacao::where('publicado',1)->find($avaliacaoId);
            $placar =  AvaliacaoPlacar::where('indica_avaliacao_id',$avaliacao->id)
                ->with(['usuario'=>function($q){
                    $q->with(['turmaDeAlunos'=>function($qq){
                        $qq->with(['escola'=>function($qqq){
                            $qqq->with('instituicao');
                        }]);
                    }]);
                    $q->with(['tentativasIndica'=>function($qq){
                        $qq->where('iniciou',0);
                    }]);
                }])
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
*/
    public function relatorio_ocorrencia($avaliacaoId){
        try {
            return  User::with(['placarIndica'=>function($q) use($avaliacaoId){
                        $q->where('indica_avaliacao_id', $avaliacaoId);
                    }])
                ->whereHas('placarIndica', function($q) use($avaliacaoId){
                    $q->where('indica_avaliacao_id', $avaliacaoId);
                    $q->where('indica_avaliacao_placar.user_id', DB::raw('users.id'));
                })
                ->with('tentativasIndica')
                ->with('escola')
                ->with('instituicaoObj')
                    ->whereHas('tentativasIndica', function($q) use($avaliacaoId){
                        $q->where('indica_avaliacao_id', $avaliacaoId);
                        $q->where('iniciou', 0);
                    })->orderBy('name')
                    ->get();
        }
        catch (\Exception $e){
            dd($e);
            return [];
        }
    }

    public function relatorio_ocorrencia_detalhes($avaliacaoId, $alunoId){
        try {
            return  User::with(['placarIndica'=>function($q) use($avaliacaoId){
                $q->where('indica_avaliacao_id', $avaliacaoId);
            }])
                ->with(['tentativasIndica'=>function($q) use($avaliacaoId){
                $q->where('indica_avaliacao_id', $avaliacaoId);
            }])
                ->with('escola')
                ->with('instituicaoObj')
                ->whereHas('tentativasIndica', function($q) use($avaliacaoId){
                    $q->where('indica_avaliacao_id', $avaliacaoId);
                })->orderBy('name')
                ->find($alunoId);
        }
        catch (\Exception $e){
            dd($e);
            return null;
        }
    }


    private function preparaRelatorioAdm($avaliacao, $usuarios){
        /// disciplinas
        if($avaliacao->disciplina_id == 14){
            $disciplina=1;
        }elseif($avaliacao->disciplina_id == 12){
            $disciplina=2;
        }elseif($avaliacao->disciplina_id == 4){
            $disciplina=3;
        }else{
            $disciplina = 'não definida no código do indica';
        }
        //// questoes
        $perguntas = unserialize($avaliacao->perguntas);
        $ordemPerguntas = [];
        foreach($perguntas as $p){
            $ordemPerguntas[$p->id] = '';
        }
        $questoesAlunos = [];
        $letras = $this->letrasQuestao();
        foreach($usuarios as $u){
            $placar = $u->placarIndica;
            if($placar){
                $questoesAlunos[$u->id] = $ordemPerguntas;
                $questoes = unserialize($placar->questoes);
                foreach($questoes as $q){
                    $questoesAlunos[$u->id][$q->id] = @$letras[$q->resposta];
                }
            }elseif(count($u->logAtividadeIndica)>0){
                $questoesAlunos[$u->id] = $ordemPerguntas;
                foreach($u->logAtividadeIndica as $q){
                    $questoesAlunos[$u->id][$q->indica_questao_id] = @$letras[$q->resposta];
                }
            }

        }


        return [
            'ordemPerguntas' => $ordemPerguntas,
            'questoesAlunos' => $questoesAlunos,
            'disciplina' => $disciplina,
        ];
    }

        /*
     * FUNCAO RELATORIO ANTIGO (_OLD)
     *
    private function preparaRelatorioAdm($placar, $avaliacao){
        /// disciplinas
        if($avaliacao->disciplina_id == 14){
            $disciplina=1;
        }elseif($avaliacao->disciplina_id == 12){
            $disciplina=2;
        }elseif($avaliacao->disciplina_id == 4){
            $disciplina=3;
        }else{
            $disciplina = 'não definida no código do indica';
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
                $questoesAlunos[$p->user_id][$q->id] = @$letras[$q->resposta];
            }
        }

        return [
            'ordemPerguntas' => $ordemPerguntas,
            'questoesAlunos' => $questoesAlunos,
            'disciplina' => $disciplina,
        ];
    }
*/
    private function letrasQuestao(){
        return [
            '' => '',
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

    public function relatorioAdmDownload($dados, $questoesAlunos, $ordemPerguntas, $disciplina, $avaliacao){
        $vetCiclo = $this->cicloEtapaAno();

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
        $ret[] = 'aplicado em';
        $ret[] = 'caderno';
        $ret[] = 'email';
        $i =1;
        foreach($ordemPerguntas as $p){
            $ret[]='Item_'.$i;
            $i++;
        }
        $ret[] = 'status';
        $ret[] = 'iniciado em';
        $ret[] = 'tentativas';
        $ret[] = 'tempo fechada';
        $ret[] = 'tempo aberta';
        $ret[] = 'tempo total';
        $ret[] = 'token';
        $csv[]=$ret;

        foreach($dados as $d){
            foreach($d->turmaDeAlunos as $t) {
                $ret = [];
                $ret[] = $d->id;
                $ret[] = $avaliacao->id;
                $ret[] = $disciplina;
                if ($t->instituicao_tipo_id==2) {
                    $ret[] = $t->cidade;
                } else {
                    $ret[] = $t->instituicao;
                }
                $ret[] = $t->escola;
                $ret[] = $vetCiclo[$t->ciclo_etapa_id];
                $ret[] = $t->titulo;
                $ret[] = $t->turno;
                if($t->matricula == ''){
                    $ret[] = @$d->matricula;
                }else{
                    $ret[] = @$t->matricula;
                }
                $ret[] = $d->nome_completo;
                if ($d->data_nascimento != '') {
                    $ret[] = $d->data_nascimento->format('d/m/Y');
                } else {
                    $ret[] = '';
                }
                if ($d->data_nascimento != '') {
                    $ret[] = \Carbon\Carbon::parse($d->data_nascimento)->age;
                } else {
                    $ret[] = '';
                }
                $ret[] = $d->genero;
                $ret[] = Str::of($avaliacao->data_hora_inicial)->explode('-')[0];
                $ret[] = $avaliacao->caderno;
                $ret[] = $d->email;
                if(isset($questoesAlunos[$d->id])){
                    foreach ($questoesAlunos[$d->id] as $p) {
                        $ret[] = $p;
                    }
                }else{
                    foreach($ordemPerguntas as $p){
                        $ret[]='';
                    }
                }

                if($d->placarIndica){
                    $ret[] = 'Finalizada';
                }elseif(count($d->logAtividadeIndica)>0 || count($d->logGeralIndica)>0){
                    $ret[] = 'Iniciada';
                }else{
                    $ret[] = 'Não iniciada';
                }
                if(count($d->tentativasIndica)>0){
                    $ret[] = $d->tentativasIndica[0]->created_at->format('d/m/Y');
                }else{
                    $ret[] = '';
                }
                $ret[] = count($d->tentativasIndica);
                $ret[] = gmdate("H:i:s", $d->logGeralIndica->sum('tempo_inativo'));
                $ret[] = gmdate("H:i:s",$d->logGeralIndica->sum('tempo_ativo'));
                $ret[] = gmdate("H:i:s",$d->logGeralIndica->sum('tempo_inativo') + $d->logGeralIndica->sum('tempo_ativo'));
                if($d->placarIndica){
                    $ret[] = $d->placarIndica->token;
                }elseif(count($d->logGeralIndica)>0){
                    $ret[] = $d->logGeralIndica[0]->token;
                }

                $csv[] = $ret;
            }
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
}
