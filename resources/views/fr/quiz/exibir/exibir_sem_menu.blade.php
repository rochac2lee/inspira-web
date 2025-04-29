<!doctype html>
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
    <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/includes/css/style_v1.css">

</head>
<body class="interna">
    @include('fr/quiz/exibir/exibicao')
</body>
</html>
