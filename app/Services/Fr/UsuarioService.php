<?php
namespace App\Services\Fr;
use App\Models\FrTurma;
use DB;
use App\Models\AlunoCicloEtapa;
use App\Models\Professor;
use App\Models\ProfessorEscola;
use App\Models\ResetToken;

use App\Models\User;
use App\Models\UserPermissao;
use App\Models\Escola;
use App\Models\Instituicao;
use App\Models\UserLogAtividade;
use Hash;
use Illuminate\Support\Facades\Storage;
use Request;

class UsuarioService {

    public function __construct()
    {
        $this->guardName = whatGuard();
    }

    public function scopoQueryUsuarios($idEscola = null, $pesquisa = null)
    {
        $sql = User::orderBy('id');
        if($idEscola){
            $sql = $sql->where('escola_id',$idEscola);
        }

        /// filtro da pesquisa
        if(isset($pesquisa['nome']) && $pesquisa['nome']!='')
        {
            $sql->where(function($q) use ($pesquisa){
                $q->orWhere('users.nome_completo','like','%'.$pesquisa['nome'].'%')
                    ->orWhere('users.id',$pesquisa['nome'])
                    ->orWhere('users.email','like','%'.$pesquisa['nome'].'%');
            });
        }

        if(isset($pesquisa['email']) && $pesquisa['email']!='')
        {
            $sql->where('users.email','like','%'.$pesquisa['email'].'%');
        }

        return $sql;
    }

    public function getListaGeral( $pesquisa = null)
    {
        $sql = $this->scopoQueryUsuarios(null, $pesquisa);
        $sql = $sql->with(['permissoes'=>function($q){
                $q->leftJoin('escolas','escolas.id','user_permissao.escola_id')
                    ->whereNull('escolas.deleted_at')
                    ->selectRaw('user_permissao.*, escolas.titulo as escola');
            }]
        )
            ->leftJoin('escolas', 'escolas.id','users.escola_id')
            ->leftJoin('instituicao','instituicao.id','users.instituicao_id')
            //->whereNull('escolas.deleted_at')
            ->selectRaw('users.*, escolas.titulo as escola, instituicao.titulo as instituicao');
        return $sql->paginate(20);
    }

    public function getForm($id)
    {
        $sql = $this->scopoQueryUsuarios();
        $sql = $sql->with(['permissoes'=>function($q){
                $q->join('escolas','escolas.id','user_permissao.escola_id')
                    ->selectRaw('user_permissao.*, escolas.titulo as escola');
            }]
        )
            ->join('escolas','escolas.id','users.escola_id')
            ->selectRaw('users.*, escolas.titulo as escola');
        $user = $sql->find($id);
        $dados['id'] = $user->id;
        $dados['nome_completo'] = $user->nome_completo;
        $dados['email'] = $user->email;
        $dados['permissoes'][] =[
            'permissao' => $user->getRawOriginal('permissao'),
            'nome_permissao' => nomePermissao($user->getRawOriginal('permissao')),
            'escola_id' => $user->getRawOriginal('escola_id'),
            'instituicao_id' => $user->getRawOriginal('instituicao_id'),
            'escola' => $user->escola,
        ];
        foreach($user->permissoes as $p){
            $dados['permissoes'][] =[
                'permissao' => $p->permissao,
                'nome_permissao' => nomePermissao($p->permissao),
                'escola_id' => $p->escola_id,
                'instituicao_id' => $p->instituicao_id,
                'escola' => $p->escola,
            ];
        }

        return $dados;
    }

    public function novaSenha($id)
    {
        try {
            $user = User::find($id);
            $dados = ['password' => Hash::make('123456')];
            $user->update($dados);
            return true;
        }
        catch (\Exception $e)
        {
            return false;
        }
    }
    public function excluir($id)
    {
        return User::find($id)->delete();
    }

