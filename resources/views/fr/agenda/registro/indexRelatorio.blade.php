<div class="container-fluid mb-5">
    <div class="col-md-12">
        <div class="row pb-4 pt-4">
            <div class="col-md-12">
                <h4 class="pb-3 border-bottom mb-3">
                    Agenda e Registro
                </h4>
            </div>
        </div>
    <div class="row">
        <section class="table-page w-100 m-3">
                <div class="table-responsive table-hover">
                    <table class="table table-striped table-bordered " id="tableRegistro">
                        <thead>
                        <tr>
                            <td>
                                <p><b>{{$registro['turma']->escola->titulo}}</b></p>
                                <p><b>{{$nome_professor}}</b></p>
                                <p><b>{{$registro['turma']->ciclo}} / {{$registro['turma']->ciclo_etapa}} - {{$registro['turma']->titulo}}</b></p>
                                <p><b>{{dataBR($data)}}</b></p>
                            </td>
                            @foreach($registro['rotina'] as $r)
                                <td class="text-center pt-2" >
                                    <img class="img-fluid" src="{{$r->linkImagem}}" width="45px">
                                    <p class="mb-2"><strong>{{$r->titulo}}</strong></p>
                                        @if(isset($registro['dadosRegistros'][$r->id]))
                                            <span class="text-success"><i class="fas fa-check-circle fa-2x"></i></span>
                                        @else
                                            <span class="text-secondary"><i class="fas fa-circle fa-2x"></i></span>
                                        @endif
                                </td>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($registro['turma']->alunos as $d)
                            <tr>
                                <td>
                                    {{$d->nome}}
                                </td>
                                @foreach($registro['rotina'] as $r)
                                    <td>
                                        <div class="form-row">
                                            <div class="col-12">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
														<span class="input-group-text" style="padding-right: 8px">
                                                            @if(@$registro['dadosRegistros'][$r->id][$d->id]->marcado == 1)
                                                                <span class="text-success"><i class="fas fa-check-circle fa-2x"></i></span>
                                                            @else
                                                                @if(isset($registro['dadosRegistros'][$r->id]))
                                                                    <span class="text-secondary"><i class="fas fa-times-circle fa-2x"></i></span>
                                                                @else
                                                                    <span class="text-secondary"><i class="fas fa-circle fa-2x"></i></span>
                                                                @endif
                                                            @endif
                                                        </span>
                                                    </div>
                                                    <textarea readonly class="form-control rotinaAlunoText{{$r->id}}" maxlength="35" aria-label="With textarea">@if(isset($registro['dadosRegistros'][$r->id][$d->id])){{$registro['dadosRegistros'][$r->id][$d->id]->texto}}@endif</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
        </section>
    </div>

</div>
