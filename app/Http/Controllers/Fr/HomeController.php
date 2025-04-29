<?php

namespace App\Http\Controllers\Fr;

use App\Mail\EmailFaleConosco;
use App\Mail\testeEmail;
use App\Services\Fr\BibliotecaService;
use App\Services\Fr\BuscaGeralService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\BannerHome;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{

	public function __construct(BuscaGeralService $buscaGeralService, BibliotecaService $bibliotecaService)
    {
        $this->middleware('auth')->except('erros');
        $this->buscaGeralService = $buscaGeralService;
        $this->bibliotecaService = $bibliotecaService;
    }

    public function index(Request $request)
    {
    	$instituicao = session('instituicao');

        $banner = BannerHome::where('instituicao_tipo_id',$instituicao['tipo'])
                            ->inRandomOrder()
                            ->limit(5);
        if(auth()->user()->permissao == 'A'){
            $banner = $banner->where('permissao','A');
        }
        else{
            $banner = $banner->where('permissao','P');
        }
        $banner = $banner->get();

        $dados = $this->bibliotecaService->listaColecaoConteudo(104,$request,1);
        $bncc = false;
        if($dados->total() > 0){
            $bncc = true;
        }

    	$view = [
    		'banner' => $banner,
            'bncc'  => $bncc,
    	];
    	//if($request->input('novo')==1) {
            return view('fr/home/index2021', $view);
       // }else{
           // return view('fr/home/index', $view);
       // }
    }

    public function busca(Request $request)
    {
        if(trim($request->input('busca')) == ''){
            return back();
        }
        $view = [
            'dados' => $this->buscaGeralService->buscar($request->input('busca'), $request->input('tipo')),
        ];
        return view('fr/busca/index',$view);
    }

    public function teste_pdf()
    {
        Mail::send(new EmailFaleConosco(['a'=>1]));
        return view('fr.teste_pdf');
    }
}
