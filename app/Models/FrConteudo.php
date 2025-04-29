<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FrConteudo
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $tipoprova_id
 * @property string|null $fim_avaliacao
 * @property string|null $ini_avaliacao
 * @property int|null $peso
 * @property int|null $ciclo_id
 * @property int|null $cicloetapa_id
 * @property int|null $disciplina_id
 * @property string $titulo
 * @property string|null $descricao
 * @property int $status
 * @property int $tipo
 * @property string $conteudo
 * @property float|null $file_size
 * @property float|null $tempo
 * @property int|null $duracao
 * @property string|null $apoio
 * @property string|null $fonte
 * @property string|null $autores
 * @property int $categoria_id
 * @property int $permissao_download 0 = não permitido / 1 = permitido
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $obrigatorio
 * @property int $privado
 * @property int $visibilidade
 * @property int $instituicao_id
 * @property int $escola_id
 * @property int $nivel_ensino
 * @property int $disciplina
 * @property string|null $alternativas
 * @property string|null $alternativa_correta
 * @property int|null $colecao_livro_id
 * @property string|null $tipo_livro
 * @property int|null $etapa_livro
 * @property int|null $ano_livro
 * @property int|null $componente_livro
 * @property string|null $capa
 * @property string|null $periodo
 * @property int|null $colecao_ed_infantil_id
 * @property string|null $comentario_pedagogico
 * @property string $compartilhado_google
 * @property string|null $id_google
 * @property string|null $full_text
 * @property int|null $qtd_paginas_livro
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo whereAlternativaCorreta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo whereAlternativas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo whereAnoLivro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo whereApoio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo whereAutores($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo whereCapa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo whereCategoriaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo whereCicloId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo whereCicloetapaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo whereColecaoEdInfantilId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo whereColecaoLivroId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo whereComentarioPedagogico($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo whereCompartilhadoGoogle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo whereComponenteLivro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo whereConteudo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo whereDisciplina($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo whereDisciplinaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo whereDuracao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo whereEscolaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo whereEtapaLivro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo whereFileSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo whereFimAvaliacao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo whereFonte($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo whereFullText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo whereIdGoogle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo whereIniAvaliacao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo whereInstituicaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo whereNivelEnsino($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo whereObrigatorio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo wherePeriodo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo wherePermissaoDownload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo wherePeso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo wherePrivado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo whereQtdPaginasLivro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo whereTempo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo whereTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo whereTipoLivro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo whereTipoprovaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrConteudo whereVisibilidade($value)
 * @mixin \Eloquent
 */
class FrConteudo extends Model
{
    protected $table = 'conteudos';

    protected $fillable = [
        'id',
        'user_id',
        'titulo',
        'descricao',
        'status',
        'tipo',
        'conteudo',
        'file_size',
        'tempo',
        'duracao',
        'apoio',
        'fonte',
        'autores',
        'categoria_id',
        'permissao_download',
        'tipoprova_id',
        'fim_avaliacao',
        'ini_avaliacao',
        'peso',
        'ciclo_id',
        'cicloetapa_id',
        'disciplina_id',
        'colecao_livro_id',
        'tipo_livro',
        'etapa_livro',
        'ano_livro',
        'componente_livro',
        'capa',
        'periodo',
        'colecao_ed_infantil_id',
        'instituicao_id',
        'compartilhado_google',
        'id_google',
        'eh_audio_cast',
        'qtd_paginas_livro',
    ];
}
