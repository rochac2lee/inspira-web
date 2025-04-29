<?php

namespace App\Http\Controllers\Fr;

use App\Models\Categoria;
use App\Models\Disciplina;
use App\Http\Requests\Fr\AudioCastRequest;
use App\Http\Requests\Fr\AlbumCastRequest;
use App\Http\Requests\Fr\PlaylistCastRequest;
use App\Services\Fr\CastService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class GestaoCastController extends Controller
{
    public function __construct(CastService $castService)
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            if(Auth::user()->permissao == 'A' )
            {
                return back();
            }
            return $next($request);
        });

        $this->castService = $castService;
    }

    public function index(Request $request){
        $view=[
            'dados'         => $this->castService->get(20, $request->all()),
            'categoria'     => Categoria::where('tipo',5)->orderBy('titulo')->get(),
            'cicloEtapa'    => $this->castService->cicloEtapa(),
            'disciplina'    => Disciplina::orderBy('titulo')->get(),
        ];
        return view('fr/cast/index_admin', $view);
    }

    public function add(AudioCastRequest $request){
        $retorno = $this->castService->inserir($request);

        if($retorno===true){
            return redirect('/gestao/cast')->with('certo', 'Áudio cadastrado.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar cadastrar áudio.');
        }
    }

    public function excluir($id)
    {
        $retorno = $this->castService->excluir($id);

        if($retorno){
            return back()->with('certo', 'Áudio excluído.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar excluir áudio.');
        }
    }

    public function getAjax(Request $request)
    {
        $retorno = $this->castService->getForm($request->input('id'));
        if($retorno){
            return response()->json($retorno);
        }
        else
        {
            return response()->json( $retorno, 400 );
        }
    }

    public function editar(AudioCastRequest $request){
        $retorno = $this->castService->editar($request);

        if($retorno===true){
            return redirect('/gestao/cast')->with('certo', 'Áudio editado.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar editar áudio.');
        }
    }

    //// gestão de álbuns
    public function formAlbum(){
        $view = [
            'categoria'     => Categoria::where('tipo',5)->orderBy('titulo')->get(),
            'cicloEtapa'    => $this->castService->cicloEtapa(),
            'disciplina'    => Disciplina::orderBy('titulo')->get(),
        ];
        return view('fr/cast/form_album',$view);
    }

    public function addAlbum(AlbumCastRequest $request){
        $retorno = $this->castService->inserirAlbum($request);

        if($retorno===true){
            return redirect('/gestao/cast?tipo=1')->with('certo', 'Álbum cadastrado.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar cadastrar álbum.');
        }
    }

    public function excluirAlbum($id)
    {
        $retorno = $this->castService->excluirAlbum($id);

        if($retorno){
            return back()->with('certo', 'Álbum excluído.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar excluir álbum.');
        }
    }

    public function getAlbum($id)
    {
        $album = $this->castService->getAlbum($id);
        $sel['selecionados'] ='';
        foreach($album->audios as $a){
            $sel['selecionados'] .= $a->conteudo_id.',';
        }
        $view = [
            'dados'         => $album,
            'categoria'     => Categoria::where('tipo',5)->orderBy('titulo')->get(),
            'cicloEtapa'    => $this->castService->cicloEtapa(),
            'disciplina'    => Disciplina::orderBy('titulo')->get(),

        ];
        return view('fr/cast/form_album',$view);
    }

    public function editarAlbum(AlbumCastRequest $request){

        $retorno = $this->castService->editarAlbum($request);

        if($retorno===true){
            return redirect('/gestao/cast?tipo=1')->with('certo', 'Álbum editado.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar editar álbum.');
        }
    }

    public function getAudiosAjax(Request $request)
    {
        $listaAudios = $this->castService->audiosParaAlBum($request->all());
        if($listaAudios!==false){
            return response()->json( $listaAudios, 200 );
        }
        else
        {
            return response()->json( false, 400 );
        }
    }


    //// gestão de playlist
    public function formPlaylist(){
        $view = [
            'categoria'     => Categoria::where('tipo',5)->orderBy('titulo')->get(),
            'cicloEtapa'    => $this->castService->cicloEtapa(),
            'disciplina'    => Disciplina::orderBy('titulo')->get(),
        ];
        return view('fr/cast/form_playlist',$view);
    }

    public function addPlaylist(PlaylistCastRequest $request){
        $retorno = $this->castService->inserirPlaylist($request);

        if($retorno===true){
            return redirect('/gestao/cast?tipo=2')->with('certo', 'Playlist cadastrado.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar cadastrar playlist.');
        }
    }

    public function excluirPlaylist($id)
    {
        $retorno = $this->castService->excluirPlaylist($id);

        if($retorno){
            return back()->with('certo', 'Playlist excluído.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar excluir playlist.');
        }
    }

    public function getPlaylist($id)
    {
        $album = $this->castService->getPlaylist($id);
        $sel['selecionados'] ='';
        foreach($album->audios as $a){
            $sel['selecionados'] .= $a->conteudo_id.',';
        }
        $view = [
            'dados'         => $album,
            'categoria'     => Categoria::where('tipo',5)->orderBy('titulo')->get(),
            'cicloEtapa'    => $this->castService->cicloEtapa(),
            'disciplina'    => Disciplina::orderBy('titulo')->get(),

        ];
        return view('fr/cast/form_playlist',$view);
    }

    public function editarPlaylist(PlaylistCastRequest $request){

        $retorno = $this->castService->editarPlaylist($request);

        if($retorno===true){
            return redirect('/gestao/cast?tipo=2')->with('certo', 'Playlist editado.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar editar playlist.');
        }
    }

    public function duplicar(Request $request){

        $retorno = $this->castService->duplicar($request->all());

        if($retorno===true){
            return redirect('/gestao/cast?tipo='.$request->input('tipo'))->with('certo', 'Registro adicionado.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar adicionar registro.');
        }
    }

    public function publicar(Request $request){

        $retorno = $this->castService->publicar($request->all());

        if($retorno===true){
            return redirect('/gestao/cast?tipo='.$request->input('tipo'))->with('certo', 'Registro publicado.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar publicar registro.');
        }
    }
}
