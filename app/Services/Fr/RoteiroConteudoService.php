<?php
namespace App\Services\Fr;
use App\Models\Altitude\CursoAulaUsuarioDiscursiva;
use App\Models\Altitude\CursoAulaUsuarioEntregavel;
use App\Models\Altitude\CursoAulaUsuarioMatricula;
use App\Models\Conteudo;
use App\Models\ConteudoAula;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RoteiroConteudoService {

    public function inserir($dados){
        DB::beginTransaction();
        try
        {
            $dados['user_id']		= auth()->user()->id;
            $dados['escola_id']		= auth()->user()->escola_id;
            $dados['instituicao_id']= auth()->user()->instituicao_id;
            $dados['criado_em_roteiros'] = 1;
            if(isset($dados['ciclo_etapa_id'])&&$dados['ciclo_etapa_id']!=''){
                $c = explode(';',$dados['ciclo_etapa_id']);
                $dados['ciclo_id'] = $c[0];
                $dados['cicloetapa_id'] = $c[1];
            }
            $dados['tipo'] = $dados['tipo'] ?? 1;
            $dados['conteudo'] = $this->gravaConteudo($dados);

            $dados['status'] = 1;
            $conteudo = new Conteudo($dados);
            $conteudo->save();
            $ordem = ConteudoAula::where('curso_id',$dados['curso_id'])->where('aula_id',$dados['aula_id'])->selectRaw('max(ordem) as maxOrdem')->first();
            $ordem = $ordem->maxOrdem+1;
            $dadosConteudoAula = [
                'conteudo_id'   => $conteudo->id,
                'curso_id'      => $dados['curso_id'],
                'aula_id'       => $dados['aula_id'],
                'user_id'       => auth()->user()->id,
                'obrigatorio'   => $dados['obrigatorio'] ?? 0,
                'ordem'         => $ordem,
            ];
            $conteudoAula = new ConteudoAula($dadosConteudoAula);
            $conteudoAula->save();
            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

    public function inserirBiblioteca($dados){
        DB::beginTransaction();
        try
        {
            $ordem = ConteudoAula::where('curso_id',$dados['curso_id'])->where('aula_id',$dados['aula_id'])->selectRaw('max(ordem) as maxOrdem')->first();
            $ordem = $ordem->maxOrdem+1;
            foreach($dados['conteudosIds'] as $c) {

                $dadosConteudoAula = [
                    'conteudo_id' => $c,
                    'curso_id' => $dados['curso_id'],
                    'aula_id' => $dados['aula_id'],
                    'user_id' => auth()->user()->id,
                    'obrigatorio' => 0,
                    'ordem' => $ordem,
                ];
                $conteudoAula = new ConteudoAula($dadosConteudoAula);
                $conteudoAula->save();
                $ordem++;
            }
            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

    private function gravaConteudo($dados){
        if($dados['tipo'] == 1 || $dados['tipo'] == 10){
            return $dados['conteudo_1'];
        }elseif($dados['tipo'] == 7){
            return json_encode(['pergunta' => $dados['conteudo_1'], 'dica' => '', 'explicacao' => '']);
        }elseif($dados['tipo'] == 2 || $dados['tipo'] == 3 || $dados['tipo'] == 4 || $dados['tipo'] == 15|| $dados['tipo'] == 6){
            if(isset($dados['conteudo_'.$dados['tipo']]) && $dados['conteudo_'.$dados['tipo']] != ''){
                $caminho = config('app.frStorage').'uploads/conteudos/';
                $url = $dados['conteudo_'.$dados['tipo']]->store($caminho);
                $url = explode('/', $url);
                return $url[count($url)-1];

            }else{
                if($dados['tipo'] == 3){
                    return $this->preparaLinkVideo($dados['link_'.$dados['tipo']]);
                }
                return $dados['link_'.$dados['tipo']];
            }
        }elseif($dados['tipo'] == 8){
            return json_encode(['pergunta' => $dados['conteudo_8'], 'alternativas' => [$dados['alternativa_1'], $dados['alternativa_2'], $dados['alternativa_3']], 'correta' => $dados['correta'], 'dica' => '', 'explicacao' => '']);
        }
    }

    public function preparaLinkVideo($link){
        if(strrpos($link, "youtu")!==false){
            $youtubeCodigo = preg_replace(
                "/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i",
                "$2",
                $link
            );
            return 'https://www.youtube.com/embed/'.$youtubeCodigo;
        }
        return $link;
    }

    public function editar($dados){
        $conteudo = $this->getConteudo($dados['curso_id'], $dados['aula_id'], $dados['conteudo_id'], null,null);
        if($conteudo->criado_em_roteiros != 1){
            return false;
        }
        DB::beginTransaction();
        try
        {
            $dados['user_id']		= auth()->user()->id;
            $dados['escola_id']		= auth()->user()->escola_id;
            $dados['instituicao_id']= auth()->user()->instituicao_id;
            $dados['obrigatorio'] = $dados['obrigatorio'] ?? 0;
            if(isset($dados['ciclo_etapa_id'])&&$dados['ciclo_etapa_id']!=''){
                $c = explode(';',$dados['ciclo_etapa_id']);
                $dados['ciclo_id'] = $c[0];
                $dados['cicloetapa_id'] = $c[1];
            }
            $dados['tipo'] = $dados['tipo'] ?? 1;
            $dados['status'] = 1;
            if($dados['existe_arquivo'] != 1){
                $dados['conteudo'] = $this->gravaConteudo($dados);
                $this->removeConteudo($conteudo);
            }
            unset($conteudo->eh_link, $conteudo->nome_arquivo);
            $conteudo->update($dados);

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

    private function removeConteudo($conteudo){
        if($conteudo->criado_em_roteiros == '1'){
            if($conteudo->grava_arquivo == 1){
                if( !Str::startsWith($conteudo->conteudo,'http') ){
                    $caminho = config('app.frStorage').'uploads/conteudos/'.$conteudo->conteudo;
                    Storage::delete($caminho);
                }
            }
        }
    }

    public function getConteudo($cursoId, $temaId, $conteudoId, $matriculado = false, $trilhaId = false){
        $roteiroService = new RoteiroService();
        $dados = [];
        if(session('RoteiroBiblioteca'.$cursoId) == 1){
            $dados['biblioteca'] = 1;
        }
        $curso = $roteiroService->get($cursoId, $matriculado, $trilhaId,$dados);
        if(!$curso){
            return false;
        }
        $conteudoAula = ConteudoAula::where('curso_id', $cursoId)->where('aula_id',$temaId)->where('conteudo_id',$conteudoId)->first();
        if(!$conteudoAula){
            return false;
        }
        if($matriculado){
            $log = CursoAulaUsuarioMatricula::where([
                'trilha_id'  => $trilhaId,
                'curso_id'  => $cursoId,
                'aula_id'   => $temaId,
                'conteudo_id'=> $conteudoId,
                'user_id'   => auth()->user()->id,
            ])->first();

            $qtd = 1;
            if($log){
                $qtd = $log->qtd+1;
                $log->where([
                    'trilha_id'  => $trilhaId,
                    'curso_id'  => $cursoId,
                    'aula_id'   => $temaId,
                    'conteudo_id'=> $conteudoId,
                    'user_id'   => auth()->user()->id,
                ])->update(['qtd'=>$qtd]);
            }else {
                $log = new CursoAulaUsuarioMatricula([
                    'trilha_id'  => $trilhaId,
                    'curso_id' => $cursoId,
                    'aula_id' => $temaId,
                    'conteudo_id'=> $conteudoId,
                    'user_id' => auth()->user()->id,
                    'qtd' => $qtd,
                ]);
                $log->save();
            }

        }
        return  Conteudo::find($conteudoId);
    }

    public function delete($cursoId, $temaId, $conteudoId){
        $conteudo = $this->getConteudo($cursoId, $temaId, $conteudoId);
        if(!$conteudo){
            return false;
        }
        if($conteudo->criado_em_roteiros == 1){
            return $conteudo->update(['status'=>0]);
        }else{
            return ConteudoAula::where('curso_id',$cursoId)
                                ->where('aula_id',$temaId)
                                ->where('conteudo_id',$conteudoId)
                                ->delete();
        }

    }

    public function duplicar($cursoId, $temaId, $conteudoId, $temaIdRecebeConteudo, $cursoRecebe = null){

        DB::beginTransaction();
        try
        {
            //$conteudo = $this->getConteudo($cursoId, $temaId, $conteudoId);
            $conteudo = Conteudo::find($conteudoId);
            if(!$conteudo){
                return false;
            }
            $conteudoAulaNovo = ConteudoAula::where('curso_id', $cursoId)->where('aula_id',$temaId)->where('conteudo_id',$conteudoId)->first()->replicate();

            if($conteudo->criado_em_roteiros == 1) {
                $conteudoNovo = $conteudo->replicate();
                if($temaId == $temaIdRecebeConteudo){
                    $conteudoNovo->titulo = $conteudoNovo->titulo . ' (Cópia)';
                }
                if($conteudoNovo->grava_arquivo == 1 && $conteudoNovo->eh_link == 0 ){
                    $ext = Str::of($conteudo->conteudo)->explode('.')->last();
                    $nomeNovo = Str::random(16).Str::uuid().'.'.$ext;
                    $origem = config('app.frStorage').'uploads/conteudos/'.$conteudo->conteudo;
                    $destino = config('app.frStorage').'uploads/conteudos/'.$nomeNovo;
                    Storage::copy($origem, $destino);
                    $conteudo->conteudo = $nomeNovo;
                }
                $conteudoNovo->save();
                $conteudoAulaNovo->conteudo_id = $conteudoNovo->id;
            }
            $conteudoAulaNovo->aula_id = $temaIdRecebeConteudo;
            if($cursoRecebe){
                $conteudoAulaNovo->curso_id = $cursoRecebe;
            }
            $conteudoAulaNovo->save();

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            dd($e);
            return false;
        }
    }

    public function ordemConteudo($dados)
    {
        DB::beginTransaction();
        try
        {
            ConteudoAula::where('curso_id', $dados['curso_id'])->where('aula_id',$dados['tema_id'])->update(['ordem'=>null]);
            $i=0;
            foreach($dados['ordem'] as $c){
                ConteudoAula::where('curso_id', $dados['curso_id'])->where('aula_id',$dados['tema_id'])->where('conteudo_id',$c)->update(['ordem'=>$i]);
                $i++;
            }
            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }


    public function salvaEntregavel($dados){
        $conteudo = $this->getConteudo($dados['curso_id'], $dados['aula_id'], $dados['conteudo_id'], true, $dados['trilha_id']);
        if(!$conteudo){
            return false;
        }
        DB::beginTransaction();
        try
        {
            $dados['user_id'] = auth()->user()->id;
            $entregavel = new CursoAulaUsuarioEntregavel($dados);
            $entregavel->save();
            $caminho = config('app.frStorage').'uploads/conteudos/entregavel';
            $url = $dados['arquivo_entregavel']->store($caminho);
            $url = explode('/', $url);
            $url = $url[count($url)-1];
            $entregavel->update(['entregavel'=>$url, 'nome_arquivo'=>$dados['arquivo_entregavel']->getClientOriginalName()]);
            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

    public function salvaDiscursiva($dados){
        $conteudo = $this->getConteudo($dados['curso_id'], $dados['aula_id'], $dados['conteudo_id'], true, $dados['trilha_id']);
        if(!$conteudo){
            return false;
        }
        DB::beginTransaction();
        try
        {
            $dados['user_id'] = auth()->user()->id;
            $entregavel = new CursoAulaUsuarioDiscursiva($dados);
            $entregavel->save();
            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }



    public function caminhoDownloadEntregavel($dados)
    {
        $entregavel = CursoAulaUsuarioEntregavel::where('curso_id', $dados['curso_id'])
            ->where('aula_id', $dados['aula_id'])
            ->where('conteudo_id', $dados['conteudo_id'] )
            ->where('trilha_id', $dados['trilha_id'])
            ->where('user_id', $dados['user_id'])
            ->find($dados['id']);
        if($entregavel){
            return config('app.frStorage').'uploads/conteudos/entregavel/'.$entregavel->entregavel;
        }
        return '';
    }

    public function listaEntregavel($dados)
    {
        $conteudo = $this->getConteudo($dados['curso_id'], $dados['aula_id'], $dados['conteudo_id'], true, $dados['trilha_id']);
        if(!$conteudo){
            return false;
        }
        $entregavel = CursoAulaUsuarioEntregavel::where('curso_id', $dados['curso_id'])
            ->where('aula_id', $dados['aula_id'])
            ->where('conteudo_id', $dados['conteudo_id'] )
            ->where('trilha_id', $dados['trilha_id'])
            ->where('user_id', auth()->user()->id)
            ->get();
        $retorno = 'Não existem entregáveis enviados.';
        if(count($entregavel)>0){
            $retorno = '<br>Entregaveis enviados <br> <table class="table table-bordered">';
            foreach ($entregavel as $e) {
                $retorno .='<tr>
                                <td> entregavel_'.$e->nome_arquivo.'</td >
                            </tr >';
                }
            $retorno .= '</table>';
        }
        return $retorno;
    }

    public function getDiscursiva($dados)
    {
        $conteudo = $this->getConteudo($dados['curso_id'], $dados['aula_id'], $dados['conteudo_id'], true, $dados['trilha_id']);
        if(!$conteudo){
            return false;
        }

        $dis =  CursoAulaUsuarioDiscursiva::where('curso_id', $dados['curso_id'])
            ->where('aula_id', $dados['aula_id'])
            ->where('conteudo_id', $dados['conteudo_id'] )
            ->where('trilha_id', $dados['trilha_id'])
            ->where('user_id', auth()->user()->id)
            ->orderBy('id','desc')
            ->first();
        if($dis){
            return $dis->conteudo;
        }
        return '';
    }
}
