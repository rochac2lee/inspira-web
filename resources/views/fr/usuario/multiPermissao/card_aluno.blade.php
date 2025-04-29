<div class="item bg-5">
    <div class="banner mx-auto">
        <div class="borda"></div>
        <div class="titulo">Estudante</div>
        <div class="user-desc">
            <img src="{{config('app.cdn')}}/fr/imagens/2021/user-5.png">
            <p class="mt-3 mb-3">{{$d->escola->titulo}}</p>
            <form action="{{url('/multiplasPermissoes/entrar')}}" method="post">
                @csrf
                <input type="hidden" name="id" value="{{$d->id}}">
                <input type="hidden" name="escola_id" value="{{$d->escola_id}}">
                <input type="hidden" name="instituicao_id" value="{{$d->instituicao_id}}">
                <input type="hidden" name="permissao" value="{{$d->permissao}}">
                <button type="submit" class="btn btn-primary btn-new">Entrar</button>
            </form>
        </div>
    </div>
</div>
