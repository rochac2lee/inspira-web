<?php
namespace App\Services\Fr\Agenda;
use App\Library\Slim;
use App\Models\Escola;
use App\Models\FrAgendaCanaisAtendimento;
use App\Models\User;
use DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Request;

class CanaisAtendimentoService {

    public function __construct()
    {
        $this->guardName = whatGuard();
    }

    public function getCanaisAtendimentoResponsavelApi($dados){

        if(auth()->user()->permissao != 'R')
        {
            $escola = Escola::where('instituicao_id',auth()->user()->instituicao_id)->orderBy('titulo')->get();
            return $this->lista($dados, count($escola));
        }

        $resp = User::with(['alunosDoResponsavel'=>function($q)use($dados){
            if(isset($dados['aluno_id']) && $dados['aluno_id'] != ''){
                $q->where('aluno_id',$dados['aluno_id']);
            }
            $q->selectRaw('users.id');
        }])
            ->find(auth()->user()->getRawOriginal('id'));
        $where = [];
        foreach($resp->alunosDoResponsavel as $r){
            $where[$r->pivot->escola_id] = $r->pivot->instituicao_id;
        }
        $lista = FrAgendaCanaisAtendimento::join('fr_agenda_canais_atendimento_escolas','fr_agenda_canais_atendimentos.id','fr_agenda_canais_atendimento_escolas.canal_id')
                                        ->join('escolas','escolas.id','fr_agenda_canais_atendimento_escolas.escola_id');

        foreach ($where as $escola => $instituicao) {
            $lista->orWhere(function ($q) use ($escola, $instituicao) {
                $q->where('fr_agenda_canais_atendimento_escolas.instituicao_id', $instituicao)
                    ->where('fr_agenda_canais_atendimento_escolas.escola_id', $escola);
            });
        }
        $lista->orderByRaw('ISNULL(fr_agenda_canais_atendimento_escolas.ordem), fr_agenda_canais_atendimento_escolas.ordem ASC')
                ->selectRaw('fr_agenda_canais_atendimentos.*, escolas.titulo as escola');


        return $lista->get();
    }

    private function scopoQueryCanais($dados = null, $qtdEscola= null){
        if(auth()->user()->permissao == 'R'){
            return null;
        }
        else{
            if((isset($dados['escola']) && $dados['escola']!= '') || $qtdEscola == 1){
                $lista = FrAgendaCanaisAtendimento::join('fr_agenda_canais_atendimento_escolas','fr_agenda_canais_atendimentos.id','fr_agenda_canais_atendimento_escolas.canal_id')
                    ->where('fr_agenda_canais_atendimento_escolas.instituicao_id',auth()->user()->instituicao_id)
                    ->orderByRaw('ISNULL(fr_agenda_canais_atendimento_escolas.ordem), fr_agenda_canais_atendimento_escolas.ordem ASC');
                if($qtdEscola != 1){
                    $lista->where('fr_agenda_canais_atendimento_escolas.escola_id',$dados['escola']);
                }

            }
            else{
                $lista = FrAgendaCanaisAtendimento::whereHas('escolas',function($q) use($dados){
                    if(auth()->user()->permissao == 'I') {
                        $q->where('fr_agenda_canais_atendimento_escolas.instituicao_id',auth()->user()->instituicao_id)
                            ->orderBy('nome');

                    }else{
                        $q->where('fr_agenda_canais_atendimento_escolas.escola_id',auth()->user()->escola_id)
                        ->orderByRaw('ISNULL(fr_agenda_canais_atendimento_escolas.ordem), fr_agenda_canais_atendimento_escolas.ordem ASC');
                    }
                });
            }
        }
        if($dados){
            if(isset($dados['nome']) && $dados['nome']!= ''){
                $lista->where(function($q) use($dados){
                    $q->orWhere('nome','like','%'.$dados['nome'].'%')
                        ->orWhere('id',$dados['nome']);
                });
            }
            if(isset($dados['publicado']) && $dados['publicado']!= ''){
                $lista->where('publicado', $dados['publicado']);
            }
        }
        return $lista;
    }

    public function lista($dados, $qtdEscola = null){
        $lista = $this->scopoQueryCanais($dados, $qtdEscola);
        return $lista->selectRaw('distinct fr_agenda_canais_atendimentos.*')
            ->with('usuario')

            ->paginate(20);
    }

