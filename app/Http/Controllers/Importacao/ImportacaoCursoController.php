<?php
namespace App\Http\Controllers\Importacao;

use App\Models\Curso;
use App\Models\Aula;
use App\Models\ConteudoAula;
use App\Models\Conteudo;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Redirect;

class ImportacaoCursoController extends ImportacaoController
{

    public function curso(Request $request)
    {
        if($request->hasFile('fileImportCurso'))
        {
            if($request->file('fileImportCurso')->getClientOriginalExtension() != 'tz')
            {
                return Redirect::back()->withErrors(['Arquivo de importação inválido!']);
            }

            $userId = Auth::user()->id;
            $escolaId = Auth::user()->escola_id;

            $fileContent = file_get_contents($request->file('fileImportCurso')->getPathName());
            $decodeAsArray = json_decode($fileContent, true);

            $myArr = $decodeAsArray;

            /* IMPORTA CURSO */
            $arrCurso = (isset($myArr['curso'])) ? $myArr['curso'] : false;

            if(!$arrCurso)
            {
                return Redirect::back()->withErrors(['Arquivo de importação não é do tipo curso!']);
            }

            unset($arrCurso['aulas']);

            $curso = new Curso();
            $curso->escola_id = $escolaId;
            $curso->user_id = $userId;
            $curso->forceFill($arrCurso);
            $curso->save();
            $insertedCursoId = $curso->id;
            /* */

            /* IMPORTA AULAS DE CURSO */
            $arrAulas = $myArr['curso']['aulas'];

            foreach($arrAulas as $index => $arrAula)
            {
                $tempAula = $arrAula;
                unset($tempAula['conteudos']);

                //ADICIONA AULA
                $aula = new Aula();
                $aula->curso_id = $insertedCursoId;
                $aula->ordem = Aula::where('curso_id', '=', $insertedCursoId)->max('ordem') + 1;
                $aula->user_id = $userId;
                $aula->forceFill($tempAula);
                $aula->save();
                $insertedAulaId = $aula->id;

                //ADICIONA CONTEUDOS DE AULA
                $arrConteudosAula = $myArr['curso']['aulas'][$index]['conteudos'];
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
                    $conteudoAula->curso_id = $insertedCursoId;
                    $conteudoAula->aula_id = $insertedAulaId;
                    $conteudoAula->user_id = $userId;
                    $conteudoAula->obrigatorio = 1;
                    $conteudoAula->save();
                    $insertedConteudoAulaId = $conteudo->id;
                }
                /* */
            }

            return Redirect::back()->with('message', 'Curso importado com sucesso!');
        }
        else
        {
            return Redirect::back()->withErrors(['Arquivo de importação não existe!']);
        }
    }

}
