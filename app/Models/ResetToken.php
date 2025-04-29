<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ResetToken
 *
 * @property string $email
 * @property string $token
 * @property string $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|ResetToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ResetToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ResetToken query()
 * @method static \Illuminate\Database\Eloquent\Builder|ResetToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResetToken whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResetToken whereToken($value)
 * @mixin \Eloquent
 */
class ResetToken extends Model
{
    protected $table = 'reset_token';

    protected $primaryKey = 'email';

    public $incrementing = false;

    public $timestamps = false;

    //Preenchiveis
    protected $fillable = [
        'email',
        'token',
        'created_at',
    ];

    // Protegidos
    protected $hidden = [
    ];

    //Padrões
    protected $attributes = [

    ];

}
