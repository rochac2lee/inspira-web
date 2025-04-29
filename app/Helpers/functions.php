<?php


function transformaUrlVideoEmbed($link){
    if (strpos($link, "youtube") !== false || strpos($link, "youtu.be") !== false) {
        if (strpos($link, "youtu.be") !== false) {
            $link = str_replace( "youtu.be", "youtube.com", $link);
        }
        $link = str_replace("/watch?v=", "/embed/", $link);
        if (strpos($link, "&") !== false) {
            $link = substr($link, 0, strpos($link, "&"));
        }
        if (strpos($link, "embed") === false) {
            $link = explode('/',$link);
            $ultimo = $link[count($link)-1];
            unset($link[count($link)-1]);
            $aux = implode('/',$link);
            $link = $aux.'/embed/'.$ultimo;
        }
        return $link;

    } elseif (strpos($link, "vimeo") !== false) {
        if (strpos($link, "player.vimeo.com") === false)
            $link = str_replace("vimeo.com/", "player.vimeo.com/video/", $link);

        return $link;
    }
    else{
        return $link;
    }
}

function trasnformaVideoIframe($link)
{
    if (strpos($link, "youtube") !== false || strpos($link, "youtu.be") !== false) {
        return '<iframe src="' . $link . '" style="width: 100%; height: 16vw;" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen>
            </iframe>';
    }elseif(strpos($link, "vimeo") !== false){
        return '<iframe src="' . $link . '" style="width: 100%; height: 16vw;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen>
            </iframe>';
    }else{
        return $link;
    }
}
function whatGuard(){
    if(Auth::guard('api')->check()){
        return "api";
    }
    if(Auth::guard('web')->check()){
        return "web";
    }
}

function privilegioId($perfil)
{
    switch ( $perfil ) {
        case "A":
            return '4';
            break;
        case "P":
            return '3';
            break;
        case "Z":
            return '1';
            break;
    }
}

function nomePermissao($codigo){
    switch ($codigo) {
        case 'A':
            return 'Estudante';
            break;
        case 'P':
            return "Docente";
            break;
        case 'Z':
            return "Super usuário";
            break;
        case 'C':
            return "Coordenador de escola";
            break;
        case 'R':
            return "Responsável";
            break;
        case 'G':
        case 'I':
            return "Gestor Instituicional";
            break;
    }
}
function dataBR($valor)
{
    $hora = '';
    if($valor!='')
    {
        $valor = explode(' ', $valor);
        if(isset($valor[1]))
        {
            $hora = ' '.$valor[1];
        }
        $valor = $valor[0];
        $valor = explode('-', $valor);
        if(count($valor) == 3)
        {
            return $valor[2].'/'.$valor[1].'/'.$valor[0].$hora;
        }
    }

}

function dataUS($valor)
{
    $hora = '';
    if($valor!='')
    {
        $valor = explode(' ', $valor);
        if(isset($valor[1]))
        {
            $hora = ' '.$valor[1];
        }
        $valor = $valor[0];
        $valor = explode('/', $valor);
        if(count($valor) == 3)
        {
            return $valor[2].'-'.$valor[1].'-'.$valor[0].$hora;
        }
    }
}


/*
 * Funções feitas pela EDULABz
 *
 */

if(! function_exists('formatDateAndTime'))
{
    function formatDateAndTime($value, $format = 'd/m/Y')
    {
// Utiliza a classe de Carbon para converter ao formato de data ou hora desejado
        return Carbon\Carbon::parse($value)->format($format);
    }

}

if(! function_exists('blockEmail'))
{
    function blockEmail($email,$emailParam)
    {
        //$email = Auth::user()->email; //Recupra o email do usuario
        $corpoEmail = explode("@",$email);// Separa o resultado em duas partes
        $parteEamil = explode(".",$corpoEmail[1]);//recuperar e separar tudo o que estiver apos o @
        $valueToSearch = $emailParam;
        if(in_array($valueToSearch,$parteEamil)){
            return true;
        }else{
            return false;
        }
    }

}

if(! function_exists('_sign'))
{
    function _sign($resource, $timeout = 60)
    {
        // This is the id of the Cloudfront key pair you generated
        $keyPairId = "APKAJMWZWM5CVWORY7XQ";
        $resource = str_replace('storage/', '', $resource);
        $resource = (strpos($resource, '/') === 0) ? config('app.signed_cdn') . $resource : config('app.signed_cdn') . '/' . $resource;
        $expires = time() + $timeout; // Timeout in seconds
        $json = '{"Statement":[{"Resource":"'.$resource.'","Condition":{"DateLessThan":{"AWS:EpochTime":'.$expires.'}}}]}';

        // Read Cloudfront Private Key Pair, do not place it in the webroot!
        $fp = fopen(config('app.fs_root') . "/pk-APKAJMWZWM5CVWORY7XQ.pem", "r");
        $priv_key = fread($fp,8192);
        fclose($fp);

        // Create the private key
        $key = openssl_get_privatekey($priv_key);
        if (!$key) {
            throw new Exception('Loading private key failed');
        }

        // Sign the policy with the private key
        if (!openssl_sign($json, $signed_policy, $key, OPENSSL_ALGO_SHA1)) {
            throw new Exception('Signing policy failed, '.openssl_error_string());
        }

        // Create url safe signed policy
        $base64_signed_policy = base64_encode($signed_policy);
        $signature = str_replace(array('+','=','/'), array('-','_','~'), $base64_signed_policy);

        // Construct the URL

        $url = $resource .  (strpos($resource, '?') === false ? '?' : '&') . 'Expires='.$expires.'&Signature=' . $signature . '&Key-Pair-Id=' . $keyPairId;

        return $url;
    }
}
