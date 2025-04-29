@extends('fr/master')
@section('content')

<section class="section section-interna">


	<div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4 class="pb-1 border-bottom mb-4">
                    <a href="{{url('/indica/gestao/questao/')}}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    INdica - Importar Questões
                </h4>
            </div>
        </div>
		<div class="row  pb-4" >
			<div class="col-md-6">
				<h4>INdica - Arquivos importados</h4>
			</div>
            <div class="col-md-6 text-right">
				<span><a href="javascript:void(0)" class="btn btn-success" onclick="$('#formImportar').modal('show')"><i class="fas fa-plus"></i> Nova importação</a></span>
			</div>
		</div>

		<div class="row">

            <div class="col-12">
                <section class="table-page w-100">
                    <div class="table-responsive table-hover">
                        <table class="table table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th >Código</th>
                                    <th scope="col">Data</th>
                                    <th scope="col">Usuário</th>
                                    <th scope="col">Nome arquivo original</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" class="text-right" style="min-width: 220px"></th>
                                </tr>
                            </thead>
                           <tbody>
                                @foreach($dados as $d)
                                    <tr>
                                        <td >{{$d->id}}</td>
                                        <td>{{$d->created_at->format('d/m/Y H:i:s')}}</td>
                                        <td>{{$d->usuario->name}}</td>
                                        <td>{{$d->nome_arquivo}}</td>
                                        <td>
                                            @if($d->qtd_linhas_certas<=0 && $d->qtd_linhas_erros<=0)
                                                <span class="text-warning" ><b>Processando fila</b></span>
                                            @else
                                                @if($d->qtd_linhas_certas>0)
                                                    <span style="color: #00cc05" ><b>{{number_format($d->qtd_linhas_certas,0,',','.')}}</b> efetivados</span>
                                                @endif
                                                @if($d->qtd_linhas_certas>0 && $d->qtd_linhas_erros>0)
                                                    <br>
                                                @endif
                                                @if($d->qtd_linhas_erros>0)
                                                    <span style="color: #ff0000" ><b>{{number_format($d->qtd_linhas_erros,0,',','.')}}</b> não efetivados</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            <a href="{{url('/indica/gestao/questao/detalhes/'.$d->id)}}" class="btn btn-secondary btn-sm"><i class="fas fa-search"></i> Detalhes</a>
                                            <a href="{{url('/indica/gestao/questao/download/'.$d->id)}}" class="btn btn-secondary btn-sm"><i class="fas fa-file-download"></i></a>
                                        </td>
                                    </tr>
                                @endforeach()

                           </tbody>
                        </table>
                    </div>
                </section>
            </div>
            <nav class="mt-4" aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    {{$dados->appends(Request::all())->links()}}
                </ul>
            </nav>
		</div>
	</div>
</section>

<div class="modal fade" id="formImportar" tabindex="-1" role="dialog" aria-labelledby="formImportar" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
            </button>
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Importação de questões do Indica em lote</h5>
            </div>
            <div class="modal-body">
                <form action="{{url('/indica/gestao/questao/importa')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Arquivo *</label>
                                <input type="file" name="arquivo" accept=".csv" value="" class="form-control rounded {{ $errors->has('arquivo') ? 'is-invalid' : '' }}">
                                <div class="invalid-feedback">{{ $errors->first('arquivo') }}</div>
                            </div>
                        </div>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Importar</button>
                <button type="button" class="btn btn-link" data-dismiss="modal">Cancelar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

</script>

@stop
