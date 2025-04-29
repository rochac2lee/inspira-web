<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use Auth;
use Redirect;
use Session;

use App\Models\User;
use App\Models\Escola;
//use App\Models\Turma;
//use App\Models\AlunoTurma;
//use App\Models\Aplicacao;
use App\Models\Conteudo;
use App\Models\Metrica;
use App\Models\AvaliacaoConteudo;
use App\Models\AvaliacaoInstrutor;
use App\Models\Ciclo;
//use App\Models\CicloEscola;
//use App\Models\InteracaoConteudo;
//use App\Models\ProgressoConteudo;

//use App\Models\LiberacaoAplicacaoEscola;
//use App\Models\CodigoAcessoEscola;

//use App\Models\PostagemEscola;
//use App\Models\ComentarioPostagemEscola;

//use App\Models\PostagemGestaoEscola;
//use App\Models\ComentarioPostagemGestaoEscola;

//use App\Models\CodigoTransmissao;
use App\Models\Endereco;
//use App\Models\EnderecoEscola;
//use App\Models\ResponsavelEscola;
use App\Models\Instituicao;
//use App\Models\TipoEnsino;
//use App\Models\TipoEnsinoEscola;
use App\Models\CicloEtapa;
//use App\Models\CicloEtapaEscola;
use App\Models\Disciplina;
//use App\Models\DisciplinaEscola;
use App\Models\InstituicaoUser;
/*
use App\Generals\Upload\Upload;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Http\Requests\Gestao\ImportarAlunos;
use App\Http\Requests\Gestao\ImportarEscolas;
use App\Http\Requests\Gestao\ImportarProfessores;
*/
class EdulabzzEscolaController extends Controller
{
    //
    // Mural de gestao da escola;...

    public function muralGestaoEscola($escola_id)
    {
        if (Input::get('qt') == null) {
            $amount = 10;
        } else {
            $amount = Input::get('qt');
        }

        if (Escola::find($escola_id) == null) {
            return Redirect::back()->withErrors(['Escola não encontrada!']);
        } else {
            $escola = Escola::with('postagens_gestao', 'postagens_gestao_comentarios')->find($escola_id);

            if (strpos(Auth::user()->permissao, "G") === false && strpos(Auth::user()->permissao, "Z") === false && $escola->user_id != Auth::user()->id && Auth::user()->escola_id != $escola_id) {
                Session::flash('error', 'Você não faz parte desta escola!');

                return redirect('catalogo');
            }

            $usuarios = User::where([['escola_id', '=', $escola_id], ['permissao', '!=', 'A']])->paginate($amount);

            return view('gestao.gestao-escola-mural')->with(compact('escola', 'usuarios', 'amount'));
        }
    }

    public function create()
    {
        // $users = User::all('privilegio_id', '!=', 4);
        $users = User::where('escola_id',  $instituicao)
            ->orwhere([['privilegio_id', 5], ['privilegio_id', 2], ['privilegio_id', 1]])
            ->orwhere([['permissao', "E"], ['permissao', "G"], ['permissao', "Z"]])
            ->get();
        return view('gestao.escola.criar-escola')->with(compact('users'));
    }

    public function postarMuralGestaoEscola($escola_id, Request $request)
    {
        if (Escola::find($escola_id) == null) {
            Redirect::back()->withErrors(['Escola não encontrada!']);
        } else {

            $request->validate([
                "conteudo" => "required"
            ]);

            $postagem = PostagemGestaoEscola::create([
                'escola_id' => $escola_id,
                'user_id'  => Auth::user()->id,
                'conteudo' => $request->conteudo
            ]);

            if ($request->arquivo != null) {
                $originalName = mb_strtolower($request->arquivo->getClientOriginalName(), 'utf-8');

                $fileExtension = \File::extension($request->arquivo->getClientOriginalName());
                $newFileNameArquivo = md5($request->arquivo->getClientOriginalName() . date("Y-m-d H:i:s") . time()) . '.' . $fileExtension;

                $pathArquivo = $request->arquivo->storeAs('uploads/escolas/' . $escola_id . '/arquivos', $newFileNameArquivo, 'local');

                if (!\Storage::disk('local')->put($pathArquivo, file_get_contents($request->arquivo))) {
                    \Session::flash('error', 'Não foi possível fazer upload de seu anexo!');
                } else {
                    $postagem->update([
                        'arquivo' => $newFileNameArquivo
                    ]);
                }
            }

            return Redirect::back()->with('message', 'Postagem enviada com sucesso!');
        }
    }

    public function postExcluirPostagemGestao($escola_id, $idPostagem)
    {
        if (PostagemGestaoEscola::find($idPostagem) != null) {
            if (\Storage::disk('local')->has('uploads/escolas/' . $escola_id . '/arquivos/' . PostagemGestaoEscola::find($idPostagem)->arquivo)) {
                \Storage::disk('local')->delete('uploads/escolas/' . $escola_id . '/arquivos/' . PostagemGestaoEscola::find($idPostagem)->arquivo);
            }

            PostagemGestaoEscola::find($idPostagem)->delete();

            return Redirect::back()->with('message', 'Postagem excluída com sucesso!');
        } else {
            return Redirect::back()->withErrors(['Postagem não encontrada!']);
        }
    }

    public function postEnviarComentarioPostagemGestao($escola_id, $idPostagem, Request $request)
    {
        if (Escola::find($escola_id) == null) {
            Redirect::back()->withErrors(['Escola não encontrada!']);
        } else if (PostagemGestaoEscola::find($idPostagem) == null) {
            Redirect::back()->withErrors(['Postagem não encontrada!']);
        } else {

            $request->validate([
                "conteudo" => "required"
            ]);

            $comentario = ComentarioPostagemGestaoEscola::create([
                'postagem_id' => $idPostagem,
                'user_id'     => Auth::user()->id,
                'conteudo'    => $request->conteudo
            ]);

            return Redirect::back()->with('message', 'Comentário enviado com sucesso!');
        }
    }

