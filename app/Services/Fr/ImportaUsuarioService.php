<?php
namespace App\Services\Fr;
use App\Models\CicloEtapa;
use App\Models\FrImportacaoUser;
use App\Models\FrImportacaoUsersLog;
use App\Jobs\ProcessaArquivoUsuario;
use App\Models\User;
use App\Models\UserPermissao;
use Hash;
use Validator;

use App\Http\Requests\Fr\ProfessorRequest;
use App\Services\Fr\UsuarioService;

use App\Models\Escola;

class ImportaUsuarioService {

	public function __construct()
    {
        $this->usuarioService = new UsuarioService();
    }

	public function importar($request,$tipoUsuario = null){
	    //$path = Storage::disk('s3')->put('directory_name/'.$user->id, $request->file('file_name'), 'public');
        ///$upload = $request->file('arquivo')->store('private/upload_usuario/'.auth()->user()->id);

        ///
        $upload = $request->file('arquivo')->store(config('app.frStorage').'upload_usuarios_novo/');

        /// cria a linha de log na tabela
        $nome_arquivo 	= explode('/',$upload);
        $nome_arquivo 	= $nome_arquivo[count($nome_arquivo)-1];
        $request->file('arquivo')->move(config('app.frTmp'), $nome_arquivo);

        $dados = [
            'arquivo' 		=> $nome_arquivo,
            'user_id' 		=> auth()->user()->id,
            'tipo_arquivo' 	=> $request->input('tipo_arquivo'),
            'nome_original' => $request->file('arquivo')->getClientOriginalName(),
        ];
        $logUsers = new FrImportacaoUser($dados);
        $logUsers->save();
        switch ($request->input('tipo_arquivo')) {
            case 2:
                return $this->importaTipoGoogleFila($nome_arquivo,$logUsers->id,$request,$tipoUsuario);
                break;
        }
    }

    private function importaTipoGoogleFila($nome_arquivo, $logUserId,$request,$tipoUsuario)
    {
        $arquivo = fopen(config('app.frTmp').$nome_arquivo, 'r');
        $iniciar = 0;
        $count = 1;
        //// percorre o arquivo
        while (!feof($arquivo)) {
            $linha = fgetcsv($arquivo, 0, ',');
            $linha = array_map('trim',$linha);
            if($iniciar == 1){ //// se a primeira linha de usuarios for encontrada
                if(trim($linha[1]) != ''){ /// se é uma linha preenchida
                    $dados = [
                        'tipoArquivo'   => 1,
                        'linha'         => $linha,
                        'numero_linha'  => $count,
                        'instituicao_id'=> $request->input('instituicao_id'),
                        'escola_id'     => $request->input('escola_id'),
                        'tipoUsuario'   => $tipoUsuario,
                        'log_id'        => $logUserId,
                    ];
                    dispatch((new ProcessaArquivoUsuario($dados)));
                   // $this->semFila($dados);
                }
            }
            elseif($linha[0] == 'MATRÍCULA') /// encontrou o inicio da leitura do arquivo
            {
                $iniciar = 1;
            }
            $count++;
        }
        fclose($arquivo);
        return true;
    }

    public function semFila($dados)
    {
        if($dados['tipoArquivo'] == 1)
        {
            $dadosTratados = $this->trataDadosTipoGoogle($dados['linha'], $dados['instituicao_id'], $dados['escola_id'], $dados['tipoUsuario'], $dados['numero_linha']);
        }

        $this->ValidaInsereDeletaUsuario($dadosTratados, $dados['log_id']);
    }

