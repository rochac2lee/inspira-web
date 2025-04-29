<?php

namespace App\Http\Controllers\Fr;

use App\Jobs\ProcessaArquivoUsuario;
use App\Jobs\ProcessaImportacaoImagensLivro;
use App\Models\ColecaoAudioEscolaPermissao;
use App\Models\ColecaoAudioInstituicao;
use App\Models\ColecaoAudioInstituicaoPermissao;
use App\Models\ColecaoLivroEscolaPermissao;
use App\Models\ColecaoLivroEscolaPermissaoPeriodo;
use App\Models\ColecaoLivroInstituicao;
use App\Models\ColecaoLivroInstituicaoPermissao;
use App\Models\ColecaoLivroInstituicaoPermissaoPeriodo;
use App\Models\ColecaoProvaEscolaPermissao;
use App\Models\ColecaoProvaInstituicao;
use App\Models\ColecaoProvaInstituicaoPermissao;
use App\Models\FrConteudo;
use App\Models\FrQuestao;
use App\Models\FrQuiz;
use App\Models\FrQuizPerguntas;
use App\Models\FrQuizResposta;
use App\Models\Instituicao;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
//use Chumper\Zipper\Zipper;
use DateTime;
use DateTimeZone;
use DateInterval;
use Illuminate\Support\Str;
use Hash;

use DB;
use App\Models\ColecaoLivros;
use App\Models\Conteudo;
use App\Models\Escola;
use App\Models\ConteudoInstituicaoEscola;
use App\Models\User;
use App\Models\ResetToken;
use App\Models\Professor;
use App\Models\ProfessorEscola;
use App\Models\InstituicaoUser;
use App\Models\AlunoCicloEtapa;
use App\Models\CicloEtapa;
use App\Models\ColecaoLivroEscola;
use App\Models\ColecaoAudioEscola;
use App\Models\ColecaoProvaEscola;
use App\Models\UserPermissao;
use App\Models\FrBncc;

