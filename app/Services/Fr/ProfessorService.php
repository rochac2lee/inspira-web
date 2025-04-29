<?php
namespace App\Services\Fr;
use Illuminate\Support\Facades\Auth;
use DB;
use Hash;
use Validator;

use App\Http\Requests\Fr\ProfessorRequest;

use App\Models\User;
use App\Models\Professor;
use App\Models\ProfessorEscola;
use App\Models\UserPermissao;
use App\Models\Escola;
use App\Models\FrImportacaoUser;
use App\Models\AlunoCicloEtapa;

class ProfessorService {

	public function getLista($idEscola, $request = null)
	{
		$professor = User::where('escola_id',$idEscola)
					->where('permissao','P')
					->selectRaw('users.*');

		$professorPermissao = User::join('user_permissao','user_permissao.user_id','users.id')
					->where('user_permissao.escola_id',$idEscola)
					->where('user_permissao.permissao','P')
					->selectRaw('users.*');

		/// filtro da pesquisa
		if($request->input('pesquisa')!='')
		{
			$pesquisa = $request->input('pesquisa');
			$professor->where(function($q) use ($pesquisa) {
				$q->orWhere('nome_completo','like','%'.$pesquisa.'%')
					->orWhere('email','like','%'.$pesquisa.'%')
					->orWhere('users.id',$pesquisa);
			});
			$professorPermissao->where(function($q) use ($pesquisa) {
				$q->orWhere('nome_completo','like','%'.$pesquisa.'%')
					->orWhere('email','like','%'.$pesquisa.'%')
					->orWhere('users.id',$pesquisa);
			});
		}

		return $professor->union($professorPermissao)->orderBy('nome_completo')->paginate(20);

	}

