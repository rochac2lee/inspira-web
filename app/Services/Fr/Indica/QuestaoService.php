<?php
namespace App\Services\Fr\Indica;
use App\Models\Indica\Questao;
use DB;

use App\Models\QuestaoTema;
use App\Models\CicloEtapa;

class QuestaoService {

	public function questoesSelecionadas($selecionados)
	{
		$ordem = implode(',', $selecionados);
		return Questao::with(['usuario','disciplina','bncc','cicloEtapa'])
						->whereIn('id',$selecionados)
						->orderByRaw("FIELD(indica_questao.id , ".$ordem.") ASC")
						->get();
	}

	public function minhasQuestoes($request, $por_pagina = null)
	{
		$ret =  Questao::with(['usuario','disciplina','bncc','cicloEtapa']);


		if($request->input('palavra_chave'))
		{
			$ret = $ret->where(function($q) use($request){
			    $q->orWhere('palavras_chave','like','%'.$request->input('palavra_chave').'%')
			        ->orWhere('id',$request->input('palavra_chave'))
			        ->orWhere('codigo',$request->input('palavra_chave'));
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
					$query->orWhere('indica_questao.cicloetapa_id',$d);
				}
			});
		}

		if($request->input('disciplina'))
		{
			$ret = $ret->where(function($query) use ($request){
				foreach($request->input('disciplina') as $d)
				{
					$query->orWhere('indica_questao.disciplina_id',$d);
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
			$ret = $ret->join('indica_bncc','indica_bncc.id','indica_questao.bncc_id');
			if($request->input('unidade_tematica'))
			{
				$ret = $ret->where(function($query) use ($request){
					foreach($request->input('unidade_tematica') as $d)
					{
						$query->orWhere('indica_bncc.unidade_tematica',$d);
					}
				});
			}
			if($request->input('habilidade'))
			{
				$ret = $ret->where(function($query) use ($request){
					foreach($request->input('habilidade') as $d)
					{
						$query->orWhere('indica_bncc.codigo_habilidade',$d);
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

			$ret = $ret->whereNotIn('indica_questao.id',$vet);
		}

		if($por_pagina == null)
		{
			$por_pagina = 20;
		}

		return $ret->selectRaw('indica_questao.*')->paginate($por_pagina);
	}


    public function getQuestao($id)
    {
        return Questao::find($id);
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
		if($dados['tema_id'] == 'Não se aplica')
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
        	$dados['user_id'] = auth()->user()->id;
        	$dados = $this->arrumaNaoSeAplica($dados);
			$questao = new Questao($dados);
	        $questao->save();
	        DB::commit();
	        return true;
	    }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
	}

	public function updateQuestoes($id, $dados)
	{
		DB::beginTransaction();
        try

        {
        	$questao = $this->getQuestao($id);
        	$dados = $this->arrumaNaoSeAplica($dados);
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

	public function excluirQuestoes($id)
	{
		DB::beginTransaction();
        try
        {
        	$questao = $this->getQuestao($id);
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
        return Questao::whereNotNull('fonte')
            ->orderBy('fonte')
            ->groupBy('fonte')
            ->selectRaw('fonte')
        ->get();
    }

	public function verQuestao($id)
	{
		return $this->getQuestao($id);
	}
}