    public function postExcluirComentarioPostagemGestao($escola_id, $idPostagem, $idComentario)
    {
        $comentarioPostagemGestaoEscola = ComentarioPostagemGestaoEscola::where('postagem_id', $idPostagem);

        if ($comentarioPostagemGestaoEscola == null) {
            return Redirect::back()->withErrors(['Comentário não encontrado!']);
        } else {
            $comentarioPostagemGestaoEscola->delete();
            return Redirect::back()->with('message', 'Comentário excluído com sucesso!');
        }
    }

    public function getArquivoGestao($escola_id, $postagem_id)
    {
        if (Escola::find($escola_id) == null) {
            Session::flash('error', 'Escola não encontrada!');

            return redirect('catalogo');
        } else {
            if (PostagemGestaoEscola::find($postagem_id) == null) {
                Session::flash('error', 'Postagem não encontrada!');

                return redirect('catalogo');
            } else {
                $postagem = PostagemGestaoEscola::find($postagem_id);

                if (\Storage::disk('local')->has('uploads/escolas/' . $escola_id . '/arquivos/' . $postagem->arquivo)) {
                    return \Storage::disk('local')->download('uploads/escolas/' . $escola_id . '/arquivos/' . $postagem->arquivo);
                } else {
                    Session::flash('error', 'Arquivo não encontrado!');

                    return redirect('catalogo');
                }
            }
        }
    }

    //
    // Mural da escola para alunos
    //

    public function muralEscola($escola_id)
    {
        if (Input::get('qt') == null) {
            $amount = 10;
        } else {
            $amount = Input::get('qt');
        }

        if (Escola::find($escola_id) == null) {
            return Redirect::back()->withErrors(['Escola não encontrada!']);
        } else {
            $escola = Escola::with('postagens_comentarios')->find($escola_id);

            if (strpos(Auth::user()->permissao, "G") === false && strpos(Auth::user()->permissao, "Z") === false && $escola->user_id != Auth::user()->id && Auth::user()->escola_id != $escola_id) {
                Session::flash('error', 'Você não faz parte desta escola!');

                return redirect('catalogo');
            }

            $alunos = User::where('escola_id', '=', $escola_id)->paginate($amount);

            $aplicacoes = Aplicacao::all();
            $conteudos = Conteudo::all();

            $transmissoes = CodigoTransmissao::with('aplicacao', 'conteudo')->where([['user_id', '=', $escola->user_id]])->get();

            return view('gestao.escola-mural')->with(compact('aplicacoes', 'conteudos', 'transmissoes', 'escola', 'alunos', 'amount'));
        }
    }

    public function postarMuralEscola($escola_id, Request $request)
    {
        if (Escola::find($escola_id) == null) {
            Redirect::back()->withErrors(['Escola não encontrada!']);
        } else {

            $request->validate([
                "conteudo" => "required"
            ]);

            $postagem = PostagemEscola::create([
                'escola_id' => $escola_id,
                'user_id'  => Auth::user()->id,
                'conteudo' => $request->conteudo
            ]);

            if ($request->arquivo != null) {
                $originalName = mb_strtolower($request->arquivo->getClientOriginalName(), 'utf-8');

                $fileExtension = \File::extension($request->arquivo->getClientOriginalName());
                $newFileNameArquivo = md5($request->arquivo->getClientOriginalName() . date("Y-m-d H:i:s") . time()) . '.' . $fileExtension;

                $pathArquivo = $request->arquivo->storeAs('uploads/escolas/' . $escola_id . '/arquivos', $newFileNameArquivo, 'local');

                if (!\Storage::disk('local')->put($pathArquivo, file_get_contents($request->arquivo))) {
                    \Session::flash('error', 'Não foi possível fazer upload de seu anexo!');
                } else {
                    $postagem->update([
                        'arquivo' => $newFileNameArquivo
                    ]);
                }
            }

            return Redirect::back()->with('message', 'Postagem enviada com sucesso!');
        }
    }

    public function postExcluirPostagem($escola_id, $idPostagem)
    {
        if (PostagemEscola::find($idPostagem) != null) {
            if (\Storage::disk('local')->has('uploads/escolas/' . $escola_id . '/arquivos/' . PostagemEscola::find($idPostagem)->arquivo)) {
                \Storage::disk('local')->delete('uploads/escolas/' . $escola_id . '/arquivos/' . PostagemEscola::find($idPostagem)->arquivo);
            }

            PostagemEscola::find($idPostagem)->delete();

            return Redirect::back()->with('message', 'Postagem excluída com sucesso!');
        } else {
            return Redirect::back()->withErrors(['Postagem não encontrada!']);
        }
    }

    public function postEnviarComentarioPostagem($escola_id, $idPostagem, Request $request)
    {
        if (Escola::find($escola_id) == null) {
            Redirect::back()->withErrors(['Escola não encontrada!']);
        } else if (PostagemEscola::find($idPostagem) == null) {
            Redirect::back()->withErrors(['Postagem não encontrada!']);
        } else {

            $request->validate([
                "conteudo" => "required"
            ]);

            $comentario = ComentarioPostagemEscola::create([
                'postagem_id' => $idPostagem,
                'user_id'     => Auth::user()->id,
                'conteudo'    => $request->conteudo
            ]);

            return Redirect::back()->with('message', 'Comentário enviado com sucesso!');
        }
    }

    public function postExcluirComentarioPostagem($escola_id, $idPostagem, $idComentario)
    {
        $comentarioPostagemEscola = ComentarioPostagemEscola::where('postagem_id', $idPostagem);

        if ($comentarioPostagemEscola == null) {
            return Redirect::back()->withErrors(['Comentário não encontrado!']);
        } else {
            $comentarioPostagemEscola->delete();
            return Redirect::back()->with('message', 'Comentário excluído com sucesso!');
        }
    }

    public function getArquivo($escola_id, $postagem_id)
    {
        if (Escola::find($escola_id) == null) {
            Session::flash('error', 'Escola não encontrada!');

            return redirect('catalogo');
        } else {
            if (PostagemEscola::find($postagem_id) == null) {
                Session::flash('error', 'Postagem não encontrada!');

                return redirect('catalogo');
            } else {
                $postagem = PostagemEscola::find($postagem_id);

                if (\Storage::disk('local')->has('uploads/escolas/' . $escola_id . '/arquivos/' . $postagem->arquivo)) {
                    return \Storage::disk('local')->download('uploads/escolas/' . $escola_id . '/arquivos/' . $postagem->arquivo);
                } else {
                    Session::flash('error', 'Arquivo não encontrado!');

                    return redirect('catalogo');
                }
            }
        }
    }

