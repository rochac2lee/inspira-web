<?php
namespace App\Services\Fr;
use App\Jobs\Questao\FullText;
use App\Jobs\Questao\ListaFullText;
use DB;

use App\Models\FrQuestao;
use App\Models\FrQuestaoTema;
use App\Models\CicloEtapa;
use App\Models\FrBncc;

class QuestaoService {

	public function questoesSelecionadas($selecionados)
	{
		$ordem = implode(',', $selecionados);
		return FrQuestao::where(function($query){

								$query->orWhere('user_id',auth()->user()->id);

								$query->orWhere(function($q){
									$q->where('escola_id',auth()->user()->escola_id)
										->where('compartilhar',1);
								});
								$query->orWhere('instituicao_id',1);
							})
						->with(['usuario','disciplina','bncc','cicloEtapa'])
						->whereIn('id',$selecionados)
						->orderByRaw("FIELD(fr_questao.id , ".$ordem.") ASC")
						->get();
	}

	public function minhasQuestoes($request, $por_pagina = null)
	{
		$ret =  FrQuestao::with(['usuario','disciplina','bncc','cicloEtapa']);
		/// filtros

        /// filtro da aba biblioteca
        if(auth()->user()->permissao == 'Z')
        {
            $ret->where('fr_questao.instituicao_id',1);
            if($request->input('ead')!=1)
            {
                $ret->where('eh_ead',0);
            }else{
                $ret->where('eh_ead',1);
            }
        }
        else
        {
            $ret->where('eh_ead',0);
            if($request->input('full')==1 && (!$request->has('biblioteca') || $request->input('biblioteca') == 'todas')){
                $ret->where(function($query){
                    $query->orWhere(function($q){
                        $q->where('escola_id',auth()->user()->escola_id)
                            ->where('compartilhar',1);
                    })
                    ->orWhere('instituicao_id',1)
                    ->orWhere(function($q){
                        $q->where('fr_questao.user_id',auth()->user()->id)
                            ->where('fr_questao.escola_id',auth()->user()->escola_id);
                    });
                })
                ->where('publicado',1);
            }
            else{
                if($request->input('biblioteca')!=1)
                {
                    $ret->where('fr_questao.user_id',auth()->user()->id)
                        ->where('fr_questao.escola_id',auth()->user()->escola_id);
                }
                else
                {
                    $ret->where(function($query){
                        $query->orWhere(function($q){
                            $q->where('escola_id',auth()->user()->escola_id)
                                ->where('compartilhar',1);
                        });
                        $query->orWhere('instituicao_id',1);
                    })->where('publicado',1);
                }
            }

        }

        if($request->input('id')!=''){
            $ret->where('id', $request->input('id'));
        }

        if($request->input('exibicao')!= '' && $request->input('biblioteca') != 1)
        {
            $ret = $ret->where('publicado', (int) $request->input('exibicao'));
        }

		if($request->input('palavra_chave'))
		{
			$ret = $ret->where(function($q) use($request){
			    $q->orWhere('palavras_chave','like','%'.$request->input('palavra_chave').'%')
			        ->orWhere('id',$request->input('palavra_chave'));
            });
		}

		if($request->input('tema'))
		{
			$ret = $ret->where(function($query) use ($request){
				foreach($request->input('tema') as $d)
				{
					$query->orWhere('tema_id',$d);
				}
			});
		}

		if($request->input('ciclo_etapa'))
		{
			$ret = $ret->where(function($query) use ($request){
				foreach($request->input('ciclo_etapa') as $d)
				{
					$query->orWhere('fr_questao.cicloetapa_id',$d);
				}
			});
		}

		if($request->input('disciplina'))
		{
			$ret = $ret->where(function($query) use ($request){
				foreach($request->input('disciplina') as $d)
				{
					$query->orWhere('fr_questao.disciplina_id',$d);
				}
			});
		}

		if($request->input('dificuldade'))
		{
			$ret = $ret->where(function($query) use ($request){
				foreach($request->input('dificuldade') as $d)
				{
					$query->orWhere('dificuldade',$d);
				}
			});
		}

		if($request->input('formato'))
		{
			$ret = $ret->where(function($query) use ($request){
				foreach($request->input('formato') as $d)
				{
					$query->orWhere('formato_id',$d);
				}
			});
		}

		if($request->input('suporte'))
		{
			$ret = $ret->where(function($query) use ($request){
				foreach($request->input('suporte') as $d)
				{
					$query->orWhere('suporte_id',$d);
				}
			});
		}

		if($request->input('unidade_tematica') || $request->input('habilidade'))
		{
			$ret = $ret->join('fr_bncc','fr_bncc.id','fr_questao.bncc_id');
			if($request->input('unidade_tematica'))
			{
				$ret = $ret->where(function($query) use ($request){
					foreach($request->input('unidade_tematica') as $d)
					{
						$query->orWhere('fr_bncc.unidade_tematica',$d);
					}
				});
			}
			if($request->input('habilidade'))
			{
				$ret = $ret->where(function($query) use ($request){
					foreach($request->input('habilidade') as $d)
					{
						$query->orWhere('fr_bncc.codigo_habilidade',$d);
					}
				});
			}
		}

        if($request->input('fonte'))
        {
            $ret = $ret->where(function($query) use ($request){
                foreach($request->input('fonte') as $d)
                {
                    $query->orWhere('fonte',$d);
                }
            });
        }

		if($request->input('selecionados'))
		{

			$vet = explode(',',$request->input('selecionados'));

			$ret = $ret->whereNotIn('fr_questao.id',$vet);
		}

		if($por_pagina == null)
		{
			$por_pagina = 20;
		}
		return $ret->selectRaw('fr_questao.*')->paginate($por_pagina);
	}

