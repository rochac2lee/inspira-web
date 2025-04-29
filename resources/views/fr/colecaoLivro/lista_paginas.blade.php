@foreach($dados as $d)
    <div class="col-2 m-3 border text-center">
        <img class="img-fluid m-2" width="100px" src="{{config('app.cdn')}}/storage/livrodigital/{{$livro_id}}/{{$d}}.webp"/><br>
        <p>Página {{$d}}</p>
        @php
            $link = '<iframe src="'.config('app.cdn').'/storage/livrodigital/'.$livro_id.'/'.$d.'.webp" width="100%" height="400px" allowfullscreen=""></iframe>';
        @endphp
        <button type="button" href="javascript:void(0)" class="btn btn-secondary btn-sm mb-2" data-trigger= "click" data-toggle="tooltip" data-placement="top" title="Link copiado!" onclick="copiarLink(this,'{{$link}}')">
            <i class="fas fa-book"></i>
        </button>
    </div>
@endforeach
<div class="col-12 m-3 text-center">
    <nav class="mt-5" aria-label="Page navigation example">
        {{ $dados->links() }}
    </nav>
</div>
<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
