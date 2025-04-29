<?php

namespace App\Http\Controllers\Fr;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class SsoController extends Controller
{

    public function recebePostAgenda(Request $request)
    {
    	$request = $request->all();

    	if($request['auth']!= '')
    	{
    		$dados = ['token_agenda' => $request['auth']];
    		$user = User::find($request['userId']);
    		$user->update($dados);
    	}
	 }

	 public function provaFacil(Request $request) {

		$user = auth()->user()->id;
		//$objSSO = new \stdClass();
		//$objSSO->username     = $user;
		//$objSSO->is_candidate = false;
		$objSSO = [
			'username' => $user,
			'is_candidate' => false
		];
		$jsonSSO = json_encode($objSSO);

		$ch = curl_init('https://sso.provafacilnaweb.com.br/universallogin/generate-token/');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonSSO);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($jsonSSO))
		);

		$result = curl_exec($ch);
		curl_close($ch);

		if ($result) {
			$t = json_decode($result);
			$token = $t->token;
			return redirect('https://opet.provafacilnaweb.com.br/opet/sso/?ticket=' . $token . '&is_candidate=false');
		}


	 }

}
