<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Request;

use Illuminate\Support\Facades\Storage;

use Auth;
use Redirect;
use Session;

use App\Models\Escola;
use App\Models\Categoria;
use App\Models\Curso;
use App\Models\Aula;
use App\Models\Conteudo;
use App\Models\ConteudoAula;

use App\Models\AvaliacaoCurso;

use App\Models\AvaliacaoInstrutor;
use App\Models\AvaliacaoEscola;
use App\Models\HelperClass;

use App\Models\Matricula;

use App\Models\Metrica;
/*

use App\Models\ComentarioConteudo;
use App\Models\Notificacao;



use App\Models\Certificado;




use App\Models\MensagemTransmissao;

use App\Models\BadgeUsuario;
use App\Entities\Trilhas\TrilhasCurso;
use App\Entities\Trilhas\TrilhasMatricula;
use App\Models\InstituicaoUser;
*/
use App\Models\Transacao;
use App\Models\ProdutoTransacao;
use App\Models\CursoCompleto;
use App\Models\ConteudoCompleto;
use App\Models\AvaliacaoConteudo;
use App\Models\ConsumoConteudo;
use App\Models\CicloEtapa;
class EdulabzzCursoController extends Controller
{
    public function index($idCurso)
    {

        if(is_numeric($idCurso))
        {
            if(Curso::find($idCurso) != null)
            {
                $curso = Curso::with('aulas', 'user', 'avaliacoes_user', 'topicos')->where([['id', '=', $idCurso]])->first();
                // $curso = Curso::with('aulas', 'user', 'avaliacoes_user')->where([['escola_id', '=', $escola->id], ['id', '=', $idCurso]])->first();
            }
        }
        else
        {
            // Verificação comentada, apenas usada se o titulo for codificado com traços ao inves de espaços
            // if(Curso::where('titulo', '=', str_replace('-', ' ', $idCurso))->first() != null)
            if(Curso::where('titulo', '=', $idCurso)->first() != null)
            {
                $curso = Curso::with('aulas', 'user', 'avaliacoes_user', 'topicos')->where([['escola_id', '=', $escola->id], ['titulo', '=', $idCurso]])->first();
            }
            elseif(is_numeric( substr(strrchr($idCurso, "-"), 1) ))
            {
                if(Curso::find(substr(strrchr($idCurso, "-"), 1)) != null)
                {
                    $temp = Curso::find(substr(strrchr($idCurso, "-"), 1));

                    $tempTitulo = mb_strtolower($temp->titulo . '-' . $temp->id, 'utf-8');

                    if($tempTitulo == $idCurso)
                    {
                        $curso = Curso::find(substr(strrchr($idCurso, "-"), 1));
                    }
                }
            }
        }

        // dd(count(array_filter(Session::get('carrinho'), function($k) use ($curso) { return ($k->tipo == 2 && $k->id == $curso->id); })));

        if(isset($curso) ? ($curso == null) : (true))
        {
            Session::flash('error', 'Conteúdo não encontrado!');

            return redirect('catalogo');
        }

        if($curso->status != 1)
        {
            if(Auth::check() ? (strtolower(Auth::user()->permissao) != "z" && $curso->user_id != Auth::user()->id) : true)
            {
                Session::flash('error', 'Conteúdo não encontrado!');

                return redirect('catalogo');
            }
            else
            {
                Session::flash('previewMode', true);
            }
        }

        if($curso->senha != "" && $curso->senha != null)
        {
            if(Session::has('senhaCurso'. $curso->id))
            {
                if(Session::get('senhaCurso'. $curso->id) != $curso->senha)
                {
                    Session::forget('senhaCurso'. $curso->id);
                    return Redirect::back()->withErrors(['Senha inválida!']);
                }
            }
            else
            {
                // if($curso->escola_id != 1)
                // {
                //     // return Redirect::route('curso.trancado', ['escola_id' => $curso->escola_id, 'idCurso' => $curso->id]);
                // }
                // else
                {
                    return Redirect::route('curso.trancado', ['idCurso' => $curso->id]);
                }
            }
        }

        $curso->duracao = 0;

        foreach ($curso->aulas as $key => $aula)
        {

            // $aula->conteudos = Conteudo::where([['curso_id', '=', $curso->id], ['aula_id', '=', $aula->id]])->orderBy('ordem', 'asc')->get();
            $aula->conteudos = Conteudo::with('conteudo_aula')
                ->whereHas('conteudo_aula', function ($query) use ($curso, $aula) {
                    $query->where([['curso_id', '=', $curso->id], ['aula_id', '=', $aula->id]]);
                })
                // ->orderBy('ordem', 'asc')
                ->get()
                ->sortBy('conteudo_aula.ordem');
            $aula->conteudos = Conteudo::detalhado($aula->conteudos);

            // $aula->duracao = Conteudo::
            // whereHas('conteudo_aula', function ($query) use ($curso, $aula) {
            //     $query->where([['curso_id', '=', $curso->id], ['aula_id', '=', $aula->id]]);
            // })
            // ->sum('duracao');


            // $aula->duracao = $aula;
            // if($aula->duracao == 0)
            // {
            //     $aula->duracao = count($aula->conteudos) * 120;
            // }

            // return $aula->duracao;

            //Verificar se a contagem de tempo vai ser na aula ou a soma dos conteúdos
            $aula->duracao  = Aula::where('id', '=', $aula->id)->sum('duracao');

            $curso->duracao += $aula->duracao;

            if($aula->duracao > 60)
            {
                $horas = floor($aula->duracao / 60);
                $minutos = number_format((($aula->duracao / 60) - $horas) * 60, 0);
                $aula->duracao = ($horas < 10 ? '0' . $horas : $horas) . ':' . ($minutos < 10 ? '0' . $minutos : $minutos);
            }
            else
            {
                $aula->duracao = '00:' . ($aula->duracao < 10 ? '0' . $aula->duracao : $aula->duracao);
            }
        }

        if($curso->duracao > 60)
        {
            $horas = floor($curso->duracao / 60);
            $minutos = number_format((($curso->duracao / 60) - $horas) * 60, 0);
            $curso->duracao = ($horas < 10 ? '0' . $horas : $horas) .  ':' . ($minutos < 10 ? '0' . $minutos : $minutos);
        }
        else
        {
            $curso->duracao = '00:' . ($curso->duracao < 10 ? '0' . $curso->duracao : $curso->duracao);
        }

        if(AvaliacaoCurso::where('curso_id', '=', $curso->id)->avg('avaliacao') > 0)
            $curso->avaliacao = AvaliacaoCurso::where('curso_id', '=', $curso->id)->avg('avaliacao');
        else
            $curso->avaliacao = 5;

        if(AvaliacaoInstrutor::where('instrutor_id', '=', $curso->user_id)->avg('avaliacao') > 0)
            $curso->user->avaliacao = AvaliacaoInstrutor::where('instrutor_id', '=', $curso->user_id)->avg('avaliacao');
        else
            $curso->user->avaliacao = 5;

        if(AvaliacaoEscola::where('escola_id', '=', $curso->escola_id)->avg('avaliacao') > 0)
            $curso->avaliacao_escola = AvaliacaoEscola::where('escola_id', '=', $curso->escola_id)->avg('avaliacao');
        else
            $curso->avaliacao_escola = 5;

        //Adiciona mais uma visita se não for o criador do curso
        if((Auth::check() && $curso->user_id != Auth::user()->id) || !Auth::check())
        {
            Metrica::create([
                'user_id' => Auth::check() ? Auth::user()->id : 0,
                'titulo' => 'Visualização do Conteúdo - ' . $curso->id
            ]);
        }

        $curso->visualizacoes = Metrica::where('titulo', '=', 'Visualização do Conteúdo - ' . $curso->id)->count();

        $matricula = Auth::check() ? Matricula::where([['user_id', '=', Auth::user()->id], ['curso_id', '=', $curso->id]])->first() : null;

        /*
        if(config('app.name') == "Toolzz")
        {
            if(!WirecardAccount::where('user_id', '=', $curso->user_id)->exists() && $curso->preco > 0)
            {
                Curso::find( $curso->id )->update([
                    'status' => 0
                ]);

                if($matricula == null)
                {
                    Session::flash('error', "Curso não encontrado, por favor entre em contato com nosso suporte. (Erro.: TZ-03)");

                    return redirect()->route('catalogo-cursos');

                    // return response()->json(["error" => "O vendedor não possui conta Wirecard vinculada, por favor entre em contato com nosso suporte."]);
                }
            }
        }
        */

        $curso->transacoes = null;

        $curso->statusPagamento = null;

        if(Auth::check())
        {
            $curso->transacoes = collect();

            foreach (ProdutoTransacao::where([['user_id', '=', Auth::user()->id], ['produto_id', '=', $curso->id]])->get() as $produto)
            {
                if(Transacao::where('referencia_id', $produto->transacao_id)->first() != null)
                {
                    $curso->transacoes->push( Transacao::where('referencia_id', $produto->transacao_id)->first() );
                }
            }

            // dd(ProdutoTransacao::where([['user_id', '=', Auth::user()->id], ['produto_id', '=', $curso->id]])->get());

            $curso->transacoes = $curso->transacoes->unique(function ($item) {
                return $item->id;
            });

            if(count($curso->transacoes) > 0)
            {
                $curso->statusPagamento = $curso->transacoes[0]->status;
            }

            foreach ($curso->transacoes as $transacao)
            {
                if($transacao->status == 3 || $transacao->status == 4)
                {
                    $curso->statusPagamento = $transacao->status;
                    break;
                }
            }
        }


        return view('curso.index')->with(compact('curso', 'matricula'));
    }

    public function cursoTrancado($idCurso)
    {
        if(is_numeric($idCurso))
        {
            if(Curso::find($idCurso) != null)
            {
                $curso = Curso::with('aulas', 'user', 'avaliacoes_user')->find($idCurso);
            }
        }
        else
        {
            if(Curso::where('titulo', '=', str_replace('-', ' ', $idCurso))->first() != null)
            {
                $curso = Curso::with('aulas', 'user', 'avaliacoes_user')->where('titulo', '=', str_replace('-', ' ', $idCurso))->first();
            }
            elseif(is_numeric( substr(strrchr($idCurso, "-"), 1) ))
            {
                if(Curso::find(substr(strrchr($idCurso, "-"), 1)) != null)
                {
                    $temp = Curso::find(substr(strrchr($idCurso, "-"), 1));

                    $tempTitulo = mb_strtolower(str_replace(' ', '-', $temp->titulo) . '-' . $temp->id, 'utf-8');

                    if($tempTitulo == $idCurso)
                    {
                        $curso = Curso::find(substr(strrchr($idCurso, "-"), 1));
                    }
                }
            }
        }

        if(isset($curso) ? ($curso == null) : (true))
        {
            Session::flash('error', 'Conteúdo não encontrado!');

            return redirect('catalogo');
        }

        if($curso->senha == "" || $curso->senha == null)
        {
            return redirect()->route('curso', ['idCurso' => $idCurso]);
        }

        if($curso->status != 1)
        {
            if(Auth::check() ? (strtoupper(Auth::user()->permissao) != "Z" && $curso->user_id != Auth::user()->id) : true)
            {
                Session::flash('error', 'Conteúdo não encontrado!');

                return redirect('catalogo');
            }
            else
            {
                Session::flash('previewMode', true);
            }
        }

        if(Session::has('senhaCurso'. $curso->id))
        {
            if(Session::get('senhaCurso'. $curso->id) == $curso->senha)
            {
                if($curso->escola_id != 1 && Escola::find($curso->escola_id) != null)
                {
                    return redirect()->route('curso', ['escola_id' => Escola::find($curso->escola_id)->url, 'idCurso' => $idCurso]);
                }
                else
                {
                    return redirect()->route('curso', ['idCurso' => $idCurso]);
                }

            }
            else
            {
                Session::forget('senhaCurso'. $curso->id);
            }
        }

        return view('curso.trancado')->with(compact('curso'));
    }

    public function postAcessarCursoTrancado($idCurso, Request $request)
    {
        if(is_numeric($idCurso))
        {
            if(Curso::find($idCurso) != null)
            {
                $curso = Curso::with('aulas', 'user', 'avaliacoes_user')->find($idCurso);
            }
        }
        else
        {
            if(Curso::where('titulo', '=', str_replace('-', ' ', $idCurso))->first() != null)
            {
                $curso = Curso::with('aulas', 'user', 'avaliacoes_user')->where('titulo', '=', str_replace('-', ' ', $idCurso))->first();
            }
            elseif(is_numeric( substr(strrchr($idCurso, "-"), 1) ))
            {
                if(Curso::find(substr(strrchr($idCurso, "-"), 1)) != null)
                {
                    $temp = Curso::find(substr(strrchr($idCurso, "-"), 1));

                    $tempTitulo = mb_strtolower(str_replace(' ', '-', $temp->titulo) . '-' . $temp->id, 'utf-8');

                    if($tempTitulo == $idCurso)
                    {
                        $curso = Curso::find(substr(strrchr($idCurso, "-"), 1));
                    }
                }
            }
        }

        if(isset($curso) ? ($curso == null) : (true))
        {
            Session::flash('error', 'Conteúdo não encontrado!');

            return redirect('catalogo');
        }

        if($curso->status != 1)
        {
            if(Auth::check() ? (strtolower(Auth::user()->permissao) != "z" && $curso->user_id != Auth::user()->id) : true)
            {
                Session::flash('error', 'Conteúdo não encontrado!');

                return redirect('catalogo');
            }
            else
            {
                Session::flash('previewMode', true);
            }
        }

        if($request->senha == $curso->senha)
        {
            Session::put('senhaCurso'. $curso->id, $request->senha);

            if($curso->escola_id != 1 && Escola::find($curso->escola_id) != null)
            {
                return redirect()->route('curso', ['escola_id' => Escola::find($curso->escola_id)->url, 'idCurso' => $idCurso]);
            }
            else
            {
                return redirect()->route('curso', ['idCurso' => $idCurso]);
            }
        }
        else
        {
            return Redirect::back()->withErrors(["Senha inválida!"]);
        }
    }

