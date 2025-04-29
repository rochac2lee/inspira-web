<?php
namespace App\Services\Fr;
use DB;

use App\Services\Fr\UsuarioService;
use App\Services\Fr\InstituicaoService;

use App\Models\Escola;
use App\Models\User;
use App\Library\Slim;
use App\Models\Ciclo;
use App\Models\UserPermissao;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class EscolaService {

	public function __construct(UsuarioService $usuarioService, InstituicaoService $instituicaoService)
    {
        $this->usuarioService = $usuarioService;
        $this->instituicaoService = $instituicaoService;
    }

	public function getLista($pagina = null, $pesquisa = null)
	{
		$escola = Escola::join('instituicao','escolas.instituicao_id','instituicao.id')
					->with('qtdAlunos')
					->with('qtdProfessores')
					->with('qtdTurmas')
                    ->with('responsavelEscola')
					->whereNull('instituicao.deleted_at')
					->orderBy('instituicao.titulo')
					->orderBy('escolas.titulo')
					->selectRaw('escolas.*, instituicao.titulo as instituicao');

		if(isset($pesquisa['comTurma']) && $pesquisa['comTurma']==1){
            $escola->with('turmas');
        }

        if(auth()->user()->permissao == 'I'){
            $escola->where('instituicao_id',auth()->user()->instituicao_id)
                ->where('escolas.status_id',1);
        }elseif(auth()->user()->permissao == 'C'){
            $escola->where('escolas.id',auth()->user()->escola_id)
                ->where('escolas.status_id',1);
        }

		/// filtro da pesquisa
		if(isset($pesquisa['nome']) && $pesquisa['nome']!='')
		{
			$escola->where(function($q) use ($pesquisa){
				$q->orWhere('escolas.titulo','like','%'.$pesquisa['nome'].'%')
					->orWhere('escolas.id',$pesquisa['nome']);
			});
		}

		if(isset($pesquisa['status']) && $pesquisa['status']!='')
		{
			$escola->where('escolas.status_id',$pesquisa['status']);
		}

		if(isset($pesquisa['inst']) && $pesquisa['inst']!='')
		{
            $escola->where(function($q) use($pesquisa){
                $q->orWhere('instituicao.titulo','like','%'.$pesquisa['inst'].'%')
                    ->orWhere('instituicao.id',$pesquisa['inst']);
            });
		}
		/// define se terá paginação
		if($pagina!= null){
			return $escola->paginate($pagina);
		}
		else{
			return $escola->get();
		}
	}

    public function mudaStatus($request)
    {
        /// retira o campo id pois o mesmo fomulário html faz insert e update
        $dados = $request->all();

        DB::beginTransaction();
        try
        {
            $inst = Escola::find($dados['id']);

            $inst->update($dados);

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

	public function excluir($id)
	{
		$inst = Escola::find($id);
        return $inst->delete();
	}

	public function inserir($request,$userId)
	{
		/// retira o campo id pois o mesmo fomulário html faz insert e update
		$dados = $request->except(['id']);
		$dados['user_id']=$userId;

		DB::beginTransaction();
        try
        {
	        $logo = $this->addLogo();
	        if($logo!=null)
	        {
	        	$dados['logo'] = $logo;
	        }
			$escola = new Escola($dados);
	        $escola->save();

	        $dados['id'] = $escola->id;

	        $userAdm = $this->addAdm($dados);
	        $this->usuarioService->tokenNovoUsuario($userAdm);

	        $escola->ciclo_etapa()->attach($dados['etapa_ano']);

            $this->instituicaoService->atualizaPermissaoEscolasLivro($escola->instituicao_id, $escola->id);
            $this->instituicaoService->atualizaPermissaoEscolasAudio($escola->instituicao_id, $escola->id);
            $this->instituicaoService->atualizaPermissaoEscolasProva($escola->instituicao_id, $escola->id);

	        DB::commit();
	        return true;
	    }
        catch (\Exception $e)
        {
            DB::rollback();
            return $e;
        }
	}

	public function editar($request)
	{
		/// retira o campo id pois o mesmo fomulário html faz insert e update
		$dados = $request->all();
		DB::beginTransaction();
        try
        {
        	$escola = Escola::find($dados['id']);

        	if(array_key_exists('imagem',$dados))
        	{
        		$logo = '';
        		if($dados['imagem'] != '')
        		{
        			$logo = $this->addLogo();
        		}
        		$dados['logo'] = $logo;
        		$this->deletaLogoAtual($escola->logo);
        	}

	        $escola->update($dados);

	        $escola->ciclo_etapa()->sync($dados['etapa_ano']);

	        if(isset($dados['radio_adm'])) {
                if ($dados['radio_adm'] == 'editar') {
                    $this->updateAdm($dados);
                } elseif ($dados['radio_adm'] == 'novo') {
                    $this->removePermissaoAdm($dados);
                    $this->addAdm($dados);
                }
            }
	        DB::commit();
	        return true;
	    }
        catch (\Exception $e)
        {
        	dd($e);
            DB::rollback();
            return $e;
        }
	}

	private function getADM($instituicao_id,$escola_id)
	{
		$up = UserPermissao::withoutGlobalScopes()->where('escola_id',$escola_id )->where('permissao','G')->first();
		if($up)
		{
			return User::find($up->user_id);
		}
		else
		{
			return User::where('escola_id',$escola_id )->where('permissao','G')->first();
		}
	}

	public function getForm($id)
	{
		$escola = $this->get($id);
		if(isset($escola->id))
		{
			$adm = $this->getADM($escola->instituicao_id,$escola->id);
			if($adm){
				$escola['adm_escola'] = $adm->nome_completo;
				$escola['adm_email'] = $adm->email;
				$escola['idUserAdm'] = $adm->id;
			}
			$ci = [];
			foreach ($escola['ciclo_etapa'] as $c) {
				$ci[]=$c['pivot']['ciclo_etapas_id'] ;
			}
			$escola['etapa_ano']=$ci;

			unset($escola['ciclo_etapa']);
			return $escola;
		}
		else
		{
			return false;
		}
	}

	public function get($id)
	{
		$escola = Escola::with('ciclo_etapa')->find($id);
		if(isset($escola->id))
		{
			return $escola;
		}
		else
		{
			return false;
		}
	}

	private function addLogo()
	{
        $images = Slim::getImages('imagem');
        if(count($images)>0)
        {
	        $image = $images[0];
	        // let's create some shortcuts
	        $name = explode('.',$image['input']['name']);
	        $ext = '.'.$name[count($name)-1];
	        $data = $image['output']['data'];


	        $file = Slim::saveFile($data, $ext,config('app.frTmp'));
            $fileName = $file['name'];

            $img = Image::make(config('app.frTmp').$fileName);
            $img->resize(800, 600, function ($constraint) {
                $constraint->aspectRatio();
            })->encode('webp', 90);

            $resource = $img->stream()->detach();
            $fileName = $img->filename.'.webp';

            Storage::disk()->put(config('app.frStorage').'logo_escola/'.$fileName, $resource);
            return $fileName;

        }
        else
        {
        	return null;
        }

	}

	private function deletaLogoAtual($logo)
	{
        $caminhoFinal= config('app.frStorage').'logo_escola/';
		if( $logo!='')
		{
            Storage::delete($caminhoFinal.$logo);
		}
	}


	private function updateAdm($dados)
	{
		$user = User::find($dados['idUserAdm']);

		$nome = explode(' ', $dados['adm_escola']);
        $userDados = [
        	'name' 				=> $nome[0],
        	'email' 			=> $dados['adm_email'],
        	'nome_completo'		=> $dados['adm_escola'],

        ];

        $user->update($userDados);

	}

	private function removePermissaoAdm($dados)
	{
		$user = User::find($dados['idUserAdm']);
		if($user->getRawOriginal('escola_id') != $dados['id']  || $user->getRawOriginal('permissao') != 'G')
        {
        	$up = UserPermissao::where('user_permissao.user_id',$user->id)->where('escola_id',$dados['id'])->where('permissao','G');
        	$up->delete();
        }
        else
        {
        	$upDados = [
        		'instituicao_id' =>null,
        		'escola_id' => null,
        		'permissao' => null,
        	];

        	$up = UserPermissao::where('user_permissao.user_id',$user->id)->first();
        	if($up){
        		$upDados = [
	        		'instituicao_id' =>null,
	        		'escola_id' => $up->escola_id,
	        		'permissao' => $up->permissao,
	        	];
	        	$up->delete();
        	}
        	$user->update($upDados);
        }
	}

	private function addAdm($dados)
	{
		$user = User::where('email', $dados['adm_email'])->first();

		if($user)
		{
			return  $this->alteraAdm($dados,$user);
		}
		else
		{
			return $this->insereAdm($dados);
		}

	}

	private function alteraAdm($dados, $user)
	{
		$nome = explode(' ', $dados['adm_escola']);
        $userDados = [
        	'name' 				=> $nome[0],
        	'email' 			=> $dados['adm_email'],
        	'nome_completo'		=> $dados['adm_escola'],

        ];

        if( $user->getRawOriginal('escola_id') != $dados['id'] || $user->getRawOriginal('permissao') != 'I')
        {
        	$up = [
        			'user_id'=>			$user->id,
        			'instituicao_id'=>	null,
        			'escola_id'=>	$dados['id'],
        			'permissao'=>		'G',
        		];

        	UserPermissao::withoutGlobalScopes()->firstOrCreate($up);
        }


        $user->update($userDados);
        return $user;

	}

	private function insereAdm($dados)
	{

		$nome = explode(' ', $dados['adm_escola']);
        $user = [
        	'name' 				=> $nome[0],
        	'email' 			=> $dados['adm_email'],
        	'nome_completo'		=> $dados['adm_escola'],
        	'data_nascimento'	=> null,
        	'permissao'			=> 'G',
        	'instituicao_id'	=> null,
        	'escola_id'			=> $dados['id'],
        	'ocupacao'			=> 'Gestor de escola',
        ];

        $user = new User($user);
        $user->save();
        return $user;

	}

	public function cicloEtapa()
	{
		return Ciclo::join('ciclo_etapas','ciclos.id','ciclo_etapas.ciclo_id')
							->orderBy('ciclos.titulo')
							->orderBy('ciclo_etapas.titulo')
							->selectRaw('ciclo_etapas.id, ciclos.titulo as ciclo, ciclo_etapas.titulo as ciclo_etapa')
							->get();
	}

	public function escolaEhDaInstituicao($escolaId){
        $escola = Escola::where('instituicao_id',auth()->user()->instituicao_id)->find($escolaId);
        if($escola){
            return true;
        }else{
            return false;
        }
    }

}