    public function modoPostagem($escola_id, Request $request)
    {
        if (Escola::find($escola_id) == null) {
            return response()->json(['error' => 'Escola não encontrada!']);
        } else {
            if ($request->postagem_aberta === "true" || $request->postagem_aberta === true) {
                Escola::find($escola_id)->update([
                    'postagem_aberta' => 1
                ]);
            } else {
                Escola::find($escola_id)->update([
                    'postagem_aberta' => 0
                ]);
            }

            return response()->json(['success' => 'Modo de postagem da escola modificado com sucesso!']);
        }
    }

    // Gestao de escolas

    public function escolas()
    {
        #Verifica se existe alguma escola
        $existEscola = Escola::all()->first();

        #Se não existir nenhuma escola o usuario é direcionado para a tela de criação de escola
        if (!$existEscola) {
            $users = User::where('escola_id', '!=', 0)
                ->orwhere([['privilegio_id', 5], ['privilegio_id', 2], ['privilegio_id', 1]])
                ->orwhere([['permissao', "E"], ['permissao', "G"], ['permissao', "Z"]])
                ->get();

            $tipoEnsino = TipoEnsino::where('status_id', '!=', 0)->get();

            return view('gestao.escola.criar-escola')->with(compact('users', 'tipoEnsino'));
        }

        #Verifica se o usuário tem alguma escola relacionada
        $instituicaoUser = Auth::user()->escola;

        $instituicao = InstituicaoUser::where('user_id', Auth::user()->id)->first()->instituicao_id;

        //variaveis
        $pesquisa = Input::get('pesquisa');
        $qt = Input::get('qt');
        $ordem = Input::get('ordem');
        $categoria = Input::get('categoria');

        if ($qt == null)
            $amount = 20;
        else
            $amount = $qt;

        if ($ordem == null)
            $ordem = "recentes";
        else
            $ordem = $ordem;

        if ($categoria == null)
            $categoria = "";

        else
            $categoria = $categoria;

        if ($categoria != null) {
            if (Categoria::where('titulo', '=', $categoria)->first() != null) {
                $categoria = Categoria::where('titulo', '=', $categoria)->first()->id;
            }
        }

        $escolas = Escola::query();

        $escolas = $escolas->where('escolas.status_id', 1);

        $escolas = Escola::permissionamentoEscolas($escolas, $pesquisa);

        $instituicao = InstituicaoUser::where('user_id', Auth::user()->id)->first();

        $tipoEnsino = TipoEnsino::where('status_id', '!=', 0)->get();

        if(Auth::user()->permissao == "Z" || Auth::user()->privilegio_id == 1){
            $users = User::where([['status_id', '!=', 2], ['privilegio_id', '!=', 4], ['privilegio_id', '!=', 3]])
                ->where([['privilegio_id', '!=', 4], ['privilegio_id', '!=', 3]])
                ->get();

        } else{
            $users = User::where([['instituicao_users.instituicao_id',  $instituicao->instituicao_id], ['users.status_id', '!=', 2]])
                ->where([['privilegio_id', '!=', 4], ['privilegio_id', '!=', 3]])
                ->join('instituicao_users', 'users.id', 'instituicao_users.user_id')
                ->select('users.*')
                ->get();
        }

        $ciclos = Ciclo::all();

        $ciclo_etapas = CicloEtapa::all();

        $disciplinas = Disciplina::all();

        $instituicoes = Instituicao::all();

        $escolas = $escolas->groupBy('escolas.id')->paginate($amount);

        return view('gestao.crm.escolas')->with(compact(
            'escolas',
            'amount',
            'ordem',
            'users',
            'tipoEnsino',
            'ciclo_etapas',
            'disciplinas',
            'ciclos',
            'instituicoes'
        ));
    }

    public function criarEscola()
    {
        return view('criar-escola');
    }

