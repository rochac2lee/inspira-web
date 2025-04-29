@extends('fr/master')


@section('content')

    <section class="section section-interna">

        <div class="container-fluid termos">

            @if(!isset($resposta) || $resposta == 1 )
                {{$resposta = ''}}

            @else
                <td>
                    <div class="alert alert-danger" role="alert">
                        {{$resposta}}
                    </div>
                </td>
            @endif


                <div class="container">
                    <h2 class="title-page">
                        TERMOS DE USO DA PLATAFORMA INSPIRA
                    </h2>
                    <div class="row section-grid colecoes text-justify" style="line-height:1.5">

                        {!!$plataforma->termo!!}

                    </div>
                </div>



            <div class="row pb-5 justify-content-center">
                <form method="POST" action="{{ url('aceitar') }}">
                    @csrf
                    <div class="form-row align-items-center">
                        <div class="col-auto my-1">
                            <div class="custom-control custom-checkbox mr-sm-2">
                                <input type="checkbox" class="custom-control-input" id="aceito" name="aceito" value="1">
                                <label class="custom-control-label" for="aceito">Declaro que <b>Li</b> e <b>aceito</b> os Termos e Politicas</label>
                            </div>
                        </div>
                        <div class="col-auto my-1">
                            <button type="submit" class="btn btn-primary">Enviar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

@endsection

@section('bodyend')

    <script>
            var config = {
                removePlugins: 'toolbarGroups, FMathEditor,selectall,forms,image,save, newpage, preview, print, templates, pastefromword, pastetext, find, specialchar, smiley, stylescombo, iframe, elementspath, dialogadvtab, clipboard, div, flash, resize, a11yhelp, bidi, sourcearea, enterkey, floatingspace, htmlwriter, indentblock, language, link, indentlist, maximize. magicline, horizontalrule, pagebreak, toolbar, forms '
            }
    </script>

@endsection

