<?php

namespace App\Http\Controllers\Fr;

use App\Mail\EmailFaleConosco;
use App\Mail\testeEmail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\Fr\ContatoRequest;
use App\Services\SendEmailService;

class ContatoController extends Controller
{
    public function index()
    {
    	return view('fr/contato');
    }

    public function edInfantil()
    {
        return view('fr/contato_ed_infantil');
    }

    public function enviar( ContatoRequest $request)
    {

        $data = [
            'email' => 'atendimentote@opeteducation.com.br',//'atendimentote@opet-sefe.com.br 'lucianorocha@opet-sefe.com.br,
            'email_contato' => $request->input('email'),
            'nome' => $request->input('nome'),
            'name' => $request->input('nome'),
            'escola' => $request->input('escola'),
            'telefone' => $request->input('telefone'),
            'assunto' => $request->input('assunto'),
            'mensagem' => $request->input('msg'),
            'cidade' => $request->input('cidade'),

        ];
        //$sendEmail = SendEmailService::sendEmailGmail($data);

        Mail::send(new EmailFaleConosco($data));
        //if($sendEmail)
        //{
            return redirect()->back()->with('certo','Em breve retornaremos seu contato.');
        /*}
        else
        {
            return redirect()->back()->with('erro','Erro ao tentar enviar o seu contato, por favor tente mais tarde!');
        }
        */
    }
}
