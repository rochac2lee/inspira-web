<?php
namespace App\Services\Fr;
use Illuminate\Support\Facades\Auth;
use DB;

use App\Models\Conteudo;
use App\Models\ColecaoEdInfantil;

class EdInfantilService {

	public function permissaoProfessorColecao($id)
	{
		$colecao = ColecaoEdInfantil::find($id);
		return $colecao->eh_professor;
	}

	public function getConteudo($id,$request)
	{
		$retorno = Conteudo::where('colecao_ed_infantil_id',$id);

		if($request->input('busca')!='')
		{
			$retorno->where('conteudos.titulo','LIKE','%'.DB::raw($request->input('busca')).'%');
		}
		return $retorno->paginate(24);
	}

    public function classeColecao($id)
    {
    	if($id==1)
    	{
    		return 'sabido';
    	}
    	elseif($id==2)
    	{
    		return 'historia';
    	}
    	elseif($id==3)
    	{
    		return 'canta';
    	}
    	elseif($id==4)
    	{
    		return 'brincadeira';
    	}
    	elseif($id==5)
    	{
    		return 'massa';
    	}
    }

	public function getConteudoAjax($id,$request)
	{
		$dados = $this->getConteudo($id,$request);
		$retorno = '';
		foreach ($dados as $d) {
			$retorno .= view('fr/ed_infantil/item_material',['d'=>$d]);
		}

		return $retorno;
	}

}
