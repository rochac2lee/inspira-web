<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\FrUser
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
 * @method static \Illuminate\Database\Eloquent\Builder|FrUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrUser newQuery()
 * @method static \Illuminate\Database\Query\Builder|FrUser onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|FrUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrUser whereAvatarSocial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrUser whereCpf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrUser whereDataNascimento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrUser whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrUser whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrUser whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrUser whereEmailNovo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrUser whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrUser whereEscolaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrUser whereFuturoExcluir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrUser whereGenero($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrUser whereHabilitarTrocaSenha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrUser whereImgPerfil($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrUser whereImportado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrUser whereInstituicao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrUser whereInstituicaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrUser whereLoginToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrUser whereMaster($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrUser whereMatricula($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrUser whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrUser whereNomeCompleto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrUser whereOcupacao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrUser wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrUser wherePermissao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrUser wherePrivilegioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrUser whereRa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrUser whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrUser whereRg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrUser whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrUser whereTelefone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrUser whereTerms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrUser whereTokenAgenda($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrUser whereTokenApp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrUser whereTokenConfirmacao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrUser whereTrial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrUser whereUltimaAtividade($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrUser whereValidadeTokenApp($value)
 * @method static \Illuminate\Database\Query\Builder|FrUser withTrashed()
 * @method static \Illuminate\Database\Query\Builder|FrUser withoutTrashed()
 * @mixin \Eloquent
 */
class FrUser extends Model
{
    use SoftDeletes;
    protected $table = 'users';
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
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];


}
