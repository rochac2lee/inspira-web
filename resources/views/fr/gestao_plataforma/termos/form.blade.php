@extends('fr/master')
@section('content')
    <!--  Exclusivo Froala Editor  -->
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/froala_editor.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/froala_style.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/code_view.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/draggable.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/colors.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/emoticons.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/image_manager.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/image.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/line_breaker.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/table.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/char_counter.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/video.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/fullscreen.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/file.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/quick_insert.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/help.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/third_party/spell_checker.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/special_characters.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/codemirror.min.css">
<!--  FIM Froala Editor  -->
<section class="section bg-dark text-white py-4">
    <div class="container-fluid">
        <div class="path float-left p-2">
            <a href="{{url('')}}">Home</a> / Alterar termos
        </div>
    </div>
</section>
<section>
    <div class="container-fluid">
        <form action="" method="post">
            @csrf
        <div class="row">
            <div class="col-md-12 mt-4">
                <h4 class="pb-3 border-bottom mb-4">Gestão de Termos</h4>
                <div class="card">
                    <div class="card-header">Termos de uso</div>
                    <div class="card-body">
                        <div class="filter">
                            <textarea id='edit1' name="termo">{{$plataforma->termo}}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mt-2">
                <div class="card">
                    <div class="card-header">Política de privacidade</div>
                    <div class="card-body">
                        <div class="filter">
                            <textarea id='edit2' name="politica">{{$plataforma->politica}}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <button type="submit" class="btn btn-success">Salvar</button>
                <a href="{{url('')}}">cancelar</a>
            </div>
        </div>
    </form>
    </div>
</section>
       <!--  Exclusivo Froala Editor  -->
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/codemirror.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/xml.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/purify.min.js"></script>

  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/froala_editor.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/align.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/code_beautifier.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/code_view.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/colors.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/draggable.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/font_size.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/font_family.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/image.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/line_breaker.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/inline_style.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/lists.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/paragraph_format.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/paragraph_style.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/word_paste.min.js"></script>
  <script type="text/javascript" src='{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/languages/pt_br.js'></script>

  <script>
      $(document).ready(function(){
            new FroalaEditor('#edit1, #edit2', {
              key: "{{config('app.froala')}}",
              attribution: false,
              heightMin: 132,
              buttonsVisible: 4,
              placeholderText: '',
            });
      })
  </script>
@stop
