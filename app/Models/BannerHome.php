<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\BannerHome
 *
 * @property int $id
 * @property string $img
 * @property int $instituicao_tipo_id
 * @property string|null $url
 * @property string|null $permissao
 * @property string $target_blanck
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|BannerHome newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BannerHome newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BannerHome query()
 * @method static \Illuminate\Database\Eloquent\Builder|BannerHome whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BannerHome whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BannerHome whereImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BannerHome whereInstituicaoTipoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BannerHome wherePermissao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BannerHome whereTargetBlanck($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BannerHome whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BannerHome whereUrl($value)
 * @mixin \Eloquent
 */
class BannerHome extends Model
{
    protected $table = 'banner_home';

    protected $fillable = [
        'id',
        'img',
        'instituicao_tipo_id',
    ];
}
