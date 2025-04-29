<script>
    var selectInstituicao;
    var selectEscola;
    $(document).ready(function(){
         selectInstituicao = new SlimSelect({
            select: '.selInst',
            placeholder: 'Buscar',
            searchPlaceholder: 'Buscar',
            closeOnSelect: true,
            allowDeselectOption: true,
            selectByGroup: true,
        });

         selectEscola = new SlimSelect({
            select: '.selEscola',
            placeholder: 'Buscar',
            searchPlaceholder: 'Buscar',
            closeOnSelect: true,
            allowDeselectOption: true,
            selectByGroup: true,
        });
    })

</script>
<div class="modal fade" id="formIncluir" tabindex="-1" role="dialog" aria-labelledby="formIncluir" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
            </button>
			<div class="modal-header">
				<h5 class="modal-title" id="tituloForm">Cadastro de Usuário</h5>
			</div>
			<div class="modal-body">
				<form id="formFormulario" action="" method="post" enctype="multipart/form-data">
					@csrf
	                <input type="hidden" name="id" value="{{old('id')}}">
	                <input type="hidden" name="matricula" value="">
	                <input type="hidden" name="ocupacao" value="">
					<div class="row">
                		<div class="col-6">
                			<div class="form-group">
                    			<label>* Nome</label>
                    			<input type="text" name="nome_completo" placeholder="" value="{{old('nome_completo')}}" class="form-control rounded {{ $errors->has('nome_completo') ? 'is-invalid' : '' }}">
		                        <div class="invalid-feedback">{{ $errors->first('nome_completo') }}</div>
                			</div>
                		</div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>* E-mail</label>
                                <input type="text" name="email" placeholder="" value="{{old('email')}}" class="form-control rounded {{ $errors->has('email') ? 'is-invalid' : '' }}">
                                <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                            </div>
                        </div>
                        <div class="col-12 mt-3 mb-4 border-bottom "><label class="font-weight-bold">Permissões</label></div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>* Permissao</label>
                                <select id="permissao" class="form-control rounded" onchange="mudaPermissao(this)">
                                    <option value="">Selecione</option>
                                    <option value="Z">Super-admin</option>
                                    <option value="I">Gestor Institucional</option>
                                    <option value="C">Gestor / Coordenador</option>
                                    <option value="P">Docente</option>
                                    <option value="A">Estudante</option>
                                </select>
                                <div class="invalid-feedback">{{ $errors->first('titulo') }}</div>
                            </div>
                        </div>
                        <div class="col-6 inst-escola" style="display: none">
                            <div class="form-group">
                                <label>* Instituicao</label>
                                <select id="instituicao" class="selInst" style="border: 1px solid #ffb100; border-radius: 0.4rem;" onchange="mudaInst(this)">
                                    <option value="">Selecione</option>
                                    @foreach($instituicao as $i)
                                        <option value="{{$i->id}}">{{$i->titulo}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">{{ $errors->first('titulo') }}</div>
                            </div>
                        </div>
                        <div class="col-6 inst-escola" style="display: none">
                            <div class="form-group">
                                <label>* Escola</label>
                                <select id="escola" class="selEscola" style="border: 1px solid #ffb100; border-radius: 0.4rem;">
                                    <option value="">Selecione</option>
                                </select>
                                <div class="invalid-feedback">{{ $errors->first('titulo') }}</div>
                            </div>
                        </div>
                        <div class="col-12 text-center">
                            <button type="button" class="btn btn-sm btn-success" onclick="addPermissao()"><i class="fas fa-plus"></i> Adicionar permissão</button>
                        </div>
                        <div class="col-12 mt-3 mb-4 border-bottom "><label class="font-weight-bold">Lista de permissões</label></div>
                        <div class="col-12">
                            <table class="table table-striped">
                                <tbody id="listaPermissao">

                                </tbody>
                            </table>
                            <div class="invalid-feedback" @if($errors->first('permissao') || $errors->first('escola_id') || $errors->first('instituicao_id')) style="display: block; font-size: 12px" @endif>É necessário escolher uma permissão para o usuário.</div>
                        </div>

                    </div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
				<button id="btnForm" type="button" class="btn btn-success" onclick="enviaFormulario()">Salvar</button>
			</div>
		</div>
	</div>
</div>
<table  style="display: none">
    <tbody id="modeloTr">
        <tr>
            <input id="inputPermissao" type="hidden" name="permissao[]" value="">
            <input id="inputEscolaId" type="hidden" name="escola_id[]" value="">
            <input id="inputInstituicaoId" type="hidden" name="instituicao_id[]" value="">
            <td id="txtPermissao"></td>
            <td id="txtEscola"></td>
            <td class="text-right"><button type="button" class="btn btn-sm btn-danger" onclick="excluirPermissao(this)"><i class="fas fa-trash-alt"></i></button></td>
        </tr>
    </tbody>
</table>
<script>
    var vet = new Array();

    function excluirPermissao(elemento){
        permissao = $(elemento).parent().parent().children().val();
        escola = $(elemento).parent().parent().children().next().val();
        const index = vet.indexOf(permissao+escola);
        if (index > -1) {
            vet.splice(index, 1);
        }
        $(elemento).parent().parent().remove();

    }

    function addPermissao(){
        permissao = $('#permissao').val();
        escola = $('#escola').val();
        instituicao = $('#instituicao').val();
        if(permissao == ''){
            alert('É necessário selecionar uma permissão.');
            return false;
        }
        if(permissao!= 'Z' && (escola == '' || instituicao == '')){
            alert('É necessário escolher uma instituição e uma escola.');
            return false;
        }
        if(vet.includes(permissao+escola)){
            alert('Permissao já adicionada.');
            return false;
        }
        addPermissaoHtml(permissao, escola, instituicao, $('#permissao option:selected').text(), $('#escola option:selected').text());
    }

    function addPermissaoHtml(permissaoId, escolaId, instituicaoId, permissao, escola)
    {
        $('#inputPermissao').val(permissaoId);
        $('#txtPermissao').html(permissao);
        if(permissaoId == 'Z'){
            $('#txtEscola').html('Editora Opet');
            $('#inputEscolaId').val(958);
            $('#inputInstituicaoId').val(1);
            vet.push(permissaoId+'958');
        }else{
            $('#txtEscola').html(escola);
            $('#inputEscolaId').val(escolaId);
            $('#inputInstituicaoId').val(instituicaoId);
            vet.push(permissaoId+escolaId);
        }
        html = $('#modeloTr').html();
        html = html.replace('id="inputPermissao"','');
        html = html.replace('id="inputEscolaId"','');
        html = html.replace('id="inputInstituicaoId"','');
        html = html.replace('id="txtPermissao"','');
        html = html.replace('id="txtEscola"','');
        $('#listaPermissao').append(html);
    }

    function mudaInst(elemento){
        escolaId = $(elemento).val();
        $.ajax({
            url: '{{url('/gestao/usuario/getEscolas/')}}',
            type: 'post',
            dataType: 'json',
            data: {
                id: escolaId,
                _token: '{{csrf_token()}}'
            },
            success: function(data) {
                selectEscola.setData(data);
            },
            error: function(data) {
                swal("", "Instituição não encontrada", "error");
                conteudo = new Array();
                selectEscola.setData(conteudo);
            }
        });
    }

    function mudaPermissao(elemento){
        perm = $(elemento).val();
        if(perm != '' && perm != 'Z'){
            $('.inst-escola').show();
        }else{
            $('.inst-escola').hide();
        }
    }
</script>