    public function add($dados)
    {
        $dados['nome_completo'] =   ucwords(strtolower($dados['nome_completo']));
        $dados['email'] =  trim(strtolower($dados['email']));

        $nome = explode(' ', $dados['nome_completo']);
        $dados['name'] = $nome[0];
        $dados['password'] = Hash::make('123456');
        $dados['img_perfil'] = null;

        $i =0;
        $certo = false;
        foreach ($dados['permissao'] as $p){
            $d = $dados;
            $d['permissao']         = $p;
            $d['escola_id'] = $dados['escola_id'][$i];
            $d['instituicao_id']    = $dados['instituicao_id'][$i];
            $d['privilegio_id']     = privilegioId($p);
            $d['ciclo_id']          = 5;
            $d['cicloetapa_id']    = 22;

            if($this->addUsuario($d, 1)===true)
            {
                $certo =true;
            }
            $i++;
        }
        return $certo;
    }

    public function editar($dados)
    {
        //$dados['nome_completo'] =   ucfirst(strtolower($dados['nome_completo']));
        $dados['nome_completo'] = $dados['nome_completo'];
        $dados['email'] =  trim(strtolower($dados['email']));

        $nome = explode(' ', $dados['nome_completo']);
        $dados['name'] = $nome[0];

        $d=[
            'name'          =>  $dados['name'],
            'nome_completo' => $dados['nome_completo'],
            'email'         => $dados['email'],
        ];

        $user = User::find($dados['id']);
        $user->update($d);
        $user->delete(); //// faz o delete apenas para a funçao addUsuario restaurar ele e refazer as permissões
        $i =0;
        $certo = false;

        foreach ($dados['permissao'] as $p){
            $d = $dados;
            $d['permissao']         = $p;
            $d['escola_id']         = $dados['escola_id'][$i];
            $d['instituicao_id']    = $dados['instituicao_id'][$i];
            $d['privilegio_id']     = privilegioId($p);
            $d['ciclo_id']          = 5;
            $d['cicloetapa_id']    = 22;
            if($this->addUsuario($d, 1)===true)
            {
                $certo =true;
            }
            $i++;
        }
        return $certo;
    }

    public function getEscolas($dados){
        $escolas = Escola::where('instituicao_id',$dados['id'])
            ->orderBy('titulo')
            ->get();
        $retorno=[];
        foreach ($escolas as $e) {
            $obj = new \stdClass();
            $obj->text = $e->titulo;
            $obj->value = $e->id;
            $retorno[] = $obj;
        }
        return $retorno;
    }

    public function getListaAlunos($idEscola, $pesquisa = null)
    {
        $sql = $this->scopoQueryUsuarios($idEscola, $pesquisa);
        $sql = $sql->where('permissao','A');

        return $sql->paginate(20);
    }

    public function tokenNovoUsuario($usuario)
    {
        $token = md5($usuario->email.$usuario->name.$usuario->id);
        $dados = [
            'email' => $usuario->email,
            'token' => $token,
        ];

        ResetToken::where('email',$usuario->email)->delete();
        $reset = new ResetToken($dados);
        return $reset->save();
    }

    public function gravaSessaoUsuario()
    {
        $dadosUser = ['user_id'=> auth($this->guardName)->user()->id];
        $user = new UserLogAtividade($dadosUser);
        $user->save();
        //// seleciona os dados da escola do usaurio que acabou de logar
        $dados = Escola::with('endereco')->find(auth($this->guardName)->user()->escola_id);
        $endereco = null;


        if(isset($dados->endereco) && count($dados->endereco)>0)
        {
            $endereco = $dados->endereco[0];
        }
        /// se existir escola
        if($dados){
            $instId = $dados->instituicao_id;
            $escola = [
                'titulo'    => $dados->titulo,
                'capa'      => $dados->capa,
                'endereco'  => $endereco,
                'id'        => $dados->id,
            ];
        }
        else{
            $instId = $user->instituicao_id;
            $escola = [
                'titulo'    => '',
                'capa'      => '',
                'endereco'  => '',
                'id'        => '',
            ];
        }
            /// seleciona os dados da instituicao do usuário que acabou de logar
        $inst = Instituicao::find($instId);
        if($inst) {
            $instituicao = [
                'tipo'  => $inst->instituicao_tipo_id,
                'id'    => $inst->id,
                'permissao_ead'    => $inst->permissao_ead,
                'permissao_indica' => $inst->permissao_indica,
            ];
        }
        else{
            $instituicao = [
                'tipo'  => '',
                'id'    => '',
                'permissao_ead'    => '',
                'permissao_indica' => '',
            ];
        }

        session(['escola'=>$escola]);
        session(['instituicao'=>$instituicao]);
    }