    function checkoutCurso($idCurso)
    {
        if(Curso::find($idCurso))
        {
            $curso = Curso::with('user')->where('id', '=', $idCurso)->get()->first();
        }
        else
        {
            return Redirect::back()->withErrors(['Conteúdo não encontrado!']);
        }

        if($curso->status == 0)
        {
            Session::flash('error', 'Conteúdo não encontrado!');

            return redirect('catalogo');
        }

        $produto = (object) [
            'id' => $idCurso,
            'preco' => $curso->preco,
            'titulo' => ucfirst($curso->titulo),
            "descricao" => substr(ucfirst($curso->descricao_curta), 0, 50) . '.',
            "tipo" => 2,
            "transacoes" => null,
            "referencia" => null,
            "quantidade" => null,
            "recorrencia" => null,
        ];

        Session::put('carrinho', [$produto]);

        $total = 0;

        $produto->curso = Curso::find($idCurso);

        $produto->transacoes = collect();

        foreach (ProdutoTransacao::where([['user_id', '=', Auth::user()->id], ['produto_id', '=', $curso->id]])->get() as $prod)
        {
            if(Transacao::where([['referencia_id', '=', $prod->transacao_id], ['status', '<', 5]])->first() != null)
            {
                $produto->transacoes->push(Transacao::where([['referencia_id', '=', $prod->transacao_id], ['status', '<', 5]])->first());
            }
        }

        $produto->transacoes = $produto->transacoes->unique(function ($item) {
            return $item->id;
        });

        if(count($produto->transacoes) > 0)
        {
            Session::put('carrinho', []);

            return redirect()->route('curso', ['idCurso' => $produto->id]);
        }

        $total += $produto->preco;

        if($total == 0)
        {
            return redirect()->route('curso.matricular', ['idCurso' => $produto->id]);
        }

        return redirect()->route('pedidos.checkout');
    }

    public function cursoCarrinho($idCurso, Request $request)
    {
        if(is_numeric($idCurso))
        {
            $curso = Curso::find($idCurso);
            if(!$curso)
            {
                Session::flash('error', 'Conteúdo não encontrado!');

                return redirect('catalogo');
            }
        }
        else
        {
            $curso = Curso::where('titulo', '=', str_replace('-', ' ', $idCurso))->first();

            if(!$curso)
            {
                Session::flash('error', 'Conteúdo não encontrado!');

                return redirect('catalogo');
            }
        }

        if($curso->status == 0)
        {
            Session::flash('error', 'Conteúdo não encontrado!');

            return redirect('catalogo');
        }

        $produto = (object) [
            'id' => $idCurso,
            'preco' => $curso->preco,
            'titulo' => ucfirst($curso->titulo),
            "descricao" => substr(ucfirst($curso->descricao_curta), 0, 50) . '.',
            "tipo" => 2,
            "curso" => $curso,
            "transacoes" => null,
            "referencia" => null,
            "quantidade" => null,
            "recorrencia" => null,
        ];

        if(Session::has('carrinho'))
        {
            $carrinho = Session::get('carrinho');

            if(array_search($idCurso, array_column($carrinho, 'id')) === false)
            {
                array_push($carrinho, $produto);

                Session::put('carrinho', $carrinho);
            }
            else
            {
                Session::flash('message', "Este produto já está em seu carrinho!");
            }
        }
        else
        {
            Session::put('carrinho', [$produto]);
        }

        // Session::flash('message', "Curso adicionado ao carrinho!");

        if($request->return != "")
        {
            return redirect($request->return);
        }

        return redirect()->route('carrinho.index');
    }

    public function matricular($idCurso)
    {
        if(is_numeric($idCurso))
        {
            if(Curso::find($idCurso) != null)
            {
                $curso = Curso::find($idCurso);
            }
            else
            {
                Session::flash('error', 'Conteúdo não encontrado!');

                return redirect('catalogo');
            }
        }
        else
        {
            if(Curso::where('titulo', '=', str_replace('-', ' ', $idCurso))->first() != null)
            {
                $curso = Curso::where('titulo', '=', str_replace('-', ' ', $idCurso))->first();
            }
            else
            {
                Session::flash('error', 'Conteúdo não encontrado!');

                return redirect('catalogo');
            }
        }

        if($curso->status != 1)
        {
            if(Auth::check() ? (strtolower(Auth::user()->permissao) != "z" && $curso->user_id != Auth::user()->id) : true)
            {
                Session::flash('error', 'Conteúdo não encontrado!');

                return redirect('catalogo');
            }
            else
            {
                Session::flash('previewMode', true);
            }
        }

        if(Matricula::where([['user_id', '=', Auth::user()->id], ['curso_id', '=', $curso->id]])->first() != null)
        {
            if(Escola::find($curso->escola_id) != null)
            {
                return redirect()->route('curso.play', ['escola_id' => Escola::find($curso->escola_id)->url, 'idCurso' => $curso->id]);
            }
            else
            {
                return redirect()->route('curso.play', ['idCurso' => $curso->id]);
            }
        }

        if($curso->preco > 0 && (Auth::check() ? (strtoupper(Auth::user()->permissao) != "Z" && $curso->user_id != Auth::user()->id) : true))
        {
            $curso->transacoes = collect();

            $curso->pago = false;

            foreach (ProdutoTransacao::where([['user_id', '=', Auth::user()->id], ['produto_id', '=', $curso->id]])->get() as $produto)
            {
                if(Transacao::where([['referencia_id', '=', $produto->transacao_id], ['status', '<', 5]])->first() != null)
                {
                    $curso->transacoes->push(Transacao::where([['referencia_id', '=', $produto->transacao_id], ['status', '<', 5]])->first());
                }

                if(Transacao::where([['referencia_id', '=', $produto->transacao_id], ['status', '=', 3]])->orWhere([['id', '=', $produto->transacao_id], ['status', '=', 4]])->first() != null)
                {
                    $curso->pago = true;
                }
            }

            $curso->transacoes = $curso->transacoes->unique(function ($item) {
                return $item->id;
            });

            if(count($curso->transacoes) == 0)
            {
                return redirect()->route('checkout');
                // return redirect()->route('curso.checkout', ['idCurso' => $curso->id]);
            }
            else
            {
                if($curso->pago)
                {
                    Matricula::create([
                        'user_id' => Auth::user()->id,
                        'curso_id' => $curso->id,
                    ]);

                    if(Escola::find($curso->escola_id) != null)
                    {
                        return redirect()->route('curso.play', ['escola_id' => Escola::find($curso->escola_id)->url, 'idCurso' => $curso->id]);
                    }
                    else
                    {
                        return redirect()->route('curso.play', ['idCurso' => $curso->id]);
                    }
                }
                else
                {
                    if(Escola::find($curso->escola_id) != null)
                    {
                        return redirect()->route('curso', ['escola_id' => Escola::find($curso->escola_id)->url, 'idCurso' => $curso->id]);
                    }
                    else
                    {
                        return redirect()->route('curso', ['idCurso' => $curso->id]);
                    }
                }
            }
        }
        else
        {
            Matricula::create([
                'user_id' => Auth::user()->id,
                'curso_id' => $curso->id,
            ]);

            if(Escola::find($curso->escola_id) != null)
            {
                return redirect()->route('curso.play', ['escola_id' => Escola::find($curso->escola_id)->url, 'idCurso' => $curso->id]);
            }
            else
            {
                return redirect()->route('curso.play', ['idCurso' => $curso->id]);
            }
        }
    }

    public function playCurso($idCurso)
    {

        $curso = null;

        $ultimaAula = null;
        $ultimoConteudo = null;

        if(is_numeric($idCurso))
        {
            if(Curso::find($idCurso) != null)
            {
                $curso = Curso::with('aulas', 'user', 'avaliacoes_user')->where([['id', '=', $idCurso]])->first();

            }
        }

        else
        {
            if(Curso::where('titulo', '=', $idCurso)->first() != null)
            {
                $curso = Curso::with('aulas', 'user', 'avaliacoes_user')->where([['titulo', '=', $idCurso]])->first();
            }
            elseif(is_numeric( substr(strrchr($idCurso, "-"), 1) ))
            {
                if(Curso::find(substr(strrchr($idCurso, "-"), 1)) != null)
                {
                    $temp = Curso::find(substr(strrchr($idCurso, "-"), 1));

                    $tempTitulo = mb_strtolower($temp->titulo . '-' . $temp->id, 'utf-8');

                    if($tempTitulo == $idCurso)
                    {
                        $curso = Curso::find(substr(strrchr($idCurso, "-"), 1));
                    }
                }
            }
        }

        if($curso == null)
        {
            return Redirect::back()->withErrors(['Conteúdo não encontrado!']);
        }

        // if(Curso::find($curso->id))
        // {
        //     $curso = Curso::with('aulas')->find($curso->id);
        // }
        // else
        // {
        //     return Redirect::back()->withErrors(['Curso não encontrado!']);
        // }

        if($curso->status != 1)
        {
            if(Auth::check() ? (strtolower(Auth::user()->permissao) != "z" && $curso->user_id != Auth::user()->id) : true)
            {
                return Redirect::back()->withErrors(['Conteúdo não encontrado!']);
            }
            else
            {
                Session::flash('previewMode', true);
            }
        }

        if(Matricula::where([['user_id', '=', Auth::user()->id], ['curso_id', '=', $curso->id]])->first() == null)
        {
            return redirect()->route('curso.matricular', ['idCurso' => $curso->id]);
        }

        if(count($curso->aulas) == 0)
        {
            return Redirect::back()->withErrors(['Este item ainda não possui nenhum Conteúdo!']);
        }

        $conteudosCompletos = 0;

        foreach ($curso->aulas as $key => $aula)
        {
            // $aula->conteudos = Conteudo::where([['curso_id', '=', $curso->id], ['aula_id', '=', $aula->id]])->orderBy('ordem')->get();
            $aula->conteudos = Conteudo::with('conteudos_aula')
                ->whereHas('conteudos_aula', function ($query) use ($curso, $aula) {
                    $query->where([['curso_id', '=', $curso->id], ['aula_id', '=', $aula->id]]);
                })
                //->orderBy('ordem', 'asc')
                ->get();

            $aula->conteudos = Conteudo::detalhado($aula->conteudos);

            $conteudosCompletos = 0;

            foreach ($aula->conteudos as $conteudo)
            {
                $conteudo->conteudo_completo = ConteudoCompleto::where([['conteudo_id', '=', $conteudo->id], ['aula_id', '=', $aula->id], ['curso_id', '=', $curso->id], ['user_id', '=', Auth::user()->id]])->first();

                $conteudo->correto = null;

                if($conteudo->conteudo_completo != null)
                    $conteudosCompletos ++;

                if($conteudo->tipo == 8)
                {
                    if(isset($conteudo->questoes) && count($conteudo->questoes)>0){
                        $tempCont = json_decode($conteudo->questoes[0]);

                        if($conteudo->conteudo_completo != null)
                            if($tempCont->resposta_correta == ConteudoCompleto::where([['conteudo_id', '=', $conteudo->id], ['aula_id', '=', $aula->id], ['curso_id', '=', $curso->id], ['user_id', '=', Auth::user()->id]])->first()->resposta)
                            {
                                $conteudo->correto = true;
                            }
                            else
                            {
                                $conteudo->correto = false;

                                $conteudosCompletos --;
                            }
                    }
                }
                elseif($conteudo->tipo == 7 || $conteudo->tipo == 10)
                {
                    if($conteudo->conteudo_completo != null)
                        if(ConteudoCompleto::where([['conteudo_id', '=', $conteudo->id], ['aula_id', '=', $aula->id], ['curso_id', '=', $curso->id], ['user_id', '=', Auth::user()->id]])->first()->correta === "1")
                        {
                            $conteudo->correto = true;
                        }
                        elseif(ConteudoCompleto::where([['conteudo_id', '=', $conteudo->id], ['aula_id', '=', $aula->id], ['curso_id', '=', $curso->id], ['user_id', '=', Auth::user()->id]])->first()->correta === "0")
                        {
                            $conteudo->correto = false;

                            $conteudosCompletos --;
                        }
                }
            }

            if($conteudosCompletos == count($aula->conteudos))
            {
                $aula->completa = true;
            }
            else
            {
                $aula->completa = false;
            }
        }

        $avaliouCurso = (AvaliacaoCurso::where([['user_id', '=', Auth::user()->id], ['curso_id', '=', $curso->id]])->first() != null);
        $avaliouProfessor = (AvaliacaoInstrutor::where([['user_id', '=', Auth::user()->id], ['instrutor_id', '=', $curso->user_id]])->first() != null);
        $avaliouEscola = (AvaliacaoEscola::where([['user_id', '=', Auth::user()->id], ['escola_id', '=', $curso->escola_id]])->first() != null);

        // if($conteudosCompletos >= Conteudo::where([['curso_id', '=', $curso->id], ['aula_id', '=', $aula->id]])->count())
        if($conteudosCompletos >= Conteudo::
            // whereHas('conteudo_aula', function ($query) use ($curso, $aula) {
            //     $query->where([['curso_id', '=', $curso->id], ['aula_id', '=', $aula->id]]);
            // })
            whereHas('conteudo_aula', function ($query) use ($curso) {
                $query->where([['curso_id', '=', $curso->id]]);
            })
                ->count())
        {
            $curso->completo = true;
        }
        else
        {
            $curso->completo = false;
        }

        $qtAulas = Aula::where('curso_id', '=', $curso->id)->count();

        if($curso->completo)
        {
            $ultimaAula = 0;
            $ultimoConteudo = 0;
        }
        else
        {
            foreach (Aula::where('curso_id', '=', $curso->id)->orderBy('id', 'asc')->get() as $key => $aula)
            {
                // $totalConteudosCurso = Conteudo::where([['curso_id', '=', $curso->id], ['obrigatorio', '=', '1'], ['aula_id', '=', $aula->id]])->count();
                $totalConteudosCurso = Conteudo::whereHas('conteudo_aula', function ($query) use ($curso, $aula) {
                    $query->where([['curso_id', '=', $curso->id], ['obrigatorio', '=', '1'], ['aula_id', '=', $aula->id]]);
                })->count();

                if(ConteudoCompleto::where([['user_id', '=', Auth::user()->id], ['curso_id', '=', $curso->id], ['aula_id', '=', $aula->id]])->count() >= $totalConteudosCurso)
                {
                    continue;
                }
                else
                {
                    if(\Request::has('aula') ? Aula::where([['curso_id', '=', $curso->id], ['id', '=', \Request::get('aula')]])->exists() : false)
                        $ultimaAula = \Request::get('aula');
                    else
                        $ultimaAula = $aula->id;

                    // $conteudos_curso = Conteudo::where([['curso_id', '=', $curso->id], ['aula_id', '=', $aula->id]])->orderBy('ordem', 'asc')->get();

                    $conteudos_curso = Conteudo::with('conteudos_aula')->whereHas('conteudos_aula', function ($query) use ($curso, $aula) {
                        $query->where([['curso_id', '=', $curso->id], ['aula_id', '=', $aula->id]]);
                    })
                        ->get()
                        ->sortBy('conteudos_aula.ordem');

                    foreach ($conteudos_curso as $key2 => $conteudo)
                    {
                        if(ConteudoCompleto::where([['user_id', '=', Auth::user()->id], ['curso_id', '=', $curso->id], ['aula_id', '=', $aula->id], ['conteudo_id', '=', $conteudo->id]])->first() != null)
                        {
                            continue;
                        }
                        else
                        {
                            if(\Request::has('conteudo'))
                            {
                                // $tem_conteudo = ConteudoAula::where([['curso_id', '=', $curso->id], ['aula_id', '=', $ultimaAula], ['id', '=', \Request::get('conteudo')]])->exists();
                                $tem_conteudo = ConteudoAula::where([['curso_id', '=', $curso->id], ['aula_id', '=', $ultimaAula], ['conteudo_id', '=', \Request::get('conteudo')]])->exists();

                                if($tem_conteudo)
                                {
                                    $ultimoConteudo = \Request::get('conteudo');
                                }
                                else
                                {
                                    $ultimoConteudo = $conteudo->id;
                                }
                            }
                            else
                            {
                                $ultimoConteudo = $conteudo->id;
                            }


                            // $qtConteudos = Conteudo::where([['curso_id', '=', $curso->id], ['aula_id', '=', $aula->id]])->orderBy('ordem', 'asc')->count();
                            $qtConteudos = Conteudo::with('conteudos_aula')->whereHas('conteudos_aula', function ($query) use ($curso, $aula) {
                                $query->where([['curso_id', '=', $curso->id], ['aula_id', '=', $aula->id]]);
                            })->count();

                            break;
                        }
                    }

                    break;
                }
            }
        }


        return view('play.curso')->with(compact('curso', 'ultimaAula', 'ultimoConteudo', 'avaliouCurso', 'avaliouProfessor', 'avaliouEscola'));
    }

