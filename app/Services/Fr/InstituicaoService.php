<?php
namespace App\Services\Fr;
use App\Models\ColecaoAudioEscola;
use App\Models\ColecaoAudioEscolaPermissao;
use App\Models\ColecaoAudioInstituicao;
use App\Models\ColecaoAudioInstituicaoPermissao;
use App\Models\ColecaoDocumentoEscola;
use App\Models\ColecaoDocumentoEscolaPermissao;
use App\Models\ColecaoDocumentoInstituicao;
use App\Models\ColecaoDocumentoInstituicaoPermissao;
use App\Models\ColecaoLivroEscola;
use App\Models\ColecaoLivroEscolaPermissao;
use App\Models\ColecaoLivroEscolaPermissaoPeriodo;
use App\Models\ColecaoLivroInstituicao;
use App\Models\ColecaoLivroInstituicaoPermissao;
use App\Models\ColecaoLivroInstituicaoPermissaoPeriodo;
use App\Models\ColecaoProvaEscola;
use App\Models\ColecaoProvaEscolaPermissao;
use App\Models\ColecaoProvaInstituicao;
use App\Models\ColecaoProvaInstituicaoPermissao;
use App\Models\Escola;
use Illuminate\Support\Facades\Auth;
use DB;

use App\Services\Fr\UsuarioService;
use App\Services\Fr\LivroService;
use App\Services\Fr\BibliotecaService;