    public function listaPermissoes()
    {
        /// variaveis para ordenacao
        $retorno = [];

        /// select
        $perm = User::with(['permissoes'=>function($q){
                $q->with(['escola'=>function($query){
                    $query->where('status_id',1);
                }])
                ->with(['instituicao'=>function($query){
                    $query->with('estiloAgenda')->where('status_id',1);
                }]);
        }])
            ->find(auth($this->guardName)->user()->getRawOriginal('id'));

        ///// permissao orignal do usuario
        $original = new \stdClass();
        $original->permissao 	= $perm->getRawOriginal('permissao');
        $original->id 			= 0;

        $inst = Instituicao::where('status_id',1)->find(auth($this->guardName)->user()->getRawOriginal('instituicao_id'));
        $escola = Escola::where('status_id',1)->find(auth($this->guardName)->user()->getRawOriginal('escola_id'));
        if($inst && $escola) {
            $original->escola = $escola;
            $original->escola_id = $escola->id;
            $original->instituicao = $inst;
            $original->instituicao_id = $inst->id;
        }elseif($inst && $original->permissao == 'I'){
            $original->escola = null;
            $vetEscola = Escola::where('instituicao_id', auth($this->guardName)->user()->getRawOriginal('instituicao_id'))
                ->selectRaw('upper(trim(escolas.titulo)) as escola, escolas.id as id')
                ->orderBy(DB::raw('trim(titulo)'))
                ->get()->toArray();
            $original->escola_id = $vetEscola;
            $original->instituicao = $inst;
            $original->instituicao_id = $inst->id;
        }
        else{
            $original = null;
        }

        /// prepara vetor para foreach
        $permissoes = $perm->permissoes;
        if($original) {
            $permissoes[] = $original;
        }
        $qtdInst = 1;
        foreach ($permissoes as $p) {
            if($p->escola && $p->permissao != 'I') {
                if ($p->permissao == 'R') {
                    $retorno[$p->escola->titulo][3] = $p;
                    $alunos = User::with('alunosDoResponsavel')->find(auth($this->guardName)->user()->getRawOriginal('id'));
                    $vetAlunos = [];
                    foreach($alunos->alunosDoResponsavel as $a){
                        $vetAlunos[$a->id] = $a->nome;
                    }
                    $p->vetAlunos = $vetAlunos;
                }elseif ($p->permissao == 'A') {
                    $retorno[$p->escola->titulo][2] = $p;
                } elseif ($p->permissao == 'P') {
                    $retorno[$p->escola->titulo][1] = $p;
                } elseif ($p->permissao == 'C') {
                    $retorno[$p->escola->titulo][0] = $p;
                }
            }elseif ($p->instituicao && $p->permissao == 'I') {
                $vetEscola = Escola::where('instituicao_id', $p->instituicao_id)
                    ->select(DB::raw('upper(trim(escolas.titulo)) as escola, escolas.id as id'))
                    ->orderBy(DB::raw('trim(titulo)'))
                    ->get()->toArray();

                $p->escola_id = $vetEscola;
                $retorno[0][$qtdInst] = $p;
                $qtdInst++;
            }
            if ($p->permissao == 'Z') {
                $retorno[0][0] = $p;
            }
        }

        if(count($retorno)>0) {
            ksort($retorno);
            foreach ($retorno as $key => $value) {
                ksort($value);
                $retorno[$key] = $value;
            }
            if (isset($retorno[0])) {
                $ret[0] = $retorno[0];
                unset($retorno[0]);
            }
            if(count($retorno)>0) {
                $ret[1] = $retorno;
            }
        }
        else{
            $ret = [];
        }

        return $ret;

    }