    function checkMatricula($idCurso)
    {
        if(Curso::find($idCurso))
        {
            $curso = Curso::with('aulas')->find($idCurso);
        }
        else
        {
            return response()->json(['error' => 'Conteúdo não encontrado!']);
        }

        if(Matricula::where([['user_id', '=', Auth::user()->id], ['curso_id', '=', $curso->id]])->first() == null)
        {
            return response()->json(['error' => 'Matricula não encontrada']);
        }
    }

    function playGetAula($idCurso, $idAula)
    {
        $this->checkMatricula($idCurso);

        if(Aula::where([['id', '=', $idAula], ['curso_id', '=', $idCurso]])->first() != null)
        {
            $aula = Aula::where([['id', '=', $idAula], ['curso_id', '=', $idCurso]])->first()->withConteudos();

            return response()->json(['success'=> 'Resultado encontrado.', 'aula' => json_encode($aula)]);
        }
        else
        {
            return response()->json(['error' => 'Não encontrado']);
        }
    }


    function playGetConteudo($idCurso, $idAula, $idConteudo)
    {
        $this->checkMatricula($idCurso);

        if(Aula::where([['id', '=', $idAula], ['curso_id', '=', $idCurso]])->first() != null)
        {
            $aula = Aula::where([['id', '=', $idAula], ['curso_id', '=', $idCurso]])->first();

            // if(Conteudo::where([['id', '=', $idConteudo], ['aula_id', '=', $idAula], ['curso_id', '=', $idCurso]])->first() != null)
            if(Conteudo::where([['id', '=', $idConteudo]])->first() != null)
            {
                $aula->conteudo = Conteudo::where([['id', '=', $idConteudo]])->first();

                $aula->conteudo->completo = false;

                if(ConteudoCompleto::where([['conteudo_id', '=', $idConteudo], ['aula_id', '=', $idAula], ['curso_id', '=', $idCurso], ['user_id', '=', Auth::user()->id]])->first() != null)
                {
                    $aula->conteudo->completo = true;
                    $resposta = ConteudoCompleto::where([['conteudo_id', '=', $idConteudo], ['aula_id', '=', $idAula], ['curso_id', '=', $idCurso], ['user_id', '=', Auth::user()->id]])->first()->resposta;
                }

                if($aula->conteudo->tipo == 2)
                {
                    if(strpos($aula->conteudo->conteudo, "soundcloud.com") !== false)
                    {
                        $aula->conteudo->conteudo = '<iframe width="100%" height="166" scrolling="no" frameborder="no" allow="autoplay" src="https://w.soundcloud.com/player/?url=' . $aula->conteudo->conteudo . '&color=%236766a6&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false&show_teaser=true"></iframe>';
                    }
                    elseif(strpos($aula->conteudo->conteudo, "http") !== false || strpos($aula->conteudo->conteudo, "www") !== false)
                    {
                        $path = config('app.cdn').'/storage/' . $aula->conteudo->conteudo;

                        $aula->conteudo->conteudo = '<audio id="player" controls style="width: 100%;">
                            <source src="' . $path . '" type="audio/mp3">
                            Your browser does not support the audio element.
                        </audio>';

                        // Insert plyr script tag
                        $aula->conteudo->conteudo = $aula->conteudo->conteudo . '<script>
                            if(player != undefined)
                                player = new Plyr(\'#player\');
                            else
                                player = new Plyr(\'#player\');
                        </script>';
                    }
                    else
                    {
                        // $aula->conteudo->conteudo = '<audio id="player" controls style="width: 100%;">
                        //     <source src="' . route('curso.play.get-arquivo', ['idCurso' => $idCurso, 'idAula' => $idAula, 'idConteudo' => $idConteudo]) . '" type="audio/mp3">
                        //     Your browser does not support the audio element.
                        // </audio>';
                        #codigo comentado para testar o audio
                        $path = config('app.cdn').'/storage/' . $aula->conteudo->conteudo;

                        if($aula->conteudo->capa == null)
                        {
                            $capa = config('app.cdn').'/fr/imagens/audio.png';
                        }
                        else
                        {
                            $capa = config('app.cdn').'/storage/capa_audios/'.$aula->conteudo->capa;
                        }

                        $aula->conteudo->conteudo = '
                            <div id="player1" data-playerid="200" class="audioplayer-tobe is-single-player " style="width:100%; margin-top:10px; margin-bottom: 10px;" data-thumb="'.$capa.'" data-thumb_link="'.$capa.'" data-type="audio" data-source="'.$path.'" data-fakeplayer="#ap1"  >
                                <div class="meta-artist"></div>
                            </div>
                        ';

                        /*
                        $aula->conteudo->conteudo = '<audio id="player" controls style="width: 100%;">
                            <source src="' . $path . '" type="audio/mp3">
                            Your browser does not support the audio element.
                        </audio>';
                        // Insert plyr script tag
                        $aula->conteudo->conteudo = $aula->conteudo->conteudo . '<script>
                            if(player != undefined)
                                player = new Plyr(\'#player\');
                            else
                                player = new Plyr(\'#player\');
                        </script>';
                        */
                    }

                }
                else if($aula->conteudo->tipo == 3)
                {
                    // if(strpos($aula->conteudo->conteudo, "www") === false && strpos($aula->conteudo->conteudo, "http") === false)
                    // {
                    //     $filePath = 'uploads/cursos/' . $idCurso . '/arquivos/' . $aula->conteudo->conteudo;

                    //     if(\Storage::disk('gcs')->exists($filePath))
                    //     {
                    //         if(\Storage::disk('gcs')->getVisibility($filePath) == 'public')
                    //         {
                    //             \Storage::disk('gcs')->setVisibility($filePath, 'private');
                    //         }

                    //         $aula->conteudo->conteudo = \Storage::disk('gcs')->getAdapter()->getBucket()->object($filePath)->signedUrl(now()->addSeconds(120));

                    //         // $aula->conteudo->conteudo = \Storage::disk('gcs')->url($filePath);
                    //     }
                    // }

                    if(strpos($aula->conteudo->conteudo, "youtube") !== false || strpos($aula->conteudo->conteudo, "youtu.be") !== false)
                    {
                        if(strpos($aula->conteudo->conteudo, "youtu.be") !== false)
                        {
                            $aula->conteudo->conteudo = str_replace("youtu.be", "youtube.com/embed/", $aula->conteudo->conteudo);
                        }

                        if(strpos($aula->conteudo->conteudo, "/embed/") === false)
                        {
                            $aula->conteudo->conteudo = str_replace("/watch?v=", "/embed/", $aula->conteudo->conteudo);
                        }

                        if(strpos($aula->conteudo->conteudo, "&") !== false)
                        {
                            $aula->conteudo->conteudo = substr($aula->conteudo->conteudo, 0, strpos($aula->conteudo->conteudo, "&"));
                        }

                        $aula->conteudo->conteudo = '<iframe src="' . $aula->conteudo->conteudo . '" style="width: 100%; height: 41vw;" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen>
                        </iframe>';

                    }
                    elseif(strpos($aula->conteudo->conteudo, "fast.player.liquidplatform.com") !== false)
                    {

                        $aula->conteudo->conteudo = '<iframe src="' . $aula->conteudo->conteudo . '" style="width: 100%; height: 41vw;" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen>
                        </iframe>';

                    }
                    elseif(strpos($aula->conteudo->conteudo, "vimeo") !== false)
                    {
                        if(strpos($aula->conteudo->conteudo, "player.vimeo.com") === false)
                        {
                            // $aula->conteudo->conteudo = str_replace("vimeo.com/", "player.vimeo.com/video/", $aula->conteudo->conteudo);
                            $aula->conteudo->conteudo = str_replace("vimeo.com/", "", $aula->conteudo->conteudo);
                            $aula->conteudo->conteudo = str_replace("player.vimeo.com/video/", "", $aula->conteudo->conteudo);
                            $aula->conteudo->conteudo = str_replace("https://", "", $aula->conteudo->conteudo);
                            $aula->conteudo->conteudo = str_replace("http://", "", $aula->conteudo->conteudo);
                            $aula->conteudo->conteudo = str_replace("www.", "", $aula->conteudo->conteudo);

                            if(substr_count($aula->conteudo->conteudo, '/') > 0)
                            {
                                $aula->conteudo->conteudo = substr($aula->conteudo->conteudo, 0, strrpos($aula->conteudo->conteudo, '/'));
                            }

                            $aula->conteudo->conteudo = 'https://player.vimeo.com/video/' . $aula->conteudo->conteudo;
                        }

                        $aula->conteudo->conteudo = '<iframe src="' . $aula->conteudo->conteudo . '" style="width: 100%; height: 41vw;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen>
                        </iframe>';
                    }
                    elseif(strpos($aula->conteudo->conteudo, "http") !== false || strpos($aula->conteudo->conteudo, "www") !== false)
                    {
                        $aula->conteudo->conteudo = '<video id="player" controls style="width: 100%; height: 41vw;">
                            <source src="' . $aula->conteudo->conteudo . '" type="video/mp4">
                            Your browser does not support the video element.
                        </video>';

                        // Insert plyr script tag
                        $aula->conteudo->conteudo = $aula->conteudo->conteudo . '<script>
                            if(player != undefined)
                                player = new Plyr(\'#player\');
                            else
                                player = new Plyr(\'#player\');
                        </script>';
                    }
                    else
                    {
                        $path = config('app.cdn').'/storage/' . $aula->conteudo->conteudo;

                        $aula->conteudo->conteudo = '<video id="player" controls style="width: 100%; height: 41vw;">
                            <source src="' . $path . '" type="video/mp4">
                            Your browser does not support the video element.
                        </video>';

                        // Insert plyr script tag
                        $aula->conteudo->conteudo = $aula->conteudo->conteudo . '<script>
                            if(player != undefined)
                                player = new Plyr(\'#player\');
                            else
                                player = new Plyr(\'#player\');
                        </script>';
                    }
                }
                else if($aula->conteudo->tipo == 4)
                {
                    if(strpos($aula->conteudo->conteudo, "http") === false && strpos($aula->conteudo->conteudo, "www") === false)
                    {
                        $url_conteudo = config('app.cdn').'/storage/' . $aula->conteudo->conteudo;
                        // $url_conteudo = route('conteudo.play.get-arquivo', ['idConteudo' => $idConteudo]);
                    }
                    else
                    {
                        $url_conteudo = config('app.cdn').'/storage/' . $aula->conteudo->conteudo;
                        // $url_conteudo = $aula->conteudo->conteudo;
                    }

                    if (strpos($aula->conteudo->conteudo, ".ppt") !== false || strpos($aula->conteudo->conteudo, ".pptx") !== false)
                    {
                        $aula->conteudo->conteudo = '<iframe src="https://docs.google.com/viewer?url=' . $url_conteudo . '&embedded=true" style="width: 100%; height: 41vw;" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>';

                    }
                    elseif (strpos($aula->conteudo->conteudo, ".html") !== false)
                    {
                        $aula->conteudo->conteudo = '<iframe src="' . $url_conteudo . '" style="width: 100%; height: 41vw;" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen>
                        </iframe>';
                    }
                    else
                    {
                        $aula->conteudo->conteudo = '<object data="' . $url_conteudo . '" type="application/pdf" style="width: 100%; height: 41vw;">
                        </object>';
                    }

                    // if(strpos($aula->conteudo->conteudo, ".ppt") !== false)
                    // {
                    //     $aula->conteudo->conteudo = '<iframe src="https://view.officeapps.live.com/op/view.aspx?src=' . route('curso.play.get-arquivo', ['idCurso' => $idCurso, 'idAula' => $idAula, 'idConteudo' => $idConteudo]) . '" style="width: 100%; height: 41vw;" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen>
                    //     </iframe>';
                    // }
                    // elseif(strpos($aula->conteudo->conteudo, ".html") !== false)
                    // {
                    //     // $aula->conteudo->conteudo = '<iframe src="http://docs.google.com/gview?url=' . route('curso.play.get-arquivo', ['idCurso' => $idCurso, 'idAula' => $idAula, 'idConteudo' => $idConteudo]) . '&embedded=true" style="width: 100%; height: 41vw;" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen>
                    //     // </iframe>';
                    //     $aula->conteudo->conteudo = '<iframe src="' . route('curso.play.get-arquivo', ['idCurso' => $idCurso, 'idAula' => $idAula, 'idConteudo' => $idConteudo]) . '" style="width: 100%; height: 41vw;" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen>
                    //     </iframe>';
                    // }
                    // else
                    // {
                    //     $aula->conteudo->conteudo = '<object data="' . route('curso.play.get-arquivo', ['idCurso' => $idCurso, 'idAula' => $idAula, 'idConteudo' => $idConteudo]) . '" type="application/pdf" style="width: 100%; height: 41vw;">
                    //     </object>';
                    // }

                }
                else if($aula->conteudo->tipo == 5)
                {
                    $mensagensTransmissao = '';

                    foreach (MensagemTransmissao::with('user')->where([['curso_id', '=', $idCurso], ['aula_id', '=', $idAula], ['conteudo_id', '=', $idConteudo]])->orderBy('created_at', 'asc')->get() as $mensagem)
                    {
                        $mensagensTransmissao = $mensagensTransmissao . '<div id="divMensagem' . $mensagem->id . '" style="font-size: 1vw/*18px*/;color:  #BCBCBC;max-width: 100%;padding:  12px 8px;">
                            <div style="display: inline-block;border: 2px solid var(--primary-color);vertical-align: middle;margin: 0px;background: url(' . route('usuario.perfil.image', [$mensagem->user->id]) . ');width: 45px;height: 45px;background-size: cover !important;background-repeat: no-repeat !important;background-position: 50% 50% !important;border-radius: 50%;margin-right:  8px;">
                            </div>
                            <div style="vertical-align:  middle;display:  inline-block;max-width: calc(100% - 58px);text-align:  -webkit-left;color: var(--primary-color);font-weight: bold;">
                                ' . ucfirst($mensagem->user->name) . ':
                                <span style="color: #354353;">
                                    ' . $mensagem->mensagem . '
                                </span>
                            </div>
                        </div>';
                    }

                    $aula->conteudo->conteudo = '<video id="my_video_1" class="video-js vjs-default-skin" controls preload="auto" style="width: 70%; height: 41vw; display: inline-block;"
                    data-setup="">
                      <source src="' . $aula->conteudo->conteudo . '" type="application/x-mpegURL">
                    </video>
                    <div id="divMainChat" style="display:  inline-block;width: calc( 30% - 10px);vertical-align: top;text-align:  -webkit-center;height: 41vw;position: relative;transition: all .3s ease-in-out;">
                        <div style="width:  100%;height:  100%;background-color: #F9F9F9;">
                            <div style="padding: 14px 4px;color: white;font-size: 1.2vw;background-color: var(--primary-color);max-height: 51px;overflow: hidden;text-overflow:  ellipsis;white-space:  nowrap;text-transform:  uppercase;">
                                Chat de transmissão ao vivo
                            </div>
                            <div id="divConteudoMensagens" style="text-align:  -webkit-left;height: calc( 92% - 52px);overflow: auto;">
                                ' . $mensagensTransmissao . '
                            </div>
                            <div class="msb-reply" style="display: block;height: 8%;position: relative;border-top: 1px solid #eee;background: #F3F3F3;margin:  0px;box-shadow:  none;">
                                <textarea id="txtConteudoMensagem" placeholder="Envie uma mensagem." onkeyup="if(event.keyCode == 13 &amp;&amp; !event.shiftKey){enviarMensagemChatTransmissao();};" style="width: calc(100% - 50px);min-height: 10px;max-height: 100%;border: 0;padding: 10px 15px;resize: none;height: 60px;background: 0 0;font-size:  16px;vertical-align: middle;"></textarea>
                                <button class="btn bg-transparent border-0" onclick="enviarMensagemChatTransmissao();" style="color: var(--primary-color);vertical-align:middle;"><i class="fas fa-paper-plane"></i></button>
                            </div>
                        </div>
                    </div>
                    <script>
                        var player = videojs("my_video_1");
                        timestamp = "' . strtotime(date('Y-m-d H:i:s')) . '";
                    </script>';
                }
                else if($aula->conteudo->tipo == 6)
                {
                    $aula->conteudo->conteudo = '<div>
                        <h4>' . ucfirst($aula->conteudo->titulo) . '</h4>
                        <p>' . ucfirst($aula->conteudo->descricao) . '</p>
                        <a href="' . route('curso.play.get-arquivo', ['idCurso' => $idCurso, 'idAula' => $idAula, 'idConteudo' => $idConteudo]) . '" target="iframe_download" class="btn btn-primary btn-lg" >
                            <i class="fas fa-arrow-alt-circle-down mr-2"></i>
                            Clique para baixar o arquivo
                        </a>
                    </div>';
                }
                else if($aula->conteudo->tipo == 7)
                {
                    $tempCont = json_decode($aula->conteudo->questoes[0]);

                    if($aula->conteudo->completo)
                    {
                        if(ConteudoCompleto::where([['conteudo_id', '=', $idConteudo], ['aula_id', '=', $idAula], ['curso_id', '=', $idCurso], ['user_id', '=', Auth::user()->id]])->first()->correta == "1")
                        {
                            $aula->conteudo->correto = true;
                        }
                        elseif(ConteudoCompleto::where([['conteudo_id', '=', $idConteudo], ['aula_id', '=', $idAula], ['curso_id', '=', $idCurso], ['user_id', '=', Auth::user()->id]])->first()->correta == "0")
                        {
                            $aula->conteudo->correto = false;
                        }
                        else
                        {
                            $aula->conteudo->correto = null;
                        }
                    }
                    else
                    {

                        $aula->conteudo->correto = null;
                    }

                    if(isset($tempCont->pergunta))
                    {
                        $aula->conteudo->conteudo = '<div class="px-3 py-2">
                            <h2>' . ucfirst($tempCont->pergunta) . '</h2>';
                    }
                    else
                    {
                        $aula->conteudo->conteudo = '<div class="px-3 py-2">
                            <h2>Sem Título</h2>';
                    }

                    if($aula->conteudo->completo != null)
                    {
                        if($aula->conteudo->correto != null)
                        {
                            $aula->conteudo->conteudo = $aula->conteudo->conteudo . '
                            <div id="boxResposta" class="box-alternativa box-shadow px-4 py-4 rounded-10 my-3 ' . ($aula->conteudo->correto ? 'alternativa-correta' : 'alternativa-incorreta') . '">
                                <div class="form-group mb-3 text-left">
                                    <textarea class="form-control" name="resposta" id="txtRespostaDissertativa" rows="3" maxlength="1000" placeholder="Clique para digitar.">' . $resposta . '</textarea>
                                </div>
                            </div>';
                        }
                        else
                        {
                            $aula->conteudo->conteudo = $aula->conteudo->conteudo . '
                            <div id="boxResposta" class="box-alternativa box-shadow px-4 py-4 rounded-10 my-3">
                                <div class="form-group mb-3 text-left">
                                    <textarea class="form-control" name="resposta" id="txtRespostaDissertativa" rows="3" maxlength="1000" placeholder="Clique para digitar." readonly>' . $resposta . '</textarea>
                                </div>
                            </div>';
                        }
                    }
                    else
                    {
                        $aula->conteudo->conteudo = $aula->conteudo->conteudo . '
                        <div id="boxResposta" class="box-alternativa box-shadow px-4 py-4 rounded-10 my-3">
                            <div class="form-group mb-3 text-left">
                                <textarea class="form-control" name="resposta" id="txtRespostaDissertativa" rows="3" maxlength="1000" placeholder="Clique para digitar."></textarea>
                            </div>
                        </div>';
                    }

                    if($aula->conteudo->correto === null)
                    {
                        $aula->conteudo->conteudo .= '
                        <div id="divAguardandoCorrecao" class="p-3 ' . ($aula->conteudo->completo ? '' : 'd-none') . '">
                            <h4>
                                <i class="fas fa-clock fa-fw mr-2 fa-lg"></i>
                                Aguardando correção do professor.' . $aula->conteudo->correto .'
                            </h4>
                        </div>
                        <div id="divRespostaCorreta" class="p-3 d-none">
                            <h4 style="color: var(--primary-color);">
                                <i class="fas fa-check fa-fw mr-2 fa-lg"></i>
                                Parabéns, você acertou!
                            </h4>
                        </div>
                        <div id="divRespostaIncorreta" class="p-3 d-none">
                            <h4 style="color: #ee9164;">
                                <i class="fas fa-times fa-fw mr-2 fa-lg"></i>
                                Que pena, você não acertou! Tente novamente.
                            </h4>
                        </div>';

                        $aula->conteudo->conteudo .= '
                            <div class="text-right">
                                <button id="btnConfirmarResposta" type="button" onclick="confirmarResposta();" class="btn btn-primary btn-lg py-2 px-4 rounded-10 text-uppercase font-weight-bold text-truncate col-12 col-sm-auto ' . ($aula->conteudo->completo ? 'd-none' : '') . '" style="color: #525870; border: 3px solid var(--primary-color);">
                                    Confirmar
                                </button>
                                <button id="btnTentarNovamente" type="button" onclick="tentarNovamente();" class="btn btn-primary btn-lg py-2 px-4 rounded-10 text-uppercase font-weight-bold text-truncate col-12 col-sm-auto d-none" style="color: #525870; border: 3px solid var(--primary-color);">
                                    Tentar novamente
                                </button>
                            </div>
                        </div>';
                    }
                    else
                    {
                        $aula->conteudo->conteudo .= '
                        <div id="divAguardandoCorrecao" class="p-3 d-none">
                            <h4>
                                <i class="fas fa-clock fa-fw mr-2 fa-lg"></i>
                                Aguardando correção do professor.
                            </h4>
                        </div>
                        <div id="divRespostaCorreta" class="p-3 ' . ($aula->conteudo->correto ? '' : 'd-none') . '">
                            <h4 style="color: var(--primary-color);">
                                <i class="fas fa-check fa-fw mr-2 fa-lg"></i>
                                Parabéns, você acertou!
                            </h4>
                        </div>
                        <div id="divRespostaIncorreta" class="p-3 ' . ($aula->conteudo->correto ? 'd-none' : '') . '">
                            <h4 style="color: #ee9164;">
                                <i class="fas fa-times fa-fw mr-2 fa-lg"></i>
                                Que pena, você não acertou! Tente novamente.
                            </h4>
                        </div>';

                        $aula->conteudo->conteudo .= '
                            <div class="text-right">
                                <button id="btnConfirmarResposta" type="button" onclick="confirmarResposta();" class="btn btn-primary btn-lg py-2 px-4 rounded-10 text-uppercase font-weight-bold text-truncate col-12 col-sm-auto ' . ($aula->conteudo->completo ? 'd-none' : '') . '" style="color: #525870; border: 3px solid var(--primary-color);">
                                    Confirmar
                                </button>
                                <button id="btnTentarNovamente" type="button" onclick="tentarNovamente();" class="btn btn-primary btn-lg py-2 px-4 rounded-10 text-uppercase font-weight-bold text-truncate col-12 col-sm-auto ' . ($aula->conteudo->completo == false ? 'd-none' : '') . ($aula->conteudo->correto == true ? 'd-none' : '') . '" style="color: #525870; border: 3px solid var(--primary-color);">
                                    Tentar novamente
                                </button>
                            </div>
                        </div>';
                    }

                }
                else if($aula->conteudo->tipo == 8)
                {
                    $tempCont = json_decode($aula->conteudo->questoes[0]);

                    if($aula->conteudo->completo)
                    {
                        $aula->conteudo->correto = ($tempCont->resposta_correta == ($resposta));
                    }
                    else
                    {
                        $aula->conteudo->correto = null;
                    }

                    $aula->conteudo->conteudo = '<div class="px-3 py-2">
                        <h2>' . ucfirst($tempCont->pergunta) . '</h2>';

                    foreach (json_decode($tempCont->alternativas) as $key => $alternativa)
                    {
                        $aula->conteudo->conteudo = $aula->conteudo->conteudo . '
                        <div id="boxAlternativa' . ($key) .'" class="box-alternativa box-shadow px-4 py-4 rounded-10 my-3 ' . ($aula->conteudo->completo ? ($resposta == ($key) ? ($aula->conteudo->correto ? 'alternativa-correta' : 'alternativa-incorreta') : 'alternativa-desativada') : '') . '">
                            <div class="custom-control custom-radio h4">
                                <input type="radio" id="alternativa' . ($key) .'" name="alternativas" onchange="selecionarAlternativa(this.id);" class="custom-control-input" ' . ($aula->conteudo->completo ? ($resposta == ($key) ? 'checked' : '' ) : '') . '>
                                <label class="custom-control-label pl-4 d-block" for="alternativa' . ($key) .'">' . $alternativa . '</label>
                            </div>
                        </div>';
                    }

                    $aula->conteudo->conteudo .= '
                        <div id="divRespostaCorreta" class="p-3 ' . ($aula->conteudo->completo ? ($aula->conteudo->correto ? '' : 'd-none') : 'd-none') . '">
                            <h4 style="color: var(--primary-color);">
                                <i class="fas fa-check fa-fw mr-2 fa-lg"></i>
                                Parabéns, você acertou!
                            </h4>
                        </div>
                        <div id="divRespostaIncorreta" class="p-3 ' . ($aula->conteudo->completo ? ($aula->conteudo->correto ? 'd-none' : '') : 'd-none') . '">
                            <h4 style="color: #ee9164;">
                                <i class="fas fa-times fa-fw mr-2 fa-lg"></i>
                                Que pena, você não acertou! Tente novamente.
                            </h4>
                        </div>
                        <div class="text-right">
                            <button id="btnConfirmarResposta" type="button" onclick="confirmarResposta();" class="btn btn-primary btn-lg py-2 px-4 rounded-10 text-uppercase font-weight-bold text-truncate col-12 col-sm-auto ' . ($aula->conteudo->completo ? 'd-none' : '') . '" style="color: #525870; border: 3px solid var(--primary-color);">
                                Confirmar
                            </button>
                            <button id="btnTentarNovamente" type="button" onclick="tentarNovamente();" class="btn btn-primary btn-lg py-2 px-4 rounded-10 text-uppercase font-weight-bold text-truncate col-12 col-sm-auto ' . ($aula->conteudo->completo == false ? 'd-none' : '') . ($aula->conteudo->correto == true ? 'd-none' : '') . '" style="color: #525870; border: 3px solid var(--primary-color);">
                                Tentar novamente
                            </button>
                        </div>
                    </div>';
                }
                else if($aula->conteudo->tipo == 9)
                {
                    $perguntas = json_decode($aula->conteudo->questoes);

                    $cmbPerguntas = '';

                    $tempConteudoCompleto = null;

                    if($aula->conteudo->completo)
                    {
                        $tempConteudoCompleto = ConteudoCompleto::where([['conteudo_id', '=', $idConteudo], ['aula_id', '=', $idAula], ['curso_id', '=', $idCurso], ['user_id', '=', Auth::user()->id]])->first();
                        $tempConteudoCompleto->resposta = json_decode($tempConteudoCompleto->resposta);
                        if($tempConteudoCompleto->correta !== null)
                        {
                            $tempConteudoCompleto->correta = json_decode($tempConteudoCompleto->correta);
                        }
                    }

                    for ($i=0; $i < count($perguntas); $i++)
                    {
                        $cmbPerguntas = $cmbPerguntas . '<option value="' . ($i + 1) . '" ' . ($i == 0 ? 'selected' : '') . '>Item ' . ($i + 1) . '</option>';
                    }

                    $aula->conteudo->conteudo = '<div class="px-3 py-2">
                    <div class="form-group mb-3">
                        <select class="custom-select form-control d-inline-block mr-2" id="cmbQuestaoAtual" onchange="mudouPerguntaProvaAtual();" style="width: auto; min-width: 200px;">
                            ' . $cmbPerguntas . '
                        </select>
                    </div>';

                    $divPerguntas = '';

                    $totalCorretas = 0;
                    $totalPontos = 0;

                    foreach ($perguntas as $key => $pergunta)
                    {
                        $divPerguntas = $divPerguntas . '<div id="divPergunta' . ($key + 1) . '" data-tipo="' . $pergunta->tipo . '" class="' . ($key > 0 ? 'd-none' : '') . '">';

                        $divPerguntas = $divPerguntas . '<h2>' . ucfirst($pergunta->pergunta) . ' <small class="text-muted mr-3">(Peso: ' . $pergunta->peso . ')</small> ' . ($key + 1) . '/' . count($perguntas) . '</h2>';

                        $pergunta->correta = null;
                        if($tempConteudoCompleto != null)
                        {
                            if($tempConteudoCompleto->correta !== null)
                            {
                                if($tempConteudoCompleto->correta[$key] == "1")
                                {
                                    $pergunta->correta = true;
                                    $totalCorretas ++;
                                    $totalPontos += $pergunta->peso;
                                }
                                else
                                {
                                    $pergunta->correta = false;
                                }
                            }
                        }

                        if($pergunta->tipo == 1)
                        {
                            $divPerguntas = $divPerguntas . '
                            <div id="boxResposta" class="box-alternativa box-shadow px-4 py-4 rounded-10 my-3 ' . ($aula->conteudo->completo ? ($pergunta->correta !== null ? ($pergunta->correta === true ? 'alternativa-correta' : 'alternativa-incorreta') : '') : '') . '">
                                <div class="form-group mb-3 text-left">
                                    <textarea class="form-control" name="resposta" id="txtRespostaDissertativa" rows="3" maxlength="1000" placeholder="Clique para digitar." ' . ($aula->conteudo->completo ? 'readonly' : '') . '>' . ($aula->conteudo->completo ? $tempConteudoCompleto->resposta[$key] : '') . '</textarea>
                                </div>
                            </div>';
                        }
                        else
                        {
                            foreach (json_decode($pergunta->alternativas) as $key2 => $alternativa)
                            {
                                $divPerguntas = $divPerguntas . '
                                <div id="boxAlternativa' . ($key2 + 1) .'" class="box-alternativa box-shadow px-4 py-4 rounded-10 my-3 ' . ($aula->conteudo->completo ? 'alternativa-desativada' : '') . ' ' . ($aula->conteudo->completo ? (($key2 + 1) == $tempConteudoCompleto->resposta[$key] ? ($pergunta->correta !== null ? ($pergunta->correta === true ? 'alternativa-correta' : 'alternativa-incorreta') : '') : '') : '') . '">
                                    <div class="custom-control custom-radio h4">
                                        <input type="radio" id="alternativa' . ($key + 1) .'-' . ($key2 + 1) .'" class="custom-control-input" ' . ($aula->conteudo->completo ? 'readonly' : '') . ' ' . ($aula->conteudo->completo ? (($key2 + 1) == $tempConteudoCompleto->resposta[$key] ? 'checked' : '') : '') . '>
                                        <label class="custom-control-label pl-4 d-block" for="alternativa' . ($key + 1) .'-' . ($key2 + 1) .'">' . $alternativa .  '</label>
                                    </div>
                                </div>';
                            }
                        }

                        $divPerguntas = $divPerguntas . '</div>';
                    }

                    $aula->conteudo->conteudo = $aula->conteudo->conteudo . '<div id="divPerguntas">
                        ' . $divPerguntas . '
                    </div>';

                    $aula->conteudo->conteudo .= '
                        <div id="divAguardandoCorrecao" class="p-3 ' . ($aula->conteudo->completo ? $tempConteudoCompleto->correta !== null ? 'd-none' : '' : 'd-none') . '">
                            <h4>
                                <i class="fas fa-clock fa-fw mr-2 fa-lg"></i>
                                Aguardando correção do professor.
                            </h4>
                        </div>
                        <div id="divRespostaCorreta" class="p-3 ' . ($aula->conteudo->completo ? $tempConteudoCompleto->correta !== null ? '' : 'd-none' : 'd-none') . '">
                            <h4 style="color: var(--primary-color);">
                                <i class="fas fa-check fa-fw mr-2 fa-lg"></i>
                                Você concluiu! Sua pontuação total foi de: ' . $totalPontos . ' e acertou ' . $totalCorretas . ' de ' . count($perguntas) . '.
                            </h4>
                        </div>
                        <div id="divRespostaIncorreta" class="p-3 d-none">
                            <h4 style="color: #ee9164;">
                                <i class="fas fa-times fa-fw mr-2 fa-lg"></i>
                                Que pena, você não acertou! Tente novamente.
                            </h4>
                        </div>
                        <div class="text-right">
                            <button id="btnConfirmarResposta" type="button" onclick="confirmarRespostaProva();" class="btn btn-primary btn-lg py-2 px-4 rounded-10 text-uppercase font-weight-bold text-truncate col-12 col-sm-auto ' . ($aula->conteudo->completo ? 'd-none' : '') . '" style="color: #525870; border: 3px solid var(--primary-color);">
                                Confirmar e prosseguir
                            </button>
                            <button id="btnTentarNovamente" type="button" onclick="tentarNovamenteProva();" class="btn btn-primary btn-lg py-2 px-4 rounded-10 text-uppercase font-weight-bold text-truncate col-12 col-sm-auto ' . ($aula->conteudo->completo && $aula->conteudo->correto === true ? '' : 'd-none') . '" style="color: #525870; border: 3px solid var(--primary-color);">
                                Recomeçar a prova
                            </button>
                        </div>
                    </div>';

                }
                else if($aula->conteudo->tipo == 10)
                {
                    if($aula->conteudo->completo)
                    {
                        if(ConteudoCompleto::where([['conteudo_id', '=', $idConteudo], ['aula_id', '=', $idAula], ['curso_id', '=', $idCurso], ['user_id', '=', Auth::user()->id]])->first()->correta === "1")
                            $aula->conteudo->correto = true;
                        elseif(ConteudoCompleto::where([['conteudo_id', '=', $idConteudo], ['aula_id', '=', $idAula], ['curso_id', '=', $idCurso], ['user_id', '=', Auth::user()->id]])->first()->correta === "0")
                            $aula->conteudo->correto = false;
                        else
                            $aula->conteudo->correto = null;
                    }
                    else
                    {
                        $aula->conteudo->correto = null;
                    }

                    $aula->conteudo->conteudo = '<div class="px-3 py-2">
                        <h2>' . ($aula->conteudo->conteudo) . '</h2>';

                    if($aula->conteudo->completo != null)
                    {
                        $idResposta = ConteudoCompleto::where([['conteudo_id', '=', $idConteudo], ['aula_id', '=', $idAula], ['curso_id', '=', $idCurso], ['user_id', '=', Auth::user()->id]])->first()->id;

                        if($aula->conteudo->correto != null)
                        {
                            $aula->conteudo->conteudo = $aula->conteudo->conteudo . '
                            <div id="boxResposta" class="box-alternativa box-shadow px-4 py-4 rounded-10 my-3 w-auto d-inline-block ' . ($aula->conteudo->correto ? 'alternativa-correta' : 'alternativa-incorreta') . '" style="pointer-events: initial;">
                                <a href="' . route('gestao.entregaveis-arquivo', ['idResposta' => $idResposta]) . '" target="_blank" class="btn btn-primary px-5 m-0 font-weight-bold">
                                    <i class="fas fa-paperclip fa-lg text-primary mr-2 align-middle"></i>
                                    Baixar arquivo
                                </a>
                            </div>';
                        }
                        else
                        {
                            $aula->conteudo->conteudo = $aula->conteudo->conteudo . '
                            <div id="boxResposta" class="box-alternativa box-shadow px-4 py-4 rounded-10 my-3 w-auto d-inline-block" style="pointer-events: initial;">
                                <a href="' . route('gestao.entregaveis-arquivo', ['idResposta' => $idResposta]) . '" target="_blank" class="btn btn-primary px-5 m-0 font-weight-bold">
                                    <i class="fas fa-paperclip fa-lg text-primary mr-2 align-middle"></i>
                                    Baixar arquivo
                                </a>
                            </div>';
                        }
                    }
                    else
                    {
                        $aula->conteudo->conteudo = $aula->conteudo->conteudo . '<form id="formEnviarEntregavel" method="POST" action="' . route('curso.play.enviar-entregavel', ['idCurso' => $idCurso, 'idAula' => $idAula, 'idConteudo' => $aula->conteudo->id]) . '" enctype="multipart/form-data" class="">
                            ' . csrf_field() .  '

                            <div id="divEnviando" class="text-center d-none">
                                <i class="fas fa-spinner fa-pulse fa-3x text-primary mb-3"></i>
                                <h4>Enviando</h4>
                            </div>

                            <div id="divEditar" class="form-page">

                                <div class="row">
                                    <div class="col my-auto">
                                        <label for="arquivo" class="btn btn-outline px-3 py-2 text-uppercase font-weight-bold position-relative m-0" style="border-width: 1px;border: 2px solid var(--primary-color);color: var(--primary-color);">
                                            <input type="file" class="custom-file-input" id="arquivo" name="arquivo" style="top: 0px;height:  100%;position:  absolute;left:  0px;" oninput="anexouArquivo(this);">
                                            <i class="fas fa-paperclip fa-lg text-primary mr-2 align-middle"></i>
                                            <span id="lblNomeArquivo">Clique para enviar seu arquivo</span>
                                        </label>
                                    </div>

                                    <div class="col my-auto text-right">
                                        <button id="btnEnviarEntregavel" type="submit" onclick="enviarEntregavel();" class="btn btn-primary px-5 m-0 font-weight-bold">Enviar</button>
                                    </div>
                                </div>

                            </div>

                        </form>';
                    }

                    if($aula->conteudo->correto === null)
                    {
                        $aula->conteudo->conteudo .= '
                        <div id="divAguardandoCorrecao" class="p-3 ' . ($aula->conteudo->completo ? '' : 'd-none') . '">
                            <h4>
                                <i class="fas fa-clock fa-fw mr-2 fa-lg"></i>
                                Aguardando correção do professor.
                            </h4>
                        </div>';
                    }
                    else
                    {
                        $aula->conteudo->conteudo .= '
                        <div id="divAguardandoCorrecao" class="p-3 d-none">
                            <h4>
                                <i class="fas fa-clock fa-fw mr-2 fa-lg"></i>
                                Aguardando correção do professora.
                            </h4>
                        </div>
                        <div id="divRespostaCorreta" class="p-3 ' . ($aula->conteudo->correto ? '' : 'd-none') . '">
                            <h4 style="color: var(--primary-color);">
                                <i class="fas fa-check fa-fw mr-2 fa-lg"></i>
                                Parabéns, você acertou!
                            </h4>
                        </div>
                        <div id="divRespostaIncorreta" class="p-3 ' . ($aula->conteudo->correto ? 'd-none' : '') . '">
                            <h4 style="color: #ee9164;">
                                <i class="fas fa-times fa-fw mr-2 fa-lg"></i>
                                Que pena, você não acertou! Tente novamente.
                            </h4>
                        </div>';

                        if(!$aula->conteudo->correto)
                        {
                            $aula->conteudo->conteudo .= '
                                <div class="text-right">
                                    <button id="btnTentarNovamente" type="button" onclick="tentarNovamente();" class="btn btn-primary btn-lg py-2 px-4 rounded-10 text-uppercase font-weight-bold text-truncate col-12 col-sm-auto ' . ($aula->conteudo->completo == false ? 'd-none' : '') . ($aula->conteudo->correto == true ? 'd-none' : '') . '" style="color: #525870; border: 3px solid var(--primary-color);">
                                        Enviar novo arquivo
                                    </button>
                                </div>
                            </div>';

                            $aula->conteudo->conteudo = $aula->conteudo->conteudo . '<form id="formEnviarEntregavel" method="POST" action="' . route('curso.play.enviar-entregavel', ['idCurso' => $idCurso, 'idAula' => $idAula, 'idConteudo' => $aula->conteudo->id]) . '" enctype="multipart/form-data" class="d-none">
                                ' . csrf_field() .  '

                                <div id="divEnviando" class="text-center d-none">
                                    <i class="fas fa-spinner fa-pulse fa-3x text-primary mb-3"></i>
                                    <h4>Enviando</h4>
                                </div>

                                <div id="divEditar" class="form-page">

                                    <div class="row">
                                        <div class="col my-auto">
                                            <label for="arquivo" class="btn btn-outline px-3 py-2 text-uppercase font-weight-bold position-relative m-0" style="border-width: 1px;border: 2px solid var(--primary-color);color: var(--primary-color);">
                                                <input type="file" class="custom-file-input" id="arquivo" name="arquivo" style="top: 0px;height:  100%;position:  absolute;left:  0px;" oninput="anexouArquivo(this);">
                                                <i class="fas fa-paperclip fa-lg text-primary mr-2 align-middle"></i>
                                                <span id="lblNomeArquivo">Clique para enviar seu arquivo</span>
                                            </label>
                                        </div>

                                        <div class="col my-auto text-right">
                                            <button id="btnEnviarEntregavel" type="submit" onclick="enviarEntregavel();" class="btn btn-primary px-5 m-0 font-weight-bold">Enviar</button>
                                        </div>
                                    </div>

                                </div>

                            </form>';
                        }

                    }
                }
                else if ($aula->conteudo->tipo == 11)
                {
                    $url_apostila = config('app.local') . '/uploads/apostilas/' . $aula->conteudo->id;

                    $aula->conteudo->conteudo = '<iframe src="' . config('app.local') . '/uploads/apostilas/' . $aula->conteudo->id . '/" style="width: 100%; height: 100vh;" frameborder="0" allowfullscreen>
                        </iframe>';


                }
                else if ($aula->conteudo->tipo == 15)
                {
                    if(strpos($aula->conteudo->conteudo, "http") === false && strpos($aula->conteudo->conteudo, "www") === false) {
                        $url_pdf = route('conteudo.play.get-arquivo', ['idConteudo' => $aula->conteudo->id]);
                    } else {
                        $url_pdf = $aula->conteudo->conteudo;
                    }

                    $aula->conteudo->conteudo = '<object data="' . $url_pdf . '" type="application/pdf" style="width: 100%; height: 31vw;">
                    </object>';
                }

                else if ($aula->conteudo->tipo == 21)
                {
                    $url = config('app.cdn').'/storage/';

                    $nomearq = '';


                    $files = Storage::disk('public')->allFiles('livrodigital/' . $aula->conteudo->id . '/');
                    $paginas = 0;
                    foreach ($files as $f) {
                        $arquivo = explode(".",$f);
                        $tam = count($arquivo);
                        if($arquivo[$tam - 1] == "pdf") {
                            $nomearq = $arquivo[$tam - 2];
                        }

                        if($arquivo[$tam - 1] == "jpg") {
                            $paginas++;
                        }
                    }

                    $download = $url.'/'.$nomearq.".pdf";
                    $url .= '/livrodigital/' . $aula->conteudo->id . '/';

                    $paginas--;

                    $aula->conteudo->conteudo = '<iframe name="interno" width="100%" height="700" src="'. url('colecao_livro/livro/'.$aula->conteudo->id.'?flip_sozinho=1') .'" style="border: 0; "></iframe>';
                }

                $aula->conteudo->comentarios = [];//ComentarioConteudo::with('user')->where([['conteudo_id', '=', $idConteudo], ['aula_id', '=', $idAula], ['curso_id', '=', $idCurso]])->orderBy('created_at', 'desc')->get();

                $aula->conteudo->qtAvaliacoesPositivas = AvaliacaoConteudo::where([
                    ['avaliacao', '=', '1'],
                    ['conteudo_id', '=', $idConteudo]
                ])
                    ->count();

                $aula->conteudo->qtAvaliacoesNegativas = AvaliacaoConteudo::where([
                    ['avaliacao', '=', '0'],
                    ['conteudo_id', '=', $idConteudo]
                ])->count();

                $aula->conteudo->minhaAvaliacao = AvaliacaoConteudo::where([
                    ['user_id', '=', Auth::user()->id],
                    ['conteudo_id', '=', $idConteudo]
                ])->first();

                if($aula->conteudo->apoio != null)
                {
                    $aula->conteudo->apoio = HelperClass::linkfy($aula->conteudo->apoio);
                    $aula->conteudo->apoio = strip_tags($aula->conteudo->apoio, '<a>');
                }

                if($aula->conteudo->fonte != null)
                {
                    $aula->conteudo->fonte = HelperClass::linkfy($aula->conteudo->fonte);
                    $aula->conteudo->fonte = strip_tags($aula->conteudo->fonte, '<a>');
                }

                if($aula->conteudo->autores != null)
                {
                    $aula->conteudo->autores = HelperClass::linkfy($aula->conteudo->autores);
                    $aula->conteudo->autores = strip_tags($aula->conteudo->autores, '<a>');
                }

                ConsumoConteudo::create([
                    'user_id' => Auth::user()->id,
                    'curso_id' => $idCurso,
                    'aula_id' => $idAula,
                    'conteudo_id' => $idConteudo,
                    'consumo' => $aula->conteudo->file_size
                ]);

                if(\Session::has('aulaCompleta') ? (\Session::get('aulaCompleta') == true) : false)
                {
                    return response()->json(['success'=> 'Aula e conteúdo encontrada.', 'aula' => json_encode($aula), 'aulaCompleta' => true]);
                }
                else
                {
                    return response()->json(['success'=> 'Aula e conteúdo encontrada.', 'aula' => json_encode($aula)]);
                }

            }
            else
            {
                return response()->json(['error' => 'Conteúdo não encontrado']);
            }
        }
        else
        {
            return response()->json(['error' => 'Conteúdo não encontrado']);
        }
    }

    function playGetProximoConteudo($idCurso, $idAula, $idConteudo)
    {
        $this->checkMatricula($idCurso);
        //Conta  quantidade de conteudos completo pelo usuário
        $conteudoCompleto = ConteudoCompleto::where([['user_id', '=', Auth::user()->id], ['curso_id', '=', $idCurso]])->count();
        //Conta a  quantidade de contudos no curso
        $qt_conteudos_curso_atual_completos = Conteudo::with('conteudos_aula')->whereHas('conteudos_aula', function ($query) use ($idCurso) {
            $query->where([['curso_id', '=', $idCurso], ['obrigatorio', '=', 1]]);
        })->count();

        if($idAula == 0)
        {
            $idAula = Aula::where([['curso_id', '=', $idCurso]])->first()->id;
        }

        if($idConteudo == 0)

        {
            // $idConteudo = Conteudo::where([['aula_id', '=', $idAula], ['curso_id', '=', $idCurso]])->first()->id;
            $idConteudo = Conteudo::whereHas('conteudos_aula', function ($query) use ($idAula, $idCurso) {
                $query->where([['aula_id', '=', $idAula], ['curso_id', '=', $idCurso]]);
            })->first()->id;
        }

        $proximaAula = $idAula;

        $curso_atual = Curso::find($idCurso);

        if(Aula::where([['id', '=', $idAula], ['curso_id', '=', $idCurso]])->first() != null)
        {
            $aula_atual = Aula::where([['id', '=', $idAula], ['curso_id', '=', $idCurso]])->first();

            if(Conteudo::where([['id', '=', $idConteudo]])->first() != null)
            {
                $tipoConteudo = Conteudo::where([['id', '=', $idConteudo]])->first()->tipo;

                if($tipoConteudo != 7 || $tipoConteudo != 8 || $tipoConteudo != 9 || $tipoConteudo != 10)
                {
                    if(ConteudoCompleto::where([['conteudo_id', '=', $idConteudo], ['aula_id', '=', $idAula], ['curso_id', '=', $idCurso], ['user_id', '=', Auth::user()->id]])->first() == null)
                    {
                        ConteudoCompleto::create([
                            'curso_id' => $idCurso,
                            'aula_id' => $idAula,
                            'conteudo_id' => $idConteudo,
                            'user_id' => Auth::user()->id
                        ]);

                        $qt_conteudos_aula_atual = ConteudoAula::where([['aula_id', '=', $idAula], ['curso_id', '=', $idCurso], ['obrigatorio', '=', 1]])->count();

                        $qt_conteudos_aula_atual_completos = ConteudoCompleto::where([['aula_id', '=', $idAula], ['curso_id', '=', $idCurso], ['user_id', '=', Auth::user()->id]])->count();

                        $aula_completa = ($qt_conteudos_aula_atual_completos >= $qt_conteudos_aula_atual);

                        if($aula_completa)
                        {
                            Notificacao::create([
                                'user_id' => Auth::user()->id,
                                'titulo' => 'Conteúdo concluído.',
                                'descricao' => 'Parabéns, você concluiu o conteúdo' . ( $aula_atual->titulo ),
                                'tipo' => 2,
                            ]);

                            Services\GamificacaoService::aulaConcluidaIncrement();
                        }

                        $qt_conteudos_curso_atual = ConteudoAula::where([['curso_id', '=', $idCurso], ['obrigatorio', '=', 1]])->count();

                        // $qt_conteudos_curso_atual_completos = ConteudoCompleto::where([['curso_id', '=', $idCurso], ['user_id', '=', Auth::user()->id]])->count();

                        // $curso_completo = ($qt_conteudos_curso_atual_completos >= $qt_conteudos_curso_atual);

                        return $conteudoCompleto;

                        if($conteudoCompleto < $qt_conteudos_curso_atual_completos)
                        {
                            return response()->json(['error' => 'Conteúdo incompleto!', 'cursoCompleto' => false]);
                        }
                        else{

                            if($curso_atual != null)
                            {
                                Notificacao::create([
                                    'user_id' => Auth::user()->id,
                                    'titulo' => 'Conteúdo concluído!',
                                    'descricao' => 'Parabéns, você concluiu o conteúdo' . ( $curso_atual->titulo ),
                                    'tipo' => 2,
                                ]);
                            }

                            Services\GamificacaoService::cursoConcluidoIncrement();
                        }

                        // if($curso_completo)
                        // {
                        //     if($curso_atual != null)
                        //     {
                        //         Notificacao::create([
                        //             'user_id' => Auth::user()->id,
                        //             'titulo' => "Curso concluído!",
                        //             'descricao' => "Parabéns, você concluiu o curso " . ( $curso_atual->titulo ),
                        //             'tipo' => 2,
                        //         ]);
                        //     }

                        //     Services\GamificacaoService::cursoConcluidoIncrement();
                        // }

                        if(BadgeUsuario::where([['badge_id', '=', 12], ['user_id', '=', Auth::user()->id]])->exists() == false)
                        {
                            BadgeUsuario::create([
                                'badge_id' => 12,
                                'user_id' => Auth::user()->id,
                            ]);
                        }
                    }
                }
            }
        }

        $conteudoAtual = Conteudo::with('conteudo_aula')
            ->whereHas('conteudo_aula', function ($query) use ($idCurso, $idAula) {
                $query->where([['curso_id', '=', $idCurso], ['aula_id', '=', $idAula]]);
            })
            ->where([['id', '=', $idConteudo]])
            ->first();

        if($conteudoAtual == null)
        {
            return response()->json(['error' => 'Conteúdo atual não encontrado!', "aulaAtual" => $idAula, "conteudoAtual" => $idConteudo]);
        }

        $proximoConteudo = Conteudo::with('conteudo_aula')->whereHas('conteudo_aula', function ($query) use ($idCurso, $idAula, $conteudoAtual) {
            $query->where([['curso_id', '=', $idCurso], ['aula_id', '=', $idAula], ['ordem', '>', $conteudoAtual->conteudo_aula->ordem]]);
            // })->pluck('id')->first();
        })
            ->get()
            ->sortByDesc('conteudos_aula.ordem')
            ->pluck('id')
            ->first();
        // if(Conteudo::where([['ordem', '>', $conteudoAtual->ordem]])->whereDoesntHave('conteudo_completo')->orderBy('ordem', 'asc')->pluck('id')->first() != null)
        // {
        // $proximoConteudo = Conteudo::where([['ordem', '>', $conteudoAtual->ordem]])->orderBy('ordem', 'asc')->pluck('id')->first();
        // }
        // else
        if($proximoConteudo == null)
        {
            $aulaCompleta = true;
            // ->whereHas('conteudo_aula', function ($query) use ($idCurso, $idAula) {
            //     $query->where([['curso_id', '=', $idCurso], ['aula_id', '=', $idAula]]);
            // })->get()->sortBy('conteudos_aula.ordem'));

            // foreach (Conteudo::where([['aula_id', '=', $idAula], ['curso_id', '=', $idCurso], ['obrigatorio', '=', 1]])->orderBy('ordem')->get() as $key => $value)
            foreach (Conteudo::with('conteudo_aula')
                         ->whereHas('conteudo_aula', function ($query) use ($idCurso, $idAula) {
                             $query->where([['curso_id', '=', $idCurso], ['aula_id', '=', $idAula]]);
                         })->get()->sortBy('conteudos_aula.ordem') as $key => $value)
            {
                if(ConteudoCompleto::where([['user_id', '=', Auth::user()->id], ['conteudo_id', '=', $value->id], ['aula_id', '=', $value->conteudo_aula->aula_id], ['curso_id', '=', $value->conteudo_aula->curso_id]])->first() == null)
                {
                    $proximaAula = $value->aula_id;
                    $proximoConteudo = $value->id;

                    $aulaCompleta = false;

                    break;
                }
                else
                {
                    continue;
                }
            }

            if($aulaCompleta == true)
            {
                if(Aula::where([['id', '>', $idAula], ['curso_id', '=', $idCurso]])->orderBy('id')->pluck('id')->first() != null)
                {
                    $proximaAula = Aula::where([['id', '>', $idAula], ['curso_id', '=', $idCurso]])->orderBy('id')->pluck('id')->first();

                    $proximoConteudo = Conteudo::with('conteudos_aula')->whereHas('conteudos_aula', function ($query) use ($proximaAula, $idCurso) {
                        $query->where([['aula_id', '=', $proximaAula], ['curso_id', '=', $idCurso]]);
                        // })->orderBy('ordem', 'asc')->pluck('id')->first();
                    })
                        ->get()
                        ->sortBy('conteudos_aula.ordem')
                        ->pluck('id')
                        ->first();

                    // if(Conteudo::where([['aula_id', '=', $proximaAula], ['curso_id', '=', $idCurso]])->orderBy('ordem', 'asc')->pluck('id')->first() != null)
                    // {
                    //     $proximoConteudo = Conteudo::where([['aula_id', '=', $proximaAula], ['curso_id', '=', $idCurso]])->orderBy('ordem', 'asc')->pluck('id')->first();
                    // }
                    // else
                    if($proximoConteudo == null)
                    {
                        if($conteudoCompleto >= $qt_conteudos_curso_atual_completos)
                        {

                            if(CursoCompleto::where([['curso_id', '=', $idCurso], ['user_id', '=', Auth::user()->id]])->first() == null)
                            {
                                CursoCompleto::create([
                                    'user_id' => Auth::user()->id,
                                    'curso_id' => $idCurso
                                ]);
                                $this->atualizarTrilhasMatriculasProgresso(Auth::user()->id, $idCurso);
                            }

                            return response()->json(['error' => 'Não há próximo conteúdo', 'cursoCompleto' => true]);

                        }
                        else{
                            return response()->json(['error' => 'Conclua todos os conteúdos para concluir o curso!']);
                        }
                    }
                }
                else
                {
                    if($conteudoCompleto >= $qt_conteudos_curso_atual_completos)
                    {
                        if(CursoCompleto::where([['curso_id', '=', $idCurso], ['user_id', '=', Auth::user()->id]])->first() == null)
                        {
                            CursoCompleto::create([
                                'user_id' => Auth::user()->id,
                                'curso_id' => $idCurso
                            ]);
                            $this->atualizarTrilhasMatriculasProgresso(Auth::user()->id, $idCurso);
                        }
                        return response()->json(['error' => 'Não há próximo conteúdo', 'cursoCompleto' => true]);

                        return response()->json(['error' => 'Não há próximo conteúdo.' , 'cursoCompleto' => true]);
                    }
                    else{
                        return response()->json(['error' => 'Conclua todos os conteúdos para concluir o curso!']);
                    }

                }
            }

            \Session::flash('aulaCompleta', $aulaCompleta);
        }

        return $this->playGetConteudo($idCurso, $proximaAula, $proximoConteudo);

    }

    public function postEnviarResposta($idCurso, $idAula, $idConteudo, Request $request)
    {
        if(Aula::where([['id', '=', $idAula], ['curso_id', '=', $idCurso]])->first() != null)
        {
            $conteudo = Conteudo::where([['id', '=', $idConteudo]])->first();

            if($conteudo != null)
            {

                if(ConteudoCompleto::where([['conteudo_id', '=', $idConteudo], ['user_id', '=', Auth::user()->id]])->first() == null)
                {
                    $tempCont = json_decode($conteudo->questoes[0]);


                    //echo '<pre>'; print_r($tempCont); die;

                    if(in_array($conteudo->tipo, [7, 9]))
                    {
                        $resposta = $request->resposta;
                    }
                    else
                    {
                        $resposta = $request->alternativa;
                    }

                    $conteudoCompleto = ConteudoCompleto::create([
                        'curso_id' => $idCurso,
                        'aula_id' => $idAula,
                        'conteudo_id' => $idConteudo,
                        'user_id' => Auth::user()->id,
                        'resposta' => $resposta,
                    ]);

                    if(in_array($conteudo->tipo, [7, 9]))
                    {
                        if($conteudo->tipo == 9)
                        {
                            $provaQuiz = true;

                            foreach ($tempCont as $pergunta)
                            {
                                if($pergunta->tipo == 1)
                                {
                                    $provaQuiz = false;
                                }
                            }


                            if($provaQuiz)
                            {
                                $correta = [];

                                $resposta = json_decode($resposta);

                                foreach ($tempCont as $key => $pergunta)
                                {
                                    if($pergunta->correta == $resposta[$key])
                                    {
                                        array_push($correta, 1);
                                    }
                                    else
                                    {
                                        array_push($correta, 0);
                                    }
                                }

                                $conteudoCompleto->update(['correta' => json_encode($correta)]);
                            }
                        }

                        return response()->json(['success' => 'Aguardando correção!']);
                    }
                    else
                    {
                        $conteudoCompleto->update(['correta' => $tempCont->resposta_correta]);


                        $myAlternativas = json_decode($tempCont->alternativas, true);



                        if($tempCont->resposta_correta == $request->alternativa)
                        {
                            return response()->json(['success' =>  'Correto!', 'correta' => true]);
                        }
                        else
                        {
                            return response()->json(['success' =>  'Incorreto!', 'correta' => false]);
                        }
                    }
                }
                else
                {
                    $tempCont = json_decode($conteudo->questoes[0]);

                    if(in_array($conteudo->tipo, [7, 9]))
                    {
                        $resposta = $request->resposta;
                    }
                    else
                    {
                        $resposta = $request->alternativa;

                    }

                    ConteudoCompleto::where([['conteudo_id', '=', $idConteudo], ['user_id', '=', Auth::user()->id]])->first()->update([
                        'resposta' => $resposta
                    ]);


                    if($conteudo->tipo == 7)
                    {
                        ConteudoCompleto::where([['conteudo_id', '=', $idConteudo], ['user_id', '=', Auth::user()->id]])->first()->update([
                            'correta' => null
                        ]);

                        return response()->json(['success' => 'Aguardando correção!']);
                    }
                    else
                    {
                        if($request->alternativa == $tempCont->resposta_correta)
                        {
                            return response()->json(['success' => 'Correto!', 'correta' => true]);
                        }
                        else
                        {
                            return response()->json(['success' => 'Incorreto!', 'correta' => false]);
                        }
                    }

                    return response()->json(['success' => 'Conteúdo já respondido!', 'correta' => ($resposta == $tempCont->resposta_correta)]);
                }
            }
            else
            {
                return response()->json(['error' => 'Conteúdo não encontrado!']);
            }
        }
        else
        {
            return response()->json(['error' => 'Conteúdo não encontrado!']);
        }
    }

    public function postEnviarEntregavel($idCurso, $idAula, $idConteudo, Request $request)
    {
        if(Aula::where([['id', '=', $idAula], ['curso_id', '=', $idCurso]])->first() != null)
        {
            if(Conteudo::where([['id', '=', $idConteudo]])->first() != null)
            {
                if(ConteudoCompleto::where([['conteudo_id', '=', $idConteudo], ['aula_id', '=', $idAula], ['curso_id', '=', $idCurso], ['user_id', '=', Auth::user()->id]])->first() == null)
                {
                    $conteudoCompleto = ConteudoCompleto::create([
                        'curso_id' => $idCurso,
                        'aula_id' => $idAula,
                        'conteudo_id' => $idConteudo,
                        'user_id' => Auth::user()->id
                    ]);
                }
                else
                {
                    $conteudoCompleto = ConteudoCompleto::where([['conteudo_id', '=', $idConteudo], ['aula_id', '=', $idAula], ['curso_id', '=', $idCurso], ['user_id', '=', Auth::user()->id]])->first();

                    $conteudoCompleto->update([
                        'correta' => null
                    ]);
                }
                if($conteudoCompleto->resposta != "")
                {
                    if(Storage::disk()->has(config('app.frStorage').'/uploads/entregavel/aluno/' . Auth::user()->id .'/' . $conteudoCompleto->resposta))
                    {
                        Storage::disk()->delete(config('app.frStorage').'/uploads/entregavel/aluno/' . Auth::user()->id .'/' . $conteudoCompleto->resposta);
                    }
                }

                if($request->arquivo != null)
                {
                    $originalName = mb_strtolower( $request->arquivo->getClientOriginalName(), 'utf-8' );

                    $fileExtension = File::extension($request->arquivo->getClientOriginalName());
                    $newFileNameArquivo =  md5( $request->arquivo->getClientOriginalName() . date("Y-m-d H:i:s") . time() ) . '.' . $fileExtension;

                    $pathArquivo = $request->arquivo->storeAs(config('app.frStorage').'/uploads/entregavel/aluno/' . Auth::user()->id .'/', $newFileNameArquivo, 'public_uploads');

                    if(!Storage::disk()->put($pathArquivo, file_get_contents($request->arquivo)))
                    {
                        Session::flash('error', 'Não foi possível fazer upload de seu anexo!');
                    }
                    else
                    {
                        $conteudoCompleto->update([
                            'resposta' => $newFileNameArquivo
                        ]);
                    }
                }
            }
        }

        return Redirect::back()->with('message', 'Entragável enviado com sucesso!');
    }

    #mostra arquivos pdf e imagem de upload
    function playGetArquivo($idCurso, $idAula, $idConteudo)
    {
        if(Curso::find($idCurso))
        {
            $curso = Curso::with('aulas')->find($idCurso);
        }
        else
        {
            return response()->json(['error' => 'Conteúdo não encontrado!']);
        }

        if(Aula::where([['id', '=', $idAula], ['curso_id', '=', $idCurso]])->first() != null)
        {
            if(Conteudo::where([['id', '=', $idConteudo]])->first() != null)
            {
                $conteudo = Conteudo::where([['id', '=', $idConteudo]])->first();

                if(strpos($conteudo->conteudo, ".ppt") === false && strpos($conteudo->conteudo, ".html") === false)
                {
                    if(!Auth::check())
                    {
                        return response()->json(['error' => 'Usuário não autenticado!']);
                    }

                    $tem_matricula = Matricula::where([['user_id', '=', Auth::user()->id], ['curso_id', '=', $curso->id]])->exists();

                    $is_admin = (Auth::user()->permissao == "Z");

                    $is_content_owner = (Auth::user()->id == $conteudo->user_id);

                    if( !$tem_matricula && !$is_admin && !$is_content_owner )
                    {
                        return response()->json(['error' => 'Matricula não encontrada']);
                    }
                }

                if(Storage::disk()->has(config('app.frStorage').'/'.$conteudo->conteudo))
                {

                    if(ConteudoCompleto::where([['conteudo_id', '=', $conteudo->id],
                            ['user_id', '=', Auth::user()->id]])->first() == null)
                    {
                        $conteudoCompleto = ConteudoCompleto::create([
                            'curso_id' => $idCurso,
                            'aula_id' => $idAula,
                            'conteudo_id' => $conteudo->id,
                            'user_id' => Auth::user()->id
                        ]);

                        if(!$conteudoCompleto)
                            return response()->json(['error' => 'Conteúdo não foi concluido!']);
                    }

                    $filePath = config('app.frStorage').'/'.$conteudo->conteudo;

                    if(strpos($conteudo->conteudo, ".jpg") !== false){
                        // $path = storage_path(). '/app/'.$filePath;
                        // return response()->download($path);
                        $path = config('app.cdn').'/storage/' . $conteudo->conteudo;

                        return $conteudo->conteudo = ' <img style="width: 100%; height: 41vw;"
                                                        src="'.$path.'">';
                    }else{
                        ob_end_clean();
                        return Storage::disk()->response($filePath);
                    }

                    // if(\Storage::disk('gcs')->exists($filePath))
                    // {
                    //     // dd(Storage::disk('gcs')->temporaryUrl( $filePath, now()->addSeconds(30) ));
                    //     // dd(\Storage::disk('gcs')->size($filePath) / 1000000); //Size in bytes / 1+e6 = size in mb
                    //     // dd(Carbon::createFromTimestamp(\Storage::disk('gcs')->lastModified($filePath))->toDateTimeString());
                    //     if(\Storage::disk('gcs')->getVisibility($filePath) == 'public')
                    //     {
                    //         \Storage::disk('gcs')->setVisibility($filePath, 'private');
                    //         // \Storage::disk('gcs')->setVisibility($filePath, 'public');
                    //     }
                    //     // Return temporary signed url
                    //     // \Storage::disk('gcs')->getAdapter()->getBucket()->object($filePath)->signedUrl(now()->addSeconds(120));
                    //     return \Storage::disk('gcs')->response($filePath);
                    // }
                    // else
                    // {
                    // Single byte streaming file response
                    // }
                    // MultiByte streaming file response
                    // return $this->streamFile(Storage::mimeType($filePath), storage_path('app/'. $filePath));
                }#Se o conteudo existe na pasta
                else
                {
                    return response()->json(['error' => 'Arquivo não encontrado']);
                }
            }#Fim se conteudo for igual a null
            else
            {
                return response()->json(['error' => 'Conteúdo não encontrado']);
            }
        }#Fim se retorno for igual null
        else
        {
            return response()->json(['error' => 'Conteúdo não encontrado']);
        }
    }#fim da função

    // Provide a streaming file with support for scrubbing
    private function streamFile( $contentType, $path )
    {
        $fullsize = filesize($path);
        $size = $fullsize;
        $stream = fopen($path, "r");
        $response_code = 200;
        $headers = array("Content-type" => $contentType);

        // Check for request for part of the stream
        $range = \Request::header('Range');
        if($range != null) {
            $eqPos = strpos($range, "=");
            $toPos = strpos($range, "-");
            $unit = substr($range, 0, $eqPos);
            $start = intval(substr($range, $eqPos+1, $toPos));
            $success = fseek($stream, $start);
            if($success == 0) {
                $size = $fullsize - $start;
                $response_code = 206;
                $headers["Accept-Ranges"] = $unit;
                $headers["Content-Range"] = $unit . " " . $start . "-" . ($fullsize-1) . "/" . $fullsize;
            }
        }

        $headers["Content-Length"] = $size;

        return \Response::stream(function () use ($stream) {
            fpassthru($stream);
        }, $response_code, $headers);
    }

    public function postEnviarAvaliacaoCurso($idCurso, Request $request)
    {
        if(Curso::find($idCurso) == null)
        {
            return response()->json(['error' => 'Conteúdo não encontrado!']);
        }

        // $qtConteudos = Conteudo::where([['curso_id', '=', $idCurso], ['obrigatorio', '=', 1]])->count();

        $qtConteudos = Conteudo::with('conteudos_aula')->whereHas('conteudos_aula', function ($query) use ($idCurso) {
            $query->where([['curso_id', '=', $idCurso], ['obrigatorio', '=', 1]]);
        })->count();

        if(ConteudoCompleto::where([['user_id', '=', Auth::user()->id], ['curso_id', '=', $idCurso]])->count() < $qtConteudos)
        {
            return response()->json(['error' => 'Conteúdo incompleto!', 'cursoCompleto' => false]);
        }

        if($request->comentario == null)
        {
            $request->comentario = '';
        }

        AvaliacaoCurso::updateOrCreate(
            [
                'user_id' => Auth::user()->id,
                'curso_id' => $idCurso
            ],
            ['avaliacao' => $request->avaliacao, 'descricao' => $request->comentario]
        );

        return response()->json(['success' => 'Avaliação enviada com sucesso!']);

    }

    public function postEnviarAvaliacaoEscola($escola_id, Request $request)
    {
        if(Escola::find($escola_id) == null)
        {
            return response()->json(['error' => 'Escola não encontrada!']);
        }

        // $qtConteudos = Conteudo::where([['curso_id', '=', $idCurso], ['obrigatorio', '=', 1]])->count();

        // $qtConteudos = Conteudo::with('conteudos_aula')->whereHas('conteudos_aula', function ($query) use ($idCurso) {
        //     $query->where([['curso_id', '=', $idCurso], ['obrigatorio', '=', 1]]);
        // })->count();

        // if(ConteudoCompleto::where([['user_id', '=', Auth::user()->id], ['curso_id', '=', $idCurso]])->count() < $qtConteudos)
        // {
        //     return response()->json(['error' => 'Curso incompleto!']);
        // }

        if($request->comentario == null)
        {
            $request->comentario = '';
        }

        AvaliacaoEscola::updateOrCreate(
            [
                'user_id' => Auth::user()->id,
                'escola_id' => $escola_id
            ],
            ['avaliacao' => $request->avaliacao, 'descricao' => $request->comentario]
        );

        return response()->json(['success' => 'Avaliação enviada com sucesso!']);

    }

    public function postEnviarAvaliacaoProfessor($idCurso, Request $request)
    {
        if(Curso::find($idCurso) == null)
        {
            return response()->json(['error' => 'Conteúdo não encontrado!']);
        }

        $idInstrutor = Curso::find($idCurso)->user_id;

        //Conta  quantidade de conteudos completo pelo usuário
        $conteudoCompleto = ConteudoCompleto::where([['user_id', '=', Auth::user()->id], ['curso_id', '=', $idCurso]])->count();
        //Conta a  quantidade de contudos no curso
        $qt_conteudos_curso_atual_completos = Conteudo::with('conteudos_aula')->whereHas('conteudos_aula', function ($query) use ($idCurso) {
            $query->where([['curso_id', '=', $idCurso], ['obrigatorio', '=', 1]]);
        })->count();

        if($conteudoCompleto < $qt_conteudos_curso_atual_completos)
        {
            return response()->json(['error' => 'Conteúdo incompleto!']);
        }

        if($request->comentario == null)
        {
            $request->comentario = '';
        }

        if(empty($request->avaliacao))
        {
            $request->avaliacao = 0;
        }
        AvaliacaoInstrutor::updateOrCreate(
            [
                'user_id' => Auth::user()->id,
                'instrutor_id' => $idInstrutor
            ],
            ['avaliacao' => $request->avaliacao, 'descricao' => $request->comentario]
        );

        return response()->json(['success' => 'Avaliação enviada com sucesso!']);

    }

    public function postEnviarAvaliacaoConteudo($idCurso, $idAula, $idConteudo, Request $request)
    {
        if(Aula::where([['id', '=', $idAula], ['curso_id', '=', $idCurso]])->first() != null)
        {
            if(Conteudo::where([['id', '=', $idConteudo]])->first() != null)
            {
                // if(AvaliacaoConteudo::updateOrCreate(
                //     [
                //         'user_id' => Auth::user()->id,
                //         'curso_id' => $idCurso,
                //         'aula_id' => $idAula,
                //         'conteudo_id' => $idConteudo,
                //     ],
                //     ['avaliacao' => $request->avaliacao]
                // )->exists())
                // {
                //     AvaliacaoConteudo::updateOrCreate(['user_id' => Auth::user()->id, 'curso_id' => $idCurso, 'aula_id' => $idAula, 'conteudo_id' => $idConteudo],
                //         ['avaliacao' => $request->avaliacao]
                //     );
                // }
                AvaliacaoConteudo::updateOrCreate(
                    [
                        'user_id' => Auth::user()->id,
                        // 'curso_id' => $idCurso,
                        // 'aula_id' => $idAula,
                        'conteudo_id' => $idConteudo,
                    ],
                    ['avaliacao' => $request->avaliacao]
                );

                return response()->json(['success' => 'Avaliação enviada com sucesso!']);
            }
            else
            {
                return response()->json(['error' => 'Conteúdo não encontrado!']);
            }
        }
        else
        {
            return response()->json(['error' => 'Conteúdo não encontrado!']);
        }
    }

    public function postEnviarComentarioConteudo($idCurso, $idAula, $idConteudo, Request $request)
    {
        if(Aula::where([['id', '=', $idAula], ['curso_id', '=', $idCurso]])->first() != null)
        {
            if(Conteudo::where([['id', '=', $idConteudo]])->first() != null)
            {
                $comentario = ComentarioConteudo::create([
                    'user_id' => Auth::user()->id,
                    'curso_id' => $idCurso,
                    'aula_id' => $idAula,
                    'conteudo_id' => $idConteudo,
                    'comentario' => $request->comentario
                ]);

                $comentario->user = Auth::user();

                return response()->json(['success' => 'Comentário enviado com sucesso!', 'comentario' => $comentario]);
            }
            else
            {
                return response()->json(['error' => 'Conteúdo não encontrado!']);
            }
        }
        else
        {
            return response()->json(['error' => 'Conteúdo não encontrado!']);
        }
    }

    public function getMensagensTransmissao($idCurso, $idAula, $idConteudo, Request $request)
    {
        $timestamp = $request->timestamp;

        if($timestamp == null)
        {
            if(MensagemTransmissao::orderBy('created_at', 'desc')->first() != null)
                $timestamp = MensagemTransmissao::orderBy('created_at', 'desc')->first()->created_at;
            else
                $timestamp = microtime(true);
        }

        $starttime = microtime(true);

        $mensagens = null;

        while(MensagemTransmissao::where('created_at', '>', $timestamp)->count() == 0)
        {
            usleep(15000);

            $endtime = microtime(true);

            $timediff = $endtime - $starttime;

            if($timediff > 30)
            {
                break;
            }
            elseif(MensagemTransmissao::where('created_at', '>', $timestamp)->count() > 0)
            {
                break;
            }
        }

        $mensagens = MensagemTransmissao::with('user')->where('created_at', '>', $timestamp)->get();

        $timestamp = MensagemTransmissao::orderBy('created_at', 'desc')->first()->created_at;

        return response()->json(['mensagens' => $mensagens, 'timestamp' => $timestamp]);
    }

    public function postEnviarMensagemTransmissao($idCurso, $idAula, $idConteudo, Request $request)
    {
        if(Aula::where([['id', '=', $idAula], ['curso_id', '=', $idCurso]])->first() != null)
        {
            if(Conteudo::where([['id', '=', $idConteudo], ['tipo', '=', 5]])->first() != null)
            {
                $mensagem = MensagemTransmissao::create([
                    'user_id' => Auth::user()->id,
                    'curso_id' => $idCurso,
                    'aula_id' => $idAula,
                    'conteudo_id' => $idConteudo,
                    'mensagem' => $request->mensagem
                ]);

                $mensagem->user = Auth::user();

                return response()->json(['success' => 'Mensagem enviada com sucesso!', 'mensagem' => $mensagem]);
            }
            else
            {
                return response()->json(['error' => 'Conteúdo não encontrado!']);
            }
        }
        else
        {
            return response()->json(['error' => 'Conteúdo não encontrado!']);
        }
    }

    public function getExcluirComentarioConteudo($idCurso, $idAula, $idConteudo, $idComentario)
    {
        $comentario = ComentarioConteudo::where([['id', '=', $idComentario], ['conteudo_id', '=', $idConteudo]])->first();

        if($comentario != null)
        {
            if($comentario->user_id == Auth::user()->id || Curso::find($idCurso)->user_id == Auth::user()->id || strtolower(Auth::user()->permissao) == "z")
            {
                $comentario->delete();

                return response()->json(['success' => 'Comentário excluido com sucesso!']);
            }
            else
            {
                return response()->json(['error' => 'Ação não permitida!']);
            }
        }
        else
        {
            return response()->json(['error' => 'Comentário não encontrado!']);
        }
    }

    public function getCertificado($idCurso)
    {
        if(Curso::find($idCurso) == null)
        {
            return response()->view('errors.404');
        }
        // if(ConteudoCompleto::where([['user_id', '=', Auth::user()->id], ['curso_id', '=', $idCurso]])->count() < Conteudo::where([['curso_id', '=', $idCurso], ['obrigatorio', '=', 1]])->count())
        if(CursoCompleto::where([['user_id', '=', Auth::user()->id], ['curso_id', '=', $idCurso]])->first() == null)
        {
            return response()->view('errors.404');
        }

        $curso = Curso::find($idCurso);

        $horas = $curso->aulas->count('duracao');

        $user = Auth::user();

        if($user == null || $curso == null)
        {
            return response()->view('errors.404');
        }
        //->created_at->format('d/m/Y')
        $dataConclusao = CursoCompleto::where([['user_id', '=', Auth::user()->id], ['curso_id', '=', $idCurso]])->first();

        $certificado = Certificado::where([
            'instituicao_id' => $curso->escola->instituicao->id
        ])->first();

        $search = ["[NOME_ALUNO]", "[DESC_CURSO]", "[NOME_PROFESSOR]", "[HORA_CURSO]"];
        $replace = [$user->name, $curso->titulo, $curso->user->name, $horas];

        $certificado->texto =  str_replace($search, $replace, $certificado->texto);

        return view('certificado')->with(compact('curso', 'user', 'dataConclusao', 'certificado'));
    }

    public function getCursoFiltrado($idEscola, Request $request)
    {
        $filtro = $request->filtro;
        $exceto = $request->exceto;

        $query = Curso::where('user_id', '=', Auth::user()->id);

        if ($filtro) {
            $query
                ->where(function ($q) use($filtro) {
                    $q->where('titulo', 'like', "%$filtro%")
                        ->orWhere('descricao', 'like', "%$filtro%");
                });
        }

        if ($exceto) {
            $query
                ->whereNotIn('id', $exceto);
        }

        /** TODO: Melhorar Js para trazer mais informações */
        return $query
            ->orderBy('titulo', 'ASC')
            ->limit(30)
            ->get();
    }

    public function atualizarTrilhasMatriculasProgresso($idUser, $idCurso)
    {
        // Todas as trilhas que possuem o curso concluido
        $trilhasUser = TrilhasMatricula::
        select('trilhas_matriculas.*')
            ->join('trilhas_cursos', 'trilhas_cursos.trilha_id', '=', 'trilhas_matriculas.trilha_id')
            ->where([
                ['trilhas_matriculas.user_id', '=', $idUser],
                ['trilhas_cursos.curso_id', '=', $idCurso]
            ])
            ->groupBy('trilhas_matriculas.id')
            ->get();

        foreach ($trilhasUser as $trilha) {
            $qtdCursos = TrilhasCurso::
            where('trilha_id', $trilha->trilha_id)
                ->count();

            $qtdConcluido = $trilha->qtd_concluido + 1;

            $progresso = ceil((100*$qtdConcluido)/$qtdCursos);

            $trilha->update([
                "progresso" => $progresso,
                "qtd_concluido" => $qtdCursos
            ]);
        }
    }

    public function searchCursos(){
        // Recupera o nome do curso para fazer a busca
        $search = \Request::input('filtro');
        $CursosNotIn = \Request::input('exceto');

        $cursos = Curso::query();
        $cursos = $cursos->where('status_id', '!=', 2);

        $cursos = Curso::curso_permissao($cursos, $search, $CursosNotIn);

        $cursos = $cursos->with('escola')->orderBy('cursos.titulo', 'ASC')->groupby('cursos.id')->get();

        return response()->json($cursos);
    }

    public function searchCicloEtapa(){

        $returnCicloEtapa = "";
        $id = \Request::input('ciclo');

        $cicloEtapas = CicloEtapa::where('ciclo_id', $id)->orderBy('titulo', 'ASC')->get();

        if (count($cicloEtapas) > 0) {
            foreach($cicloEtapas as $cicloEtapa){
                $returnCicloEtapa .= '<option value="'. $cicloEtapa->id.'">'. $cicloEtapa->titulo.'</option> ';
            }
        } else {
            $returnCicloEtapa .='<div class="form-group mb-3">
                        <div class="showCurso"><p class="font-weight-bold">Nenhum ano para essa etapa</p></div>
                    </div>';
        }

        return response()->json($returnCicloEtapa);

    }

    public function cursoLivre(){
        $filtro = \Request::input('pesquisa');

        $cursos = Curso::where([
            ['status_id', 1], ['status', 1],
            ['cursos_tipo_id', 2], ['escola_id', Auth::user()->escola_id]
        ]);

        if ($filtro) {
            $cursos->where('titulo', 'like', "%$filtro%")
                ->orWhere('descricao', 'like', "%$filtro%");
        }

        $cursos = $cursos->paginate(10);

        return view('play.cursos-livre')->with(compact('cursos'));
    }

}
