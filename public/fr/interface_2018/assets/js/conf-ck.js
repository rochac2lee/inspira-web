< textarea name = "conteudoEntregavel"
id = "txtConteudoEntregavel"
style = "width:100%;font:Arial;" >
    <
    /textarea> <
    script >
    var textarea = document.getElementById('txtConteudoEntregavel');
sceditor.create(textarea, {
    format: 'bbcode',
    icons: 'null',
    plugins: 'undo',
    autofocus: true,
    resizeWidth: false,
    resizeHeight: true,
    height: '200',
    resizeMaxHeight: '300',
    toolbar: "bold,italic,underline,bulletlist,orderedlist|left,center,right,justify|font,size,color|table,image,youtube|link,unlink,horizontalrule",
    colors: '#008CC8,#000000,#FFA500|#ff0000,#555555,#00ff00',
    fonts: 'Arial,Arial Black,Comic Sans MS,Courier New,Georgia,Impact,Sans-serif,Serif,Times New Roman,Trebuchet MS,Verdana',
});
sceditor.instance(textarea).val('<p style="font-family:Arial;font-size:14px;">Utilize esta área para passar as <strong>instruções necessárias</strong> ao seu aluno sobre o que ele deverá lhe enviar.</p>', false); <
/script>



<
link href = "https://unpkg.com/multiple-select@1.4.0/dist/multiple-select.css"
rel = "stylesheet" >
    <
    script src = "https://code.jquery.com/jquery-3.4.1.min.js" > < /script> <
    script src = "https://unpkg.com/multiple-select@1.4.0/dist/multiple-select.js" > < /script>


<
div class = "accordion"
id = "accordionCategorias" >

    <!-- <div class="label-categorias">
    <
    div class = "panel-title"
id = "disciplinas"
data - toggle = "collapse"
data - target = "#disciplinasOne"
aria - expanded = "true"
aria - controls = "disciplinasOne" >
    <
    span > Disciplinas < /span> <
    /div> <
    div id = "disciplinasOne"
class = "collapse"
aria - labelledby = "disciplinas"
data - parent = "#accordionCategorias" >
    <
    div class = "card-body" >
    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid.3 wolf moon officia aute, non alemanha 0 x 2 coreia do sul cupidatat skateboard dolor brunch.Food truck quinoa nesciunt laborum eiusmod.Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single - origin coffee nulla assumenda shoreditch et.Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident.Ad vegan excepteur butcher vice lomo.Leggings occaecat craft beer farm - to - table, raw denim aesthetic synth nesciunt you probably haven 't heard of them accusamus labore sustainable VHS. <
        /div> <
        /div> <
        /div>

    <
    div class = "label-categorias" >
    <
    div class = "panel-title"
id = "ensinoSeries"
data - toggle = "collapse"
data - target = "#ensinoSeriesOne"
aria - expanded = "true"
aria - controls = "ensinoSeriesOne" >
    <
    span > Ensino e séries < /span> <
    /div> <
    div id = "ensinoSeriesOne"
class = "collapse"
aria - labelledby = "ensinoSeries"
data - parent = "#accordionCategorias" >
    <
    div class = "card-body" >
    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid.3 wolf moon officia aute, non alemanha 0 x 2 coreia do sul cupidatat skateboard dolor brunch.Food truck quinoa nesciunt laborum eiusmod.Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single - origin coffee nulla assumenda shoreditch et.Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident.Ad vegan excepteur butcher vice lomo.Leggings occaecat craft beer farm - to - table, raw denim aesthetic synth nesciunt you probably haven 't heard of them accusamus labore sustainable VHS. <
        /div> <
        /div> <
        /div>

    <
    div class = "label-categorias" >
    <
    div class = "panel-title"
id = "outros"
data - toggle = "collapse"
data - target = "#outrosOne"
aria - expanded = "true"
aria - controls = "outrosOne" >
    <
    span > Outros < /span> <
    /div> <
    div id = "outrosOne"
class = "collapse"
aria - labelledby = "outros"
data - parent = "#accordionCategorias" >
    <
    div class = "card-body" >
    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid.3 wolf moon officia aute, non alemanha 0 x 2 coreia do sul cupidatat skateboard dolor brunch.Food truck quinoa nesciunt laborum eiusmod.Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single - origin coffee nulla assumenda shoreditch et.Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident.Ad vegan excepteur butcher vice lomo.Leggings occaecat craft beer farm - to - table, raw denim aesthetic synth nesciunt you probably haven 't heard of them accusamus labore sustainable VHS. <
        /div> <
        /div> <
        /div> -->

    <
    /div>



    <!-- <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    <
    div class = "panel panel-default" >
    <
    div class = "panel-heading"