    public function excluir($id){
        DB::beginTransaction();
        try
        {
            $canais = $this->getEditar($id);
            if($canais->id){
                $canais = FrAgendaCanaisAtendimento::find($canais->id);
                $canais->escolas()->detach();
                $this->apagarImagem($canais,1);
                $canais->delete();
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

    public function publicar($id, $publicar){
        DB::beginTransaction();
        try
        {
            $canais = $this->getEditar($id);
            if($canais->id){
                $canais = FrAgendaCanaisAtendimento::find($canais->id);
                $dados['publicado'] = 1;
                if($publicar == 1){
                    $dados['publicado'] = 0;
                }
                $canais->update($dados);
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

    public function getEditar($id){
        try {
            $canais = $this->scopoQueryCanais();
            $canais = $canais->with('escolas')
                ->selectRaw('distinct fr_agenda_canais_atendimentos.*')
                ->find($id);
            return $canais;
        }
        catch (\Exception $e)
        {
            return false;
        }


    }

	public function inserir($dados)
    {
        DB::beginTransaction();
        try
        {
            $dados['user_id'] = auth()->user()->id;
            $dados['instituicao_id'] = auth()->user()->instituicao_id;
            unset($dados['imagem']);
            $canal = new FrAgendaCanaisAtendimento($dados);
            $canal->save();
            $img = [
                'imagem' => $this->gravaImagem($canal->id),
            ];
            $canal->update($img);
            $escolas = [];
            if($dados['visualizacao']==  1) {
                $dados['escola'] = Escola::where('instituicao_id',auth()->user()->instituicao_id)
                            ->get(['id']);
            }
            foreach ($dados['escola'] as $e) {
                if($dados['visualizacao']==  1){
                    $e = $e->id;
                }
                $escola = [
                    'escola_id' => $e,
                    'instituicao_id' => auth()->user()->instituicao_id,
                ];
                $escolas[] = $escola;
            }
            $canal->escolas()->attach($escolas);
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

    public function update($dados)
    {
        DB::beginTransaction();
        try
        {
            $canais = $this->getEditar($dados['id']);
            if($canais->id>0)
            {
                $canais = FrAgendaCanaisAtendimento::find($canais->id);
            }else{
                return false;
            }

            $publicado = 0;
            if(isset($dados['publicado']) && $dados['publicado'] == 1)
            {
                $publicado = 1;
            }
            $dados['publicado'] = $publicado;


            unset($dados['imagem']);

            if(!isset($dados['telefone_eh_zap'])){
                $dados['telefone_eh_zap'] = 0;
            }

            if($dados['existeImg'] == ''){
                $this->apagarImagem($canais);
                $dados['imagem'] = $this->gravaImagem($canais->id);
            }

            $canais->update($dados);
            $escolas = [];
            foreach($dados['escola'] as $e){
                $escola = [
                        'escola_id' => $e,
                        'instituicao_id' => auth()->user()->instituicao_id,
                    ];
                $escolas[] = $escola;
            }
            $canais->escolas()->sync($escolas);

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

    private function gravaImagem($idCanal){
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
                $img->resize(200, 200, function ($constraint) {
                    $constraint->aspectRatio();
                })->encode('webp', 90);

                $resource = $img->stream()->detach();
                $fileName = $img->filename.'.webp';

                Storage::disk()->put(config('app.frStorage') .'agenda/canais_atendimento/' .auth()->user()->id.'/'.$idCanal.'/'.$fileName, $resource);
                return $fileName;
            }
        }
        catch (\Exception $e)
        {
            return false;
        }
    }

    public function getEscolasSelecionados($dados){
        return Escola::whereIn('id',$dados)
            ->orderBy('titulo')
            ->get();
    }

    public function getExibir($idComunicado){
        $canais = $this->scopoQueryCanais();
        $canais = $canais->selectRaw('distinct fr_agenda_comunicados.*')
            ->with('imagens')
            ->with('usuario')
            ->find($idComunicado);

        return $canais;
    }

    private function apagarImagem($canal, $ehDiretorio = null){
        if($ehDiretorio){
            $caminhoStorage = config('app.frStorage') . 'agenda/canais_atendimento/' . $canal->user_id . '/' . $canal->id ;
            return Storage::disk()->deleteDirectory($caminhoStorage);
        }else {
            $caminhoStorage = config('app.frStorage') . 'agenda/canais_atendimento/' . $canal->user_id . '/' . $canal->id . '/' . $canal->imagem;
            return Storage::disk()->delete($caminhoStorage);
        }
    }

    public function ordem($dados){
        try {
            DB::beginTransaction();
            foreach ($dados['ordem'] as $k => $v) {
                $escola = [
                    'escola_id' => $dados['escola'],
                    'instituicao_id' => auth()->user()->instituicao_id,
                    'ordem' => $k
                ];

                $canal = FrAgendaCanaisAtendimento::find($v);
                $canal->escolas()->wherePivot('escola_id', $dados['escola'])->detach();
                $canal->escolas()->attach([$escola]);
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
}