	public function getQuestaoPessoal($id)
	{
		if(auth()->user()->permissao == 'Z')
        {
           return  FrQuestao::where('instituicao_id',1)
                    ->find($id);
        }
        else{
            return  FrQuestao::where('user_id',auth()->user()->id )
                        ->where('escola_id',auth()->user()->escola_id )
                        ->find($id);
        }

	}

    public function getQuestao($id)
    {
        return FrQuestao::where(function($query){
            $query->orWhere('instituicao_id',1)
                    ->orWhere(function($q){
                        $q->where('escola_id',auth()->user()->escola_id)
                            ->where('compartilhar',1);
                    })
                    ->orWhere('user_id',auth()->user()->id);
            })
            ->find($id);
    }

	public function cicloEtapa()
	{
		return CicloEtapa::join('ciclos','ciclos.id','ciclo_etapas.ciclo_id')
    				->selectRaw('ciclo_etapas.id, ciclo_etapas.titulo as ciclo_etapa, ciclos.titulo as ciclo')
    				->where('ciclos.id','<>','1')
    				->where('ciclos.id','<>','5')
    				->get();
	}

	public function duplicarQuestao($id)
	{
        $questao = $this->getQuestao($id);
        if($questao) {
            DB::beginTransaction();
            try {

                $questao = $questao->replicate();
                $questao->save();
                $questao->update([
                    'user_id'       => auth()->user()->id,
                    'escola_id'     => auth()->user()->escola_id,
                    'instituicao_id'=> auth()->user()->instituicao_id,
                ]);
                DB::commit();
                return $questao->id;
            } catch (\Exception $e) {
                DB::rollback();
                return false;
            }
        }else{
            return false;
        }
	}

