<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Escola
 *
 * @property int $id
 * @property int $instituicao_id
 * @property int $user_id
 * @property string $titulo
 * @property string $descricao
 * @property string|null $logo
 * @property string $capa
 * @property int|null $postagem_aberta
 * @property string|null $url
 * @property int $limite_alunos
 * @property string|null $nome_completo
 * @property string|null $cnpj
 * @property string|null $facebook
 * @property string|null $instagram
 * @property string|null $youtube
 * @property string|null $numero_contrato
 * @property string|null $nome_responsavel
 * @property string|null $email_responsavel
 * @property string|null $telefone_responsavel
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $caracteristicas
 * @property string|null $css
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $status_id
 * @property string $cep
 * @property string $uf
 * @property string $cidade
 * @property string $bairro
 * @property string $logradouro
 * @property string $numero
 * @property string|null $complemento
 * @property string|null $cor_primaria
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CicloEtapa[] $ciclo_etapa
 * @property-read int|null $ciclo_etapa_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Disciplina[] $disciplina
 * @property-read int|null $disciplina_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Endereco[] $endereco
 * @property-read int|null $endereco_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FrUser[] $qtdAlunos
 * @property-read int|null $qtd_alunos_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FrUser[] $qtdProfessores
 * @property-read int|null $qtd_professores_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FrTurma[] $qtdTurmas
 * @property-read int|null $qtd_turmas_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FrUser[] $responsavelEscola
 * @property-read int|null $responsavel_escola_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FrTurma[] $turmas
 * @property-read int|null $turmas_count
 * @method static \Illuminate\Database\Eloquent\Builder|Escola newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Escola newQuery()
 * @method static \Illuminate\Database\Query\Builder|Escola onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Escola query()
 * @method static \Illuminate\Database\Eloquent\Builder|Escola whereBairro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Escola whereCapa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Escola whereCaracteristicas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Escola whereCep($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Escola whereCidade($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Escola whereCnpj($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Escola whereComplemento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Escola whereCorPrimaria($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Escola whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Escola whereCss($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Escola whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Escola whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Escola whereEmailResponsavel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Escola whereFacebook($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Escola whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Escola whereInstagram($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Escola whereInstituicaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Escola whereLimiteAlunos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Escola whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Escola whereLogradouro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Escola whereNomeCompleto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Escola whereNomeResponsavel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Escola whereNumero($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Escola whereNumeroContrato($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Escola wherePostagemAberta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Escola whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Escola whereTelefoneResponsavel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Escola whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Escola whereUf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Escola whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Escola whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Escola whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Escola whereYoutube($value)
 * @method static \Illuminate\Database\Query\Builder|Escola withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Escola withoutTrashed()
 * @mixin \Eloquent
 */
class Escola extends Model
{
    use SoftDeletes;

    //Preenchiveis
    protected $fillable = [
        'instituicao_id',
        'user_id',
        'titulo',
        'descricao',
        'facebook',
        'instagram',
        'cnpj',
        'youtube',
        'numero_contrato',
        'logo',
        'capa',
        'postagem_aberta',
        'url',
        'caracteristicas',
        'css',
        'status_id',
        'uf',
        'cidade',
        'bairro',
        'logradouro',
        'numero',
        'complemento',
        'cep',
        'cor_primaria',
    ];


    protected $dates = ['deleted_at'];

    //Padrões
    protected $attributes = [
        'descricao' => '',
        'capa'      => '',
        'postagem_aberta' => 1,
        'status_id' => 1,
    ];



    //
    // Relationships
    //
/*
/* inicio funcoes F&R
*/
    public function qtdAlunos()
    {
        return $this->hasMany('App\Models\FrUser','escola_id','id')->where('permissao','A')->groupBy('escola_id')->selectRaw('count(id) as qtd, escola_id');
    }

    public function qtdProfessores()
    {
        return $this->hasMany('App\Models\FrUser','escola_id')->where('permissao','P')->groupBy('escola_id')->selectRaw('count(id) as qtd, escola_id');
    }

    public function responsavelEscola()
    {
        return $this->hasMany('App\Models\FrUser','escola_id')->where('permissao','R')->groupBy('escola_id')->selectRaw('count(id) as qtd, escola_id');
    }

    public function qtdTurmas()
    {
        return $this->hasMany(FrTurma::class)->selectRaw('count(id) as qtd, escola_id');
    }

    public function turmas()
    {
        return $this->hasMany(FrTurma::class)
                            ->join('ciclo_etapas','fr_turmas.ciclo_etapa_id','ciclo_etapas.id')
                            ->join('ciclos','ciclo_etapas.ciclo_id','ciclos.id')
                            ->selectRaw('fr_turmas.*, ciclo_etapas.titulo as ciclo_etapa, ciclos.titulo as ciclo')
                            ->orderBy('ciclos.id')
                            ->orderBy('ciclo_etapas.id')
                            ->orderBy('fr_turmas.titulo');
    }

/*
/* Fim funcoes F&R
*/
    public function ciclo_etapa()
    {
        return $this->belongsToMany(CicloEtapa::class, 'ciclo_etapa_escolas', 'escola_id', 'ciclo_etapas_id');
    }

    public function disciplina()
    {
        return $this->belongsToMany(Disciplina::class, 'disciplina_escolas', 'escola_id', 'disciplina_id');
    }

    public function endereco()
    {
        return $this->belongsToMany(Endereco::class, 'endereco_escolas', 'escola_id', 'endereco_id');
    }

    public function instituicao()
    {
        return  $this->belongsTo(Instituicao::class);
    }
}
