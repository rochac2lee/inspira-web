<?php

namespace App\Http\Controllers;

//use App\Entities\Historico\Historico;
use Illuminate\Http\Request;

use Auth;
use Redirect;
use Session;
use Carbon\Carbon;

use App\Models\User;
//use App\Models\Aplicacao;
//use App\Models\LiberacaoAplicacao;
use App\Models\Conteudo;
use App\Models\Categoria;
//use App\Models\AlunoTurma;
//use App\Models\Turma;
use App\Models\Metrica;
use App\Models\Ciclo;
use App\Models\CicloEtapa;
use App\Models\Disciplina;

use App\Models\ProgressoConteudo;

class AplicacaoController extends Controller
{
    public function index($idAplicacao)
    {

        $aplicacao = Aplicacao::find($idAplicacao);

        if ($aplicacao == null) {
            \Session::flash('warning', 'Aplicação não encontrada.');
            return redirect('catalogo');
        }

        if (!\Storage::disk('public_uploads')->has('aplicacoes/' . $aplicacao->id) || ($aplicacao->status == 0 && Auth::user()->permissao != "Z")) {
            \Session::flash('warning', 'Aplicação não encontrada.');

            return redirect('catalogo');
        }

        // Histórico
        if (Historico::where([
                ['user_id', Auth::user()->id],
                ['referencia_id', $idAplicacao],
                ['tipo', 1],
                ['created_at', '>', (Carbon::now()->subMinutes(15))]])
                ->exists() == false) {
            Historico::create([
                'user_id'       => Auth::user()->id,
                'referencia_id' => $idAplicacao,
                'tipo'          => 1
            ]);
        }

        if (Metrica::where([
                ['user_id', Auth::user()->id],
                ['titulo', 'Jogar aplicação - ' . $aplicacao->id],
                ['created_at', '>', (Carbon::now()->subMinutes(15))]])
                ->exists() == false) {
            Metrica::create([
                'user_id' => Auth::check() ? Auth::user()->id : 0,
                'titulo'  => 'Jogar aplicação - ' . $aplicacao->id
            ]);
        }


        if (!ProgressoConteudo::where([['conteudo_id', '=', $idAplicacao], ['tipo', '=', 1], ['user_id', '=', Auth::user()->id]])->exists()) {
            ProgressoConteudo::create([
                'conteudo_id' => $idAplicacao,
                'tipo'        => 1,
                'user_id'     => Auth::user()->id
            ]);
        }

        return view('play.aplicacao')->with(compact('aplicacao'));
    }

    public function ultimaAplicacao()
    {
        if (Metrica::where([['user_id', '=', Auth::user()->id], ['titulo', 'like', 'Jogar aplicação%']])->first() != null) {
            $idUltimaAplicacao = str_replace('Jogar aplicação - ', "", Metrica::where([['user_id', '=', Auth::user()->id], ['titulo', 'like', 'Jogar aplicação - %']])->first()->titulo);

            return redirect()->route('aplicacao', ['idAplicacao' => $idUltimaAplicacao]);
        } else {
            \Session::flash('error', 'Você não acessou nenhum aplicação recentemente.');

            return redirect('catalogo');
        }
    }

    public function gestaoAplicacoes()
    {
        $aplicacoes = Aplicacao::query();

        $tem_pesquisa = Input::has('pesquisa') ? (Input::get('pesquisa') != null && Input::get('pesquisa') != "") : false;

        $aplicacoes = Aplicacao::when($tem_pesquisa, function ($query) {
            return $query
            ->where('titulo', 'like', '%' . Input::get('pesquisa') . '%')
            ->orWhere('descricao', 'like', '%' . Input::get('pesquisa') . '%');
        })
        ->get();

        $categorias = Categoria::all();
        $etapas = Ciclo::all();
        $cicloEtapas = CicloEtapa::all();
        $disciplinas = Disciplina::all();

        return view('gestao.aplicacoes')->with(compact('aplicacoes', 'categorias', 'etapas', 'cicloEtapas', 'disciplinas'));
    }

