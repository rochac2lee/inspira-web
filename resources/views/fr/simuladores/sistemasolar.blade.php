<!doctype html>

<head>
  <title></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
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
<script defer src="{{config('app.cdn')}}/fr/sistemasolar_ajuste/main.bundle.js"></script></head>

<body>
  <div class="main-container">
  <div id="ui"></div>
  <div id="scene"></div>
  </div>
  <div vw class="enabled">
    <div vw-access-button class="active"></div>
    <div vw-plugin-wrapper>
      <div class="vw-plugin-top-wrapper"></div>
    </div>
  </div>
  <!-- Acessibilidade -->
  <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
  <script>
    new window.VLibras.Widget('https://vlibras.gov.br/app');
  </script>
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