    public function alteraPermissao($dados)
    {
        //// se for usar a permissao da linha da tabela user
        if($dados['id'] == 0)
        {
            $permissao = User::where('id',auth($this->guardName)->user()->id);


        }
        else /// se for usar a permissao da tabela user_permissao
        {
            $permissao = UserPermissao::where('user_permissao.id',$dados['id'])
                ->where('user_permissao.user_id',auth($this->guardName)->user()->id);
        }

        $permissao = $permissao->where('permissao',$dados['permissao']);

        if($dados['escola_id']>0){
            if($dados['permissao']!= 'I') { /// não é gestor instituiconal
                $permissao = $permissao->where('escola_id', $dados['escola_id']);
            }else{ /// é gestor instituicional
                /// verifica se a escola pertence a instituicao
                $esc = Escola::where('instituicao_id',$dados['instituicao_id'])->find($dados['escola_id']);
                /// senão pertencer criar uma exxecao na consulta
                if(!$esc){
                    $permissao = $permissao->whereNull('users.id');
                }
            }
        }

        if($dados['instituicao_id']>0){
            $permissao = $permissao->where('instituicao_id',$dados['instituicao_id']);
        }

        $permissao = $permissao->first();

        if($permissao == null )
        {
            return false;
        }
        else
        {
            if($dados['permissao'] == 'R' ){
                if( $this->guardName != 'api'){
                    $aluno = User::with(['turmaDeAlunos'])->find($dados['aluno_id']);
                    $dados['alunoDoResponsavel'] = $aluno->name;
                    $turmas = $aluno->turmaDeAlunos;
                    $vetTurmas = [];
                    foreach ($turmas as $t){
                        $vetTurmas[]=$t->id;
                    }
                    $dados['turmasAlunoDoResponsavel'] = $vetTurmas;
                }else{
                    $aluno = User::with(['alunosDoResponsavel'])->find(auth($this->guardName)->user()->id);
                    $vetTurmas = [];
                    foreach($aluno->alunosDoResponsavel as $a){
                        $al[] = $a->id;
                        foreach($a->turmaDeAlunos as $t){
                            $vetTurmas[]=$t->id;
                        }
                    }
                    $dados['turmasAlunoDoResponsavel'] = $vetTurmas;
                }
            }

            $this->criarSessaoGeralUsuario($dados);
            $this->criarSessaoMultiUsuario($dados);
            return true;
        }

    }

    public function criarSessaoGeralUsuario($user)
    {
        //// seleciona os dados da escola do usaurio que acabou de logar
        if($user['escola_id']>0)
        {
            $dados = Escola::with('endereco')->find($user['escola_id'] );
            $endereco = null;
            if(isset($dados->endereco) && count($dados->endereco)>0)
            {
                $endereco = $dados->endereco[0];
            }
            $escola = [
                'titulo'      =>$dados->titulo,
                'capa'      =>$dados->capa,
                'endereco'  => $endereco,
                'id'  => $dados->id,
            ];
            session(['escola'=>$escola]);
        }
        /// seleciona os dados da instituicao do usuário que acabou de logar
        if($user['instituicao_id']>0)
        {
            $inst = $user['instituicao_id'];
        }
        else
        {
            $inst = $dados->instituicao_id;
        }
        $dados = Instituicao::find($inst);
        $instituicao = [
            'tipo'  =>$dados->instituicao_tipo_id,
            'id'    =>$dados->id,
            'permissao_ead'    =>$dados->permissao_ead,
            'permissao_indica'    =>$dados->permissao_indica,
        ];
        session(['instituicao'=>$instituicao]);
        if(isset($user['alunoDoResponsavel']) && $user['alunoDoResponsavel'] != ''){
            session(['alunoDoResponsavel' => $user['alunoDoResponsavel'] ]);
        }
        if(isset($user['turmasAlunoDoResponsavel']) && $user['turmasAlunoDoResponsavel'] != ''){
            session(['turmasAlunoDoResponsavel' => $user['turmasAlunoDoResponsavel'] ]);
        }
    }