    public function publicar($questaoId)
    {
        DB::beginTransaction();
        try
        {
            $questao = $this->getQuestaoPessoal($questaoId);

            $publicado = 0;

            if($questao->publicado == 0)
            {
                $publicado = 1;
            }
            $questao->update(['publicado'=>$publicado]);
            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

	public function arrumaNaoSeAplica($dados)
	{
		if($dados['bncc_id'] == 'Não se aplica')
		{
			unset($dados['bncc_id']);
		}
		if($dados['unidade_tematica'] == 'Não se aplica')
		{
			unset($dados['unidade_tematica']);
		}
		if(@$dados['tema_id'] == 'Não se aplica')
		{
			unset($dados['tema_id']);
		}

		return $dados;
	}

	public function addQuestoes($dados)
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
        	$dados = $this->arrumaNaoSeAplica($dados);
			$questao = new FrQuestao($dados);
	        $questao->save();
            $this->preparaFullText($questao);
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

	public function updateQuestoes($id, $dados)
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
            if(!isset($dados['compartilhar']) || $dados['compartilhar']!=1){
                $dados['compartilhar'] = 0;
            }
            if(!isset($dados['disponibilizar_resolucao']) || $dados['disponibilizar_resolucao']!=1){
                $dados['disponibilizar_resolucao'] = 0;
            }

        	$questao = $this->getQuestaoPessoal($id);
        	$dados = $this->arrumaNaoSeAplica($dados);
	        $questao->update($dados);
            $this->preparaFullText($questao);
	        DB::commit();
	        return true;
	    }
        catch (\Exception $e)
        {

            DB::rollback();
            return false;
        }
	}

    public function preparaFullText($questao){
        $fullText = '';
        $fullText .= ' '.html_entity_decode(strip_tags( $questao->pergunta));
        $fullText .= ' '.html_entity_decode(strip_tags( $questao->alternativa_1));
        $fullText .= ' '.html_entity_decode(strip_tags( $questao->alternativa_2));
        $fullText .= ' '.html_entity_decode(strip_tags( $questao->alternativa_3));
        $fullText .= ' '.html_entity_decode(strip_tags( $questao->alternativa_4));
        $fullText .= ' '.html_entity_decode(strip_tags( $questao->alternativa_5));
        $fullText .= ' '.html_entity_decode(strip_tags( $questao->alternativa_6));
        $fullText .= ' '.html_entity_decode(strip_tags( $questao->alternativa_7));
        $fullText .= ' '.html_entity_decode(strip_tags( $questao->resolucao));
        $fullText .= ' '.$questao->palavras_chave;
        $fullText .= ' '.$questao->fonte;
        if($questao->disciplina && isset($questao->disciplina->titulo) && $questao->disciplina->titulo != ''){
            $fullText .= ' '.$questao->disciplina->titulo;
        }
        $fullText .= ' '.$questao->unidade_tematica;
        if($questao->bncc ){
            $fullText .= ' '.$questao->bncc->objeto_conhecimento;
            $fullText .= ' '.$questao->bncc->habilidade;
            $fullText .= ' '.$questao->bncc->codigo_habilidade;
            $fullText .= ' '.$questao->bncc->comentario;
            $fullText .= ' '.$questao->bncc->unidade_tematica;
        }
        $questao->update(['full_text'=>$fullText]);
    }

	public function excluirQuestoes($id)
	{
		DB::beginTransaction();
        try
        {
        	$questao = $this->getQuestaoPessoal($id);
	        $questao->delete();
	        DB::commit();
	        return true;
	    }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
	}

    public function fonte(){
        return FrQuestao::where(function($query){
            $query->orWhere('instituicao_id',1)
                ->orWhere(function($q){
                    $q->where('escola_id',auth()->user()->escola_id)
                        ->where('compartilhar',1);
                })
                ->orWhere('user_id',auth()->user()->id);
        })
            ->whereNotNull('fonte')
            ->orderBy('fonte')
            ->groupBy('fonte')
            ->selectRaw('fonte')
        ->get();
    }

