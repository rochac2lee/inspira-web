<?php

namespace App\Http\Controllers\Fr;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Tutorial;
use App\Models\ColecaoTutorial;
use App\Models\Plataforma;
use Auth;
use Illuminate\Support\Facades\Storage;

class TutorialController extends Controller
{
    public function colecao()
    {
        $tutorial = ColecaoTutorial::join('tutorial', 'tutorial.colecao_id', 'colecao_tutorial.id');
        if(Auth::check())
        {
            $instituicao = session('instituicao');
            $user = Auth::user();
            if($user->permissao == 'A')
            {
                $tutorial = $tutorial->where(function($q){
                    $q->orWhere('tutorial.permissao','A')
                    ->orWhere('tutorial.permissao','F');
                });
            }
            $tutorial = $tutorial->where(function($q) use($instituicao){
                $q->orWhereNull('tutorial.instituicao_tipo_id')
                    ->orWhere('tutorial.instituicao_tipo_id',$instituicao['tipo']);
            });
        }
        else
        {
            $tutorial = $tutorial->where('tutorial.permissao','F');
        }
        $tutorial = $tutorial->orderBy('colecao_tutorial.ordem')
                            ->groupBy('colecao_tutorial.id')
                            ->selectRaw('colecao_tutorial.*')
                            ->paginate(10);
        $view = [
            'dados' => $tutorial,
        ];
        return view('fr/tutorial/colecao',$view);
    }

    public function index($idColecao)
    {
        $tutorial = Tutorial::where('colecao_id',$idColecao);
    	if(Auth::check())
    	{
    		$user = Auth::user();
            $instituicao = session('instituicao');
    		if($user->permissao == 'A')
    		{
                $tutorial = $tutorial->where(function($q){
                                $q->orWhere('permissao','A')
                                    ->orWhere('permissao','F');
                            });
    		}
            $tutorial = $tutorial->where(function($q) use($instituicao){
                $q->orWhereNull('tutorial.instituicao_tipo_id')
                    ->orWhere('tutorial.instituicao_tipo_id',$instituicao['tipo']);
            });
    	}
    	else
    	{
            $tutorial = $tutorial->where('permissao','F');

    	}
        $tutorial = $tutorial->orderBy('ordem')->get();
    	$view = [
    		'tutorial' => $tutorial,
            'colecao'=> ColecaoTutorial::find($idColecao),
    	];
    	return view('fr/tutorial/index',$view);
    }


    public function downloadTutorial($id){
        if(Auth::check())
        {
            $user = Auth::user();
            if($user->permissao == 'A')
            {
                $tutorial = Tutorial::orWhere('permissao','A')
                        ->orWhere('permissao','F')
                        ->find($id);
            }
            else
            {
                $tutorial = Tutorial::find($id);
            }
        }
        else
        {
            $tutorial = Tutorial::where('permissao','F')
                                ->find($id);
        }

        if($tutorial){
            ob_end_clean();
            return Storage::download('storage/tutorial_pdf/'.$tutorial->arquivo_pdf);
        }
        else{
            return back();
        }
    }

    public function termosUso()
    {
        $view = [
            'plataforma' => Plataforma::first(),
        ];
        return view('fr/termos-de-uso',$view);
    }

    public function termosPrivacidade()
    {
        $view = [
            'plataforma' => Plataforma::first(),
        ];
        return view('fr/termos-de-privacidade',$view);
    }

}
