@foreach($dados as $d)
    <div class="row">
        <div class="col-12 text-justify">
            <p>
                <span class="avatar"><img class="img-fluid" width="40px" src="{{$d->avatar}}"></span>
                <b>{{$d->nome}}</b>
            </p>
        </div>
    </div>
    <div class="row">
        @foreach($d->documentosEnviadosResponsavel as $f)
        <div class="col-4 p-1 text-center">
            <img src="{{$f->link_arquivo}}" class="img-fluid" >
            <small>postado em <br>{{$f->updated_at->format('d/m/Y H:i:s')}}</small> <br>
            <a href="{{url('/gestao/agenda/documentos/recebidos/download/'.$f->id)}}" class="btn btn-sm btn-secondary">Download</a>
        </div>
        @endforeach
    </div>
@endforeach