    public function criarSessaoMultiUsuario($dados)
    {
        session(['multiPermissoesEscolhido'=>1]);
        session(['dadosPermissoesEscolhido'=>$dados]);
    }

    public function addUsuario($dados, $apenasProfessor = null)
    {
        DB::beginTransaction();
        try
        {
            $users = User::where('email',$dados['email'])->withTrashed()->first();

            if(isset($users->id) && $users->deleted_at!=''){
                UserPermissao::where('user_id',$users->id)
                    ->withoutGlobalScopes()
                    ->delete();
                $novo = [
                    'permissao'     => $dados['permissao'],
                    'escola_id'     => $dados['escola_id'],
                    'instituicao_id'=> $dados['instituicao_id'],
                    'matricula'     => $dados['matricula'],
                    'ocupacao'      => $dados['ocupacao'],
                    'privilegio_id' => $dados['privilegio_id'],
                    'status_id'     => 1,
                    'deleted_at'    => null,
                ];
                $users->update($novo);
            }
            elseif(isset($users->id) && $users->id > 0)
            {
                $dados['user_id'] 		 = $users->id;

                $permissao = UserPermissao::where('user_id',$dados['user_id'])
                    ->where('escola_id',$dados['escola_id'])
                    ->where('instituicao_id',$dados['instituicao_id'])
                    ->where('permissao',$dados['permissao'])
                    ->withoutGlobalScopes()
                    ->first();
                if($permissao == null){
                    $permissao = new UserPermissao($dados);
                    $permissao->save();
                }
            }
            else
            {
                $users = new User($dados);
                $users->save();
            }
            if($dados['permissao']=='P'){
                $this->addUsuarioProfessor($users->id, $dados, $apenasProfessor);
            }elseif($dados['permissao']=='A'){
                $this->addUsuarioAluno($users->id, $dados);
                $this->addResponsavelEmAluno($users->id, $dados);
            }elseif($dados['permissao']=='R'){
                $users->alunosDoResponsavel()->attach($dados['vetAluno']);
            }

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            dd($e);
            return $e->getMessage();
        }
    }

    public function updateUsuario($dados, $apenasProfessor = null)
    {
        DB::beginTransaction();
        try
        {
            if(isset($dados['id']) && $dados['id']>0){
                $users = User::find($dados['id']);
            }else{
                $users = User::where('email',$dados['email'])->first();
            }
            $novo = [
                'name'          => $dados['name'],
                'email'          => $dados['email'],
                'nome_completo' => $dados['nome_completo'],
            ];
            if(isset($dados['ocupacao'])) {
                $novo['ocupacao'] = $dados['ocupacao'];
            }
            if(isset($dados['genero'])) {
                $novo['genero'] = $dados['genero'];
            }
            $users->update($novo);

            if($dados['permissao']=='A') {
                $this->addUsuarioAluno($users->id, $dados);
                $this->addResponsavelEmAluno($users->id, $dados);
            }elseif($dados['permissao']=='P'){
                $this->addUsuarioProfessor($users->id, $dados, $apenasProfessor);
            }elseif($dados['permissao']=='R'){
                //$users->alunosDoResponsavel()->detach();
                $users->alunosDoResponsavel()->attach($dados['vetAluno']);
            }
            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return $e->getMessage();
        }
    }

    private function addResponsavelEmAluno($aluno_id, $dados){
        if(isset($dados['email_responsavel']) && $dados['email_responsavel'] != '' && $dados['nome_responsavel'] != '' ){
            $dados['permissao'] = 'R';
            $dados['email'] = $dados['email_responsavel'];

            $nome = explode(' ',$dados['nome_responsavel']);
            $dados['name'] 			= ucfirst(mb_strtolower($nome[0]));
            $dados['nome_completo'] 	= ucwords(mb_strtolower($dados['nome_responsavel']));
            $dados['vetAluno'][] = [
                                    'aluno_id'      =>$aluno_id,
                                    'instituicao_id'=>$dados['instituicao_id'],
                                    'escola_id'     =>$dados['escola_id'],
                                ];

            if($this->ehNovoUsuario($dados['email_responsavel'], $dados['escola_id'], 'R')) {
                $this->addUsuario($dados);
            }else{
                $this->updateUsuario($dados);
            }
        }
    }

