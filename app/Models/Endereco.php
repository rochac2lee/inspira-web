<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Endereco
 *
 * @property int $id
 * @property string $cep
 * @property string $uf
 * @property string $cidade
 * @property string $bairro
 * @property string $logradouro
 * @property string|null $numero
 * @property string|null $complemento
 * @property int|null $tipo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Escola[] $escola
 * @property-read int|null $escola_count
 * @method static \Illuminate\Database\Eloquent\Builder|Endereco newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Endereco newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Endereco query()
 * @method static \Illuminate\Database\Eloquent\Builder|Endereco whereBairro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Endereco whereCep($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Endereco whereCidade($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Endereco whereComplemento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Endereco whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Endereco whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Endereco whereLogradouro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Endereco whereNumero($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Endereco whereTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Endereco whereUf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Endereco whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Endereco extends Model
{
    //tabela de endereços
    protected $table ='enderecos';

    //Preenchiveis
    protected $fillable = [
        'cep',
        'uf',
        'cidade',
        'bairro',
        'logradouro',
        'numero',
        'complemento',
        'tipo'
    ];

    // Protegidos
    protected $hidden = [
    ];

    //Padrões
    protected $attributes = [
        'tipo'       => 1,
        'cep'        => 0,
        'uf'         => '',
        'cidade'     => '',
        'bairro'     => '',
        'logradouro' => '',
    ];

    //Relacionamentos
    public function escola()
    {
        return $this->belongsToMany('App\Models\Escola', 'endereco_escolas', 'endereco_id', 'escola_id');
    }
}
