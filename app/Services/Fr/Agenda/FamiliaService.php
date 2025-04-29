<?php
namespace App\Services\Fr\Agenda;
use App\Models\FrAgendaFamilia;
use App\Models\FrAgendaFamiliaEscola;
use App\Models\FrAgendaFamiliaImagens;
use App\Models\Instituicao;
use DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class FamiliaService {

    public function __construct()
    {
        $this->guardName = whatGuard();
    }

    private function scopoQueryFamilia($dados = null){
        $lista = FrAgendaFamilia::join('fr_agenda_espaco_familia_escolas', 'familia_id', 'fr_agenda_espaco_familia.id');
        if(auth()->user()->permissao != 'Z'){
            $lista->where(function($q){
                $q->orWhere(function($query){
                    $query->where('fr_agenda_espaco_familia_escolas.escola_id',auth()->user()->escola_id);
                });
                $q->orWhere(function($query){
                    $query->where('fr_agenda_espaco_familia_escolas.instituicao_id',auth()->user()->instituicao_id)
                        ->where('fr_agenda_espaco_familia_escolas.escola_id',0);
                });
                $q->orWhere(function($query){
                    $query->where('fr_agenda_espaco_familia_escolas.instituicao_id',0)
                        ->where('fr_agenda_espaco_familia_escolas.escola_id',0)
                        ->where('fr_agenda_espaco_familia_escolas.publico',0)
                        ->where('fr_agenda_espaco_familia_escolas.privado',0);
                });
                $q->orWhere(function($query){
                    if($this->guardName == 'web') {
                        $instituicao = session('instituicao');
                    }else{
                        $instituicao = Instituicao::find(auth()->user()->instituicao_id);
                        $instituicao=['tipo'=>$instituicao->instituicao_tipo_id];
                    }
                    $query->where('fr_agenda_espaco_familia_escolas.instituicao_id',0)
                        ->where('fr_agenda_espaco_familia_escolas.escola_id',0);
                    if($instituicao['tipo'] == 1){ /// privado
                        $query->where('fr_agenda_espaco_familia_escolas.privado',1);
                    }elseif($instituicao['tipo'] == 2){ /// publico
                        $query->where('fr_agenda_espaco_familia_escolas.publico',1);
                    }
                });
            })
            ->where('publicado', 1);
        }

        if($dados){
            if(isset($dados['nome']) && $dados['nome']!= ''){
                $lista->where(function($q) use($dados){
                    $q->orWhere('titulo','like','%'.$dados['nome'].'%')
                        ->orWhere('id',$dados['nome']);
                });
            }
            if(isset($dados['publicado']) && $dados['publicado']!= ''){
                $lista->where('publicado', $dados['publicado']);
            }
        }
        return $lista;
    }

    public function lista($dados){
        $lista = $this->scopoQueryFamilia($dados);
        if($this->guardName == 'api'){
            ///// se for para pegar o ultimo id cadastrado para a API
            if(isset($dados['ultimo']) && $dados['ultimo'] == 1){
                return $lista->selectRaw('max(fr_agenda_espaco_familia.id) as ultimo')
                    ->orderBy('updated_at','DESC')
                    ->first();
            }
            else{
                $lista = $lista->with('imagens');
            }
        }
        return $lista->selectRaw('distinct fr_agenda_espaco_familia.*')
            ->with('usuario')
            ->orderBy('updated_at','DESC')
            ->paginate(20);
    }

    public function excluir($id){
        DB::beginTransaction();
        try
        {
            $familia = $this->scopoQueryFamilia();
            $familia = $familia->where('publicado',0)
                                    ->with('imagens')
                                    ->selectRaw('distinct fr_agenda_espaco_familia.*')
                                    ->find($id);
            if($familia){
                $familia->escolas()->detach();
                foreach ($familia->imagens as $i){
                    $this->apagarImg($familia->id, $i->id);
                }
                $caminhoStorage = config('app.frStorage') .'agenda/familia/' .auth()->user()->id.'/'.$familia->id;
                Storage::disk()->deleteDirectory($caminhoStorage);
                $familia->delete();
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
            $familia = $this->scopoQueryFamilia();
            $familia = $familia->where('publicado',0)
                            ->selectRaw('distinct fr_agenda_espaco_familia.*')
                            ->find($id);
            if($familia){
                $familia->update(['publicado'=>1]);
                DB::commit();
                $this->notificacaoMobile($familia);
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

    private function notificacaoMobile($familia){
        $dados = [
            'id' => $familia->id,
            'titulo' => 'INspira Agenda - Espaço da Família',
            'corpo' => $familia->titulo,
            'tipo' => 'agenda',
        ];
        $notificacao = new PushNotificationService();
        $notificacao->addNotificacaoFamilia($dados);
    }

    public function getEditar($id){
        try {
            $familia = $this->scopoQueryFamilia();
            $familia = $familia->where('publicado', 0)
                ->with('imagens')
                ->selectRaw('distinct fr_agenda_espaco_familia.*')
                ->find($id);

            $escolas = FrAgendaFamiliaEscola::where('familia_id',$id)->get();
            $familia->escolas = $escolas;
            return $familia;
        }
        catch (\Exception $e)
        {
            return false;
        }


    }

    private function preparaEscolas($dados){
        $escolas = [];
        if($dados['vizualizacao'] == 1){
            $escolas[]=[
                'escola_id'     => 0,
                'instituicao_id'=> 0,
                'publico'       => 0,
                'privado'       => 0,
            ];
        }elseif($dados['vizualizacao'] == 2) {
            $escolas[]=[
                'escola_id'     => 0,
                'instituicao_id'=> 0,
                'publico'       => 1,
                'privado'       => 0,
            ];
        }elseif($dados['vizualizacao'] == 3) {
            $escolas[]=[
                'escola_id'     => 0,
                'instituicao_id'=> 0,
                'publico'       => 0,
                'privado'       => 1,
            ];
        }elseif($dados['vizualizacao'] == 4) {
            foreach($dados['instituicao'] as $e){
                foreach($dados['escola'][$e] as $t){
                    $escola = [
                        'escola_id' => $t,
                        'instituicao_id' => $e,
                    ];
                    $escolas[] = $escola;
                }
            }
        }
        return $escolas;
    }

	public function inserir($dados, $file)
    {
        DB::beginTransaction();
        try
        {
            $dados['user_id'] = auth()->user()->id;
            $dados['permissao_usuario'] = auth()->user()->permissao;
            $familia = new FrAgendaFamilia($dados);
            $familia->save();

            $escolas = $this->preparaEscolas($dados);
            if($file != '') {
                $img = [
                    'caminho' => $this->gravaImagem($file, $familia->id),
                ];
                $img = new FrAgendaFamiliaImagens($img);
                $familia->imagens()->save($img);
            }
            $familia->escolas()->attach($escolas);
            DB::commit();

            if($familia->publicado == 1) {
                $this->notificacaoMobile($familia);
            }

            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

    public function update($dados)
    {
        DB::beginTransaction();
        try
        {
            $familia = $this->scopoQueryFamilia();
            $familia = $familia->where('publicado',0)
                ->selectRaw('distinct fr_agenda_espaco_familia.*')
                ->find($dados['id']);

            $familia->update($dados);
            $familia->escolas()->detach();

            $escolas = $this->preparaEscolas($dados);
            $familia->escolas()->attach($escolas);
            DB::commit();

            if($familia->publicado == 1) {
                $this->notificacaoMobile($familia);
            }
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

    private function gravaImagem($fileRequest,$idFamilia){
        try {
            $fileName = uniqid() . '.webp';
            $img = Image::make($fileRequest);
            $img->resize(500, 500, function ($constraint) {
                $constraint->aspectRatio();
            })->encode('webp', 90);
            $resource = $img->stream()->detach();
            $caminhoStorage = config('app.frStorage') .'agenda/familia/' .auth()->user()->id.'/'.$idFamilia.'/'.$fileName;
            Storage::disk()->put($caminhoStorage, $resource);
            return $fileName;
        }
        catch (\Exception $e)
        {
            return false;
        }
    }

    public function uploadImagensAjax($idFamilia, $fileRequest){
        try {
            $familia = $this->scopoQueryFamilia();
            $familia = $familia->where('publicado', 0)
                ->selectRaw('distinct fr_agenda_espaco_familia.*')
                ->find($idFamilia);
            if($familia){
                $img = [
                    'caminho' => $this->gravaImagem($fileRequest,$familia->id),
                ];
                if($img['caminho']){
                    $img = new FrAgendaFamiliaImagens($img);
                    $img = $familia->imagens()->save($img);
                    return [
                        'id' => $img->id,
                        'caminho' => $img->caminho,
                    ];
                }else{
                    return false;
                }
            }
            else{
                return false;
            }
        }
        catch (\Exception $e)
        {
            return false;
        }
    }

    public function excluirImagem($idFamilia, $idImagem){
        try {
            $familia = $this->scopoQueryFamilia();
            $familia = $familia->where('publicado', 0)
                ->selectRaw('distinct fr_agenda_espaco_familia.*')
                ->find($idFamilia);
            if($familia) {
                return $this->apagarImg($idFamilia, $idImagem);
            }
            else{
                return false;
            }
        }
        catch (\Exception $e)
        {
            return false;
        }
    }

    private function apagarImg($idFamilia, $idImagem){
        $img = FrAgendaFamiliaImagens::find($idImagem);
        $caminhoStorage = config('app.frStorage') .'agenda/familia/' .auth()->user()->id.'/'.$idFamilia.'/'.$img->caminho;
        Storage::disk()->delete($caminhoStorage);
        return $img->delete();
    }

    public function getListaInstituicao($dados){
        $inst =  Instituicao::with('escolas')
            ->orderBy('titulo');
        if(isset($dados['nome']) && $dados['nome']!=''){
            $inst->where('titulo', 'like', '%'.$dados['nome'].'%');
        }
        if(isset($dados['nomeEscola']) && $dados['nomeEscola']!=''){
            $inst->whereHas('escolas', function($q) use ($dados){
                $q->where('titulo', 'like', '%'.$dados['nomeEscola'].'%');
            });
        }
        if(isset($dados['tipo']) && $dados['tipo']!='-1'){
            $inst->where('instituicao_tipo_id', $dados['tipo']);
        }
        return $inst->paginate(10);
    }

    public function getEscolasTurmasSelecionados($dados){
        $instituicao = [];
        $escolas = [];
        foreach($dados['instituicao'] as $e){
            foreach($dados['escola'][$e] as $t){
                $instituicao[$e]= $e;
                $escolas[$t] = $t;
            }
        }
        return Instituicao::with(['escolas'=>function($q) use($escolas){
                        $q->whereIn('escolas.id',$escolas);
                    }])
            ->whereIn('id',$instituicao)
            ->orderBy('titulo')
            ->get();
    }

    public function ordernarImagem($idFamilia, $dados){
        DB::beginTransaction();
        try
        {
            $familia = $this->scopoQueryFamilia();
            $familia = $familia->where('publicado', 0)
                ->selectRaw('distinct fr_agenda_comunicados.*')
                ->find($idFamilia);
            if($familia) {
                $i = 0;
                foreach ($dados['ordem'] as $d){
                    $img = FrAgendaFamiliaImagens::where('comunicado_id',$idFamilia)->find($d);
                    $img->update(['ordem'=>$i]);
                    $i++;
                }
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

    public function getExibir($idFamilia){
        $familia = $this->scopoQueryFamilia();
        $familia = $familia->selectRaw('distinct fr_agenda_espaco_familia.*')
            ->with('imagens')
            ->with('usuario')
            ->find($idFamilia);

        return $familia;
    }
}