    public function postCriarEscola(Request $request)
    {
        if ($request->descricao == null) {
            $request->descricao = "";
        }

        // try {
        #Se o usuario for super admin recupera a instituicao que ele selecionou
        if($request->instituicao_id && (Auth::user()->permissao == "Z" || Auth::user()->privilegio_id == 1))
            $instituicao_id = $request->instituicao_id;
        // #Se não, recupera a escola que o usuario é relacionado
        else
            $instituicao_id = InstituicaoUser::where('user_id', Auth::user()->id)->first()->instituicao_id;

        // $instituicao_id = $request->instituicao_id;

        #Verifica se existe alguma escola relacionada com o usuário, se não, seleciona a primeira instituicao
        #Verificar essa mudança para a criação de varias instituições
        #Se não existir nenhuma escola cadastrada, usa a primeira instituicao como a padrão
        // if (!$instituicao)
        //     $instituicao = Instituicao::all()->first()->id;
        // else
        //     $instituicao = Auth::user()->escola->instituicao->id;
        if ($request->escola_id) {

            $ciclo_escola = CicloEscola::where('escola_id', $request->escola_id)->get();

            if($ciclo_escola->isEmpty()){
                // foreach($request->tipo_ensino as $request_ciclo){
                //     $ciclo = CicloEscola::create([
                //                'ciclo_id' => $request_ciclo,
                //                'escola_id' => $request->escola_id
                //            ]);
                //    }
            }else{
                foreach($ciclo_escola as $ciclo){
                    $ciclo = CicloEscola::find($ciclo->id)->delete();
                }

                foreach($request->tipo_ensino as $request_ciclo){
                    $ciclo = CicloEscola::create([
                        'ciclo_id' => $request_ciclo,
                        'escola_id' => $request->escola_id
                    ]);
                }
            }

            $ciclo_etapa_escola = CicloEtapaEscola::where('escola_id', $request->escola_id)->get();

            if($ciclo_etapa_escola->isEmpty()){
                // foreach($request->ciclo_etapa as $request_ciclo_etapa){
                //     CicloEtapaEscola::create([
                //         'ciclo_etapas_id' => $request_ciclo_etapa,
                //         'escola_id' => $request->escola_id
                //     ]);
                //    }
            }else{
                foreach($ciclo_etapa_escola as $ciclo_etapa){
                    CicloEtapaEscola::find($ciclo_etapa->id)->delete();
                }

                foreach($request->ciclo_etapa as $request_ciclo_etapa){
                    CicloEtapaEscola::create([
                        'ciclo_etapas_id' => $request_ciclo_etapa,
                        'escola_id' => $request->escola_id
                    ]);
                }
            }

            $disciplina_escola = DisciplinaEscola::where('escola_id', $request->escola_id)->get();

            if($disciplina_escola->isEmpty()){
                // foreach($request->disciplina as $disciplina){
                //     DisciplinaEscola::create([
                //         'disciplina_id' => $disciplina,
                //         'escola_id' => $request->escola_id
                //     ]);
                //    }
            }else{
                foreach($disciplina_escola as $disciplina){
                    DisciplinaEscola::find($disciplina->id)->delete();
                }

                foreach($request->disciplina as $disciplina){
                    DisciplinaEscola::create([
                        'disciplina_id' => $disciplina,
                        'escola_id' => $request->escola_id
                    ]);
                }
            }

            $responsavel_escola = ResponsavelEscola::where('escola_id', $request->escola_id)->get();

            if($responsavel_escola->isEmpty()){
                foreach($request->user_id as $user){
                    ResponsavelEscola::create([
                        'user_id' => $user,
                        'escola_id' => $request->escola_id
                    ]);
                }
            }else{
                foreach($responsavel_escola as $responsavel){
                    ResponsavelEscola::find($responsavel->id)->delete();
                }

                foreach($request->user_id as $user){
                    ResponsavelEscola::create([
                        'user_id' => $user,
                        'escola_id' => $request->escola_id
                    ]);
                }
            }

            $escola = Escola::findorfail($request->escola_id);
            $escola->update([
                'instituicao_id' => $instituicao_id,
                'titulo'         => $request->titulo,
                'descricao'      => $request->descricao,
                'cnpj'           => $request->cnpj,
                'facebook'       => $request->facebook,
                'instagram'      => $request->instagram,
                'youtube'        => $request->youtube,
                'numero_contrato' => $request->numero_contrato,
                'url'            => $request->url,
                'css'            => $request->css
            ]);

            if ($request->capa != null) {
                $fileExtension = \File::extension($request->capa->getClientOriginalName());
                $newFileNameCapa =  md5($request->capa->getClientOriginalName() . date("Y-m-d H:i:s") . time()) . '.' . $fileExtension;

                $pathCapa = $request->capa->storeAs('escolas/capas', $newFileNameCapa, 'public_uploads');

                if (\Storage::disk('public_uploads')->has('plataforma/' . $escola->capa)) {
                    \Storage::disk('public_uploads')->delete('plataforma/' . $escola->capa);
                }

                if (!\Storage::disk('public_uploads')->put($pathCapa, file_get_contents($request->capa))) {
                    \Session::flash('error', 'Não foi possivel enviar a capa.');
                } else {
                    $escola->capa = $newFileNameCapa;

                    $escola->save();
                }
            }

            $endereco = Endereco::where('id', $request->endereco_id);
            if ($endereco)
                $endereco->update([
                    'cep'         => $request->cep,
                    'uf'          => $request->uf,
                    'cidade'      => $request->cidade,
                    'bairro'      => $request->bairro,
                    'logradouro'  => $request->logradouro,
                    'numero'      => $request->numero,
                    'complemento' => $request->complemento,
                ]);



        } else {
            $escola = Escola::firstOrCreate([
                'instituicao_id' => $instituicao_id,
                'user_id'        => Auth::user()->id,
                'titulo'         => $request->titulo,
                'descricao'      => $request->descricao,
                'cnpj'           => $request->cnpj,
                'facebook'       => $request->facebook,
                'instagram'      => $request->instagram,
                'youtube'        => $request->youtube,
                'numero_contrato' => $request->numero_contrato,
                'url'            => $request->url,
                'css'            => $request->css
            ]);

            if ($request->cep) {
                #cadastrando o endereco
                $endereco = Endereco::firstOrCreate([
                    'cep'         => $request->cep,
                    'uf'          => $request->uf,
                    'cidade'      => $request->cidade,
                    'bairro'      => $request->bairro,
                    'logradouro'  => $request->logradouro,
                    'numero'      => $request->numero,
                    'complemento' => $request->complemento,
                ]);

                #Faz o relacionamento de escola com o endereço
                // Método eloquent não esta funcionando, então funcinou com o create
                // $enderecoEscola = $escola->endereco()->sync[$escola->id, $endereco->id]);
                $enderecoEscola = EnderecoEscola::firstOrCreate([
                    'escola_id'   =>  $escola->id,
                    'endereco_id' =>  $endereco->id
                ]);
            }

            #faz relacionamento de escola com tipo de escola
            if ($request->tipo_ensino) {
                foreach ($request->tipo_ensino as $ensino) {
                    $responsavel = CicloEscola::firstOrCreate([
                        'ciclo_id' => $ensino,
                        'escola_id' => $escola->id
                    ]);
                }
            }

            if ($request->user_id) {
                foreach ($request->user_id as $user) {
                    $responsavel = ResponsavelEscola::firstOrCreate([
                        'user_id' => $user,
                        'escola_id' => $escola->id
                    ]);
                }
            }

            #Relaciona ciclo etapa com escola
            if ($request->ciclo_etapa) {
                foreach ($request->ciclo_etapa as $ciclo_etapa) {
                    // $escola->ciclo_etapa()->create([$ciclo_etapa, $escola->id]);
                    $ciclo_etapa_escola = CicloEtapaEscola::firstOrCreate([
                        'ciclo_etapas_id' => $ciclo_etapa,
                        'escola_id' => $escola->id
                    ]);
                }
            }
            #Relaciona disciplina com escola
            if ($request->disciplina) {
                foreach ($request->disciplina as $disciplina) {
                    // $escola->disciplina()->create([$disciplina, $escola->id]);
                    $ciclo_etapa_escola = DisciplinaEscola::firstOrCreate([
                        'disciplina_id' => $disciplina,
                        'escola_id' => $escola->id
                    ]);
                }
            }

            #Funcionalidade aguardando o texto do E-mail para completar
            // if($responsavel){
            //     # Send Email
            //     // $data = [
            //     // 'name' => $request->name,
            //     // 'email' => $request->email,
            //     // 'password' => $senhaAleatoria,
            //     // ];
            //     // $sendEmail = SendEmailService::newUser($data);
            // }

            if ($request->capa != null) {
                $fileExtension = \File::extension($request->capa->getClientOriginalName());
                $newFileNameCapa =  md5($request->capa->getClientOriginalName() . date("Y-m-d H:i:s") . time()) . '.' . $fileExtension;

                $pathCapa = $request->capa->storeAs('escolas/capas', $newFileNameCapa, 'public_uploads');

                if (!\Storage::disk('public_uploads')->put($pathCapa, file_get_contents($request->capa))) {
                    \Session::flash('error', 'Não foi possivel enviar a capa.');
                } else {
                    $escola->capa = $newFileNameCapa;

                    $escola->save();
                }
            }
        }
        if (Auth::user()->trial == 1) {
            Session::flash("message", 'Sua escola foi criada com sucesso, comece a criar seus cursos e um E-mail foi enviado para o responsável!');

            return redirect()->route('gestao.cursos');
        } else {
            if ($request->escola_id) {
                $mensagem = 'Atualizado com sucesso!';
            } else {
                $mensagem = 'Criado com sucesso!';
            }

            if(!$request->internalCall) {
                Session::flash("message", $mensagem);
            }

            return redirect()->route('gestao.escolas');
        }
        // } catch (\Exception $e) {
        //     return redirect()->back()->withErrors("Erro para adicionar o registro!");
        // }
    }

