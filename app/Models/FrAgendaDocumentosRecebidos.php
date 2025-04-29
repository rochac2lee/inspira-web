<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Config;

/**
 * App\Models\FrAgendaDocumentosRecebidos
 *
 * @property int $id
 * @property int $turma_id
 * @property int $aluno_id
 * @property int $documento_id
 * @property int $responsavel_id
 * @property string $arquivo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $link_arquivo
 * @property-read mixed $link_arquivo_download
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaDocumentosRecebidos newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaDocumentosRecebidos newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaDocumentosRecebidos query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaDocumentosRecebidos whereAlunoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaDocumentosRecebidos whereArquivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaDocumentosRecebidos whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaDocumentosRecebidos whereDocumentoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaDocumentosRecebidos whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaDocumentosRecebidos whereResponsavelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaDocumentosRecebidos whereTurmaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaDocumentosRecebidos whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FrAgendaDocumentosRecebidos extends Model
{
    use HasFactory;

    protected $table = 'fr_agenda_documentos_recebidos';

    protected $fillable = [
        'turma_id',
        'aluno_id',
        'documento_id',
        'responsavel_id',
        'arquivo',

    ];

    public function getLinkArquivoDownloadAttribute()
    {
        if($this->arquivo != ''){
            return config('app.frStorage') .'agenda/documentos/recebidos/'.$this->documento_id.'/'.$this->aluno_id.'/'.$this->responsavel_id.'/'.$this->arquivo;
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
            return config('app.cdn').'/storage/agenda/documentos/recebidos/'.$this->documento_id.'/'.$this->aluno_id.'/'.$this->responsavel_id.'/'.$this->arquivo;
        }
        else{ //// servidor
            /// return
            $client = Storage::disk('s3')->getDriver()->getAdapter()->getClient();
            $bucket = Config::get('filesystems.disks.s3.bucket');

            $command = $client->getCommand('GetObject', [
                'Bucket' => $bucket,
                'Key' => config('app.frStorage') .'agenda/documentos/recebidos/'.$this->documento_id.'/'.$this->aluno_id.'/'.$this->responsavel_id.'/'.$this->arquivo
            ]);

            $request = $client->createPresignedRequest($command, '+20 minutes');

            return  (string)$request->getUri();
        }
    }
}
