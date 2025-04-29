<?php
namespace App\Services\Fr\Agenda;
use App\Models\Escola;
use App\Models\FrAgendaNoticiaImagens;
use App\Models\FrAgendaNoticias;
use App\Models\FrAgendaNoticiasTurmas;
use App\Models\User;
use DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class NoticiaService {

    public function __construct()
    {
        $this->guardName = whatGuard();
    }

    private function alunosDoResponsavel(){

        $resp = User::with(['alunosDoResponsavel'=>function($q){
            $q->selectRaw('users.id');
        }])
            ->find(auth()->user()->getRawOriginal('id'));

        return $resp->alunosDoResponsavel;
    }

    private function scopoQueryNoticiasResponsavel(){
        return FrAgendaNoticias::join('fr_agenda_noticias_turmas', 'agenda_noticia_id', 'fr_agenda_noticias.id')
                ->where(function($query){
                    $alunos = $this->alunosDoResponsavel();
                    foreach ($alunos as $a) {
                        $query->orWhere(function ($qq) use($a){
                            $qq->where('fr_agenda_noticias_turmas.instituicao_id', $a->pivot->instituicao_id)
                                ->where(function ($q) use($a){
                                    $q->orWhere('fr_agenda_noticias_turmas.escola_id', $a->pivot->escola_id)
                                        ->orWhere('fr_agenda_noticias_turmas.escola_id', 0);
                                });
                        });
                    }
                });
    }

    private function scopoQueryNoticias($dados = null){
        if(auth()->user()->permissao == 'R')
        {
            $lista = $this->scopoQueryNoticiasResponsavel();
        }else {
            $lista = FrAgendaNoticias::join('fr_agenda_noticias_turmas', 'agenda_noticia_id', 'fr_agenda_noticias.id')
                ->where('fr_agenda_noticias_turmas.instituicao_id', auth()->user()->instituicao_id);
            if (auth()->user()->permissao != 'I') {
                $lista->where(function ($q) {
                    $q->orWhere('fr_agenda_noticias_turmas.escola_id', auth()->user()->escola_id)
                        ->orWhere('fr_agenda_noticias_turmas.escola_id', 0);
                });
            }
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

        if(auth()->user()->permissao == 'P'){
            $lista->where('publicado', 1);
        }
        return $lista;
    }

    private function scopoQueryPermissaoNoticias($query, $permissao){
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

    public function lista($dados){
        $lista = $this->scopoQueryNoticias($dados);
        if($this->guardName == 'api'){
            ///// se for para pegar o ultimo id cadastrado para a API
            if(isset($dados['ultimo']) && $dados['ultimo'] == 1){
                return $lista->selectRaw('max(fr_agenda_noticias.id) as ultimo')
                    ->first();
            }
            else{
                $lista = $lista->with('imagens');
            }
        }
        return $lista->selectRaw('distinct fr_agenda_noticias.*')
            ->with('usuario')
            ->orderBy('updated_at','DESC')
            ->paginate(20);
    }

    public function excluir($id){
        DB::beginTransaction();
        try
        {
            $noticia = $this->scopoQueryNoticias();
            $noticia = $this->scopoQueryPermissaoNoticias($noticia, auth()->user()->permissao);
            $noticia = $noticia->where('publicado',0)
                                    ->with('imagens')
                                    ->selectRaw('distinct fr_agenda_noticias.*')
                                    ->find($id);
            if($noticia){
                $noticia->turmas()->detach();
                foreach ($noticia->imagens as $i){
                    $this->apagarImg($noticia->id, $i->id);
                }
                $caminhoStorage = config('app.frStorage') .'agenda/noticias/' .auth()->user()->id.'/'.$noticia->id;
                Storage::disk()->deleteDirectory($caminhoStorage);
                $noticia->delete();
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
            $noticia = $this->scopoQueryNoticias();
            $noticia = $this->scopoQueryPermissaoNoticias($noticia, auth()->user()->permissao);
            $noticia = $noticia->where('publicado',0)
                            ->selectRaw('distinct fr_agenda_noticias.*')
                            ->find($id);
            if($noticia){
                $noticia->update(['publicado'=>1]);
                DB::commit();
                $this->notificacaoMobile($noticia);
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

    private function notificacaoMobile($noticia){
        $dados = [
            'id' => $noticia->id,
            'titulo' => 'INspira Agenda - Fotos e Notícias',
            'corpo' => $noticia->titulo,
            'tipo' => 'agenda',
        ];
        $notificacao = new PushNotificationService();
        $notificacao->addNotificacaoNoticia($dados);
    }

    public function getEditar($id){
        try {
            $noticia = $this->scopoQueryNoticias();
            $noticia = $this->scopoQueryPermissaoNoticias($noticia, auth()->user()->permissao);
            $noticia = $noticia->where('publicado', 0)
                ->with('imagens')
                ->selectRaw('distinct fr_agenda_noticias.*')
                ->find($id);
            $turmas = FrAgendaNoticiasTurmas::where('agenda_noticia_id', $noticia->id)->get();

            $noticia->turmas = $turmas;
            return $noticia;
        }
        catch (\Exception $e)
        {
            return false;
        }


    }

	public function inserir($dados, $file)
    {
        DB::beginTransaction();
        try
        {
            $dados['user_id'] = auth()->user()->id;
            $dados['permissao_usuario'] = auth()->user()->permissao;
            $noticia = new FrAgendaNoticias($dados);
            $noticia->save();
            $turmas = [];
            foreach($dados['escola'] as $e){
                foreach($dados['turma'][$e] as $t){
                    $turma = [
                        'turma_id' => $t,
                        'escola_id' => $e,
                        'instituicao_id' => auth()->user()->instituicao_id,
                    ];
                    $turmas[] = $turma;
                }
            }
            if($file != '') {
                $img = [
                    'caminho' => $this->gravaImagem($file, $noticia->id),
                ];
                $img = new FrAgendaNoticiaImagens($img);
                $noticia->imagens()->save($img);
            }
            $noticia->turmas()->attach($turmas);
            DB::commit();

            if($noticia->publicado == 1){
                $this->notificacaoMobile($noticia);
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
            $noticia = $this->scopoQueryNoticias();
            $noticia = $this->scopoQueryPermissaoNoticias($noticia, auth()->user()->permissao);
            $noticia = $noticia->where('publicado',0)
                ->selectRaw('distinct fr_agenda_noticias.*')
                ->find($dados['id']);

            $noticia->update($dados);
            $noticia->turmas()->detach();
            $turmas = [];

            foreach($dados['escola'] as $e){
                foreach($dados['turma'][$e] as $t){
                    $turma = [
                        'turma_id' => $t,
                        'escola_id' => $e,
                        'instituicao_id' => auth()->user()->instituicao_id,
                    ];
                    $turmas[] = $turma;
                }
            }
            $noticia->turmas()->attach($turmas);
            DB::commit();

            if($noticia->publicado == 1){
                $this->notificacaoMobile($noticia);
            }
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

    private function gravaImagem($fileRequest,$idNoticia){
        try {
            $fileName = uniqid() . '.webp';
            $img = Image::make($fileRequest);
            $img->resize(500, 500, function ($constraint) {
                $constraint->aspectRatio();
            })->encode('webp', 90);
            $resource = $img->stream()->detach();
            $caminhoStorage = config('app.frStorage') .'agenda/noticias/' .auth()->user()->id.'/'.$idNoticia.'/'.$fileName;
            Storage::disk()->put($caminhoStorage, $resource);
            return $fileName;
        }
        catch (\Exception $e)
        {
            return false;
        }
    }

    public function uploadImagensAjax($idNoticia, $fileRequest){
        try {
            $noticia = $this->scopoQueryNoticias();
            $noticia = $noticia->where('publicado', 0)
                ->selectRaw('distinct fr_agenda_noticias.*')
                ->find($idNoticia);
            if($noticia){
                $img = [
                    'caminho' => $this->gravaImagem($fileRequest,$noticia->id),
                ];
                if($img['caminho']){
                    $img = new FrAgendaNoticiaImagens($img);
                    $img = $noticia->imagens()->save($img);
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

    public function excluirImagem($idNoticia, $idImagem){
        try {
            $noticia = $this->scopoQueryNoticias();
            $noticia = $this->scopoQueryPermissaoNoticias($noticia, auth()->user()->permissao);
            $noticia = $noticia->where('publicado', 0)
                ->selectRaw('distinct fr_agenda_noticias.*')
                ->find($idNoticia);
            if($noticia) {
                return $this->apagarImg($idNoticia, $idImagem);
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

    private function apagarImg($idNoticia, $idImagem){
        $img = FrAgendaNoticiaImagens::find($idImagem);
        $caminhoStorage = config('app.frStorage') .'agenda/noticias/' .auth()->user()->id.'/'.$idNoticia.'/'.$img->caminho;
        Storage::disk()->delete($caminhoStorage);
        return $img->delete();
    }

    public function getEscolasTurmasSelecionados($dados){
        $escolas = [];
        $turmas = [];
        foreach($dados['escola'] as $e){
            foreach($dados['turma'][$e] as $t){
                $escolas[$e]= $e;
                $turmas[$t] = $t;
            }
        }
        return Escola::with(['turmas'=>function($q) use($turmas){
                        $q->whereIn('fr_turmas.id',$turmas);
                    }])
            ->whereIn('id',$escolas)
            ->orderBy('titulo')
            ->get();
    }

    public function ordernarImagem($idNoticia, $dados){
        DB::beginTransaction();
        try
        {
            $noticia = $this->scopoQueryNoticias();
            $noticia = $this->scopoQueryPermissaoNoticias($noticia, auth()->user()->permissao);
            $noticia = $noticia->where('publicado', 0)
                ->selectRaw('distinct fr_agenda_noticias.*')
                ->find($idNoticia);
            if($noticia) {
                $i = 0;
                foreach ($dados['ordem'] as $d){
                    $img = FrAgendaNoticiaImagens::where('noticia_id',$idNoticia)->find($d);
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

    public function getExibir($idNoticia){
        $noticia = $this->scopoQueryNoticias();
        $noticia = $noticia->selectRaw('distinct fr_agenda_noticias.*')
            ->with('imagens')
            ->with('usuario')
            ->find($idNoticia);

        return $noticia;
    }
}
