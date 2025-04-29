<?php

namespace App\Http\Controllers\Fr;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Conteudo;
use App\Services\Fr\BibliotecaService;

class ExibicaoGoogleController extends Controller
{
	public function __construct(BibliotecaService $bibliotecaService)
    {
        $this->bibliotecaService = $bibliotecaService;
    }
    public function exibir(Request $request)
    {
    	$chave = $request->input('c');
    	$d = Conteudo::where('id_google',$chave)->where('compartilhado_google',1)->first();

    	if($d == null)
    	{
    		return '';
    	}
    	$ret = [];
		switch ($d->tipo) {
		    case 2:
		    	$ret['conteudo'] = $d->iframe;
		        break;
		    case 3:
		    	$cont = str_replace("vimeo.com/", "player.vimeo.com/video/", $d->conteudo);
		    	$ret['conteudo'] =  '<iframe width="100%" height="100%" src="'.$cont.'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
		        break;
		    case 4:
		    	$url = config('app.cdn').'/storage/' . $d->conteudo;
		    	$url = 'https://docs.google.com/viewer?url=' . $url.'&embedded=true';
		    	$ret['conteudo'] = '<iframe width="100%" height="100%" src="'.$url.'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
		        break;
		    case 22:
		    	$url = config('app.cdn').'/storage/' .$d->conteudo;
		    	$ret['conteudo'] =  '<object data="' . $url  . '" type="application/pdf" style="width: 100%; height: 41vw;"></object>';
		        break;
		    case 100:
		    	$img = explode('.',$d->conteudo);
		        $img = $img[0].'_view'.'.'.$img[1];
		        $img = config('app.cdn').'/storage/banco_imagens/views/'.$img;
		    	$ret['conteudo'] =  '<img src= "'.$img.'"><br><br><p style="font-size: 11px" >Fonte: '.$d->fonte.'</p>';
		        break;
		    case 101:
		        $ret['conteudo'] =  '<iframe width="100%" height="90%" src="'.$d->conteudo.'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe><br><p><b>'.$d->descricao.'</b></p><br><p style="font-size: 11px" >Fonte: '.$d->fonte.'</p>';
				break;
		    case 102:
		        $url = config('app.cdn').'/storage/provas/' . $d->conteudo;
		        $url = 'https://docs.google.com/viewer?url=' . $url.'&embedded=true';
		        $ret['conteudo'] = '<iframe width="100%" height="100%" src="'.$url.'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
		        break;
		    case 103:
		        $ret['conteudo'] =  '<iframe width="100%" height="100%" src="'.$d->conteudo.'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
		        break;
            case 104:
                $ret['conteudo'] =  $d->conteudo;
                break;
		}

    	$view = [
    		'dados' => $ret,
    		'titulo'=> $d->titulo,
    		'tipo'  => $d->tipo,
    	];
    	return view('fr/google/documento_publico',$view);

    }
}
