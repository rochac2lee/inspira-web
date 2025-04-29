<?php
namespace App\Services\Fr\Agenda;

use App\Models\FrAgendaDocumento;
use App\Models\FrAgendaDocumentosRecebidos;
use App\Models\FrTurma;
use App\Models\User;
use DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class DocumentoService {

    public function __construct()
    {
        $this->guardName = whatGuard();
    }



    public function scopoQueryDocumentosResponsavelApi($documentos,$dados){
        $documentos->join('fr_agenda_documentos_alunos','fr_agenda_documentos.id','fr_agenda_documentos_alunos.documento_id')
            ->join('users','users.id','fr_agenda_documentos_alunos.aluno_id')
            ->selectRaw('fr_agenda_documentos.*, users.id as aluno_id, users.name as aluno_name, users.nome_completo as aluno_nome_completo, users.avatar_social, users.img_perfil, fr_agenda_documentos_alunos.turma_id');

        $resp = User::with(['alunosDoResponsavel'=>function($q) use($dados){
            if(isset($dados['aluno_id']) && $dados['aluno_id']!='')
            {
                $q->where('fr_responsavel_aluno.aluno_id',$dados['aluno_id']);
            }
            $q->selectRaw('users.id');
        }])->find(auth()->user()->getRawOriginal('id'));
        $alunosResponsavel = $resp->alunosDoResponsavel;

        $documentos = $documentos->where(function($q) use($alunosResponsavel){

            foreach($alunosResponsavel as $r) {
                $q->orWhere(function ($query) use ($r){
                    $query->where('fr_agenda_documentos_alunos.aluno_id',$r->id)
                        ->where('fr_agenda_documentos_alunos.escola_id',$r->pivot->escola_id)
                        ->where('fr_agenda_documentos_alunos.instituicao_id',$r->pivot->instituicao_id);
                });
            }
        });
        return $documentos;
    }

    public function scopoQueryDocumentos($dados = null){
        $documentos = FrAgendaDocumento::with(['escola','usuario']);
        if(auth()->user()->permissao == 'R') {
            if($this->guardName == 'api') {
                $documentos = $this->scopoQueryDocumentosResponsavelApi($documentos,$dados);
            }else{
                return false;
            }
        }
        else{
            $documentos->where('instituicao_id',auth()->user()->instituicao_id)
                        ->with('recebidos');
            if(auth()->user()->permissao == 'P'){
                $documentos->where('escola_id',auth()->user()->escola_id)
                    ->where('user_id',auth()->user()->id);
            }
            elseif(auth()->user()->permissao == 'C'){
                $documentos->where('escola_id',auth()->user()->escola_id);
            }
        }


        if($dados){
            if(isset($dados['nome']) && $dados['nome']!= ''){
                $documentos->where(function($q) use($dados){
                    $q->orWhere('titulo','like','%'.$dados['nome'].'%')
                        ->orWhere('id',$dados['nome']);
                });
            }
            if(isset($dados['publicado']) && $dados['publicado']!= ''){
                $documentos->where('publicado', $dados['publicado']);
            }
        }
        if($this->guardName != 'api' && !isset($dados['editar'])){
            if(isset($dados['enviados']) && $dados['enviados'] == '1'){
                $documentos->whereNotNull('arquivo');
            }
            else{
                $documentos->whereNull('arquivo');
            }
        }

        return $documentos;
    }

    private function scopoQueryPermissaoComunicados($query, $permissao){
        if($permissao == 'P'){
            $query = $query->where('user_id',auth()->user()->id);
        }elseif($permissao == 'C'){
            $query = $query->where(function($q){
                $q->orWhere('permissao_usuario','P')
                    ->orWhere('user_id',auth()->user()->id);
            });
        }
        return $query;
    }

    public function lista($dados = null){
        $documentos = $this->scopoQueryDocumentos($dados);

        if($this->guardName == 'api'){
            ///// se for para pegar o ultimo id cadastrado para a API
            if(isset($dados['ultimo']) && $dados['ultimo'] == 1){
                return $documentos->selectRaw('max(fr_agenda_documentos.id) as ultimo')
                    ->first();
            }
        }

        return $documentos->orderBy('updated_at','DESC')
            ->paginate(20);
    }

    public function inserir($dados, $file)
    {
        DB::beginTransaction();
        try
        {
            $dados['user_id'] = auth()->user()->id;
            $dados['instituicao_id'] = auth()->user()->instituicao_id;
            $dados['escola_id'] = auth()->user()->escola_id;
            $dados['permissao_usuario'] = auth()->user()->permissao;
            $documentos = new FrAgendaDocumento($dados);
            $documentos->save();
            if($dados['tipo'] != 1) {
                $arquivo = $this->gravaArquivo($file,$documentos->id);
                if($arquivo != false){
                    $documentos->update(['arquivo'=>$arquivo, 'nome_arquivo_original'=>$file->getClientOriginalName()]);
                }
            }

            $alunos = [];
            foreach($dados['turma'] as $t){
                foreach($dados['aluno'][$t] as $a){
                    $alu = [
                        'turma_id' => $t,
                        'aluno_id' => $a,
                        'escola_id' => auth()->user()->escola_id,
                        'instituicao_id' => auth()->user()->instituicao_id,
                    ];
                    $alunos[] = $alu;
                }
            }
            $documentos->alunos()->attach($alunos);
            DB::commit();
            if($documentos->publicado == 1) {
                $this->notificacaoMobile($documentos);
            }
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

    public function excluir($id){
        DB::beginTransaction();
        try
        {
            $documentos = $this->scopoQueryDocumentos();
            $documentos = $this->scopoQueryPermissaoComunicados($documentos, auth()->user()->permissao);
            $documentos = $documentos->where('publicado',0)
                ->selectRaw('distinct fr_agenda_documentos.*')
                ->find($id);
            if($documentos){
                if($documentos->arquivo != '') {
                    $caminhoStorage = config('app.frStorage') . 'agenda/documentos/' . auth()->user()->id . '/' . $documentos->id;
                    Storage::disk()->deleteDirectory($caminhoStorage);
                }
                $documentos->alunos()->detach();
                $documentos->delete();
                DB::commit();
                return true;
            }else{
                DB::commit();
                return false;
            }
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

    public function publicar($id){
        DB::beginTransaction();
        try
        {
            $documentos = $this->scopoQueryDocumentos();
            $documentos = $this->scopoQueryPermissaoComunicados($documentos, auth()->user()->permissao);
            $documentos = $documentos->where('publicado',0)
                ->find($id);
            if($documentos){
                $documentos->update(['publicado'=>1]);
                DB::commit();
                $this->notificacaoMobile($documentos);
                return true;
            }else{
                DB::commit();
                return false;
            }
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

    private function notificacaoMobile($documento){
        $dados = [
            'id' => $documento->id,
            'titulo' => 'INspira Agenda - Documentos',
            'corpo' => $documento->titulo,
            'tipo' => 'agenda',
        ];
        $notificacao = new PushNotificationService();
        $notificacao->addNotificacaoDocumento($dados);
    }

    private function gravaArquivo($fileRequest, $idDocumento){
        try {
            $vetTipo = ['webp', 'gif', 'png', 'bmp', 'jpg', 'jpeg', 'tif'];
            $ext = $fileRequest->getClientOriginalExtension();

            if(in_array( strtolower($ext), $vetTipo)){
                return $this->gravaImagem($fileRequest, $idDocumento);
            }
            else{
                $disk = config('app.frS3Private');
                $file = $fileRequest->store(
                    config('app.frStorage').'agenda/documentos/' .auth()->user()->id.'/'.$idDocumento,
                     $disk
                );

                $file = explode('/', $file);
                return $file[count($file)-1];
            }
        }
        catch (\Exception $e)
        {
            return false;
        }
    }

    private function gravaImagem($fileRequest, $idDocumento){
        $fileName = uniqid() . '.webp';
        $img = Image::make($fileRequest);
        $img->resize(500, 500, function ($constraint) {
            $constraint->aspectRatio();
        })->encode('webp', 90);
        $resource = $img->stream()->detach();
        $caminhoStorage = config('app.frStorage') .'agenda/documentos/' .auth()->user()->id.'/'.$idDocumento.'/'.$fileName;

        $disk = config('app.frS3Private');
        Storage::disk($disk)->put($caminhoStorage, $resource);

        return $fileName;
    }

    public function get($idDocumento,$ativo=null){
        $dados['editar'] = 1;
        $documentos = $this->scopoQueryDocumentos($dados);

        $documentos->with('alunos')
                ->selectRaw('distinct fr_agenda_documentos.*');
        if($ativo != null){
            $documentos = $documentos->where('publicado',$ativo);
        }
        return $documentos->find($idDocumento);

    }

    public function update($dados,$file)
    {
        DB::beginTransaction();
        try
        {
            $documentos = $this->get($dados['id'],0);
            if((isset($dados['arquivo_novo']) && $dados['arquivo_novo'] == 1) || $file != '')
            {
                if($documentos->arquivo != '') {
                    $caminhoStorage = config('app.frStorage') . 'agenda/documentos/' . auth()->user()->id . '/' . $documentos->id.'/'.$documentos->arquivo;
                    Storage::disk()->delete($caminhoStorage);
                }
                $dados['arquivo'] = '';
                $dados['nome_arquivo_original'] = '';
                if($file != '') {
                    $arquivo = $this->gravaArquivo($file,$documentos->id);
                    if($arquivo != false){
                        $dados['arquivo'] = $arquivo;
                        $dados['nome_arquivo_original'] = $file->getClientOriginalName();
                    }
                }
            }
            $documentos->update($dados);
            $alunos = [];
            foreach($dados['turma'] as $t){
                foreach($dados['aluno'][$t] as $a){
                    $alu = [
                        'turma_id' => $t,
                        'aluno_id' => $a,
                        'escola_id' => auth()->user()->escola_id,
                        'instituicao_id' => auth()->user()->instituicao_id,
                    ];
                    $alunos[] = $alu;
                }
            }
            $documentos->alunos()->sync($alunos);
            DB::commit();
            if($documentos->publicado == 1) {
                $this->notificacaoMobile($documentos);
            }
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

    public function getTurmasSelecionadas($dados){
        $turmas = [];
        $alunos = [];
        foreach($dados['turma'] as $e){
            foreach($dados['aluno'][$e] as $t){
                $turmas[$e]= $e;
                $alunos[$t] = $t;
            }
        }
        return  FrTurma::join('ciclo_etapas','fr_turmas.ciclo_etapa_id','ciclo_etapas.id')
            ->join('ciclos','ciclo_etapas.ciclo_id','ciclos.id')
            ->with(['qtdAlunos','alunos'=>function($q) use($alunos, $dados){
                $q->join('fr_agenda_documentos_alunos',function($join){
                    $join->on('fr_agenda_documentos_alunos.aluno_id','=','fr_turma_aluno.aluno_id')
                        ->on('fr_turma_aluno.turma_id','=','fr_agenda_documentos_alunos.turma_id');
                })
                    ->whereIn('users.id',$alunos);
                if(isset($dados['documento'])){
                    $q->where('documento_id', $dados['documento']);
                }
            }])
            ->whereIn('fr_turmas.id',$turmas)
            ->orderBy('ciclos.id')
            ->orderBy('ciclo_etapas.id')
            ->orderBy('fr_turmas.titulo')
            ->selectRaw('fr_turmas.*, ciclo_etapas.titulo as ciclo_etapa, ciclos.titulo as ciclo')
            ->get();

    }

    public function gravaArquivoEnviado($request){
        DB::beginTransaction();
        try
        {
            $dados = [
                'turma_id'      => $request->input('turma_id'),
                'aluno_id'      => $request->input('aluno_id'),
                'documento_id'  => $request->input('id'),
                'responsavel_id'=> auth()->user()->id,
            ];

            $fileName = uniqid() . '.webp';
            $img = Image::make($request->file('arquivo'));
            $img->resize(1200, 1200, function ($constraint) {
                $constraint->aspectRatio();
            })->encode('webp', 90);
            $resource = $img->stream()->detach();
            $caminhoStorage = config('app.frStorage') .'agenda/documentos/recebidos/'.$dados['documento_id'].'/'.$dados['aluno_id'].'/'.auth()->user()->id.'/'.$fileName;

            $disk = config('app.frS3Private');
            Storage::disk($disk)->put($caminhoStorage, $resource);
            $dados['arquivo'] = $fileName;
            $recebidos = new FrAgendaDocumentosRecebidos($dados);
            $recebidos->save();
            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return $e->getMessage();
        }
    }

    public function getRecebidos($documentosId, $request=null){
        $tem = $this->get($documentosId,1);
        if($tem){
        $recebidos = FrAgendaDocumentosRecebidos::join('fr_turmas','fr_turmas.id', 'fr_agenda_documentos_recebidos.turma_id')
                ->join('ciclo_etapas','fr_turmas.ciclo_etapa_id','ciclo_etapas.id')
                ->join('ciclos','ciclo_etapas.ciclo_id','ciclos.id')
                ->join('escolas','escolas.id', 'fr_turmas.escola_id')
                ->join('users','users.id', 'fr_agenda_documentos_recebidos.aluno_id')
                ->where('fr_agenda_documentos_recebidos.documento_id',$documentosId)
                ->orderBy('escolas.titulo')
                ->orderBy('ciclos.id')
                ->orderBy('ciclo_etapas.id')
                ->orderBy('fr_turmas.titulo')
                ->orderBy('users.nome_completo')
                ->groupBy('escolas.id')
                ->groupBy('fr_turmas.id')
                ->groupBy('users.id')
                ->selectRaw('fr_agenda_documentos_recebidos.*, escolas.titulo as escola, fr_turmas.titulo as turma, ciclo_etapas.titulo as ciclo_etapa, ciclos.titulo as ciclo, users.nome_completo as aluno_nome_completo, users.name as aluno_nome, count(fr_agenda_documentos_recebidos.id) as qtd');

                if(isset($request['nome']) && $request['nome']!= ''){
                    $recebidos->where(function($q) use($request){
                        $q->orWhere('escolas.titulo','like', '%'.$request['nome'].'%')
                            ->orWhere('fr_turmas.titulo','like', '%'.$request['nome'].'%')
                            ->orWhere('users.name','like', '%'.$request['nome'].'%')
                            ->orWhere('users.nome_completo','like', '%'.$request['nome'].'%');
                    });
                }


        return $recebidos->paginate(20);
        }
        else{
            return [];
        }
    }

    public function getRecebidosArquivos($dados){
        $tem = $this->get($dados['documento_id'],1);
        if($tem){
            return User::whereHas('documentosEnviadosResponsavel',function($q) use ($dados){
                $q->where('documento_id',$dados['documento_id'])
                    ->where('aluno_id',$dados['aluno_id'])
                    ->where('turma_id',$dados['turma_id']);
            })->with(['documentosEnviadosResponsavel'=>function($q) use ($dados){
                    $q->where('documento_id',$dados['documento_id'])
                        ->where('aluno_id',$dados['aluno_id'])
                        ->where('turma_id',$dados['turma_id']);
                }]
            )->get();
        }
        else{
            return [];
        }
    }

    public function findRecebido($recebidoId){
        $recebido = FrAgendaDocumentosRecebidos::find($recebidoId);
        $tem = $this->get($recebido->documento_id,1);
        if($tem){
            return $recebido;
        }
    }
}
