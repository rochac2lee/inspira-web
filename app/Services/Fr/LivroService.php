<?php
namespace App\Services\Fr;
use App\Models\ColecaoLivroInstituicao;
use App\Models\ColecaoLivroInstituicaoPermissao;
use App\Models\ColecaoLivroInstituicaoPermissaoPeriodo;
use Illuminate\Support\Facades\Auth;
use DB;

use App\Models\Conteudo;
use App\Models\ColecaoLivros;
use App\Models\ColecaoLivroEscola;
use App\Models\ColecaoLivroEscolaPermissao;
use App\Models\ColecaoLivroEscolaPermissaoPeriodo;

class LivroService {

	public function livrosParaTrilhas($idEscola, $colecao)
	{
        $sql = $this->scopoQueryLivro($colecao, $idEscola);
		return $sql->selectRaw("conteudos.*,'Editora Opet' as nome_completo")
					->get();
	}

	public function scopoQueryLivro($idColecao, $idEscola = null, $busca = null)
    {
        $user = Auth::user();

        // Determinando escola padrão, caso não seja especificado
        if (empty($idEscola)) {
            $idEscola = $user->escola_id;
        }

        // Inicializando a query base
        $sql = Conteudo::query()
            ->where('conteudos.tipo', 21);

        // Filtrando por coleção de livros
        if (!empty($idColecao)) {
            $sql->where('colecao_livro_id', $idColecao);
        }

        // Filtro por busca
        if (!empty($busca)) {
            $sql->join('ciclo_etapas', 'ciclo_etapas.id', '=', 'conteudos.cicloetapa_id')
                ->join('disciplinas', 'disciplinas.id', '=', 'conteudos.disciplina_id')
                ->join('ciclos', 'ciclos.id', '=', 'ciclo_etapas.ciclo_id')
                ->where(function ($q) use ($busca) {
                    $q->whereRaw("MATCH(conteudos.titulo, conteudos.apoio, conteudos.full_text) AGAINST(? IN BOOLEAN MODE)", [$busca]);
                });
        }

        // Filtrando permissões
        $permissao = ColecaoLivroEscola::with([
            'permissao' => function ($q) use ($idEscola) {
                $q->where('escola_id', $idEscola)->selectRaw('cicloetapa_id, colecao_id');
            },
            'permissaoPeriodo' => function ($q) use ($idEscola) {
                $q->where('escola_id', $idEscola)->selectRaw('periodo, colecao_id');
            }
        ])
        ->where('escola_id', $idEscola);

        // Adicionando condição de filtro por coleção
        if (!empty($idColecao)) {
            $permissao->where('colecao_id', $idColecao);
        }

        $permissaoTotal = $permissao->get();

        if ($permissaoTotal->isNotEmpty()) {
            $sql->where(function ($qGeral) use ($permissaoTotal) {
                foreach ($permissaoTotal as $permissao) {
                    $qGeral->orWhere(function ($qFor) use ($permissao) {
                        $qFor->where('colecao_livro_id', $permissao->colecao_id);

                        if ($permissao->todos != 1) {
                            $cicloetapa = $permissao->permissao->pluck('cicloetapa_id')->toArray();
                            $qFor->whereIn('cicloetapa_id', $cicloetapa);
                        }

                        if ($permissao->todos_periodos != 1) {
                            $periodos = $permissao->permissaoPeriodo->pluck('periodo')->toArray();
                            $qFor->where(function ($q) use ($periodos) {
                                $q->whereIn('periodo', $periodos)->orWhereNull('periodo');
                            });
                        }
                    });
                }
            });
        } else {
            $sql->where('conteudos.id', 0); // Nenhum resultado
        }

        // Filtrando por tipo de livro
        if (in_array($user->permissao, ['A', 'R'])) {
            $sql->where(function ($q) {
                $q->where('tipo_livro', 'AA')->orWhere('tipo_livro', 'A');
            });
        } else {
            $sql->where(function ($q) {
                $q->whereIn('tipo_livro', ['AP', 'A', 'AA', 'P']);
            });
        }

        // Ajustando ordenação para Volume e Unidade
        $sql->orderByRaw("CASE
                            WHEN conteudos.titulo LIKE '%Volume%' THEN 1
                            WHEN conteudos.titulo LIKE '%Unidade%' THEN 2
                            ELSE 3
                        END")
            ->orderByRaw("SUBSTRING_INDEX(conteudos.titulo, ' - ', 1)") // Ordenação por primeiro termo antes do hífen
            ->orderByRaw("CAST(SUBSTRING(conteudos.titulo, LOCATE('Volume ', conteudos.titulo) + 7, LOCATE(' - ', conteudos.titulo, LOCATE('Volume ', conteudos.titulo)) - LOCATE('Volume ', conteudos.titulo) - 7) AS UNSIGNED)") // Ordenação por volume
            ->orderByRaw("CAST(SUBSTRING(conteudos.titulo, LOCATE('Unidade ', conteudos.titulo) + 8, LOCATE(' - ', conteudos.titulo, LOCATE('Unidade ', conteudos.titulo)) - LOCATE('Unidade ', conteudos.titulo) - 8) AS UNSIGNED)"); // Ordenação por unidade

        return $sql;
    }
    
	public function livroColecao($idColecao, $request)
	{
		$sql = $this->scopoQueryLivro($idColecao);

        if($request->input('id')!=''){
            $sql->where('conteudos.id',$request->input('id'));
        }

		if($request->input('componente')!=''){
		    $dis = $request->input('componente');
			$sql->where(function($q) use ($dis){
			    $q->orWhere('disciplina_id',$dis);
			    $q->orWhere('disciplina_id',1);
            });
		}
		if($request->input('etapa')!=''){
            $sql->where('ciclo_id',$request->input('etapa'));
		}
		if ($request->input('ano') != '') {
            $ano = $request->input('ano');
            $sql->where('cicloetapa_id', $ano);
        }        

        if($request->input('material')!=''){
            $sql->where('tipo_livro',$request->input('material'));
        }

        if($request->input('periodo')!=''){
            $sql->where('periodo',$request->input('periodo'));
        }

		if($request->input('texto')!=''){
			$sql->where('titulo','LIKE','%'.DB::raw($request->input('texto')).'%');
		}

		$sql = $sql->selectRaw('conteudos.*')
			->orderBy('cicloetapa_id')
			->orderBy('disciplina_id')
            ->orderBy('titulo')
            ->orderBy('tipo_livro')
			->paginate(20);

		return $sql;
	}

	public function colecoes($etapa = null)
	{
		$user = Auth::user();
		$idEscola = $user->escola_id;

		$retorno =  ColecaoLivros::join('colecao_livro_escola','colecao_livros.id','colecao_livro_escola.colecao_id')
							->where('escola_id',$idEscola);
		if($user->permissao == 'A' || $user->permissao == 'R')
		{
			$retorno = $retorno->where('aluno',1);
		}
		if($etapa != ''){
            $conteudo = Conteudo::where('tipo',21)
                ->where('ciclo_id',$etapa)
                ->groupBy('colecao_livro_id')
                ->selectRaw('colecao_livro_id')
                ->get()->pluck('colecao_livro_id');
            $retorno = $retorno->whereIn('colecao_livros.id', $conteudo);
        }
		return $retorno->selectRaw('colecao_livros.*')
							->orderBy('ordem')
							->orderBy('id')
							->paginate(10);
	}


	public function defineMenuPesquisa($idColecao, $request)
    {
        // Inicializa o retorno com as etapas e disciplinas
        $retorno = [
            'etapas' => $this->etapasColecao($idColecao),
            'anos' => $this->anoLivro($idColecao),
            'disciplinas' => $this->disciplinasColecao($idColecao, $request->input('etapa'), $request->input('ano')),
            'tipo_livro' => $this->tipoLivro($idColecao)
        ];

        // Verifica se foi passada uma etapa, para determinar os anos disponíveis
        if ($request->filled('etapa')) {
            $retorno['anos'] = $this->anosColecao($idColecao, $request->input('etapa'));
        }

        // Verifica a existência de conteúdo para cada período
        for ($i = 1; $i <= 24; $i++) {
            $retorno['periodo'.$i] = Conteudo::where('tipo', 21)
                ->where('colecao_livro_id', $idColecao)
                ->where('periodo', $i)
                ->exists();
        }

        // Verifica se o tipo de livro selecionado é 'P' ou 'AP'
        $tipoLivroSelecionado = $request->input('material');
        if ($tipoLivroSelecionado === 'P' || $tipoLivroSelecionado === 'AP') {
            // Limpa o parâmetro 'periodo'
            $request->merge(['periodo' => null]);
        }

        return $retorno;
    }

    public function tipoLivro($idColecao)
    {
        // Obtenha os tipos de livro para a coleção especificada
        $tiposLivro = Conteudo::where('tipo', 21)
            ->where('colecao_livro_id', $idColecao)
            ->pluck('tipo_livro')
            ->unique()
            ->toArray();

        // Retorna apenas os tipos de livro
        return $tiposLivro;
    }    

    public function anoLivro($idColecao) {
        // Obtenha os anos de livro para a coleção especificada
        $anosLivros = Conteudo::where('tipo', 21)
            ->where('colecao_livro_id', $idColecao)
            ->join('ciclo_etapas', 'ciclo_etapas.id', 'conteudos.cicloetapa_id')
            ->distinct()
            ->select('ciclo_etapas.id', 'ciclo_etapas.titulo')
            ->get();
    
        // Retorna os anos de livro como um array
        return $anosLivros->toArray();
    }
    
    

	private function etapasColecao($idColecao)
	{
		$sql = $this->scopoQueryLivro($idColecao);

		return 	$sql->join('ciclos','ciclos.id','conteudos.ciclo_id')
					->groupBy('ciclos.id')
					->selectRaw('ciclos.*')
					->get();
	}

	private function anosColecao($idColecao, $idEtapa)
	{
        
		$sql = $this->scopoQueryLivro($idColecao);

		return 	$sql->join('ciclo_etapas','ciclo_etapas.id','conteudos.cicloetapa_id')
					->where('conteudos.ciclo_id',$idEtapa)
					->groupBy('ciclo_etapas.id')
					->selectRaw('ciclo_etapas.*')
					->get();
	}

	private function disciplinasColecao($idColecao, $idEtapa, $idAno)
	{
		$sql = $this->scopoQueryLivro($idColecao);

		return 	$sql->join('disciplinas','disciplinas.id','conteudos.disciplina_id')
					->groupBy('disciplinas.id')
					->selectRaw('disciplinas.*')
					->orderBy('disciplinas.titulo')
                    ->get();
	}

	public function getLivro($idLivro)
	{
        try
        {
			$livro = Conteudo::find($idLivro);
			$sql = $this->scopoQueryLivro($livro->colecao_livro_id);
			return $sql->where('conteudos.id',$idLivro)->selectRaw('conteudos.*')->first();
		}
        catch (\Exception $e)
        {
            return null;
        }
	}
/*
/*   Funções para gerenciar livros nas INSTITUICOES
/*
/*
*/
    public function colecaoNaInstituicao($idInsti,$idColecao = null,$pagina=true)
    {
        $retorno =  ColecaoLivroInstituicao::join('colecao_livros','colecao_livros.id','colecao_livro_instituicao.colecao_id')
            ->with(['cicloEtapaColecao' => function($q) use($idInsti){
                $q->leftJoin('colecao_livro_instituicao_permissao', function($qq) use($idInsti){
                    $qq->on('colecao_livro_instituicao_permissao.instituicao_id',DB::raw($idInsti))
                        ->on('colecao_livro_instituicao_permissao.colecao_id','conteudos.colecao_livro_id')
                        ->on('colecao_livro_instituicao_permissao.cicloetapa_id','conteudos.cicloetapa_id');
                });

            }])
            ->with(['periodoColecao' => function($q) use($idInsti){
                $q->leftJoin('colecao_livro_instituicao_permissao_periodo', function($qq) use($idInsti){
                    $qq->on('colecao_livro_instituicao_permissao_periodo.instituicao_id',DB::raw($idInsti))
                        ->on('colecao_livro_instituicao_permissao_periodo.colecao_id','conteudos.colecao_livro_id')
                        ->on('colecao_livro_instituicao_permissao_periodo.periodo','conteudos.periodo');
                });

            }])
            ->where('instituicao_id',$idInsti)
            ->selectRaw('colecao_livros.*, colecao_livro_instituicao.todos, colecao_livro_instituicao.todos_periodos');
        if($idColecao == null)
        {
            if($pagina)
            {
                $retorno = $retorno->paginate(10);
            }
            else{
                $retorno = $retorno->get();
            }
        }
        else
        {
            $retorno = $retorno->where('colecao_livros.id',$idColecao)
                ->first();
        }
        return $retorno;

    }

    public function colecaoForaDaInstituicao($idInst)
    {
        $conteudos =  ColecaoLivros::join('colecao_livro_instituicao','colecao_livros.id','colecao_livro_instituicao.colecao_id')
            ->where('instituicao_id',$idInst)
            ->selectRaw('colecao_livros.id')
            ->get();

        $conteudos = $conteudos->toArray();

        return ColecaoLivros::whereNotIn('id',$conteudos)->where('tipo',21)->orderBy('ordem')->get();
    }

    public function removerColecaoInstituicao($idInsti,$idColecao)
    {
        DB::beginTransaction();
        try
        {
            ColecaoLivroInstituicaoPermissao::where('instituicao_id',$idInsti)->where('colecao_id',$idColecao)->delete();
            ColecaoLivroInstituicao::where('instituicao_id',$idInsti)->where('colecao_id',$idColecao)->delete();

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return $e;
        }

    }
    public function addColecaoInstituicao($dados)
    {
        DB::beginTransaction();
        try
        {
            foreach($dados['colecao'] as $c){
                ColecaoLivroInstituicao::firstOrCreate([
                    'colecao_id'    => $c,
                    'instituicao_id'=> $dados['instituicao_id'],
                    'todos'         => '1',
                    'todos_periodos'=> '1',
                ]);
            }

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return $e;
        }

    }

    public function permissaoColecaoInstituicao($dados)
    {
        DB::beginTransaction();
        try
        {
            $this->updatePermissaoEtapaAnoInstituicao($dados);
            $this->updatePermissaoPeriodoInstituicao($dados);

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            dd($e);
            return $e;
        }
    }

    private function updatePermissaoEtapaAnoInstituicao($dados)
    {
        if(! isset($dados['cicloetapa']))
        {
            $dados['cicloetapa'] = [];
        }

        $colecao = $this->colecaoNaInstituicao($dados['instituicao_id'],$dados['colecao_id']);
        $totalColecao = count($colecao->cicloEtapaColecao);

        ColecaoLivroInstituicaoPermissao::where('colecao_id',$dados['colecao_id'])->where('instituicao_id',$dados['instituicao_id'])->delete();

        if($totalColecao==count($dados['cicloetapa']))
        {
            $todos = '1';
        }
        else
        {
            $a = $dados['cicloetapa'];
            $todos = '0';
            foreach ($a as $d) {
                ColecaoLivroInstituicaoPermissao::create([
                    'colecao_id'    => $dados['colecao_id'],
                    'instituicao_id'     => $dados['instituicao_id'],
                    'cicloetapa_id' => $d,
                ]);
            }
        }

        DB::select('update colecao_livro_instituicao set todos = '.$todos.' where colecao_id= '.$dados['colecao_id'].' and instituicao_id ='.$dados['instituicao_id']);

    }

    private function updatePermissaoPeriodoInstituicao($dados)
    {
        if(! isset($dados['periodo']))
        {
            $dados['periodo'] = [];
        }

        $colecao = $this->colecaoNaInstituicao($dados['instituicao_id'],$dados['colecao_id']);
        $totalColecao = count($colecao->periodoColecao);


        ColecaoLivroInstituicaoPermissaoPeriodo::where('colecao_id',$dados['colecao_id'])->where('instituicao_id',$dados['instituicao_id'])->delete();

        if($totalColecao==count($dados['periodo']))
        {
            $todos = '1';
        }
        else
        {
            $a = $dados['periodo'];
            $todos = '0';
            foreach ($a as $k => $v) {
                ColecaoLivroInstituicaoPermissaoPeriodo::create([
                    'colecao_id'    => $dados['colecao_id'],
                    'instituicao_id'     => $dados['instituicao_id'],
                    'periodo' => $v,
                ]);
            }
        }

        DB::select('update colecao_livro_instituicao set todos_periodos = '.$todos.' where colecao_id= '.$dados['colecao_id'].' and instituicao_id ='.$dados['instituicao_id']);

    }

/*
/*   Funções para gerenciar livros nas escolas
/*
/*
*/

	public function colecaoNaEscola($idEscola,$idColecao = null,$pagina=true)
	{
		$retorno =  ColecaoLivroEscola::join('colecao_livros','colecao_livros.id','colecao_livro_escola.colecao_id')
							->with(['cicloEtapaColecao' => function($q) use($idEscola){
    							$q->leftJoin('colecao_livro_escola_permissao', function($qq) use($idEscola){
			    					$qq->on('colecao_livro_escola_permissao.escola_id',DB::raw($idEscola))
			    					  ->on('colecao_livro_escola_permissao.colecao_id','conteudos.colecao_livro_id')
			    					  ->on('colecao_livro_escola_permissao.cicloetapa_id','conteudos.cicloetapa_id');
			    				});

							}])
							->with(['periodoColecao' => function($q) use($idEscola){
    							$q->leftJoin('colecao_livro_escola_permissao_periodo', function($qq) use($idEscola){
			    					$qq->on('colecao_livro_escola_permissao_periodo.escola_id',DB::raw($idEscola))
			    					  ->on('colecao_livro_escola_permissao_periodo.colecao_id','conteudos.colecao_livro_id')
			    					  ->on('colecao_livro_escola_permissao_periodo.periodo','conteudos.periodo');
			    				});

							}])
							->where('escola_id',$idEscola)
							->selectRaw('colecao_livros.*, colecao_livro_escola.todos, colecao_livro_escola.todos_periodos');
		if($idColecao == null)
		{
			if($pagina)
			{
				$retorno = $retorno->paginate(10);
			}
			else{
				$retorno = $retorno->get();
			}
		}
		else
		{
			$retorno = $retorno->where('colecao_livros.id',$idColecao)
								->first();
		}
		return $retorno;

	}

	public function colecaoForaDaEscola($idEscola)
	{
		$conteudos =  ColecaoLivros::join('colecao_livro_escola','colecao_livros.id','colecao_livro_escola.colecao_id')
							->where('escola_id',$idEscola)
							->selectRaw('colecao_livros.id')
							->get();

		$conteudos = $conteudos->toArray();

		return ColecaoLivros::whereNotIn('id',$conteudos)->where('tipo',21)->orderBy('ordem')->get();
	}

	public function removerColecaoEscola($idEscola,$idColecao)
	{
		DB::beginTransaction();
        try
        {
			ColecaoLivroEscolaPermissao::where('escola_id',$idEscola)->where('colecao_id',$idColecao)->delete();
			ColecaoLivroEscola::where('escola_id',$idEscola)->where('colecao_id',$idColecao)->delete();

	        DB::commit();
	        return true;
	    }
        catch (\Exception $e)
        {
            DB::rollback();
            return $e;
        }

	}

	public function addColecaoEscola($dados)
	{
		DB::beginTransaction();
        try
        {
        	foreach($dados['colecao'] as $c){
				ColecaoLivroEscola::firstOrCreate([
	                'colecao_id'    => $c,
	                'escola_id'     => $dados['escola_id'],
	                'todos'         => '1',
	            ]);
	        }

	        DB::commit();
	        return true;
	    }
        catch (\Exception $e)
        {
            DB::rollback();
            return $e;
        }

	}

	public function permissaoColecaoEscola($dados)
	{
		DB::beginTransaction();
        try
        {
			$this->updatePermissaoEtapaAno($dados);
			$this->updatePermissaoPeriodo($dados);

		    DB::commit();
	        return true;
	    }
        catch (\Exception $e)
        {
            DB::rollback();
            dd($e);
            return $e;
        }

	}

	private function updatePermissaoEtapaAno($dados)
	{
		if(! isset($dados['cicloetapa']))
		{
			$dados['cicloetapa'] = [];
		}

		$colecao = $this->colecaoNaEscola($dados['escola_id'],$dados['colecao_id']);
		$totalColecao = count($colecao->cicloEtapaColecao);

		ColecaoLivroEscolaPermissao::where('colecao_id',$dados['colecao_id'])->where('escola_id',$dados['escola_id'])->delete();

		if($totalColecao==count($dados['cicloetapa']))
		{
			$todos = '1';
		}
		else
		{
			$a = $dados['cicloetapa'];
			$todos = '0';
			foreach ($a as $d) {
				ColecaoLivroEscolaPermissao::create([
	                'colecao_id'    => $dados['colecao_id'],
	                'escola_id'     => $dados['escola_id'],
	                'cicloetapa_id' => $d,
	            ]);
			}
		}

		DB::select('update colecao_livro_escola set todos = '.$todos.' where colecao_id= '.$dados['colecao_id'].' and escola_id ='.$dados['escola_id']);

	}

	private function updatePermissaoPeriodo($dados)
	{
		if(! isset($dados['periodo']))
		{
			$dados['periodo'] = [];
		}

		$colecao = $this->colecaoNaEscola($dados['escola_id'],$dados['colecao_id']);
		$totalColecao = count($colecao->periodoColecao);


		ColecaoLivroEscolaPermissaoPeriodo::where('colecao_id',$dados['colecao_id'])->where('escola_id',$dados['escola_id'])->delete();

		if($totalColecao==count($dados['periodo']))
		{
			$todos = '1';
		}
		else
		{
			$a = $dados['periodo'];
			$todos = '0';
			foreach ($a as $k => $v) {
				ColecaoLivroEscolaPermissaoPeriodo::create([
	                'colecao_id'    => $dados['colecao_id'],
	                'escola_id'     => $dados['escola_id'],
	                'periodo' => $v,
	            ]);
			}
		}

		DB::select('update colecao_livro_escola set todos_periodos = '.$todos.' where colecao_id= '.$dados['colecao_id'].' and escola_id ='.$dados['escola_id']);

	}

}
