<div class="item bg-2">
    <div class="banner mx-auto">
        <div class="borda"></div>
        <div class="titulo">Gestor Institucional</div>
        <div class="user-desc">
            <img src="{{config('app.cdn')}}/fr/imagens/2021/user-2.png">
            <p class="mt-3">{{$d->instituicao->titulo}}</p>
            <form action="{{url('/multiplasPermissoes/entrar')}}" method="post">
                @csrf
                <input type="hidden" name="id" value="{{$d->id}}">
                <input type="hidden" name="instituicao_id" value="{{$d->instituicao_id}}">
                <input type="hidden" name="permissao" value="{{$d->permissao}}">
                <div class=" input-group">
                    <select name="escola_id" class="form-control mb-3 ml-2 mr-2">
                        @foreach($d->escola_id as $e)
                            <option value="{{$e['id']}}">{{$e['escola']}}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-new">Entrar</button>
            </form>
        </div>
    </div>
</div>
