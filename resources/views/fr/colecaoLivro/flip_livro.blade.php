@if(isset($flipSozinho) && $flipSozinho==1)
    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/js/jquery/jquery-3.4.1.min.js"></script>
@endif
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
<script src="{{config('app.cdn')}}/fr/flipbook/js/flipbook.min.js"></script>
<link rel="stylesheet" type="text/css" href="{{config('app.cdn')}}/fr/flipbook/css/flipbook.style.css">
<link rel="stylesheet" type="text/css" href="{{config('app.cdn')}}/fr/flipbook/css/font-awesome.css">

<div id="container" class="flipLivro"></div>

<script type="text/javascript">

    $(document).ready(function () {
        $("#container").flipBook({
            pages:[
                @for($i=1;$i<=$livro->qtd_paginas_livro;$i++)
                    {src:"{{config('app.cdn')}}/storage/livrodigital/{{$livro->id}}/{{$i}}.webp?v=req-original-{{$livro->id}}-{{$i}}", thumb:"{{config('app.cdn')}}/storage/livrodigital/{{$livro->id}}/{{$i}}.webp?v=thumb-{{$livro->id}}-{{$i}}", title:"Página {{$i}}"},
                @endfor
            ],
            btnZoomIn : {enabled:true},
            btnZoomOut : {enabled:true},
            btnToc : {enabled:false},
            btnBookmark : {enabled:false},
            btnShare : {enabled:false},
            btnDownloadPages : {enabled:false},
            btnDownloadPdf : {enabled:false},
            btnSound : {enabled:false},
            btnPrint : {enabled:false},

            skin:'dark',
            sound: false,

            menuMargin:10,
            menuBackground:'none',
            menuAlignHorizontal:'right',

            btnRadius:40,
            btnMargin:4,
            btnSize:14,
            btnPaddingV:16,
            btnPaddingH:16,
            btnBorder:'2px solid rgba(255,255,255,.7)',
            btnBackground:"rgba(0,0,0,.3)",
            btnColor:'rgb(255,120,60)',

            sideBtnSize:60,
            sideBtnBackground:"rgba(0,0,0,.7)",
            sideBtnColor:'rgb(255,120,60)',
            backgroundColor:"#666",

            strings: {

                search: "Busca",
                findInDocument: "Encontrar no documento",
                pagesFoundContaining: "'paginas encontradas contendo",

                thumbnails: "Páginas",
                tableOfContent: "Tabela de Conteúdos",
                pages: "Páginas",

                pressEscToClose:"ESC para fechar"


            },

        });
    })
</script>