    public function getEscola($escola_id)
    {
        $escola = Escola::find($escola_id);

        $endereco = Endereco::where('endereco_escolas.escola_id', '=', $escola->id)
            ->join('endereco_escolas', 'enderecos.id', '=', 'endereco_escolas.endereco_id')
            ->join('escolas', 'endereco_escolas.escola_id', '=', 'escolas.id')
            ->first();

        $tipo_ensino_escola = TipoEnsinoEscola::where('tipo_ensino_escolas.escola_id', '=', $escola->id)
            ->leftjoin('tipo_ensinos', 'tipo_ensino_escolas.tipo_ensino_id', '=', 'tipo_ensinos.id')
            ->select('tipo_ensino_escolas.tipo_ensino_id')
            ->first();

        $responsavel_escola = ResponsavelEscola::where('responsavel_escolas.escola_id', $escola->id)
            ->leftjoin('users', 'responsavel_escolas.user_id', '=', 'users.id')
            ->get();

        $ciclo_escola = CicloEscola::where('ciclo_escolas.escola_id', $escola->id)
            ->leftjoin('ciclos', 'ciclo_escolas.ciclo_id', '=', 'ciclos.id')
            ->get();

        $ciclo_etapa_escola = CicloEtapaEscola::where('ciclo_etapa_escolas.escola_id', $escola->id)
            ->leftjoin('ciclo_etapas', 'ciclo_etapa_escolas.ciclo_etapas_id', '=', 'ciclo_etapas.id')
            ->get();

        $disciplina_escola = DisciplinaEscola::where('disciplina_escolas.escola_id', $escola->id)
            ->leftjoin('disciplinas', 'disciplina_escolas.disciplina_id', '=', 'disciplinas.id')
            ->get();

        if ($escola != null) {
            return response()->json([
                'success'           => 'Escola encontrada.', 'escola'   => $escola,
                'endereco'          => $endereco,
                'tipo_ensino_escola'=> $tipo_ensino_escola,
                'responsavel_escola'=> $responsavel_escola,
                'ciclo_escola'      => $ciclo_escola,
                'ciclo_etapa_escola'=> $ciclo_etapa_escola,
                'disciplina_escola' => $disciplina_escola
            ]);

        } else {
            return response()->json(['error' => 'Escola não encontrada!']);
        }
    }

    public function postSalvarEscola(Request $request)
    {
        if (Escola::where([['id', '=', $request->escola_id]])->exists() != false) {
            $escola = Escola::where([['id', '=', $request->escola_id]])->first();
            $responsavel = ResponsavelEscola::where([['escola_id', '=', $request->escola_id]])->first();

            $escola->update([
                'titulo' => $request->titulo,
                'descricao' => $request->descricao,
                'limite_alunos' => $request->limite_alunos,
                'nome_completo' => $request->nome_completo,
                'cnpj' => $request->cnpj,
                'nome_responsavel' => $request->nome_responsavel,
                'email_responsavel' => $request->email_responsavel,
                'telefone_responsavel' => $request->telefone_responsavel,
                'responsavel_id' => $request->responsavel_id,
            ]);

            $responsavel->user_id = $request->responsavel_id;
            $responsavel->save();

            if ($request->capa != null) {
                $fileExtension = \File::extension($request->capa->getClientOriginalName());
                $newFileNameCapa =  md5($request->capa->getClientOriginalName() . date("Y-m-d H:i:s") . time()) . '.' . $fileExtension;

                $pathCapa = $request->capa->storeAs('escolas/capas', $newFileNameCapa, 'public_uploads');

                if (\Storage::disk('public_uploads')->has('plataforma/' . $escola->capa)) {
                    \Storage::disk('public_uploads')->delete('plataforma/' . $escola->capa);
                }

                if (!\Storage::disk('public_uploads')->put($pathCapa, file_get_contents($request->capa))) {
                    \Session::flash('error', 'Não foi possivel enviar a capa.');
                } else {
                    $escola->capa = $newFileNameCapa;

                    $escola->save();
                }
            }

            Session::flash("message", 'Escola atualizada com sucesso!');
        } else {
            return redirect()->back()->withErrors("Escola não encontrada!");
        }

        return redirect()->back();
    }

