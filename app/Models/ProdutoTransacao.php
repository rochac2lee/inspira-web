<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProdutoTransacao
 *
 * @property int $id
 * @property string $transacao_id
 * @property int $user_id
 * @property int $produto_id
 * @property string $titulo
 * @property string $descricao
 * @property int $tipo
 * @property float $valor
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Transacao $transacao
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|ProdutoTransacao newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProdutoTransacao newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProdutoTransacao query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProdutoTransacao whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProdutoTransacao whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProdutoTransacao whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProdutoTransacao whereProdutoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProdutoTransacao whereTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProdutoTransacao whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProdutoTransacao whereTransacaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProdutoTransacao whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProdutoTransacao whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProdutoTransacao whereValor($value)
 * @mixin \Eloquent
 */
class ProdutoTransacao extends Model
{
    protected $table = 'produto_transacao';

    //Preenchiveis
    protected $fillable = [
        'transacao_id',
        'user_id',
        'produto_id',
        'titulo',
        'descricao',
        'tipo',
        'valor',
    ];

    // Protegidos
    protected $hidden = [
    ];

    //Padrões
    protected $attributes = [
        'descricao' => '',
        'tipo' => '1',
    ];

    // Tipos
    // 1 = Licenças
    // 2 = Cursos

    // public function transacao()
    // {
    //     return $this->belongsTo('App\Transacao');
    // }

    public function transacao()
    {
        return $this->belongsTo(Transacao::class, 'transacao_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
