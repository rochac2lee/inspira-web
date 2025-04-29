<?php
namespace App\Http\Controllers\Importacao;

use App\Models\Aula;
use App\Models\Conteudo;
use App\Models\ConteudoAula;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Redirect;

class ImportacaoAulaController extends ImportacaoController
{

    public function aula($idCurso, $idAula, Request $request)
    {

        if($request->hasFile('fileImportAula'))
        {
            if($request->file('fileImportAula')->getClientOriginalExtension() != 'tz')
            {
                return Redirect::back()->withErrors(['Arquivo de importação inválido!']);
            }

            $userId = Auth::user()->id;

            $fileContent = file_get_contents($request->file('fileImportAula')->getPathName());
            $decodeAsArray = json_decode($fileContent, true);

            $myArr = $decodeAsArray;

            /* IMPORTA AULA */
            $arrAula = (isset($myArr['aula'])) ? $myArr['aula'] : false;

            if(!$arrAula)
            {
                return Redirect::back()->withErrors(['Arquivo de importação não é do tipo correto!']);
            }

            unset($arrAula['conteudos']);

            //Adiciona Aula
            $aula = new Aula();
            $aula->curso_id = $idCurso;
            $aula->user_id = $userId;
            $aula->ordem = Aula::where('curso_id', '=', $idCurso)->max('ordem') + 1;
            $aula->forceFill($arrAula);
            $aula->save();
            $insertedAulaId = $aula->id;
            /* */

            /* IMPORTA CONTEUDOS DA AULA */
            $arrConteudosAula = $myArr['aula']['conteudos'];
            foreach($arrConteudosAula as $conteudoAula)
            {
                //Adiciona Conteúdo
                $conteudo = new Conteudo();
                $conteudo->user_id = $userId;
                $conteudo->forceFill($conteudoAula);
                $conteudo->save();
                $insertedConteudoId = $conteudo->id;

                //Adiciona Relacionamento do Conteúdo com o Curso/Aula
                $conteudoAula = new ConteudoAula();
                $conteudoAula->ordem = ConteudoAula::where('aula_id', '=', $insertedAulaId)->max('ordem') + 1;
                $conteudoAula->conteudo_id = $insertedConteudoId;
                $conteudoAula->curso_id = $idCurso;
                $conteudoAula->aula_id = $insertedAulaId;
                $conteudoAula->user_id = $userId;
                $conteudoAula->obrigatorio = 1;
                $conteudoAula->save();
                $insertedConteudoAulaId = $conteudo->id;
            }
            /* */

            return Redirect::back()->with('message', 'Conteúdo importado com sucesso!');
        }
        else
        {
            return Redirect::back()->withErrors(['Arquivo de importação não existe!']);
        }
    }

}
