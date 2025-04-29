<form action="{{url('/gestao/agenda/registros/buscar')}}" method="get" autocomplete="off">
    @csrf
    <div class="form-row">
        <div class="col-2">
            <label>Data:</label>
            <input size="40" type="text" name="data" id="datetimepicker" value="@if(@$data!= ''){{dataBR($data)}}@else{{date('d/m/Y')}}@endif" class="form-control {{ $errors->has('data') ? 'is-invalid' : '' }}" />
            <div class="invalid-feedback" style="display: block">{{ $errors->first('data') }}</div>
        </div>
        <div class="col-3">
            <label>Escola:</label>
            <select class="selEscola " id="selEscola" name="escola_id" style="border: 1px solid {{ $errors->has('escola_id') ? '#DC3545' : '#ffb100' }} ; border-radius: 0.4rem;" onchange="mudouEscola()">
                <option value="">Selecione</option>
                @foreach($escolas as $e)
                    <option @if(@$escola_id == $e->id) selected @endif value="{{$e->id}}">{{$e->titulo}}</option>
                @endforeach
            </select>
            <div class="invalid-feedback" style="display: block">{{ $errors->first('escola_id') }}</div>
        </div>
        <div class="col-3">
            <label>Professor:</label>
            <select class="selProfessor " id="selProfessor" name="professor_id" style="border: 1px solid {{ $errors->has('professor_id') ? '#DC3545' : '#ffb100' }} ; border-radius: 0.4rem;" onchange="mudouProfessor()">
                <option value="">Selecione</option>
            </select>
            <div class="invalid-feedback" style="display: block">{{ $errors->first('professor_id') }}</div>
        </div>
        <div class="col-3">
            <label>Turma:</label>
            <select class="selTurma" id="selTurma" name="turma_id" style="border: 1px solid {{ $errors->has('turma_id') ? '#DC3545' : '#ffb100' }} ; border-radius: 0.4rem;">
                <option value="">Selecione</option>
            </select>
            <div class="invalid-feedback" style="display: block">{{ $errors->first('turma_id') }}</div>
        </div>

        <div class="col-1">
            <button type="submit" class="btn btn-secondary mt-4">Buscar</button>
        </div>
    </div>
</form>
<script>
    var selProfessor = null;
    var selEscola = null;
    $(document).ready(function(){
        selEscola = new SlimSelect({
            select: '.selEscola',
            placeholder: 'Buscar',
            searchPlaceholder: 'Buscar',
            closeOnSelect: true,
            allowDeselectOption: true,
            selectByGroup: true,
        });

        selProfessor = new SlimSelect({
            select: '.selProfessor',
            placeholder: 'Buscar',
            searchPlaceholder: 'Buscar',
            closeOnSelect: true,
            allowDeselectOption: true,
            selectByGroup: true,
        });

        @if(isset($escola_id))
            getProf({{$escola_id}}, {{$professor_id}})
        @endif

        @if(isset($professor_id))
            getTurmasProf({{$professor_id}}, {{$registro['turma']->id}})
        @endif
    })

    function mudouEscola(){
        escolaId = $('#selEscola').val();
        getProf(escolaId)
    }

    function mudouProfessor(){
        professorId = $('#selProfessor').val();
        getTurmasProf(professorId)
    }

    function getTurmasProf(idProf, turma = null)
    {
        $.ajax({
            url: '{{url('/gestao/agenda/registros/getTurmaProf')}}',
            type: 'post',
            dataType: 'json',
            data: {
                escola_id: $('#selEscola').val(),
                professor_id: idProf,
                turma_id: turma,
                _token: '{{csrf_token()}}'
            },
            success: function (data) {
                selTurma.setData(data);
            },
            error: function () {
                swal("", "Não foi possível carregar a lista de turmas selecionadas.", "error");
            }
        });
    }

    function getProf(idEscola, prof = null)
    {
        $.ajax({
            url: '{{url('/gestao/agenda/registros/getProf')}}',
            type: 'post',
            dataType: 'json',
            data: {
                escola_id: idEscola,
                professor_id: prof,
                _token: '{{csrf_token()}}'
            },
            success: function (data) {
                selProfessor.setData(data);
            },
            error: function () {
                swal("", "Não foi possível carregar a lista de professores selecionadas.", "error");
            }
        });
    }
</script>
