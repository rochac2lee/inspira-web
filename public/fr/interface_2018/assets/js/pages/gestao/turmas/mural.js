$(document).ready(function () {

    $('#txtDatePicker').datepicker({
        weekStart: 0,
        language: "pt-BR",
        daysOfWeekHighlighted: "0,6",
        autoclose: true,
        todayHighlight: true
    });

    $(".datepicker").mask('00/00/0000');

    $(".datepicker").datepicker({
        autoclose: true,
        language: 'pt-BR'
    });

    if (window.location.hash) {
        $(".nav-link[href='" + window.location.hash + "']").tab('show');
    }

    $('.summernote-airmode').summernote({
        lang: 'pt-BR',
        placeholder: 'Clique aqui para escrever sua postagem, você pode colocar imagens, personalizar o texto e muito mais...',
        airMode: true,
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['fontsize', 'color']],
            ['font', ['fontname']],
            ['para', ['paragraph']],
            ['insert', ['hr', 'picture', 'video', 'link', 'table', 'image', 'doc']],
            ['misc', ['undo', 'redo', 'codeview', 'fullscreen', 'help']],
        ],
        popover: {
            image: [
                ['imagesize', ['imageSize100', 'imageSize50', 'imageSize25']],
                ['float', ['floatLeft', 'floatRight', 'floatNone']],
                ['remove', ['removeMedia']]
            ],
            link: [
                ['link', ['linkDialogShow', 'unlink']]
            ],
            air: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['fontsize', 'color']],
                ['font', ['fontname']],
                ['para', ['paragraph']],
                ['table', ['table']],
                ['insert', ['hr', 'picture', 'video', 'link', 'table', 'image', 'doc']],
                ['misc', ['undo', 'redo']],
            ],
        },
    });
});

window.escreveuPostagemTurma = function () {
    if ($("#formPostar #txtPostagem").val() != "") {
        $("#formPostar #btnEnviarPostagem").removeClass('d-none');
    } else {
        $("#formPostar #btnEnviarPostagem").addClass('d-none');
    }
}

window.enviarPostagemTurma = function () {
    $("#formPostar #divEnviando").removeClass('d-none');
    $("#formPostar #divEditar").addClass('d-none');
}

window.mudouModoPostagemTurma = function (turma_id, ckb) {

    data = {'postagem_aberta': $(ckb).prop('checked')};


    $.ajax({
        url: `${appurl}/gestao/turma/${turma_id}/mural/modo`,
        type: 'get',
        dataType: 'json',
        data: data,
        success: function (_response) {

            if (_response.success) {
                //swal("Yeah!", _response.success, "success");
            } else {
                swal("", _response.error, "error");
            }
        },
        error: function (_response) {
        }
    });
}