class ImportacaoController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            if(Auth::user()->permissao != 'Z' )
            {
                return back();
            }
            return $next($request);
        });
    }

    private function dataUS($valor)
    {
        if($valor!='')
        {
            $valor = explode(' ', $valor);
            $valor = $valor[0];
            $valor = explode('/', $valor);
            if(count($valor) == 3)
            {
                return $valor[2].'-'.$valor[1].'-'.$valor[0];
            }
        }
    }

    public function erro()
    {
        return view('errors/500');
    }


    public function corrigeImportaUsuario()
    {
        /*
        $ret = DB::select('select users.id, escolas.instituicao_id from users inner join escolas on escolas.id = users.escola_id where importado is not null and not exists (select * from instituicao_users where users.id = instituicao_users.user_id)');

        foreach ($ret as $r) {
            InstituicaoUser::create([
                        'instituicao_id' => $r->instituicao_id,
                        'user_id' => $r->id
                    ]);
        }
        */

        $ret = DB::select("select users.id, escolas.id as escola_id from users inner join escolas on escolas.id = users.escola_id where importado is not null and permissao = 'P' ");
        foreach ($ret as $r) {
            Professor::firstOrCreate([
                'user_id' => $r->id
            ]);
            ProfessorEscola::firstOrCreate([
                'user_id' => $r->id,
                'escola_id' => $r->escola_id
            ]);
        }
        return 'certo';
    }

    public function usuarios($v)
    {
        $file = fopen('../storage/app/arquivos_rocha/usuariosv'.$v.'.csv','r');
        //$file = Storage::get('teste.csv');
        $i=0;
        while ( ($linha = fgetcsv($file, 200, ";")) !==FALSE) {
            if($i>0 && $linha[1]!=''){


                if($linha[4] == 'Z')
                {
                    $privilegio = 1;
                }
                else if($linha[4] == 'E')
                {
                    $privilegio = 5;
                }
                else if($linha[4] == 'G')
                {
                    $privilegio = 2;
                }
                else if($linha[4] == 'P')
                {
                    $privilegio = 3;
                }
                else if($linha[4] == 'A')
                {
                    $privilegio = 4;
                }
                else
                {
                    $privilegio = 3;
                }


                $primeiroNome = explode(' ', trim($linha[2]));
                $dados = [
                    'escola_id'     =>  $linha[1],
                    'name'          =>  $primeiroNome[0],
                    'nome_completo' =>  $linha[2],
                    'ocupacao'      =>  $linha[3],
                    'permissao'     =>  $linha[4],
                    'email'         =>  $linha[5],
                    'cpf'           =>  $linha[6],
                    'data_nascimento' =>  $this->dataUS($linha[7]),
                    'importado'     =>  $v,
                    'privilegio_id' =>  $privilegio,
                    'password'      =>  '$2y$10$Wi3Px4MP6qepUwA7Wt9Z.OdebkkLD3mXame.STS6zd9LZ3W/XXXVi',
                ];
                if(count($linha)>=10 && $linha[4] == 'A')
                {
                    $dados['ocupacao'] = $linha[8].' - '.$linha[9];
                }

                $users = User::where('email',$linha[5])->first();
                if(isset($users->id) && $users->id>0 && $users->permissao != '')
                {
                    unset($dados['password']);
                    $users->update($dados);
                    echo 'salvo<br>';
                }
                else
                {
                    $users = new User($dados);
                    $users->save();
                    echo 'novo<br>';

                }

                if($linha[4] == 'P')
                {
                    Professor::firstOrCreate([
                        'user_id' => $users->id
                    ]);

                    ProfessorEscola::firstOrCreate([
                        'user_id' => $users->id,
                        'escola_id' => $linha[1]
                    ]);

                }
                if($linha[4] == 'A' && count($linha)>=11)
                {
                    $cicloEtapa = CicloEtapa::find($linha[10]);
                    if(isset($cicloEtapa->id))
                    {
                        $aluno = AlunoCicloEtapa::where('user_id', $users->id)->first();
                        //$aluno->delete();

                        AlunoCicloEtapa::firstOrCreate([
                            'user_id' => $users->id,
                            'ciclo_id' => $cicloEtapa->ciclo_id,
                            'ciclo_etapa_id' => $cicloEtapa->id,
                        ]);
                    }
                    else
                    {
                        echo 'Erro: '.$linha[5].' ciclo_etapa nao encontrado. <br>';
                    }
                }

                $esc = Escola::find($linha[1]);
                    $intituicao_user = InstituicaoUser::firstOrCreate([
                        'instituicao_id' => $esc->instituicao_id,
                        'user_id' => $users->id
                    ]);

                if($linha[4] == 'P')
                {
                    $dados = [
                        'user_id'       => $users->id,
                        'permissao'     => 'A',
                        'escola_id'     => $linha[1],
                        'instituicao_id'=> $esc->instituicao_id,
                    ];

                    UserPermissao::withoutGlobalScopes()->firstOrCreate($dados);
                }
            }
            $i++;


        }
    }

    public function resetarSenha($v)
    {
        $user = User::where('importado',$v)->get();

        foreach($user as $u)
        {
            $token = md5($u->escola_id.$u->name.$u->id);
            $dados = [
                'email' => $u->email,
                'token' => $token,
            ];

            $reset = ResetToken::where('email',$u->email)->first();
            if(isset($reset->email)){
                $reset->update($dados);
            }
            else
            {
                $reset = new ResetToken($dados);
                $reset->save();
            }

        }
        return 'fim';
    }

    public function colecao()
    {
        $file = fopen('../storage/app/importa_livros/teste.csv','r');
    	//$file = Storage::get('teste.csv');
    	$i=1;
        while ( ($linha = fgetcsv($file, 200, ";")) !==FALSE) {
        	$dados = [
        		'id'	=> 	$linha[0],
        		'nome'	=> 	$linha[1],
        		'selo'	=> 	$linha[2],
        		'img'	=> 	$linha[3],
        		'ordem'	=> 	$linha[0],
        	];
        	$i++;
        	$colecao = new ColecaoLivros($dados);
        	$colecao->save();

        }
    }

    public function conteudo($nomeArquivo)
    {
        $etapaLivro = $this->defineEtapaLivro();
        $anoLivro = $this->defineAnoLivro();
        $disciplinaLivro = $this->defineDisciplinaLivro();

        $file = fopen('https://cf.opetinspira.com.br/livros/'.$nomeArquivo.'.csv','r');
        //$url = 'https://cf.opetinspira.com.br/livros/206.csv';
       // $file =   Storage::disk()->get(config('app.frStorage').'livros_teste/'.$nomeArquivo.'.csv');
       // $file = get_resources($file);
       // $file = file_get_contents($url);
        $i=0;
        $erro = '';


        while ( ($linha = fgetcsv($file, 0, ";")) !==FALSE) {
            if($i>0){

                        $dados = [
                        'tipo'              =>  21,
                        'user_id'           =>  1,
                        'status'            =>  1,
                        'colecao_livro_id'  =>  $linha[0],
                        'fonte'             =>  $linha[1],

                        'etapa_livro'       =>  $linha[2], ///// ciclo
                        'ano_livro'         =>  $linha[3], /// ciclo_etapa

                        'componente_livro'  =>  $linha[4], /// disciplina
                        'tipo_livro'        =>  $linha[5], /// criar na tabela conteudo (char2)
                        'titulo'            =>  $linha[6],
                        'descricao'         =>  trim($linha[7]),

                        'ciclo_id'          =>  $linha[2], ///// ciclo
                        'cicloetapa_id'     =>  $linha[3], /// ciclo_etapa
                        'disciplina_id'     =>  $linha[4], /// disciplina
                        'versao'            =>  $linha[8],
                        'periodo'           =>  $linha[9]=='' ? null : $linha[9],
                        ];

                        $diretorioOriginal = 'livros/'.trim($linha[7]);
                        $arquivos = Storage::files($diretorioOriginal);
                        $dados['qtd_paginas_livro'] = count($arquivos)-2;

                        $conteudo = new FrConteudo($dados);
                        $conteudo->save();

                        foreach ($arquivos as $a){
                            $aux = explode('/',$a);
                            $fileImg = $aux[count($aux)-1];
                            $para = config('app.frStorage').'livrodigital/'.$conteudo->id.'/'.$fileImg;

                            $dadosImg =[
                                'de' => $diretorioOriginal.'/'.$fileImg,
                                'para' => $para,
                            ];
                            dispatch((new ProcessaImportacaoImagensLivro($dadosImg)));
                        }
            }
            $i++;
        }
        return $erro;
    }

    private function defineAnoLivro()
    {
        $dados = [];
        $dados[1][1]=4;
        $dados[1][2]=5;
        $dados[1][3]=6;
        $dados[1][4]=7;
        $dados[1][5]=8;
        $dados[1]['todos']=1;
        $dados[2]['todos']=2;
        $dados[3]['todos']=3;
        $dados[4]['todos']=21;
        $dados[5]['todos']=22;
        $dados[5]['todas']=22;
        $dados[1]['ALL']=1;
        $dados[2]['ALL']=2;
        $dados[3]['ALL']=3;
        $dados[4]['ALL']=21;
        $dados[5]['ALL']=22;
        $dados[2][1]=9;
        $dados[2][2]=10;
        $dados[2][3]=11;
        $dados[2][4]=12;
        $dados[2][5]=13;
        $dados[3][6]=14;
        $dados[3][7]=15;
        $dados[3][8]=16;
        $dados[3][9]=17;
        $dados[4][1]=18;
        $dados[4][2]=19;
        $dados[4][3]=20;
        return $dados;
    }

    private function defineEtapaLivro()
    {
        return [
            'EF1' => 2,
            'F1' => 2,
            'EF2' => 3,
            'F2' => 3,
            'EI'  => 1,
            'EM'  => 4,
            'todas'  => 5,
            'todos'  => 5,
            'ALL'  => 5,
        ];
    }

    private function defineDisciplinaLivro()
    {
        return [
            'APO' => 1, /// todos
            'INI' => 1, /// todos
            'ALL' => 1, /// todos
            'todas' => 1, /// todos
            'todos' => 1, /// todos
            'ART' => 2, //arte
            'BIO'  => 3, // biologia
            'CIE'  => 4, // ciencias
            'EDF'  => 5, // ed fisica
            'ALL'  => 6,// ed infantil
            'EDI'  => 6,// ed infantil
            'FIL'  => 7, //filosofia
            'GEO'  => 8, // geografia
            'HIS'  => 9, // historia
            'ESP'  => 10, // L espanhola
            'ING'  => 11,// L inglesa
            'POR'  => 12, // l portuguesa
            'LIT'  => 13, // literatura
            'MAT'  => 14, // matematica
            'QUI'  => 15, // quimica
            'SOC'  => 16, // sociologia
            'FIS'  => 17, // fisica
            'EDT'  => 18, // fisica
            'EMP'  => 19, // fisica
            'EAM'  => 20, // fisica
            'EDM'  => 21, // fisica
            'TUR'  => 22, // fisica
            'TXT'  => 23,
        ];
    }

    private function moverArquivoLivro($id, $arquivo)
    {
        $zipperFile = \Zipper::make('../storage/app/arquivos_rocha/2021/ser&viver/'.$arquivo);
        $zipperFile->extractTo('../storage/app/public/livrodigital/'.$id.'/');
    }


    public function updateConteudo($nomeArquivo)
    {
        $etapaLivro = $this->defineEtapaLivro();
        $anoLivro = $this->defineAnoLivro();
        $disciplinaLivro = $this->defineDisciplinaLivro();

        $file = fopen('../storage/app/importa_livros/'.$nomeArquivo.'.csv','r');
        //$file = Storage::get('teste.csv');
        $i=0;
        $erro = '';
        while ( ($linha = fgetcsv($file, 200, ";")) !==FALSE) {

            if($i>0){
                $conteudo = Conteudo::where('titulo',$linha[6])->where('tipo',21)->first();
                if(isset($conteudo->id) && $conteudo->id>0){
                    $dados = [
                    'etapa_livro'       =>  $etapaLivro[$linha[2]], ///// ciclo
                    'ano_livro'         =>  $anoLivro[ $etapaLivro[$linha[2]] ][ $linha[3] ], /// ciclo_etapa
                    'componente_livro'  =>  $disciplinaLivro[$linha[4]], /// disciplina
                    ];

                    $conteudo->update($dados);
                }
                else
                {
                    $erro .= 'livro nao existe <b>'.$linha[6].'</b><br>';
                }
            }
            $i++;
        }
        return $erro;
    }

    public function adcionaLivroUsuario()
    {
        $escola = Escola::join('instituicao', 'instituicao.id','escolas.instituicao_id')
                ->where('escolas.id', 980)
               // ->where('instituicao.id', 254)
                ->selectRaw('escolas.id, escolas.instituicao_id')
                ->get();

        $livro = Conteudo::where('tipo',21)
                        ->whereNotNull('colecao_livro_id')
                        //->where('colecao_livro_id','14')
                        ->where(function($q){
                           // $q->orWhere('fonte','Editora Opet')
                             //   ->orWhere('fonte','Opet Solucões Educacionais');
                                $q->orWhere('colecao_livro_id','5')
                               ->orWhere('colecao_livro_id','20')
                               ->orWhere('colecao_livro_id','13');
                               //->orWhere('colecao_livro_id','13')
                              // ->orWhere('colecao_livro_id','20')
                               // ->orWhere('colecao_livro_id','16');
                                //->orWhere('colecao_livro_id','14');
                                //->orwhere('colecao_livro_id','20');
                        })
                        ->selectRaw('conteudos.id')
                        ->get();

        foreach($escola as $e)
        {
            foreach($livro as $l)
            {
                $dados = [
                    'conteudo_id'   =>  $l->id,
                    'escola_id'     =>  $e->id,
                    'instituicao_id' => $e->instituicao_id,
                ];
                ConteudoInstituicaoEscola::firstOrCreate($dados);
            }
        }
        return 1;
    }


    public function adcionaLivroUsuario2()
    {
        $escola = Conteudo::join('conteudo_instituicao_escola','conteudo_instituicao_escola.conteudo_id','conteudos.id' )
                ->where('tipo',21)
                ->where('colecao_livro_id',8)
                ->whereNotNull('conteudo_instituicao_escola.escola_id')
                ->groupBy('conteudo_instituicao_escola.escola_id')
                ->selectRaw('conteudo_instituicao_escola.*')
                ->get();
        $livro = Conteudo::where('tipo',21)
                        ->whereNotNull('colecao_livro_id')
                        //->where('colecao_livro_id','14')
                        ->where(function($q){
                           // $q->orWhere('fonte','Editora Opet')
                             //   ->orWhere('fonte','Opet Solucões Educacionais');
                                $q->orWhere('colecao_livro_id','8');
                                //->orWhere('colecao_livro_id','3')
                               //->orWhere('colecao_livro_id','14');
                                //->orwhere('colecao_livro_id','20');
                        })
                        ->selectRaw('conteudos.id')
                        ->get();

        foreach($escola as $e)
        {
            foreach($livro as $l)
            {
                $dados = [
                    'conteudo_id'   =>  $l->id,
                    'escola_id'     =>  $e->escola_id,
                    'instituicao_id' => $e->instituicao_id,
                ];

                ConteudoInstituicaoEscola::firstOrCreate($dados);
            }
        }
        return 1;
    }

    public function capaVideo()
    {
        $videos = Conteudo::where('tipo',3)->where('conteudo','like','%vimeo%')->get();

        foreach($videos as $v){
            $capa = $v->conteudo;
            $capa = explode('/',$capa);
            $dados = [
                'capa' => $capa[3].'.jpg'
            ];
            $v->update($dados);
        }
    }

    public function carregaConteudo()
    {
        $erro = '';
        $livros = Conteudo::where('tipo',21)->orderBy('id','desc')->limit(2)->get();

        foreach($livros as $l)
        {

            $caminho = trim($l->descricao);
            $caminho = str_replace('.pdf', '.zip', $caminho);
            if(file_exists('../storage/app/arquivos_rocha/livros/'.$caminho))
            {
                $destino = Storage::allFiles('/public/livrodigital/'.$l->id.'/');
                if(count($destino)==0)
                {
                    $this->moverArquivoLivro($l->id, $caminho);
                }
                else
                {
                    $erro .= 'livro já existe <b>'.$l->id.'</b><br>';
                }
            }
            else
            {
                $erro .= 'arquivo nao existe <b>'.$caminho.'</b><br>';
            }
        }

        return $erro;
    }

    public function conteudoPPT()
    {

        $etapaLivro = $this->defineEtapaLivro();
        $anoLivro = $this->defineAnoLivro();
        $disciplinaLivro = $this->defineDisciplinaLivro();

        $file = fopen('../storage/app/arquivos_rocha/lista_ppt4Bim.csv','r');
        $i=0;
        $erro = '';
        while ( ($linha = fgetcsv($file, 2000, ";")) !==FALSE) {
            if($i>0){

              //  if(file_exists('../storage/app/arquivos_rocha/ppt_novos/'.$linha[7]))
              //  {
                    $nomeCapa = str_replace('.pptx', '.png', $linha[7]);
                    $dados = [
                    'tipo'              =>  4,
                    'user_id'           =>  1,
                    'fonte'             =>  $linha[1],
                    'colecao_livro_id'  =>  $linha[0],

                    'ciclo_id'          =>  $etapaLivro[$linha[2]], ///// ciclo
                    'cicloetapa_id'     =>  $anoLivro[ $etapaLivro[$linha[2]] ][$linha[3]], /// ciclo_etapa

                    'disciplina_id'     =>  $disciplinaLivro[$linha[4]], /// disciplina
                    'tipo_livro'        =>  $linha[5], /// criar na tabela conteudo (char2)
                    'titulo'            =>  $linha[6],
                    'descricao'         =>  $linha[7],
                    'conteudo'          =>  $linha[7],
                    'status'            => 1,
                    'instituicao_id'    => 1,
                    'permissao_download'=> 1,
                    'capa'              => $nomeCapa,
                    ];

                    $conteudo = new Conteudo($dados);
                    $conteudo->save();
                        /*
                        if(!file_exists('../storage/app/public/'.$linha[7])){
                            Storage::copy('arquivos_rocha/ppt_novos/'.$linha[7], 'public/'.$linha[7]);
                        }
                        */


/*
                    if(file_exists('../storage/app/arquivos_rocha/ppt_capas/'.$nomeCapa)){
                        $capa = ['capa'=>$conteudo->id.'.jpg'];

                        if(!file_exists('../storage/app/public/capa_ppt/'.$conteudo->id.'.jpg')){
                            Storage::copy('arquivos_rocha/ppt_capas/'.$nomeCapa, 'public/capa_ppt/'.$conteudo->id.'.jpg');
                        }
                        $conteudo->update($capa);
                    }
                    else
                    {
                        $erro .= 'capa nao existe <b>'.$linha[7].'</b><br>';
                    }
                    */
             //   }
             //   else
             //   {
             //       $erro .= 'arquivo nao existe <b>'.$linha[7].'</b><br>';
             //   }
            }
            $i++;
        }
        return $erro;
    }


    public function conteudoGenerico($arq)
    {
        $TIPO = 102;

        $etapaLivro = $this->defineEtapaLivro();
        $anoLivro = $this->defineAnoLivro();
        $disciplinaLivro = $this->defineDisciplinaLivro();

        $file = fopen('../storage/app/arquivos_rocha/'.$arq.'.csv','r');
        $i=0;
        $erro = '';
        while ( ($linha = fgetcsv($file, 200, ";")) !==FALSE) {
            if($i>0){

                //$nomeCapa = str_replace('.docx', '.png', $linha[7]);

                $dados = [
                'tipo'              =>  $TIPO,
                'user_id'           =>  1,
                'fonte'             =>  $linha[1],
                'colecao_livro_id'  =>  $linha[0],

                'ciclo_id'          =>  $etapaLivro[$linha[2]], ///// ciclo
                'cicloetapa_id'     =>  $anoLivro[ $etapaLivro[$linha[2]] ][ $linha[3] ], /// ciclo_etapa

                'disciplina_id'     =>  $disciplinaLivro[$linha[4]], /// disciplina
                'tipo_livro'        =>  $linha[5], /// criar na tabela conteudo (char2)
                'titulo'            =>  $linha[6],
                'descricao'         =>  $linha[7],
                'conteudo'          =>  $linha[7],
                'periodo'           =>  $linha[8],
                'capa'              =>  $linha[9],
                'status'            => 1,
                'instituicao_id'    => 1,
                'permissao_download'=> 1,
                //'capa'              => $nomeCapa,
                ];

                $conteudo = new Conteudo($dados);
                $conteudo->save();
            }
            $i++;
        }
        return $erro;
    }


    public function conteudoSimulacoes($arq)
    {
        $TIPO = 101;

        $etapaLivro = $this->defineEtapaLivro();
        $anoLivro = $this->defineAnoLivro();
        $disciplinaLivro = $this->defineDisciplinaLivro();

        $file = fopen('../storage/app/arquivos_rocha/'.$arq.'.csv','r');
        $i=0;
        $erro = '';
        while ( ($linha = fgetcsv($file, 20000, ";")) !==FALSE) {
            if($i>0){

                $nomeCapa = $linha[11].'.jpg';

                $dados = [
                'tipo'              =>  $TIPO,
                'user_id'           =>  1,
                'fonte'             =>  $linha[10],
                //'colecao_livro_id'  =>  $linha[0],

                'ciclo_id'          =>  $etapaLivro[trim($linha[2])], ///// ciclo
                'cicloetapa_id'     =>  $anoLivro[ $etapaLivro[trim($linha[2])] ][ strtolower($linha[3]) ], /// ciclo_etapa

                'disciplina_id'     =>  $disciplinaLivro[trim($linha[4])], /// disciplina
                'tipo_livro'        =>  $linha[5], /// criar na tabela conteudo (char2)
                'titulo'            =>  $linha[6],
                'descricao'         =>  $linha[7],
                'conteudo'          =>  $linha[8],
                'status'            => 1,
                'instituicao_id'    => 1,
                'permissao_download'=> 0,
                'capa'              => $nomeCapa,
                'apoio'             => $linha[9],
                ];

                $conteudo = new Conteudo($dados);
                $conteudo->save();
            }
            $i++;
        }
        return $erro;
    }

    public function carregaAudio()
    {
        $etapaLivro = $this->defineEtapaLivro();
        $anoLivro = $this->defineAnoLivro();
        $disciplinaLivro = $this->defineDisciplinaLivro();

        $file = fopen('../storage/app/arquivos_rocha/audio.csv','r');
        $i=0;
        $erro = '';
        while ( ($linha = fgetcsv($file, 200, ";")) !==FALSE) {
            if($i>0){

                $conteudo = Conteudo::where('conteudo',$linha[7])->where('tipo',2)->where('user_id',1)->first();

                    $dados = [
                    'fonte'             =>  $linha[1],
                    'user_id'           =>  1,
                    'instituicao_id'           =>  1,
                    'tipo'              =>  2,

                    'ciclo_id'          =>  $etapaLivro[$linha[2]], ///// ciclo
                    'cicloetapa_id'     =>  $anoLivro[ $etapaLivro[$linha[2]] ][ strtolower($linha[3]) ], /// ciclo_etapa

                    'disciplina_id'     =>  $disciplinaLivro[$linha[4]], /// disciplina
                    'tipo_livro'        =>  $linha[5], /// criar na tabela conteudo (char2)
                    'titulo'            =>  $linha[6],
                    'descricao'         =>  $linha[6],
                    'status'            =>  1,
                    'colecao_livro_id'  =>  $linha[0],
                    'conteudo'          =>  $linha[7],
                    //'capa'              => $linha[9],
                    ];
                if(isset($conteudo->id) && $conteudo->id>0){

                    $conteudo->update($dados);
                }
                else
                {
                    $c = new Conteudo($dados);
                    $c->save();
                }

            }
            $i++;
        }
        return 1;
    }

    public function carregaImg()
    {
       // dd();
        $etapaLivro = $this->defineEtapaLivro();
        $anoLivro = $this->defineAnoLivro();
        $disciplinaLivro = $this->defineDisciplinaLivro();

        $file = fopen('../storage/app/arquivos_rocha/lista_teste_img.csv','r');
        $i=0;
        $erro = '';
        while ( ($linha = fgetcsv($file, 200, ";")) !==FALSE) {
            if($i>1){
                try
                {
                    $original = file_exists('../storage/app/arquivos_rocha/imagens/'.$linha[8].'.jpg');
                    $miniatura = file_exists('../storage/app/arquivos_rocha/thumb/'.$linha[8].'_thumb.jpg');
                    if($original && $miniatura)
                    {
                        $arquivo = Str::uuid()->toString();
                        Storage::copy('/arquivos_rocha/imagens/'.$linha[8].'.jpg', '/public/banco_imagens/'.$arquivo.'.jpg');
                        Storage::copy('/arquivos_rocha/thumb/'.$linha[8].'_thumb.jpg', '/public/banco_imagens/thumbs/'.$arquivo.'_thumb.jpg');

                        $dados = [
                        'fonte'             =>  $linha[7],
                        'user_id'           =>  1,
                        'instituicao_id'    =>  1,
                        'tipo'              =>  100,

                        'ciclo_id'          =>  $etapaLivro[$linha[2]], ///// ciclo
                        'cicloetapa_id'     =>  $anoLivro[ $etapaLivro[$linha[2]] ][ strtolower($linha[3]) ], /// ciclo_etapa

                        'disciplina_id'     =>  $disciplinaLivro[$linha[4]], /// disciplina
                        'tipo_livro'        =>  $linha[5], /// criar na tabela conteudo (char2)
                        'titulo'            =>  $linha[6],
                        'descricao'         =>  $linha[6],
                        'status'            =>  1,
                        'colecao_livro_id'  =>  $linha[0],
                        'conteudo'          =>  $arquivo.'.jpg',
                        'capa'              =>  $arquivo.'_thumb.jpg',
                        'permissao_download'=> 1,
                        ];

                        $c = new Conteudo($dados);
                        $c->save();
                        echo 'deu certo<br>';
                    }
                    else
                    {
                        echo 'não achou img<br>';
                    }
                }
                catch (\Exception $e)
                {
                     echo 'erro try <br>';
                }
            }
            $i++;
        }
        return 1;
    }

    public function dispararViaMailgun($v) {

        $MAILGUN_API_URL = 'https://api.mailgun.net/v3/opetinspira.com.br';
        $MAILGUN_API_KEY = 'de3f9a17aba0700471875fae2d854d96-46ac6b00-b49146b1';

        $lista = []; // Lista de professores

        $user = User::where('importado',$v)
                    ->where('permissao','P')
                    ->join('reset_token','reset_token.email','users.email')
                    ->selectRaw('reset_token.token, users.*')
                    ->get();

        foreach($user as $u){
            $lista[] = [
                'first_name' => ucfirst(strtolower($u->name)),
                'email'      => trim(strtolower($u->email)),
                'link'       => 'https://opetinspira.com.br/resetar-senha/'.$u->token
            ];
        }

        $dataDisparo = date("Y-m-d H:i",strtotime(date('Y-m-d H:i')." + 15 minutes"));


        $minutes_to_add = 1; // Informar o delay em minutos para cada disparo
        $time = new DateTime($dataDisparo, new DateTimeZone('America/Sao_Paulo')); // Horario que o mailgun vai começar a disparar

        foreach ($lista as $r) {
            $time->add(new DateInterval('PT' . $minutes_to_add . 'M'));
            $disparar_em = $time->format('D, d M Y H:i:s O'); // Wed, 19 Feb 2020 08:00:00 -0300 (exemplo do formato exigido configurado)
            //die($disparar_em);

            $toname = $r['first_name'];
            $to     = $r['email'];
            $link   = $r['link'];

            $array_data = array(
                'from'=> 'Opet INspira <naoresponda@opetinspira.com.br>',
                'to'=>$toname.' <'.$to.'>',
                'subject'=> 'Acesso à plataforma INspira: ' . $toname,
                'template'=>'acesso-inspira',
                't:text'=> 'yes',
                'h:X-Mailgun-Variables' => '{"first_name": "'.$toname.'", "email": "'.$to.'", "link": "'.$link.'"}',
                'o:deliverytime' => $disparar_em
            );

            $session = curl_init($MAILGUN_API_URL.'/messages');
            curl_setopt($session, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($session, CURLOPT_USERPWD, 'api:'.$MAILGUN_API_KEY);
            curl_setopt($session, CURLOPT_POST, true);
            curl_setopt($session, CURLOPT_POSTFIELDS, $array_data);
            curl_setopt($session, CURLOPT_HEADER, false);
            curl_setopt($session, CURLOPT_ENCODING, 'UTF-8');
            curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);

            $response = curl_exec($session);
            curl_close($session);
            $results = json_decode($response, true);
        }
    }

    public function migraPermissaoLivro()
    {
        $elementos = ConteudoInstituicaoEscola::join('conteudos','conteudos.id','conteudo_instituicao_escola.conteudo_id')
                                                ->groupBy('conteudo_instituicao_escola.escola_id')
                                                ->groupBy('conteudos.colecao_livro_id')
                                                ->selectRaw('conteudo_instituicao_escola.escola_id, conteudos.colecao_livro_id')
                                                ->whereNotNull('conteudo_instituicao_escola.escola_id')
                                                ->whereNotNull('conteudos.colecao_livro_id')
                                                ->where('conteudos.colecao_livro_id','>',0)
                                                ->get();
        foreach($elementos as $e)
        {
            ColecaoLivroEscola::firstOrCreate([
                'colecao_id'    => $e->colecao_livro_id,
                'escola_id'     => $e->escola_id,
                'todos'         => '1',
            ]);
        }
    }


    public function migraPermissaoAudio()
    {
        $elementos = [36];

        $escolas = Escola::selectRaw('id')->get();

        foreach($elementos as $e)
        {
            foreach ($escolas as $esc) {
                ColecaoAudioEscola::firstOrCreate([
                    'colecao_id'    => $e,
                    'escola_id'     => $esc->id,
                    'todos'         => '1',
                ]);
            }

        }
    }