    public function postExcluirEscola(Request $request)
    {
        try {
            if (Escola::find($request->idEscola) != null) {
                // Escola::find($request->idEscola)->delete();
                // Mudando do método delete para atualizar a escola para o status de desativado
                $delEscola = Escola::find($request->idEscola)->update([
                    'status_id' => 2
                ]);
                // Mudando a ordem para só excluir a capa se o curso for desativado
                // Verificar se vai apagar a capa da escola ou não, por enquanto não apaga a imagem
                // if(\Storage::disk('public_uploads')->has('escolas/capas/' . Escola::find($request->idEscola)->capa))
                // {
                //     \Storage::disk('public_uploads')->delete('escolas/capas/' . Escola::find($request->idEscola)->capa);
                // }

                if ($delEscola)
                    Session::flash("message", 'Registro excluído com sucesso!');
                else
                    Session::flash("error", 'Erro para excluír o registro!');
            } else {
                return redirect()->back()->withErrors("Escola não encontrada!");
            }

            return redirect()->back();
        } catch (\Exception $e) {
            // var_dump($e->getMessage());
            return redirect()->back()->withErrors("Erro para excluír o registro!");
        }
    }

    /**
     * Apresenta as telas de importação de arquivos.
     *
     * @param $escola_id ID da escola
     * @return View
     */
    public function importarProfessor(Request $request, $escola_id)
    {
        return view('pages.escola.importar-professor');
    }


    /**
     * Apresenta as telas de importação de arquivos.
     *
     * @param $escola_id ID da escola
     * @return View
     */
    public function importarAluno(Request $request, $escola_id)
    {
        return view('pages.escola.importar-aluno');
    }

    /**
     * Apresenta as telas de importação de arquivos.
     *
     * @param $escola_id ID da escola
     * @return View
     */
    public function importarEscola(Request $request)
    {
        return view('pages.escola.importar-escola');
    }


    /**
     * Processa o CSV recebido.
     *
     * @param $escola_id ID da escola
     * @return Redirect
     */
    public function importarProfessorPost(ImportarProfessores $request, $escola_id)
    {
        $CSVManager = $request;

        $upload = new Upload;
        $upload = $upload->fastUpload([
            'file' => $request->file('csv'),
            'path' => 'temporary_csv'
        ]);

        $proccess = $CSVManager->init($upload->path);

        $result = [
            'headers' => $proccess->getHeaders(),
            'success' => $proccess->getData(),
            'errors'  => $proccess->getErrors()
        ];

        // Salva na session
        $request->session()->put('privilegio', 3);
        $request->session()->put('escola_id', $escola_id);
        $request->session()->put('uploadedHeadersCSV', $result['headers']);
        $request->session()->put('uploadedSuccessCSV', $result['success']);
        $request->session()->put('uploadedErrorCSV'  , $result['errors']);
        $request->session()->flash('exception', $CSVManager->exception);

        return redirect(route('gestao.escola.importar.preview'));
    }

    /**
     * Processa o CSV recebido.
     *
     * @param $escola_id ID da escola
     * @return Redirect
     */
    public function importarAlunoPost(ImportarAlunos $request, $escola_id)
    {
        $CSVManager = $request;

        $upload = new Upload;
        $upload = $upload->fastUpload([
            'file' => $request->file('csv'),
            'path' => 'temporary_csv'
        ]);

        $proccess = $CSVManager->init($upload->path);

        $result = [
            'headers' => $proccess->getHeaders(),
            'success' => $proccess->getData(),
            'errors'  => $proccess->getErrors()
        ];

        // Salva na session
        $request->session()->put('privilegio', 4);
        $request->session()->put('escola_id', $escola_id);
        $request->session()->put('uploadedHeadersCSV', $result['headers']);
        $request->session()->put('uploadedSuccessCSV', $result['success']);
        $request->session()->put('uploadedErrorCSV'  , $result['errors']);
        $request->session()->flash('exception', $CSVManager->exception);

        return redirect(route('gestao.escola.importar.preview', $escola_id));
    }

    /**
     * Processa o CSV recebido.
     *
     * @param $escola_id ID da escola
     * @return Redirect
     */
    public function importarEscolaPost(ImportarEscolas $request)
    {
        $CSVManager = $request;

        $upload = new Upload;
        $upload = $upload->fastUpload([
            'file' => $request->file('csv'),
            'path' => 'temporary_csv'
        ]);

        $proccess = $CSVManager->init($upload->path);

        $result = [
            'headers' => $proccess->getHeaders(),
            'success' => $proccess->getData(),
            'errors'  => $proccess->getErrors()
        ];

        // Salva na session
        $request->session()->put('privilegio', null);
        $request->session()->put('uploadedHeadersCSV', $result['headers']);
        $request->session()->put('uploadedSuccessCSV', $result['success']);
        $request->session()->put('uploadedErrorCSV'  , $result['errors']);
        $request->session()->flash('exception', $CSVManager->exception);

        return redirect(route('gestao.escola.importar.preview'));
    }

    /**
     * Insere no banco de dados os registros do CSV com sucesso.
     *
     * @param $escola_id
     * @return View
     */
    public function importarSuccess(Request $request, $escola_id)
    {
        $result = session('uploadedSuccessCSV');
        $deleteItems = explode(',', $request->_delete_items);

        foreach($deleteItems as $delete) {
            unset($result[$delete]);
        }

        $result = array_values($result); // reorder

        // Se privilegio for null, então é criação de escola
        if(is_null($request->session()->get('privilegio'))) {
            foreach($result as $school) {
                $requestData = $school;
                $requestData['_token'] = $request->only('_token')['_token'];
                $requestData['internalCall'] = 1;
                $requestData['instituicao_id'] = 1;
                $sendRequest = Request::create(route('gestao.escola-nova'), 'POST', $requestData);
                $response    = app()->handle($sendRequest);
            }
        }
        // Fazemos o request interno para criar os usuários
        else if($request->session()->get('privilegio') == 3 || $request->session()->get('privilegio') == 4) {
            foreach($result as $user) {
                $requestData = $request->only(['escola_id', 'privilegio', '_token']);
                $requestData['name']  = $user['nome'];
                $requestData['email'] = $user['email'];
                $requestData['internalCall'] = 1;
                $sendRequest = Request::create(route('gestao.usuarios.novo'), 'POST', $requestData);
                $response    = app()->handle($sendRequest);
            }
        }


        return view('pages.escola.importar-sucesso', [
            'total_cadastros' => count($result)
        ]);
    }

