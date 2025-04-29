<?php
namespace App\Services\Fr\Agenda;
use App\Library\Slim;
use App\Models\Escola;
use App\Models\FrAgendaCanaisAtendimento;
use App\Models\FrAgendaConfiguracaoEstilo;
use App\Models\FrAgendaConfiguracaoRotulosCalendario;
use App\Models\FrAgendaRegistroRotina;
use App\Models\FrAgendaRegistroRotinaOpet;
use App\Models\FrTurma;
use App\Services\Fr\TurmaService;
use DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Request;

class ConfiguracoesService {
    /*
    ///Estilo
    ///
    */
    public function getEstilo(){
        return FrAgendaConfiguracaoEstilo::where('instituicao_id', auth()->user()->instituicao_id)->first();
    }

    public function limpaEstilo(){
        DB::beginTransaction();
        try {
            $estilo = $this->getEstilo();
            $estilo->delete();
            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

	public function editarEstilo($dados)
    {
        DB::beginTransaction();
        try
        {
            $dados['imagem'] = null;
            $estilo = $this->getEstilo();
            if($estilo) {
                $dados['imagem'] = $estilo->imagem;
                $estilo->delete();
            }

            $dados['user_id'] = auth()->user()->id;
            $dados['instituicao_id'] = auth()->user()->instituicao_id;
            $estilo = new FrAgendaConfiguracaoEstilo($dados);
            $estilo->save();
            if($dados['existeImg'] == '') {
                $caminho = config('app.frStorage') .'agenda/configuracoes/estilo/' .auth()->user()->instituicao_id.'/imagem_inicio/' ;
                $img = [
                    'imagem' => $this->gravaImagem($caminho, 200,200),
                ];
                $estilo->update($img);
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


    private function gravaImagem($caminho, $width, $height){
        try {
            $images = Slim::getImages('imagem');
            if(count($images)>0)
            {

                $image = $images[0];
                // let's create some shortcuts
                $name = explode('.',$image['input']['name']);
                $ext = '.'.$name[count($name)-1];
                $data = $image['output']['data'];

                // store the file
                $file = Slim::saveFile($data, $ext,config('app.frTmp'));
                $fileName = $file['name'];

                $img = Image::make(config('app.frTmp').$fileName);
                $img->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                })->encode('png', 90);

                $resource = $img->stream()->detach();
                $fileName = $img->filename.'.png';

                Storage::disk()->put($caminho.$fileName, $resource);
                return $fileName;
            }
        }
        catch (\Exception $e)
        {
            return false;
        }
    }

    /*
    ///Rótulos
    ///
    */
    public function getRotulosCalendario(){
        $rotulos = FrAgendaConfiguracaoRotulosCalendario::where('instituicao_id', auth()->user()->instituicao_id)
                                        ->orderBy('ordem')
                                        ->orderBy('id')
                                        ->get();
        if(count($rotulos)==0){
            $rotulo1 = new \stdClass();
            $rotulo1->titulo = 'Tema Amarelo';
            $rotulo1->id = 'Tema Amarelo';
            //$rotulo1->cor = '#F9A500';
            $rotulo1->cor = '#FFAA00';
            $rotulo2 = new \stdClass();
            $rotulo2->titulo = 'Tema Azul';
            $rotulo2->id = 'Tema Azul';
            //$rotulo2->cor = '#1B018B';
            $rotulo2->cor = '#32D377';
            $rotulo3 = new \stdClass();
            $rotulo3->titulo = 'Tema Vermelho';
            $rotulo3->id = 'Tema Vermelho';
            //$rotulo3->cor = '#8B0800';
            $rotulo3->cor = '#B73F3F';

            $rotulos = [$rotulo1,$rotulo2,$rotulo3];
        }
        return $rotulos;
    }

    public function editarRotuloCalendario($dados)
    {
        DB::beginTransaction();
        try
        {
            FrAgendaConfiguracaoRotulosCalendario::where('instituicao_id', auth()->user()->instituicao_id)->delete();

            $dados['user_id'] = auth()->user()->id;
            $dados['instituicao_id'] = auth()->user()->instituicao_id;

            $rotulo = [];
            for($i=0;$i<count($dados['cor']); $i++)
            {
                if($dados['titulo'][$i]!= ''&& $dados['cor'][$i]!= '')
                $rotulo[] = [
                    'user_id' => auth()->user()->id,
                    'instituicao_id' => auth()->user()->instituicao_id,
                    'titulo' => $dados['titulo'][$i],
                    'cor' => $dados['cor'][$i],
                ];
            }
            FrAgendaConfiguracaoRotulosCalendario::insert($rotulo);

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

    public function excluirRotuloCalendario($dados){
        if(intval($dados['id']) > 0){
            $rotulos = FrAgendaConfiguracaoRotulosCalendario::where('instituicao_id',auth()->user()->instituicao_id)->get();
            if(count($rotulos)==1){
                return false;
            }
            return FrAgendaConfiguracaoRotulosCalendario::where('instituicao_id',auth()->user()->instituicao_id)->find($dados['id'])->delete();
        }else{
            $insert=[];
            if($dados['id']!= 'Tema Amarelo'){
                $insert[] = [
                    'user_id' => auth()->user()->id,
                    'instituicao_id' => auth()->user()->instituicao_id,
                    'titulo' => 'Tema Amarelo',
                    //'cor'    => '#F9A500',
                    'cor'    => '#FFAA00',
                ];
            }
            if($dados['id']!= 'Tema Azul'){
                $insert[] = [
                    'user_id' => auth()->user()->id,
                    'instituicao_id' => auth()->user()->instituicao_id,
                    'titulo' => 'Tema Azul',
                    //'cor'    => '#1B018B',
                    'cor'    => '#323D77',
                ];
            }
            if($dados['id']!= 'Tema Vermelho'){
                $insert[] = [
                    'user_id' => auth()->user()->id,
                    'instituicao_id' => auth()->user()->instituicao_id,
                    'titulo' => 'Tema Vermelho',
                    //'cor'    => '#8B0800',
                    'cor'    => '#B73F3F',
                ];
            }
            return FrAgendaConfiguracaoRotulosCalendario::insert($insert);
        }
    }

    /*
    ///Rotinas de registro
    ///
    */
    private function scopoQueryRotinas($tipo){
        return FrAgendaRegistroRotina::where('rotina',$tipo)
            ->where('instituicao_id',auth()->user()->instituicao_id)
            ->orderByRaw('ISNULL(ordem), ordem ASC')
            ->orderBy('titulo')
            ->get();
    }

    public function listaRotina($dados){
        $tipo = 1;
        if(isset($dados['tipo']) && $dados['tipo']!= '')
        {
            $tipo = $dados['tipo'];
        }
        $rotinas = $this->scopoQueryRotinas($tipo);
        if(count($rotinas)==0){
            $rotinaOpet = FrAgendaRegistroRotinaOpet::where('rotina',$tipo)
                ->selectRaw('fr_agenda_registros_rotinas_opet.imagem, fr_agenda_registros_rotinas_opet.ordem, fr_agenda_registros_rotinas_opet.rotina,fr_agenda_registros_rotinas_opet.titulo,  '.auth()->user()->instituicao_id.' as instituicao_id')
                ->get()->toArray();
            FrAgendaRegistroRotina::insert($rotinaOpet);
            /// copiar imagens
            foreach ($rotinaOpet as $r){
                $origem = config('app.frStorage').'agenda/registro/rotinas/'.$r['imagem'];
                $destino = config('app.frStorage').'agenda/registro/rotinas/'.auth()->user()->instituicao_id.'/'.$r['imagem'];
                if(!Storage::exists($destino)) {
                    Storage::copy($origem, $destino);
                }
            }
            $rotinas = $this->scopoQueryRotinas($tipo);
        }
        return $rotinas;
    }

    public function getEditarRotina($rotinaId){
        return FrAgendaRegistroRotina::where('instituicao_id',auth()->user()->instituicao_id)
            ->find($rotinaId);
    }

    public function updateRotina($dados){
        DB::beginTransaction();
        try
        {
            $rotinaOld = FrAgendaRegistroRotina::where('instituicao_id',auth()->user()->instituicao_id)
                ->find($dados['id']);
            $rotinaNew = $rotinaOld->replicate();

            $rotinaNew->titulo = $dados['titulo'];
            if($dados['existeImg'] == ''){
                $caminho = config('app.frStorage').'agenda/registro/rotinas/'.auth()->user()->instituicao_id.'/';
                $rotinaNew->imagem = $this->gravaImagem($caminho, 150, 150);
            }
            $rotinaNew->save();
            $rotinaOld->delete();
            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

    public function rotinasOrdem($dados){
        try {
            DB::beginTransaction();
            foreach ($dados['ordem'] as $k => $v) {
                $rotina = [
                    'ordem' => $k
                ];
                $r = FrAgendaRegistroRotina::where('instituicao_id',auth()->user()->instituicao_id)->find($v);
                $r->update($rotina);
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

    public function rotinasAtivar($dados){

        try {
            DB::beginTransaction();

            $r = FrAgendaRegistroRotina::where('instituicao_id',auth()->user()->instituicao_id)->find($dados['id']);
            $r->update(['ativo' => $dados['ativo'] ]);

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }

    }

    public function rotinasGetTurmas($dados){
        try {
            $turmaService = new TurmaService();
            $lista = $turmaService->lista($dados['escola_id'],['sem_rotina'=>1],300);
            $retorno = [];
            foreach ($lista as $l){
                $obj = new \stdClass();
                $obj->text = $l->ciclo.'/'.$l->ciclo_etapa.' - '.$l->titulo.' / '.$l->turno;
                $obj->value = $l->id;
                $retorno[] = $obj;
            }
            return $retorno;
        }
        catch (\Exception $e)
        {
            return false;
        }
    }

    public function rotinasAddTurmas($dados)
    {
        try {
            DB::beginTransaction();
            $turma = FrTurma::where('escola_id',$dados['escola_id'])
                ->whereNull('rotina_id')
                ->where('id',$dados['turma_id'])
                ->first();
            if($dados['rotina_id']==''){
                $dados['rotina_id'] = 1;
            }
            $turma->update(['rotina_id'=>$dados['rotina_id']]);

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }

    }

    public function listaRotinaTurmas($dados){
        $escolas = Escola::where('instituicao_id',auth()->user()->instituicao_id)
            ->get(['id']);
        $vetEscolas = [];
        foreach($escolas as $e){
            $vetEscolas[] = $e->id;
        }
        $turmaService = new TurmaService();
        $rotina = 1;
        if(isset($dados['tipo'])) {
            if ($dados['tipo'] != '') {
                $rotina = $dados['tipo'];
            }
        }
        return $turmaService->lista($vetEscolas,['rotina_id'=>$rotina],300);
    }

    public function rotinasRemoverTurma($turmaId){
        try {
            DB::beginTransaction();
            $escolas = Escola::where('instituicao_id',auth()->user()->instituicao_id)
                ->get(['id']);
            $vetEscolas = [];
            foreach($escolas as $e){
                $vetEscolas[] = $e->id;
            }
            $turma = FrTurma::whereIn('escola_id',$vetEscolas)->find($turmaId);
            $turma->update(['rotina_id'=>null]);
            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }
}
