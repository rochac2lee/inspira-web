<div class="row">
    <div class="col-12 text-justify">
        <p class="text-right">
            <span class="avatar"><img class="img-fluid" width="40px" src="{{$dados->usuario->avatar}}"></span>
            <b>{{$dados->usuario->nome}}</b>
        </p>
    </div>
    <div class="col-12">
        <h5>{{$dados->titulo}}</h5>

    </div>
    <div class="col-12 text-justify">
        <p>@php echo nl2br($dados->descricao) @endphp</p>
    </div>

    @if(count($dados->imagens) == 1)
        <div class="col-12 text-center">
            <img class="img-fluid" width="300px" src="{{ config('app.cdn').'/storage/agenda/noticias/'.$dados->user_id.'/'.$dados->id.'/'.$dados->imagens[0]->caminho}}">
        </div>
    @elseif(count($dados->imagens) > 1)
        <div class="col-12 text-center ">
            <section id="banner" class="section">
                <div class="slide-banner">
                    @foreach($dados->imagens as $img)
                        <img class="img-fluid m-2 border" width="200px" src="{{ config('app.cdn').'/storage/agenda/noticias/'.$dados->user_id.'/'.$dados->id.'/'.$img->caminho}}">
                    @endforeach
                </div>
            </section>
        </div>
    @endif
    @if($dados->link_video != '')
        <div class="col-12 text-center pt-3">
            {!!$dados->link_video_iframe!!}
        </div>
    @endif
    <div class="col-12 text-right">
        <p>postado em: {{$dados->updated_at->format('d/m/Y')}}</p>
    </div>

</div>
<script type="text/javascript">
    $('.slide-banner').slick({
        dots: true,
        infinite: true,
        speed: 300,
        slidesToShow: 1,
        centerMode: true,
        variableWidth: true
    });
</script>
