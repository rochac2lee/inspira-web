<?php
namespace App\Services\Fr\Agenda;
use App\Models\FrAgendaCalendario;
use App\Models\FrAgendaTarefa;
use App\Models\User;
use Carbon\Carbon;
use DB;

class CalendarioService {

    public function __construct()
    {
        $this->guardName = whatGuard();
    }

    private function escolasAlunosDoResponsavel($dados){
        $alunos=[];
        if(isset($dados['aluno_id']) && $dados['aluno_id']!=''){
            $alunos[] = $dados['aluno_id'];
        }
        $resp = User::with(['alunosDoResponsavel'=>function($q) use($alunos){
            if(count($alunos)>0){
                $q->whereIn('users.id',$alunos);
            }
        }])
            ->find(auth()->user()->getRawOriginal('id'));
        $escolas =[];
        $instituicao =[];
        foreach($resp->alunosDoResponsavel as $r){
            $escolas[] = $r->pivot->escola_id;
            $instituicao[] = $r->pivot->instituicao_id;
        }

        return ['escolas' => $escolas, 'instituicao'=>$instituicao];
    }

    public function getCalendarioResposavelApi( $dados){
        $dadosEscolas = $this->escolasAlunosDoResponsavel($dados);
        return FrAgendaCalendario::join('fr_agenda_calendario_escola','fr_agenda_calendario_escola.calendario_id','fr_agenda_calendario.id')
            ->where(function($q) use($dadosEscolas){
                $q->whereIn('fr_agenda_calendario_escola.escola_id',$dadosEscolas['escolas']);
                $q->orWhere(function($query) use($dadosEscolas){
                    foreach($dadosEscolas['instituicao'] as $i){
                        $query->where('instituicao_id',$i)
                            ->where('escola_id',0);
                    }
                });
            });
    }

    public function getCalendarioApi($dados = null){
        $data = Carbon::now()->subMonths(12)->format('Y-m-d');
        $data = explode('-',$data);
        $data = $data[0].'-'.$data[1].'-31 23:59:59';

        if(auth()->user()->permissao == 'R'){
            $lista = $this->getCalendarioResposavelApi( $dados);
        }else{
            $lista = $this->scopoQuery();

        }

        if($this->guardName == 'api'){
            ///// se for para pegar o ultimo id cadastrado para a API
            if(isset($dados['ultimo']) && $dados['ultimo'] == 1){
                return $lista->selectRaw('max(fr_agenda_calendario.id) as ultimo')
                    ->first();
            }
        }

        $lista = $lista->with('usuario')
            ->where('data_final','>',$data)
            ->selectRaw('fr_agenda_calendario.*')
            ->get();
        return $this->formataDadosParaApp($lista);

    }

    private function formataDadosParaApp($lista){
        $calendario = [];
        foreach($lista as $l){
            $objCalendario = new \stdClass();
            $objCalendario->id = $l->id;
            $objCalendario->titulo = $l->titulo;
            $objCalendario->descricao = $l->descricao;
            $objCalendario->dia_inteiro = (boolean) $l->dia_inteiro;
            if($l->dia_inteiro == 1) {
                $objCalendario->data_inicial = $l->data_inicial->format('d/m/Y');
                $objCalendario->data_final = $l->data_final->format('d/m/Y');
            }else{
                $objCalendario->data_inicial = $l->data_inicial->format('d/m/Y H:i:s');
                $objCalendario->data_final = $l->data_final->format('d/m/Y H:i:s');
            }
            $objCalendario->tema = $l->tema;
            $objCalendario->proprietario_nome = $l->usuario->nome;
            $objCalendario->proprietario_avatar = $l->usuario->avatar;

            $data = $l->data_inicial->format('Y-m-d');

            $diffInicial = new Carbon($l->data_inicial->format('Y-m-d'));
            $diffFinal = new Carbon($l->data_final->format('Y-m-d'));
            if($diffInicial->diffInDays($diffFinal) == 0) {
                $calendario[$data][] =$objCalendario;
            }else{
                $fim = 0;
                while ($fim == 0){
                    $calendario[$data][] = $objCalendario;

                    $dataFinal = $l->data_final->format('Y-m-d');
                    $dataFinal = new Carbon($dataFinal);
                    $data = new Carbon($data);
                    if($dataFinal->lte($data)){
                        $fim = 1;
                    }
                    else{
                        $data = $data->addDay();
                        $data = $data->format('Y-m-d');
                    }
                }
            }
        }
        $retorno =[];
        foreach($calendario as $chave => $valor){
            $retorno[] = [
                'data'      => $chave,
                'eventos'   => $valor,
            ];
        }

        return $retorno;
    }

    private function scopoQuery(){
        $lista = FrAgendaCalendario::join('fr_agenda_calendario_escola','fr_agenda_calendario_escola.calendario_id','fr_agenda_calendario.id')
            ->where('fr_agenda_calendario_escola.instituicao_id',auth()->user()->instituicao_id);

        if(auth()->user()->permissao != 'I'){
            $lista->where(function($q){
                $q->orWhere('fr_agenda_calendario_escola.escola_id',auth()->user()->escola_id)
                    ->orWhere('fr_agenda_calendario_escola.escola_id',0);
            });
        }
        return $lista;
    }