    public function getBnccAjaxLista($request)
    {
        $retorno = "";

        $dados = FrBncc::orderBy('objeto_conhecimento');

        if(is_array($request->input('disciplina_id')) && count($request->input('disciplina_id'))>0) {
            $dados = $dados->whereIn('disciplina_id', $request->input('disciplina_id'));
        }
        if(is_array($request->input('cicloetapa_id')) && count($request->input('cicloetapa_id'))>0) {
            $dados = $dados->whereIn('cicloetapa_id', $request->input('cicloetapa_id'));
        }
        if(is_array($request->input('unidade_tematica_id')) && count($request->input('unidade_tematica_id'))>0) {
            $dados = $dados->whereIn('unidade_tematica', $request->input('unidade_tematica_id'));
        }

        $dados = $dados->get();
        foreach ($dados as $d) {
            $selecionado = '';
            if($request->input('selecionado')!='' && in_array($d->id,$request->input('selecionado')) )
            {
                $selecionado = 'selected';
            }
            $retorno .= '<option '.$selecionado.' value="'.$d->id.'">'.$d->codigo_habilidade.'</option>';
        }
        return $retorno;
    }

	public function getBnccAjax($request)
	{
		$retorno = [];
		$vet = [];
		$obj = new \stdClass();
		$obj->text = 'Não se aplica';
		$obj->value = '';
		$vet[][] = $obj;
		$dados = FrBncc::where('disciplina_id',$request->input('disciplina_id'))
				->where('cicloetapa_id',$request->input('cicloetapa_id'))
				->where('unidade_tematica',$request->input('unidade_tematica_id'))
				->orderBy('objeto_conhecimento')
                ->get();
		foreach ($dados as $d) {
			$obj = new \stdClass();
			$obj->text = '('.$d->codigo_habilidade.') '.$d->habilidade;
			$obj->value = $d->id;
			if($request->input('selecionado')!='' && $request->input('selecionado')==$d->id)
			{
				$obj->selected = true;
			}
			$vet[$d->objeto_conhecimento][] = $obj;
		}

		foreach ($vet as $key => $value) {
			$obj = new \stdClass();
			$obj->label = $key;
			$obj->options = $value;
			$retorno[] = $obj;
		}
		return $retorno;
	}

    public function getUnidadeTematicaAjaxLista($request)
    {
        $retorno = '';
        $dados = FrBncc::groupBy('unidade_tematica');

        if(is_array($request->input('disciplina_id')) && count($request->input('disciplina_id'))>0) {
            $dados = $dados->whereIn('disciplina_id', $request->input('disciplina_id'));
        }
        if(is_array($request->input('cicloetapa_id')) && count($request->input('cicloetapa_id'))>0){
            $dados = $dados->whereIn('cicloetapa_id',$request->input('cicloetapa_id'));
        }
        $dados = $dados->get();

        foreach ($dados as $d) {
            $selecionado = '';
            if($request->input('selecionado')!='' && in_array($d->unidade_tematica,$request->input('selecionado')) )
            {
                $selecionado = 'selected';
            }
            $retorno .= '<option '.$selecionado.' value="'.$d->unidade_tematica.'">'.$d->unidade_tematica.'</option>';


        }
        return $retorno;
    }

	public function getUnidadeTematicaAjax($request)
	{
		$retorno = [];
		$obj = new \stdClass();
		$obj->text = 'Não se aplica';
		$obj->value = '';
		$retorno[] = $obj;

		$dados = FrBncc::where('disciplina_id',$request->input('disciplina_id'))
						->where('cicloetapa_id',$request->input('cicloetapa_id'))
						->groupBy('unidade_tematica')
						->get();
		foreach ($dados as $d) {
			$obj = new \stdClass();
			$obj->text = $d->unidade_tematica;
			$obj->value = $d->unidade_tematica;
			if($request->input('selecionado')!='' && $request->input('selecionado')==$d->unidade_tematica)
			{
				$obj->selected = true;
			}
			$retorno[] = $obj;
		}
		return $retorno;
	}

