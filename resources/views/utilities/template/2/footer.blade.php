<style>
    /* FOOTER */
    footer.footer-template-2 {
        background-color: #1d2345 !important;
        color: #fff;
        margin-top: 50px;
        position: relative;
        min-height: 75px;
    }
    footer.footer-template-2 .itens{
        min-height: 75px;
        color: #fff!important;

    }
    .sidebar + main + footer.footer-template-2,
    footer.footer-template-2{
        padding-left:120px!important;
        padding-right:120px!important;
    }
    footer.footer-template-2 .itens .school-name {
        color: #fff!important;
        font-size: 20px;
        margin-bottom: 0!important;
        padding-left:25px;
        padding-right:80px;
    }
    footer.footer-template-2 .itens .social ul li {
        background-color: #e65a24;
        color: #fff!important;
        width: 45px;
        margin-right: 15px;
        padding: 5px;
        border-radius: 5px;
        margin-top: -20px;
    }
    footer.footer-template-2 .itens .redes-sociais ul{
        padding: 0;
        list-style: none;
        display: flex!important;
    }
    footer.footer-template-2 .itens .redes-sociais ul li{
        margin-right: 15px;
        width: 40px!important;
        height: 40px!important;
        float: left!important;
    }
    footer.footer-template-2 .itens .redes-sociais ul li a{
        width: 40px;
        height: 40px;
        display: block;
        text-indent: -99999px;
        overflow: hidden;
        background: {{ config('app.cdn') }}/old/images/icons-social-footer.png no-repeat;
    }
    footer.footer-template-2 .itens .redes-sociais ul li+li a{
        background-position-x: -56px;
    }
    footer.footer-template-2 .itens .redes-sociais ul li+li+li a{
        background-position-x: -112px;
    }
    footer.footer-template-2 .itens .redes-sociais ul li+li+li+li a{
        background-position-x: -168px;
    }
    footer.footer-template-2 .itens .redes-sociais ul li a:hover{
        opacity: 0.7;
    }
    footer.footer-template-2 ul {
        margin: 0;
        padding: 0;
    }

    footer.footer-template-2 ul li {
        margin: 0;
        padding: 0;
        list-style: none;

    }

    footer.footer-template-2 .links {
        text-align: center;
        padding: 20px 0;
    }
    footer.footer-template-2 .links a{
        color: #fff!important;
    }

    @media screen and (max-width: 1024px) {
        footer.footer-template-2 .footer{
            width: 100%!important;
        }
    }
</style>
@php
    $escola = session('escola');
@endphp
<footer class="footer-template-2">

    <div class="footer d-flex justify-content-between">
        <div class="itens d-flex align-items-center">
            <!--<img data-src="{{ config('app.cdn') }}/images/school-img.png" class="lazyload">-->
            <p class="school-name">{{$escola['titulo']}}</p>
            @if($escola['endereco'])
                <ul>
                    <li>{{$escola['endereco']->logradouro}}</li>
                    <li>{{$escola['endereco']->cidade}}/{{$escola['endereco']->estado}}</li>
                </ul>
            @endif
        </div>
        <div class="itens d-flex align-items-center links">
            <a href="#" class="mr-1">Fale conosco</a> •
            <a href="{{url('tutorial')}}" class="mr-1"> Tutoriais</a> •
            <a href="{{ url('/termos-de-uso') }}" class="ml-1">Termos de uso</a>
        </div>
        <div class="itens d-flex align-items-center">
            <div class="redes-sociais">
                <ul class="d-md-flex flex-row">
                    <li><a href="https://www.editoraopet.com.br/" target="_blank" title="Editora Opet">Editora Opet</a></li>
                    <li><a href="https://www.facebook.com/editora.opet.3" target="_blank" title="Acessar Facebook - Editora Opet">Acessar Facebook - Editora Opet</a></li>
                    <li><a href="https://www.instagram.com/explore/locations/493212283/editora-opet" target="_blank" title="Acessar o Instagram - Editora Opet">Acessar o Instagram - Editora Opet</a></li>
                    <li><a href="https://www.youtube.com/channel/UCPXnBBshEViOKyv9EoVKRaQ" target="_blank" title="Acessar YouTube - Editora Opet">Acessar YouTube - Editora Opet</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>