    private function ehNovoUsuario($email, $escolaId, $permissao){

        $usuario = User::where('email',$email)
            ->first();
        if($usuario != null)
        {
            $usuarioEscola = User::where('escola_id',$escolaId)
                ->where('permissao',$permissao)
                ->find($usuario->id);

            $usuarioEscolaPermissao = UserPermissao::where('escola_id',$escolaId)
                ->where('permissao',$permissao)
                ->where('user_permissao.user_id',$usuario->id)
                ->first();

            if($usuarioEscola == null && $usuarioEscolaPermissao == null)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return true;
        }
    }

    private function addUsuarioProfessor($userId, $dados, $apenasProfessor = null)
    {
        Professor::firstOrCreate([
            'user_id' => $userId
        ]);

        ProfessorEscola::firstOrCreate([
            'user_id' => $userId,
            'escola_id' => $dados['escola_id']
        ]);
        if($apenasProfessor==null) {
            $this->addProfessorComoAluno($userId, $dados);
        }
    }

    private function addProfessorComoAluno($userId,$dados)
    {
        $this->addUsuarioAluno($userId, $dados);
        $dadosPer = [
            'user_id'       => $userId,
            'permissao'     => 'A',
            'escola_id'     => $dados['escola_id'],
            'instituicao_id'=> $dados['instituicao_id'],
        ];

        UserPermissao::withoutGlobalScopes()->firstOrCreate($dadosPer);
    }

    private function addUsuarioAluno($userId, $dados){
        AlunoCicloEtapa::where('user_id', $userId)->delete();
        $aluno = new AlunoCicloEtapa([
            'user_id' => $userId,
            'ciclo_id' => $dados['ciclo_id'],
            'ciclo_etapa_id' => $dados['cicloetapa_id'],
        ]);

        if($dados['permissao'] == 'A' && isset($dados['turma'])){
            $turma = FrTurma::where('titulo',$dados['turma'])
                ->where('turno',$dados['turno'])
                ->where('escola_id',$dados['escola_id'])
                ->where('ciclo_etapa_id', $dados['cicloetapa_id'])
                ->with(['alunos'=>function($q) use($userId){
                    $q->where('users.id',$userId);
                }])
                ->first();
            if($turma == null ){
                $dadosTurma = [
                    'titulo' => $dados['turma'],
                    'turno' => $dados['turno'],
                    'escola_id' => $dados['escola_id'],
                    'ciclo_etapa_id' => $dados['cicloetapa_id'],
                ];
                $turma = new FrTurma($dadosTurma);
                $turma->save();
                $turma->alunos()->attach([$userId]);


            }else{
                if(isset( $turma->alunos) && count($turma->alunos)==0) {
                    $turma->alunos()->attach([$userId]);
                }
            }
        }


        $aluno->save();
    }