    public function lista($dados)
    {
        $start = new Carbon($dados['start']);
        $start = $start->format('Y-m-d 00:00:00');
        $end = new Carbon($dados['end']);
        $end = $end->format('Y-m-d 23:59:59');

        $lista = $this->scopoQuery();
        $lista = $lista->where(function ($q) use($start,$end){
                $q->orWhere(function($query) use($start,$end){
                        $query->where('data_inicial','>=', $start)->where('data_final','<=', $end);
                })->orWhere(function ($query) use($start,$end){
                        $query->where('data_inicial','<=', $start)->where('data_final','>=', $end);
                })->orWhere(function ($query) use($start,$end){
                        $query->where('data_inicial','<=', $start)->where('data_final','<=', $end)->where('data_final','>=', $start);
                })->orWhere(function ($query) use($start,$end){
                        $query->where('data_inicial','>=', $start)->where('data_inicial','<=', $end)->where('data_final','>=', $end);
                });
        });

        $lista = $lista->selectRaw('distinct fr_agenda_calendario.*')->get();

        $retorno = [];
        foreach ($lista as $l){
            $obj = new \stdClass();
            $obj->id = $l->id;
            $obj->title = $l->titulo;
            if($l->dia_inteiro == '1') {
                $obj->start = $l->data_inicial->format('Y-m-d');
                $obj->end = $l->data_final->format('Y-m-d');
            }
            else {
                $obj->start = $l->data_inicial->format('Y-m-d H:i:s');
                $obj->end = $l->data_final->format('Y-m-d H:i:s');
            }
            $obj->backgroundColor = $l->tema;
            $obj->borderColor = $l->tema;
            $obj->editavel = 0;
            if(auth()->user()->permissao == 'P'){
               if($l->user_id ==  auth()->user()->id){
                   $obj->editavel = 1;
               }
            }elseif(auth()->user()->permissao == 'C'){
                if($l->user_id ==  auth()->user()->id || $l->permissao_usuario == 'P'){
                    $obj->editavel = 1;
                }
            }elseif(auth()->user()->permissao == 'I'){
                $obj->editavel = 1;
            }
            $retorno[] = $obj;
        }
        return $retorno;
    }

    public function get($calendarioId){
        $evento = $this->scopoQuery();
        $evento = $evento->with(['usuario', 'escolas'=>function($q){$q->selectRaw('id');}])
                    ->selectRaw('distinct fr_agenda_calendario.*')
                    ->find($calendarioId);
        $eventoGet = new \stdClass();
        $eventoGet->usuario = new \stdClass();
        $eventoGet->usuario->nome = $evento->usuario->nome;
        $eventoGet->usuario->avatar = $evento->usuario->avatar;

        $eventoGet->dia_inteiro = $evento->dia_inteiro;
        $formato = 'd/m/Y H:i:s';
        if($eventoGet->dia_inteiro == 1){
            $formato = 'd/m/Y';
        }
        $eventoGet->data_inicial = $evento->data_inicial->format($formato);
        $eventoGet->data_final = $evento->data_final->format($formato);

        $eventoGet->descricao = $evento->descricao;
        $eventoGet->titulo = $evento->titulo;
        $eventoGet->id = $evento->id;
        $eventoGet->escolas = $evento->escolas;

        $eventoGet->permissao_usuario = $evento->permissao_usuario;
        $eventoGet->tema = $evento->tema;

        return $eventoGet;
    }

	public function inserir($dados)
    {
        DB::beginTransaction();
        try
        {
            $dados['user_id'] = auth()->user()->id;
            $dados['permissao_usuario'] = auth()->user()->permissao;
            $calendario = new FrAgendaCalendario($dados);
            $calendario->save();
            $escolas = [];
            if(isset($dados['visualizacao']) && $dados['visualizacao']==2) {
                foreach ($dados['escola'] as $e) {
                    $escola = [
                        'escola_id' => $e,
                        'instituicao_id' => auth()->user()->instituicao_id,
                    ];
                    $escolas[] = $escola;
                }
            }else{
                $escola = [
                    'escola_id' => 0,
                    'instituicao_id' => auth()->user()->instituicao_id,
                ];
                $escolas[] = $escola;
            }
            $calendario->escolas()->attach($escolas);
            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

    public function editar($dados)
    {
        DB::beginTransaction();
        try
        {
            $calendario = $this->scopoQuery();
            $calendario = $calendario->find($dados['id']);
            if(!$this->temPermissao($calendario)){
                DB::rollback();
                return false;
            }
            $calendario->update($dados);
            $escolas = [];
            if(isset($dados['visualizacao']) && $dados['visualizacao']==2) {
                foreach ($dados['escola'] as $e) {
                    $escola = [
                        'escola_id' => $e,
                        'instituicao_id' => auth()->user()->instituicao_id,
                    ];
                    $escolas[] = $escola;
                }
            }else{
                $escola = [
                    'escola_id' => 0,
                    'instituicao_id' => auth()->user()->instituicao_id,
                ];
                $escolas[] = $escola;
            }
            $calendario->escolas()->sync($escolas);
            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

    public function excluir($id)
    {
        DB::beginTransaction();
        try
        {
            $calendario = $this->scopoQuery();
            $calendario = $calendario->find($id);
            if(!$this->temPermissao($calendario)){
                DB::rollback();
                return false;
            }
            $calendario->escolas()->detach();
            $calendario->delete();
            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }


    private function temPermissao($calendario){
        if(auth()->user()->permissao != 'I'){
            if(auth()->user()->permissao == 'P' && auth()->user()->id != $calendario->user_id ){
                return false;
            }
            if(auth()->user()->permissao == 'C' && $calendario->permissao_usuario == 'I'){
                return false;
            }
        }
        return true;
    }

    public function setNovaData($dados)
    {
        DB::beginTransaction();
        try
        {
            $calendario = $this->scopoQuery();
            $calendario = $calendario->find($dados['id']);

            if(!$this->temPermissao($calendario)){
                DB::rollback();
                return false;
            }

            $dados['data_inicial'] = Carbon::parse($dados['inicio'])->format('Y-m-d H:m:s');
            $dados['data_final'] = Carbon::parse($dados['fim'])->format('Y-m-d H:m:s');
            $calendario->update($dados);

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