	/*
	public function importar($request)
	{
		$upload = $request->file('arquivo')->store('upload_usuario/'.auth()->user()->id);

		switch ($request->input('tipo_arquivo')) {
		    case 1:
		        return $this->importaTipoGoogle($upload,$request);
		    break;
		}

	}

	private function importaTipoGoogle($upload,$request)
	{
		$arquivo = fopen('../storage/app/'.$upload, 'r');
		$iniciar = 0;
		$count = 1;
		$linhas = [];
		//// percorre o arquivo
	    while (!feof($arquivo)) {
	    	$linha = fgetcsv($arquivo, 0, ',');
	    	if($iniciar == 1){ //// se a primeira linha de usuarios for encontrada
	    		if(trim($linha[1]) != ''){ /// se é uma linha preenchida
	    			/// organiza os dados da planilha para uma variavel padrao
	        		$linhas[] = $this->trataDadosTipoGoogle($linha,$request->input('escola_id'), $request->input('instituicao_id'),$count);
	    		}
	    	}
	    	elseif($linha[0] == 'MATRÍCULA') /// encontrou o inicio da leitura do arquivo
	    	{
	    		$iniciar = 1;
	    	}
	    	$count++;
	    }
	    fclose($arquivo);
	    /// valida cada uma das linhas com REQUEST
	    $dadosValidados = $this->validacaoDadosImportacao($linhas);
	    /// insere no banco de dados
	    return $this->insereDadosImportados($dadosValidados,$upload,$request->input('tipo_arquivo'));
	}

	private function trataDadosTipoGoogle($linha, $idEscola, $idInstituicao, $count)
	{

		$linha = array_map('trim',$linha);
		$nome = explode(' ',$linha[1]);

		$retorno = [];
		$retorno['name'] 			= ucfirst(strtolower($nome[0]));
		$retorno['nome_completo'] 	= ucwords(strtolower($linha[1]));
		$retorno['email'] 			= strtolower($linha[8]);
        $retorno['escola_id'] 		= $idEscola;
        $retorno['instituicao_id'] 	= $idInstituicao;
		$retorno['permissao'] 		= 'P';
		$retorno['ocupacao'] 		= $linha[3];
		$retorno['status_id'] 		= 1;
		$retorno['genero'] 			= ucfirst(strtolower($linha[5]));
		$retorno['privilegio_id'] 	= 3;
		$retorno['password'] 		= Hash::make(rand(1111111,9999999));
		$retorno['linha'] 			= $count;
		$retorno['ciclo_id'] 		= $this->cicloIdGoogle($linha[2]);
        $retorno['cicloetapa_id'] 	= $this->cicloEtapaIdGoogle($linha[2]);
		return $retorno;
	}

	private function cicloIdGoogle($id)
	{

		switch ( mb_strtolower($id) ) {
		    case "educação infantil":
		        return 1;
		    break;
		    case "anos iniciais":
		        return 2;
		    break;
		    case "anos finais":
		        return 3;
		    break;
		    case "ensino médio":
		        return 4;
		    break;
            case "":
                return 5;
            break;
		}
	}

	private function cicloEtapaIdGoogle($id)
	{
		switch ( mb_strtolower($id) ) {
		    case "educação infantil":
		        return 1;
		    break;
		    case "anos iniciais":
		        return 2;
		    break;
		    case "anos finais":
		        return 3;
		    break;
		    case "ensino médio":
		        return 21;
		    break;
            case "":
                return 22;
            break;
		}
	}

	private function validacaoDadosImportacao($dados)
	{

		$erro=[];
		$i=0;
		foreach($dados as $d)
		{
			/// instacia o request
			$requestLote = new ProfessorRequest;
			/// popula o request com os dados a serem validados
			$requestLote->replace($d);
			/// realiza a validação
			$csv_errors = Validator::make($d,$requestLote->rules(), $requestLote->messages(), $requestLote->attributes())->errors();
			/// se teve erro
			if ($csv_errors->any()) {
	            $erro[]=['dados'=>$d, 'erroValidator'=>$csv_errors, 'erroBanco'=>''];
	        	unset($dados[$i]);
	        }
	        $i++;
		}
		return ['dados' => $dados, 'erro' => $erro];
	}

	private function insereDadosImportados($dadosValidados,$upload,$tipoArquivo)
	{
		$certo 			= [];
		$erro 			= $dadosValidados['erro'];
		$dados 			= $dadosValidados['dados'];
		$nome_arquivo 	= explode('/',$upload);
		$nome_arquivo 	= $nome_arquivo[count($nome_arquivo)-1];

		foreach ($dados as $d) {
			$add = $this->addProfessor($d);
			if($add===true)
			{
				$certo[] = $d;
			}
			else
			{
				$erro[] = ['dados'=>$d, 'erroValidator'=>'', 'erroBanco'=>$add];
			}
		}

		$dados = [
			'arquivo' 		=> $nome_arquivo,
			'user_id' 		=> auth()->user()->id,
			'tipo_arquivo' 	=> $tipoArquivo,
			'erros' 		=> serialize($erro),
			'acertos' 		=> serialize($certo),
		];
		$users = new FrImportacaoUser($dados);
        $users->save();

 		return [
 			'qtdCerto' 	=> count($certo),
 			'qtdErro' 	=> count($erro),
 		];

	}
*/
	public function addProfessor($dados)
	{
		DB::beginTransaction();
        try
        {
        	$users = User::where('email',$dados['email'])->first();
            if(isset($users->id) && $users->id > 0)
            {
            	$escola = Escola::find($dados['escola_id']);
            	$dados['user_id'] 		 = $users->id;
            	$dados['instituicao_id'] = $escola->instituicao_id;
                $permissao = new UserPermissao($dados);
                $permissao->save();
            }
            else
            {
                $users = new User($dados);
                $users->save();
            }
        	Professor::firstOrCreate([
                'user_id' => $users->id
            ]);

            ProfessorEscola::firstOrCreate([
                'user_id' => $users->id,
                'escola_id' => $dados['escola_id']
            ]);

            $this->addProfessorComoAluno($users->id,$dados);

	        DB::commit();
	        return true;
	    }
        catch (\Exception $e)
        {
            dd($e);
            DB::rollback();
            return false;
        }
	}



}
