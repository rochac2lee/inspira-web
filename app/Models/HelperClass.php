<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;
use Auth;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

use Request;

/**
 * App\Models\HelperClass
 *
 * @method static \Illuminate\Database\Eloquent\Builder|HelperClass newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HelperClass newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HelperClass query()
 * @mixin \Eloquent
 */
class HelperClass extends Model
{
    public static function AppendToUrl($newQueries)
    {
        //Retrieve current query strings:
        $currentQueries = Request::query();

        //Merge together current and new query strings:
        $allQueries = array_merge($currentQueries, $newQueries);

        //Generate the URL with all the queries:
        return Request::fullUrlWithQuery($allQueries);
    }

    /**
     * Gera a paginação dos itens de um array ou collection.
     *
     * @param array|Collection $items
     * @param int $perPage
     * @param int $page
     * @param array $options
     *
     * @return LengthAwarePaginator
     */
    public static function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);

        $pagination = new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);

        return $pagination;
    }

    public static function RandomString($length)
    {
        $char = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $char = str_shuffle($char);
        for ($i = 0, $rand = '', $l = strlen($char) - 1; $i < $length; $i++) {
            $rand .= $char{mt_rand(0, $l)};
        }

        return $rand;
    }

    public static function needSideBar()
    {
        if (Request::is('home') || \Request::is('playlists') || \Request::is('plano-de-estudos') || \Request::is('canal-do-professor/*/*') || Request::is('grade-de-aula/data/*') || Request::is('catalogo') || Request::is('historico') || Request::is('historico/*') || Request::is('favoritos') || Request::is('favoritos/*') || Request::is('ranking') || Request::is('plano-de-aulas/*') || Request::is('estatisticas') || Request::is('catalogo') || Request::is('catalogo/*') || Request::is('biblioteca') || Request::is('play/*') ||
            Request::is('aplicacao/*') || Request::is('glossario') || Request::is('glossario/*') || Request::is('perfil/*') ||
            Request::is('turmas') || Request::is('turma/*') || Request::is('habilidades') || Request::is('habilidades/*') || Request::is('agenda') || Request::is('agenda/*') || Request::is('teste-nivelamento') || Request::is('teste-nivelamento/*') ||
            Request::is('professor/*') || Request::is('trilhas/*') ||
            Request::is('artigos') ||   Request::is('artigos/*') ||
            Request::is('escola/*/mural') ||
            Request::is('painel*') ||
            ((Request::is('carrinho') || Request::is('configuracao*')) && (Auth::check() && Auth::user()->privilegio_id == 1))) {
            return true;
        } else {
            return false;
        }
    }

    public static function needGestaoSideBar()
    {
        if (Request::is('home') ||
            Request::is('gestao/*') ||
            Request::is('curso/*') ||
            Request::is('dashboard/*') ||
            Request::is('turmas') || Request::is('turma/*') ||
            ((Request::is('carrinho') || Request::is('configuracao*')) && (Auth::check() &&Auth::user()->privilegio_id != 4))  ||
            (Session::has('previewMode') && Request::is('curso/*'))
        ){
            return true;
        } else {
            return false;
        }
    }

    public static function getAplicacaoAtual()
    {
        if(Request::is('dashboard/*') || Request::is('gestao/relatorios') || Request::is('gestao/aplicacoes')   || Request::is('gestao/usuarios'))
        {
            return "manager1";
        }
        else if(Request::is('gestao/escola/*')  || Request::is('gestao/escolas'))
        {
            return "school";
        }
        else if (Request::is('gestao/*') || (Session::has('previewMode') && Request::is('curso/*')))
        {
            return "master";
        }
        else if (Request::is('home') || \Request::is('playlists') || \Request::is('plano-de-estudos') || \Request::is('canal-do-professor/*/*') || Request::is('grade-de-aula/data/*') || Request::is('catalogo') || Request::is('historico') || Request::is('historico/*') || Request::is('favoritos') || Request::is('favoritos/*') || Request::is('ranking') || Request::is('plano-de-aulas/*') || Request::is('estatisticas') || Request::is('catalogo') || Request::is('catalogo/*') || Request::is('biblioteca') || Request::is('play/*')  || Request::is('glossario') || Request::is('glossario/*') || Request::is('perfil/*') ||
            Request::is('turmas') || Request::is('turma/*') || Request::is('habilidades') || Request::is('habilidades/*') || Request::is('agenda') || Request::is('agenda/*') || Request::is('teste-nivelamento') || Request::is('teste-nivelamento/*') ||
            Request::is('professor/*') || Request::is('trilhas/*') ||
            Request::is('artigos') || Request::is('artigos/*') ||
            Request::is('escola/*/mural')  ) {
            return "play1";
        }
        else
        {
            return false;
        }
    }

    public static function getLogoPerfilUser()
    {
        if(Auth::check()){
            switch (Auth::user()->privilegio_id) {
                case 1:
                case 2:
                    return "manager-branco";
                    break;
                case 5:
                    return "school-branco";
                    break;
                case 3:
                    return "master-branco";
                    break;
                default:
                    return "play-branco";
                    break;
            }
        } else {
            return "play-branco";
        }
    }

    public static function needDocSideBar()
    {
        if (Request::is('dashboard/documentacao/*')) {
            return true;
        }
        return false;
    }

    public static function needSideBarButton()
    {
        if (Request::is('home') || \Request::is('plano-de-estudos') || \Request::is('canal-do-professor/*/*') || Request::is('grade-de-aula/data/*') || Request::is('teste-nivelamento') || Request::is('playlists') || Request::is('catalogo') || Request::is('historico') || Request::is('historico/*') || Request::is('favoritos') || Request::is('favoritos/*') || Request::is('ranking') || Request::is('plano-de-aulas/*') || Request::is('estatisticas') || Request::is('catalogo') || Request::is('catalogo/*') || Request::is('biblioteca') || Request::is('play/*') ||
            Request::is('aplicacao/*') || Request::is('glossario') || Request::is('glossario/*') || Request::is('perfil/*') ||
            Request::is('turmas') || Request::is('turma/*') || Request::is('habilidades') || Request::is('habilidades/*') || Request::is('agenda') || Request::is('agenda/*') ||
            Request::is('professor/*') || Request::is('gestao/*') || Request::is('dashboard/*') ||
            Request::is('artigos') || Request::is('artigos/*') || Request::is('trilhas/*') ||
            Request::is('escola/*/mural') ||
            Request::is('curso/*') ||
            Request::is('planos') || Request::is('planos/*') ||
            Request::is('carrinho') || Request::is('carrinho/*') ||
            Request::is('checkout') || Request::is('checkout/*') ||
            Request::is('configuracao*') ||
            Request::is('painel*') || Request::is('cursos-livre') ||
            (Session::has('previewMode') && Request::is('curso/*'))) {
            return true;
        } else {
            return false;
        }
    }

    public static function previousRoute()
    {
        try
        {
            if(\URL::previous())
            {
                return app('router')->getRoutes()->match(app('request')->create( str_replace(config('app.url'), "", \URL::previous())  ))->getName();
            }
            else
            {
                return null;
            }
        }
        catch (\Throwable $th)
        {
            return null;
        }
    }

    public static function comparePreviousRoute($routeName)
    {
        if(\URL::previous())
        {
            if($routeName == self::previousRoute())
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return null;
        }
    }

    public static function linkfy($text)
    {
        $url = '~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i';

        $string = preg_replace($url, '<a href="$0" target="_blank" title="$0">$0</a>', $text);

        return  $string;
    }

    public static function normalize ($string) {
        $table = array(
            'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
            'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
            'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
            'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
            'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
            'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
            'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r',
        );

        return strtr($string, $table);
    }

    public static function drawCarrinho()
    {
        $html = "";

        if(\Session::has('carrinho') ? count(\Session::get('carrinho')) > 0 : false)
        {
            $total = 0;

            foreach(\Session::get('carrinho') as $produto)
            {
                $total += $produto->preco;

                $html = $html . '<div class="dropdown-item px-3 py-1 mb-1" style="border-bottom:  1px solid #f8f8f8;width: auto;clear: both;font-weight: 400;color: #212529;text-align: inherit;white-space: nowrap;background-color: transparent;border: 0;flex-direction: row;display: flex; align-items: center;">';

                if($produto->tipo == 2 && $produto->curso != null)
                {
                    $html = $html . '<div class="" style="background-image: url(' . config('app.cdn') . '/storage/uploads/cursos/capas/' .  $produto->curso->capa . ');background-size: cover;background-position: 50% 50%;background-repeat: no-repeat;width: auto;flex: 1;margin-right: 10px;display: inline-block;vertical-align: middle;height: 60px;">
                    </div>';
                }

                $html = $html . '
                        <div>
                            <b style="max-width: 200px;white-space: normal; overflow: hidden;" class="pl-2 mb-0">
                                ' . ucfirst($produto->titulo) . '
                            </b>

                            <p class="small pl-2 mb-0">' . ucfirst($produto->descricao) . '</p>

                            <p style="" class="pl-2 mb-0">
                                R$ ' . number_format($produto->preco, 2, ',', '.') . ($produto->quantidade >= 2 ? ' (' . $produto->quantidade . 'x)' : '')  . '
                            </p>
                        </div>
                        <a class="text-primary ml-auto" href="' . route('carrinho.remover', ['idProduto' => $produto->id, 'return' => Request::url()]) .'" style="align-self: flex-start; justify-self: flex-end;"><i class="fa fa-times fa-fw fa-sm" aria-hidden="true"></i></a>
                    ';

                $html = $html . '</div>';
            }

            $html = $html . '<div class="dropdown-item px-3 py-3" style="min-width:  340px;border-bottom:  2px solid #E3E5F0;">
                <h5>
                    <span class="text-lightgray">Total: </span>
                    R$ ' . number_format($total, 2, ',', '.') . '
                </h5>
                ' . (Request::is('carrinho') == false ? '<a href="' . route('carrinho.index') . '" class="btn btn-primary btn-block text-center">Ir para o carrinho</a>' : '') . '
            </div>';
        }
        else
        {
            $html = '
                <div class="dropdown-item px-4 py-3" style="min-width:  340px;border-bottom:  2px solid #E3E5F0;">
                    Seu carrinho está vazio.
                </div>
            ';
        }

        return $html;
    }

    /**
     * Gera senhas aleatórias
     *
     * @param int $qtyCaraceters quantidade de caracteres na senha, por padrão 8
     * @author Carlos Ferreira &lt;carlos@especializati.com.br&gt;
     * @return String
    */
    public static function generatePassword($qtyCaraceters = 8)
    {
        //Letras minúsculas embaralhadas
        $smallLetters = str_shuffle('abcdefghijklmnopqrstuvwxyz');

        //Letras maiúsculas embaralhadas
        $capitalLetters = str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ');

        //Números aleatórios
        $numbers = (((date('Ymd') / 12) * 24) + mt_rand(800, 9999));
        $numbers .= 1234567890;

        //Caracteres Especiais
        $specialCharacters = str_shuffle('!@#$%*-');

        //Junta tudo
        $characters = $capitalLetters.$smallLetters.$numbers.$specialCharacters;

        //Embaralha e pega apenas a quantidade de caracteres informada no parâmetro
        $password = substr(str_shuffle($characters), 0, $qtyCaraceters);

        //Retorna a senha
        return $password;
    }

    public static function showNavBarRoute($escola)
    {
        if (\Request::is('hub')     ||
            \Request::is('hub/*')   ||
            \Request::is('aplicacao/*'))
        {
            return route('hub.index');
        } elseif(Request::is('/')) {
            return '#';
        } elseif(isset($escola)) {
            return config('app.url') . '/' . $escola->url . '/catalogo';
        } else {
            return route('catalogo');
        }
    }

    public static function showNavBarLogo($escola = null)
    {
        if (isset($escola)){
            if (strrpos($escola->capa, 'http') === false) {
                returnconfig('app.cdn') . '/uploads/escolas/capas/' . $escola->capa;
            } else {
                return $escola->capa;
            }
        } else {
            returnconfig('app.cdn').'/images/logo-branco.svg';
        }
    }

    //Função para dar permissão de editar um conteúdo
    public static function perfilEditar($created){
        $user = Auth::user();
        //Se o usuário for super admin, adm, escola ou o criador do conteúdo pode editar
        if(in_array($user->privilegio_id, [1, 2, 5]) || $created == $user->id){
            return true;
        }
    }

    //Função para dar permissão de editar um conteúdo
    public static function perfilEditarSuperAdmin($created){
        $user = Auth::user();
        //Se o usuário for super admin ou criador do conteúdo pode editar
        if($user->privilegio_id == 1 || $created == $user->id){
            return true;
        }
    }

    //Função para dar permissão de editar um conteúdo, privilegio Super adm e adm
    public static function perfilEditarAdmin($created){
        $user = Auth::user();
        //Se o usuário for super admin ou adm ou criado do conteúdo pode editar
        if(in_array($user->privilegio_id, [1, 2]) || $created == $user->id){
            return true;
        }
    }

    //Função para dar permissão de editar um conteúdo
    public static function perfilCriacao(){
        $user = Auth::user();
        //Se o usuário for super admin, adm, escola ou o criador do conteúdo pode editar
        if(in_array($user->privilegio_id, [1, 2, 5])){
            return true;
        }
    }

    //Função para definir o que o perfil school pode visualizar
    public static function perfilVisualizarSchool(){
        $user = Auth::user();
        //Se o usuário for super admin, adm, escola ou o criador do conteúdo pode editar
        if(in_array($user->privilegio_id, [1, 2, 5])){
            return true;
        }
    }

    //Função para o que o administrador pode visualizar
    public static function perfilVisualizarAdmin(){
        $user = Auth::user();
        //Se o usuário for super admin, adm, escola ou o criador do conteúdo pode editar
        if(in_array($user->privilegio_id, [1, 2])){
            return true;
        }
    }

    //Função para o que o administrador pode visualizar
    public static function perfilVisualizarSuperAdmin(){
        $user = Auth::user();
        //Se o usuário for super admin, adm, escola ou o criador do conteúdo pode editar
        if($user->privilegio_id == 1){
            return true;
        }
    }

    //Função para o que o administrador pode visualizar
    public static function perfilVisualizarGestao(){
        $user = Auth::user();
        //Se o usuário for super admin, adm, escola ou professor
        if(in_array($user->privilegio_id, [1, 2, 3, 5])){
            return true;
        }
    }

}