    public function removeUsuario($dados,$removerTodosDaEscola){
        if(isset($dados['id']) && $dados['id']>0)
        {
            $usuario = User::find($dados['id']);
        }else{
            $usuario = User::where('email',$dados['email'])->first();
        }

        if($usuario)
        {
            DB::beginTransaction();
            try
            {
                $permissao = UserPermissao::withoutGlobalScopes()
                    ->where('user_id',$usuario->id)
                    ->where('escola_id',$dados['escola_id']);
                if($removerTodosDaEscola == 0)
                {
                   $permissao = $permissao->where('permissao',$dados['permissao']);
                }
                $permissao->delete();
                $permissao = UserPermissao::withoutGlobalScopes()
                    ->where('user_id',$usuario->id)
                    ->get();
                if(count($permissao)==0){
                    if($usuario->getRawOriginal('escola_id') == $dados['escola_id'] && ($removerTodosDaEscola == 1 || ($removerTodosDaEscola == 0 && $usuario->getRawOriginal('permissao') == $dados['permissao']) ) ){
                        $usuario->delete();
                        DB::commit();
                        return true;
                    }
                    else{
                        DB::commit();
                        return true;
                    }
                }
                else{

                    if($usuario->escola_id == $dados['escola_id'] && ($removerTodosDaEscola == 1 || ($removerTodosDaEscola == 0 && $usuario->getRawOriginal('permissao') == $dados['permissao']) ) ){
                        $troca = $permissao[0];
                        $novo = [
                            'permissao'     => $troca->getRawOriginal('permissao'),
                            'escola_id'     => $troca->getRawOriginal('escola_id'),
                            'instituicao_id'=> $troca->getRawOriginal('instituicao_id'),
                            'matricula'     => $troca->matricula,
                            'ocupacao'      => $troca->ocupacao,
                        ];
                        $usuario->update($novo);
                        $troca->delete();
                        DB::commit();
                        return  true;
                    }
                    else{
                        DB::commit();
                        return true;
                    }
                }
            }
            catch (\Exception $e)
            {
                DB::rollback();
                return $e->getMessage();
            }
        }
        else
        {
            $usuario = User::where('email',$dados['email'])
                ->withTrashed()
                ->first();
            if($usuario) {
                return true;
            }
            else{
                return 'E-mail não encontrato para a exclusão.';
            }
        }

    }

    public function relatorioAcesso($dados, $idEscola = null, $pagina = null) {
        if(!isset($dados['data_inicial']) || !isset($dados['data_final']) || $dados['data_inicial']=='' || $dados['data_final'] == ''){
            return [];
        }
        $selecao = 'users.*, users.permissao as permissaoOriginal,user_log_atividade.tipo as tipo_acesso, user_log_atividade.created_at as data_logado, escolas.titulo as escola, instituicao.titulo as inst';
        $retorno = User::join('user_log_atividade','users.id','user_log_atividade.user_id')
            ->where('user_log_atividade.created_at', '>=',dataUs($dados['data_inicial']).' 00:00:00')
            ->where('user_log_atividade.created_at', '<=',dataUs($dados['data_final']).' 23:59:59')
            //->where('users.instituicao_id', '<=',1)
            ->join('escolas','users.escola_id','escolas.id')
            ->join('instituicao', 'escolas.instituicao_id', 'instituicao.id');
        if($idEscola != ''){
            $retorno = $retorno->where('escola_id',$idEscola);
        }

        if(isset($dados['acessoPor']) && $dados['acessoPor'] != ''){
            if($dados['acessoPor']!='t'){
                if($dados['acessoPor']=='w'){
                    $retorno = $retorno->whereNull('user_log_atividade.tipo');
                }else{
                    $retorno = $retorno->where('user_log_atividade.tipo',$dados['acessoPor']);
                }
            }
        }

        if(auth()->user()->permissao == 'I'){
            $retorno = $retorno->where('escolas.instituicao_id',auth()->user()->instituicao_id);
        }

        if(isset($dados['instituicaoTipo']) && $dados['instituicaoTipo'] != ''){
            $retorno = $retorno->where('instituicao.instituicao_tipo_id', $dados['instituicaoTipo']);
        }

        if(isset($dados['permissao']) && $dados['permissao'] != ''){
            $retorno = $retorno->where('users.permissao', $dados['permissao']);
        }

        if(@$dados['tipo']== 1){
            $retorno = $retorno->groupBy('users.id');
            $selecao .= ', count(users.id) as qtd';
        }

        if(isset($dados['ordenacao']) && $dados['ordenacao'] != ''){
            if($dados['ordenacao'] == 1){
                $retorno = $retorno->orderBy('instituicao.titulo');
            }elseif($dados['ordenacao'] == 2){
                $retorno = $retorno->orderBy('escolas.titulo');
            }elseif($dados['ordenacao'] == 3){
                $retorno = $retorno->orderBy('users.permissao');
            }elseif($dados['ordenacao'] == 4){
                $retorno = $retorno->orderBy('users.nome_completo');
            }
        }
        else{
            $retorno = $retorno->orderBy('user_log_atividade.created_at', 'DESC');
        }

        $retorno = $retorno->selectRaw($selecao);

        if($pagina) {
            return $retorno->paginate(50);
        }else{
            return $retorno->get();
        }
    }

