<?php

namespace App\Http\Controllers\Fr;

use App\Models\Categoria;
use App\Models\Disciplina;
use App\Services\Fr\CastService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CastController extends Controller
{
    public function __construct(CastService $castService)
    {
        $this->middleware('auth');

        $this->castService = $castService;
    }

    public function index(Request $request){
        $view=[
            'dados'         => $this->castService->get(20, $request->all()),
            'categoria'     => Categoria::where('tipo',5)->orderBy('titulo')->get(),
            'cicloEtapa'    => $this->castService->cicloEtapa(),
            'disciplina'    => Disciplina::orderBy('titulo')->get(),
        ];
        return view('fr/cast/index_aluno', $view);
    }

    public function exibirPlayList(Request $request){
        $view =[
            'dados' => $this->castService->getListaAudioAlbumPlayList($request->all()),
        ];
        return view('fr/cast/relacao_audio_album_playlist',$view);
    }
}
