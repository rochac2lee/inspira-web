<form action="{{url('/gestao/agenda/registros/buscar')}}" method="get" autocomplete="off">
    @csrf
    <div class="form-row">
        <div class="col-3">
            <label>Data:</label>
            <input size="40" type="text" name="data" id="datetimepicker" value="@if(@$data!= ''){{dataBR($data)}}@else{{date('d/m/Y')}}@endif" class="form-control {{ $errors->has('data') ? 'is-invalid' : '' }}" />
            <div class="invalid-feedback" style="display: block">{{ $errors->first('data') }}</div>
        </div>
        <div class="col-4">
            <label>Turma:</label>
            <select class="selTurma " id="selTurma" name="turma_id" style="border: 1px solid {{ $errors->has('turma_id') ? '#DC3545' : '#ffb100' }} ; border-radius: 0.4rem;">
                <option value="">Selecione</option>
                @foreach($turmas as $t)
                    <option @if(@$registro['turma']->id == $t->id) selected @endif value="{{$t->id}}">{{$t->ciclo}} / {{ $t->ciclo_etapa}} - {{$t->titulo}} / {{$t->turno}}</option>
                @endforeach
            </select>
            <div class="invalid-feedback" style="display: block">{{ $errors->first('turma_id') }}</div>
        </div>

        <div class="col-4">
            <button type="submit" class="btn btn-secondary mt-4">Buscar</button>
        </div>
    </div>
</form>