    public function trataDadosTipoGoogle($linha, $idInstituicao, $idEscola, $tipoUsuario, $count)
    {
        $linha = array_map('trim',$linha);
        $perfil = $tipoUsuario;
        /// senão tiver tipo de usuario nao tem campos de input da escola, mas tem na planilha
        if($tipoUsuario == null)
        {
            $perfil = $this->perfilGoogle($linha[6]);

            $escola = Escola::find($linha[5]);
            if($escola != null){
                $idEscola       = $escola->id;
                $idInstituicao  = $escola->instituicao_id;
            }else{
                $idEscola       = null;
                $idInstituicao  = null;
            }

        }
        $ciclo = null;
        $cicloEtapa = null;
        if($perfil != 'A')
        {
            $cicloEtapa = 22;
            $ciclo = 5;
        }else{
            if($linha[7] != ''){
                $cicloEtapa = $linha[7];
                $aux = CicloEtapa::find($linha[7]);
                $ciclo = $aux->ciclo_id;
            }
        }

        //$ciclo = $this->cicloIdGoogle($linha[5]);
        $nome = explode(' ',$linha[2]);

        $retorno = [];
        $retorno['matricula'] 		= ($linha[0]);
        $retorno['name'] 			= ucfirst(mb_strtolower($nome[0]));
        $retorno['nome_completo'] 	= ucwords(mb_strtolower($linha[2]));
        $retorno['email'] 			= mb_strtolower($linha[1]);
        $retorno['escola_id'] 		= $idEscola;
        $retorno['instituicao_id'] 	= $idInstituicao;
        $retorno['permissao'] 		= $perfil;
        $retorno['ocupacao'] 		= ucfirst(mb_strtolower($linha[6]));
        $retorno['status_id'] 		= 1;
        $retorno['genero'] 			= ucfirst(mb_strtolower($linha[12]));
        $retorno['privilegio_id'] 	= $this->privilegioId($perfil);
        if(str_contains($retorno['email'],'@souopet') || str_contains($retorno['email'],'@opeteducation')){
            $retorno['password']    = Hash::make(rand(11111111,99999999));
        }
        else{
            $retorno['password'] 	= Hash::make('123456');
        }
        $retorno['ciclo_id'] 		= $ciclo;
        $retorno['cicloetapa_id'] 	= $cicloEtapa;
        $retorno['turma'] 	= $linha[9];
        $retorno['turno'] 	= ucfirst(mb_strtolower($linha[10]));
        /*
        if(trim($linha[14])!='') {
            $retorno['data_nascimento'] = dataUS($linha[14]);
        }
        */
        $retorno['inserir'] 	    = mb_strtolower($linha[14]) == 's' ? true : false;
        $retorno['linha'] 			= $count;
        $retorno['nome_responsavel'] = @$linha[16];
        $retorno['email_responsavel']= @$linha[15];
        return $retorno;
    }

    private function perfilGoogle($id)
    {
        $campo = trim(mb_strtolower($id));
        if(strpos($campo, 'estudante')!== false || strpos($campo, 'aluno')!== false){
            return 'A';
        }
        elseif(strpos($campo, 'coordenador')!== false || strpos($campo, 'diretor pedagógico')!== false || strpos($campo, 'diretor da unidade educativa')!== false){
            return 'C';
        }elseif(strpos($campo, 'diretor geral')!== false || strpos($campo, 'secretaria de educação')!== false){
            return 'I';
        }elseif(strpos($campo, 'professor')!== false || strpos($campo, 'docente')!== false){
            return 'P';
        }
    }

    private function privilegioId($perfil)
    {
        switch ( $perfil ) {
            case "A":
                return '4';
                break;
            case "P":
                return '3';
                break;
        }
    }

    private function cicloIdGoogle($id)
    {
        switch ( mb_strtolower($id) ) {
            case "educação infantil":
            case "educação infantil e anos iniciais":
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
            default:
                return 5;
        }
    }

