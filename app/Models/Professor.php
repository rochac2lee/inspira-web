<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Professor
 *
 * @property int $id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $user
 * @property-read int|null $user_count
 * @method static \Illuminate\Database\Eloquent\Builder|Professor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Professor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Professor query()
 * @method static \Illuminate\Database\Eloquent\Builder|Professor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Professor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Professor whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Professor whereUserId($value)
 * @mixin \Eloquent
 */
class Professor extends Model
{
    protected $table = 'professores';

    protected $fillable = [
        'user_id',
    ];

    protected $hidden = [];

    public function user(){
        return $this->belongsToMany(User::class);
    }
}