    public function downloadRelatorioAcessos($dados){
        ///$value = iconv('UTF-8', 'Windows-1252', $value);
        $rel = $this->relatorioAcesso($dados, @$dados['escola']);
        $csv = [];
        $conteudo = [];
        $conteudo[] = iconv('UTF-8', 'Windows-1252', 'Código do usuário');
        $conteudo[] = iconv('UTF-8', 'Windows-1252', 'Usuário');
        $conteudo[] = iconv('UTF-8', 'Windows-1252', 'E-mail');
        $conteudo[] = iconv('UTF-8', 'Windows-1252', 'Permissão');
        $conteudo[] = iconv('UTF-8', 'Windows-1252', 'Acesso por');
        $conteudo[] = iconv('UTF-8', 'Windows-1252', 'Instituição');
        $conteudo[] = iconv('UTF-8', 'Windows-1252', 'Escola');

        if($dados['tipo']!=1){
            $conteudo[] =iconv('UTF-8', 'Windows-1252','Data');
        }else{
            $conteudo[] = iconv('UTF-8', 'Windows-1252','Quantidade');
        }
        $csv[]=$conteudo;

        foreach ($rel as $r){
            $conteudo = [];
            $conteudo[]=$r->id;
            $conteudo[]=iconv('UTF-8', 'Windows-1252//IGNORE', $r->nome);
            $conteudo[]=iconv('UTF-8', 'Windows-1252//IGNORE', $r->email);
            $conteudo[]=iconv('UTF-8', 'Windows-1252//IGNORE', $r->permissaoOriginal);
            if($r->tipo_acesso == '') {
                $conteudo[]=iconv('UTF-8', 'Windows-1252//IGNORE', 'WEB');
            }elseif($r->tipo_acesso == 'agenda'){
                $conteudo[]=iconv('UTF-8', 'Windows-1252//IGNORE', 'App Agenda');
            }elseif($r->tipo_acesso == 'opet'){
                $conteudo[]=iconv('UTF-8', 'Windows-1252//IGNORE', 'App Opet');
            }

            $conteudo[]=iconv('UTF-8', 'Windows-1252//IGNORE',$r->inst);
            $conteudo[]=iconv('UTF-8', 'Windows-1252//IGNORE', $r->escola);

            if($dados['tipo']!=1){
                $conteudo[]=iconv('UTF-8', 'Windows-1252//IGNORE',dataBR($r->data_logado));
            }else{
                $conteudo[]=iconv('UTF-8', 'Windows-1252//IGNORE',$r->qtd);
            }
            $csv[]= $conteudo;
        }

        $nomeArquivo = md5(date('H:i:s')).'.csv';
        $caminho = config('app.frTmp').$nomeArquivo;
        $fp = fopen($caminho, 'x+');

        foreach ($csv as $fields) {
            fputcsv($fp, $fields, ';');
        }

        fclose($fp);
        $putEm = 'upload_usuario/relatorio_acesso/'.$nomeArquivo;
        Storage::disk()->put($putEm, file_get_contents($caminho));
        return $putEm;
    }

    public function setaDeviceKey($tipo, $chave){
        if($tipo != '') {
            if ($tipo == 'agenda') {
                $dados['device_key_agenda'] = null;
                $dados['notificacao_ativa_agenda'] = 0;
                if ($chave != '') {
                    $dados['device_key_agenda'] = $chave;
                    $dados['notificacao_ativa_agenda'] = 1;
                }
            }
            $user = User::find(auth()->user()->id);
            return $user->update($dados);
        }
    }

    public function trocaSenha($senha){
        DB::beginTransaction();
        try
        {
            $user = User::find(auth()->user()->id);
            $user->update(['password' => Hash::make($senha)]);
            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

}
