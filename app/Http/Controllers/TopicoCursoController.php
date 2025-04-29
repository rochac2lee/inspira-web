<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Redirect;
use Session;

use App\Models\Metrica;

use App\Models\Curso;
use App\Models\TopicoCurso;
use App\Models\ComentarioTopicoCurso;

use App\Models\AvaliacaoInstrutor;

class TopicoCursoController extends Controller
{
    public function index($curso_id)
    {
        $curso = Curso::with('escola')->find($curso_id);

        if($curso == null)
        {
            return redirect()->back()->withErrors('Conteúdo não encontrado!');
        }

        if(AvaliacaoInstrutor::where('instrutor_id', '=', $topico->user->id)->avg('avaliacao') > 0)
            $avaliacaoInstrutor = AvaliacaoInstrutor::where('instrutor_id', '=', $topico->user->id)->avg('avaliacao');
        else
            $avaliacaoInstrutor = '-';

        $topicos = TopicoCurso::where([['curso_id', '=', $curso_id]])
        ->orderBy('status', 'asc')
        ->orderBy('created_at', 'desc')
        ->get();
        // ->sortBy('status');

        foreach ($topicos as $topico)
        {
            $topico->qt_comentarios = ComentarioTopicoCurso::where([['topico_curso_id', '=', $topico->id]])->count();
        }

        // dd($topicos);

        return view('curso.topicos')->with(compact('topicos', 'curso', 'avaliacaoInstrutor'));
    }

    public function postNovoTopico($curso_id, Request $request)
    {
        if(Curso::find($curso_id) == null)
        {
            Redirect::back()->withErrors(['Conteúdo não encontrado!']);
        }
        else if(Curso::find($curso_id)->user_id != Auth::user()->id && (strtoupper(Auth::user()->permissao) != "P" && strtoupper(Auth::user()->permissao) != "G" && strtoupper(Auth::user()->permissao) != "Z"))
        {
            Redirect::back()->withErrors(['Você não tem permissão para realizar esta ação!']);
        }
        else
        {
            $topico = TopicoCurso::create([
                'curso_id' => $curso_id,
                'user_id' => Auth::user()->id,
                'titulo' => $request->titulo,
                'descricao' => $request->descricao
            ]);

            return Redirect::back()->with('message', 'Tópico enviada com sucesso!');
        }
    }

    public function topico($curso_id, $topico_curso_id)
    {
        $topico = TopicoCurso::with('curso', 'user', 'comentarios')->has('curso')->has('user')->find($topico_curso_id);

        // dd($topico);

        if($topico == null)
        {
            return redirect()->route('curso', $curso_id)->withErrors("Tópico não encontrado!");
        }

        if(AvaliacaoInstrutor::where('instrutor_id', '=', $topico->user->id)->avg('avaliacao') > 0)
            $avaliacaoInstrutor = AvaliacaoInstrutor::where('instrutor_id', '=', $topico->user->id)->avg('avaliacao');
        else
            $avaliacaoInstrutor = '-';

        $curso = Curso::find($curso_id);

        Metrica::create([
            'user_id' => Auth::check() ? Auth::user()->id : 0,
            'titulo' => 'Visualização tópico - ' . $topico->id
        ]);

        $topico->visualizacoes = Metrica::where('titulo', '=', 'Visualização tópico - ' . $topico->id)->pluck('user_id')->unique('user_id')->count();

        return view('curso.topico')->with(compact('curso', 'topico', 'avaliacaoInstrutor'));
    }

    public function postAtualizarTopico($curso_id, $topico_curso_id, Request $request)
    {
        // dd($topico_curso_id);

        if(TopicoCurso::find($topico_curso_id) == null)
        {
            return Redirect::back()->withErrors(['Tópico não encontrado!']);
        }
        else
        {
            TopicoCurso::find($topico_curso_id)->update([
                'status' => $request->status
            ]);

            return Redirect::back()->with('message', 'Tópico atualizado com sucesso!');
        }
    }

    public function postExcluirTopico($curso_id, $topico_curso_id)
    {
        if(TopicoCurso::find($topico_curso_id) == null)
        {
            return Redirect::back()->withErrors(['Tópico não encontrado!']);
        }
        else
        {
            TopicoCurso::find($topico_curso_id)->delete();

            return Redirect::back()->with('message', 'Tópico excluída com sucesso!');
        }
    }

    public function postEnviarComentarioTopico($curso_id, $topico_curso_id, Request $request)
    {
        if(TopicoCurso::find($topico_curso_id) == null)
        {
            Redirect::back()->withErrors(['Tópico não encontrado!']);
        }
        else
        {
            $comentario = ComentarioTopicoCurso::create([
                'topico_curso_id' => $topico_curso_id,
                'user_id' => Auth::user()->id,
                'conteudo' => $request->conteudo
            ]);

            return Redirect::back();//->with('message', 'Comentário enviado com sucesso!');
        }
    }

    public function postExcluirComentarioTopico($curso_id, $topico_curso_id, $comentario_id)
    {
        if(ComentarioTopicoCurso::find($comentario_id) == null)
        {
            return Redirect::back()->withErrors(['Comentário não encontrado!']);
        }
        else
        {
            ComentarioTopicoCurso::find($comentario_id)->delete();

            return Redirect::back();//->with('message', 'Comentário excluído com sucesso!');
        }
    }
}
