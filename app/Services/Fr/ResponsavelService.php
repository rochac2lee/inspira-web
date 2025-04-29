<?php
namespace App\Services\Fr;
use DB;
use App\Models\User;
use Hash;
use Illuminate\Support\Facades\Password;

class ResponsavelService {

    public function __construct( UsuarioService $usuarioService)
    {
        $this->usuarioService     = $usuarioService;
    }

    private function scopoQuery($idEscola, $request = null){
        $responsavel = User::where('escola_id',$idEscola)
            ->where('permissao','R')
            ->selectRaw('users.*');

        $responsavelPermissao = User::join('user_permissao','user_permissao.user_id','users.id')
            ->where('user_permissao.escola_id',$idEscola)
            ->where('user_permissao.permissao','R')
            ->selectRaw('users.*');

        /// filtro da pesquisa
        if(isset($request['pesquisa']) && $request['pesquisa']!='')
        {
            $pesquisa = $request['pesquisa'];
            $responsavel->where(function($q) use ($pesquisa) {
                $q->orWhere('nome_completo','like','%'.$pesquisa.'%')
                    ->orWhere('email','like','%'.$pesquisa.'%')
                    ->orWhere('users.id',$pesquisa);
            });
            $responsavelPermissao->where(function($q) use ($pesquisa) {
                $q->orWhere('nome_completo','like','%'.$pesquisa.'%')
                    ->orWhere('email','like','%'.$pesquisa.'%')
                    ->orWhere('users.id',$pesquisa);
            });
        }
        return $responsavel->union($responsavelPermissao)->with(['alunosDoResponsavel'=>function($q) use($idEscola){
            $q->where('fr_responsavel_aluno.escola_id',$idEscola);
        }]);
    }

	public function getLista($idEscola, $request = null)
	{
        $responsavel = $this->scopoQuery($idEscola, $request);
        return $responsavel->orderBy('nome_completo')
                            ->paginate(20);
	}

	public function inserir($dados){
        $nome =  $dados['nome'];
        $nome = explode(' ', $nome);
        $vetAlunos = [];
        if(isset($dados['aluno']) && count($dados['aluno'])>0) {
            foreach ($dados['aluno'] as $a) {
                $vetAlunos[] = [
                    'aluno_id' => $a,
                    'escola_id' => $dados['escola_id'],
                    'instituicao_id' => auth()->user()->instituicao_id,
                ];
            }
        }
        $resp = [
            'nome_completo' => $dados['nome'],
            'name'          => $nome[0],
            'email'         =>  trim(strtolower($dados['email'])),
            //'password'      => Hash::make(rand(11111111,99999999)),
            'password'      => Hash::make('123456'),
            'img_perfil'    => null,
            'permissao'     => 'R',
            'escola_id'     => $dados['escola_id'],
            'instituicao_id'=> auth()->user()->instituicao_id,
            'matricula'     => null,
            'ocupacao'      => 'Responsável',
            'privilegio_id' => null,
            'vetAluno'      =>  $vetAlunos,
        ];

        $addUsuario = $this->usuarioService->addUsuario($resp);
        //$status = Password::reset(['email'=>$resp['email']]);
        //if($addUsuario){

        //}
        return true;
    }

    public function editar($dados){
        $nome =  $dados['nome'];
        $nome = explode(' ', $nome);
        $vetAlunos = [];
        if(isset($dados['aluno']) && count($dados['aluno'])>0) {
            foreach ($dados['aluno'] as $a) {
                $vetAlunos[] = [
                    'aluno_id' => $a,
                    'escola_id' => $dados['escola_id'],
                    'instituicao_id' => auth()->user()->instituicao_id,
                ];
            }
        }
        $resp = [
            'id'            => $dados['id'],
            'nome_completo' => $dados['nome'],
            'name'          => $nome[0],
            'email'         =>  trim(strtolower($dados['email'])),
            'permissao'     => 'R',
            'vetAluno'      =>  $vetAlunos,
        ];
        return $this->usuarioService->updateUsuario($resp);
    }

    public function excluir($idEscola, $idResponsavel){

        $resp = [
            'id'    => $idResponsavel,
            'escola_id' => $idEscola,
            'permissao' => 'R',
        ];

        return $this->usuarioService->removeUsuario($resp,0);
    }

    public function get($idEscola, $idResponsavel){
        $responsavel = $this->scopoQuery($idEscola);
        return $responsavel->where('id', $idResponsavel)->first();
    }
}
