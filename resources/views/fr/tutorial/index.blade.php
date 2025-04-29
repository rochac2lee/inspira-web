@extends(Auth::user() ? 'fr/master' : 'fr/masterFora')
@section('content')
	<section class="section section-interna">
		<div class="container">
			<h2 class="title-page">
				<a href="{{url('colecao_tutorial')}}" title="Voltar" class="btn btn-secondary"> <i class="fas fa-arrow-left"></i> </a>
				Tutoriais - {{$colecao->nome}}
			</h2>
			<div class="row section-grid colecoes">
			@foreach ($tutorial as $t)
				<div class="col-md-4 grid-item">
					<a href="javascript: void(0);" data-toggle="modal" data-target="#novo-topico" data-title='{{$t->descricao}}' data-descricao='{{$t->descricao_modal}}' data-conteudo='<iframe class="embed-responsive-item" src="https://player.vimeo.com/video/{{$t->codigo_vimeo}}?autoplay=1&color=ffffff&title=0&byline=0&portrait=0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>' >
						<div class="card text-center">
							<div class="card-body">
								<div class="img">
									<img class="img-fluid" src="{{config('app.cdn')}}/storage/colecaotutorial/capa_internas/{{$t->capa}}" />
								</div>
							</div>
							<div class="card-footer" style="min-height: 85px;">
								<strong class="title h6 font-weight-bold d-block">{{$t->titulo}}</strong>
								<span class="text">{{$t->descricao}}</span>
							</div>
                            @if($t->arquivo_pdf != '')
                                <div class="card-footer" >
                                    <a href="javascript:void(0)" onclick="verPdf('{{$t->arquivo_pdf}}' , '{{$t->descricao}}', '{{$t->descricao_modal}}')" class="btn btn-secondary btn-sm mt-1" data-toggle="tooltip" data-placement="top" title="Ver tutorial em PDF"><i class="fas fa-eye"></i></a>
                                    <a href="{{url('/tutorial/download/'.$t->id)}}" class="btn btn-secondary btn-sm mt-1" data-toggle="tooltip" data-placement="top" title="Baixar tutorial em PDF"><i class="fas fa-download"></i></a>
                                </div>
                            @endif
						</div>
					</a>
				</div>
			@endforeach
			</div>
		</div>
	</section>

	<!-- MODAL VIDEO-->
	<div class="modal fade" id="novo-topico" tabindex="-1" role="dialog" aria-labelledby="novo-topico" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
			</button>
			<div class="modal-header">
				<h5 class="modal-title"></h5>
			</div>
			<div class="modal-body p-0">
				<div class="embed-responsive embed-responsive-16by9">
				  	<iframe src="" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
				</div>
			</div>
			<div class="modal-footer">
				<p></p>
			</div>
			</div>
		</div>
	</div>
	<!-- FIM MODAL EXEMPLO -->

    <!-- MODAL VIDEO-->
    <div class="modal fade" id="verPDF" tabindex="-1" role="dialog" aria-labelledby="verPDF" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
                </button>
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                </div>
                <div class="modal-body p-0">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe src="" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
                    </div>
                </div>
                <div class="modal-footer">
                    <p></p>
                </div>
            </div>
        </div>
    </div>
    <!-- FIM MODAL EXEMPLO -->

	<script type="text/javascript">
		$('#novo-topico').on('show.bs.modal', function (event) {
		  var button = $(event.relatedTarget) // Button that triggered the modal
		  var title = button.data('title') // Extract info from data-* attributes
		  var conteudo = button.data('conteudo')
		  var descricao = button.data('descricao')
		  var modal = $(this)
		  modal.find('.embed-responsive').html(conteudo);
		  modal.find('.modal-title').html(title);
		  modal.find('.modal-footer').html('<p>'+descricao+'</p>');
		});

		$('#novo-topico').on('hide.bs.modal', function (event) {
		  var modal = $(this)
		  modal.find('.modal-title').html('');
		  modal.find('.embed-responsive').html('');
		  modal.find('.modal-footer').html('');
		});

		function verPdf(conteudo, titulo, descricao){
            var modal = $('#verPDF');
            modal.find('.embed-responsive').html('<object data="{{config('app.cdn')}}/storage/tutorial_pdf/'+conteudo+'" type="application/pdf" style="width: 100%; height: 31vw;"> </object>');
            modal.find('.modal-title').html(titulo);
            modal.find('.modal-footer').html('<p>'+descricao+'</p>');
            modal.modal('show');
        }
	</script>
@stop