    private function cicloEtapaIdGoogle($ciclo,$etapa)
    {
        if($ciclo == 1) {
            switch (mb_strtolower($etapa)) {
                case "infantil 1":
                    return 9;
                    break;
                case "infantil 2":
                    return 10;
                    break;
                case "infantil 3":
                    return 11;
                    break;
                case "infantil 4":
                    return 12;
                    break;
                case "infantil 5":
                    return 13;
                    break;
                default:
                    return 1;
            }
        }
        elseif($ciclo == 2) {
            switch (mb_strtolower($etapa)) {
                case "1º ano":
                    return 9;
                break;
                case "2º ano":
                    return 10;
                break;
                case "3º ano":
                    return 11;
                break;
                case "4º ano":
                    return 12;
                break;
                case "5º ano":
                    return 13;
                break;
                default:
                    return 2;
            }
        }elseif($ciclo == 3) {
            switch (mb_strtolower($etapa)) {
                case "6º ano":
                    return 14;
                    break;
                case "7º ano":
                    return 15;
                    break;
                case "8º ano":
                    return 16;
                    break;
                case "9º ano":
                    return 17;
                    break;
                default:
                    return 3;
            }
        }elseif($ciclo == 4) {
            switch (mb_strtolower($etapa)) {
                case "1º ano":
                    return 18;
                    break;
                case "2º ano":
                    return 19;
                    break;
                case "3º ano":
                    return 20;
                    break;
                default:
                    return 21;
            }
        }elseif($ciclo == 5) {
            return 22;
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
    /// funcao da fila

    public function ValidaInsereDeletaUsuario($dados, $logUserId)
    {
        $validaLinha = $this->validaLinha($dados);

        if($validaLinha===true)
        {
            if($dados['inserir']) {
                if($this->ehNovoUsuario($dados['email'], $dados['escola_id'], $dados['permissao'])) {
                    $add = $this->usuarioService->addUsuario($dados);
                }
                else{
                    $add = $this->usuarioService->updateUsuario($dados);
                }
            }
            else{
                $add = $this->usuarioService->removeUsuario($dados,1);
            }
            if($add!==true)
            {
                $this->gravaErroImportacao($dados, $logUserId, $add, 1);
            }
            else{
                FrImportacaoUser::find($logUserId)->increment('qtd_certo');
            }
        }
        else{
            $this->gravaErroImportacao($dados, $logUserId, $validaLinha);
        }
    }

    public function validaLinha($d)
    {
        if($d['inserir']) {
            /// instacia o request
            $requestLote = new ProfessorRequest;
            /// popula o request com os dados a serem validados
            $requestLote->replace($d);
            /// realiza a validação
            $csv_errors = Validator::make($d, $requestLote->rules(), $requestLote->messages(), $requestLote->attributes())->errors();
            /// se teve erro
            if ($csv_errors->any()) {
                return $csv_errors;
            }
        }
        return true;
    }

    public function gravaErroImportacao($dados, $logUserId, $erro, $erroBanco = null)
    {
        $msgErroBanco = '';
        $msgErroValidacao = '';
        if($erroBanco){
            $msgErroBanco = $erro;
        }
        else
        {
            $msgErroValidacao = serialize($erro);
        }

        $l =[
            'importacao_id' => $logUserId,
            'erro'          => 1,
            'erro_validacao'=> $msgErroValidacao,
            'erro_banco'    => $msgErroBanco,
            'nome'          => $dados['nome_completo'],
            'email'         => $dados['email'],
            'escola_id'     => $dados['escola_id'],
            'instituicao_id'=> $dados['instituicao_id'],
            'permissao'     => $dados['permissao'],
            'inserir'       => $dados['inserir'],
            'linha'         => $dados['linha'],
        ];
        $log = new FrImportacaoUsersLog($l);
        $log->save();
        FrImportacaoUser::find($logUserId)->increment('qtd_errado');

    }

    /*
     * Relatorios
     */
    public function lista_relatorio()
    {
        return FrImportacaoUser::join('users','users.id','fr_importacao_users.user_id')
            ->orderBy('created_at', 'desc')
            ->selectRaw('fr_importacao_users.*, users.name')
            ->paginate(20);
    }

    public function caminhoDownloadArquivo($id){
        $dados = FrImportacaoUser::find($id);
       /// return 'private/upload_usuario/'.$dados->user_id.'/'.$dados->arquivo;
        return config('app.frStorage').'upload_usuarios_novo/'.$dados->arquivo;
    }

    public function relatorioDetalhes($id){
        $dados = FrImportacaoUser::join('users','users.id','fr_importacao_users.user_id')
            ->selectRaw('fr_importacao_users.*, users.name')
            ->find($id)
            ->toArray();
        if($dados['acertos'] != '' || $dados['erros'] != ''){
            try
            {
                $dados['acertos']   = unserialize($dados['acertos']);
            }
            catch (\Exception $e)
            {
                $dados['acertos']   = [];
            }

            try
            {
                $dados['erros']     = unserialize($dados['erros']);
            }
            catch (\Exception $e)
            {
                $dados['erros']     = [];
            }
        }
        else{
            $dados = [
                'dados' => $dados,
                'log'   => FrImportacaoUsersLog::where('importacao_id',$id)
                                                ->where('erro',1)
                                                ->paginate(50),
            ];


        }

        return $dados;
    }
}
