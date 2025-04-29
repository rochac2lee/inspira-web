<?php

namespace App\Models;

use App\Models\Indica\AvaliacaoLogAtividade;
use App\Models\Indica\AvaliacaoLogGeral;
use App\Models\Indica\AvaliacaoPlacar;
use App\Models\Indica\AvaliacaoTempo;
use App\Models\Indica\AvaliacaoTentativas;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $password
 * @property string|null $ra
 * @property string|null $img_perfil
 * @property string $nome_completo
 * @property string|null $data_nascimento
 * @property string|null $cpf
 * @property string|null $rg
 * @property string|null $telefone
 * @property string|null $genero
 * @property string|null $descricao
 * @property int|null $escola_id
 * @property string $permissao
 * @property int $terms
 * @property string|null $remember_token
 * @property string $ultima_atividade
 * @property string|null $login_token
 * @property string|null $token_confirmacao
 * @property string|null $email_verified_at
 * @property string|null $email_novo
 * @property int|null $master
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $ocupacao
 * @property string|null $instituicao
 * @property int $trial
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $privilegio_id
 * @property int $status_id
 * @property string|null $importado
 * @property string|null $token_agenda
 * @property int|null $instituicao_id
 * @property int $habilitar_troca_senha
 * @property string|null $futuro_excluir
 * @property string|null $avatar_social
 * @property string|null $matricula
 * @property string|null $token_app
 * @property string|null $validade_token_app
 * @property-read \Illuminate\Database\Eloquent\Collection|User[] $alunosDoResponsavel
 * @property-read int|null $alunos_do_responsavel_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FrAgendaDocumentosRecebidos[] $documentosEnviadosResponsavel
 * @property-read int|null $documentos_enviados_responsavel_count
 * @property-read \App\Models\EnderecoUser $endereco
 * @property-read \App\Models\Escola|null $escola
 * @property-read mixed $avatar
 * @property-read mixed $nome
 * @property-read \App\Models\Instituicao|null $instituicaoObj
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\UserPermissao[] $permissoes
 * @property-read int|null $permissoes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FrTurma[] $turmaDeAlunos
 * @property-read int|null $turma_de_alunos_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Query\Builder|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatarSocial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCpf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDataNascimento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailNovo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEscolaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFuturoExcluir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGenero($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereHabilitarTrocaSenha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereImgPerfil($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereImportado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereInstituicao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereInstituicaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLoginToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMaster($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMatricula($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNomeCompleto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereOcupacao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePermissao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePrivilegioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTelefone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTerms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTokenAgenda($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTokenApp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTokenConfirmacao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTrial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUltimaAtividade($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereValidadeTokenApp($value)
 * @method static \Illuminate\Database\Query\Builder|User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|User withoutTrashed()
 * @mixin \Eloquent
 */
class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'ra',
        'img_perfil',
        'nome_completo',
        'data_nascimento',
        'cpf',
        'rg',
        'telefone',
        'ocupacao',
        'instituicao',
        'genero',
        'descricao',
        'escola_id',
        'permissao',
        'terms',
        'ultima_atividade',
        'token_confirmacao',
        'trial',
        'privilegio_id',
        'status_id',
        'importado',
        'token_agenda',
        'instituicao_id',
        'avatar_social',
        'matricula',
        'deleted_at',
        'token_app',
        'validade_token_app',
        'device_key_agenda',
        'notificacao_ativa_agenda',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];



    protected static $permissoes = [
        'Administrador',
        'Gestor',
        'Professor',
        'Aluno',
        'School',
    ];

    protected $dates = ['deleted_at', 'data_nascimento'];

    /* Inicio
    /* Mutators F&R
    /*
    */
    public function getIdAttribute($value)
    {
        $url = url()->current();
        $tem =  strpos($url,'multiplasPermissoes');
        $tem1 =  strpos($url,'configuracao');
        $tem2 =  strpos($url,'/api/');
        $tem3 =  strpos($url,'/teste');

        $dados = session('dadosPermissoesEscolhido');
        if(isset($dados['permissao']) && $dados['permissao'] == 'R' && $tem === false && $tem1 === false && $tem2 === false && $tem3 === false)
        {
            return $dados['aluno_id'];
        }
        else
        {
            return $value;
        }
    }

    public function getPermissaoAttribute($value)
    {
        $url = url()->current();
        $api =  strpos($url,'/api/');
        if($api === false){
            $dados = session('dadosPermissoesEscolhido');
            if(isset($dados['permissao']))
            {
                return $dados['permissao'];
            }
            else
            {
                return $value;
            }
        }else{
            $payload = auth('api')->getPayload();
            $sessao = $payload->get('sessao');
            if($sessao){
                return $sessao->permissao;
            }else{
                return $value;
            }
        }

    }

    public function getEscolaIdAttribute($value)
    {
        $url = url()->current();
        $api =  strpos($url,'/api/');
        if($api === false) {
            $dados = session('dadosPermissoesEscolhido');
            if (isset($dados['escola_id'])) {
                return $dados['escola_id'];
            } else {
                return $value;
            }
        }else{
            $payload = auth('api')->getPayload();
            $sessao = $payload->get('sessao');
            if($sessao){
                return $sessao->escola_id;
            }else{
                return $value;
            }
        }
    }

    public function getInstituicaoIdAttribute($value)
    {
        $url = url()->current();
        $api =  strpos($url,'/api/');
        if($api === false) {
            $dados = session('dadosPermissoesEscolhido');
            if (isset($dados['instituicao_id'])) {
                return $dados['instituicao_id'];
            } else {
                return $value;
            }
        }else{
            $payload = auth('api')->getPayload();
            $sessao = $payload->get('sessao');
            if($sessao){
                return $sessao->instituicao_id;
            }else{
                return $value;
            }
        }
    }

    public function getAvatarAttribute()
    {
        if($this->avatar_social!=''){
            return $this->avatar_social;
        }
        elseif($this->img_perfil!=''){
            return config('app.cdn').'/storage/uploads/usuarios/perfil/'.$this->img_perfil;
        }else{
            return config('app.cdn').'/fr/imagens/avatar-user.png';
        }
    }

    public function getNomeAttribute()
    {
        if($this->nome_completo!=''){
            return $this->nome_completo;
        }
        elseif($this->name!=''){
            return $this->name;
        }else{
            return '';
        }
    }
    /* Fim
    /* Mutators F&R
    /*
    */

    /* Inicio
    /* Ligações F&R
    /*
    */

    public function profile()
    {
        return $this->hasOne('App\Models\SocialProfile');
    }

    public function permissoes()
    {
        return $this->hasMany('App\Models\UserPermissao', 'user_id');
    }

    public function instituicaoObj()
    {
        return $this->hasOne('App\Models\Instituicao', 'id', 'instituicao_id');
    }

    public function alunosDoResponsavel()
    {
        return $this->belongsToMany(User::class,'fr_responsavel_aluno','responsavel_id','aluno_id')
            ->withPivot(['escola_id','instituicao_id']);
    }

    public function responsaveisDoAluno()
    {
        return $this->belongsToMany(User::class,'fr_responsavel_aluno','aluno_id','responsavel_id')
            ->withPivot(['escola_id','instituicao_id']);
    }

    public function turmaDeAlunos()
    {
        return $this->belongsToMany(FrTurma::class,'fr_turma_aluno','aluno_id','turma_id');
    }

    public function turmaDeProfessores()
    {
        return $this->belongsToMany(FrTurma::class,'fr_turma_professor','professor_id','turma_id')
                    ->join('ciclo_etapas', 'ciclo_etapas.id', 'fr_turmas.ciclo_etapa_id')
                    ->join('ciclos', 'ciclos.id', 'ciclo_etapas.ciclo_id')
                    ->where('fr_turmas.escola_id', $this->escola_id)
                    ->orderBy('ciclo_etapas.titulo')
                    ->orderBy('ciclos.titulo')
                    ->orderBy('fr_turmas.titulo')
                    ->selectRaw('fr_turmas.*, ciclo_etapas.titulo as ciclo_etapa, ciclos.titulo as ciclo');
    }

    public function documentosEnviadosResponsavel()
    {
        return $this->hasMany(FrAgendaDocumentosRecebidos::class, 'responsavel_id');
    }

    public function cursos(){
        return $this->belongsToMany(Trilha::class, 'trilha_usuario_matricula', 'user_id', 'trilha_id');
    }

    public function placarIndica()
    {
        return $this->hasOne(AvaliacaoPlacar::class, 'user_id');
    }

    public function logAtividadeIndica()
    {
        return $this->hasMany(AvaliacaoLogAtividade::class, 'user_id');
    }

    public function logGeralIndica()
    {
        return $this->hasMany(AvaliacaoLogGeral::class, 'user_id');
    }

    public function tentativasIndica()
    {
        return $this->hasMany(AvaliacaoTentativas::class, 'user_id')->orderBy('id');
    }

    public function escola()
    {
        return $this->belongsTo(Escola::class, 'escola_id');
    }

    public function endereco()
    {
        return $this->hasOne(EnderecoUser::class, 'user_id')
            ->withDefault([
                'user_id'     => null,
                'cep'         => null,
                'uf'          => null,
                'cidade'      => null,
                'bairro'      => null,
                'logradouro'  => null,
                'numero'      => null,
                'complemento' => null,
            ]);
    }

// Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
