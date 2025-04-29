
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
        <section class="table-page w-100">
            <form action="{{url('/gestao/agenda/registros/salvar')}}" method="post" autocomplete="off">
                @csrf
                <input type="hidden" value="{{$registro['turma']->id}}" name="turma_id">
                <input type="hidden" value="{{$data}}" name="data">
                <div class="table-responsive table-hover">
                    <table class="table table-striped table-bordered mb-0" id="tableRegistro">
                        <thead>
                        <tr>
                            <td>
                                <p><b>{{$registro['turma']->escola->titulo}}</b></p>
                                <p><b>{{$nome_professor}}</b></p>
                                <p><b>{{$registro['turma']->ciclo}} / {{$registro['turma']->ciclo_etapa}} - {{$registro['turma']->titulo}} / {{$registro['turma']->turno}}</b></p>
                                <p><b>{{dataBR($data)}}</b></p>

                            </td>
                            @foreach($registro['rotina'] as $r)
                                <td class="text-center pt-2" >
                                    <img class="img-fluid" src="{{$r->linkImagem}}" width="45px">
                                    <p class="mb-2 mt-1"><strong>{{$r->titulo}}</strong></p>
                                    <div class="custom-control custom-switch ">
                                        <input @if(isset($registro['dadosRegistros'][$r->id]))checked @endif  name="registro[]"  value="{{$r->id}}" type="checkbox" class="custom-control-input" id="rotinaSwitch{{$r->id}}" onchange="ativaRotina({{$r->id}})" >
                                        <label class="custom-control-label pt-1" for="rotinaSwitch{{$r->id}}"></label>
                                    </div>

                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="checkTodosRotina{{$r->id}}" onchange="marcarTodosRotina({{$r->id}})">
                                        <label class="custom-control-label" for="checkTodosRotina{{$r->id}}">Marcar todos</label>
                                    </div>
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
														<span class="input-group-text">
                                                            <div class="custom-control custom-switch ">
                                                                <input @if(@$registro['dadosRegistros'][$r->id][$d->id]->marcado == 1)checked @endif name="marcado[{{$r->id}}][{{$d->id}}]"  value="1" type="checkbox" class="custom-control-input rotinaAlunoSwitch{{$r->id}}" id="rotinaSwitch{{$d->id}}{{$r->id}}" >
                                                                <label class="custom-control-label pt-1" for="rotinaSwitch{{$d->id}}{{$r->id}}"></label>
                                                            </div>
                                                        </span>
                                                    </div>
                                                    <textarea name="texto[{{$r->id}}][{{$d->id}}]" class="form-control rotinaAlunoText{{$r->id}}" maxlength="35" aria-label="With textarea">@if(isset($registro['dadosRegistros'][$r->id][$d->id])){{$registro['dadosRegistros'][$r->id][$d->id]->texto}}@endif</textarea>
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
                <div class="row">
                    <div class="col-12 my-4 pb-3">
                        <a href="{{url('/gestao/agenda/registros')}}" class="btn btn-secondary float-left" >Cancelar</a>
                        <button type="submit" class="btn btn-default mt-0 float-right ml-2">Salvar</button>
                    </div>
                </div>
            </form>
        </section>
    </div>

</div>
