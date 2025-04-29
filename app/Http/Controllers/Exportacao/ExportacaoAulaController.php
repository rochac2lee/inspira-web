<?php
namespace App\Http\Controllers\Exportacao;

use App\Models\Aula;
use App\Models\ConteudoAula;
use App\Models\Conteudo;

class ExportacaoAulaController extends ExportacaoController
{

    public function aula($idCurso, $idAula)
    {
        $aula = Aula::where([['id', '=', $idAula], ['curso_id', '=', $idCurso]])->first();

        if(!$aula){
            return redirect()->back()->witherror( 'Conteúdo não encontrado!');
        }

        $aula->makeHidden(['id', 'curso_id', 'user_id', 'created_at', 'updated_at']); //LISTA DE CAMPOS QUE NÃO PRECISAMOS CAPTURAR.

        $aulaConteudos = ConteudoAula::where([['aula_id', '=', $idAula], ['curso_id', '=', $idCurso]])->get();
        foreach($aulaConteudos as $aulaConteudo)
        {
            $aulaConteudo = Conteudo::find($aulaConteudo->conteudo_id);
            $aulaConteudo->makeHidden(['id', 'user_id', 'created_at', 'updated_at']); //LISTA DE CAMPOS QUE NÃO PRECISAMOS CAPTURAR.

            $resAulaConteudos[] = $aulaConteudo;
        }

        if(!isset($aulaConteudo)){
            return redirect()->back()->witherror('Item sem conteúdo para ser exportado!');
        }

        $res['aula'] = $aula;
        $res['aula']['conteudos'] = $resAulaConteudos;

        $res = json_encode($res);

        $fileName = $this->generateFileName('aula', $aula->titulo, 'tz');

        return $this->callDownloadFromStream($res, $fileName, '');
    }

}
