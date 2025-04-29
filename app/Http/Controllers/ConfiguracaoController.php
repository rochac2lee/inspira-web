<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request\Validate;




use Image;
use Auth;
use Session;

use App\Models\User;
use App\Models\EnderecoUser;

class ConfiguracaoController extends Controller
{
    public function index()
    {
        $user = User::with('escola', 'endereco')->find(auth()->user()->id);

        $transacoes = [];




        $cores = [];
        $totalGasto =null;

        $bancos = [];
        $certificado = null;

        $recebedor = null;

        return view('pages.configuracoes-conta.index')->with(compact('user', 'transacoes', 'totalGasto', 'bancos', 'recebedor', 'cores', 'certificado'));
    }

    public function trocarEmail(Request $request)
    {
        Auth::user()->update([
            'email' => $request->email
        ]);

        Session::flash('success', 'Dados alterados com sucesso!');

        return redirect()->back();
    }

    public function salvarDados(Request $request)
    {
        //dd($request->validate());

        $request->validate([
            'nome_completo'    => '|string|min:2|max:50',
            'data_nascimento' =>'|max:12|min:10|date_format:d/m/Y|before: 1 year ago|after: 130 year ago',
            'cpf' =>'|string|size:14',
            'rg' =>'|string|min:10|max:12',
            'telefone' =>'|string|min:10|max:16',
        ], [
            'data_nascimento.date_format' => "Data de nascimento não está no formato: dia/mês/ano ex.: 30/01/1990.",
            'data_nascimento.before' => 'A data de nascimento deve ser maior que 1 ano.',
            'data_nascimento.after' => 'A data de nascimento deve ser menor que 130 anos.'
        ]);


        if ($request->descricao == null)
            $request->descricao = "";

        $user = Auth::user();

        $dataNascimento = $request->data_nascimento != null ? $request->data_nascimento : $user->data_nascimento;
        $dataNascimento = str_replace('/', '-', $dataNascimento);
        $dataNascimento = date('Y-m-d', strtotime($dataNascimento));
        #dd($request);
        $user->update([
            'name'          => $request->name != null ? $request->name : $user->name,
            'nome_completo' => $request->nome_completo != null ? $request->nome_completo : $user->nome_completo,
            'telefone'      => $request->telefone != null ? $request->telefone : $user->telefone,
            'descricao'     => $request->descricao != null ? $request->descricao : $user->descricao,
            'genero'          => $request->genero != null ? $request->genero : $user->genero,
            'data_nascimento' => $dataNascimento,
        ]);

        if(isset($request->has_endereco_user))
        #dd($request);
        {
            EnderecoUser::updateOrCreate([
                'user_id' => Auth::user()->id
            ], [
                'user_id'     => Auth::user()->id,
                "cep"         => $request->cep,
                "uf"          => $request->uf,
                "cidade"      => $request->cidade,
                "bairro"      => $request->bairro,
                "logradouro"  => $request->logradouro,
                "numero"      => $request->numero,
                "complemento" => $request->complemento,
            ]);
        }

        Session::flash('success', 'Dados alterados com sucesso!');


        return redirect()->back();

    }

    public function trocarFotoPerfil(Request $request)
    {

        $fileExtension = \File::extension($request->foto->getClientOriginalName());

        $newFileName = md5($request->foto->getClientOriginalName() . date("Y-m-d H:i:s") . time()) . '.' . $fileExtension;

        $pathFoto = $request->foto->storeAs('public/uploads/usuarios/perfil', $newFileName, 'local');

        if ($img = Image::make(file_get_contents($request->foto))) {
            $img->resize(250, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $img->encode('jpg', 85);
        }

        // if(!\Storage::disk('local')->put($pathFoto, file_get_contents($request->foto)))
        if (!\Storage::disk('local')->put($pathFoto, $img)) {
            \Session::flash('middle_popup', 'Algo deu errado.');
            \Session::flash('popup_style', 'danger');
        } else {
            $user = User::find(\Auth::user()->id);

            if ($user->img_perfil != null && $user->img_perfil != "" && $user->img_perfil !="imagem_padrao.jpg") {
                if (\Storage::disk('local')->has('public/uploads/usuarios/perfil/' . $user->img_perfil)) {
                    \Storage::disk('local')->delete('public/uploads/usuarios/perfil/' . $user->img_perfil);
                }
            }

            $user->img_perfil = $newFileName;
            $user->save();
        }

        return redirect()->back();

    }

    public function trocarSenha(Request $request)
    {
        // dd($request);

        $user = \Auth::user();

        if (strlen($request->senha_nova) < 6) {
            \Session::flash('middle_popup', 'A sua nova senha deve ter no mínimo 6 caracteres!');
            \Session::flash('popup_style', 'warning');

            return redirect()->back();
        }

        if (\Hash::check($request->senha_nova, $user->password)) {
            \Session::flash('middle_popup', 'A sua nova senha deve ser diferente da anterior!');
            \Session::flash('popup_style', 'warning');

            return redirect()->back();
        }

        if ($request->senha_nova != $request->senha_confirmacao) {
            \Session::flash('middle_popup', 'A senha de confirmação deve ser idêntica a sua nova senha!');
            \Session::flash('popup_style', 'warning');

            return redirect()->back();
        }

        if (\Hash::check($request->senha_atual, $user->password) == false) {
            \Session::flash('middle_popup', 'A sua antiga senha está incorreta!');
            \Session::flash('popup_style', 'danger');

            return redirect()->back();
        }
        //recebendo o request do formulario

        $user->update([
            'password' => \Hash::make($request->senha_nova)
        ]);

        \Session::flash('success', 'Senha alterada com sucesso!');

        return redirect()->back();

    }





}
