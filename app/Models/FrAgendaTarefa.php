<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FrAgendaTarefa
 *
 * @property int $id
 * @property string $titulo
 * @property string $descricao
 * @property \Illuminate\Support\Carbon $data_entrega
 * @property int $disciplina_id
 * @property string|null $arquivo
 * @property string|null $nome_arquivo_original
 * @property int $professor_id
 * @property int $instituicao_id
 * @property int $escola_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $publicado
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $alunos
 * @property-read int|null $alunos_count
 * @property-read \App\Models\Disciplina|null $disciplina
 * @property-read \App\Models\Escola|null $escola
 * @property-read mixed $link_arquivo
 * @property-read mixed $link_arquivo_download
 * @property-read \App\Models\User|null $professor
 * @property-read \Illuminate\Database\Eloquent\Collection|FrAgendaTarefa[] $tarefaData
 * @property-read int|null $tarefa_data_count
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaTarefa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaTarefa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaTarefa query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaTarefa whereArquivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaTarefa whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaTarefa whereDataEntrega($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaTarefa whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaTarefa whereDisciplinaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaTarefa whereEscolaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaTarefa whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaTarefa whereInstituicaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaTarefa whereNomeArquivoOriginal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaTarefa whereProfessorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaTarefa wherePublicado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaTarefa whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaTarefa whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FrAgendaTarefa extends Model
{
    protected $table = 'fr_agenda_tarefa';
    protected $dates = ['data_entrega'];

    protected $fillable = [
        'titulo',
        'descricao',
        'data_entrega',
        'disciplina_id',
        'arquivo',
        'nome_arquivo_original',
        'professor_id',
        'instituicao_id',
        'escola_id',
        'publicado',
    ];

    public function getLinkArquivoDownloadAttribute()
    {
        if($this->arquivo != ''){
            return config('app.frStorage') . 'agenda/tarefas/' . $this->professor_id . '/' . $this->id.'/'.$this->arquivo;
        }else{
            return null;
        }
    }

    public function getLinkArquivoAttribute()
    {
        if($this->arquivo != ''){
            return config('app.cdn').'/storage/agenda/tarefas/' . $this->professor_id . '/' . $this->id.'/'.$this->arquivo;
        }else{
            return null;
        }
    }

    public function professor(){
        return $this->hasOne(User::class, 'id','professor_id');
    }

    public function disciplina()
    {
        return $this->hasOne(Disciplina::class,'id','disciplina_id');
    }

    public function escola()
    {
        return $this->hasOne(Escola::class,'id','escola_id');
    }

    public function alunos()
    {
        return $this->belongsToMany(User::class,'fr_agenda_tarefa_alunos','tarefa_id','aluno_id')->orderBy('nome_completo')
            ->withPivot(['aluno_id','turma_id','escola_id','instituicao_id']);
    }

    public function tarefaData()
    {
        return $this->hasMany(FrAgendaTarefa::class,'data_entrega','data_entrega');
    }
}
