<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use Auth;
use Redirect;
use Session;
use Carbon\Carbon;

use App\Models\Escola;
use App\Models\Curso;
use App\Models\Aula;
use App\Models\Conteudo;
use App\Models\ConteudoCompleto;
use App\Models\Categoria;

class EntregaveisController extends Controller
{
    public function index()
    {
        // if(strtoupper(Auth::user()->permissao) == "Z")
        {
            $conteudosEntregaveis = Conteudo::with('conteudo_aula')->whereHas('conteudo_aula',function($q){
                                                                                        $q->where('user_id',Auth::user()->id);
                                                                                })
                                            ->where(function($q){
                                                $q->orWhere('tipo','7')
                                                ->orWhere('tipo','8')
                                                ->orWhere('tipo','10');
                                            })
                                            ->get();
            $conteudosEntregaveis;

        }
        // else
        // {
        //     $conteudosEntregaveis = Conteudo::where([['tipo', '=', '7'], ['user_id', '=', Auth::user()->id]])->orWhere([['tipo', '=', '10'], ['user_id', '=', Auth::user()->id]])->get();
        // }

        // dd($conteudosEntregaveis);

        $idCursos = [];

        foreach ($conteudosEntregaveis as $key => $conteudo)
        {
            if($conteudo->conteudo_aula != null)
            if(!in_array($conteudo->conteudo_aula->curso_id, $idCursos))
            {
                array_push($idCursos, $conteudo->conteudo_aula->curso_id);
            }
        }

        $cursos = Curso::whereIn('id', $idCursos)->get();

        return view('gestao.entregaveis')->with(compact('cursos'));
    }

    public function getEntregaveisCurso($idCurso)
    {
        if(Curso::find($idCurso) != null)
        {
            $curso = Curso::find($idCurso);
        }
        else
        {
            return redirect()->back()->withErrors('Conteúdo não encontrado!');
        }

        // if(strtolower(Auth::user()->permissao) == "z")
        {
            $conteudos = Conteudo::with('conteudo_aula')
                                    ->whereHas('conteudo_aula', function ($query) use ($idCurso) {
                                            $query->where([['curso_id', '=', $idCurso]]);
                                        })->get();
                                  /*  ->where(function ($query) {
                                            $query->where([['tipo', '=', '7']])
                                    ->orWhere([['tipo', '=', '9']])
                                    ->orWhere([['tipo', '=', '10']]);
                                    })->get(); */



        }
        // else
        // {
        //     $conteudos = Conteudo::where([['tipo', '=', '7'], ['user_id', '=', Auth::user()->id]])
        //     ->orWhere([['tipo', '=', '9'], ['curso_id', '=', $idCurso]])
        //     ->orWhere([['tipo', '=', '10'], ['user_id', '=', Auth::user()->id], ['curso_id', '=', $idCurso]])->get();
        // }
    //   return response()->json($conteudos);

        foreach ($conteudos as $index => $conteudo)
        {
            $conteudo->respostas = ConteudoCompleto::with('user')->where([['curso_id', '=', $conteudo->conteudo_aula->curso_id], ['aula_id', '=', $conteudo->conteudo_aula->aula_id], ['conteudo_id', '=', $conteudo->id]])->get();


            $conteudo->data = $conteudo->created_at->format('d/m/Y \à\s H:i');

            if (is_array($conteudo) || is_object($conteudo))
            {

                if($conteudo->tipo == 7 || $conteudo->tipo == 9)
                {
                    $conteudo->conteudo = json_decode($conteudo->questoes[0]);
                }
                elseif($conteudo->tipo == 9)
                {
                    $conteudo->provaQuiz = true;
                    if($conteudo->provaQuiz)
                    {
                        $conteudos->forget($index);
                    }
                }
                elseif($conteudo->tipo != 10 && $conteudo->tipo != 8){
                    $conteudos->forget($index);
                }
            }
        }

        return view('gestao.entregaveis')->with(compact('curso', 'conteudos'));
    }

    public function postCorrigirResposta($idCurso, $idResposta, Request $request)
    {
        if(Curso::find($idCurso) != null)
        {
            $curso = Curso::find($idCurso);
        }
        else
        {
            return response()->json(['error' => 'Conteúdo não encontrado!']);
        }

        if(ConteudoCompleto::with('user')->where([['curso_id', '=', $idCurso], ['id', '=', $idResposta]])->first() == null)
        {
            return response()->json(['error' => 'não encontrada!']);
        }

        $resp = ConteudoCompleto::with('user')->where([['curso_id', '=', $idCurso], ['id', '=', $idResposta]])->first();

        // if(Conteudo::where([['curso_id', '=', $idCurso], ['aula_id', '=', $resp->aula_id], ['id', '=', $resp->conteudo_id]])->first() == null)
        if(Conteudo::whereHas('conteudo_aula', function ($query) use ($idCurso, $resp) {
            $query->where([['curso_id', '=', $idCurso], ['aula_id', '=', $resp->aula_id], ['conteudo_id', '=', $resp->conteudo_id]]);
        })->first() == null)
        {
            return response()->json(['error' => 'Conteúdo não encontrado!']);
        }

        // $conteudo = Conteudo::where([['curso_id', '=', $idCurso], ['aula_id', '=', $resp->aula_id], ['id', '=', $resp->conteudo_id]])->first();
        $conteudo = Conteudo::whereHas('conteudo_aula', function ($query) use ($idCurso, $resp) {
            $query->where([['curso_id', '=', $idCurso], ['aula_id', '=', $resp->aula_id]]);
        })
        ->where([['id', '=', $resp->conteudo_id]])->first();

        $resp->resposta = json_decode($resp->resposta);
        $conteudo->conteudo = json_decode($conteudo->conteudo);

        if($conteudo->tipo == 9)
        {
            $newResp = $request->correta;

            foreach($request->correta as $index => $resposta)
            {
                if($resposta == null)
                {
                    if($conteudo->conteudo[$index]->correta == $resp->resposta[$index])
                    {
                        $newResp[$index] = 1;
                    }
                    else
                    {
                        $newResp[$index] = 0;
                    }
                }
            }

            ConteudoCompleto::with('user')->where([['curso_id', '=', $idCurso], ['id', '=', $idResposta]])->first()->update([
                'correta' => json_encode($newResp)
            ]);
        }
        else
        {
            if($request->correta == null)
            {
                return response()->json(['error' => 'Correção inválida!']);
            }
            elseif($request->correta !== "true" && $request->correta !== "false")
            {
                return response()->json(['error' => 'Correção inválida!', 'correta' => $request->correta ]);
            }

            ConteudoCompleto::with('user')->where([['curso_id', '=', $idCurso], ['id', '=', $idResposta]])->first()->update([
                'correta' => $request->correta === "true" ? 1 : 0
            ]);
        }

        return response()->json(['success' => 'Corrigição feita com sucesso!']);
    }

    public function getArquivoEntregavel($idResposta)
    {
        if(ConteudoCompleto::with('user')->where([['id', '=', $idResposta]])->first() == null)
        {
            return response()->view('errors.404');
        }

        $resposta = ConteudoCompleto::with('user')->where([['id', '=', $idResposta]])->first();
        $path = config('app.frStorage').'/uploads/entregavel/aluno/' . $resposta->user_id . '/' . $resposta->resposta;
        if(Storage::disk()->has($path))
        {
            ob_end_clean();
            return Storage::disk()->response($path);
        }
        else
        {
            return response()->view('errors.404');
        }
    }


}
