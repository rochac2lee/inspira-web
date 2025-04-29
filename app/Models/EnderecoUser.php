<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Input;

/**
 * App\Models\EnderecoUser
 *
 * @property int $user_id
 * @property string|null $cep
 * @property string|null $uf
 * @property string|null $cidade
 * @property string|null $bairro
 * @property string|null $logradouro
 * @property string|null $numero
 * @property string|null $complemento
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|EnderecoUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EnderecoUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EnderecoUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|EnderecoUser whereBairro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnderecoUser whereCep($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnderecoUser whereCidade($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnderecoUser whereComplemento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnderecoUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnderecoUser whereLogradouro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnderecoUser whereNumero($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnderecoUser whereUf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnderecoUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnderecoUser whereUserId($value)
 * @mixin \Eloquent
 */
class EnderecoUser extends Model
{
    protected $table = 'endereco_user';

    protected $primaryKey = 'user_id';

    //Preenchiveis
    protected $fillable = [
        'user_id',
        'cep',
        'uf',
        'cidade',
        'bairro',
        'logradouro',
        'numero',
        'complemento',
    ];

    // Protegidos
    protected $hidden = [
    ];

    //Padrões
    protected $attributes = [
        'cidade' => '',
        'bairro' => '',
        'logradouro' => '',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