    public function getTemaAjaxLista($request)
    {
        $retorno = '';
        $dados = FrQuestaoTema::orderBy('disciplina_id')
                            ->orderBy('titulo');
        if(is_array($request->input('disciplina_id')) && count($request->input('disciplina_id'))>0){
            $dados = $dados->whereIn('disciplina_id',$request->input('disciplina_id'));
        }
        $dados = $dados->get();

        foreach ($dados as $d) {
            $selecionado = '';
            if($request->input('selecionado')!='' && in_array($d->id,$request->input('selecionado')) )
            {
                $selecionado = 'selected';
            }
            $retorno .= '<option '.$selecionado.' value="'.$d->id.'">'.$d->titulo.'</option>';
        }
        return $retorno;
    }

	public function getTemaAjax($request)
	{
		$retorno = [];
		$obj = new \stdClass();
		$obj->text = 'Não se aplica';
		$obj->value = '';
		$retorno[] = $obj;

		$dados = FrQuestaoTema::where('disciplina_id',$request->input('disciplina_id'))
						->orderBy('titulo')
						->get();
		foreach ($dados as $d) {
			$obj = new \stdClass();
			$obj->text = $d->titulo;
			$obj->value = $d->id;
			if($request->input('selecionado')!='' && $request->input('selecionado')==$d->id)
			{
				$obj->selected = true;
			}
			$retorno[] = $obj;
		}
		return $retorno;
	}

	public function verQuestao($id)
	{
		return $this->getQuestao($id);
	}

	public function mudaStatusMinhasQuestoes($request)
	{
		/// retira o campo id pois o mesmo fomulário html faz insert e update
		$dados = $request->all();
		DB::beginTransaction();
        try
        {
        	$questao = $this->getQuestaoPessoal($dados['id']);

        	$questao->update($dados);
	        DB::commit();
	        return true;
	    }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }

	}


    public function buscaGeral($porPagina, $busca)
    {
        $ret =  FrQuestao::join('disciplinas', 'fr_questao.disciplina_id','disciplinas.id')
            ->join('ciclo_etapas','ciclo_etapas.id','fr_questao.cicloetapa_id')
            ->join('ciclos','ciclos.id','ciclo_etapas.ciclo_id');
        /// filtros

        /// filtro da aba biblioteca
        if(auth()->user()->permissao == 'Z')
        {
            $ret->where('fr_questao.instituicao_id',1);
        }
        else
        {
            $ret->where('eh_ead',0)
                ->where(function($qq){
                    $qq->orWhere(function($query){
                        $query->orWhere(function($q){
                            $q->where('escola_id',auth()->user()->escola_id)
                                ->where('compartilhar',1);
                        })
                            ->orWhere('instituicao_id',1)
                            ->orWhere(function($q){
                                $q->where('fr_questao.user_id',auth()->user()->id)
                                    ->where('fr_questao.escola_id',auth()->user()->escola_id);
                            });
                    })->orWhere(function($query){
                        $query->where('fr_questao.user_id',auth()->user()->id)
                            ->where('fr_questao.escola_id',auth()->user()->escola_id);
                    })->orWhere(function($que){
                        $que->where(function($query){
                            $query->orWhere(function($q){
                                $q->where('escola_id',auth()->user()->escola_id)
                                    ->where('compartilhar',1);
                            });
                            $query->orWhere('instituicao_id',1);
                        });
                    });
                })
                ->where('publicado',1);
        }

        $ret->whereRaw("MATCH(fr_questao.full_text)AGAINST('".$busca."' IN BOOLEAN MODE)");
        return $ret->selectRaw('fr_questao.*, disciplinas.titulo as disciplina, ciclos.titulo as ciclo, ciclo_etapas.titulo as etapa')->paginate($porPagina);
    }

    public function fila($cursor=null){
        foreach(FrQuestao::orderBy('id')
                    ->whereNull('full_text')
                    ->cursor() as $q){
            FullText::dispatch(['dados'=>$q]);

        }
    }

    public function gravadadosfila($questao){
        foreach($questao as $q){
            FullText::dispatch(['dados'=>$q]);
        }
    }
}
