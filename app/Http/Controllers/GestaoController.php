<?php

namespace App\Http\Controllers;

use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

use Auth;
use Intervention\Image\Facades\Image;
use Redirect;
use App\Models\Escola;
use App\Models\Conteudo;
//use App\Models\Aplicacao;
use App\Models\Categoria;
use App\Models\Curso;
use App\Models\Aula;
use App\Models\ConteudoAula;
use App\Models\Matricula;
use App\Models\Ciclo;
use App\Models\CicloEtapa;
use App\Models\Disciplina;
use App\Models\Instituicao;
//use App\Models\Visibilidade;
use App\Models\TrilhasCurso;
use App\Models\InstituicaoUser;
use App\Models\ProfessorCurso;
use App\Models\ResponsavelEscola;
use App\Services\ConteudoService;
use App\Models\User;
use App\Models\Professor;
use App\Models\ProfessorEscola;
use App\Models\ColecaoLivros;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class GestaoController extends Controller
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
    public function index()
    {
        return redirect()->route('gestao.cursos');
    }


    public function biblioteca()
    {
        $conteudos = [];
        $conteudo_tipo = [];
        $aplicacoes = [];
        $Privilegios = [];
        $user_id = Auth::user()->id;
        $etapas = Ciclo::all();

        $cicloEtapas = CicloEtapa::all();

        $disciplinas = Disciplina::all();
        $colecoesAcaoDestaque = ColecaoLivros::where('tipo',106)->orderBy('ordem')->orderBy('nome')->get();
        $hasFilters = false;
        $colecaoLivro = ColecaoLivros::where('tipo',21)->orderBy('ordem')->get();
        return view('gestao.biblioteca')->with(compact('colecaoLivro','conteudos', 'conteudo_tipo', 'aplicacoes', 'Privilegios',
                                                        'user_id', 'etapas', 'cicloEtapas', 'disciplinas', 'hasFilters', 'colecoesAcaoDestaque'));
    }

    public function biblioteca2()
    {
        $conteudos = Conteudo::query();

        if(!auth()->user()->isAdmin) {
            $conteudos = Conteudo::whereHas('instituicao', function($query) {
                $query->where('escola_id', auth()->user()->escola_id);
            });
        }

        $tem_pesquisa = Input::has('pesquisa') ? (Input::get('pesquisa') != null && Input::get('pesquisa') != "") : false;

        $tipocatalogo = Input::has('catalogo') ? (Input::get('catalogo') != null && Input::get('catalogo') != "") : false;

        $disciplina = Input::has('disciplina') ? (Input::get('disciplina') != null && Input::get('disciplina') != "") : false;

        $ciclo = Input::has('ciclo') ? (Input::get('ciclo') != null && Input::get('ciclo') != "") : false;

        $ciclo_etapa = Input::has('ciclo_etapa') ? (Input::get('ciclo_etapa') != null && Input::get('ciclo_etapa') != "") : false;

        if($disciplina && Input::get('disciplina') > 1) {
            $conteudos = $conteudos->where('cursos.disciplina_id', Input::get('disciplina'));
        }

        if($ciclo) {
            $conteudos = $conteudos->where('cursos.ciclo_id', Input::get('ciclo'));
        }

        if($ciclo_etapa) {
            $conteudos = $conteudos->where('cursos.cicloetapa_id', Input::get('ciclo_etapa'));
        }

        if($tem_pesquisa) {
            $pesquisa = Input::get('pesquisa');
            $conteudos = $conteudos->where(function($q) use ($pesquisa) {
                $q->where('conteudos.titulo', 'like', '%' . $pesquisa . '%')
                  ->orWhere('conteudos.descricao', 'like', '%' . $pesquisa . '%')
                  ->orWhere('cursos.tag', 'like', '%' . $pesquisa . '%')
                  ->orWhere('cursos.titulo', 'like', '%' . $pesquisa . '%')
                  ->orWhere('cursos.descricao', 'like', '%' . $pesquisa . '%');
            });
        }

        if($tipocatalogo) {
            $conteudos = $conteudos->where('conteudos.tipo', '=', Input::get('catalogo'));
        }

        $conteudos = $conteudos
                        ->select([
                            //Por enquanto não vai ter relacionamento com aula ou curso
                            // 'conteudo_aula.*',
                            // 'cursos.tipo as curso_tipo',
                            'conteudo_instituicao_escola.instituicao_id',
                            'ciclos.titulo as ciclo_nome',
                            'ciclo_etapas.titulo as cicloetapa_nome',
                            'disciplinas.titulo as disciplina_nome',
                            'responsavel_escolas.user_id as user_responsavel',
                            'professor_escolas.user_id as user_professor',
                            (new Conteudo)->getTable().'.*'
                        ])
                        //Por enquanto não vai ter relacionamento com aula ou curso
                        // ->join('conteudo_aula', 'conteudos.id', 'conteudo_aula.conteudo_id')
                        // ->join('aulas', 'conteudo_aula.aula_id', 'aulas.id')
                        ->leftjoin('conteudo_aula', 'conteudo_aula.conteudo_id', 'conteudos.id')
                        ->leftjoin('cursos', 'conteudo_aula.curso_id', 'cursos.id')
                        ->leftjoin('ciclos', 'ciclos.id', 'conteudos.ciclo_id')
                        ->leftjoin('ciclo_etapas', 'ciclo_etapas.id', 'conteudos.cicloetapa_id')
                        ->leftjoin('disciplinas', 'disciplinas.id', 'conteudos.disciplina_id')
                        ->leftjoin('conteudo_instituicao_escola', 'conteudos.id', 'conteudo_instituicao_escola.conteudo_id')
                        ->leftjoin('responsavel_escolas', 'conteudo_instituicao_escola.escola_id', 'responsavel_escolas.escola_id')
                        ->leftjoin('professor_escolas', 'conteudo_instituicao_escola.escola_id', 'professor_escolas.escola_id')
                       /* Alterardo por F&R
                             para resolver o problema de desempenho do sistema
                       */

                        ->paginate(1);
                        ///->get();

                       /* Fim da Alteração */

        $aplicacoes = Aplicacao::query();

        $tem_pesquisa = Input::has('pesquisa') ? (Input::get('pesquisa') != null && Input::get('pesquisa') != "") : false;

        $aplicacoes = Aplicacao::select([
            'aplicacoes.*',
            'ciclos.titulo as titulo_ciclo',
            'ciclo_etapas.titulo as titulo_ciclo_etapas',
            'disciplinas.titulo as titulo_disciplinas',
        ])->when($tem_pesquisa, function ($query) {
            return $query
                ->where('aplicacoes.titulo', 'like', '%' . Input::get('pesquisa') . '%')
                ->orWhere('aplicacoes.descricao', 'like', '%' . Input::get('pesquisa') . '%');
        })->leftjoin('ciclos', 'ciclos.id', 'aplicacoes.ciclo_id')
          ->leftjoin('ciclo_etapas', 'ciclo_etapas.id', 'aplicacoes.cicloetapa_id')
          ->leftjoin('disciplinas', 'disciplinas.id', 'aplicacoes.disciplina_id')
          ->get();

        $conteudos = Conteudo::detalhado($conteudos);

        #filtra os conteudos para apenas livro digital e retorna os livros conforme o perfil do usuário
        $conteudo_livro_gigital = Conteudo::getLivroDigitalBiblioteca($conteudos);

        $conteudo_livro_gigital = Conteudo::detalhado($conteudo_livro_gigital);

        $audios = $conteudos->filter(function ($c) {
            return $c->tipo == 2;
        });

        $videos = $conteudos->filter(function ($c) {
            return $c->tipo == 3;
        });

        $slides = $conteudos->filter(function ($c) {
            return ($c->tipo == 4 && (strpos($c->conteudo, ".ppt") !== false || strpos($c->conteudo, ".pptx") !== false));
        });

        $documentos = $conteudos->filter(function ($c) {
            return $c->tipo == 1 || ($c->tipo == 4 && (strpos($c->conteudo, ".ppt") == false && strpos($c->conteudo, ".pptx") == false));
        });

        $transmissoes = $conteudos->filter(function ($c) {
            return $c->tipo == 5;
        });

        $uploads = $conteudos->filter(function ($c) {
            return $c->tipo == 6;
        });

        $dissertativas = $conteudos->filter(function ($c) {
            return $c->tipo == 7;
        });

        $quizzes = $conteudos->filter(function ($c) {
            return $c->tipo == 8;
        });

        $provas = $conteudos->filter(function ($c) {
            return $c->tipo == 9;
        });

        $entregaveis = $conteudos->filter(function ($c) {
            return $c->tipo == 10;
        });

        $apostilas = $conteudos->filter(function ($c) {
            return $c->tipo == 11;
        });

        $pdf = $conteudos->filter(function ($c) {
            return $c->tipo == 15;
        });

        $revista = $conteudo_livro_gigital->filter(function ($c) {
            return $c->tipo == 21;
        });

        $docoficial = $conteudos->filter(function ($c) {
            return $c->tipo == 22;
        });

        $conteudo_tipo[1] = $documentos;
        $conteudo_tipo[2] = $audios;
        $conteudo_tipo[3] = $videos;
        $conteudo_tipo[4] = $slides;
        $conteudo_tipo[5] = $transmissoes;
        $conteudo_tipo[6] = $uploads;
        $conteudo_tipo[7] = $dissertativas;
        $conteudo_tipo[8] = $quizzes;
        $conteudo_tipo[9] = $provas;
        $conteudo_tipo[10] = $entregaveis;
        $conteudo_tipo[11] = $apostilas;
        $conteudo_tipo[15] = $pdf;
        $conteudo_tipo[21] = $revista;
        $conteudo_tipo[22] = $docoficial;

        $getPrivilegios = auth()->user()->Privilegios;

        $Privilegios = [];
        foreach ($getPrivilegios as $getPrivilegio) {
            $Privilegios = $getPrivilegio['id_privilegio'];
        }

        $user_id = auth()->user()->id;

        $etapas = Ciclo::all();

        $cicloEtapas = CicloEtapa::all();

        $disciplinas = Disciplina::all();
        $hasFilters = ($disciplina || $ciclo || $ciclo_etapa || $tem_pesquisa || $tipocatalogo);

        return view('gestao.biblioteca')->with(compact('conteudos', 'conteudo_tipo', 'aplicacoes', 'Privilegios',
                                                        'user_id', 'etapas', 'cicloEtapas', 'disciplinas', 'hasFilters'));
    }

    public function verificaVinculoConteudo($idConteudo)
    {

        $conteudoaula = ConteudoAula::where('conteudo_id', $idConteudo)->count();
        return $conteudoaula;
    }

    public function verificaVinculoCursoTrilha($idCurso)
    {

        $vinculo = TrilhasCurso::where('curso_id', $idCurso)->count();
        return $vinculo;
    }

    public function postNovoConteudo(Request $request)
    {

        if ($request->descricao == null) {
            $request->descricao = '';
        }

        if ($request->obrigatorio == null) {
            $request->obrigatorio = 0;
        } elseif ($request->obrigatorio == 'on') {
            $request->obrigatorio = 1;
        }

        if ($request->tempo == null) {
            $request->tempo = 0;
        }

        if ($request->fonte == null) {
            $request->fonte = '';
        }

        if ($request->autores == null) {
            $request->autores = '';
        }
        switch ($request->tipo) {
            case 1:
                $conteudo = str_replace('"','\'',$request->conteudo);
                break;

            case 2:
                if (isset($request->arquivoAudio)) {
                    $request->arquivo = $request->arquivoAudio;
                    $validator = Validator::make($request->all(), [
                        'arquivoAudio' => 'mimetypes:audio/*|max:350000'
                    ],[
                        'max'=>'Tamanho máximo excedido!','mimetypes' =>'Arquivo de áudio não compatível!'
                    ]);

                    if ($validator->fails()) {
                        return Redirect::back()->withErrors($validator->messages()->first());
                    }
                } else {
                    $request->conteudo = $request->conteudoAudio;
                }
            case 3:
                if (isset($request->arquivoVideo)) {
                    $request->arquivo = $request->arquivoVideo;
                    $validator = Validator::make($request->all(), [
                        'arquivoVideo' => 'mimetypes:video/*|max:500000'
                    ],[
                        'max'=>'Tamanho máximo excedido!','mimetypes' =>'Arquivo de vídeo não compatível!'
                    ]);

                    if ($validator->fails()) {
                        return Redirect::back()->withErrors($validator->messages()->first());
                    }
                } else {
                    $request->conteudo = $request->conteudoVideo;
                }

                break;
            case 4:
                if (isset($request->arquivoSlide)) {
                    $request->arquivo = $request->arquivoSlide;
                    $validator = Validator::make($request->all(), [
                        'arquivoSlide' => 'mimetypes:application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.slideshow,application/vnd.openxmlformats-officedocument.presentationml.presentation,application/zip|max:150000'
                    ],[
                        'max'=>'Tamanho máximo excedido!','mimetypes' =>'Arquivo de slide não compatível!'
                    ]);

                    if ($validator->fails()) {
                        if (
                            $request->arquivoSlide->getMimeType() == 'application/zip' && ($request->arquivoSlide->getClientMimeType() == 'application/vnd.openxmlformats-officedocument.presentationml.presentation')
                        ) {
                            break;
                        } else {
                            return Redirect::back()->withErrors($validator->messages()->first());
                        }
                    }
                } else {
                    $request->conteudo = $request->conteudoSlide;
                }
                break;
            case 5:
                $conteudo = $request->conteudoTransmissao;
                break;
            case 6:
                if (isset($request->arquivoArquivo))
                    $request->arquivo = $request->arquivoArquivo;
                else
                    $request->conteudo = $request->conteudoArquivo;
                break;
            case 7:
                $request->pergunta = str_replace('"','\'',$request->conteudoDissertativa);
                $request->dica = $request->conteudoDissertativaDica;
                $request->explicacao = $request->conteudoDissertativaExplicacao;
                $request->questao_id = $request->questaoTipo7Id;
                $conteudo = json_encode(['pergunta' => $request->conteudoQuizPergunta, 'alternativas' => [$request->conteudoQuizAlternativa1, $request->conteudoQuizAlternativa2, $request->conteudoQuizAlternativa3], 'correta' => $request->conteudoQuizAlternativaCorreta, 'dica' => $request->conteudoQuizDica, 'explicacao' => $request->conteudoQuizExplicacao]);
                break;
            case 8:
                $request->correta = $request->conteudoQuizAlternativaCorreta;
                $request->pergunta = str_replace('"','\'',$request->conteudoQuizPergunta);
                $request->alternativas = [str_replace('"','\'',$request->conteudoQuizAlternativa1), str_replace('"','\'',$request->conteudoQuizAlternativa2), str_replace('"','\'',$request->conteudoQuizAlternativa3)];
                $request->dica = $request->conteudoQuizDica;
                $request->explicacao = $request->conteudoQuizExplicacao;
                $request->questao_id = $request->questaoTipo8Id;
                $conteudo = json_encode(['pergunta' => $request->conteudoQuizPergunta, 'alternativas' => [$request->conteudoQuizAlternativa1, $request->conteudoQuizAlternativa2, $request->conteudoQuizAlternativa3], 'correta' => $request->conteudoQuizAlternativaCorreta, 'dica' => $request->conteudoQuizDica, 'explicacao' => $request->conteudoQuizExplicacao]);
                break;
            case 9:
                $request->questao_id = $request->questaoTipo9Id;
                $conteudo = $request->conteudoProva;
                break;
            case 10:
                $conteudo = str_replace('"','\'',$request->conteudoEntregavel);
                break;
            case 11:
                if (isset($request->arquivoApostila)) {
                    $request->arquivo = $request->arquivoApostila;
                } else {
                    $request->conteudo = $request->conteudoApostila;
                }

                break;
            case 15:
                if (isset($request->arquivoPDF)) {
                    $request->arquivo = $request->arquivoPDF;
                    $validator = Validator::make($request->all(), [
                        'arquivoPDF' => 'mimetypes:application/pdf|max:150000'
                    ],[
                        'max'=>'Tamanho máximo excedido!','mimetypes' =>'Arquivo de PDF não compatível!'
                    ]);

                    if ($validator->fails()) {
                        return Redirect::back()->withErrors($validator->messages()->first());
                    }
                } else {
                    $request->conteudo = $request->conteudoPDF;
                }
                break;

            case 21:
                if (isset($request->arquivoApostila)) {
                    $request->arquivo = $request->arquivoApostila;
                } else {
                    $request->conteudo = $request->conteudoApostila;
                }
                break;

            case 22:
                if (isset($request->arquivoPDF2)) {
                    $request->arquivo = $request->arquivoPDF2;
                    $validator = Validator::make($request->all(), [
                        'arquivoPDF2' => 'mimetypes:application/pdf|max:150000'
                    ],[
                        'max'=>'Tamanho máximo excedido!','mimetypes' =>'Arquivo de PDF não compatível!'
                    ]);

                    if ($validator->fails()) {
                        return Redirect::back()->withErrors($validator->messages()->first());
                    }
                } else {
                    $request->conteudo = $request->conteudoPDF2;
                }
                break;
            case 106:
                $request->merge([
                    'colecao_livro_id' => $request->colecao_destaque_livro_id,
                ]);
                if (isset($request->arquivoVideo)) {
                    $request->arquivo = $request->arquivoVideo;
                    $validator = Validator::make($request->all(), [
                        'arquivoVideo' => 'mimetypes:video/*|max:500000'
                    ],[
                        'max'=>'Tamanho máximo excedido!','mimetypes' =>'Arquivo de vídeo não compatível!'
                    ]);

                    if ($validator->fails()) {
                        return Redirect::back()->withErrors($validator->messages()->first());
                    }
                } else {
                    $request->conteudo = $request->conteudoVideo;
                }

                break;
            default:

                $conteudo = $request->conteudo;
                break;
        }

        if (in_array($request->tipo, [2, 3, 4, 6, 15, 22, 106])) {
            if (isset($request->arquivo)) {
                $originalName = mb_strtolower($request->arquivo->getClientOriginalName(), 'utf-8');

                $fileExtension = \File::extension($request->arquivo->getClientOriginalName());

                $newFileNameArquivo =  md5($request->arquivo->getClientOriginalName() . date("Y-m-d H:i:s") . time()) . '.' . $fileExtension;

                //$path = asset('storage/');

                //$pathArquivo = $request->arquivo->storeAs('conteudos', $newFileNameArquivo, 'public');
                $pathArquivo = $request->arquivo->storeAs('', config('app.frStorage').'/'.$newFileNameArquivo);

                //$path = asset('storage/' . $conteudo->conteudo);

                // Tamanho do arquivo
                $fileSizeConteudo = $request->arquivo->getSize();

                if (!$pathArquivo) {
                    \Session::flash('error', 'Não foi possível fazer upload de seu conteúdo!');
                } else {
                    $conteudo = $newFileNameArquivo;
                }
            } else {
                $conteudo = $request->conteudo;
            }
        } else if ($request->tipo == 11) {
            $conteudo = "index.html";
        } else if ($request->tipo == 21) {
            $conteudo = "index.html";
        }
        //Contagem de registros com mesmo nome, usuario e tipo
        $consultConteudo = Conteudo::where('titulo', $request->titulo)->where('user_id', \Auth::user()->id)->where('tipo', $request->tipo)
            ->get()->count();
        /* if($consultConteudo > 0){
            return Redirect::back()->withErrors(['Já existe um conteúdo com esse nome!']);
        } */

        $instituicao = session('instituicao');
        $dados = $request->all();
        $dados['user_id'] = \Auth::user()->id;
        $dados['conteudo'] = $conteudo;
        $dados['instituicao_id'] = $instituicao['id'];

        if($request->tipo == 3 || $request->tipo == 2){
            $dados['id_google'] = base64_encode(Hash::make(date('y-m-d H:i:s').rand(1111,9999)));
            $dados['compartilhado_google'] = 1;
        }

        if($request->file('capaVideo'))
        {

            $img = Image::make($request->file('capaVideo'));
            $img->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
            })->encode('webp',90);

            $fileName = $img->filename.'.webp';
            $resource = $img->stream()->detach();
            $localCapa = 'capa_videos/';
            if($request->tipo == 106){
                $localCapa = 'capa_videos_acao_destaque/';
            }
            Storage::disk()->put(config('app.frStorage').$localCapa.$fileName, $resource);

            $dados['capa'] = $fileName;
        }

        $novo_conteudo = Conteudo::create($dados);

        if ($request->tipo == 11) {

            $zipperFile = \Zipper::make($request->arquivo);

            $logFiles = $zipperFile->listFiles();

            if (in_array("index.html", $logFiles) == false || in_array("index.js", $logFiles) == false) {
                $novo_conteudo->delete();

                return redirect()->back()->withErrors("Conteúdo do zip inválido, por favor consulte as instruções de upload do  digital!");
            }

            $zipperFile->extractTo(public_path('uploads') . '/apostilas/' . $novo_conteudo->id . '/');

            if (!\Storage::disk('public_uploads')->has('apostilas/' . $novo_conteudo->id)) {
                $novo_conteudo->delete();

                return redirect()->back()->withErrors("Não foi possível extrair o conteúdo do zip, por favor envie sua aplicação novamente!");
            }
        }

        if ($request->tipo == 21) {

            $file = $request->file('arquivoRevista');
            $destino = config('app.frTmp').'livro_digital/'.$novo_conteudo->id;

            $zip = new \ZipArchive();
            $zip->open($file->path());
            $zip->extractTo($destino);
            $zip->close();

            $myfiles = array_diff(scandir($destino), array('.', '..'));
            $i=0;
            foreach ($myfiles as $f){
                if(is_file($destino.'/'.$f)){
                    $resource = file_get_contents($destino.'/'.$f);
                    Storage::disk()->put(config('app.frStorage').'livrodigital/'.$novo_conteudo->id.'/'.$f, $resource);
                    $i++;
                }
            }
            $novo_conteudo->update(['qtd_paginas_livro'=>$i-2]);
            /*
            dd($novo_conteudo->id);


            if (empty($file)) {
                return redirect()->back()->withErrors("Selecione um arquivo!");
            }

            $zipperFile = \Zipper::make($request->arquivoRevista);

            $logFiles = $zipperFile->listFiles();

            if (!Storage::disk('public')->has('livrodigital/' . $novo_conteudo->id . '/')) {
                Storage::disk('public')->makeDirectory('livrodigital/' . $novo_conteudo->id . '/');
                $zipperFile->extractTo('storage/livrodigital/' . $novo_conteudo->id . '/');
            }

            if (!\Storage::disk('public')->has('livrodigital/' . $novo_conteudo->id)) {
                $novo_conteudo->delete();
                return redirect()->back()->withErrors("Não foi possível extrair o conteúdo do zip, por favor envie seu arquivo novamente!");
            }
            */
        }

        if (in_array($request->tipo, [2, 3, 4, 6, 15, 22])) {

           if($request->conteudoVideo == ''){
            $novo_conteudo->update([
                'file_size' => $fileSizeConteudo
            ]);
           }
        }

        if (in_array($request->tipo, [7, 8, 9])) {
            $request->novoConteudo = $novo_conteudo->id;
            ConteudoService::salvarQuestoes($request);
        }

        return Redirect::route('gestao.biblioteca')->with('message', 'Conteúdo criado com sucesso!');
    }

    public function editarConteudo($idConteudo)
    {
        if (Conteudo::where([['id', '=', $idConteudo]])->first() != null) {
            $conteudo = Conteudo::where([['id', '=', $idConteudo]])->first();

            if (in_array($conteudo->tipo, [2, 3, 4, 6, 15])) {
                if (\Storage::disk('local')->has('uploads/conteudos/' . $conteudo->conteudo)) {
                    $conteudo->temArquivo = true;
                } else {
                    $conteudo->temArquivo = false;
                }
            }

            if (in_array($conteudo->tipo, [7, 8, 9])) {
                $questoes = $conteudo->questoes;
                $conteudo->conteudo = ConteudoService::ajustarQuestoes($questoes);
                $conteudo->questoes_id = $questoes->pluck('id')->toArray();
            }

            return response()->json(['success' => 'Conteudo encontrado..', 'conteudo' => json_encode($conteudo)]);
        } else {
            return response()->json(['error' => 'Conteúdo não encontrado!']);
        }
    }

    public function postSalvarConteudo(Request $request)
    {
        if (Conteudo::where([['id', '=', $request->idConteudo]])->first() != null) {
            $conteudoOriginal = Conteudo::where([['id', '=', $request->idConteudo]])->first();

            if ($request->descricao == null) {
                $request->descricao = '';
            }

            if ($request->obrigatorio == null) {
                $request->obrigatorio = 0;
            } elseif ($request->obrigatorio == 'on') {
                $request->obrigatorio = 1;
            }

            if ($request->tempo == null) {
                $request->tempo = 0;
            }

            if ($request->fonte == null) {
                $request->fonte = '';
            }

            if ($request->autores == null) {
                $request->autores = '';
            }

            switch ($request->tipo) {
                case 1:
                    $conteudo = str_replace('"','\'',$request->conteudo);
                    break;

                case 2:
                    if (isset($request->arquivoAudio)) {
                        $request->arquivo = $request->arquivoAudio;
                        $validator = Validator::make($request->all(), [
                            'arquivoAudio' => 'mimetypes:audio/*|max:350000'
                        ],[
                            'max'=>'Tamanho máximo excedido!','mimetypes' =>'Arquivo de áudio não compatível!'
                        ]);

                        if ($validator->fails()) {
                            return Redirect::back()->withErrors($validator->messages()->first());
                        }
                    } else {
                        $request->conteudo = $request->conteudoAudio;
                    }
                case 3:
                    if (isset($request->arquivoVideo)) {
                        $request->arquivo = $request->arquivoVideo;
                        $validator = Validator::make($request->all(), [
                            'arquivoVideo' => 'mimetypes:video/*|max:500000'
                        ],[
                            'max'=>'Tamanho máximo excedido!','mimetypes' =>'Arquivo de vídeo não compatível!'
                        ]);

                        if ($validator->fails()) {
                            return Redirect::back()->withErrors($validator->messages()->first());
                        }
                    } else {
                        $request->conteudo = $request->conteudoVideo;
                    }
                    break;
                case 4:
                    if (isset($request->arquivoSlide)) {
                        $request->arquivo = $request->arquivoSlide;
                        $validator = Validator::make($request->all(), [
                            'arquivoSlide' => 'mimetypes:application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.slideshow,application/vnd.openxmlformats-officedocument.presentationml.presentation,application/zip|max:150000'
                        ],[
                            'max'=>'Tamanho máximo excedido!','mimetypes' =>'Arquivo de slide não compatível!'
                        ]);

                        if ($validator->fails()) {
                            if (
                                $request->arquivoSlide->getMimeType() == 'application/zip' && ($request->arquivoSlide->getClientMimeType() == 'application/vnd.openxmlformats-officedocument.presentationml.presentation')
                            ) {
                                break;
                            } else {
                                return Redirect::back()->withErrors($validator->messages()->first());
                            }
                        }
                    } else {
                        $request->conteudo = $request->conteudoSlide;
                    }
                    break;
                case 5:
                    $conteudo = $request->conteudoTransmissao;
                    break;
                case 6:
                    if (isset($request->arquivoArquivo))
                        $request->arquivo = $request->arquivoArquivo;
                    else
                        $request->conteudo = $request->conteudoArquivo;
                    break;
                case 7:
                    $request->pergunta = str_replace('"','\'',$request->conteudoDissertativa);
                    $request->dica = $request->conteudoDissertativaDica;
                    $request->explicacao = $request->conteudoDissertativaExplicacao;
                    $request->questao_id = $request->questaoTipo7Id;
                    $conteudo = json_encode(['pergunta' => $request->conteudoQuizPergunta, 'alternativas' => [$request->conteudoQuizAlternativa1, $request->conteudoQuizAlternativa2, $request->conteudoQuizAlternativa3], 'correta' => $request->conteudoQuizAlternativaCorreta, 'dica' => $request->conteudoQuizDica, 'explicacao' => $request->conteudoQuizExplicacao]);
                    break;
                case 8:
                    $request->correta = $request->conteudoQuizAlternativaCorreta;
                    $request->pergunta = str_replace('"','\'',$request->conteudoQuizPergunta);
                    $request->alternativas = [str_replace('"','\'',$request->conteudoQuizAlternativa1), str_replace('"','\'',$request->conteudoQuizAlternativa2), str_replace('"','\'',$request->conteudoQuizAlternativa3)];
                    $request->dica = $request->conteudoQuizDica;
                    $request->explicacao = $request->conteudoQuizExplicacao;
                    $request->questao_id = $request->questaoTipo8Id;
                    $conteudo = json_encode(['pergunta' => $request->conteudoQuiz, 'alternativas' => [$request->conteudoQuizAlternativa1, $request->conteudoQuizAlternativa2, $request->conteudoQuizAlternativa3], 'correta' => $request->conteudoQuizAlternativaCorreta, 'dica' => $request->conteudoQuizDica, 'explicacao' => $request->conteudoQuizExplicacao]);
                    break;
                case 9:
                    $request->questao_id = $request->questaoTipo9Id;
                    $conteudo = $request->conteudoProva;
                    break;
                case 10:
                    $conteudo = str_replace('"','\'',$request->conteudoEntregavel);
                    break;
                case 11:
                    if (isset($request->arquivoApostila))
                        $request->arquivo = $request->arquivoApostila;
                    else
                        $request->conteudo = $request->conteudoApostila;
                    break;
                case 21:
                    if (isset($request->arquivoRevista)) {
                        $request->arquivo = $request->arquivoRevista;
                        $request->conteudo = 'index.html';
                    } else {
                        $request->conteudo = 'index.html';
                    }
                    break;
                case 15:
                    if (isset($request->arquivoPDF)) {
                        $request->arquivo = $request->arquivoPDF;
                        $validator = Validator::make($request->all(), [
                            'arquivoPDF' => 'mimetypes:application/pdf|max:150000'
                        ],[
                            'max'=>'Tamanho máximo excedido!','mimetypes' =>'Arquivo de PDF não compatível!'
                        ]);

                        if ($validator->fails()) {
                            return Redirect::back()->withErrors($validator->messages()->first());
                        }
                    } else {
                        $request->conteudo = $request->conteudoPDF;
                    }
                    break;

                case 22:
                    if (isset($request->arquivoPDF2)) {

                        $request->arquivo = $request->arquivoPDF2;
                        $validator = Validator::make($request->all(), [
                            'arquivoPDF' => 'mimetypes:application/pdf|max:150000'
                        ],[
                            'max'=>'Tamanho máximo excedido!','mimetypes' =>'Arquivo de PDF não compatível!'
                        ]);

                        if ($validator->fails()) {
                            return Redirect::back()->withErrors($validator->messages()->first());
                        }
                    } else {
                        $request->conteudo = $request->arquivoPDF2;
                    }
                    break;

                default:
                    $conteudo = $request->conteudo;
                    break;
            }

            if (in_array($request->tipo, [2, 3, 4, 6, 15, 22])) {
                if (isset($request->arquivo)) {

                    if ($conteudoOriginal->conteudo != null) {

                        if (\Storage::disk('local')->has('uploads/conteudos/' . $conteudoOriginal->conteudo)) {
                            \Storage::disk('local')->delete('uploads/conteudos/' . $conteudoOriginal->conteudo);
                        }
                    }

                    $originalName = mb_strtolower($request->arquivo->getClientOriginalName(), 'utf-8');

                    $fileExtension = \File::extension($request->arquivo->getClientOriginalName());

                    $newFileNameArquivo =  md5($request->arquivo->getClientOriginalName() . date("Y-m-d H:i:s") . time()) . '.' . $fileExtension;

                    $pathArquivo = $request->arquivo->storeAs('', $newFileNameArquivo, 'local');

                    // Tamanho do arquivo
                    $fileSizeConteudo = $request->arquivo->getClientSize();

                    if (!\Storage::disk('public')->put($pathArquivo, file_get_contents($request->arquivo))) {
                        \Session::flash('error', 'Não foi possível fazer upload de seu conteúdo!');
                    } else {
                        $conteudo = $newFileNameArquivo;
                    }
                } else {
                    if ($request->conteudo != "" && $request->conteudo != null)
                        $conteudo = $request->conteudo;
                    else
                        $conteudo = $conteudoOriginal->conteudo;
                }
            } else if (in_array($request->tipo, [11, 21])) {
                $conteudo = $request->conteudo;
            }

            if ($request->tipo == 21) {

                $tem_arquivo = isset($request->arquivoRevista);


                if ($tem_arquivo) {
                    $zipperFile = \Zipper::make($request->arquivoRevista);

                    if (Storage::disk('public')->has('livrodigital/' . $request->idConteudo . '/')) {
                        Storage::disk('public')->deleteDirectory('livrodigital/' . $request->idConteudo . '/');
                        Storage::disk('public')->makeDirectory('livrodigital/' . $request->idConteudo . '/');
                        $zipperFile->extractTo('storage/livrodigital/' . $request->idConteudo . '/');
                    }

                    if (!\Storage::disk('public')->has('livrodigital/' . $request->idConteudo . '/')) {
                        return redirect()->back()->withErrors("Não foi possível extrair o conteúdo do zip, por favor envie sua aplicação novamente!");
                    }
                }
            }

            $conteudoOriginal->update([
                'ciclo_id'      => $request->ciclo_id,
                'cicloetapa_id' => $request->cicloetapa_id,
                'disciplina_id' => $request->disciplina_id,
                'titulo'        => $request->titulo,
                'descricao'     => $request->descricao,
                'conteudo'      => $conteudo,
                'fonte'         => $request->fonte,
                'autores'       => $request->autores,
                'status'        => $request->status,
            ]);

            if (in_array($request->tipo, [7, 8, 9])) {
                ConteudoService::salvarQuestoes($request);
            }

            return Redirect::route('gestao.biblioteca')->with('message', 'Conteúdo atualizado com sucesso!');
        } else {
            return response()->json(['error' => 'Conteúdo não encontrado!']);
        }
    }

    public function postDuplicarConteudo(Request $request)
    {
        if (Conteudo::where([['id', '=', $request->idConteudo]])->first() != null) {
            $newConteudo = Conteudo::where([['id', '=', $request->idConteudo]])->first()->replicate();

            $newConteudo->id = null;
            // $newConteudo->id = Conteudo::max('id') + 1;

            $newConteudo->save();

            \Session::flash('message', 'Conteúdo duplicado com sucesso!');
            return redirect()->route('gestao.biblioteca');
        } else {
            return Redirect::back()->withErrors(['Conteúdo não encontrado!']);
        }
    }

    public function postExcluirConteudo($idConteudo, Request $request)
    {
        if (Conteudo::find($idConteudo) != null) {
            $conteudo = Conteudo::where([['id', '=', $request->idConteudo]])->first();

            if ($conteudo->tipo == 2 || $conteudo->tipo == 3 || $conteudo->tipo == 4 || $conteudo->tipo == 6) {
                if ($conteudo->conteudo != null) {
                    if (\Storage::disk('local')->has('uploads/conteudos/' . $conteudo->conteudo)) {
                        \Storage::disk('local')->delete('uploads/conteudos/' . $conteudo->conteudo);
                    }
                }
            }

            Conteudo::find($idConteudo)->delete();

            return response()->json(["success" => "Aplicação excluída com sucesso!"]);
        } else {
            return response()->json(["error" => "Aplicação não encontrada!"]);
        }
    }

    public function cursosProfessores()
    {
        $cursos = Curso::query();

        $tem_pesquisa = Input::has('pesquisa') ? (Input::get('pesquisa') != null && Input::get('pesquisa') != "") : false;

        $cursos->when($tem_pesquisa, function ($query) {
            return $query
                ->where('titulo', 'like', '%' . Input::get('pesquisa') . '%')
                ->orWhere('descricao', 'like', '%' . Input::get('pesquisa') . '%');
        });

        $is_admin = strtoupper(Auth::user()->permissao) == "Z";

        $cursos->when($is_admin == false, function ($query) {
            return $query->where([['tipo', '=', 2], ['escola_id', '=', Auth::user()->escola_id], ['user_id', '=', Auth::user()->id]])
                ->orWhere([['tipo', '=', 2], ['user_id', '=', Auth::user()->id]]);
        });

        $cursos->when($is_admin == true, function ($query) {
            return $query->where([['tipo', '=', 2]]);
        });

        $cursos = $cursos
            ->where([['status', '=', 1]])
            ->orderBy('id', 'DESC')
            ->get();

        return view('gestao.cursos-professores')->with(compact('cursos'));
    }

    public function cursos(Request $request)
    {
        // Condição para trazer todos os cursos que não estejam desativados
        $cursos = Curso::where([['cursos.cursos_tipo_id', '=', 1], ['cursos.status_id', '!=', 2]])->whereNull('cursos.instituicao_id');

        $tem_pesquisa = false;
        if($request->input('pesquisa') != ''){
            $tem_pesquisa = $request->input('pesquisa');
        }

        if(Auth::user()->privilegio_id == 2){

            //$instituicaoUser = InstituicaoUser::where('user_id', Auth::user()->id)->first()->instituicao_id;

            $instituicao = session('instituicao');
            $instituicaoUser = $instituicao['id'];

            $cursos = $cursos->where([['cursos.status_id', '!=', 2],['escolas.instituicao_id', $instituicaoUser]])
                                ->join('escolas', 'cursos.escola_id', 'escolas.id')
                                    ->select('cursos.*', 'escolas.instituicao_id');

        }elseif(Auth::user()->privilegio_id == 5){
            $responsavelId = ResponsavelEscola::where('user_id', Auth::user()->id)->first()->escola_id;
            $cursos = $cursos->where('escola_id', $responsavelId);

        }elseif(Auth::user()->privilegio_id == 3){
            #Mostra os cursos que o usuário é professor e que criou
            $cursos = $cursos->where('cursos.user_id', Auth::user()->id)
                            ->orwhere('professor_cursos.user_id', Auth::user()->id)
                                ->leftjoin('professor_cursos', 'cursos.id', 'professor_cursos.curso_id')
                                ->select('cursos.*');
            }

        if($tem_pesquisa){
                $cursos = $cursos->where('titulo', 'like', '%' . $request->input('pesquisa') . '%')
                    ->orWhere('descricao', 'like', '%' . $request->input('pesquisa') . '%');
        }

        $cursos = $cursos->with('escola')->paginate(20);


        $escolas = Escola::join('professor_escolas', 'escolas.id', 'professor_escolas.escola_id')
                          ->where('professor_escolas.user_id', Auth::user()->id)
                          ->orderBy('escolas.titulo')
                          ->selectRaw('escolas.*')
                          ->get();

        return view('gestao.cursos')->with(compact('cursos','escolas'));
    }

    public function cursoslivres()
    {
        $cursos = Curso::query();

        // Condição para trazer todos os cursos que não estejam desativados
        $cursos->where([
            ['cursos_tipo_id', '=', 2],
            ['cursos.status_id', '!=', 2]
        ]);

        //$instituicaoUser = InstituicaoUser::where('user_id', Auth::user()->id)->first()->instituicao_id;
        $instituicao = session('instituicao');
        $instituicaoUser = $instituicao['id'];

        if(Auth::user()->privilegio_id == 2){
            $cursos = $cursos->where([['escolas.instituicao_id', $instituicaoUser]])
                                ->join('escolas', 'cursos.escola_id', 'escolas.id')
                                ->select('cursos.*', 'escolas.instituicao_id');

        }elseif(Auth::user()->privilegio_id == 3){
            // Mostra os cursos livres da escola que ele é professor e os cursos que ele criou
            $cursos = $cursos->where('professor_escolas.user_id', Auth::user()->id)
                                ->orwhere('cursos.user_id', Auth::user()->id)
                                ->leftjoin('professor_escolas', 'cursos.escola_id', 'professor_escolas.escola_id')
                                ->select('cursos.*');

        }elseif(Auth::user()->privilegio_id == 5){
            // Mostra os cursos livres da escola que ele é responsavel e os cursos que ele criou
            $cursos = $cursos->where('responsavel_escolas.user_id', Auth::user()->id)
                                ->orwhere('cursos.user_id', Auth::user()->id)
                                ->leftjoin('responsavel_escolas', 'cursos.escola_id', 'responsavel_escolas.escola_id')
                                ->select('cursos.*');
        }

        $tem_pesquisa = Input::has('pesquisa') ? (Input::get('pesquisa') != null && Input::get('pesquisa') != "") : false;

        if($tem_pesquisa){
            $cursos = $cursos->where('titulo', 'like', '%' . Input::get('pesquisa') . '%')
                ->orWhere('descricao', 'like', '%' . Input::get('pesquisa') . '%');
        }

        $cursos = $cursos->where('cursos_tipo_id', '=', 2)->paginate(20);

        return view('gestao.cursolivre.cursos')->with(compact('cursos'));
    }

    public function novoCurso()
    {
        $categorias = Categoria::all();

        $user = Auth::user();

        //$instituicaoUser = InstituicaoUser::where('user_id', $user->id)->first()->instituicao_id;
        $instituicao = session('instituicao');
            $instituicaoUser = $instituicao['id'];

        if($user->privilegio_id != 1){
            #mudar quando outros perfis forem relacionados a mais de uma instituicao
            $instituicoes = Instituicao::where('id', $instituicaoUser)->get();
        }else{
            $instituicoes = Instituicao::all();
        }

        $escolas = Escola::where('escolas.status_id', '!=', 2);

        #privilegio school
        if($user->privilegio_id == 5){
            $escolas = $escolas->where('responsavel_escolas.user_id', $user->id)
                                ->join('responsavel_escolas', 'escolas.id', 'responsavel_escolas.escola_id');
        #privilegio professor
        }elseif($user->privilegio_id == 3){
            /*método provisorio, verificar o relacionamento posteriormente
             o correto é o relacionamento de 1:N na tabela de professor, não esta bem definida
             a AVALIAR
            */
            $escolas = $escolas->where('professor_escolas.user_id', $user->id)
                                    ->join('professor_escolas', 'escolas.id', 'professor_escolas.escola_id');

        }else{
                $escolas = $escolas->where('instituicao_id', $instituicaoUser);
            }

        $escolas = $escolas->selectRaw('escolas.*')->orderby('escolas.titulo', 'ASC')->get();

        $etapas = Ciclo::all();

        $cicloEtapas = CicloEtapa::all();

        $disciplinas = Disciplina::all();

        $visibilidades = [];//Visibilidade::all();

        #mudar para tabela professores quando estiver mais bem definido
        $professores = User::where([
                ['privilegio_id', '!=', 2],
                ['privilegio_id', 3]
            ]);

        if($user->privilegio_id == 2){

            $professores = InstituicaoUser::where([
                                            ['privilegio_id', '!=', 2],
                                            ['privilegio_id', 3],
                                            ['instituicao_id', $instituicaoUser]
                                        ])
                                         ->join('users', 'instituicao_users.user_id', 'users.id')
                                          ->select('users.*','instituicao_users.instituicao_id');

        }elseif($user->privilegio_id == 3){
            $professores = $professores->where('id', $user->id);

        }elseif($user->privilegio_id == 5){
            $responsavelId = ResponsavelEscola::where('user_id', $user->id)->first()->escola_id;
            $professores = $professores->where('professor_escolas.escola_id', $responsavelId)
                                            ->join('professor_escolas', 'users.id', 'professor_escolas.user_id')
                                                ->select('users.*');
        }

        $professores = $professores->get();

        //dd($escolas);
        return view('gestao.novo-curso')->with(compact(
            'categorias',
            'escolas',
            'etapas',
            'cicloEtapas',
            'disciplinas',
            'instituicoes',
            'visibilidades',
            'professores'
        ));
    }

    public function novoCursoLivre()
    {
        $categorias = Categoria::all();

        $instituicaoUser = InstituicaoUser::where('user_id', Auth::user()->id)->first()->instituicao_id;

        $user = Auth::user();

        $instituicoes = Instituicao::query();

        if($user->privilegio_id != 1)
            $instituicoes = $instituicoes->where('id', $instituicaoUser);

        $instituicoes = $instituicoes->get();

        $user = Auth::user();

        $escolas = Escola::where('escolas.status_id', '!=', 2);

        #privilegio school
        if($user->privilegio_id == 5){
            $escolas = $escolas->where('responsavel_escolas.user_id', $user->id)
                                ->join('responsavel_escolas', 'escolas.id', 'responsavel_escolas.escola_id');
        #privilegio professor
        }elseif($user->privilegio_id == 3){
            /*método provisorio, verificar o relacionamento posteriormente
             o correto é o relacionamento de 1:N na tabela de professor, não esta bem definida
             a AVALIAR
            */
            $escolas = $escolas->where('professor_escolas.user_id', $user->id)
                                    ->join('professor_escolas', 'escolas.id', 'professor_escolas.escola_id');

        }else{
                $escolas = $escolas->where('instituicao_id', $instituicaoUser);
            }

        $escolas = $escolas->orderby('escolas.titulo', 'ASC')->get();

        $etapas = Ciclo::all();

        $cicloEtapas = CicloEtapa::all();

        $disciplinas = Disciplina::all();

        $visibilidades = Visibilidade::all();

        return view('gestao.cursolivre.novo-curso')->with(compact(
            'categorias',
            'escolas',
            'etapas',
            'cicloEtapas',
            'disciplinas',
            'instituicoes',
            'visibilidades'
        ));
    }

    public function postNovoCurso(Request $request)
    {
        // try{

            if ($request->preco == null) {
                $request->preco = 0;
            }

            if ($request->senha == null) {
                $request->senha = '';
            }

            if ($request->descricao_curta == null) {
                $request->descricao_curta = '';
            }

            if ($request->descricao == null) {
                $request->descricao = '';
            }

            // Se o tipo do curso for null ou diferente de 2 atribui 1
            // Tipo 1 é um curso normal, e não um curso livre

            $request->preco = str_replace(".", "", $request->preco);
            $request->preco = str_replace(",", ".", $request->preco);

            if (is_array($request->escola_id)){
                foreach($request->escola_id as $escola){
                    $curso = Curso::create([
                        'escola_id'       => $escola,
                        'user_id'         => \Auth::user()->id,
                        'ciclo_id'        =>  $request->ciclo_id,
                        'cicloetapa_id'   =>  $request->cicloetapa_id,
                        'disciplina_id'   =>  $request->disciplina_id,
                        'tag'             =>  $request->tag,
                        'titulo'          => $request->titulo,
                        'descricao_curta' => $request->descricao_curta,
                        'descricao'       => $request->descricao,
                        'categoria'       => $request->categoria,
                        'tipo'            => $request->cursos_tipo_id,
                        'visibilidade'    => $request->visibilidade,
                        'senha'           => $request->senha,
                        'preco'           => $request->preco,
                        'periodo'         => $request->periodo,
                        'vagas'           => $request->vagas,
                        'link_checkout'   => $request->link_checkout,
                        'identificador'   => $request->identificador,
                        'cursos_tipo_id'  => $request->cursos_tipo_id,
                    ]);

                    #Relaciona usuário com curso
                    if($request->professor_id){
                        foreach($request->professor_id as $professor){
                            $professor_curso = ProfessorCurso::create([
                                'user_id'   => $professor,
                                'escola_id' => $escola,
                                'curso_id'  => $curso->id,
                            ]);
                        }
                    }

                    if ($request->capa != null) {
                        //$fileExtension = \File::extension($request->capa->getClientOriginalName());
                        //$newFileNameCapa =  md5($request->capa->getClientOriginalName() . date("Y-m-d H:i:s") . time()) . '.' . $fileExtension;

                        //$pathCapa = $request->capa->storeAs(config('app.frStorage').'uploads/cursos/capas', $newFileNameCapa);
                        $path = $request->file('capa')->store(config('app.frStorage').'uploads/cursos/capas');

                        //if (!\Storage::disk('public_uploads')->put($pathCapa, file_get_contents($request->capa))) {
                        ///    \Session::flash('middle_popup', 'Não foi possivel enviar a capa.');
                       //     \Session::flash('popup_style', 'danger');
                       // } else {
                            $curso->capa = basename($path);

                            $curso->save();
                        //}
                    }
                }
            }
            else
            {
                $curso = Curso::create([
                    'escola_id'       => $request->escola_id ,
                    'user_id'         => \Auth::user()->id,
                    'ciclo_id'        =>  $request->ciclo_id,
                    'cicloetapa_id'   =>  $request->cicloetapa_id,
                    'disciplina_id'   =>  $request->disciplina_id,
                    'tag'             =>  $request->tag,
                    'titulo'          => $request->titulo,
                    'descricao_curta' => $request->descricao_curta,
                    'descricao'       => $request->descricao,
                    'categoria'       => $request->categoria,
                    'tipo'            => $request->cursos_tipo_id,
                    'visibilidade'    => $request->visibilidade,
                    'senha'           => $request->senha,
                    'preco'           => $request->preco,
                    'periodo'         => $request->periodo,
                    'vagas'           => $request->vagas,
                    'link_checkout'   => $request->link_checkout,
                    'identificador'   => $request->identificador,
                    'cursos_tipo_id'  => $request->cursos_tipo_id,
                ]);

                #Relaciona usuário com curso
                if($request->professor_id){
                    foreach($request->professor_id as $professor){
                        $professor_curso = ProfessorCurso::create([
                            'user_id'   => $professor,
                            'escola_id' => '',
                            'curso_id'  => $curso->id,
                        ]);
                    }
                }

                if ($request->capa != null) {
                    $fileExtension = \File::extension($request->capa->getClientOriginalName());
                    $newFileNameCapa =  md5($request->capa->getClientOriginalName() . date("Y-m-d H:i:s") . time()) . '.' . $fileExtension;

                    $pathCapa = $request->capa->storeAs('cursos/capas', $newFileNameCapa, 'public_uploads');

                    if (!\Storage::disk('public_uploads')->put($pathCapa, file_get_contents($request->capa))) {
                        \Session::flash('middle_popup', 'Não foi possivel enviar a capa.');
                        \Session::flash('popup_style', 'danger');
                    } else {
                        $curso->capa = $newFileNameCapa;

                        $curso->save();
                    }
                }
            }

            if ($request->rascunho) {
                if ($request->cursos_tipo_id == 2) {
                    return redirect()->route('gestao.cursoslivres');
                } else {
                    return redirect()->route('gestao.cursos');
                }
            } else {
                if ($request->cursos_tipo_id == 2) {
                    return redirect()->route('gestao.curso-livre-conteudo', ['idCurso' => $curso->id]);
                } else {
                    return redirect()->route('gestao.curso-conteudo', ['idCurso' => $curso->id]);
                }
            }

        // } catch (\Exception $exception) {
        //     return redirect()->back()->with('error', 'Erro para criar registro!');
        // }
    }

    public function postSalvarCurso($idCurso, Request $request)
    {
        if (strtoupper(Auth::user()->permissao) == "E") {
            $escola = Escola::where('user_id', '=', Auth::user()->id)->first();
        }

        if (!isset($escola)) {
            $escola = Escola::find(1);
        } elseif ($escola == null) {
            $escola = Escola::find(1);
        }
        // $escola->caracteristicas = json_decode($escola->caracteristicas);

        if (Curso::find($idCurso)) {
            $curso = Curso::find($idCurso);

            if (config('app.name') == "Toolzz") {
                if (!\App\WirecardAccount::where('user_id', '=', $curso->user_id)->exists() && $request->preco > 0 && $curso->status != 0) {
                    $curso->update([
                        'status' => 0
                    ]);

                    return Redirect::back()->withErrors(['Para publicar um conteúdo pago, você deve vincular uma conta Wirecard. Para mais informações por favor entre em contato com nosso suporte. (Erro.: TZ-04)!']);
                }
            }

            if ($request->preco == null) {
                $request->preco = 0;
            }

            if ($request->senha == null) {
                $request->senha = '';
            }

            if ($request->preco == null) {
                $request->preco = 0;
            }

            if ($request->vagas == null) {
                $request->vagas = 0;
            }

            if ($request->senha == null) {
                $request->senha = '';
            }

            if ($request->descricao == null) {
                $request->descricao = '';
            }

            if (isset($request->escola_id) ? $request->escola_id == null : true) {
                $request->escola_id = 1;
            }

            $request->preco = str_replace(".", "", $request->preco);
            $request->preco = str_replace(",", ".", $request->preco);
            $tipo = $request->tipo;
            if($tipo == ''){
                $tipo = 1;
            }
            $curso->update([
                'escola_id' => $request->escola_id,
                'ciclo_id' =>  $request->ciclo_id,
                'cicloetapa_id' =>  $request->cicloetapa_id,
                'disciplina_id' =>  $request->disciplina_id,
                'tag' =>  $request->tag,
                'titulo' => $request->titulo,
                'descricao_curta' => $request->descricao_curta.' ',
                'descricao' => $request->descricao,
                'categoria' => $request->categoria,
                'tipo' => $tipo,
                'visibilidade' => $request->visibilidade,
                'senha' => $request->senha,
                'preco' => $request->preco,
                'periodo' => $request->periodo,
                'vagas' => $request->vagas,
                'link_checkout' => $request->link_checkout,
                'identificador' => $request->identificador,
            ]);

            if ($request->capa != null) {
                $fileExtension = \File::extension($request->capa->getClientOriginalName());
                $newFileNameCapa =  md5($request->capa->getClientOriginalName() . date("Y-m-d H:i:s") . time()) . '.' . $fileExtension;

                $pathCapa = $request->capa->storeAs('cursos/capas', $newFileNameCapa, 'public_uploads');

                if (!\Storage::disk('public_uploads')->put($pathCapa, file_get_contents($request->capa))) {
                    \Session::flash('middle_popup', 'Não foi possivel enviar a capa.');
                    \Session::flash('popup_style', 'danger');
                } else {
                    if ($curso->capa != null) {
                        if (\Storage::disk('public_uploads')->has('cursos/capas/' . $curso->capa)) {
                            \Storage::disk('public_uploads')->delete('cursos/capas/' . $curso->capa);
                        }
                    }

                    $curso->capa = $newFileNameCapa;

                    $curso->save();
                }
            }

            return Redirect::back()->with('message', 'Conteúdo atualizado com sucesso!');
        } else {
            return Redirect::back()->withErrors(['Conteúdo não encontrado!']);
        }
    }

    public function postExcluirCurso($idCurso)
    {
        try {
            if (Curso::find($idCurso)) {
                // Pode inativar o curso se o criador for o usuário logado ou ser o super admin, admin ou school
                if (Curso::find($idCurso)->user_id == Auth::user()->id || in_array(Auth::user()->privilegio_id, [1, 2, 5])) {
                    //
                    $curso = Curso::find($idCurso);
                    $curso->update(['status_id' => 2]);
                    $curso->delete();
                    return Redirect::back()->with('message', 'Conteúdo excluido com sucesso!');

                } else {
                    return Redirect::back()->with('error', 'Ação não permitida!');
                }
            } else {
                return Redirect::back()->with('error', 'Conteúdo não encontrado!');
            }
        } catch (\Exception $exception) {
            return Redirect::back()->with('error', 'Erro para excluir registro!');
        }
    }

    public function postPublicarCurso($idCurso)
    {
        if (Curso::find($idCurso)) {
            if (Curso::find($idCurso)->status == 0) {
                if (ConteudoAula::where('curso_id', '=', $idCurso)->exists() == false) {
                    return Redirect::back()->withErrors(['Você não pode publicar sem nenhum conteúdo!']);
                }

                if (config('app.name') == "Toolzz") {
                    if (Curso::find($idCurso)->preco > 0 && !WirecardAccount::where('user_id', '=', Curso::find($idCurso)->user_id)->exists()) {
                        return Redirect::back()->withErrors(['Você não pode publicar um conteúdo pago, sem antes vincular sua conta Wirecard!']);
                    }
                }

                Curso::find($idCurso)->update([
                    'status' => 1,
                    'data_publicacao' => date('Y-m-d H:i:s'),
                ]);

                return Redirect::back()->with('message', 'Conteúdo publicado com sucesso!');
            } else {
                Curso::find($idCurso)->update(['status' => 0]);

                return Redirect::back()->with('message', 'Conteúdo despublicado com sucesso!');
            }
        } else {
            return Redirect::back()->withErrors(['Conteúdo não encontrado 9!']);
        }
    }

    public function conteudoCurso($idCurso, Request $request)
    {
        $curso = Curso::with(['aulas' => function($query) {
            $query->orderBy('ordem', 'DESC');
        }])->find($idCurso);

        /*
        if(auth()->user()->isAdmin) {
            //$conteudos = Conteudo::all();
            $conteudos = Conteudo::join('users','conteudos.user_id','users.id')->where('tipo','<>',21)
                                    ->where('tipo','<',100)
                                    ->where('user_id',1)
                                    ->selectRaw('conteudos.*,users.nome_completo')
                                    ->get();
        } else {

            $conteudos = Conteudo::join('users','conteudos.user_id','users.id')->where(function($q){
                                            $q->where('tipo','<',100)
                                            ->where('user_id',1)
                                            ->where('tipo','<>',21);
                                    })
                                    ->orWhere('user_id',Auth::user()->id)
                                    ->selectRaw('conteudos.*,users.nome_completo')
                                    ->get();

        }
*/
        $conteudos = [];
        if ($curso == null) {
            return redirect('gestao/cursos');
        }

        if ($request->aula != null) {
            if ($curso->aulas->filter(function ($item) {
                return $item->id == \Request::get('aula');
            })->first() == null) {
                return redirect()->route('gestao.curso-conteudo', ['idCurso' => $idCurso]);
            }
        }

        if ($curso->data_publicacao != null && $curso->periodo > 0) {
            $curso->data_publicacao = \Carbon\Carbon::parse($curso->data_publicacao);

            $curso->data_expiracao = new \Carbon\Carbon($curso->data_publicacao);
            $curso->data_expiracao->addDays($curso->periodo);

            $curso->periodo_restante = \Carbon\Carbon::now()->diffInDays($curso->data_expiracao);

            if ($curso->data_expiracao->gt(\Carbon\Carbon::now()) == false) {
                $curso->periodo_restante *= -1;
            }
        } else {
            $curso->periodo_restante = 0;
        }

        $curso->matriculados = Matricula::with('user')->where('curso_id', '=', $curso->id)->whereHas('user')->count();

        $curso->vagasRestante = $curso->vagas - $curso->matriculados;

        $categorias = Categoria::all();

        $instituicoes = Instituicao::all();

        $user = Auth::user();
        if (!$user->escola && $user->permissao     != "A" || $user->privilegio_id != 4) {
            $escolas = Escola::all();
        } else {
            $escolas = Escola::where('instituicao_id', Auth::user()->escola->instituicao->id)->get();
        }

        $etapas = Ciclo::all();
        $cicloEtapas = CicloEtapa::all();
        $disciplinas = Disciplina::all();
        $visibilidades = [];

        return view('gestao.curso-conteudo')->with(compact(
            'curso',
            'categorias',
            'conteudos',
            'escolas',
            'etapas',
            'cicloEtapas',
            'disciplinas',
            'instituicoes',
            'visibilidades'
        ));
    }

    public function conteudoCursoLivre($idCurso, Request $request)
    {
        $curso = Curso::with('aulas')
            ->find($idCurso);

        $conteudos = Conteudo::all('id', 'titulo', 'autores', 'tipo');

        if ($curso == null) {
            return redirect()->route('gestao.cursoslivres');
            // return redirect()->back();
        }

        if ($request->aula != null) {
            if ($curso->aulas->filter(function ($item) {
                return $item->id == \Request::get('aula');
            })->first() == null) {
                return redirect()->route('gestao.curso-livre-conteudo', ['idCurso' => $idCurso]);
            }
        }

        if ($curso->data_publicacao != null && $curso->periodo > 0) {
            $curso->data_publicacao = \Carbon\Carbon::parse($curso->data_publicacao);

            $curso->data_expiracao = new \Carbon\Carbon($curso->data_publicacao);
            $curso->data_expiracao->addDays($curso->periodo);

            $curso->periodo_restante = \Carbon\Carbon::now()->diffInDays($curso->data_expiracao);

            if ($curso->data_expiracao->gt(\Carbon\Carbon::now()) == false) {
                $curso->periodo_restante *= -1;
            }
        } else {
            $curso->periodo_restante = 0;
        }

        $categorias = Categoria::all();

        $curso->matriculados = Matricula::with('user')->where('curso_id', '=', $curso->id)->whereHas('user')->count();

        $curso->vagasRestante = $curso->vagas - $curso->matriculados;

        $etapas = Ciclo::all();
        $cicloEtapas = CicloEtapa::all();
        $disciplinas = Disciplina::all();

        return view('gestao.cursolivre.curso-conteudo')->with(compact('curso', 'categorias', 'conteudos', 'etapas', 'cicloEtapas', 'disciplinas'));
    }

    public function postNovaAula($idCurso, Request $request)
    {
        if (strtoupper(Auth::user()->permissao) == "E") {
            $escola = Escola::where('user_id', '=', Auth::user()->id)->first();
        }

        if (!isset($escola)) {
            $escola = Escola::find(1);
        } elseif ($escola == null) {
            $escola = Escola::find(1);
        }

        if ($request->duracao == null) {
            $duracaoMinuto = 0;
        } else {

            $duracao = $request->duracao;

            $duracaoMinuto = ltrim($duracao, '0');
        }

        if ($request->requisito == "aula") {
            if ($request->aula_especifica == null)
                $request->requisito_id = 0;
            else
                $request->requisito_id = $request->aula_especifica;
        } else {
            $request->requisito_id = 0;
        }

        //cadastro de aula
        $newAula = Aula::create([
            'curso_id' => $idCurso,
            'user_id' => \Auth::user()->id,
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'duracao' => $duracaoMinuto,
            'requisito' => $request->requisito,
            'requisito_id' => $request->requisito_id,
            'ordem' => Aula::where('curso_id', '=', $idCurso)->max('ordem') + 1,
        ]);

        $cursoatual = Curso::where([['id', '=', $idCurso]])->first();

        if ($cursoatual->cursos_tipo_id == 2) {
            return Redirect::route('gestao.curso-livre-conteudo', ['idCurso' => $idCurso, 'aula' => $newAula->id]);
        } else {
            return Redirect::route('gestao.curso-conteudo', ['idCurso' => $idCurso, 'aula' => $newAula->id]);
        }
    }

    public function editarAula($idCurso, $idAula)
    {

        if (Aula::where([['id', '=', $idAula], ['curso_id', '=', $idCurso]])->first() != null) {
            $aula = Aula::where([['id', '=', $idAula], ['curso_id', '=', $idCurso]])->first();

            return response()->json(['success' => 'Conteúdo encontrado.', 'aula' => json_encode($aula)]);
            return response()->json(['aula' => $aula]);
        } else {
            return response()->json(['error' => 'Não encontrado']);
        }
    }

    public function postEditarAula($idCurso, Request $request)
    {
        if (Aula::where([['id', '=', $request->idAula], ['curso_id', '=', $idCurso]]) != null) {
            if ($request->duracao == null) {
                $duracaoMinuto = 0;
            } else {

                $duracao = $request->duracao;

                $duracaoMinuto = ltrim($duracao, '0');
            }

            if ($request->requisito == "aula") {
                if ($request->aula_especifica == null)
                    $request->requisito_id = 0;
                else
                    $request->requisito_id = $request->aula_especifica;
            } else {
                $request->requisito_id = 0;
            }

            Aula::where([['id', '=', $request->idAula], ['curso_id', '=', $idCurso]])->update([
                'titulo' => $request->titulo,
                'descricao' => $request->descricao,
                'duracao' => $duracaoMinuto,
                'requisito' => $request->requisito,
                'requisito_id' => $request->requisito_id,
            ]);

            return Redirect::back()->with('message', 'Conteúdo atualizado com sucesso!');
        } else {
            return Redirect::back()->withErrors(['Conteúdo não encontrado!']);
        }
    }

    public function reordenarAula($idCurso, $idAula, $index)
    {
        $aula = Aula::find($idAula);
        $ultimoIndex = $aula->ordem;

        if ($aula != null) {
            Aula::where('id', '=', $idAula)->update([
                'ordem' => $index
            ]);

            if ($index > $ultimoIndex) {
                Aula::where([
                    ['curso_id', '=', $idCurso],
                    ['id', '!=', $idAula],
                    ['ordem', '<=', $index],
                    ['ordem', '>', $ultimoIndex],
                ])
                    ->update([
                        'ordem' => DB::raw('ordem - 1')
                    ]);
            } else {
                Aula::where([
                    ['curso_id', '=', $idCurso],
                    ['id', '!=', $idAula],
                    ['ordem', '>=', $index],
                    ['ordem', '<', $ultimoIndex],
                ])
                    ->update([
                        'ordem' => DB::raw('ordem + 1')
                    ]);
            }

            return response()->json(['success' => 'Conteúdo reordenado com sucesso!' . $ultimoIndex . '-' . $index]);
        } else {
            return response()->json(['error' => 'Conteúdo não encontrado!']);
        }
    }

    /**
     * Atualiza a ordem das aulas no sistema.
     *
     * @param $curso_id ID do curso
     * @param $aula_id ID da aula
     * @param $positions Array com todas as posições a serem atualizadas
     *
     * @return JSON
     */
    public function reordenarAulaV2(Request $request, $curso_id, $aula_id)
    {
        $aula = Aula::find($aula_id);

        if(!$aula) {
            return response()->json(['error' => 'Conteúdo não encontrado!']);
        }

        foreach($request->positions as $aula) {
            $updateClass = Aula::find($aula['aula_id'])->update(['ordem' => $aula['position']]);
        }

        return response()->json(['success' => 'Conteúdo reordenado com sucesso!']);
    }

    public function postDuplicarAula($idCurso, Request $request)
    {
        if (Aula::where([['id', '=', $request->idAula], ['curso_id', '=', $idCurso]])->first() != null) {
            $newAula = Aula::where([['id', '=', $request->idAula], ['curso_id', '=', $idCurso]])->first()->withConteudos()->replicate();

            $newAula->ordem = Aula::where('curso_id', '=', $idCurso)->max('ordem') + 1;

            foreach ($newAula->conteudos as $key => $conteudo) {
                $conteudo = $conteudo->replicate();
                $conteudo->id = $key + 1;
                $conteudo->aula_id = $newAula->id;
                $conteudo->save();
            }

            unset($newAula->conteudos);

            $newAula->save();

            \Session::flash('message', 'Conteúdo duplicado com sucesso!');
            return redirect()->route('gestao.curso-conteudo', ['idCurso' => $idCurso, 'aula' => $request->idAula]);
        } else {
            return Redirect::back()->withErrors(['Conteúdo não encontrado!']);
        }
    }

    public function postExcluirAula($idCurso, Request $request)
    {
        $aula = Aula::where([['id', '=', $request->idAula], ['curso_id', '=', $idCurso]]);
        if ($aula->first() != null) {
            $ordem = $aula->first()->ordem;
            $aula->delete();

            Aula::where([
                ['curso_id', '=', $idCurso],
                ['ordem', '>', $ordem]
            ])
                ->update([
                    'ordem' => DB::raw('ordem - 1')
                ]);

            $conteudosAula = ConteudoAula::where([['aula_id', '=', $request->idAula], ['curso_id', '=', $idCurso]])->get();
            foreach ($conteudosAula as $conteudoAula) {
                Conteudo::where([['id', '=', $conteudoAula->conteudo_id]])->delete();
            }

            ConteudoAula::where([['aula_id', '=', $request->idAula], ['curso_id', '=', $idCurso]])->delete();

            return Redirect::back()->with('message', 'Conteúdo excluido com sucesso!');
        } else {
            return Redirect::back()->withErrors(['Não encontrado!']);
        }
    }

    public function aulaConteudos($idCurso, $idAula)
    {
        if (Aula::where([['id', '=', $idAula], ['curso_id', '=', $idCurso]])->first() != null) {
            $aula = Aula::where([['id', '=', $idAula], ['curso_id', '=', $idCurso]])->first(); //->withConteudos();

            $aula->conteudos = ConteudoAula::join('conteudos', 'conteudo_aula.conteudo_id', '=', 'conteudos.id')
                ->whereHas('conteudo')
                ->where([['curso_id', '=', $idCurso], ['aula_id', '=', $idAula]])
                ->orderBy('conteudo_aula.ordem')
                ->get();

            $aula->conteudos = Conteudo::detalhado($aula->conteudos);

            return response()->json(['success' => 'Conteúdo encontrado.', 'aula' => json_encode($aula, JSON_PRETTY_PRINT)]);
            return response()->json(['aula' => $aula]);

        } else {
            return response()->json(['error' => 'Conteúdo não encontrada!']);
        }
    }

    public function postNovoConteudoCurso($idCurso, Request $request)
    {
        if (Aula::where([['id', '=', $request->idAula], ['curso_id', '=', $idCurso]]) != null) {
            if ($request->descricao == null) {
                $request->descricao = '';
            }

            if ($request->obrigatorio == null) {
                $request->obrigatorio = 0;
            } elseif ($request->obrigatorio == 'on') {
                $request->obrigatorio = 1;
            }

            if ($request->tempo == null) {
                $request->tempo = 0;
            }

            if ($request->apoio == null) {
                //    $request->apoio = '';
            } else {
                $request["apoio"] = strip_tags($request->apoio, '<a>');
            }

            if ($request->fonte == null) {
                //    $request->fonte = '';
            } else {
                $request["fonte"] = strip_tags($request->fonte, '<a>');
            }

            if ($request->autores == null) {
                //    $request->autores = '';
            } else {
                $request["autores"] = strip_tags($request->autores, '<a>');
            }

            switch ($request->tipo) {
                case 1:
                    $conteudo = str_replace('"','\'',$request->conteudo);
                    break;

                case 2:
                    if (isset($request->arquivoAudio)) {
                        $request->arquivo = $request->arquivoAudio;
                        $validator = Validator::make($request->all(), [
                            'arquivoAudio' => 'mimetypes:audio/*|max:350000'
                        ],[
                            'max'=>'Tamanho máximo excedido!','mimetypes' =>'Arquivo de áudio não compatível!'
                        ]);

                        if ($validator->fails()) {
                            return Redirect::back()->withErrors($validator->messages()->first());
                        }
                    } else {
                        $request->conteudo = $request->conteudoAudio;
                    }
                case 3:
                    if (isset($request->arquivoVideo)) {
                        $request->arquivo = $request->arquivoVideo;
                        $validator = Validator::make($request->all(), [
                            'arquivoVideo' => 'mimetypes:video/*|max:500000'
                        ],[
                            'max'=>'Tamanho máximo excedido!','mimetypes' =>'Arquivo de vídeo não compatível!'
                        ]);

                        if ($validator->fails()) {
                            return Redirect::back()->withErrors($validator->messages()->first());
                        }
                    } else {
                        $request->conteudo = $request->conteudoVideo;
                    }
                    break;
                case 4:
                    if (isset($request->arquivoSlide)) {
                        $request->arquivo = $request->arquivoSlide;
                        $validator = Validator::make($request->all(), [
                            'arquivoSlide' => 'mimetypes:application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.slideshow,application/vnd.openxmlformats-officedocument.presentationml.presentation,application/zip|max:150000'
                        ],[
                            'max'=>'Tamanho máximo excedido!','mimetypes' =>'Arquivo de slide não compatível!'
                        ]);

                        if ($validator->fails()) {
                            if (
                                $request->arquivoSlide->getMimeType() == 'application/zip' && ($request->arquivoSlide->getClientMimeType() == 'application/vnd.openxmlformats-officedocument.presentationml.presentation')
                            ) {
                                break;
                            } else {
                                return Redirect::back()->withErrors($validator->messages()->first());
                            }
                        }
                    } else {
                        $request->conteudo = $request->conteudoSlide;
                    }
                    break;
                case 5:
                    $conteudo = $request->conteudoTransmissao;
                    break;
                case 6:
                    if (isset($request->arquivoArquivo))
                        $request->arquivo = $request->arquivoArquivo;
                        $validator = Validator::make($request->all(), [
                            'arquivoArquivo' => 'max:350000'
                        ],[
                            'max'=>'Tamanho máximo excedido!'
                        ]);

                        if ($validator->fails()) {
                            return Redirect::back()->withErrors($validator->messages()->first());
                        }
                    else
                        $request->conteudo = $request->conteudoArquivo;
                    break;
                case 7:
                    $request->pergunta = str_replace('"','\'',$request->conteudoDissertativa);
                    $request->dica = $request->conteudoDissertativaDica;
                    $request->explicacao = $request->conteudoDissertativaExplicacao;
                    $request->questao_id = $request->questaoTipo7Id;
                    $conteudo = json_encode(['pergunta' => $request->conteudoQuizPergunta, 'dica' => $request->conteudoQuizDica, 'explicacao' => $request->conteudoQuizExplicacao]);
                    break;
                case 8:
                    $request->correta = $request->conteudoQuizAlternativaCorreta;
                    $request->pergunta = str_replace('"','\'',$request->conteudoQuizPergunta);
                    $request->alternativas = [str_replace('"','\'',$request->conteudoQuizAlternativa1), str_replace('"','\'',$request->conteudoQuizAlternativa2), str_replace('"','\'',$request->conteudoQuizAlternativa3)];
                    $request->dica = $request->conteudoQuizDica;
                    $request->explicacao = $request->conteudoQuizExplicacao;
                    $request->questao_id = $request->questaoTipo8Id;
                    $conteudo = json_encode(['pergunta' => $request->conteudoQuizPergunta, 'alternativas' => [$request->conteudoQuizAlternativa1, $request->conteudoQuizAlternativa2, $request->conteudoQuizAlternativa3], 'correta' => $request->conteudoQuizAlternativaCorreta, 'dica' => $request->conteudoQuizDica, 'explicacao' => $request->conteudoQuizExplicacao]);
                    break;
                case 9:
                    $request->questao_id = $request->questaoTipo9Id;
                    $conteudo = $request->conteudoProva;
                    break;
                case 10:
                    $conteudo = str_replace('"','\'',$request->conteudoEntregavel);
                    break;
                case 11:
                    if (isset($request->arquivoApostila))
                        $request->arquivo = $request->arquivoApostila;
                    else
                        $request->conteudo = $request->conteudoApostila;
                    break;
                case 12:

                    $conteudo = $request->palavra;
                    break;

                case 15:
                    if (isset($request->arquivoPDF)) {
                        $request->arquivo = $request->arquivoPDF;
                        $validator = Validator::make($request->all(), [
                            'arquivoPDF' => 'mimetypes:application/pdf|max:150000'
                        ],[
                            'max'=>'Tamanho máximo excedido!','mimetypes' =>'Arquivo de PDF não compatível!'
                        ]);

                        if ($validator->fails()) {
                            return Redirect::back()->withErrors($validator->messages()->first());
                        }
                    } else {
                        $request->conteudo = $request->conteudoPDF;
                    }
                    break;

                case 13:

                    $conteudo = [];

                    foreach ($request->afirmacao as $af) {
                        $conteudo[] = [
                            "afirmacao" => $af
                        ];
                    }

                    foreach ($request->verdadeirofalso as $vf) {
                        $conteudo[] = [
                            "verdadeirofalso" => $vf
                        ];
                    }

                    $conteudo = json_encode(["enunciado" =>  $request->enunciado, "afirmacao" => $request->afirmacao, "verdadeira" => $request->verdadeirofalso]);
                    break;

                case 16:

                    $conteudo = [];

                    foreach ($request->cacapalavras as $plv) {
                        $conteudo[] = [
                            'cacapalavras' => $plv
                        ];
                    }

                    $conteudo = json_encode(['cacapalavras' =>  $request->cacapalavras, 'nome' => $request->nome]);
                    break;

                case 17:

                    $conteudo = [];
                    foreach ($request->conteudoQuiz as $cq) {
                        $conteudo[] = [
                            'conteudoQuiz' => $cq
                        ];
                    }

                    foreach ($request->conteudoQuizAlternativaCorreta as $cqc) {
                        $conteudo[] = [
                            'conteudoQuizAlternativaCorreta' => $cqc
                        ];
                    }

                    foreach ($request->conteudoQuizAlternativa as $cqa) {
                        $conteudo[] = [
                            'conteudoQuizAlternativa' => $cqa
                        ];
                    }

                    $conteudo = json_encode([
                        'conteudoQuiz' => $request->conteudoQuiz, 'conteudoQuizAlternativa' => [$request->conteudoQuizAlternativa],
                        'correta' => $request->conteudoQuizAlternativaCorreta
                    ]);
                    break;

                case 19:

                    $myConteudoQuiz = [];

                    foreach ($request->conteudoQuiz as $key => $value) {
                        $myConteudoQuiz1[$key] = [
                            'pergunta' => $value['pergunta'],
                            'palavra1' => $value['palavra1'],
                            'palavra2' => $value['palavra2'],
                            'tipo'     => $value['tipo']
                        ];
                    }

                    array_pop($myConteudoQuiz);

                    //$conteudo = json_encode(['conteudoQuiz' => $request->conteudoQuiz,'conteudoQuizAlternativa' => [ $request->conteudoQuizAlternativa],
                    //'conteudoQuizAlternativa' => [ $request->conteudoQuizAlternativa],'conteudoAlternativaVerdadeira' => $request->conteudoAlternativaVerdadeira]);

                    $conteudo = json_encode($myConteudoQuiz);

                    break;

                case 20:

                    $myConteudoQuiz1 = [];

                    if ($request->hasFile('conteudoQuiz')) {

                        foreach ($request->conteudoQuiz as $conteudoQuiz) {
                            $filename = $conteudoQuiz->getClienteOriginalCliente();
                            $conteudoQuiz->storeAs('uploads/correlacaodeimagens', $filename);
                        }
                    }

                    foreach ($request->conteudoQuiz as $key => $value) {
                        $myConteudoQuiz1[] = [
                            'pergunta' => $value['pergunta'],
                            'image1' => $value['image1'],
                            'image2' => $value['image2'],
                            'tipo'     => $value['tipo']
                        ];
                    }

                    array_pop($myConteudoQuiz1);

                    //$conteudo = json_encode(['conteudoQuiz' => $request->conteudoQuiz,'conteudoQuizAlternativa' => [ $request->conteudoQuizAlternativa],
                    //'conteudoQuizAlternativa' => [ $request->conteudoQuizAlternativa],'conteudoAlternativaVerdadeira' => $request->conteudoAlternativaVerdadeira]);

                    $conteudo = json_encode($myConteudoQuiz1);

                    break;

                case 21:
                    if (isset($request->arquivoApostila))
                        $request->arquivo = $request->arquivoApostila;
                    else
                        $request->conteudo = $request->conteudoApostila;
                    break;

                case 22:
                    if (isset($request->arquivoPDF2)) {
                        $request->arquivo = $request->arquivoPDF2;
                        $validator = Validator::make($request->all(), [
                            'arquivoPDF2' => 'mimetypes:application/pdf|max:150000'
                        ],[
                            'max'=>'Tamanho máximo excedido!','mimetypes' =>'Arquivo de PDF não compatível!'
                        ]);

                        if ($validator->fails()) {
                            return Redirect::back()->withErrors($validator->messages()->first());
                        }
                    } else {
                        $request->conteudo = $request->conteudoPDF2;
                    }
                    break;

                default:
                    $conteudo = $request->conteudo;
                    break;
            }

            if (in_array($request->tipo, [2, 3, 4, 6, 15, 22])) {
                //dd($request->allFiles());
                if (isset($request->arquivo)) {
                    if($request->hasFile('arquivoAudio')){
                        $path = $request->file('arquivoAudio')->store(config('app.frStorage'));
                    }elseif($request->hasFile('arquivoVideo')){
                        $path = $request->file('arquivoVideo')->store(config('app.frStorage'));
                    }elseif($request->hasFile('arquivoSlide')){
                        $path = $request->file('arquivoSlide')->store(config('app.frStorage'));
                    }elseif($request->hasFile('arquivoPDF')){
                        $path = $request->file('arquivoPDF')->store(config('app.frStorage'));
                    }elseif($request->hasFile('arquivoArquivo')){
                        $path = $request->file('arquivoArquivo')->store(config('app.frStorage'));
                    }else{
                        $path = $request->file('arquivo')->store(config('app.frStorage'));
                    }

                    $conteudo = basename($path);

                } else {
                    // $conteudo = $request->conteudo;
                    if($request->tipo == 3 && $request->conteudoVideo != '') {
                      $conteudo = $request->conteudoVideo;
                    }elseif($request->tipo == 15 && $request->conteudoPDF!= ''){
                        $conteudo = $request->conteudoPDF;
                    }else {
                      return redirect()->back()->with("error", "Erro ao anexar arquivo, tente novamente");
                    }
                }
            } else if ($request->tipo == 11) {
                $conteudo = "index.html";
            } else if ($request->tipo == 21) {
                $conteudo = "index.html";
            }

            $novo_conteudo = Conteudo::create([
                'user_id'       => \Auth::user()->id,
                'ciclo_id'      => $request->ciclo_id,
                'cicloetapa_id' => $request->cicloetapa_id,
                'disciplina_id' => $request->disciplina_id,
                'titulo'        => $request->titulo,
                'descricao'     => $request->descricao,
                'tipo'          => $request->tipo,
                'conteudo'      => $conteudo,
                'tempo'         => $request->tempo,
                'apoio'         => $request->apoio,
                'fonte'         => $request->fonte,
                'autores'       => $request->autores,
                'status'        => $request->status
            ]);
/*
            if (in_array($request->tipo, [2, 3, 4, 6, 15])) {
               if ($request->conteudoVideo == '') {
                $novo_conteudo->update([
                    'file_size' => 0
                ]);
               }
            }
*/

            if ($request->tipo == 11) {
                $zipperFile = \Zipper::make($request->arquivo);

                $logFiles = $zipperFile->listFiles();

                if (in_array("index.html", $logFiles) == false || in_array("index.js", $logFiles) == false) {
                    $novo_conteudo->delete();

                    return redirect()->back()->withErrors("Conteúdo do zip inválido, por favor consulte as instruções de upload de apostilas!");
                }

                $zipperFile->extractTo(public_path('uploads') . '/apostilas/' . $novo_conteudo->id . '/');

                if (!\Storage::disk('public_uploads')->has('apostilas/' . $novo_conteudo->id)) {
                    $novo_conteudo->delete();

                    return redirect()->back()->withErrors("Não foi possível extrair o conteúdo do zip, por favor envie sua aplicação novamente!");
                }
            }

            if ($request->tipo == 21) {

                $zipperFile = \Zipper::make($request->arquivoRevista);

                $logFiles = $zipperFile->listFiles();


                if (!Storage::disk('public')->has('livrodigital/' . $novo_conteudo->id . '/')) {
                    Storage::disk('public')->makeDirectory('livrodigital/' . $novo_conteudo->id . '/');
                    $zipperFile->extractTo('storage/livrodigital/' . $novo_conteudo->id . '/');
                }

                if (!\Storage::disk('public')->has('livrodigital/' . $novo_conteudo->id)) {
                    $novo_conteudo->delete();
                    return redirect()->back()->withErrors("Não foi possível extrair o conteúdo do zip, por favor envie seu arquivo novamente!");
                }
            }

            $novoConteudo = ConteudoAula::create([
                'ordem' => ConteudoAula::where([['aula_id', '=', $request->idAula], ['curso_id', '=', $idCurso]])->max('ordem') + 1,
                'curso_id' => $idCurso,
                'aula_id' => $request->idAula,
                'conteudo_id' => $novo_conteudo->id,
                'user_id' => \Auth::user()->id,
                'obrigatorio' => $request->obrigatorio,
            ]);

            if (in_array($request->tipo, [7, 8, 9])) {
                $request->novoConteudo = $novo_conteudo->id;
                ConteudoService::salvarQuestoes($request);
            }

            $cursoatual = Curso::where([['id', '=', $idCurso]])->first();

            if ($cursoatual->cursos_tipo_id == 2) {
                return Redirect::route('gestao.curso-livre-conteudo', ['idCurso' => $idCurso, 'aula' => $request->idAula])->with('message', 'Conteúdo criado com sucesso!');
            } else {
                return Redirect::route('gestao.curso-conteudo', ['idCurso' => $idCurso, 'aula' => $request->idAula])->with('message', 'Conteúdo criado com sucesso!');
            }
            // return Redirect::back()->with('message', 'Conteúdo criado com sucesso!');
        } else {
            return Redirect::back()->withErrors(['Conteúdo não encontrado!']);
        }
    }

    #Seleção de conteudos
    public function postAulaSelecaoConteudos($idCurso, Request $request)
    {
        if (Aula::where([['id', '=', $request->idAulaB], ['curso_id', '=', $idCurso]]) != null) {
            if (!$request->conteudosIds || empty($request->conteudosIds)) {
                return Redirect::back()->withErrors(['Nenhum conteúdo selecionado!']);
            } else {
                foreach ($request->conteudosIds as $conteudoId) {
                    if (
                        ConteudoAula::where([
                            ['curso_id', '=', $idCurso],
                            ['aula_id', '=', $request->idAulaB],
                            ['conteudo_id', '=', $conteudoId],
                            ['user_id', '=', \Auth::user()->id]
                        ])
                        ->first() != null
                    ) {
                        continue;
                    } else {
                        ConteudoAula::create([
                            'ordem' => ConteudoAula::where([['aula_id', '=', $request->idAulaB], ['curso_id', '=', $idCurso]])->max('ordem') + 1,
                            'curso_id' => $idCurso,
                            'aula_id' => $request->idAulaB,
                            'conteudo_id' => $conteudoId,
                            'user_id' => \Auth::user()->id,
                            'obrigatorio' => 1,
                        ]);
                    }
                }
            }

            return Redirect::route('gestao.curso-conteudo', ['idCurso' => $idCurso, 'aula' => $request->idAulaB])->with('message', 'Conteúdo criado com sucesso!');
        } else {
            return Redirect::back()->withErrors(['Conteúdo não encontrado!']);
        }
    }

    #Edita conteudo do curso
    public function editarConteudoCurso($idCurso, $idAula, $idConteudo)
    {
        if (Aula::where([['id', '=', $idAula], ['curso_id', '=', $idCurso]])->first() != null) {
            $cont = Conteudo::with(['conteudo_aula'=>function($q) use($idCurso){ $q->where('curso_id',$idCurso); }])
                ->whereHas('conteudo_aula', function ($query) use ($idCurso, $idAula) {
                    $query->where([['curso_id', '=', $idCurso], ['aula_id', '=', $idAula]]);
                })->where([['id', '=', $idConteudo]])->first();
            if ($cont  != null
            ) {
                $conteudo = $cont;

                if (in_array($conteudo->tipo, [2, 3, 4, 6, 15])) {

                    if (\Storage::disk('local')->has('uploads/conteudo/' . $conteudo->conteudo)) {
                        $conteudo->temArquivo = true;
                    } else {
                        $conteudo->temArquivo = false;
                    }
                }

                if (in_array($conteudo->tipo, [7, 8, 9])) {
                    $questoes = $conteudo->questoes;
                    $conteudo->conteudo = ConteudoService::ajustarQuestoes($questoes);
                    $conteudo->questoes_id = $questoes->pluck('id')->toArray();
                }

                return response()->json(['success' => 'Conteudo encontrado..', 'conteudo' => json_encode($conteudo)]);
            } else {
                return response()->json(['error' => 'Conteúdo não encontrado!']);
            }
        } else {
            return response()->json(['error' => 'Conteúdo não encontrado!']);
        }
    }

    #Salva conteudo
    public function postSalvarConteudoCurso($idCurso, Request $request)
    {
        if (Aula::where([['id', '=', $request->idAula], ['curso_id', '=', $idCurso]])->first() != null) {
            if (Conteudo::where([['id', '=', $request->idConteudo]])->first() != null) {
                $conteudoOriginal = Conteudo::where([['id', '=', $request->idConteudo]])->first();

                if ($request->descricao == null) {
                    $request->descricao = '';
                }

                if ($request->obrigatorio == null) {
                    $request->obrigatorio = 0;
                } elseif ($request->obrigatorio == 'on') {
                    $request->obrigatorio = 1;
                }

                if ($request->tempo == null) {
                    $request->tempo = 0;
                }

                switch ($request->tipo) {
                    case 1:
                        $conteudo = str_replace('"','\'',$request->conteudo);
                        break;

                    case 2:
                        if (isset($request->arquivoAudio)) {
                            $request->arquivo = $request->arquivoAudio;
                            $validator = Validator::make($request->all(), [
                                'arquivoAudio' => 'mimetypes:audio/*|max:350000'
                            ],[
                                'max'=>'Tamanho máximo excedido!','mimetypes' =>'Arquivo de áudio não compatível!'
                            ]);

                            if ($validator->fails()) {
                                return Redirect::back()->withErrors($validator->messages()->first());
                            }
                        } else {
                            $request->conteudo = $request->conteudoAudio;
                        }
                    case 3:
                        if (isset($request->arquivoVideo)) {
                            $request->arquivo = $request->arquivoVideo;
                            $validator = Validator::make($request->all(), [
                                'arquivoVideo' => 'mimetypes:video/*|max:500000'
                            ],[
                                'max'=>'Tamanho máximo excedido!','mimetypes' =>'Arquivo de vídeo não compatível!'
                            ]);

                            if ($validator->fails()) {
                                return Redirect::back()->withErrors($validator->messages()->first());
                            }
                        } else {
                            $request->conteudo = $request->conteudoVideo;
                        }
                        break;
                    case 4:
                        if (isset($request->arquivoSlide)) {
                            $request->arquivo = $request->arquivoSlide;
                            $validator = Validator::make($request->all(), [
                                'arquivoSlide' => 'mimetypes:application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.slideshow,application/vnd.openxmlformats-officedocument.presentationml.presentation,application/zip|max:150000'
                            ],[
                                'max'=>'Tamanho máximo excedido!','mimetypes' =>'Arquivo de slide não compatível!'
                            ]);

                            if ($validator->fails()) {
                                if (
                                    $request->arquivoSlide->getMimeType() == 'application/zip' && ($request->arquivoSlide->getClientMimeType() == 'application/vnd.openxmlformats-officedocument.presentationml.presentation')
                                ) {
                                    break;
                                } else {
                                    return Redirect::back()->withErrors($validator->messages()->first());
                                }
                            }
                        } else {
                            $request->conteudo = $request->conteudoSlide;
                        }
                        break;
                    case 5:
                        $conteudo = $request->conteudoTransmissao;
                        break;
                    case 6:
                        if (isset($request->arquivoArquivo))
                            $request->arquivo = $request->arquivoArquivo;
                        else
                            $request->conteudo = $request->conteudoArquivo;
                        break;
                    case 7:
                        $request->pergunta = str_replace('"','\'',$request->conteudoDissertativa);
                        $request->dica = $request->conteudoDissertativaDica;
                        $request->explicacao = $request->conteudoDissertativaExplicacao;
                        $request->questao_id = $request->questaoTipo7Id;
                        $conteudo = json_encode(['pergunta' => $request->conteudoQuizPergunta, 'alternativas' => [$request->conteudoQuizAlternativa1, $request->conteudoQuizAlternativa2, $request->conteudoQuizAlternativa3], 'correta' => $request->conteudoQuizAlternativaCorreta, 'dica' => $request->conteudoQuizDica, 'explicacao' => $request->conteudoQuizExplicacao]);
                        break;
                    case 8:
                        $request->correta = $request->conteudoQuizAlternativaCorreta;
                        $request->pergunta = str_replace('"','\'',$request->conteudoQuizPergunta);
                        $request->alternativas = [str_replace('"','\'',$request->conteudoQuizAlternativa1), str_replace('"','\'',$request->conteudoQuizAlternativa2), str_replace('"','\'',$request->conteudoQuizAlternativa3)];
                        $request->dica = $request->conteudoQuizDica;
                        $request->explicacao = $request->conteudoQuizExplicacao;
                        $request->questao_id = $request->questaoTipo8Id;
                        $conteudo = json_encode(['pergunta' => $request->conteudoQuizPergunta, 'alternativas' => [$request->conteudoQuizAlternativa1, $request->conteudoQuizAlternativa2, $request->conteudoQuizAlternativa3], 'correta' => $request->conteudoQuizAlternativaCorreta, 'dica' => $request->conteudoQuizDica, 'explicacao' => $request->conteudoQuizExplicacao]);
                        break;
                    case 9:
                        $request->questao_id = $request->questaoTipo9Id;
                        $conteudo = $request->conteudoProva;
                        break;
                    case 10:
                        $conteudo = str_replace('"','\'',$request->conteudoEntregavel);
                        break;
                    case 11:
                        if (isset($request->arquivoApostila))
                            $request->arquivo = $request->arquivoApostila;
                        else
                            $request->conteudo = $request->conteudoApostila;
                        break;
                    case 12:

                        $conteudo = $request->nome;
                        $conteudo = $request->palavra;

                        break;

                    case 15:
                        if (isset($request->arquivoPDF)) {
                            $request->arquivo = $request->arquivoPDF;
                            $validator = Validator::make($request->all(), [
                                'arquivoPDF' => 'mimetypes:application/pdf|max:150000'
                            ],[
                                'max'=>'Tamanho máximo excedido!','mimetypes' =>'Arquivo de PDF não compatível!'
                        ]);

                            if ($validator->fails()) {
                                return Redirect::back()->withErrors($validator->messages()->first());
                            }
                        } else {
                            $request->conteudo = $request->conteudoPDF;
                        }
                        break;

                    case 13:

                        $conteudo = [];

                        foreach ($request->afirmacao as $af) {
                            $conteudo[] = [
                                "afirmacao" => $af
                            ];
                        }

                        foreach ($request->verdadeirofalso as $vf) {
                            $conteudo[] = [
                                "verdadeirofalso" => $vf
                            ];
                        }

                        $conteudo = json_encode(["enunciado" =>  $request->enunciado, "afirmacao" => $request->afirmacao, "verdadeira" => $request->verdadeirofalso]);
                        break;

                    case 16:

                        $conteudo = [];

                        foreach ($request->cacapalavras as $plv) {
                            $conteudo[] = [
                                'cacapalavras' => $plv
                            ];
                        }

                        $conteudo = json_encode(['cacapalavras' =>  $request->cacapalavras, 'nome' => $request->nome]);
                        break;

                    case 17:

                        $conteudo = [];
                        foreach ($request->conteudoQuiz as $cq) {
                            $conteudo[] = [
                                'conteudoQuiz' => $cq
                            ];
                        }

                        foreach ($request->conteudoQuizAlternativaCorreta as $cqc) {
                            $conteudo[] = [
                                'conteudoQuizAlternativaCorreta' => $cqc
                            ];
                        }

                        foreach ($request->conteudoQuizAlternativa as $cqa) {
                            $conteudo[] = [
                                'conteudoQuizAlternativa' => $cqa
                            ];
                        }

                        $conteudo = json_encode([
                            'conteudoQuiz' => $request->conteudoQuiz, 'conteudoQuizAlternativa' => [$request->conteudoQuizAlternativa],
                            'correta' => $request->conteudoQuizAlternativaCorreta
                        ]);
                        break;

                    case 19:

                        $myConteudoQuiz = [];

                        foreach ($request->conteudoQuiz as $key => $value) {
                            $myConteudoQuiz1[$key] = [
                                'pergunta' => $value['pergunta'],
                                'palavra1' => $value['palavra1'],
                                'palavra2' => $value['palavra2'],
                                'tipo'     => $value['tipo']
                            ];
                        }

                        array_pop($myConteudoQuiz);

                        //$conteudo = json_encode(['conteudoQuiz' => $request->conteudoQuiz,'conteudoQuizAlternativa' => [ $request->conteudoQuizAlternativa],
                        //'conteudoQuizAlternativa' => [ $request->conteudoQuizAlternativa],'conteudoAlternativaVerdadeira' => $request->conteudoAlternativaVerdadeira]);

                        $conteudo = json_encode($myConteudoQuiz);

                        break;

                    case 20:

                        $myConteudoQuiz1 = [];

                        if ($request->hasFile('conteudoQuiz')) {

                            foreach ($request->conteudoQuiz as $conteudoQuiz) {
                                $filename = $conteudoQuiz->getClienteOriginalCliente();
                                $conteudoQuiz->storeAs('uploads/correlacaodeimagens', $filename);
                            }
                        }

                        foreach ($request->conteudoQuiz as $key => $value) {
                            $myConteudoQuiz1[] = [
                                'pergunta' => $value['pergunta'],
                                'image1' => $value['image1'],
                                'image2' => $value['image2'],
                                'tipo'     => $value['tipo']
                            ];
                        }


                        array_pop($myConteudoQuiz1);

                        //$conteudo = json_encode(['conteudoQuiz' => $request->conteudoQuiz,'conteudoQuizAlternativa' => [ $request->conteudoQuizAlternativa],
                        //'conteudoQuizAlternativa' => [ $request->conteudoQuizAlternativa],'conteudoAlternativaVerdadeira' => $request->conteudoAlternativaVerdadeira]);

                        $conteudo = json_encode($myConteudoQuiz1);

                        break;

                    case 21:
                        if (isset($request->arquivoApostila))
                            $request->arquivo = $request->arquivoApostila;
                        else
                            $request->conteudo = $request->conteudoApostila;
                        break;

                    case 22:
                        if (isset($request->arquivoPDF2)) {
                            $request->arquivo = $request->arquivoPDF2;
                            $validator = Validator::make($request->all(), [
                                'arquivoPDF2' => 'mimetypes:application/pdf|max:150000'
                            ],[
                                'max'=>'Tamanho máximo excedido!','mimetypes' =>'Arquivo de PDF não compatível!'
                            ]);

                            if ($validator->fails()) {
                                return Redirect::back()->withErrors($validator->messages()->first());
                            }
                        } else {
                            $request->conteudo = $request->conteudoPDF2;
                        }
                        break;

                    default:
                        $conteudo = $request->conteudo;
                        break;
                }

                if (in_array($request->tipo, [2, 3, 4, 6, 15, 22])) {
                    if (isset($request->arquivo)) {
                        if ($conteudoOriginal->conteudo != null) {
                            if (\Storage::disk('local')->has('uploads/conteudos/' . $conteudoOriginal->conteudo)) {
                                \Storage::disk('local')->delete('uploads/conteudos/' . $conteudoOriginal->conteudo);
                            }
                        }

                        $originalName = mb_strtolower($request->arquivo->getClientOriginalName(), 'utf-8');

                        $fileExtension = \File::extension($request->arquivo->getClientOriginalName());
                        $newFileNameArquivo =  md5($request->arquivo->getClientOriginalName() . date("Y-m-d H:i:s") . time()) . '.' . $fileExtension;

                        $pathArquivo = $request->arquivo->storeAs('uploads/conteudos', $newFileNameArquivo, 'local');

                        if (!\Storage::disk('local')->put($pathArquivo, file_get_contents($request->arquivo)))
                        {
                            \Session::flash('error', 'Não foi possível fazer upload de seu conteúdo!');
                        } else {
                            $conteudo = $newFileNameArquivo;
                        }
                    } else {
                        if ($request->conteudo != "" && $request->conteudo != null)
                            $conteudo = $request->conteudo;
                        else
                            $conteudo = $conteudoOriginal->conteudo;
                    }
                } else if ($request->tipo == 11) {
                    $conteudo = "index.html";
                } else if ($request->tipo == 21) {
                    $conteudo = "index.html";
                }

                $conteudoOriginal->update([
                    'ciclo_id'      => $request->ciclo_id,
                    'cicloetapa_id' => $request->cicloetapa_id,
                    'disciplina_id' => $request->disciplina_id,
                    'titulo'        => $request->titulo,
                    'descricao'     => $request->descricao,
                    'conteudo'      => $conteudo,
                    'obrigatorio'   => $request->obrigatorio,
                    'tempo'         => $request->tempo,
                    'apoio'         => $request->apoio,
                    'fonte'         => $request->fonte,
                    'autores'       => $request->autores,
                ]);

                if (in_array($request->tipo, [7, 8, 9])) {
                    ConteudoService::salvarQuestoes($request);
                }

                if ($request->tipo == 11) {
                    $tem_arquivo = isset($request->arquivoApostila) || isset($request->conteudoApostila);

                    $tem_conteudo = isset($request->conteudoApostila) ? ($request->conteudoApostila != null && $request->conteudoApostila != '') : false;

                    if ($tem_arquivo && $tem_conteudo) {
                        $zipperFile = \Zipper::make($request->arquivo);

                        $logFiles = $zipperFile->listFiles();

                        if (in_array("index.html", $logFiles) == false || in_array("index.js", $logFiles) == false) {
                            $novo_conteudo->delete();

                            return redirect()->back()->withErrors("Conteúdo do zip inválido, por favor consulte as instruções de upload de apostilas!");
                        }

                        $zipperFile->extractTo(public_path('uploads') . '/apostilas/' . $novo_conteudo->id . '/');

                        if (!\Storage::disk('public_uploads')->has('apostilas/' . $novo_conteudo->id)) {
                            $novo_conteudo->delete();

                            return redirect()->back()->withErrors("Não foi possível extrair o conteúdo do zip, por favor envie sua aplicação novamente!");
                        }
                    }
                }

                if ($request->tipo == 21) {

                    $tem_arquivo = isset($request->arquivoRevista);


                    if ($tem_arquivo) {
                        $zipperFile = \Zipper::make($request->arquivoRevista);

                        if (Storage::disk('public')->has('livrodigital/' . $request->idConteudo . '/')) {
                            Storage::disk('public')->deleteDirectory('livrodigital/' . $request->idConteudo . '/');
                            Storage::disk('public')->makeDirectory('livrodigital/' . $request->idConteudo . '/');
                            $zipperFile->extractTo('storage/livrodigital/' . $request->idConteudo . '/');
                        }

                        if (!\Storage::disk('public')->has('livrodigital/' . $request->idConteudo . '/')) {
                            return redirect()->back()->withErrors("Não foi possível extrair o conteúdo do zip, por favor envie sua aplicação novamente!");
                        }
                    }
                }

                $cursoatual = Curso::where([['id', '=', $idCurso]])->first();

                if ($cursoatual->cursos_tipo_id == 2) {
                    return Redirect::route('gestao.curso-livre-conteudo', ['idCurso' => $idCurso, 'aula' => $request->idAula])->with('message', 'Conteúdo atualizado com sucesso!');
                } else {
                    return Redirect::route('gestao.curso-conteudo', ['idCurso' => $idCurso, 'aula' => $request->idAula])->with('message', 'Conteúdo atualizado com sucesso!');
                }
            } else {
                return response()->json(['error' => 'Conteúdo não encontrado!']);
            }
        } else {
            return response()->json(['error' => 'Conteúdo não encontrado!']);
        }

        return Redirect::back()->with('message', 'Conteúdo atualizado com sucesso!');
    }

    public function reordenarConteudo($idCurso, $idAula, $idConteudo, $index)
    {
        $conteudoAula = ConteudoAula::where([
            ['conteudo_id', '=', $idConteudo],
            ['curso_id', '=', $idCurso],
            ['aula_id', '=', $idAula]
        ])
            ->first();

        if ($conteudoAula != null) {
            $ordemAtual = $conteudoAula->ordem;

            ConteudoAula::find($conteudoAula->id)->update([
                'ordem' => $index
            ]);

            if ($index > $ordemAtual) {
                ConteudoAula::where([
                    ['curso_id', '=', $idCurso],
                    ['aula_id', '=', $idAula],
                    ['id', '!=', $conteudoAula->id],
                    ['ordem', '<=', $index],
                    ['ordem', '>', $ordemAtual],
                ])
                    ->update([
                        'ordem' => DB::raw('ordem - 1')
                    ]);
            } else {
                ConteudoAula::where([
                    ['curso_id', '=', $idCurso],
                    ['aula_id', '=', $idAula],
                    ['id', '!=', $conteudoAula->id],
                    ['ordem', '>=', $index],
                    ['ordem', '<', $ordemAtual],
                ])
                    ->update([
                        'ordem' => DB::raw('ordem + 1')
                    ]);
            }

            return response()->json(['success' => 'Conteudo reordenado com sucesso!' . $ordemAtual . '-' . $index]);
        } else {
            return response()->json(['error' => 'Conteudo não encontrado!']);
        }
    }

    /**
     * Atualiza a ordem dos conteudos no sistema.
     *
     * @param $curso_id ID do curso
     * @param $aula_id ID da aula
     * @param $conteudo_id ID do conteudo
     * @param $positions Array com todas as posições a serem atualizadas
     *
     * @return JSON
     */
    public function reordenarConteudoV2(Request $request, $curso_id, $aula_id, $conteudo_id)
    {
        $conteudo = ConteudoAula::where(['conteudo_id' => $conteudo_id])->first();

        if(!$conteudo) {
            return response()->json(['error' => 'Conteúdo não encontrado!']);
        }

        foreach($request->positions as $conteudo) {
            $updateClass = ConteudoAula::where(['conteudo_id' => $conteudo['conteudo_id']])->update(['ordem' => $conteudo['position']]);
        }

        return response()->json(['success' => 'Conteúdo reordenado com sucesso!']);
    }

    public function postDuplicarConteudoCurso($idCurso, Request $request)
    {
        $exists_conteudo = Conteudo::where([['id', '=', $request->idConteudo]])->exists();

        $exists_conteudo_aula = ConteudoAula::where([['conteudo_id', '=', $request->idConteudo], ['curso_id', '=', $idCurso], ['aula_id', '=', $request->idAula]])->exists();

        if ($exists_conteudo && $exists_conteudo_aula) {
            $new_conteudo = Conteudo::where([['id', '=', $request->idConteudo]])->first()->replicate();

            $new_conteudo->save();

            $new_conteudo_aula = ConteudoAula::where([['conteudo_id', '=', $request->idConteudo], ['curso_id', '=', $idCurso], ['aula_id', '=', $request->idAula]])->first()->replicate();
            $new_conteudo_aula->ordem = ConteudoAula::where([
                ['aula_id', '=', $request->idAula],
                ['curso_id', '=', $idCurso]
            ])
                ->max('ordem') + 1;
            $new_conteudo_aula->conteudo_id = $new_conteudo->id;

            // $newConteudo->id = Conteudo::where([['aula_id', '=', $request->idAula], ['curso_id', '=', $idCurso]])->max('id') + 1;

            //Duplicar arquivo do conteudo se aplicavel
            // if($request->tipo == 2 || $request->tipo == 3 || $request->tipo == 4 || $request->tipo == 6)
            // {
            //     $file = \Storage::disk('local')->get('uploads/cursos/' . $idCurso . '/arquivos/' . $newConteudo->conteudo);

            //     $originalName = mb_strtolower( $file->getClientOriginalName(), 'utf-8' );

            //     $fileExtension = \File::extension($file->getClientOriginalName());
            //     $newFileNameArquivo =  md5( $file->getClientOriginalName() . date("Y-m-d H:i:s") . time() ) . '.' . $fileExtension;

            //     $pathArquivo = $file->copy('uploads/cursos/' . $idCurso . '/arquivos', $newFileNameArquivo, 'local');

            //     if(!\Storage::disk('local')->copy($pathArquivo, file_get_contents($request->arquivo)))
            //     {
            //         \Session::flash('error', 'Não foi possível duplicar seu conteúdo!');
            //     }
            //     else
            //     {
            //         $conteudo = $newFileNameArquivo;
            //     }
            // }

            $new_conteudo_aula->save();

            \Session::flash('message', 'Conteúdo duplicado com sucesso!');

            $cursoatual = Curso::where([['id', '=', $idCurso]])->first();

            if ($cursoatual->cursos_tipo_id == 2) {
                return redirect()->route('gestao.curso-livre-conteudo', ['idCurso' => $idCurso, 'aula' => $request->idAula]);
            } else {
                return redirect()->route('gestao.curso-conteudo', ['idCurso' => $idCurso, 'aula' => $request->idAula]);
            }

        } else {
            return Redirect::back()->withErrors(['Conteúdo não encontrado!']);
        }
    }

    public function postExcluirConteudoCurso($idCurso, Request $request)
    {
        if (ConteudoAula::where([['conteudo_id', '=', $request->idConteudo], ['curso_id', '=', $idCurso], ['aula_id', '=', $request->idAula]])->first() != null) {
            $conteudo = ConteudoAula::where([['conteudo_id', '=', $request->idConteudo], ['curso_id', '=', $idCurso], ['aula_id', '=', $request->idAula]])->first();

            // if($conteudo->tipo == 2 || $conteudo->tipo == 3 || $conteudo->tipo == 4 || $conteudo->tipo == 6)
            // {
            //     if($conteudo->conteudo != null)
            //     {
            //         if(\Storage::disk('local')->has('uploads/cursos/' . $idCurso . '/arquivos/' . $conteudo->conteudo))
            //         {
            //             \Storage::disk('local')->delete('uploads/cursos/' . $idCurso . '/arquivos/' . $conteudo->conteudo);
            //         }
            //     }
            // }

            $conteudoAula = ConteudoAula::where([
                ['conteudo_id', '=', $request->idConteudo],
                ['curso_id', '=', $idCurso],
                ['aula_id', '=', $request->idAula]
            ]);
            $ordem = $conteudoAula->first()->ordem;
            $conteudoAula->delete();

            // Atualiza a ordem dos conteudos
            ConteudoAula::where([
                ['curso_id', '=', $idCurso],
                ['aula_id', '=', $request->idAula],
                ['ordem', '>', $ordem]
            ])
                ->update([
                    'ordem' => DB::raw('ordem - 1')
                ]);

            \Session::flash('success', 'Conteúdo excluido com sucesso!');

            $cursoatual = Curso::where([['id', '=', $idCurso]])->first();

            if ($cursoatual->cursos_tipo_id == 2) {
                return redirect()->route('gestao.curso-livre-conteudo', ['idCurso' => $idCurso, 'aula' => $request->idAula]);
            } else {
                return redirect()->route('gestao.curso-conteudo', ['idCurso' => $idCurso, 'aula' => $request->idAula]);
            }

        } else {
            return Redirect::back()->withErrors(['Conteúdo não encontrado!']);
        }
    }

    public function rankingProfessores()
    {
        $userLogged = Auth::user(); //PEGA USUARIO LOGADO

        /* RANKING GERAL */
        $professoresGeral = DB::table('users')
            ->leftJoin('avaliacoes_instrutor', 'users.id', '=', 'avaliacoes_instrutor.instrutor_id')
            ->select(DB::raw('users.id as id, users.name as nome, sum(avaliacoes_instrutor.avaliacao) as pontos'))
            ->where('users.permissao', '=', 'P')
            ->groupBy('instrutor_id')
            ->orderBy('pontos', 'DESC')
            ->get();

        $professorIndexGet = $professoresGeral->search(function ($user) {
            return $user->id === Auth::id();
        });

        $professorIndex = ($professorIndexGet !== false) ? $professorIndexGet + 1 : $professorIndexGet;

        /* RANKING DE ESCOLAS */
        $escolas = DB::table('escolas')
            ->leftJoin('avaliacoes_escola', 'escolas.id', '=', 'avaliacoes_escola.escola_id')
            ->select(DB::raw('escolas.id as id, escolas.titulo as titulo, sum(avaliacoes_escola.avaliacao) as pontos'))
            ->groupBy('escola_id')
            ->orderBy('pontos', 'DESC')
            ->get();

        $escolaUserIndexGet = $escolas->search(function ($escola) {
            return $escola->id === Auth::user()->escola_id;
        });

        $escolaIndex = ($escolaUserIndexGet !== false) ? $escolaUserIndexGet + 1 : $escolaUserIndexGet;

        return view('gestao.ranking-professores', compact('userLogged', 'professoresGeral', 'professorIndex', 'escolas', 'escolaIndex'));
    }
}
