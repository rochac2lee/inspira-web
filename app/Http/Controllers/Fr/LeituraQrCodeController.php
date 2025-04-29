<?php

namespace App\Http\Controllers\Fr;

use App\Models\FrQrCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LeituraQrCodeController extends Controller
{

    public function exibir(Request $request)
    {
        $url = FrQrCode::where('chave',$request->input('c'))->first();

        if(isset($url) && $url->url != ''){
            return redirect($url->url);
        }else{
            return redirect('/catalogo');
        }

    }
}
