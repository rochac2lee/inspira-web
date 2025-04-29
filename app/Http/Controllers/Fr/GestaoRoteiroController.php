<?php

namespace App\Http\Controllers\Fr;

use App\Models\Disciplina;
use App\Http\Requests\Fr\RoteiroRequest;
use App\Models\Instituicao;
use App\Models\InstituicaoTipo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Services\Fr\RoteiroService;
use Illuminate\Support\Facades\Auth;
use Validator;

class GestaoRoteiroController extends Controller
{
    public function __construct(RoteiroService $roteiroService)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if(Auth::user()->permissao == 'A' || Auth::user()->permissao == 'R')
            {
                return back();
            }
            return $next($request);
        });
        $this->roteiroService = $roteiroService;
	}

    public function  teste(){
        $this->roteiroService->get(743,false,false, ['biblioteca'=>1]);
        return 1;
    }

	public function index(Request $request)
	{
        $busca = $request->all();
        $view = [
            'dados' => $this->roteiroService->getLista(20,$busca),
            'cicloEtapa' => $this->roteiroService->cicloEtapa(),
            'disciplina' => Disciplina::orderBy('titulo')->get(),

        ];
        return view('fr/roteiro/index', $view);
	}

    public function form(Request $request)
    {
        $view = [
            'disciplinas' => Disciplina::orderBy('titulo')->get(),
            'ciclo' => $this->roteiroService->cicloEtapa(),
            'instituicao' => Instituicao::orderBy('titulo')->get(),
            'tipoInstituicao' => InstituicaoTipo::orderBy('titulo')->get(),
        ];
        return view('fr/roteiro/form', $view);
    }

    public function add(RoteiroRequest $request)
    {
        $retorno = $this->roteiroService->inserir($request);

        if($retorno===true){
            return redirect('/gestao/roteiros')->with('certo', 'Roteiro cadastrado.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar cadastrar roteiro.');
        }
    }

    public function editar($id){
            $dados = $this->roteiroService->get($id);
            $view = [
                'disciplinas' => Disciplina::orderBy('titulo')->get(),
                'ciclo' => $this->roteiroService->cicloEtapa(),
                'instituicao' => Instituicao::orderBy('titulo')->get(),
                'tipoInstituicao' => InstituicaoTipo::orderBy('titulo')->get(),
                'dados' => $dados,
            ];
            return view('fr/roteiro/form', $view);
    }



    public function update(RoteiroRequest $request)
    {
        $retorno = $this->roteiroService->update($request);

        if($retorno===true){
            return redirect('/gestao/roteiros')->with('certo', 'Roteiro editado.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar editar roteiro.');
        }
    }

    public function excluir($id)
    {
        $retorno = $this->roteiroService->excluir($id);

        if($retorno===true){
            return redirect('/gestao/roteiros')->with('certo', 'Roteiro excluído.');
        }
        else{
            return redirect('/gestao/roteiros')->with('erro', 'Erro ao tentar excluir roteiro.');
        }
    }

    public function publicar($id, Request $request)
    {
        $retorno = $this->roteiroService->publicar($id, $request->all());

        if($retorno===true){
            return back()->with('certo', 'Roteiro publicado.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar publicar roteiro.');
        }
    }

    public function duplicar($id, Request $request)
    {
        $retorno = $this->roteiroService->duplicar($id, $request->all());

        if($retorno===true){
            return back()->with('certo', 'Roteiro publicado.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar publicar roteiro.');
        }
    }

    public function editarConteudo($id){
        $roteiro = $this->roteiroService->get($id);
        if(!$roteiro)
        {
            return back()->with('erro', 'Erro ao tentar encontrar roteiro para editar conteúdo.');
        }

        $view = [
            'roteiro' => $roteiro,
            'disciplinas' => Disciplina::orderBy('titulo')->get(),
            'ciclo' => $this->roteiroService->cicloEtapa(),
        ];
        return view('fr/roteiro/form_conteudo', $view);
    }

    public function addUpdateConteudos(Request $request){
        $roteiro = $this->roteiroService->get($request->input('curso_id'));
        if(!$roteiro)
        {
            return back()->with('erro', 'Erro ao tentar encontrar roteiro para editar conteúdo.');
        }
        $queryString = '';
        switch ($request->input('op')) {
            case 'novo_tema':
                if($this->inserirTema($request)){
                    $msg = 'Tema inserido com sucesso';
                }
                else{
                    return back()->with('erro','Erro ao tentar inserir Tema.');
                }
                break;
            case 'editar_tema':
                $queryString = '?tema='.$request->input('aula_id');
                if($this->editarTema($request)){
                    $msg = 'Tema editado com sucesso';
                }
                else{
                    return back()->with('erro','Erro ao tentar editar Tema.');
                }
                break;
        }

        return redirect('/gestao/roteiros/'.$request->input('curso_id').'/editar_conteudo'.$queryString)->with('certo',$msg);
    }

    private function inserirTema($request){
        $rules = [
            'titulo' => 'required',
            //'descricao' => 'required',
        ];
        $attributes = [
            'titulo' => 'Título',
            'descricao' => 'Descrição',
        ];
        Validator::make($request->all(), $rules, [], $attributes)->validate();
        return $this->roteiroService->inserirTema($request->all());
    }

    private function editarTema($request){
        $rules = [
            'titulo' => 'required',
            //'descricao' => 'required',
        ];
        $attributes = [
            'titulo' => 'Título',
            'descricao' => 'Descrição',
        ];
        Validator::make($request->all(), $rules, [], $attributes)->validate();
        return $this->roteiroService->editarTema($request->all());
    }

    public function getTemaConteudoAjax(Request $request){
        $view = [
            'curso_id'  => $request->input('curso_id'),
            'tema'      => $this->roteiroService->getTema($request->all()),
        ];
        $retorno = view('fr.roteiro.conteudo.conteudo',$view)->render();
        return response()->json( $retorno, 200 );
    }

    public function getTemaAjax(Request $request){
        $tema = $this->roteiroService->getTema($request->all());

        return response()->json( $tema->toArray(), 200 );
    }

    public function ordemTema(Request $request){
        $ordem = $this->roteiroService->ordemTema($request->all());
        $status = 200;
        if(!$ordem)
        {
            $status = 500;
        }
        return response()->json( $ordem, $status );
    }

    public function excluirTema($cursoId, $temaId){
        $retorno = $this->roteiroService->excluirTema($cursoId, $temaId);

        if($retorno===true){
            return redirect('/gestao/roteiros/'.$cursoId.'/editar_conteudo')->with('certo', 'Tema excluído.');
        }
        else{
            return redirect('/gestao/roteiros/'.$cursoId.'/editar_conteudo?tema='.$temaId)->with('erro', 'Erro ao tentar excluir tema.');
        }
    }

    public function duplicarTema($cursoId, $temaId){
        $retorno = $this->roteiroService->duplicarTema($cursoId, $temaId);

        if($retorno===true){
            return redirect('/gestao/roteiros/'.$cursoId.'/editar_conteudo')->with('certo', 'Tema duplicado.');
        }
        else{
            return redirect('/gestao/roteiros/'.$cursoId.'/editar_conteudo?tema='.$temaId)->with('erro', 'Erro ao tentar duplicar tema.');
        }
    }

    public function iniciarRoteiro($roteiroId, Request $request ){
        $dados = [];
        if($request->input('biblioteca') == 1){
            $dados['biblioteca'] = 1;
            session(['RoteiroBiblioteca'.$roteiroId=>1]);
        }
        $roteiro = $this->roteiroService->get($roteiroId,false,false,$dados);
        if(!$roteiro)
        {
            return back()->with('erro', 'Erro ao tentar encontrar roteiro para editar conteúdo.');
        }

        $view = [
            'roteiro' => $roteiro,
            'executar' => false,
        ];

        return view('fr.roteiro.exibe.index',$view);
    }

    public function realizarRoteiro($roteiroId){
        $dados = [];
        if(session('RoteiroBiblioteca'.$roteiroId) == 1){
            $dados['biblioteca'] = 1;
        }
        $roteiro = $this->roteiroService->get($roteiroId, false, false, $dados);
        if(!$roteiro)
        {
            return back()->with('erro', 'Erro ao tentar encontrar roteiro para editar conteúdo.');
        }

        $view = [
            'roteiro' => $roteiro,
        ];

        return view('fr.roteiro.exibe.realizar',$view);
    }

    public function getRoteiroAjax(Request $request)
    {
        $listaRoteiro = $this->roteirosParaTrilhas($request);

        if($listaRoteiro!==false){
            return response()->json( $listaRoteiro, 200 );
        }
        else
        {
            return response()->json( false, 400 );
        }
    }

    public function getRoteiroSelecionadosAjax(Request $request)
    {
        $view = [
            'dados' => $this->roteiroService->getLista(1000,['id' => $request->input('notId')]),
        ];
        $roteirosSelecionados = view('fr.trilhas.lista_roteiro_trilha',$view)->render();

        if($roteirosSelecionados!==false){
            return response()->json( $roteirosSelecionados, 200 );
        }
        else
        {
            return response()->json( false, 400 );
        }
    }

    private function roteirosParaTrilhas($request)
    {
        $dados = $request->all();
        $dados['publicado'] = 1;
        $roteiros = $this->roteiroService->getLista(30,$dados);
        $view = [
            'dados' => $roteiros,
        ];

        return [
            'roteiro' => view('fr.trilhas.lista_roteiro_trilha',$view)->render(),
            'total' => $roteiros->total(),
            'exibindo' => count($roteiros),
        ];
    }



}
