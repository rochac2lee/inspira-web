<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <meta name="title" content="Opet Inspira" />
    <meta name="description" content="Opet Inspira" />
    <meta name="keywords" content="Opet Inspira" />
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/css/bootstrap/bootstrap.min.css">

    <style type="text/css">
        body {
            background-color: #666;
        }

        .affix {
            top:50px;
            position: fixed;
            width: 100%;
            z-index:777;
            float: right;
        }

    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $(document).bind("contextmenu",function(e){
                return false;
            });
        });
    </script>

</head>

<body>
<div class='container-fluid'>
    <div data-spy="affix" data-offset-top="50">
        <div class="header_for_fix" style="float: right;" >
            <button class="btn btn-secondary btn-sm" onclick="openFullscreen();">Tela Cheia</button>
        </div>
    </div>


    <div id="container" align="center" />

    @for($i=1;$i<=$livro->qtd_paginas_livro;$i++)
        <img src="{{config('app.cdn')}}/storage/livrodigital/{{$livro->id}}/{{$i}}.webp"/><br>
    @endfor

</div>
<script>
    var elem = document.documentElement;
    function openFullscreen() {
        if (elem.requestFullscreen) {
            elem.requestFullscreen();
        } else if (elem.mozRequestFullScreen) { /* Firefox */
            elem.mozRequestFullScreen();
        } else if (elem.webkitRequestFullscreen) { /* Chrome, Safari & Opera */
            elem.webkitRequestFullscreen();
        } else if (elem.msRequestFullscreen) { /* IE/Edge */
            elem.msRequestFullscreen();
        }
    }


    $(document).ready(function(){
        // bind and scroll header div
        $(window).bind('resize', function(e){
            $(".affix").css('width',$(".container-fluid" ).width());
        });
        $(window).on("scroll", function() {
            $(".affix").css('width',$(".container-fluid" ).width());
        });
        $(".affix").css('width',$(".container-fluid" ).width());
    });
</script>

</body>


</html>