    /**
     * Mostra os dados do CSV pro usuário.
     *
     */
    public function preview(Request $request)
    {
        $uploadedSuccessCSV = session('uploadedSuccessCSV');
        $uploadedErrorCSV   = session('uploadedErrorCSV');
        $headers = session('uploadedHeadersCSV');

        return view('pages.escola.importar-preview', compact('headers', 'uploadedSuccessCSV', 'uploadedErrorCSV'));
    }

    public function painelEscola($escola_id)
    {
        $escola = Escola::find($escola_id);

        $userId = Auth::user()->id;

        if (Input::get('qtAlunos') == null)
            $amountAlunos = 10;
        else
            $amountAlunos = Input::get('qtAlunos');

        if (Input::get('qtProfessores') == null)
            $amountProfessores = 10;
        else
            $amountProfessores = Input::get('qtProfessores');

        if (Input::get('qtTurmas') == null)
            $amountTurmas = 10;
        else
            $amountTurmas = Input::get('qtTurmas');

        $tem_pesquisa = Input::has('pesquisa') ? (Input::get('pesquisa') != null && Input::get('pesquisa') != "") : false;

        //Relatorios gerais

        //
        // Carregar alunos da escola
        //
        $alunos = User::query();
        $alunos->when($tem_pesquisa, function ($query) {
            return $query
                ->where([['escola_id', Input::get('escola_id') ], ['status_id', '!=', 2], ['privilegio_id', 4]])
                ->where('name', 'like', '%' . Input::get('pesquisa') . '%')
                ->orWhere('email', 'like', '%' . Input::get('pesquisa') . '%');
        });

        $alunos = $alunos
            ->with('turmas_aluno')
            ->where([['escola_id', '=', $escola_id], ['status_id', '!=', 2], ['privilegio_id', 4]])
            ->paginate($amountAlunos);

        //
        // Carregar professores da escola
        //
        $professores = User::query();
        $professores->when($tem_pesquisa, function ($query) {
            return $query
                ->where([['escola_id', Input::get('escola_id') ], ['status_id', '!=', 2], ['privilegio_id', 3]])
                ->where('name', 'like', '%' . Input::get('pesquisa') . '%')
                ->orWhere('email', 'like', '%' . Input::get('pesquisa') . '%');
        });

        $professores = $professores
            ->with('turmas_instrutor')
            ->where([['escola_id', '=', $escola_id], ['status_id', '!=', 2], ['privilegio_id', 3]])
            ->paginate($amountProfessores);

        //
        // Carregar gestores da escola
        //
        $gestores = User::query();
        $gestores->when($tem_pesquisa, function ($query) {
            return $query
                ->where([['escola_id', Input::get('escola_id') ], ['status_id', '!=', 2], ['privilegio_id', 2]])
                ->where('name', 'like', '%' . Input::get('pesquisa') . '%')
                ->orWhere('email', 'like', '%' . Input::get('pesquisa') . '%');
        });

        $gestores = $gestores
            ->where([['escola_id', '=', $escola_id], ['status_id', '!=', 2], ['privilegio_id', 2]])
            ->paginate($amountProfessores);

        foreach ($alunos as $aluno) {
            if (ProgressoConteudo::where('user_id', '=', $aluno->id)->count() > 0)
                $aluno->desempenho = ProgressoConteudo::where('user_id', '=', $aluno->id)->avg('progresso');
            else
                $aluno->desempenho = 0;
        }

        foreach ($professores as $professor) {
            if (AvaliacaoInstrutor::where('instrutor_id', '=', $professor->id)->count() > 0) {
                $professor->avaliacao = AvaliacaoInstrutor::where('instrutor_id', '=', $professor->id)->avg('avaliacao');
            } else {
                $professor->avaliacao = 5;
            }
        }

        $turmas = Turma::with('professor')->whereHas('professor', function ($q) use ($escola_id) {
            $q->where('escola_id', '=', $escola_id);
        })->paginate($amountTurmas);

        foreach ($turmas as $turma) {
            $turma->qt_alunos = AlunoTurma::where([['turma_id', '=', $turma->id]])->count();
        }

        $totalAlunos = count($alunos);
        $totalProfessores = count($professores);
        $totalGestores = count($gestores);
        $totalTurmas = count($turmas);

        $mediaAlunosConectados = [0, 0, 0, 0, 0];

        $nivelAprendizado = [0, 0, 0, 0, 0, 0];

        foreach (User::all() as $user) {
            $user->acessos = Metrica::where([['titulo', '=', 'Acesso na plataforma'], ['user_id', '=', $user->id]])
                ->whereHas('user', function ($q) use ($escola_id) {
                    $q->where('escola_id', '=', $escola_id);
                })
                ->count();

            if ($user->acessos >= 31) {
                $mediaAlunosConectados[0]++;
            } elseif ($user->acessos >= 16 && $user->acessos <= 30) {
                $mediaAlunosConectados[1]++;
            } elseif ($user->acessos >= 6 && $user->acessos <= 15) {
                $mediaAlunosConectados[2]++;
            } elseif ($user->acessos >= 1 && $user->acessos <= 5) {
                $mediaAlunosConectados[3]++;
            } else {
                $mediaAlunosConectados[4]++;
            }

            $totalAvaliacoes = AvaliacaoConteudo::where([['user_id', '=', $user->id]])
                ->whereHas('user', function ($q) use ($escola_id) {
                    $q->where('escola_id', '=', $escola_id);
                })
                ->count();

            $totalAvaliacoesPositivas = AvaliacaoConteudo::where([['user_id', '=', $user->id], ['avaliacao', '=', 1]])
                ->whereHas('user', function ($q) use ($escola_id) {
                    $q->where('escola_id', '=', $escola_id);
                })
                ->count();

            if ($totalAvaliacoes == 0) {
                $nivelAprendizado[5]++;
            } else {
                $percentualAprendizagem = ($totalAvaliacoesPositivas * 100) / $totalAvaliacoes;

                if ($percentualAprendizagem >= 80) {
                    $nivelAprendizado[0]++;
                } elseif ($percentualAprendizagem >= 60 && $percentualAprendizagem <= 79) {
                    $nivelAprendizado[1]++;
                } elseif ($percentualAprendizagem >= 40 && $percentualAprendizagem <= 59) {
                    $nivelAprendizado[2]++;
                } elseif ($percentualAprendizagem >= 20 && $percentualAprendizagem <= 39) {
                    $nivelAprendizado[3]++;
                } else //if($percentualAprendizagem >= 0 && $percentualAprendizagem <= 19)
                {
                    $nivelAprendizado[4]++;
                }
            }
        }

        $aplicacoes = Aplicacao::with('progressos_user.user')
            ->whereHas('user', function ($q) use ($escola_id) {
                $q->where('escola_id', '=', $escola_id);
            })
            ->get();

        $conteudos = Conteudo::query();

        $conteudos = $conteudos->where([['cursos.status_id', '!=', 2],
            ['cursos.escola_id', $escola_id],
            ['cursos.status_id', '!=', 2]])
            ->join('conteudo_aula', 'conteudos.id', 'conteudo_aula.conteudo_id')
            ->join('aulas', 'conteudo_aula.aula_id', 'aulas.id')
            ->leftjoin('cursos', 'conteudo_aula.curso_id', 'cursos.id')
            ->leftjoin('ciclos', 'ciclos.id', 'conteudos.ciclo_id')
            ->leftjoin('ciclo_etapas', 'ciclo_etapas.id', 'conteudos.cicloetapa_id')
            ->leftjoin('disciplinas', 'disciplinas.id', 'conteudos.disciplina_id')
            ->select([
                'ciclos.titulo as ciclo_nome',
                'ciclo_etapas.titulo as cicloetapa_nome',
                'disciplinas.titulo as disciplina_nome',
                'conteudos.*']);

        $conteudos = $conteudos->get();

        $conteudo_livro_gigital = Conteudo::query();
        #filtra os conteudos para apenas livro digital e retorna os livros conforme a escola
        $conteudo_livro_gigital = $conteudo_livro_gigital->where([['conteudo_instituicao_escola.escola_id', $escola_id], ['conteudos.tipo', 21]])
            ->leftjoin('conteudo_instituicao_escola', 'conteudos.id', 'conteudo_instituicao_escola.conteudo_id');

        //$conteudo_livro_gigital = $conteudo_livro_gigital->get();

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

        /*   $revista = $conteudo_livro_gigital->filter(function ($c) {
              return $c->tipo == 21;
          }); */

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
        /*         $conteudo_tipo[21] = $revista;
         */        $conteudo_tipo[22] = $docoficial;

        $totalAplicacoes = count($aplicacoes);
        $totalConteudos = count($conteudos);

        $mediaAtividadesCompletas = 0;
        $somatoria = 0;
        $total = 0;

        $participativos = 0;

        foreach ($aplicacoes as $aplicacao) {
            foreach ($aplicacao->progressos_user as $progresso) {
                if ($progresso->user['escola_id'] == $escola_id) {
                    if ($progresso->progresso > 0)
                        $participativos++;

                    if ($progresso->progresso > 100)
                        $progresso->progresso = 100;

                    $somatoria++;
                    $total += $progresso->progresso;
                }
            }
        }

        foreach ($conteudos as $conteudo) {
            foreach ($conteudo->progressos_user as $progresso) {
                if ($progresso->user['escola_id'] == $escola_id) {
                    if ($progresso->progresso > 0)
                        $participativos++;

                    if ($progresso->progresso > 100)
                        $progresso->progresso = 100;

                    $somatoria++;
                    $total += $progresso->progresso;
                }
            }
        }

        $totalProgressos = ProgressoConteudo::count();

        if ($somatoria > 0) {
            $mediaAtividadesCompletas = number_format($total / $somatoria, 0);

            if ($participativos > 0) {
                $participacao = number_format(($participativos * 100) / $participativos, 0);
            } else {
                $participacao = 0;
            }
        } else {
            $mediaAtividadesCompletas = 0;

            $participacao = 0;
        }

        // Liberação de aplicações para escola
        $liberacaoAplicacoesEscola = LiberacaoAplicacaoEscola::where('escola_id', $escola->id)->whereHas('aplicacao')->get();

        foreach ($liberacaoAplicacoesEscola as $key => $liberacao) {
            $liberacao->titulo = ucfirst(Aplicacao::where('id', $liberacao->aplicacao_id)->first()->titulo);
        }

        $codigosAcesso = CodigoAcessoEscola::where('escola_id', $escola->id)->get();

        return view('gestao.escola-painel')->with(compact(
            'escola',
            'codigosAcesso',
            'liberacaoAplicacoesEscola',
            'totalGestores',
            'totalProfessores',
            'totalProgressos',
            'mediaAtividadesCompletas',
            'participacao',
            'mediaAlunosConectados',
            'nivelAprendizado',
            'aplicacoes',
            'conteudos',
            'conteudo_tipo',
            'videos',
            'slides',
            'documentos',
            'alunos',
            'totalAlunos',
            'amountAlunos',
            'turmas',
            'totalTurmas',
            'amountTurmas',
            'professores',
            'amountProfessores',
            'gestores'
        ));
    }

    public function editarEscola($escola_id)
    {
        $escola = Escola::find($escola_id);

        if ($escola == null) {
            return redirect()->route('gestao.escolas');
        }

        $escola->caracteristicas = json_decode($escola->caracteristicas);

        return view('gestao.editar-escola')->with(compact('escola'));
    }

    public function searchEscola()
    {
        $returnEscolas = "";
        $id = \Request::input('id');

        $escolas = Escola::where([['instituicao_id', $id],['status_id', '!=', 2]])->orderBy('titulo', 'ASC')->get();
        if (count($escolas) > 0) {
            return response()->json(['success' => $escolas]);
        } else {
            return response()->json(['error']);
        }
    }

}
