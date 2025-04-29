
    $(document).ready(function(){

            $('#datepickerRecorrente').mask("00/00/0000", {placeholder: "__/__/____"}).datepicker({
                locale: 'pt-br',
                format: 'dd/mm/yyyy',
                endDate : new Date('2016-05-20'),
            });

            $('.datepickerRecorrenteEdit').mask("00/00/0000", {placeholder: "__/__/____"}).datepicker({
                locale: 'pt-br',
                format: 'dd/mm/yyyy',
                endDate : new Date('2016-05-20'),
            });
            
        });

        // Ano filter dropbox
        $('.selectAno').click(function () {
            var css = $("div#selectAno").attr("class");
            if (css !== 'collapse show') {
                $(this).addClass('btn-select-open');
                $('i#icon-ano').removeClass('fas fa-caret-down').addClass('fas fa-caret-up');
            } else {
                $('i#icon-ano').removeClass('fas fa-caret-up').addClass('fas fa-caret-down');
                $(this).removeClass('btn-select-open');
            }

        });

        $('.selectDisciplina').click(function () {
            var css = $("div#selectDisciplina").attr("class");

            if (css !== 'collapse show') {
                $(this).addClass('btn-select-open');
                $('i#icon-ano').removeClass('fas fa-caret-down').addClass('fas fa-caret-up');
            } else {
                $('i#icon-ano').removeClass('fas fa-caret-up').addClass('fas fa-caret-down');
                $(this).removeClass('btn-select-open');
            }
        });


        $("button.editPlano").on('click', function () {
            var idPlano = $(this).attr('data-id');
            var gradeAula = $("select.selectGradeAulasEdit_" + idPlano + " option:selected");
            if (gradeAula.attr('class') === '1') {
                var today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
                var idGrade = gradeAula.val();
                var daySelected = gradeAula.attr('id');
                var planoDate = gradeAula.attr('data-id');
                $.ajax({
                    method: 'get',
                    url: 'plano-de-aulas/getDates/' + idGrade,
                    success: function (date) {
                        $('.selectDataRecorrenteEdit').slideDown();
                        calendarEdit(daySelected, today, planoDate, date);
                    }
                });
            } else {
                $('.selectDataRecorrenteEdit').slideUp();
            }
        });


        $(".selectGradeAulas").on('change', function () {
            var idGrade = $(".selectGradeAulas option:selected").val();
            var recorrente = $(".selectGradeAulas option:selected").attr('class');
            var daySelected = $(".selectGradeAulas option:selected").attr('id');

            var today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
            if (recorrente === '1') {
                $.ajax({
                    method: 'get',
                    url: 'plano-de-aulas/getDates/' + idGrade,
                    success: function (date) {
                        $('.selectDataRecorrente').slideDown();
                        calendar(daySelected, today, date);
                    }
                });
            } else {
                $('.selectDataRecorrente').slideUp();
            }
        });

        function getDaysWeek(daySelected) {
            days = [0, 1, 2, 3, 4, 5, 6]; // days of week
            var removeItem = daySelected;
            return days = $.grep(days, function (value) {
                return value != removeItem;
            });
        }

        function excluirPlano(id) {
            $("#formExcluirPlano #idPlano").val(id);

            swal({
                title: 'Excluir Plano de aula?',
                text: "Você deseja mesmo excluir este plano de aula?",
                icon: "warning",
                buttons: ['Não', 'Sim, excluir!'],
                dangerMode: true,
            }).then((result) => {
                if (result == true) {
                    $("#formExcluirPlano").submit();
                }
            });
        }

        $(document).ready(function(){
            $('button.validaCampos').on('click', function(evt){
                // essa linha cancela o comportamento padrão do navegador
                evt.preventDefault();
                camposPreenchidos = true;

                newLocal = '#divModalNovaBadge :input[required]';

                $(newLocal).each(function()
                {
                    if($(this).val() == "")
                    {
                        $(this).focus();

                        swal("", 'Por favor, preencha os campos obrigatórios!', 'error');
                        camposPreenchidos = false;
                        return false;
                    }
                });

            // validação de data

            //recupera o input de data
            campo = $('#datepickerRecorrente').val();
            //Verifica se é uma data valida
            const dataVerificar = moment(campo, "DD/MM/YYYY", true);
                data =  moment(dataVerificar).isValid();
            //Formata a data para yyyy-mm-dd
            campoInput = moment(dataVerificar).format("YYYY-MM-DD");
            //captura data atual
            after = moment().format("YYYY-MM-DD");
         
            dataMaxima = moment(after).isAfter(campoInput);

            if(data == false || dataMaxima == true)
            {
                $("#datepickerRecorrente").focus();
                swal("", 'A data deve ser igual ou maior que a de hoje!', 'error');
                return false;
            }
                if(camposPreenchidos && data && dataMaxima == false)
                {
                    // continue;
                    $('#formInput').submit();                    
                }
            });

           
        });

        function validaPlanosUpdate(id)
        {
               camposPreenchidos = true;

               //recupera o input de data
               campo = $('#datepickerRecorrenteEdit'+ id).val();
                //Verifica se é uma data valida
                const dataVerificar = moment(campo, "DD/MM/YYYY", true);
                    data =  moment(dataVerificar).isValid();
                //Formata a data para yyyy-mm-dd
                campoInput = moment(dataVerificar).format("YYYY-MM-DD");
                //captura data atual
                after = moment().format("YYYY-MM-DD");
            
                dataMaxima = moment(after).isAfter(campoInput);

                if(data == false || dataMaxima == true)
                {
                    $("#datepickerRecorrenteEdit" + id).focus();
                    swal("", 'A data deve ser igual ou maior que a de hoje!', 'error');
                    return false;
                }

                if(data && dataMaxima == false)
                {
                    $( "#formAtualizaPlano_" + id ).submit();

                }
            
        }