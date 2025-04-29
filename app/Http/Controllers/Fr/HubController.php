<?php

namespace App\Http\Controllers\Fr;

use Auth;
use Illuminate\Http\Request;
use App\Services\Fr\QrCodeService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Fr\QRCodeRequest;

class HubController extends Controller
{
    public function __construct( QrCodeService $qrCodeService)
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            if(Auth::user()->permissao != 'P' && Auth::user()->permissao != 'Z')
            {
                return back();
            }
            return $next($request);
        });
        $this->qrCodeService = $qrCodeService;
    }
    

    public function hub()
    {
        $view = [
           
        ];
        return view('fr.hub.gabaritos', $view);
    }
    
}