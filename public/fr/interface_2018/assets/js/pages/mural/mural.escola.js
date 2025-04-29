    $('#txtDatePicker').datepicker({
        weekStart: 0,
        language: "pt-BR",
        daysOfWeekHighlighted: "0,6",
        autoclose: true,
        todayHighlight: true
    });

                $(document).ready(function () {

                    if (window.location.hash) {
                        $(".nav-link[href='" + window.location.hash + "']").tab('show');
                    }

                    $('.summernote-airmode').summernote({
                        minHeight: '15vh',
                        lang: 'pt-BR',
                        placeholder: 'Clique aqui para escrever sua postagem, você pode colocar imagens, personalizar o texto e muito mais...',
                        airMode: false,
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

                function escreveuPostagem() {
                    if ($("#formPostar #txtPostagem").val() != "") {
                        $("#formPostar #btnEnviarPostagem").removeClass('d-none');
                    } else {
                        $("#formPostar #btnEnviarPostagem").addClass('d-none');
                    }
                }

                function enviarPostagem() {
                    $("#formPostar #divEnviando").removeClass('d-none');
                    $("#formPostar #divEditar").addClass('d-none');
                }

                function mudouModoPostagemEscola(ckb) {
                    $.ajax({
                        url: '{{ route("gestao.escola.mural-modo-postagem", ["escola_id" => $escola->id]) }}',
                        type: 'get',
                        dataType: 'json',
                        data: {'postagem_aberta': $(ckb).prop('checked')},
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

                function anexouArquivo(input) {
                    if (input.value != null && input.value != "") {
                        $("#lblNomeArquivo").text(input.value.split(/(\\|\/)/g).pop());
                    } else {
                        $("#lblNomeArquivo").text("");
                    }
                }

        // Função para buscar dados na tabela
        $(document).ready(function(){
            $('#search').on('click', function (){

            var filtroAluno = $("#search-text").val();
            var excetoCurso = [];
            var escola = $("#escola_select").val();

            $.ajax({
                url: appurl + '/gestao/searchusuarios', 
                type : 'get',
                data : {
                    filtro: filtroAluno
                },
                dataType: 'json',
                success : function(_response) {

                    $('#TableUser').empty();
                    // $('nav ul li').empty();
                    
                    if(_response == 0){
                        let htmlCurso = `<tr class="rounded shadow-sm"> 
                                            <td colspan="4" align="center">Nenhum aluno encontrado!</td> 
                                        </tr>`;
                            $('#TableUser').append(htmlCurso);
                    }else{
                            $('#TableUser').append(_response);
                        }
                    /** Fim da criação de alunos */
                    }
                });
            });
        });

        //API de tabela com busca dinamica e filtro de tabela por sort by
        $(document).ready(function() {
            $('#tableAlunos').DataTable(
            {
                "language": {
                    "search": "Buscar:",
                    "emptyTable":     "Tabela sem registros",
                    "zeroRecords":    "Nenhum registro encontrado",
                    "info":           "Mostrando _START_ até _END_ de _TOTAL_ registros",
                    "infoFiltered":   "(mostrando de _MAX_ total registros)",
                    "lengthMenu":     "Mostrar _MENU_ registros",
                    "infoEmpty":      "Mostrando 0 até 0 de 0 registros",
                 
                 "paginate": {
                    "first":      "Primeira",
                    "last":       "Última",
                    "next":       "Próxima",
                    "previous":   "Anterior"
                    }
                }
            });
        } );
