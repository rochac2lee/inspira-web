@foreach($selecionados as $s)
    <li class="ui-state-default text-truncate" id="{{$tipo}}Adicionado{{$s->id}}">
        <input type="hidden" name="{{$tipo}}[]" value="{{$s->id}}">
        <span class="m-2"><img src="{{$s->avatar}}" style="height:35px;"></span>
        {{$s->nome}} <small class="ml-2">{{$s->email}}</small>
        <button type="button" class="btn btn-sm btn-danger" onclick="excluir{{ucfirst($tipo)}}({{$s->id}})" style="float: right"><i class="fas fa-trash-alt" style="color: white"></i></button>
    </li>
@endforeach