role = "tab"
id = "headingOne" >
    <
    h4 class = "panel-title" >
    <
    a data - toggle = "collapse"
data - parent = "#accordion"
href = "#collapseOne"
aria - expanded = "true"
aria - controls = "collapseOne" >
    Collapsible Group Item #1

        </a>

      </h4>



        </div>

        <div id= "collapseOne"
class = "panel-collapse collapse in"
role = "tabpanel"
aria - labelledby = "headingOne" >
    <
    div class = "panel-body" > Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid.3 wolf moon officia aute, non cupidatat skateboard dolor brunch.Food truck quinoa nesciunt laborum eiusmod.Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single - origin coffee nulla assumenda shoreditch et.Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident.Ad vegan excepteur butcher vice lomo.Leggings occaecat craft beer farm - to - table, raw denim aesthetic synth nesciunt you probably haven 't heard of them accusamus labore sustainable VHS.</div> <
    /div> <
    /div> <
    /div> -->




@extends('layouts.master')

@section('title', 'Gestão de Albuns')

@section('headend')

<!-- Custom styles for this template -->
<
style >

    header {
        padding: 154 px 0 100 px;
    }

@media(min - width: 992 px) {
    header {
        padding: 156 px 0 100 px;
    }
}

.capa - curso {
        min - height: 160 px;
        border - radius: 10 px 10 px 0 px 0 px;
        background - image: url('{{ config('
            app.local ') }}/images/default-cover.jpg');
        background - size: cover;
        background - position: 50 % 50 % ;
        background - repeat: no - repeat;
    }
    .input - group input.text - secondary::placeholder {
        color: #989EB4;

        }

        .audiosAddInline {

            max-height: 200px !important;

        }

        .mainAlbumsAdminIndex .modal # file - name {
                margin: 135 px 0 20 px 0 px;
                font - size: 12 px;
                position: relative;
                top: 10 px;
                width: 100 % ;
                text - align: center;
            }
            .mainAlbumsAdminIndex.modal.form - group - descricao - album { margin - top: 5 px; }


            <
            /style>

            @endsection

            @section('content')

            <
            link rel = "stylesheet"
        href = "https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css" >

        <
        main role = "main"
        class = "mainAlbumsAdminIndex" >

        @include('pages.albums.admin._create') <
        div class = "container-fluid cast-index" >
        <
        div id = "divMainMenu"
        class = "col-12 mt-2"
        style = "width: calc(100% - 1px); flex: inherit; transition: 0.3s all ease-in-out;" >
        <
        div class = "row cast-header" >
        <
        div class = "offset-md-1 col-6" >
        <
        h2 > Cast Creator < /h2> <
        /div>
        @include('pages.albums.admin._menu', ["albumActive" => 1]) <
        /div>

        <
        div class = "col-12 offset-md-1 col-md-10 px-1" >

        <
        div class = "row mx-auto mt-3 pt-3 mb-4" >

        <
        div class = "col-12 col-sm-9 pl-0 pr-0 pr-sm-2 mb-3 mb-sm-0" >
        @include('components.search-input', ['placeholder' => 'Digite o nome do álbum que está procurando']) <
        /div> <
        div class = "col-12 col-sm-3 my-auto pl-0 pl-sm-2 pr-0" >
        <
        button type = "button"
        class = "btn btn-block btn-master box-shadow text-white text-uppercase font-weight-bold py-3 text-wrap"
        data - toggle = "modal"
        data - target = "#modalNewAlbum" >
        <
        i class = "fas fa-plus mr-1" > < /i>
        Novo álbum <
        /button>

        <
        div class = "modal fade modal-cast"
        id = "modalNewAlbum"
        tabindex = "-1"
        role = "dialog"
        data - keyboard = "false"
        data - backdrop = "static"
        aria - labelledby = "modalNewAlbumLabel"
        aria - hidden = "true" >
        <
        div class = "modal-dialog"
        role = "document" >
        <
        div class = "modal-content" >

        <
        button type = "button"
        class = "close close-modal"
        data - dismiss = "modal"
        aria - label = "Fechar" >
        <
        span aria - hidden = "true" > & times; < /span> <
        /button>

        <
        div class = "row" >
        <
        div class = "boxs box-biblioteca py-3 px-4" >

        <!-- NOVO ÁUDIO -->
        <
        div class = "content-novo-audio" >
        <
        h2 > Novo áudio < /h2>

        <
        form id = ""
        class = "w-100"
        method = "post" >

        <
        div class = "form-group" >
        <
        input type = "text"
        class = "form-control"
        id = ""
        placeholder = "Título do áudio"
        maxlength = "50" >
        <
        /div> <
        div class = "form-group" >
        <
        textarea class = "form-control font-weight-normal"
        id = ""
        rows = "3"
        placeholder = "Descrição breve do álbum"
        maxlength = "150" > < /textarea> <
        /div>

        <
        label
        for = "file"
        id = "divFileInputCapaCreate"
        tag - index = "create"
        class = "file-input-area input-capa w-100 text-left" >
        <
        input type = "file"
        class = "custom-file-input"
        id = "file"
        name = "file"
        required = ""
        style = "top: 0; height:  100%;position: absolute;left:  0;"
        accept = "audio/mp3, audio/wav" >

        <
        h6 id = "placeholder" >
        <
        div class = "row" >
        <
        div class = "col-4 d-flex justify-content-end align-items-center" >
        <
        i class = "fas fa-podcast fa-2x" > < /i> <
        /div> <
        div class = "col-8 audio-input-desc" >
        Arquivo de áudio <
        small class = "d-block small mt-2"
        style = "font-size:  70%;" >
        Clique aqui ou < br > arraste para esta área <
        /small> <
        /div> <
        /div> <
        /h6> <
        /label>

        <
        div class = "form-group" >
        <
        div class = "showAudio_create" > < /div> <
        /div>

        <
        div class = "form-group" >
        <
        h6 class = "font-weight-bold"
        for = "categoria" > Categoria < /h6>

        <
        div class = "accordion-categoria"
        id = "accordionCategoria" >
        <
        div class = "d-block pr-4" >
        <
        div class = "panel-title"
        id = "headingOne" >
        <
        div class = "btn-link collapsed"
        data - toggle = "collapse"
        data - target = "#collapseOne"
        aria - expanded = "true"
        aria - controls = "collapseOne" >
        Disciplinas <
        /div> <
        /div> <
        div id = "collapseOne"
        class = "collapse"
        aria - labelledby = "headingOne"
        data - parent = "#accordionCategoria"
        style = "margin-left:15px;" >
        <
        div class = "form-group form-check"
        style = "margin-bottom:5px!important;" >
        <
        input type = "checkbox"
        class = "form-check-input"
        id = "Artes"
        style = "width:16px;height:16px;" >
        <
        label class = "form-check-label"
        for = "Artes" > Artes < /label> <
        /div> <
        div class = "form-group form-check"
        style = "margin-bottom:5px!important;" >
        <
        input type = "checkbox"
        class = "form-check-input"
        id = "Biologia"
        style = "width:16px;height:16px;" >
        <
        label class = "form-check-label"
        for = "Biologia" > Biologia < /label> <
        /div> <
        div class = "form-group form-check"
        style = "margin-bottom:5px!important;" >
        <
        input type = "checkbox"
        class = "form-check-input"
        id = "Geografia"
        style = "width:16px;height:16px;" >
        <
        label class = "form-check-label"
        for = "Geografia" > Geografia < /label> <
        /div> <
        div class = "form-group form-check"
        style = "margin-bottom:5px!important;" >
        <
        input type = "checkbox"
        class = "form-check-input"
        id = "História"
        style = "width:16px;height:16px;" >
        <
        label class = "form-check-label"
        for = "História" > História < /label> <
        /div> <
        div class = "form-group form-check"
        style = "margin-bottom:5px!important;" >
        <
        input type = "checkbox"
        class = "form-check-input"
        id = "Inglês"
        style = "width:16px;height:16px;" >
        <
        label class = "form-check-label"
        for = "Inglês" > Inglês < /label> <
        /div> <
        div class = "form-group form-check"
        style = "margin-bottom:5px!important;" >
        <
        input type = "checkbox"
        class = "form-check-input"
        id = "Matemática"
        style = "width:16px;height:16px;" >
        <
        label class = "form-check-label"
        for = "Matemática" > Matemática < /label> <
        /div> <
        div class = "form-group form-check"
        style = "margin-bottom:5px!important;" >
        <
        input type = "checkbox"
        class = "form-check-input"
        id = "Português"
        style = "width:16px;height:16px;" >
        <
        label class = "form-check-label"
        for = "Português" > Português < /label> <
        /div> <
        /div> <
        /div>

        <
        div class = "d-block pr-4" >
        <
        div class = "panel-title"
        id = "headingTwo" >
        <
        div class = "btn-link collapsed"
        data - toggle = "collapse"
        data - target = "#collapseTwo"
        aria - expanded = "false"
        aria - controls = "collapseTwo" >
        Ensino e séries <
        /div> <
        /div> <
        div id = "collapseTwo"
        class = "collapse"
        aria - labelledby = "headingTwo"
        data - parent = "#accordionCategoria"
        style = "margin-left:15px;" >
        <
        div class = "form-group form-check"
        style = "margin-bottom:5px!important;" >
        <
        input type = "checkbox"
        class = "form-check-input"
        id = "Artes"
        style = "width:16px;height:16px;" >
        <
        label class = "form-check-label"
        for = "Artes" > Artes < /label> <
        /div> <
        div class = "form-group form-check"
        style = "margin-bottom:5px!important;" >
        <
        input type = "checkbox"
        class = "form-check-input"
        id = "Biologia"
        style = "width:16px;height:16px;" >
        <
        label class = "form-check-label"
        for = "Biologia" > Biologia < /label> <
        /div> <
        div class = "form-group form-check"
        style = "margin-bottom:5px!important;" >
        <
        input type = "checkbox"
        class = "form-check-input"
        id = "Geografia"
        style = "width:16px;height:16px;" >
        <
        label class = "form-check-label"
        for = "Geografia" > Geografia < /label> <
        /div> <
        div class = "form-group form-check"
        style = "margin-bottom:5px!important;" >
        <
        input type = "checkbox"
        class = "form-check-input"
        id = "História"
        style = "width:16px;height:16px;" >
        <
        label class = "form-check-label"
        for = "História" > História < /label> <
        /div> <
        div class = "form-group form-check"
        style = "margin-bottom:5px!important;" >
        <
        input type = "checkbox"
        class = "form-check-input"
        id = "Inglês"
        style = "width:16px;height:16px;" >
        <
        label class = "form-check-label"
        for = "Inglês" > Inglês < /label> <
        /div> <
        div class = "form-group form-check"
        style = "margin-bottom:5px!important;" >
        <
        input type = "checkbox"
        class = "form-check-input"
        id = "Matemática"
        style = "width:16px;height:16px;" >
        <
        label class = "form-check-label"
        for = "Matemática" > Matemática < /label> <
        /div> <
        div class = "form-group form-check"
        style = "margin-bottom:5px!important;" >
        <
        input type = "checkbox"
        class = "form-check-input"
        id = "Português"
        style = "width:16px;height:16px;" >
        <
        label class = "form-check-label"
        for = "Português" > Português < /label> <
        /div> <
        /div> <
        /div>

        <
        div class = "d-block pr-4" >
        <
        div class = "panel-title"
        id = "headingThree" >
        <
        div class = "btn-link collapsed"
        data - toggle = "collapse"
        data - target = "#collapseThree"
        aria - expanded = "false"
        aria - controls = "collapseThree" >
        Outros <
        /div> <
        /div> <
        div id = "collapseThree"
        class = "collapse"
        aria - labelledby = "headingThree"
        data - parent = "#accordionCategoria"
        style = "margin-left:15px;" >
        <
        div class = "form-group form-check"
        style = "margin-bottom:5px!important;" >
        <
        input type = "checkbox"
        class = "form-check-input"
        id = "Artes"
        style = "width:16px;height:16px;" >
        <
        label class = "form-check-label"
        for = "Artes" > Artes < /label> <
        /div> <
        div class = "form-group form-check"
        style = "margin-bottom:5px!important;" >
        <
        input type = "checkbox"
        class = "form-check-input"
        id = "Biologia"
        style = "width:16px;height:16px;" >
        <
        label class = "form-check-label"
        for = "Biologia" > Biologia < /label> <
        /div> <
        div class = "form-group form-check"
        style = "margin-bottom:5px!important;" >
        <
        input type = "checkbox"
        class = "form-check-input"
        id = "Geografia"
        style = "width:16px;height:16px;" >
        <
        label class = "form-check-label"
        for = "Geografia" > Geografia < /label> <
        /div> <
        div class = "form-group form-check"
        style = "margin-bottom:5px!important;" >
        <
        input type = "checkbox"
        class = "form-check-input"
        id = "História"
        style = "width:16px;height:16px;" >
        <
        label class = "form-check-label"
        for = "História" > História < /label> <
        /div> <
        div class = "form-group form-check"
        style = "margin-bottom:5px!important;" >
        <
        input type = "checkbox"
        class = "form-check-input"
        id = "Inglês"
        style = "width:16px;height:16px;" >
        <
        label class = "form-check-label"
        for = "Inglês" > Inglês < /label> <
        /div> <
        div class = "form-group form-check"
        style = "margin-bottom:5px!important;" >
        <
        input type = "checkbox"
        class = "form-check-input"
        id = "Matemática"
        style = "width:16px;height:16px;" >
        <
        label class = "form-check-label"
        for = "Matemática" > Matemática < /label> <
        /div> <
        div class = "form-group form-check"
        style = "margin-bottom:5px!important;" >
        <
        input type = "checkbox"
        class = "form-check-input"
        id = "Português"
        style = "width:16px;height:16px;" >
        <
        label class = "form-check-label"
        for = "Português" > Português < /label> <
        /div>     <
        /div> <
        /div> <
        /div> <
        /div>

        <
        div class = "row border-top" >
        <
        div class = "col-12 d-flex justify-content-between p-0 mt-4 mb-0" >
        <
        button type = ""
        id = "btn-cancelar"
        class = "btn btn-cancelar float-left" > CANCELAR < /button> <
        button type = ""
        class = "btn btn-adicionar float-right" > ADICIONAR < /button> <
        /div>  <
        /div> <
        /form> <
        /div>
        <!-- fim NOVO ÁUDIO -->

        <
        h2 > Biblioteca < /h2>

        <
        div class = "input-group search-div-input" >
        <
        input name = "pesquisa"
        type = "text"
        id = "searchaudio"
        class = "form-control search-input"
        placeholder = "Digite o nome do áudio que está procurando"
        aria - label = "Recipient's username"
        aria - describedby = "button-addon2"
        maxlength = "50" >
        <
        div class = "input-group-append search-div-icon" >
        <
        div class = "bg-white d-flex align-items-center"
        id = "" >
        <
        span class = "fa-stack fa-1x mr-3 search-icon-background" >
        <
        i class = "fas fa-circle fa-stack-2x" > < /i> <
        i class = "fas fa-search fa-stack-1x search-icon" > < /i> <
        /span> <
        /div> <
        /div> <
        /div>

        <
        div class = "content-audio" >
        <
        div class = "card shadow-none" >
        <
        div class = "card-body" >
        <
        i class = "fas fa-plus btn btn-light btn-add-audio"
        id = "" > < /i> <
        h5 class = "card-title" > Título do áudio < /h5> <
            p class = "card-text" > Descrição breve do áudio < /p> <
                audio controls = ""
            preload >
            <
            source src = "/uploads/audios/user_id_1/70f5a4a3e29ebb0b5cf267d03a2350d5.mp3"
        type = "audio/mpeg" >
        Seu navegador não suporta áudio em HTML5,
        atualize - o. <
        /audio> <
        /div> <
        /div> <
        div class = "card shadow-none" >
        <
        div class = "card-body" >
        <
        i class = "fas fa-plus btn btn-light btn-add-audio"
        id = "" > < /i> <
        h5 class = "card-title" > Título do áudio < /h5> <
            p class = "card-text" > Descrição breve do áudio < /p> <
                audio controls = ""
            preload >
            <
            source src = "/uploads/audios/user_id_1/70f5a4a3e29ebb0b5cf267d03a2350d5.mp3"
        type = "audio/mpeg" >
        Seu navegador não suporta áudio em HTML5,
        atualize - o. <
        /audio> <
        /div> <
        /div> <
        div class = "card shadow-none" >
        <
        div class = "card-body" >
        <
        i class = "fas fa-plus btn btn-light btn-add-audio"
        id = "" > < /i> <
        h5 class = "card-title" > Título do áudio < /h5> <
            p class = "card-text" > Descrição breve do áudio < /p> <
                audio controls = ""
            preload >
            <
            source src = "/uploads/audios/user_id_1/70f5a4a3e29ebb0b5cf267d03a2350d5.mp3"
        type = "audio/mpeg" >
        Seu navegador não suporta áudio em HTML5,
        atualize - o. <
        /audio> <
        /div> <
        /div> <
        div class = "card shadow-none" >
        <
        div class = "card-body" >
        <
        i class = "fas fa-plus btn btn-light btn-add-audio"
        id = "" > < /i> <
        h5 class = "card-title" > Título do áudio < /h5> <
            p class = "card-text" > Descrição breve do áudio < /p> <
                audio controls = ""
            preload >
            <
            source src = "/uploads/audios/user_id_1/70f5a4a3e29ebb0b5cf267d03a2350d5.mp3"
        type = "audio/mpeg" >
        Seu navegador não suporta áudio em HTML5,
        atualize - o. <
        /audio> <
        /div> <
        /div> <
        div class = "card shadow-none" >
        <
        div class = "card-body" >
        <
        i class = "fas fa-plus btn btn-light btn-add-audio"
        id = "" > < /i> <
        h5 class = "card-title" > Título do áudio < /h5> <
            p class = "card-text" > Descrição breve do áudio < /p> <
                audio controls = ""
            preload >
            <
            source src = "/uploads/audios/user_id_1/70f5a4a3e29ebb0b5cf267d03a2350d5.mp3"
        type = "audio/mpeg" >
        Seu navegador não suporta áudio em HTML5,
        atualize - o. <
        /audio> <
        /div> <
        /div> <
        div class = "card shadow-none" >
        <
        div class = "card-body" >
        <
        i class = "fas fa-plus btn btn-light btn-add-audio"
        id = "" > < /i> <
        h5 class = "card-title" > Título do áudio < /h5> <
            p class = "card-text" > Descrição breve do áudio < /p> <
                audio controls = ""
            preload >
            <
            source src = "/uploads/audios/user_id_1/70f5a4a3e29ebb0b5cf267d03a2350d5.mp3"
        type = "audio/mpeg" >
        Seu navegador não suporta áudio em HTML5,
        atualize - o. <
        /audio> <
        /div> <
        /div> <
        /div> <
        button type = "button"
        id = "btn-novo-audio"
        class = "btn w-100 btn-novo-audio btn-outline-primary" > NOVO ÁUDIO < /button>

        <
        /div> <!--box-bliblioteca-->


        <
        div class = "boxs box-new-album py-3 px-4" >
        <
        div class = "w-100" >
        <
        h2 class = "w-100" > Novo álbum < /h2> <
        div class = "info-album" >
        <
        form action = "" >
        <
        div class = "form-group" >
        <
        input type = "text"
        class = "form-control"
        id = ""
        placeholder = "Título do álbum"
        maxlength = "50" >
        <
        /div> <
        div class = "form-group" >
        <
        textarea class = "form-control font-weight-normal"
        id = ""
        rows = "3"
        placeholder = "Descrição breve do álbum"
        maxlength = "150" > < /textarea> <
        /div> <
        h6 > Categorias < /h6> <
        /form>

        <
        select class = "selectpicker col-12 px-0 show-menu-arrow"
        multiple data - actions - box = "true"
        multiple multiple data - selected - text - format = "count > 3" >
        <
        option data - content = "<span class='badge' style='background-color:#6646DB!important;padding:.4em .9em;color:#fff;border-radius:10px;'>Matemática</span>" > < /option> <
        option data - content = "<span class='badge' style='background-color:#6646DB!important;padding:.4em .9em;color:#fff;border-radius:10px;'>Português</span>" > < /option> <
        option data - content = "<span class='badge' style='background-color:#6646DB!important;padding:.4em .9em;color:#fff;border-radius:10px;'>vestibular</span>" > < /option> <
        option data - content = "<span class='badge' style='background-color:#6646DB!important;padding:.4em .9em;color:#fff;border-radius:10px;'>vestibular 1</span>" > < /option> <
        option data - content = "<span class='badge' style='background-color:#6646DB!important;padding:.4em .9em;color:#fff;border-radius:10px;'>vestibular 2</span>" > < /option> <
        option data - content = "<span class='badge' style='background-color:#6646DB!important;padding:.4em .9em;color:#fff;border-radius:10px;'>vestibular 3</span>" > < /option> <
        option data - content = "<span class='badge' style='background-color:#6646DB!important;padding:.4em .9em;color:#fff;border-radius:10px;'>vestibular 4</span>" > < /option> <
        /select> <
        /div> <
        div class = "foto-album" >
        <
        img src = "http://lorempixel.com/g/260/260/"
        alt = "" >
        <
        /div> <
        /div>

        <
        div class = "w-100 inline-block mt-4"
        style = "display:inline-block;" >
        <
        h5 class = "w-100 d-block" > Faixas do álbum < /h5> <
            div class = "box-faixas-album" >
            <
            div class = "card shadow-none" >
            <
            div class = "card-body" >
            <
            i class = "fas fa-minus btn btn-light btn-add-audio"
        id = "" > < /i> <
        h5 class = "card-title" > Título do áudio < /h5> <
            p class = "card-text" > Descrição breve do áudio < /p> <
                audio controls = ""
            preload >
            <
            source src = "/uploads/audios/user_id_1/70f5a4a3e29ebb0b5cf267d03a2350d5.mp3"
        type = "audio/mpeg" >
        Seu navegador não suporta áudio em HTML5,
        atualize - o. <
        /audio> <
        /div> <
        /div> <
        div class = "card shadow-none" >
        <
        div class = "card-body" >
        <
        i class = "fas fa-minus btn btn-light btn-add-audio"
        id = "" > < /i> <
        h5 class = "card-title" > Título do áudio < /h5> <
            p class = "card-text" > Descrição breve do áudio < /p> <
                audio controls = ""
            preload >
            <
            source src = "/uploads/audios/user_id_1/70f5a4a3e29ebb0b5cf267d03a2350d5.mp3"
        type = "audio/mpeg" >
        Seu navegador não suporta áudio em HTML5,
        atualize - o. <
        /audio> <
        /div> <
        /div> <
        /div>

        <
        div class = "w-100 block" >
        <
        button type = ""
        class = "btn btn-cancelar float-left" > CANCELAR < /button> <
        button type = ""
        class = "btn btn-adicionar float-right" > ADICIONAR < /button> <
        /div>

        <
        /div> <
        /div> <
        /div> <
        /div> <
        /div> <
        /div>

        <
        /div>

        @if(Request::has('pesquisa'))
        @include('components.search-response', ['pesquisa' => Request::get('pesquisa')])
        @endif <
        /div>

        <
        /div>

        <
        div class = "col-12 col-md-10 mx-auto py-1" >
        @php
        $letra = '';
        @endphp
        @forelse($albuns as $album)
        <!-- Modal edit album -->
        @include('pages.albums.admin._edit')
        <!-- Lista de Albuns -->
        @if($album - > letra != $letra)
        @if($letra != '') <
        /div>
        @endif <
        div class = "row mb-2" >
        <
        span class = "col-12 text-letra" > {
            { $album - > letra } } < /span> <
        /div> <
        div class = "row mb-3" >
        @php
        $letra = $album - > letra;
        @endphp
        @endif <
        div class = "col-12 col-sm-6 col-md-6 col-lg-4 box-card" >
        <
        div class = "card" >
        <
        div class = "w-100 card-body d-flex align-items-center" >
        <
        div class = "col-4 h-100 rounded-left capa-item"
        style = "background-image: url('{{ env("
        APP_LOCAL ") }}/uploads/albuns/capas/{{ $album->capa }}');" >
        <
        /div>

        <
        div class = "col-5 pt-3 h-100" >
        <
        p class = "pl-2 mb-1 text-dark text-truncate font-weight-bold"
        title = "{{ $album->titulo }}" > {
            { $album - > titulo } } < /p> <
        p class = "pl-2 text-dark text-truncate"
        title = "{{ $album->descricao }}" > {
            { $album - > descricao } } < /p> <
        /div>

        <
        div class = "col-3 btn-group h-100 d-flex justify-content-end align-content-between flex-wrap px-0" >
        <
        div class = "col-12 d-flex justify-content-end pr-0" >
        <
        div id = "btn_drop{{$album->id}}"
        class = "div-group btn-menu-drop-down d-flex align-items-center rounded-left" >
        <
        i class = "fas fa-ellipsis-v align-middle px-1"
        id = "expandirmenu{{$album->id}}"
        onclick = '$("#extendido{{$album->id}} > i").toggle(1000);' > < /i>  <
        /div> <
        div class = "div-group extendido d-flex align-items-center"
        id = "extendido{{$album->id}}" >
        <
        i class = "fas fa-edit px-2 ml-2"
        title = "Editar"
        data - toggle = "modal"
        data - target = "#divModalEditAlbum_{{$album->id}}" > < /i> <
        i class = "fas fa-trash px-2 p-none"
        title = "Excluir"
        onclick = "excluirAlbum({{ $album->id }})" > < /i> <
        form id = "formExcluirAlbum{{ $album->id }}"
        action = "{{ route('gestao.albuns.destroy', ['idAlbum' => $album->id]) }}"
        method = "post" > @csrf < /form> <
        /div> <
        /div>

        <
        div class = "col-12 d-flex justify-content-end pl-0 pr-1" >
        <
        small class = "font-weight-bold text-nowrap" > {
            { count($album - > audios) } }
        músicas < /small> <
        /div> <
        /div> <
        /div> <
        /div> <
        /div>

        <!--Fim lista de Albuns -->

        @empty <
        div class = "col-12 mb-4" >
        <
        div class = "card-curso box-shadow rounded-10 py-3" >
        <
        h5 class = "pl-3" >
        Nenhum álbum encontrado <
        /h5> <
        /div> <
        /div>
        @endforelse <
        /div><!--Fim row  -->   <
        /div><!--Fim col-12  -->   <
        /div><!-- main menu -->
        <!-- Modal novo album -->
        <
        /div><!-- Fim container -->


        <
        /main>

        @endsection

        @section('bodyend')

        <
        script src = "{{config('app.url') }}/assets/js/pages/gestao/albuns.min.js" > < /script>
        <!-- <script src="{{config('app.url') }}/assets/js/select2.min.js"></script>
        <
        link rel = "stylesheet"
        href = "{{config('app.url') }}/assets/css/select2.min.css" > -->

        <
        script >

        // BTN Abrir modal Novo áudio
        $('#btn-novo-audio').click(function() {
            $('.content-novo-audio').addClass('d-block animated bounceInLeft');
            $('.content-novo-audio').removeClass('d-none slideOutLeft')
        });
        // BTN Fechar modal Novo áudio
        $('#btn-cancelar').click(function() {
            $('.content-novo-audio').removeClass('d-none bounceInLeft')
            $('.content-novo-audio').addClass('slideOutLeft')
        });
        $('.modal-cast').on('hidden.bs.modal', function() {
            $('.content-novo-audio').removeClass('d-none d-block bounceInLeft slideOutLeft');
        })

        $(".sidebar").removeClass("show");

        function adicionarAudio(idAudio) {
            var tituloAudio = $('#' + idAudio).attr('data-id');
            $('div.audiosIds').append('<input type="hidden" id="audio_id_' + idAudio + '" name="audio_id[]" value="' + idAudio + '">');
            $('div.audiosAddInline').append('<div class="col-lg-12 col-12 border-bottom mb-3" id="box_audio_id_' + idAudio + '">\n' +
                '<i class="fas fa-times d-flex text-danger removeAudio justify-content-end" id="' + idAudio + '" style="cursor: pointer;"></i>\n' +
                '<p class="font-weight-bold text-primary">\n' +
                '<i class="fas fa-music mr-3"></i> ' + tituloAudio + '\n' +
                '</p>\n' +
                '</div>');

            $('div.removeContainerAudio_' + idAudio).slideUp();

            // remover audio
            $('i.removeAudio').click(function() {
                var idAudioRemove = $(this).attr('id');
                $("input#audio_id_" + idAudioRemove).remove();
                $("div#box_audio_id_" + idAudioRemove).remove();
                $('div.removeContainerAudio_' + idAudioRemove).slideDown();
            });
        }

        // remover audio
        $('i.removeAudio').click(function() {
            var idAudioRemove = $(this).attr('id');
            $("input#audio_id_" + idAudioRemove).remove();
            $("div#box_audio_id_" + idAudioRemove).remove();
            $('div.removeContainerAudio_' + idAudioRemove).slideDown();
        });

        // Stop som player
        $('.divModalAlbum').on('hide.bs.modal', function() {
            $(".audioAdded").trigger('pause');
            $(".audioAdded").prop("currentTime", 0);
        });


        function excluirAlbum(id) {
            if ($("#formExcluirAlbum" + id).length == 0)
                return;
            swal({
                title: 'Excluir álbum?',
                text: "Você deseja mesmo excluir este álbum? Todo seu conteúdo será apagado!",
                icon: "warning",
                buttons: ['Não', 'Sim, excluir!'],
                dangerMode: true,
            }).then((result) => {
                if (result == true) {
                    $("#formExcluirAlbum" + id).submit();
                }
            });
        }

        $(document).ready(function() {

            $('.selectpicker').selectpicker();

            $('input#searchaudio').on('keypress', function(e) {
                if (e.which == 13) {
                    e.preventDefault();
                    var value = $(this).val();
                    var parentFormId = $(this).closest('form').attr('id');
                    filtraAudios(value, parentFormId)
                }
            });

            $('.search-audios').on('click', function() {
                var parentFormId = $(this).closest('form').attr('id');
                var value = $("#" + parentFormId + " #searchaudio").val();
                filtraAudios(value, parentFormId)
            });
        });

        function filtraAudios(value, parentFormId) {
            var audios = $("#" + parentFormId + " input[name='audio_id[]']")
                .map(function() { return $(this).val(); }).get();
            $("#" + parentFormId + " div.showAudio").empty();

            $.ajax({
                type: 'get',
                dataType: 'json',
                url: '{{ route('
                gestao.audios.searchaudios ')}}',
                data: {
                    value: value,
                    audios: audios
                },

                success: function(data) {
                    $("#" + parentFormId + " div.showAudio").html(data);
                },
                error: function() {
                }
            })
        } <
        /script>
        @endsection






        data - toggle = "tooltip"
        data - placement = "top"
        title = ""