/// SELECT * FROM escolas inner join instituicao on escolas.instituicao_id = instituicao.id where instituicao.instituicao_tipo_id =1;

    public function migraPermissaoProvaPublica()
    {
        $elementos = [96,97];

        $escolas = Escola::join('instituicao','escolas.instituicao_id','instituicao.id')
                    ->where('instituicao.instituicao_tipo_id',2)
                    ->selectRaw('escolas.id')->get();

        foreach($elementos as $e)
        {
            foreach ($escolas as $esc) {
                ColecaoProvaEscola::firstOrCreate([
                    'colecao_id'    => $e,
                    'escola_id'     => $esc->id,
                    'todos'         => '1',
                ]);
            }

        }
    }

    public function migraPermissaoProvaParticular()
    {
        $elementos = [94,95];

        $escolas = Escola::join('instituicao','escolas.instituicao_id','instituicao.id')
                    ->where('instituicao.instituicao_tipo_id',1)
                    ->selectRaw('escolas.id')->get();

        foreach($elementos as $e)
        {
            foreach ($escolas as $esc) {
                ColecaoProvaEscola::firstOrCreate([
                    'colecao_id'    => $e,
                    'escola_id'     => $esc->id,
                    'todos'         => '1',
                ]);
            }
        }
    }

    public function EscolaPermissaoLivro()
    {
        $elementos = Escola::get();
        foreach($elementos as $e)
        {
            ColecaoLivroEscola::firstOrCreate([
                'colecao_id'    => 146,
                'escola_id'     => $e->id,
                'todos'         => '1',
            ]);
        }
    }

    public function ProfessorComoAluno()
    {
        $user = User::with('professor_escola')->where('permissao','P')->get();
        foreach($user as $u)
        {
            foreach ($u->professor_escola as $e)
            {
                $dados = [
                    'user_id'       => $u->id,
                    'permissao'     => 'A',
                    'escola_id'     => $e->id,
                    'instituicao_id'=> $e->instituicao_id,
                ];

                UserPermissao::withoutGlobalScopes()->firstOrCreate($dados);
            }
        }
    }

    public function importarBncc()
    {
        $files = Storage::allFiles('/arquivos_rocha/bncc');
        foreach ($files as $f) {
            $file = fopen('../storage/app/'.$f,'r');
            $i=0;
            while ( ($linha = fgetcsv($file, 2000, ";")) !==FALSE) {
                if($i>0)
                {
                   $dados=[
                        'disciplina_id'     => $linha[0],
                        'cicloetapa_id'     => $linha[1],
                        'unidade_tematica'  => $linha[2],
                        'objeto_conhecimento' => $linha[3],
                        'habilidade'        => $linha[4],

                   ];
                   if(isset($linha[5])){
                        $dados['codigo_habilidade'] = $linha[5];
                   }
                   $bncc = new FrBncc($dados);
                   $bncc->save();
                }


                $i++;
            }
        }
    }

    public function importarBncc2()
    {
        $files = Storage::allFiles('/arquivos_rocha/bncc2');
        foreach ($files as $f) {
            $file = fopen('../storage/app/'.$f,'r');
            $i=0;
            while ( ($linha = fgetcsv($file, 20000, ";")) !==FALSE) {
                if($i>0)
                {
                    $pos = strpos($linha[4], ')');
                    $codigo = substr($linha[4], 1, $pos-1);
                    $habilidade = substr($linha[4], $pos+2);
                   $dados=[
                        'disciplina_id'     => $linha[0],
                        'cicloetapa_id'     => $linha[1],
                        'unidade_tematica'  => $linha[2],
                        'objeto_conhecimento' => $linha[3],
                        'habilidade'        => $habilidade,
                        'codigo_habilidade' => $codigo,
                        'comentario' => $linha[5],


                   ];
                   $bncc = new FrBncc($dados);
                   $bncc->save();
                }


                $i++;
            }
        }
    }

    public function hashConteudoGoogle()
    {
        $cont = Conteudo::where(function($q){
                    $q->orWhere('tipo', 102)
                        ->orWhere('tipo', 103)
                        ->orWhere('tipo', 4)
                        ->orWhere('tipo', 3)
                        ->orWhere('tipo', 2)
                        ->orWhere('tipo', 22)
                        ->orWhere('tipo', 101)
                        ->orWhere('tipo', 104);
                })
                ->whereNull('id_google')
                ->where('instituicao_id',1)
                ->get();

        foreach ($cont as $c) {
            $dados = [];
            $dados['id_google'] = base64_encode(Hash::make($c->created_at.$c->id));
            $dados['compartilhado_google'] = 1;
            $c->update($dados);
        }
    }



    public function geraSenha($senha)
    {
        return Hash::make($senha);
    }


    public function verificaLivroPDF()
    {
        $vet = glob('../storage/app/public/livrodigital/*', GLOB_ONLYDIR);
        foreach ($vet as $v) {
            if(!file_exists($v.'/livro.pdf'))
            {
                echo $v."<br>";
            }

        }
    }

    public function resetaPermissaoPeriodoLivro()
    {
        $dados = ColecaoLivroEscola::join('conteudos','conteudos.colecao_livro_id','colecao_livro_escola.colecao_id')
            ->join('escolas','colecao_livro_escola.escola_id','escolas.id')
            ->join('instituicao','escolas.instituicao_id','instituicao.id')
            ->whereNotNull('conteudos.periodo')
            ->where('conteudos.periodo','<>','')
            ->where('instituicao.instituicao_tipo_id',1)
            ->groupBy('colecao_livro_escola.colecao_id')
            ->groupBy('colecao_livro_escola.escola_id')
            ->selectRaw('colecao_livro_escola.*')
            ->get();

        foreach($dados as $d){
                ColecaoLivroEscolaPermissaoPeriodo::where('colecao_id', $d->colecao_id)->where('escola_id', $d->escola_id)->delete();
                $p = [
                    'colecao_id' => $d->colecao_id,
                    'escola_id' => $d->escola_id,
                    'periodo' => 1,
                ];
                $periodo = new ColecaoLivroEscolaPermissaoPeriodo($p);
                $periodo->save();
                $t = ['todos_periodos' => 0];
                ColecaoLivroEscola::where('colecao_id', $d->colecao_id)->where('escola_id', $d->escola_id)->update($t);


        }
        return 'fim';
    }

    public function clonaPermissaoEscolaParaInstituicao(){
        $inst = Instituicao::selectRaw('id')->get();

        foreach($inst as $d){
            $escola = Escola::selectRaw('id')->where('instituicao_id',$d->id)->orderBy('id')->first();
            if($escola) {
                $escolaLivro = ColecaoLivroEscola::where('escola_id', $escola->id)->get()->toArray();
                $escolaLivroPermissao = ColecaoLivroEscolaPermissao::where('escola_id', $escola->id)->get()->toArray();
                $escolaLivroPermissaoPeriodo = ColecaoLivroEscolaPermissaoPeriodo::where('escola_id', $escola->id)->get()->toArray();
                foreach ($escolaLivro as $il) {
                    $il['instituicao_id'] = $d->id;
                    $cl = new ColecaoLivroInstituicao($il);
                    $cl->save();
                }
                foreach ($escolaLivroPermissao as $il) {
                    $il['instituicao_id'] = $d->id;
                    $cl = new ColecaoLivroInstituicaoPermissao($il);
                    $cl->save();
                }
                foreach ($escolaLivroPermissaoPeriodo as $il) {
                    $il['instituicao_id'] = $d->id;
                    $cl = new ColecaoLivroInstituicaoPermissaoPeriodo($il);
                    $cl->save();
                }

                $escolaAudio = ColecaoAudioEscola::where('escola_id', $escola->id)->get()->toArray();
                $escolaAudioPermissao = ColecaoAudioEscolaPermissao::where('escola_id', $escola->id)->get()->toArray();
                foreach ($escolaAudio as $il) {
                    $il['instituicao_id'] = $d->id;
                    $cl = new ColecaoAudioInstituicao($il);
                    $cl->save();
                }
                foreach ($escolaAudioPermissao as $il) {
                    $il['instituicao_id'] = $d->id;
                    $cl = new ColecaoAudioInstituicaoPermissao($il);
                    $cl->save();
                }

                $escolaProva = ColecaoProvaEscola::where('escola_id', $escola->id)->get()->toArray();
                $escolaProvaPermissao = ColecaoProvaEscolaPermissao::where('escola_id', $escola->id)->get()->toArray();
                foreach ($escolaProva as $il) {
                    $il['instituicao_id'] = $d->id;
                    $cl = new ColecaoProvaInstituicao($il);
                    $cl->save();
                }
                foreach ($escolaProvaPermissao as $il) {
                    $il['instituicao_id'] = $d->id;
                    $cl = new ColecaoProvaInstituicaoPermissao($il);
                    $cl->save();
                }
            }
        }
    }

    public function permissaoColecaoLivroInstituicao(){
        $instituicao= Instituicao::get();
        foreach($instituicao as $d){
            $dados['colecao_id'] = 188;
            $dados['instituicao_id'] = $d->id;
            $dados['todos'] = 1;
            $dados['todos_periodos'] = 1;
            ColecaoLivroInstituicao::firstOrCreate($dados);
        }
    }

    public function permissaoColecaoLivroEscola(){
        $escola = Escola::get();
        foreach($escola as $d){
            $dados['colecao_id'] = 188;
            $dados['escola_id'] = $d->id;
            $dados['todos'] = 1;
            $dados['todos_periodos'] = 1;
            ColecaoLivroEscola::firstOrCreate($dados);

        }
    }

    public function normalizaUsuario(){
        $instituicao = [];
        $user = User::whereNull('instituicao_id')->whereNotNull('escola_id')->get();
        foreach($user as $u){
            if(!isset($instituicao[$u->escola_id])){
                $inst = Escola::find($u->escola_id);
                $instituicao[$u->escola_id] = $inst->instituicao_id;
            }
            $dados = ['instituicao_id'=>$instituicao[$u->escola_id]];
            $u->update($dados);
        }
    }

    public function fulltextQuiz(){
        $quiz = FrQuiz::join('disciplinas','disciplinas.id','fr_quiz.disciplina_id')
                ->join('ciclo_etapas','ciclo_etapas.id','fr_quiz.ciclo_etapa_id')
                ->join('ciclos','ciclos.id','ciclo_etapas.ciclo_id')
                ->selectRaw('fr_quiz.id, disciplinas.titulo as disciplina, ciclo_etapas.titulo as etapa, ciclos.titulo as ciclo')
                //->whereNull('full_text')
                ->get();
        foreach ($quiz as $q){
            $dados['full_text'] = $q->disciplina.' '.$q->ciclo.' '.$q->etapa;
            $editar = FrQuiz::find($q->id);
            $editar->update($dados);
        }
        return '1';
    }

    public function fulltextConteudo(){
        $quiz = Conteudo::join('disciplinas','disciplinas.id','conteudos.disciplina_id')
            ->join('ciclo_etapas','ciclo_etapas.id','conteudos.cicloetapa_id')
            ->join('ciclos','ciclos.id','ciclo_etapas.ciclo_id')
            ->selectRaw('conteudos.id, disciplinas.titulo as disciplina, ciclo_etapas.titulo as etapa, ciclos.titulo as ciclo')
            ->where(function($q){
                $q->orWhere('tipo', 102)
                    ->orWhere('tipo', 103)
                    ->orWhere('tipo', 21)
                    ->orWhere('tipo', 100)
                    ->orWhere('tipo', 4)
                    ->orWhere('tipo', 3)
                    ->orWhere('tipo', 2)
                    ->orWhere('tipo', 101);
            })
            ->whereNull('full_text')
            ->get();
        foreach ($quiz as $q){

            $dados['full_text'] = $q->disciplina.' '.$q->ciclo.' '.$q->etapa;
            $editar = Conteudo::find($q->id);
            $editar->update($dados);
        }
        return '1';
    }


    public function livroPorLinha()
    {
        $retorno = [];
        $linhas = [];
        $etapaLivro = $this->defineEtapaLivro();
        $anoLivro = $this->defineAnoLivro();
        $disciplinaLivro = $this->defineDisciplinaLivro();

        $linhas[]= explode(';', '8;Opet;EM;1;POR;A;Ensino Médio - 1º ano - 1º Semestre - L. Portuguesa e Literatura - Aluno - Parte 1;por_e_lit_1m1s_aluno_2_anos_e_meio_parte01;;1');
        $linhas[]= explode(';', '8;Opet;EM;1;POR;A;Ensino Médio - 1º ano - 1º Semestre - L. Portuguesa e Literatura - Aluno - Parte 2;por_e_lit_1m1s_aluno_2_anos_e_meio_parte02;;1');
        $linhas[]= explode(';', '8;Opet;EM;1;POR;A;Ensino Médio - 1º ano - 2º Semestre - L. Portuguesa e Literatura - Aluno - Parte 1;por_e_lit_1m2s_aluno_2_anos_e_meio_parte01;;2');
        $linhas[]= explode(';', '8;Opet;EM;1;POR;A;Ensino Médio - 1º ano - 2º Semestre - L. Portuguesa e Literatura - Aluno - Parte 2;por_e_lit_1m2s_aluno_2_anos_e_meio_parte02;;2');
        $linhas[]= explode(';', '8;Opet;EM;2;POR;A;Ensino Médio - 2º ano - 1º Semestre - L. Portuguesa e Literatura - Aluno - Parte 1;por_e_lit_2m1s_aluno_2_anos_e_meio_parte01;;1');
        $linhas[]= explode(';', '8;Opet;EM;2;POR;A;Ensino Médio - 2º ano - 1º Semestre - L. Portuguesa e Literatura - Aluno - Parte 2;por_e_lit_2m1s_aluno_2_anos_e_meio_parte02;;1');
        $linhas[]= explode(';', '8;Opet;EM;2;POR;A;Ensino Médio - 2º ano - 2º Semestre - L. Portuguesa e Literatura - Aluno - Parte 1;por_e_lit_2m2s_aluno_2_anos_e_meio_parte01;;2');
        $linhas[]= explode(';', '8;Opet;EM;2;POR;A;Ensino Médio - 2º ano - 2º Semestre - L. Portuguesa e Literatura - Aluno - Parte 2;por_e_lit_2m2s_aluno_2_anos_e_meio_parte02;;2');
        $linhas[]= explode(';', '8;Opet;EM;3;POR;A;Ensino Médio - 3º ano - 1º Semestre - L. Portuguesa e Literatura - Aluno - Parte 1;por_e_lit_3m1s_aluno_2_anos_e_meio_parte01;;1');
        $linhas[]= explode(';', '8;Opet;EM;3;POR;A;Ensino Médio - 3º ano - 1º Semestre - L. Portuguesa e Literatura - Aluno - Parte 2;por_e_lit_3m1s_aluno_2_anos_e_meio_parte02;;1');
        foreach($linhas as $linha) {
            $dados = [
                'tipo' => 21,
                'user_id' => 1,
                'status' => 1,
                'colecao_livro_id' => $linha[0],
                'fonte' => $linha[1],

                'etapa_livro' => $etapaLivro[$linha[2]], ///// ciclo
                'ano_livro' => $anoLivro[$etapaLivro[$linha[2]]][$linha[3]], /// ciclo_etapa

                'componente_livro' => $disciplinaLivro[$linha[4]], /// disciplina
                'tipo_livro' => $linha[5], /// criar na tabela conteudo (char2)
                'titulo' => $linha[6],
                'descricao' => $linha[7],

                'ciclo_id' => $etapaLivro[$linha[2]], ///// ciclo
                'cicloetapa_id' => $anoLivro[$etapaLivro[$linha[2]]][$linha[3]], /// ciclo_etapa
                'disciplina_id' => $disciplinaLivro[$linha[4]], /// disciplina
                'versao' => $linha[8],
                'periodo' => $linha[9],

            ];

            $conteudo = new Conteudo($dados);
            $conteudo->save();
            $retorno[$conteudo->id] = $conteudo->descricao;
        }
                    dd($retorno);
    }

    public function qtdPaginas(){
        $l1 = Conteudo::find(19706);
        $l1->update(['qtd_paginas_livro'=>119]);

        $l1 = Conteudo::find(19707);
        $l1->update(['qtd_paginas_livro'=>130]);

        $l1 = Conteudo::find(19708);
        $l1->update(['qtd_paginaslivro'=>83]);

        $l1 = Conteudo::find(19709);
        $l1->update(['qtd_paginas_livro'=>130]);

        $l1 = Conteudo::find(19710);
        $l1->update(['qtd_paginas_livro'=>51]);

        $l1 = Conteudo::find(19711);
        $l1->update(['qtd_paginas_livro'=>114]);

        $l1 = Conteudo::find(19712);
        $l1->update(['qtd_paginas_livro'=>47]);

        $l1 = Conteudo::find(19713);
        $l1->update(['qtd_paginas_livro'=>94]);

        $l1 = Conteudo::find(19714);
        $l1->update(['qtd_paginas_livro'=>47]);

        $l1 = Conteudo::find(19715);
        $l1->update(['qtd_paginas_livro'=>82]);
    }

    public function xlsQuiz(){
        $ret = FrQuiz::where('fr_quiz.instituicao_id',1)
            ->join('users','fr_quiz.user_id','users.id')
            ->join('ciclo_etapas','ciclo_etapas.id','fr_quiz.ciclo_etapa_id')
            ->join('ciclos','ciclos.id','ciclo_etapas.ciclo_id')
            ->with(['disciplina','qtdPerguntas'])
            ->selectRaw('fr_quiz.*, users.nome_completo as usuario, ciclos.titulo as ciclo,ciclo_etapas.titulo as etapa ')
            ->get();
        $csv = [];
        $conteudo = [];
        $conteudo[] = iconv('UTF-8', 'Windows-1252','Código');
        $conteudo[] = iconv('UTF-8', 'Windows-1252','Título');
        $conteudo[] = iconv('UTF-8', 'Windows-1252','Disciplina');
        $conteudo[] = iconv('UTF-8', 'Windows-1252','Etapa');
        $conteudo[] = iconv('UTF-8', 'Windows-1252','Ano');
        $conteudo[] = iconv('UTF-8', 'Windows-1252','Usuário');
        $conteudo[] = iconv('UTF-8', 'Windows-1252','Unidade Temática');
        $conteudo[] = iconv('UTF-8', 'Windows-1252','Habilidades');
        $conteudo[] = iconv('UTF-8', 'Windows-1252','Palavras Chave');
        $conteudo[] = iconv('UTF-8', 'Windows-1252','Pontuação');
        $conteudo[] = iconv('UTF-8', 'Windows-1252','Publicado');
        $conteudo[] = iconv('UTF-8', 'Windows-1252','Quantidade de perguntas');
        $conteudo[] = iconv('UTF-8', 'Windows-1252','Criado');
        $conteudo[] = iconv('UTF-8', 'Windows-1252','Editado');
        $csv[] = $conteudo;
        foreach($ret as $r){
            $qtdPerguntas = 0;
            if(count($r->qtdPerguntas)>0)
            {
                $qtdPerguntas = $r->qtdPerguntas[0]->qtd;
            }
            $conteudo = [];
            try{
            $conteudo[] = iconv('UTF-8', 'Windows-1252',$r->id);
            $conteudo[] = iconv('UTF-8', 'Windows-1252',$r->titulo);
            $conteudo[] = iconv('UTF-8', 'Windows-1252',$r->disciplina->titulo);
            $conteudo[] = iconv('UTF-8', 'Windows-1252',$r->ciclo);
            $conteudo[] = iconv('UTF-8', 'Windows-1252',$r->etapa);
            $conteudo[] = iconv('UTF-8', 'Windows-1252',$r->usuario);
            $conteudo[] = iconv('UTF-8', 'Windows-1252',$r->unidade_tematica);
            $conteudo[] = iconv('UTF-8', 'Windows-1252',$r->habilidades);
            $conteudo[] = iconv('UTF-8', 'Windows-1252',$r->palavras_chave);
            $conteudo[] = iconv('UTF-8', 'Windows-1252',$r->pontuacao);
            $conteudo[] = iconv('UTF-8', 'Windows-1252',$r->publicado);
            $conteudo[] = iconv('UTF-8', 'Windows-1252',$qtdPerguntas);
            $conteudo[] = iconv('UTF-8', 'Windows-1252',$r->created_at->format('d/m/Y H:i:s'));
            $conteudo[] = iconv('UTF-8', 'Windows-1252',$r->updated_at->format('d/m/Y H:i:s'));
            }catch (\Exception $e)
            {
                $conteudo[] = $r->id;
                $conteudo[] = $r->titulo;
                $conteudo[] =$r->disciplina->titulo;
                $conteudo[] = $r->ciclo;
                $conteudo[] = $r->etapa;
                $conteudo[] = $r->usuario;
                $conteudo[] = $r->unidade_tematica;
                $conteudo[] = $r->habilidades;
                $conteudo[] = $r->habilidades;
                $conteudo[] = $r->palavras_chave;
                $conteudo[] = $r->pontuacao;
                $conteudo[] = $r->publicado;
                $conteudo[] = $r->created_at->format('d/m/Y H:i:s');
                $conteudo[] = $r->updated_at->format('d/m/Y H:i:s');
            }
            $csv[]=$conteudo;
        }

        $nomeArquivo = md5(date('H:i:s')).'.csv';
        $caminho = config('app.frTmp').$nomeArquivo;
        $fp = fopen($caminho, 'x+');

        foreach ($csv as $fields) {
            fputcsv($fp, $fields, ';');
        }

        fclose($fp);
        $putEm = 'upload_usuario/relatorio_quiz/'.$nomeArquivo;
        Storage::disk()->put($putEm, file_get_contents($caminho));
        return Storage::download($putEm, 'relatorio_quiz_'.date('d-m-Y H:i:s').'.csv');
    }

    public function copiaQuestaoQuiz($lista){
        //$path = (Storage::path('private/copiar_questao_quiz/'.$lista.'.csv');
        $path = Storage::disk()->url('https://cf.opetinspira.com.br/private/copiar_questao_quiz/'.$lista.'.csv');

        $arquivo = fopen('https://cf.opetinspira.com.br/'.$lista.'.csv','r');
        $j=0;
        while (!feof($arquivo)) {
            $j++;
            $linha = fgetcsv($arquivo, 0, ',');
            if($j == 1 || $linha[0] == '' || $linha[1] ==''){
                continue;
            }
            $quizId = $linha[0];
            $questaoId = $linha[1];
            $titulo = $linha[2];
            DB::beginTransaction();
            try {
                $questao = FrQuestao::find($questaoId)->toArray();
                $dados = [
                    'tipo' => 4,
                    'quiz_id' => $quizId,
                    'titulo' => $titulo,
                    'sub_titulo' => $questao['pergunta'],
                    'importado_questao' => $questao['id'],
                    'ordem' => $j,
                ];
                $tem = FrQuizPerguntas::where('quiz_id',$quizId)->where('importado_questao',$questao['id'])->first();
                if(!$tem) {
                    $pergunta = new FrQuizPerguntas($dados);
                    $pergunta->save();
                    for ($i = 1; $i < 8; $i++) {
                        if ($questao['alternativa_' . $i] != '') {
                            $ehCorreta = 0;
                            if ($i == $questao['correta']) {
                                $ehCorreta = 1;
                            }
                            $r = [
                                'pergunta_id' => $pergunta->id,
                                'titulo' => $questao['alternativa_' . $i],
                                'correta' => $ehCorreta,
                            ];
                            $resposta = new FrQuizResposta($r);
                            $resposta->save();
                        }
                    }
                }
                DB::commit();

            } catch (\Exception $e) {
                DB::rollback();
                echo 'Erro quizID= '.$quizId.'; questaoID= '.$questaoId.';<br>'.$e->getMessage().'<br><br><br><br>';
            }

        }
        return 'certinho.';
    }

    public function idPublicoQuiz(){
        $retorno = FrQuiz::where('instituicao_id',1)->whereNull('public_id')->get();
        foreach ($retorno as $r){
            $str = Str::random(9);
            $str = $str.$r->id;

            $r->update(['public_id'=>$str]);
        }
        return 'fim';
    }
}
