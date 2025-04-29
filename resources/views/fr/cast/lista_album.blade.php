@foreach($dados as $d)
    <div class="col-md-3 grid-item" id="divConteudo{{$d->id}}">
        <a href="javascript:" onclick="visualizarConteudo({{$d->id}})" class="wrap">
            <div class="card text-center">
                <div class="card-header" style="background-color:transparent;border-bottom:transparent;display:inline-block;text-align:center;width:260px;max-width:100%;">
                    <img src="{{$d->capa_album}}" style="max-height: 100%;object-fit: fill;width: auto;height:auto;max-width:100%;" />
                </div>
                <div class="card-body" style="height:80px;display:inline-flex; ">
                    <div class="text mb-2">
                        <h6 class="title font-weight-bold" style="margin-top: 5px">{{$d->titulo}}  </h6>
                        @if(Request::input('biblioteca')!=1 && auth()->user()->permissao != 'A')
                            @if($d->publicado!=1)
                                <p><span id="badgeParcial" class="badge badge-danger" >Rascunho</span></p>
                            @else
                                <p><span id="badgeParcial" class="badge badge-success" >Publicado</span></p>
                            @endif
                        @endif
                    </div>
                </div>
                <div  class="card-footer">
                    <a href="javascript:" alt="Ver álbum" title="Ver álbum" data-toggle="tooltip" data-placement="top" onclick="visualizarLista({{$d->id}}, 1, '{{$d->titulo}}')"  class="btn btn-secondary btn-sm" >
                        <i class="fas fa-eye"></i>
                    </a>
                    @if(auth()->user()->permissao != 'A')
                        @if(Request::input('biblioteca')!=1)
                            <a href="{{url('/gestao/cast/publicar?tipo=1&status='.$d->publicado.'&c='.$d->id)}}" class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="top" title="@if($d->publicado==1) Despublicar @else Publicar @endif"><i class="fas fa-bullhorn"></i></a>
                        @endif
                        @if(Request::input('biblioteca') == 1 && auth()->user()->permissao != 'Z' )
                        <a href="{{url('/gestao/cast/duplicar?tipo=1&c='.$d->id)}}" class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="top" title="@if(Request::input('biblioteca') == 1 ) Adicionar aos meus álbuns @endif">
                            <i class="fas fa-heart"></i>
                        </a>
                        @endif
                        @if( (auth()->user()->permissao == 'Z') || (auth()->user()->permissao != 'Z' && $d->user_id == auth()->user()->id) )
                            <a href="/gestao/cast/album/editar/{{$d->id}}" data-toggle="tooltip" data-placement="top"  alt="Editar" title="Editar"  class="btn btn-secondary btn-sm" >
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="javascript:" onclick="modalExcluirAlbum('{{$d->id}}', '{{$d->titulo}}')" data-toggle="tooltip" data-placement="top" alt="Apagar" title="Apagar" class="btn btn-secondary btn-sm" >
                                <i class="fas fa-trash"></i>
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </a>
    </div>
@endforeach
