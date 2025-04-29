
$(document).ready(function () {
    
    $('#subm').on('click', function () {
        preenchido = true;
        //Add verificar se campos input ou select são obrigatórios
        $('#formNovaTrilha :input[required]').each(function () {
            if ($(this).val() == "") {
                $(this).focus();
                swal("", "Preencha todos os campos!", "warning");

                preenchido = false;
                return false;
            }
        });  

        if($("#escola_id").val() == "") {
            swal("", "Preencha todos os campos!", "warning");

            preenchido = false;
            return false;

        }


        if($('#ciclo_id').val() == "") {
            swal("", "Preencha todos os campos!", "warning");

            preenchido = false;
            return false;

        }

        if($("#disciplina_id").val() == "") {
            swal("", "Preencha todos os campos!", "warning");

            preenchido = false;
            return false;


            
        }

        
        
        if($("#formNovaTrilha [name=tcursos]").val() == 0) {
            swal("", "Selecione pelo menos um curso para a trilha!", "warning");

            preenchido = false;
            return false;

        }

        if (preenchido == true) {
            $("#subm").attr("disabled", true);
            document.getElementById('formNovaTrilha').submit();
        }
    });

    $(window).keydown(function (event) {
        if (event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });

    filtrarCursos();

    $('.cursosAddInline').sortable({
        handle: '.handle',
        update: function (event, ui) { reordenarCurso(event, ui) },
    });

    // CRIA AS FUNCOES DE CONTROLE DE ADICAO/REMOCAO
    $('#escola_select').change(function () {
        $('#escola_id').val($(this).val());
        filtrarCursos();
    });
    /** Fim do controle de adicao/remocao */
});

$('#search').click(filtrarCursos);

function ajustarOrdemRemove(ordemAntiga) {
    var contaCurso = 0;

    $("[name='curso_id[]']").each(function () {
        let cursoAtual = $("#curso_id_ordem_" + $(this).val());
        if (cursoAtual.val() > ordemAntiga) {
            let ordemNova = parseInt(cursoAtual.val()) - 1;
            cursoAtual.val(ordemNova)
        }
        contaCurso = contaCurso + 1;
    });

    if (contaCurso == 0) {
        $("#escola_select").attr("disabled", false);
    }
}



function adicionarCurso(id) {

    let tcursos = $("#formNovaTrilha [name=tcursos]").val();
    tcursos = parseInt(tcursos) + 1;
    $("#formNovaTrilha [name=tcursos]").val(tcursos);
    
    let idCurso = id;
    let ordem = $('#ordem').val();

    ordem = parseInt(ordem) + 1;
    $('div.cursosIds').append('<input type="hidden" id="curso_id_' + idCurso + '" name="curso_id[]" value="' + idCurso + '">');
    $('div.cursosIds').append('<input type="hidden" id="curso_id_ordem_' + idCurso + '" name="curso_id_ordem_' + idCurso + '" value="' + ordem + '">');
    $('#ordem').val(ordem);
    

    let curso = $('div.removeContainerCurso_' + idCurso).clone();
    curso.removeClass('removeContainerCurso_' + idCurso);
    curso.addClass('addContainerCurso_' + idCurso);
    curso.attr('cursoid', idCurso);

    let divClick = curso.find(".addCurso");
    divClick.attr("onclick", "removerCurso(this.id)");

    let buttonMove = curso.find("i");
    buttonMove.removeClass('fa-plus');
    buttonMove.addClass('fa-times');
    buttonMove.addClass('removeCurso' + idCurso);
    buttonMove.addClass('removeCurso');
    buttonMove.attr('id', idCurso);

    $('div.cursosAddInline').append(curso);
    $('div.removeContainerCurso_' + idCurso).slideUp();

    $("#escola_select").attr("disabled", true);
}

function removerCurso(id) {
    let idCursoRemove = id;
    let ordemAntiga = $("input#curso_id_ordem_" + idCursoRemove).val();
    $(".addContainerCurso_" + idCursoRemove).remove();
    $("input#curso_id_" + idCursoRemove).remove();
    $("input#curso_id_ordem_" + idCursoRemove).remove();
    $('div.removeContainerCurso_' + idCursoRemove).slideDown();

    let tcursos = $("#formNovaTrilha [name=tcursos]").val();
    tcursos = parseInt(tcursos) - 1;
    $("#formNovaTrilha [name=tcursos]").val(tcursos);


    ordem = parseInt(ordem) + 1;
    $('div.box-cursos-add').append('<input type="hidden" id="curso_id_' + idCurso + '" name="curso_id[]" value="' + idCurso + '">');
    $('div.box-cursos-add').append('<input type="hidden" id="curso_id_ordem_' + idCurso + '" name="curso_id_ordem_' + idCurso + '" value="' + ordem + '">');
    $('#ordem').val(ordem);

    let ordem = $('#ordem').val();
    ordem = parseInt(ordem) - 1;
    $('#ordem').val(ordem);

    

    ajustarOrdemRemove(ordemAntiga);
}

function reordenarCurso(event, ui) {
    let curso = ui.item[0].attributes.cursoid.value;
    let ordemNova = $(ui.item[0]).index() + 1;
    let ordemAntiga = $("#curso_id_ordem_" + curso).val();

    $("#curso_id_ordem_" + curso).val(ordemNova)
    $("[name='curso_id[]']").each(function () {
        let cursoAtual = $("#curso_id_ordem_" + $(this).val());
        let cursoId = $("#curso_id_" + $(this).val()).val();
        if (cursoId != curso) {
            if (ordemNova > ordemAntiga) {
                if (cursoAtual.val() > ordemAntiga && cursoAtual.val() <= ordemNova) {
                    cursoAtual.val(parseInt(cursoAtual.val()) - 1);
                }
            } else {
                if (cursoAtual.val() < ordemAntiga && cursoAtual.val() >= ordemNova) {
                    cursoAtual.val(parseInt(cursoAtual.val()) + 1);
                }
            }
        }
    });
}


// Funções de criação de curso que não esta nessa sprint
function mudouPeriodo(el) {
    if (el.value > 0 && el.value <= 365) {
        $("#lblPeriodo").text(el.value);
    } else {
        $("#lblPeriodo").text("Ilimitado");
    }
}

function mudouVagas(el) {
    if (el.value > 0 && el.value <= 100) {
        $("#lblVagas").text(el.value);
    } else {
        $("#lblVagas").text("Ilimitado");
    }
}

function salvar(rascunho) {
    var isValid = true;

    $('#modalNovoCurso').each(function() {
        if (($(this).val() === '' || $(this).val() == null) && $(this).attr('required')) {
            console.log(this);

            Swal.fire({
                type: 'error',
                title: 'Oops...',
                text: 'Preencha todos os campos!'
            })
            // return false;

            $(this).focus();

            isValid = false;
        }
    });

    if (!isValid)
        return;

    $("#rascunho").val(rascunho);
    $("#divSalvando").removeClass('d-none');
    $("#formNovoCurso").submit();
    $('#modalNovoCurso').modal('hide');
}