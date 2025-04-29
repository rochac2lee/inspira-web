@extends('fr/master')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
.select2.select2-container .select2-selection {
    border: 1px solid #ffb100;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
    height: 39px;

    outline: none !important;
    transition: all .15s ease-in-out;
}
.select2.select2-container .select2-selection .select2-selection__rendered {
    color: #333;
    line-height: 32px;
    padding-right: 33px;
}
</style>
    <section class="section section-interna">
        <div class="container" style="max-width: 90%">

            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="filter">
                        @if(auth()->user()->permissao == 'Z')
                        <form class="form-inline d-flex justify-content-end">
                            <div class="input-group ml-1">
                                <input type="text" name="descricao" value="{{Request::input('descricao')}}" placeholder="Descrição" class="form-control rounded "/>
                            </div>
                            <div class="input-group ml-1">
                                <input type="text" name="url" value="{{Request::input('url')}}" placeholder="Url de Destino" class="form-control rounded " />
                            </div>
                            <div class="input-group ml-1">
                                <input type="text" name="observacao" value="{{Request::input('observacao')}}" placeholder="Observação" class="form-control rounded "/>
                            </div>
                            <div class="input-group ml-1">
                                <input type="text" name="chave" value="{{Request::input('chave')}}" placeholder="Chave" class="form-control rounded "/>
                            </div>
                            <div class="input-group ml-1">
                                <select id="tipo_midia" name="tipo_midia" class="form-control rounded " >
                                    <option value=""></option>
                                    <option value="Vídeo" {{ Request::input('tipo_midia') == 'Vídeo' ? 'selected' : '' }}>Vídeo</option>
                                    <option value="Áudio" {{ Request::input('tipo_midia') == 'Áudio' ? 'selected' : '' }}>Áudio</option>
                                    <option value="Jogo" {{ Request::input('tipo_midia') == 'Jogo' ? 'selected' : '' }}>Jogo</option>
                                    <option value="Quiz" {{ Request::input('tipo_midia') == 'Quiz' ? 'selected' : '' }}>Quiz</option>
                                    <option value="Site" {{ Request::input('tipo_midia') == 'Site' ? 'selected' : '' }}>Site</option>
                                    <option value="Gabarito" {{ Request::input('tipo_midia') == 'Gabarito' ? 'selected' : '' }}>Gabarito</option>
                                    <option value="Apresentação" {{ Request::input('tipo_midia') == 'Apresentação' ? 'selected' : '' }}>Apresentação</option>
                                    <option value="Simulador" {{ Request::input('tipo_midia') == 'Simulador' ? 'selected' : '' }}>Simulador</option>
                                    <option value="Documentos" {{ Request::input('tipo_midia') == 'Documentos' ? 'selected' : '' }}>Documentos</option>
                                    <option value="Livro Digital" {{ Request::input('tipo_midia') == 'Livro Digital' ? 'selected' : '' }}>Livro Digital</option>
                                    <option value="BNCC" {{ Request::input('tipo_midia') == 'BNCC' ? 'selected' : '' }}>BNCC</option>
                                    <option value="Imagem" {{ Request::input('tipo_midia') == 'Imagem' ? 'selected' : '' }}>Imagem</option>
                                    <option value="Prova" {{ Request::input('tipo_midia') == 'Prova' ? 'selected' : '' }}>Prova</option>
                                    <option value="RED" {{ Request::input('tipo_midia') == 'RED' ? 'selected' : '' }}>RED</option>
                                </select>
                            </div>
                            <div class="input-group ml-1" style="max-width: 250px">
                                <select id="disciplina_id" name="disciplina">
                                    <option value=""></option>
                                    @foreach($disciplinas as $disciplina)
                                        <option value="{{$disciplina->id}}" {{ Request::input('disciplina') == $disciplina->id ? 'selected' : '' }}>{{$disciplina->titulo}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-group ml-1" style="max-width: 250px">
                                <select id="colecao_livro_id" name="colecaoLivros">
                                    <option value=""></option>
                                    @foreach($colecaoLivros as $colecao)
                                        <option value="{{$colecao->id}}">{{$colecao->nome}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-group ml-1" style="max-width: 250px">
                                <select id="ciclo_etapa_id" name="cicloEtapa">
                                        <option value=""></option>
                                    @foreach($cicloEtapa as $c)
                                        <option value="{{$c->id}}" {{ Request::input('cicloEtapa') == $c->id ? 'selected' : '' }}>{{$c->ciclo}} / {{$c->ciclo_etapa}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-group ml-1">
                                <button type="submit" class="btn btn-secondary">Localizar</button>
                            </div>
                            <div class="input-group ml-1">
                                <button type="button" onclick="javascript:window.location.href='{{url()->current()}}'" class="btn btn-secondary">Limpar Filtros</button>
                            </div>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row border-top pt-4 pb-4">
                <div class="col-md-4">
                    <h3>
                        Gestão de QRCode
                    </h3>
                </div>
                <div class="col-md-8 text-right">
                    <a href="{{url('/gestao/qrcode/novo')}}" class="btn btn-success" >
                        <i class="fas fa-plus"></i>
                        Novo QRCode
                    </a>
                </div>
            </div>
            <div class="row">
                @if(count($qrcode)>0)
                <section class="table-page w-100">
                    <div class="table-responsive table-hover">
                        <table class="table table-striped">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">Descrição</th>
                                <th scope="col">URL de destino</th>
                                <th scope="col">Observação</th>
                                <th scope="col">Chave</th>
                                <th scope="col">Tipo Mídia</th>
                                <th scope="col">Componente</th>
                                <th scope="col" style="min-width: 220px">Coleção</th>
                                <th scope="col">Etapa/Ano</th>
                                <th scope="col" class="text-right" style="min-width: 220px">Ação</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($qrcode as $d)
                                <tr>
                                    <td>
                                        {{$d->descricao}}
                                    </td>
                                    <td>
                                        {{$d->url}}
                                    </td>
                                    <td>
                                        {{$d->observacao}}
                                    </td>
                                    <td >
                                        {{$d->chave}}
                                    </td>
                                    <td >
                                        {{$d->tipo_midia}}
                                    </td>
                                    <td>
                                        {{$d->titulo_disciplina}}
                                    </td>
                                    <td >
                                        {{$d->nome_colecao}}
                                    </td>
                                    <td >
                                        {{$d->titulo_ciclo_etapa}}
                                    </td>
                                    <td class="text-right">
                                        <span>
                                            <a href="{{url('/gestao/qrcode/baixar/'.$d->id)}}" title="Baixar imagem"  data-toggle="tooltip" data-placement="top"  class="btn btn-success btn-sm"><i class="fas fa-download"></i></a>
                                        </span>
                                        <span>
                                            <a href="{{url('/gestao/qrcode/edita/'.$d->id)}}" title="Editar"  data-toggle="tooltip" data-placement="top"  class="btn btn-success btn-sm"><i class="fas fa-edit"></i></a>
                                        </span>
                                        <span>
                                            <button title="Excluir" data-toggle="tooltip" data-placement="top" onclick="modalExcluir('{{$d->id}}', '{{$d->descricao}}')" class="btn btn-danger btn-sm" data-placement="left" title="Excluir"><i class="fas fa-trash-alt"></i></button>
                                        </span>
                                       <!-- <span>
                                            <button title="Copiar código QRCode" onclick="copiar('{{$d->chave}}')" data-toggle="tooltip" data-placement="top" onclick="" class="btn btn-success btn-sm" data-placement="left" title="Excluir"><i class="fas fa-copy"></i></button>
                                        </span>
                                        -->

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
                @else
                    <div class="col">
                        <div class="card text-center">
                            <div class="card-header"></div>
                            <div class="card-body">
                                <h5 class="card-title mt-2"><i class="fas fa-exclamation-circle"></i> Nenhum Resultado Encontrado</h5>
                                <p class="card-text ">Não foi encontrado resultado contendo todos os seus termos de pesquisa, clique no botão abaixo para reiniciar a pesquisa</p>
                                <a class="btn btn-danger fs-13 mb-2" href="{{Request::url()}}" title="Excluir"><i class="fas fa-undo-alt"></i> Limpar Filtro</a>
                            </div>
                            <div class="card-footer text-muted"></div>
                        </div>
                    </div>
                @endif
            </div>
            <nav class="mt-4" aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    {{ $qrcode->appends(Request::all())->links() }}
                </ul>
            </nav>
        </div>
    </section>
    <!-- EXCLUIR -->
    <div class="modal fade" id="formExcluir" tabindex="-1" role="dialog" aria-labelledby="formExcluir" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"> <i class="fas fa-times-circle"></i></span>
                </button>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Exclusão de QRCode</h5>
                </div>
                <div class="modal-body">
                    <form action="">
                        <div class="row">
                            <div class="col-12">
                                Você deseja mesmo excluir esse registro?<br><br>
                                <b id="nomeDescricao"></b>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                    <button type="button" onclick="excluir()" class="btn btn-danger">Sim, excluir</button>
                </div>
            </div>
        </div>
    </div>
    <!-- FIM EXCLUIR -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        function copiar(chave){
            link = '{{url('leitura/qrcode?c=')}}'+chave
            navigator.clipboard.writeText(link);
            alert('Código copiado.');
        }

        function modalExcluir(id, descricao)
        {
            idExcluir = id;
            $('#nomeDescricao').html(descricao);
            $('#formExcluir').modal('show');
        }
        function excluir()
        {
            window.location.href = '{{url("/gestao/qrcode/deletar/")}}/'+idExcluir;
        }
        $('#formExcluir').on('hidden.bs.modal', function () {
            idExcluir = 0;
        });

        $(document).ready(function() {
            $('#colecao_livro_id').select2({
                placeholder: "Coleção",
                allowClear: true
            });
            $('#ciclo_etapa_id').select2({
                placeholder: "Ciclo Etapa",
                allowClear: true
            });
            $('#disciplina_id').select2({
                placeholder: "Componente",
                allowClear: true
            });
            $('#tipo_midia').select2({
                placeholder: "Tipo Mídia",
                allowClear: true
            });
        });

    </script>
@stop