    public function postCriarAplicacao(Request $request)
    {

        if ($request->descricao == null) {
            $request->descricao = "";
        }

        if ($request->arquivo == null)
        {
            return redirect()->back()->withErrors("Arquivo não encontrado, por favor envie um zip válido!");
        }

        if ($request->data_lancamento != null)
        {
            if(date("Y-m-d H:i", strtotime($request->data_lancamento)) == str_replace("T", " ", $request->data_lancamento))
            {
                if(date("Y-m-d H:i") < date("Y-m-d H:i", strtotime($request->data_lancamento)))
                {
                    $request->data_lancamento = date("Y-m-d H:i:s", strtotime(str_replace("T", " ", $request->data_lancamento)));

                }
                else
                {
                    return redirect()->back()->withErrors("Data de lançamento inválida, você deve preencher uma data e hora que ainda não passaram!");
                }
            }
            else
            {
                return redirect()->back()->withErrors("Data de lançamento inválida, você deve preencher uma data e hora!");
            }
        }

        // if($request->marcadores != null)
        // {
        //     $request->marcadores = explode(";", $request->marcadores);
        // }


        $aplicacao = Aplicacao::create([
            'user_id'      => Auth::user()->id,
            'titulo'       => $request->titulo,
            'descricao'    => $request->descricao,
            'status'       => $request->status,
            'liberada'     => $request->liberada,
            'data_lancamento'     => $request->data_lancamento,
            'destaque'     => isset($request->destaque) ? true : false,
            'categoria_id'     => $request->categoria,
            'nivel_ensino'     => $request->nivel_ensino,
            'ano_serie'     => $request->ano_serie,
            'tags' => $request->marcadores,
            'ciclo_id'      => $request->ciclo_id,
            'cicloetapa_id' => $request->cicloetapa_id,
            'disciplina_id' => $request->disciplina_id
        ]);

        $logFiles = \Zipper::make($request->arquivo)->listFiles();

        //Salva o jogo na pasta gameZip
        $gameId = $aplicacao->id .'.zip';
        $uploadGame = $request->arquivo->storeAs('gameZip', $gameId, 'public_uploads');

        \Zipper::make($request->arquivo)->extractTo(public_path('uploads') . '/aplicacoes/' . $aplicacao->id);

        if (!\Storage::disk('public_uploads')->has('aplicacoes/' . $aplicacao->id)) {
            $aplicacao->delete();

            return redirect()->back()->withErrors("Não foi possível extrair o conteúdo do zip, por favor envie sua aplicação novamente!");
        }

        if ($request->capa != null) {
            $fileExtension = \File::extension($request->capa->getClientOriginalName());
            $newFileNameCapa = md5($request->capa->getClientOriginalName() . date("Y-m-d H:i:s") . time()) . '.' . $fileExtension;

            $pathCapa = $request->capa->storeAs('aplicacoes/capas', $newFileNameCapa, 'public_uploads');

            if (!\Storage::disk('public_uploads')->put($pathCapa, file_get_contents($request->capa))) {
                \Session::flash('error', 'Não foi possivel enviar a capa.');
            } else {
                $aplicacao->capa = $newFileNameCapa;

                $aplicacao->save();
            }
        }

        Session::flash("message", 'Aplicação criada com sucesso!');

        return redirect()->back();
    }

    public function getAplicacao($idAplicacao)
    {
        if (Aplicacao::find($idAplicacao) != null) {
            return response()->json(['success' => 'Aplicação encontrada.', 'aplicacao' => Aplicacao::find($idAplicacao)]);
        } else {
            return response()->json(['error' => 'Aplicação não encontrada!']);
        }

        return redirect()->back();
    }

    public function postSalvarAplicacao(Request $request)
    {
        if ($request->descricao == null)
        {
            $request->descricao = "";
        }

        if($request->marcadores != null)
        {
            $request->marcadores = explode(";", $request->marcadores);
        }

        if (Aplicacao::find($request->idAplicacao) != null)
        {
            $aplicacao = Aplicacao::find($request->idAplicacao);

            if ($request->data_lancamento != null ? ($aplicacao->data_lancamento != date("Y-m-d H:i", strtotime($request->data_lancamento))) : false)
            {
                if(date("Y-m-d H:i", strtotime($request->data_lancamento)) == str_replace("T", " ", $request->data_lancamento))
                {
                    if(date("Y-m-d H:i") < date("Y-m-d H:i", strtotime($request->data_lancamento)))
                    {
                        $request->data_lancamento = date("Y-m-d H:i:s", strtotime(str_replace("T", " ", $request->data_lancamento)));

                    }
                    else
                    {
                        return redirect()->back()->withErrors("Data de lançamento inválida, você deve preencher uma data e hora que ainda não passaram!");
                    }
                }
                else
                {
                    return redirect()->back()->withErrors("Data de lançamento inválida, você deve preencher uma data e hora!");
                }
            }

            $aplicacao->update([
                'titulo'       => $request->titulo,
                'descricao'    => $request->descricao,
                'status'       => $request->status,
                'liberada'     => $request->liberada,
                'data_lancamento'     => $aplicacao->data_lancamento != date("Y-m-d H:i", strtotime($request->data_lancamento)) ? $request->data_lancamento : $aplicacao->data_lancamento,
                'destaque'      => isset($request->destaque) ? true : false,
                'categoria_id'  => $request->categoria,
                'nivel_ensino'  => $request->nivel_ensino,
                'ano_serie'     => $request->ano_serie,
                'tags'          => $request->marcadores,
                'ciclo_id'      => $request->ciclo_id,
                'cicloetapa_id' => $request->cicloetapa_id,
                'disciplina_id' => $request->disciplina_id
            ]);

            if ($request->capa != null) {
                $fileExtension = \File::extension($request->capa->getClientOriginalName());
                $newFileNameCapa = md5($request->capa->getClientOriginalName() . date("Y-m-d H:i:s") . time()) . '.' . $fileExtension;

                $pathCapa = $request->capa->storeAs('aplicacoes/capas', $newFileNameCapa, 'public_uploads');

                if (!\Storage::disk('public_uploads')->put($pathCapa, file_get_contents($request->capa))) {
                    \Session::flash('error', 'Não foi possivel enviar a capa.');
                } else {
                    if (\Storage::disk('public_uploads')->has('aplicacoes/capas/' . $aplicacao->capa)) {
                        \Storage::disk('public_uploads')->delete('aplicacoes/capas/' . $aplicacao->capa);
                    }

                    $aplicacao->capa = $newFileNameCapa;

                    $aplicacao->save();
                }
            }

            if ($request->arquivo != null) {
                $logFiles = \Zipper::make($request->arquivo)->listFiles();

                \Zipper::make($request->arquivo)->extractTo(public_path('uploads') . '/aplicacoes/' . $aplicacao->id . '/');

                if (!\Storage::disk('public_uploads')->has('aplicacoes/' . $aplicacao->id)) {
                    return redirect()->back()->withErrors("Não foi possível extrair o conteúdo do zip, por favor atualize sua aplicação novamente!");
                }
            }

            // return response()->json(['success' => 'Aplicação atualizada com sucesso!']);
            Session::flash("message", 'Aplicação atualizada com sucesso!');
            return redirect()->back();
        } else {
            // return response()->json(['error' => 'Aplicação não encontrada!']);
            Session::flash("error", 'Aplicação não encontrada!');
            return redirect()->back();
        }
    }

