    <div class="modal fade" id="formAddEvento" tabindex="-1" role="dialog" aria-labelledby="formAddEvento" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
                </button>
                <div class="modal-header">
                    <h5 class="modal-title" id="tituloForm">Novo Evento</h5>
                </div>
                <div class="modal-body">
                    <form action="" id="formFormulario" method="post">
                        <div class="row">
                            <div class="col-12">
                                @csrf
                                <input type="hidden" name="id" id="eventoId" value="{{old('id')}}">
                                <div class="row">
                                    <div class="col-12 mb-1 text-center">
                                        <img width="50px" class="img-fluid" id="avatarUsuario" src="{{auth()->user()->avatar}}">
                                        <span class="ml-3"><b id="nomeUsuario">{{auth()->user()->nome}}</b></span>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Título *</label>
                                            <input type="text" name="titulo" id="titulo" placeholder="" value="{{old('titulo')}}" class="form-control rounded {{ $errors->has('titulo') ? 'is-invalid' : '' }}">
                                            <div class="invalid-feedback">{{ $errors->first('titulo') }}</div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Descrição *</label>
                                            <textarea name="descricao" id="descricaoEvento" class="form-control rounded  {{ $errors->has('descricao') ? 'is-invalid' : '' }}" rows="4">{{old('descricao')}}</textarea>
                                            <div class="invalid-feedback">{{ $errors->first('descricao') }}</div>
                                        </div>

                                    </div>
                                    <div class="col-12">
                                        <div class="form-group" style="margin-bottom: 5px">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="ckDiaInteiro" name="dia_inteiro" value="1" onchange="mudaTipoEvento()">
                                                <label class="form-check-label" for="ckDiaInteiro">Evento ocorrerá o dia todo</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group" >
                                            <label>Data inicial *</label>
                                            <input name="data_inicial" autocomplete="off" value="{{old('data_inicial')}}" type="text" id="datetimepicker1" class="form-control form-control-sm rounded {{ $errors->has('data_inicial') ? 'is-invalid' : '' }}" />
                                            <div class="invalid-feedback">{{ $errors->first('data_inicial') }}</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Data final *</label>
                                            <input name="data_final" autocomplete="off" value="{{old('data_final')}}" type="text" id="datetimepicker" placeholder="dd/mm/aaaa" class="form-control form-control-sm rounded {{ $errors->has('data_final') ? 'is-invalid' : '' }}" />
                                            <div class="invalid-feedback">{{ $errors->first('data_final') }}</div>
                                        </div>
                                    </div>
                                    @if(auth()->user()->permissao == 'I')
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>Visualização *</label>
                                                <select name="visualizacao" id="visualizacao" class="form-control" onchange="mudaVisualizacao(this)">
                                                    <option value="1" @if(old('visualizacao') == '1' || old('visualizacao') == '') selected @endif>Todas as escolas de sua instituição</option>
                                                    <option value="2" @if(old('visualizacao') == '2') selected @endif>Selecionar escolas específicas de sua instituição</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12" id="divEscola">
                                            <div class="form-group">
                                                <label>Escolas *</label>
                                                <select id="ckEscola" name="escola[]" size="4" class="form-control ckEscola" multiple>
                                                    @php
                                                        $vet =[];
                                                        if(is_array(old('escola')))
                                                        {
                                                           $vet = old('escola');
                                                        }
                                                    @endphp
                                                    @foreach($escola as $e)
                                                        <option @if(in_array($e->id,$vet)) selected @endif value="{{$e->id}}">{{$e->titulo}}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback" style="display: block">{{ $errors->first('escola') }}</div>
                                            </div>
                                        </div>

                                    @else
                                        <input type="hidden" value="{{auth()->user()->escola_id}}" name="escola[]">
                                    @endif
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Tema *</label><br>
                                            @foreach($etiquetas as $e)
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" name="tema" type="radio" id="tema{{$loop->iteration}}" value="{{$e->cor}}" @if(old('tema') == $e->cor ||old('tema')=='' ) checked @endif>
                                                <label class="form-check-label p-2" for="tema{{$loop->iteration}}" style="background-color: {{$e->cor}}; color: white">{{$e->titulo}}</label>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
                <div id="btns" class="modal-footer">
                    <button id="btnExcluir" onclick="confirmaExcluir()" type="button" class="btn btn-danger mr-5"  style="display: none"><i class="fas fa-trash-alt"></i> Excluir</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" onclick="enviaFormulario()">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- EXCLUIR -->
    <div class="modal fade" id="formExcluir" tabindex="-1" role="dialog" aria-labelledby="formExcluir" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
                </button>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Exclusão de Evento</h5>
                </div>
                <div class="modal-body">
                    <form action="">
                        <div class="row">
                            <div class="col-12">
                                Você deseja mesmo excluir esse evento?<br><br>
                                <b id="nomeEvento"></b>
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
    <script>

        var avatarUsuario = '{{auth()->user()->avatar}}';
        var nomeUsuario = '{{auth()->user()->nome}}';
        var selUnidade = null;
        var tipoOperacao = 'add';
        var idExcluir = 0;
        function dataBR(data){
            data = data.split('-');
            return data[2]+'/'+data[1]+'/'+data[0];
        }
        jQuery.datetimepicker.setLocale('pt-BR');
        function mudaTipoEvento(){
            if($('#ckDiaInteiro').is(":checked")){
                config ={
                    format:'d/m/Y',
                    timepicker:false,
                };
            }
            else{
                config ={
                    format:'d/m/Y H:i:00',
                    timepicker:true,
                };
            }
            jQuery('#datetimepicker, #datetimepicker1').datetimepicker(config);
        }

        function mudaVisualizacao(elemento){
            if($(elemento).val() != 1){
                $('#divEscola').show();
            }else{
                $('#divEscola').hide();
            }
        }

        $(document).ready(function(){

            $('#visualizacao').change();
            mudaTipoEvento();

            @if(auth()->user()->permissao == 'I')
            selUnidade = new vanillaSelectBox(".ckEscola", {
                "maxHeight": 250,
                "search": true ,
                "placeHolder": "Selecione as escolas",
            });
            @endif

            @if($errors->all() != null )
                erro = 1;
                @if(old('id')!='')
                    tipoOperacao = 'editar';
                    $('#tituloForm').html('Editar Evento');
                @else
                    tipoOperacao = 'add';
                @endif
                $('#formAddEvento').modal('show');
            @endif

        });

        $('#formAddEvento').on('hidden.bs.modal', function () {
            $('#btnExcluir').hide();
            tipoOperacao = 'add';
            $('#eventoId').val('');

            $('input').removeClass('is-invalid');
            $('textarea').removeClass('is-invalid');
            $('#visualizacao option[value="1"]').prop('selected', true);
            $('#visualizacao').change();
            $('#ckEscola option').prop('selected', false);
            $('#avatarUsuario').prop('src',avatarUsuario)
            $('#nomeUsuario').html(nomeUsuario);
            $('#tituloForm').html('Novo Evento');
            $('#titulo').val('');
            $('#descricaoEvento').val('');
            $('#ckDiaInteiro').prop('checked',false);
            mudaTipoEvento();
            $('#btns').show();
            $('#formFormulario input').prop('disabled',false);
            $('#formFormulario textarea').prop('disabled',false);
        });
        $('#formExcluir').on('hidden.bs.modal', function () {
            idExcluir = 0;
        });
        function enviaFormulario()
        {
            if(tipoOperacao == 'add')
            {
                $('#formFormulario').attr('action','{{url('/gestao/agenda/calendario/add/')}}');
                $('#formFormulario').submit();
            }
            else if(tipoOperacao == 'editar')
            {
                $('#formFormulario').attr('action','{{url('/gestao/agenda/calendario/editar/')}}');
                $('#formFormulario').submit();
            }
        }

        function excluir()
        {
            window.location.href = '{{url("/gestao/agenda/calendario/excluir")}}/'+idExcluir;
        }

        function confirmaExcluir()
        {
            $('#nomeEvento').html($('#titulo').val());
            $('#formAddEvento').modal('hide');
            $('#formExcluir').modal('show');
        }

        function tornaReadyOnly(){
            $('#btns').hide();
            $('#formFormulario input').prop('disabled',true);
            $('#formFormulario textarea').prop('disabled',true);
        }
    </script>
