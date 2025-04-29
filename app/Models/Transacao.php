<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Transacao
 *
 * @property int $id
 * @property string|null $referencia_id
 * @property string|null $payment_id
 * @property int $user_id
 * @property float $sub_total
 * @property float|null $adicional
 * @property float|null $desconto
 * @property float|null $frete
 * @property float $total
 * @property int $status
 * @property string $metodo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProdutoTransacao[] $produtos
 * @property-read int|null $produtos_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProdutoTransacao[] $produtos_user
 * @property-read int|null $produtos_user_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Transacao newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transacao newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transacao query()
 * @method static \Illuminate\Database\Eloquent\Builder|Transacao whereAdicional($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transacao whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transacao whereDesconto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transacao whereFrete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transacao whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transacao whereMetodo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transacao wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transacao whereReferenciaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transacao whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transacao whereSubTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transacao whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transacao whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transacao whereUserId($value)
 * @mixin \Eloquent
 */
class Transacao extends Model
{
    protected $table = 'transacoes';

    // protected $primaryKey = 'id'; // or null

    // public $incrementing = false;

    //Preenchiveis
    protected $fillable = [
        'referencia_id',
        'payment_id',
        'user_id',
        'sub_total',
        'adicional',
        'desconto',
        'frete',
        'total',
        'status',
        'metodo',
    ];

    // 0 = Criado
    // 1 = Pedente
    // 2 = Aguardando pagamento
    // 3 = Pagamento autorizado
    // 4 = Pagamento liberado
    // 7 = Pagamento cancelado

    // Protegidos
    protected $hidden = [
    ];

    //Padrões
    protected $attributes = [
        'metodo' => '',
        'sub_total' => 0,
        'adicional' => 0,
        'desconto' => 0,
        'frete' => 0,
        'total' => 0,
    ];

    public function produtos()
    {
        return $this->hasMany(ProdutoTransacao::class, 'transacao_id', 'id');
    }

    public function produtos_user()
    {
        return $this->hasMany(ProdutoTransacao::class, 'transacao_id', 'id')->with('user');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
