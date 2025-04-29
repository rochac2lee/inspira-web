<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GestaoRelatoriosController extends Controller
{
    public function index()
    {
        return view('fr.gestao.relatorios.lista');
    }

    public function instituicao(){
        return view('fr.gestao.relatorios.instituicao');
    }

    public function usuarios(){
        return view('fr.gestao.relatorios.usuarios');
    }

    public function biblioteca(){
        return view('fr.gestao.relatorios.biblioteca');
    }

    public function qrcode(){
        return view('fr.gestao.relatorios.qrcode');
    }
}
