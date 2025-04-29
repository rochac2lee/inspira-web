<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Primary Meta Tags -->
    <title>Tabela Periódica</title>
    <meta name="title" content="Tabela Periódica - Inspira">
    <meta name="description" content="Tabela periódica interativa e educativa para aprendizado - Inspira - Editora Opet">
    <meta name="keywords" content="tabela periodica, inspira, opet, elementos">
    <meta name="author" content="Wagner D`Albuquerque Viana">
    <meta name="robots" content="instruções para os mecanismos de busca, como indexar, seguir, etc.">
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://opetinspira.com.br/tabelaperiodica">
    <meta property="og:title" content="Tabela Periódica - Inspira">
    <meta property="og:description" content="Tabela periódica interativa e educativa para aprendizado - Inspira - Editora Opet"> 

    <!-- Twitter -->    
    <meta property="twitter:url" content="https://opetinspira.com.br/tabelaperiodica">
    <meta property="twitter:title" content="Tabela Periódica - Inspira">
    <meta property="twitter:description" content="Tabela periódica interativa e educativa para aprendizado - Inspira - Editora Opet">    
     
    <!-- Favicon -->
    <!--
    <link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
    <link rel="manifest" href="json/manifest.json">
    -->
  <style>
    body {

      margin: 0;
      width: 100vw;
      position: fixed;
    }
    #scene{
      position: fixed;
      top: 0;
      bottom: 0;
      left: 0;
      right: 0;
      width: 100vw;
      z-index: 0;
      display: block;
    }
    #ui{
      z-index: 100;
      position: fixed;
      width: 100vw;
      height: calc(100vh - calc(100vh - 100%));
      pointer-events: none;
    }
    .main-container{
      position: relative;
      height: calc(100vh - calc(100vh - 100%));
      width: 100vw;
    }

    @media (orientation: landscape) {
      .objectLabel {
        font-size: 1.1vw;
      }
    }
    @media (orientation: portrait) {
      .objectLabel {
        font-size: 1.3vh;
      }
    }
  </style>
<body>
  <iframe src="{{ asset('fr/tabelaperiodica/index.html') }}" style="width: 100%; height: 100vh; border: none;"></iframe>
  
  <!-- USERWAY acessibilidade -->
  <script>
    (function(d){
        var s = d.createElement("script");
        s.setAttribute("data-account", "km7YAnlPrA");
        s.setAttribute("src", "https://accessibilityserver.org/widget.js");
        (d.body || d.head).appendChild(s);
    })
    (document)
  </script>
  <noscript>Please ensure Javascript is enabled for purposes of <a href="https://accessibilityserver.org"website> accessibility</a></noscript>
</body>