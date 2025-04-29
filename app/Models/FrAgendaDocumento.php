<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Config;

/**
 * App\Models\FrAgendaDocumento
 *
 * @property int $id
 * @property int $user_id
 * @property string $titulo
 * @property string $descricao
 * @property string|null $arquivo
 * @property string|null $nome_arquivo_original
 * @property string $permissao_usuario
 * @property int $instituicao_id
 * @property int $escola_id
 * @property string $publicado
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $alunos
 * @property-read int|null $alunos_count
 * @property-read \App\Models\Escola|null $escola
 * @property-read mixed $link_arquivo
 * @property-read mixed $link_arquivo_download
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FrAgendaDocumentosRecebidos[] $recebidos
 * @property-read int|null $recebidos_count
 * @property-read \App\Models\User|null $usuario
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaDocumento newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaDocumento newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaDocumento query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaDocumento whereArquivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaDocumento whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaDocumento whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaDocumento whereEscolaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaDocumento whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaDocumento whereInstituicaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaDocumento whereNomeArquivoOriginal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaDocumento wherePermissaoUsuario($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaDocumento wherePublicado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaDocumento whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaDocumento whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaDocumento whereUserId($value)
 * @mixin \Eloquent
 */
class FrAgendaDocumento extends Model
{
    protected $table = 'fr_agenda_documentos';

    protected $fillable = [
        'user_id',
        'titulo',
        'descricao',
        'arquivo',
        'nome_arquivo_original',
        'permissao_usuario',
        'instituicao_id',
        'escola_id',
        'publicado',
    ];

    public function getLinkArquivoDownloadAttribute()
    {
        if($this->arquivo != ''){
            return config('app.frStorage') . 'agenda/documentos/' . $this->user_id . '/' . $this->id.'/'.$this->arquivo;
        }else{
            return null;
        }
    }

    public function getLinkArquivoAttribute()
    {
        if($this->arquivo == ''){
            return null;
        }
        //// esses arquivos são todos private
        $disk = config('app.frS3Private');
        if($disk == '') ///// local
        {
            return config('app.cdn').'/storage/agenda/documentos/'. $this->user_id . '/' . $this->id.'/'.$this->arquivo;
        }
        else{ //// servidor
            /// return
            $client = Storage::disk('s3')->getDriver()->getAdapter()->getClient();
            $bucket = Config::get('filesystems.disks.s3.bucket');

            $command = $client->getCommand('GetObject', [
                'Bucket' => $bucket,
                'Key' => config('app.frStorage') .'agenda/documentos/'. $this->user_id . '/' . $this->id.'/'.$this->arquivo
            ]);

            $request = $client->createPresignedRequest($command, '+20 minutes');

            return  (string)$request->getUri();
        }

    }

    public function escola()
    {
        return $this->hasOne(Escola::class,'id','escola_id');
    }

    public function alunos()
    {
        return $this->belongsToMany(User::class,'fr_agenda_documentos_alunos','documento_id','aluno_id')->orderBy('nome_completo')
            ->withPivot(['aluno_id','turma_id','escola_id','instituicao_id']);
    }

    public function usuario(){
        return $this->hasOne(User::class, 'id','user_id');
    }

    public function recebidos(){
        return $this->hasMany(FrAgendaDocumentosRecebidos::class, 'documento_id','id');
    }

}