    public function postExcluirAplicacao($idAplicacao)
    {
        if (Aplicacao::find($idAplicacao) != null) {

            if (\Storage::disk('public_uploads')->has('aplicacoes/capas/' . Aplicacao::find($idAplicacao)->capa)) {
                \Storage::disk('public_uploads')->delete('aplicacoes/capas/' . Aplicacao::find($idAplicacao)->capa);
            }

            if (\Storage::disk('public_uploads')->has('aplicacoes/' . Aplicacao::find($idAplicacao)->id)) {
                \Storage::disk('public_uploads')->deleteDirectory('aplicacoes/' . Aplicacao::find($idAplicacao)->id);
            }

            if (\Storage::disk('public_uploads')->has('aplicacoes/gameZip' . Aplicacao::find($idAplicacao)->id. '.zip')) {
                \Storage::disk('public_uploads')->deleteDirectory('aplicacoes/gameZip' . Aplicacao::find($idAplicacao)->id. '.zip');
            }

            Aplicacao::find($idAplicacao)->delete();

            return response()->json(["success" => "Aplicação excluída com sucesso!"]);
        } else {
            return response()->json(["error" => "Aplicação não encontrada!"]);
        }
    }

    public function postLiberacaoAplicacao($idTurma, Request $request)
    {
        if (Turma::find($idTurma) != null) {
            if (Aplicacao::find($request->idAplicacao) != null) {
                $alunos = json_decode($request->alunos);

                if ($alunos == null) {
                    // return redirect()->back()->withErrors("Você deve selecionar ao menos um aluno!");
                    $alunos = [];
                }
                // if(count($alunos) == 0)
                // {
                //     return redirect()->back()->withErrors("Você deve selecionar ao menos um aluno!");
                // }

                if ($request->quais == "nenhum") {
                    LiberacaoAplicacao::where([['aplicacao_id', '=', $request->idAplicacao]])->whereNotIn('user_id', $alunos)->delete();

                    foreach ($alunos as $aluno) {
                        if (!LiberacaoAplicacao::where([['aplicacao_id', '=', $request->idAplicacao], ['user_id', '=', $aluno]])->exists()) {
                            LiberacaoAplicacao::create([
                                'aplicacao_id' => $request->idAplicacao,
                                'user_id'      => $aluno
                            ]);
                        }
                    }
                } else {
                    LiberacaoAplicacao::where([['aplicacao_id', '=', $request->idAplicacao]])->whereIn('user_id', $alunos)->delete();

                    foreach (AlunoTurma::where([['turma_id', '=', $idTurma]])->whereNotIn('user_id', $alunos)->get() as $aluno) {
                        if (!LiberacaoAplicacao::where([['aplicacao_id', '=', $request->idAplicacao], ['user_id', '=', $aluno->user_id]])->exists()) {
                            LiberacaoAplicacao::create([
                                'aplicacao_id' => $request->idAplicacao,
                                'user_id'      => $aluno->user_id
                            ]);
                        }
                    }
                }

                Session::flash("message", 'Aplicação liberada com sucesso para ' . count($alunos) . ' aluno' . (count($alunos) != 1 ? 's' : '') . '!');
                return redirect()->back();

            } else {
                return redirect()->back()->withErrors("Aplicação não encontrada!");
            }
        } else {
            return redirect()->back()->withErrors("Turma não encontrada!");
        }
    }
}
