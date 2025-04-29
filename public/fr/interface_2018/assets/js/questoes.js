// inputs para multipla escolha
 $('.selectTipo').on('change', function () {
    let tipo = $(this).val();
    if (tipo == 2) {
        $('.innerAlternativas').slideDown();
    } else {
        $('.innerAlternativas').slideUp();

        $('.innerAlternativas input[type="text"]').removeAttr('required');
        $('.showNewInput input[type="text"]').removeAttr('required');
        $('.showNewInput').empty();
    }
});

    // inputs para multipla escolha
    $('.selectTipo').on('change', function () {
        let tipo = $(this).val();
        if (tipo == 2) {
            $('.innerAlternativas').slideDown();
        } else {
            $('.innerAlternativas').slideUp();

            $('.innerAlternativas input[type="text"]').removeAttr('required');
            $('.showNewInput input[type="text"]').removeAttr('required');
            $('.showNewInput').empty();
        }
    });

    // adiciona nova opção de alternativas
    $('.btnAdd').click(function () {
        let count = parseInt($(this).attr('id'));
        let sum = count + 1;
        $(this).attr('id', sum);

        if($(this).closest('.modal').find('input[type="radio"]').length == 10) {
            swal("", "Limite itens atingido!", "warning");
        } else {
            $('.showNewInput').append(
                '<div class="input-group mb-2 groupNewInput_' + sum + '">\n' +
                '    <div class="input-group-prepend">\n' +
                '        <div class="input-group-text">\n' +
                '            <input type="radio" name="alternativa_correta" value="' + sum + '">\n' +
                '            <span class="ml-2 text-secondary">Alternativa ' + sum + ':</span>\n' +
                '        </div>\n' +
                '    </div>\n' +
                '    <input type="text" class="form-control" name="' + sum + '"\n' +
                '    placeholder="Digite aqui a alternativa ' + sum + '" required>\n' +
                '    <button type="button" class="ml-2 btn btn-sm btn-danger removeInput" id="' + sum + '"><i class="fas fa-times"></i></button>' +
                '</div>');
        }
    });

    $('.modal-create-questao').on('hide.bs.modal', function () {
        $(this).find('input, textarea').val('');
        $(this).find('.showNewInput').empty();
    });         

    // remove input extra
    $('.showNewInput , .innerAlternativasEdit').on('click', '.removeInput', function () {
        let id = $(this).attr('id');
        $('.groupNewInput_' + id).remove();
    });


    function excluirQuestao(id) {
        $("#formExcluirQuestao #idQuestao").val(id);

        swal({
            title: '',
            text: "Você deseja mesmo excluir este item?",
            icon: "warning",
            buttons: ['Não', 'Sim, excluir!'],
            dangerMode: true,
        }).then((result) => {
            if (result == true) {
                $("#formExcluirQuestao").submit();
            }
        });
    }
