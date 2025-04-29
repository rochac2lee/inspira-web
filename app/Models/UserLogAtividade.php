<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserLogAtividade
 *
 * @property int $id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserLogAtividade newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserLogAtividade newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserLogAtividade query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserLogAtividade whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLogAtividade whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLogAtividade whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLogAtividade whereUserId($value)
 * @mixin \Eloquent
 */
class UserLogAtividade extends Model
{
    protected $table = 'user_log_atividade';

    protected $fillable = [
        'user_id',
        'tipo',
    ];
}
