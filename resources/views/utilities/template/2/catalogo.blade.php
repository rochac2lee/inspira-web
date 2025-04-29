@extends('layouts.master')

@section('title', 'Catálogo')

@section('headend')

<link rel="stylesheet" href="{{ config('app.cdn') }}/assets/css/custom-carousel.css">

<!-- CONFIG. TEMPLATE -->
@if(isset($plataforma->template_id))
    <link rel="stylesheet" href="{{ config('app.cdn') }}/assets/template/{{$plataforma->template_id}}/css/style.css">
@endif

<style>
    body {
        margin-top: 0!important;
    }
    main {
        margin-top: 28px!important;
    }
   
</style>

 @if(!Auth::check())
    <style>
        main {
            margin-top: -12px!important;
        }
    </style>
@endif

@endsection

@section('content')

@if(!Auth::check())
    @include('utilities.template.2.catalogo-content')
@endif

@if(Auth::check())
@if(strtoupper(Auth::user()->permissao) === "A")
    {{-- CATALOGO DIFERENCIADO PARA PLAY --}}
   
    @include('catalogo')

    {{-- END - CATALOGO DIFERENCIADO PARA PLAY --}}
@endif
@endif

@if(Auth::check())
@if(strtoupper(Auth::user()->permissao) !== "A")
    {{-- CATALOGO DIFERENCIADO PARA OUTROS NIVEIS DE ACESSO --}}

    {{-- NOVO CATALOGO --}}
    @include('utilities.template.2.catalogo-content')
    
    {{-- END - CATALOGO DIFERENCIADO PARA OUTROS NIVEIS DE ACESSO --}}
@endif
@endif


@endsection

@section('bodyend')
<script src="{{ asset('/assets/js/script.js') }}"></script>
<script>
    var numbers = new Carousel("numbers");
    var numbersHome = new Carouselhome("numbersHome");
</script>

<!--Plugin Initialization-->
<script type="text/javascript">
    $(document).ready(function () {
        $("#respMenu").aceResponsiveMenu({
            resizeWidth: '768', // Set the same in Media query       
            animationSpeed: 'fast', //slow, medium, fast
            accoridonExpAll: false //Expands all the accordion menu on click
        });
    });
    /* 
    Responsive Menu
    ----------------------------------------*/
    (function ($) {
    $.fn.aceResponsiveMenu = function (options) {

        //plugin's default options
        var defaults = {
            resizeWidth: '768',
            animationSpeed: 'fast',
            accoridonExpAll: false
        };
        //Variables
        var options = $.extend(defaults, options),
            opt = options,
            $resizeWidth = opt.resizeWidth,
            $animationSpeed = opt.animationSpeed,
            $expandAll = opt.accoridonExpAll,
            $aceMenu = $(this),
            $menuStyle = $(this).attr('data-menu-style');

        // Initilizing        
        $aceMenu.find('ul').addClass("sub-menu");
        $aceMenu.find('ul').siblings('a').append('<span class="arrow "></span>');
        if ($menuStyle == 'accordion') { $(this).addClass('collapse'); }

        // Window resize on menu breakpoint 
        if ($(window).innerWidth() <= $resizeWidth) {
            menuCollapse();
        }
        $(window).resize(function () {
            menuCollapse();
        });

        // Menu Toggle
        function menuCollapse() {
            var w = $(window).innerWidth();
            if (w <= $resizeWidth) {
                $aceMenu.find('li.menu-active').removeClass('menu-active');
                $aceMenu.find('ul.slide').removeClass('slide').removeAttr('style');
                $aceMenu.addClass('collapse hide-menu');
                $aceMenu.attr('data-menu-style', '');
                $('.menu-toggle').show();
            } else {
                $aceMenu.attr('data-menu-style', $menuStyle);
                $aceMenu.removeClass('collapse hide-menu').removeAttr('style');
                $('.menu-toggle').hide();
                if ($aceMenu.attr('data-menu-style') == 'accordion') {
                    $aceMenu.addClass('collapse');
                    return;
                }
                $aceMenu.find('li.menu-active').removeClass('menu-active');
                $aceMenu.find('ul.slide').removeClass('slide').removeAttr('style');
            }
        }
        //ToggleBtn Click
        $('#menu-btn').click(function () {
            $aceMenu.slideToggle().toggleClass('hide-menu');
        });
        // Main function 
        return this.each(function () {
            // Function for Horizontal menu on mouseenter
            $aceMenu.on('mouseover', '> li a', function () {
                if ($aceMenu.hasClass('collapse') === true) {
                    return false;
                }
                $(this).off('click', '> li a');
                $(this).parent('li').siblings().children('.sub-menu').stop(true, true).slideUp($animationSpeed).removeClass('slide').removeAttr('style').stop();
                $(this).parent().addClass('menu-active').children('.sub-menu').slideDown($animationSpeed).addClass('slide');
                return;
            });
            $aceMenu.on('mouseleave', 'li', function () {
                if ($aceMenu.hasClass('collapse') === true) {
                    return false;
                }
                $(this).off('click', '> li a');
                $(this).removeClass('menu-active');
                $(this).children('ul.sub-menu').stop(true, true).slideUp($animationSpeed).removeClass('slide').removeAttr('style');
                return;
            });
            //End of Horizontal menu function

            // Function for Vertical/Responsive Menu on mouse click
            $aceMenu.on('click', '> li a', function () {
                if ($aceMenu.hasClass('collapse') === false) {
                    //return false;
                }
                $(this).off('mouseover', '> li a');
                if ($(this).parent().hasClass('menu-active')) {
                    $(this).parent().children('.sub-menu').slideUp().removeClass('slide');
                    $(this).parent().removeClass('menu-active');
                } else {
                    if ($expandAll == true) {
                        $(this).parent().addClass('menu-active').children('.sub-menu').slideDown($animationSpeed).addClass('slide');
                        return;
                    }
                    $(this).parent().siblings().removeClass('menu-active');
                    $(this).parent('li').siblings().children('.sub-menu').slideUp().removeClass('slide');
                    $(this).parent().addClass('menu-active').children('.sub-menu').slideDown($animationSpeed).addClass('slide');
                }
            });
            //End of responsive menu function
        });
        //End of Main function
    }
    })(jQuery);
</script>

<script>
    const siemas = document.querySelectorAll('.siema');
        // this is fairly new way of looping through DOM Elements
        // More about ithere: https://pawelgrzybek.com/loop-through-a-collection-of-dom-elements/
        // For better compatibility I suggest using for loop
        for(const siema of siemas) {
        new Siema({
            selector: siema
        })
    }
</script>
<script>
    $( document ).ready(function(){
        $("#ordem").change(function()
        {
            document.location.href = this.value;
        });
    });
</script>
@endsection