use App\Models\Instituicao;
use App\Models\InstituicaoTipo;
use App\Models\User;
use App\Library\Slim;
use App\Models\UserPermissao;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class InstituicaoService {

	public function __construct(UsuarioService $usuarioService, LivroService $livroService, BibliotecaService $bibliotecaService)
    {
        $this->usuarioService = $usuarioService;
        $this->livroService = $livroService;
        $this->bibliotecaService = $bibliotecaService;
    }

	public function getLista($pagina = null, $pesquisa = null)
	{
		$inst = Instituicao::with('tipo_instituicao')
					->orderBy('titulo');

		/// filtro da pesquisa
		if(isset($pesquisa['nome']) && $pesquisa['nome']!='')
		{
			$inst->where('titulo','like','%'.$pesquisa['nome'].'%');
		}

		if(isset($pesquisa['status']) && $pesquisa['status']!='')
		{
			$inst->where('status_id',$pesquisa['status']);
		}

		if(isset($pesquisa['tipo']) && $pesquisa['tipo']!='')
		{
			$inst->where('instituicao_tipo_id',$pesquisa['tipo']);
		}

		/// define se terá paginação
		if($pagina!= null){
			return $inst->paginate($pagina);
		}
		else{
			return $inst->get();
		}
	}

	public function getTipos()
	{
		return InstituicaoTipo::orderBy('titulo')->get();
	}

	public function excluir($id)
	{
		$inst = Instituicao::find($id);
        return $inst->delete();
	}

	public function inserir($request)
	{
		/// retira o campo id pois o mesmo fomulário html faz insert e update
		$dados = $request->except(['id']);

		DB::beginTransaction();
        try
        {
	        $logo = $this->addLogo();
	        if($logo!=null)
	        {
	        	$dados['logo_url'] = $logo;
	        }
			$inst = new Instituicao($dados);
	        $inst->save();

	        $dados['id'] = $inst->id;

	        $userAdm = $this->addAdm($dados);

	       // $this->usuarioService->tokenNovoUsuario($userAdm);

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

	public function editar($request)
	{
		/// retira o campo id pois o mesmo fomulário html faz insert e update
		$dados = $request->all();

		DB::beginTransaction();
        try
        {
        	$inst = Instituicao::find($dados['id']);



        	if(array_key_exists('arquivo',$dados))
        	{
        		$logo = '';
        		if($dados['arquivo'] != '')
        		{
        			$logo = $this->addLogo();
        		}
        		$dados['logo_url'] = $logo;
        		$this->deletaLogoAtual($inst->logo_url);
        	}

	        $inst->update($dados);

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
            DB::rollback();
            dd($e);
            return $e;
        }
	}

	private function getADM($instituicao_id)
	{
		$up = UserPermissao::where('instituicao_id',$instituicao_id )->where('permissao','I')->first();

		if($up)
		{
			return User::find($up->user_id);
		}
		else
		{
			return User::where('instituicao_id',$instituicao_id )->where('permissao','I')->first();
		}
	}

	public function getForm($id)
	{
		$inst = $this->get($id);
		//dd($inst);
		if(isset($inst->id))
		{
			$adm = $this->getADM($inst->id);
			$inst =  $inst->toArray();
			if($adm){
				$inst['adm_inst'] = $adm->nome_completo;
				$inst['adm_email'] = $adm->email;
				$inst['idUserAdm'] = $adm->id;
			}

			unset($inst['responsavel_instituicao']);
			return $inst;
		}
		else
		{
			return false;
		}
	}


	public function get($id)
	{
		$inst = Instituicao::find($id);
		if(isset($inst->id))
		{
			return $inst;
		}
		else
		{
			return false;
		}
	}

	private function addLogo()
	{
        $images = Slim::getImages('arquivo');
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

            Storage::disk()->put(config('app.frStorage').'logo_instituicao/'.$fileName, $resource);
            return $fileName;

        }
        else
        {
        	return null;
        }

	}

	private function deletaLogoAtual($logo)
	{
        $caminhoFinal= config('app.frStorage').'logo_instituicao/';
        if( $logo!='')
        {
            Storage::delete($caminhoFinal.$logo);
        }
	}


	private function updateAdm($dados)
	{
		$user = User::find($dados['idUserAdm']);

		$nome = explode(' ', $dados['adm_inst']);
        $userDados = [
        	'name' 				=> $nome[0],
        	'email' 			=> $dados['adm_email'],
        	'nome_completo'		=> $dados['adm_inst'],

        ];

        $user->update($userDados);

	}

	private function removePermissaoAdm($dados)
	{
		$user = User::find($dados['idUserAdm']);
		if($user->getRawOriginal('instituicao_id') != $dados['id'] || $user->getRawOriginal('permissao') != 'I')
        {
        	$up = UserPermissao::where('user_id',$user->id)->where('instituicao_id',$dados['id'])->where('permissao','I');
        	$up->delete();
        }
        else
        {
        	$upDados = [
        		'instituicao_id' =>null,
        		'escola_id' => null,
        		'permissao' => null,
        	];

        	$up = UserPermissao::where('user_id',$user->id)->first();
        	if($up){
        		$upDados = [
	        		'instituicao_id' =>$up->instituicao_id,
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

		$nome = explode(' ', $dados['adm_inst']);
        $userDados = [
        	'name' 				=> $nome[0],
        	'email' 			=> $dados['adm_email'],
        	'nome_completo'		=> $dados['adm_inst'],

        ];

        if($user->getRawOriginal('instituicao_id') != $dados['id'] || $user->getRawOriginal('permissao') != 'I')
        {
        	$up = [
        			'user_id'=>			$user->id,
        			'instituicao_id'=>	$dados['id'],
        			'permissao'=>		'I',
        		];

        	UserPermissao::withoutGlobalScopes()->firstOrCreate($up);
        }


        $user->update($userDados);
        return $user;

	}

	private function insereAdm($dados)
	{

		$nome = explode(' ', $dados['adm_inst']);
        $user = [
        	'name' 				=> $nome[0],
        	'email' 			=> $dados['adm_email'],
        	'nome_completo'		=> $dados['adm_inst'],
        	'data_nascimento'	=> null,
        	'permissao'			=> 'I',
        	'instituicao_id'	=> $dados['id'],
        	'escola_id'			=> null,
        	'ocupacao'			=> 'Adm de instituição',
        ];

        $user = new User($user);
        $user->save();
        return $user;

	}

	public function mudaStatus($request)
	{
		/// retira o campo id pois o mesmo fomulário html faz insert e update
		$dados = $request->all();

		DB::beginTransaction();
        try
        {
        	$inst = Instituicao::find($dados['id']);

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

	public function removerColecaoLivro($idInsti,$idColecao){
        $perInst = $this->livroService->removerColecaoInstituicao($idInsti,$idColecao);
        if($perInst){
            $this->atualizaPermissaoEscolasLivro($idInsti);
            return true;
        }
        else{
            return false;
        }

    }
    public function addColecaoLivro($dados)
    {
        $perInst = $this->livroService->addColecaoInstituicao($dados);
        if($perInst){
            $this->atualizaPermissaoEscolasLivro($dados['instituicao_id']);
            return true;
        }
        else{
            return false;
        }
    }

    public function permissaoColecaoLivro($dados){
        $perInst = $this->livroService->permissaoColecaoInstituicao($dados);
        if($perInst){
            $this->atualizaPermissaoEscolasLivro($dados['instituicao_id']);
            return true;
        }
        else{
            return false;
        }
    }
    public function removerColecaoAudio($idInst,$idColecao){
        $perInst = $this->bibliotecaService->removerColecaoAudioInstituicao($idInst,$idColecao);
        if($perInst){
            $this->atualizaPermissaoEscolasAudio($idInst);
            return true;
        }
        else{
            return false;
        }
    }

    public function addColecaoAudio($dados){
        $perInst = $this->bibliotecaService->addColecaoAudioInstituicao($dados);
        if($perInst){
            $this->atualizaPermissaoEscolasAudio($dados['instituicao_id']);
            return true;
        }
        else{
            return false;
        }
    }

    public function permissaoColecaoAudio($dados){
        $perInst = $this->bibliotecaService->permissaoColecaoAudioInstituicao($dados);
        if($perInst){
            $this->atualizaPermissaoEscolasAudio($dados['instituicao_id']);
            return true;
        }
        else{
            return false;
        }
    }
    public function removerColecaoDocumento($idInst,$idColecao){
        $perInst = $this->bibliotecaService->removerColecaoDocumentoInstituicao($idInst,$idColecao);
        if($perInst){
            $this->atualizaPermissaoEscolasDocumento($idInst);
            return true;
        }
        else{
            return false;
        }
    }

    public function addColecaoDocumento($dados){
        $perInst = $this->bibliotecaService->addColecaoDocumentoInstituicao($dados);
        if($perInst){
            $this->atualizaPermissaoEscolasDocumento($dados['instituicao_id']);
            return true;
        }
        else{
            return false;
        }
    }

    public function permissaoColecaoDocumento($dados){
        $perInst = $this->bibliotecaService->permissaoColecaoDocumentoInstituicao($dados);
        if($perInst){
            $this->atualizaPermissaoEscolasDocumento($dados['instituicao_id']);
            return true;
        }
        else{
            return false;
        }
    }

    public function removerColecaoProva($idInst,$idColecao){
        $perInst = $this->bibliotecaService->removerColecaoProvaInstituicao($idInst,$idColecao);
        if($perInst){
            $this->atualizaPermissaoEscolasProva($idInst);
            return true;
        }
        else{
            return false;
        }
    }

    public function addColecaoProva($dados){
        $perInst = $this->bibliotecaService->addColecaoProvaInstituicao($dados);
        if($perInst){
            $this->atualizaPermissaoEscolasProva($dados['instituicao_id']);
            return true;
        }
        else{
            return false;
        }
    }

    public function permissaoColecaoProva($dados){
        $perInst = $this->bibliotecaService->permissaoColecaoProvaInstituicao($dados);
        if($perInst){
            $this->atualizaPermissaoEscolasProva($dados['instituicao_id']);
            return true;
        }
        else{
            return false;
        }
    }

	public function atualizaPermissaoEscolasLivro($instId, $escolaId = null){
	   $escolas = Escola::where('instituicao_id',$instId);
        if($escolaId){
            $escolas = $escolas->where('id',$escolaId);
        }
       $escolas = $escolas->selectRaw('id')->get();
	   $instituicaoLivro = ColecaoLivroInstituicao::where('instituicao_id',$instId)->get()->toArray();
       $instituicaoLivroPermissao = ColecaoLivroInstituicaoPermissao::where('instituicao_id',$instId)->get()->toArray();
       $instituicaoLivroPermissaoPeriodo =ColecaoLivroInstituicaoPermissaoPeriodo::where('instituicao_id',$instId)->get()->toArray();

       foreach ($escolas as $e){
            DB::beginTransaction();
            try
            {
                ColecaoLivroEscolaPermissaoPeriodo::where('escola_id',$e->id)->delete();
                ColecaoLivroEscolaPermissao::where('escola_id',$e->id)->delete();
                ColecaoLivroEscola::where('escola_id',$e->id)->delete();
                foreach ($instituicaoLivro as $il){
                    $il['escola_id'] = $e->id;
                    $cl = new ColecaoLivroEscola($il);
                    $cl->save();
                }
                foreach ($instituicaoLivroPermissao as $il){
                    $il['escola_id'] = $e->id;
                    $cl = new ColecaoLivroEscolaPermissao($il);
                    $cl->save();
                }
                foreach ($instituicaoLivroPermissaoPeriodo as $il){
                    $il['escola_id'] = $e->id;
                    $cl = new ColecaoLivroEscolaPermissaoPeriodo($il);
                    $cl->save();
                }
               DB::commit();
            }
            catch (\Exception $e)
            {
               DB::rollback();
            }
       }
    }


    public function atualizaPermissaoEscolasAudio($instId, $escolaId = null){
        $escolas = Escola::where('instituicao_id',$instId);
        if($escolaId){
            $escolas = $escolas->where('id',$escolaId);
        }
        $escolas = $escolas->selectRaw('id')->get();
        $instituicaoAudio = ColecaoAudioInstituicao::where('instituicao_id',$instId)->get()->toArray();
        $instituicaoAudioPermissao = ColecaoAudioInstituicaoPermissao::where('instituicao_id',$instId)->get()->toArray();

        foreach ($escolas as $e){
            DB::beginTransaction();
            try
            {
                ColecaoAudioEscolaPermissao::where('escola_id',$e->id)->delete();
                ColecaoAudioEscola::where('escola_id',$e->id)->delete();
                foreach ($instituicaoAudio as $il){
                    $il['escola_id'] = $e->id;
                    $cl = new ColecaoAudioEscola($il);
                    $cl->save();
                }
                foreach ($instituicaoAudioPermissao as $il){
                    $il['escola_id'] = $e->id;
                    $cl = new ColecaoAudioEscolaPermissao($il);
                    $cl->save();
                }
                DB::commit();
            }
            catch (\Exception $e)
            {
                DB::rollback();
            }
        }
    }
    public function atualizaPermissaoEscolasDocumento($instId, $escolaId = null){
        $escolas = Escola::where('instituicao_id',$instId);
        if($escolaId){
            $escolas = $escolas->where('id',$escolaId);
        }
        $escolas = $escolas->selectRaw('id')->get();
        $instituicaoDocumento = ColecaoDocumentoInstituicao::where('instituicao_id',$instId)->get()->toArray();
        $instituicaoDocumentoPermissao = ColecaoDocumentoInstituicaoPermissao::where('instituicao_id',$instId)->get()->toArray();

        foreach ($escolas as $e){
            DB::beginTransaction();
            try
            {
                ColecaoDocumentoEscolaPermissao::where('escola_id',$e->id)->delete();
                ColecaoDocumentoEscola::where('escola_id',$e->id)->delete();
                foreach ($instituicaoDocumento as $il){
                    $il['escola_id'] = $e->id;
                    $cl = new ColecaoDocumentoEscola($il);
                    $cl->save();
                }
                foreach ($instituicaoDocumentoPermissao as $il){
                    $il['escola_id'] = $e->id;
                    $cl = new ColecaoDocumentoEscolaPermissao($il);
                    $cl->save();
                }
                DB::commit();
            }
            catch (\Exception $e)
            {
                DB::rollback();
            }
        }
    }

    public function atualizaPermissaoEscolasProva($instId, $escolaId = null){
        $escolas = Escola::where('instituicao_id',$instId);
        if($escolaId){
            $escolas = $escolas->where('id',$escolaId);
        }
        $escolas = $escolas->selectRaw('id')->get();
        $instituicaoProva = ColecaoProvaInstituicao::where('instituicao_id',$instId)->get()->toArray();
        $instituicaoProvaPermissao = ColecaoProvaInstituicaoPermissao::where('instituicao_id',$instId)->get()->toArray();

        foreach ($escolas as $e){
            DB::beginTransaction();
            try
            {
                ColecaoProvaEscolaPermissao::where('escola_id',$e->id)->delete();
                ColecaoProvaEscola::where('escola_id',$e->id)->delete();
                foreach ($instituicaoProva as $il){
                    $il['escola_id'] = $e->id;
                    $cl = new ColecaoProvaEscola($il);
                    $cl->save();
                }
                foreach ($instituicaoProvaPermissao as $il){
                    $il['escola_id'] = $e->id;
                    $cl = new ColecaoProvaEscolaPermissao($il);
                    $cl->save();
                }
                DB::commit();
            }
            catch (\Exception $e)
            {
                DB::rollback();
            }
        }
    }
}
