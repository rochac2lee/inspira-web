<?php

namespace App\Http\Controllers\Fr;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SimuladoresController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        
	}

    public function sistemaSolar(){
        return view("fr.simuladores.sistemasolar");
    }

    public function tabelaPeriodica(){
    	return view("fr.simuladores.tabelaperiodica");
    }


}