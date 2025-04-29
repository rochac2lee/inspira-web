<html lang="pt">
<head>
    <!-- Fonts -->
    <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/includes/fonts/stylesheet.css" type="text/css" charset="utf-8" />
    <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/includes/fonts/fontawesome/css/all.min.css" type="text/css" charset="utf-8" />

    <!-- JS -->
    <script type="text/javascript" src="{{ config('app.cdn') }}/fr/includes/js/jquery/jquery-3.4.1.min.js"></script>
    <script src="{{ config('app.cdn') }}/fr/includes/js/popper.min.js"></script>
    <script type="text/javascript" src="{{ config('app.cdn') }}/fr/includes/js/bootstrap/bootstrap.min.js"></script>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/includes/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/includes/css/style_v5.css">
    <link rel='stylesheet' type="text/css" href="{{ config('app.cdn') }}/fr/includes/audioplayer/audioplayer.css"/>
    <script src="{{ config('app.cdn') }}/fr/includes/audioplayer/audioplayer.js" type="text/javascript"></script>
    <script>
        var settings1 = {
            disable_volume: "off"
            ,autoplay: "off"
            ,cue: "on"
            ,disable_scrub: "default"
            ,design_skin: "skin-wave"
            ,skinwave_dynamicwaves:"on"
            ,skinwave_enableSpectrum: "off"
            ,settings_backup_type:"full"
            ,settings_useflashplayer:"auto"
            ,skinwave_spectrummultiplier: "4"
            ,skinwave_comments_enable:"on"
            ,skinwave_mode: "small"
            ,skinwave_comments_retrievefromajax:"on"
            ,pcm_data_try_to_generate: "on"
        };
    </script>
</head>
<body class="interna">
    <section class="section mb-3">
        <div class="container">
            <div class="row">
                <div class="col-auto">
                    <img style="max-width: 150px;" src="{{$dados->capa_album}}" width="100%">
                </div>
                <div class="col-auto">
                    <h2>{{$dados->titulo}}</h2>
                    <p>{{$dados->descricao}}</p>
                </div>
            </div>
        </div>
    </section>
    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                <table class="table table-striped table-bordered">
                    @php $i = 1; @endphp
                    @foreach($dados->lista as $l)
                    <tr>
                        <td>
                            <b>{{$l->titulo}}</b>
                            <div id="player{{$i}}" data-playerid="200" class="audioplayer-tobe is-single-player " style="width:100%; margin-top:10px; margin-bottom: 10px;" data-thumb="{{$l->capa_audio}}" data-thumb_link="{{$l->capa_audio}}" data-type="audio" data-source="{{$l->audio}}" data-fakeplayer="#ap1"  >
                                <div class="meta-artist"></div>
                            </div>
                        </td>
                    </tr>
                    @php $i++; @endphp
                    @endforeach
                </table>
            </div>
        </div>
    </section>
<script>
    $(document).ready(function(){
        @php $i = 1; @endphp
        @foreach($dados->lista as $l)
            $("#player{{$i}}").audioplayer(settings1);
        @php $i++; @endphp
        @endforeach
    });
</script>
</body>
</html>
