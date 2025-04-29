<?php
namespace App\Services\Fr\Agenda;
use App\Jobs\PushAgenda\ProcessaDocumentoOutrosUsuarios;
use App\Jobs\PushAgenda\ProcessaEscola;
use App\Jobs\PushAgenda\ProcessaInstituicao;
use App\Jobs\PushAgenda\ProcessaNotificacaoDocumentoResponsavel;
use App\Jobs\PushAgenda\ProcessaNotificacaoDocumentoResponsavelTurma;
use App\Jobs\PushAgenda\ProcessaTurma;
use App\Jobs\PushAgenda\ProcessaUsuario;
use App\Jobs\PushAgenda\SendNotificacao;
use App\Jobs\PushAgenda\TodosUsuariosInstituicao;
use App\Jobs\PushAgenda\UsuariosAutorizacao;
use App\Jobs\PushAgenda\UsuariosDocumentos;
use App\Jobs\PushAgenda\UsuariosEnquete;
use App\Jobs\PushAgenda\UsuariosTarefa;
use App\Models\Escola;
use App\Models\FrAgendaAutorizacao;
use App\Models\FrAgendaCalendario;
use App\Models\FrAgendaComunicadosTurmas;
use App\Models\FrAgendaDocumento;
use App\Models\FrAgendaEnquete;
use App\Models\FrAgendaFamiliaEscola;
use App\Models\FrAgendaNoticiasTurmas;
use App\Models\FrAgendaRegistrosTurmaProfessor;
use App\Models\FrAgendaTarefa;
use App\Models\FrTurma;
use App\Models\Instituicao;
use App\Models\User;
use DB;

class PushNotificationService {


    public function addNotificacaoFamilia($dados){

        $retorno =[
            'inst' => [],
            'escola' => [],
            'turma' => [],
        ];

        $familia = FrAgendaFamiliaEscola::where('familia_id',$dados['id'])->get();

        foreach ($familia as $f){
            if($f->instituicao_id == 0 ){
                if( $f->privado == 1){
                    $inst = Instituicao::where('instituicao_tipo_id',1)
                        ->pluck('id')
                        ->toArray();
                    $retorno['inst'] = array_merge($retorno['inst'], $inst);
                }
                elseif($f->publico == 1){
                    $inst = Instituicao::where('instituicao_tipo_id',2)
                        ->pluck('id')
                        ->toArray();
                    $retorno['inst'] = array_merge($retorno['inst'], $inst);
                }
                else{
                    $inst = Instituicao::selectRaw('id')
                        ->pluck('id')
                        ->toArray();
                    $retorno['inst'] = array_merge($retorno['inst'], $inst);
                }
            }
            else{

                if($f->escola_id == 0 )
                {
                    $retorno['inst'] = array_merge($retorno['inst'], [$f->instituicao_id]);
                }
                else{
                    $retorno['escola'][] = [
                        'escola_id' => $f->escola_id,
                        'instituicao_id' => $f->instituicao_id,
                    ];
                }
            }
        }
        $dados['consulta'] = $retorno;
        dispatch((new TodosUsuariosInstituicao($dados)));
    }

    public function addNotificacaoComunicado($dados){
        $retorno =[
            'inst' => [],
            'escola' => [],
            'turma' => [],
        ];

        $comunicado = FrAgendaComunicadosTurmas::where('agenda_comunicado_id',$dados['id'])->get();

        foreach ($comunicado as $c){
            if($c->escola_id == 0 )
            {
                $retorno['inst'] = array_merge($retorno['inst'], [$c->instituicao_id]);
            }
            else{
                if($c->turma_id == 0) {
                    $retorno['escola'][] = [
                        'escola_id' => $c->escola_id,
                        'instituicao_id' => $c->instituicao_id,
                    ];
                }
                else{
                    $retorno['turma'][] = [
                        'escola_id'     => $c->escola_id,
                        'instituicao_id' => $c->instituicao_id,
                        'turma_id'      => $c->turma_id,
                    ];
                }
            }
        }
        $dados['consulta'] = $retorno;
        dispatch((new TodosUsuariosInstituicao($dados)));
    }

    public function addNotificacaoNoticia($dados){
        $retorno =[
            'inst' => [],
            'escola' => [],
            'turma' => [],
        ];

        $comunicado = FrAgendaNoticiasTurmas::where('agenda_noticia_id',$dados['id'])->get();

        foreach ($comunicado as $c){
            if($c->escola_id == 0 )
            {
                $retorno['inst'] = array_merge($retorno['inst'], [$c->instituicao_id]);
            }
            else{
                if($c->turma_id == 0) {
                    $retorno['escola'][] = [
                        'escola_id' => $c->escola_id,
                        'instituicao_id' => $c->instituicao_id,
                    ];
                }
                else{
                    $retorno['turma'][] = [
                        'escola_id'     => $c->escola_id,
                        'instituicao_id' => $c->instituicao_id,
                        'turma_id'      => $c->turma_id,
                    ];
                }
            }
        }
        $dados['consulta'] = $retorno;
        dispatch((new TodosUsuariosInstituicao($dados)));
    }

    public function addNotificacaoRegistro($dados){

        $send = FrTurma::join('fr_turma_aluno','fr_turma_aluno.turma_id','fr_turmas.id')
                ->join('fr_responsavel_aluno',function($q){
                    $q->on('fr_responsavel_aluno.aluno_id', 'fr_turma_aluno.aluno_id');
                    $q->on('fr_responsavel_aluno.escola_id', 'fr_turmas.escola_id');
                })
            ->join('users','users.id','fr_responsavel_aluno.responsavel_id')
            ->where('fr_turmas.id',$dados['id'])
            ->whereNotNull('users.device_key_agenda')
            ->selectRaw('users.device_key_agenda')
            ->groupBy('users.id')
            ->pluck('users.device_key_agenda')
            ->toArray();

        if(count($send)>0){
            $dados['send'] = $send;
            dispatch((new SendNotificacao($dados)));
        }
    }

    public function addNotificacaoDocumento($dados){
        $retorno =[
            'inst' => [],
            'escola' => [],
            'turma' => [],
        ];
        $retorno['inst'] = FrAgendaDocumento::join('fr_agenda_documentos_alunos','fr_agenda_documentos.id','fr_agenda_documentos_alunos.documento_id')
            ->where('fr_agenda_documentos.id', $dados['id'])
            ->groupBy('fr_agenda_documentos_alunos.instituicao_id')
            ->selectRaw('fr_agenda_documentos_alunos.instituicao_id')
            ->pluck('fr_agenda_documentos_alunos.instituicao_id')
            ->toArray();

        $retorno['escola'] = FrAgendaDocumento::join('fr_agenda_documentos_alunos','fr_agenda_documentos.id','fr_agenda_documentos_alunos.documento_id')
            ->where('fr_agenda_documentos.id', $dados['id'])
            ->groupBy('fr_agenda_documentos_alunos.escola_id')
            ->pluck('fr_agenda_documentos_alunos.escola_id')
            ->toArray();

        $dados['consulta'] = $retorno;
        dispatch((new UsuariosDocumentos($dados)));

    }



    public function usuariosDocumentos($dados){
        $inst = $dados['consulta']['inst'];
        $escola = $dados['consulta']['escola'];
        unset($dados['consulta']);

        $usuario = User::where(function($query) use($inst,$escola){
                $query->orWhere(function($q) use($inst){
                    $q->where('permissao','I')->whereIn('instituicao_id', $inst);
                })->orWhere(function($q) use($escola){
                    $q->where('permissao','C')->whereIn('escola_id', $escola);
                });
            })
            ->whereNotNull('device_key_agenda')
            ->selectRaw('users.device_key_agenda');

        $usuarioPermissao = User::join('user_permissao','user_permissao.user_id','users.id')
            ->where(function($query) use($inst,$escola){
                $query->orWhere(function($q) use($inst){
                    $q->where('user_permissao.permissao','I')->whereIn('user_permissao.instituicao_id', $inst);
                })->orWhere(function($q) use($escola){
                    $q->where('user_permissao.permissao','C')->whereIn('user_permissao.escola_id', $escola);
                });
            })
            ->whereNotNull('users.device_key_agenda')
            ->selectRaw('users.device_key_agenda');

        $usuarioResponsavel = User::join('fr_responsavel_aluno','users.id','fr_responsavel_aluno.responsavel_id')
            ->join('fr_agenda_documentos_alunos',function($q){
                $q->on('fr_agenda_documentos_alunos.aluno_id','fr_responsavel_aluno.aluno_id');
                $q->on('fr_agenda_documentos_alunos.instituicao_id','fr_responsavel_aluno.instituicao_id');
                $q->on('fr_agenda_documentos_alunos.escola_id','fr_responsavel_aluno.escola_id');
            })
            ->where('fr_agenda_documentos_alunos.documento_id', $dados['id'])
            ->whereNotNull('users.device_key_agenda')
            ->selectRaw('users.device_key_agenda');

        $send = $usuario->union($usuarioPermissao)
            ->union($usuarioResponsavel)
            ->pluck('device_key_agenda')
            ->toArray();

        if(count($send)>0){
            $dados['send'] = $send;
            dispatch((new SendNotificacao($dados)));
        }
    }

    public function addNotificacaoAutorizacao($dados){
        $retorno =[
            'inst' => [],
            'escola' => [],
            'turma' => [],
        ];
        $retorno['inst'] = FrAgendaAutorizacao::join('fr_agenda_autorizacao_alunos','fr_agenda_autorizacao.id','fr_agenda_autorizacao_alunos.autorizacao_id')
            ->where('fr_agenda_autorizacao.id', $dados['id'])
            ->groupBy('fr_agenda_autorizacao_alunos.instituicao_id')
            ->selectRaw('fr_agenda_autorizacao_alunos.instituicao_id')
            ->pluck('fr_agenda_autorizacao_alunos.instituicao_id')
            ->toArray();

        $retorno['escola'] = FrAgendaAutorizacao::join('fr_agenda_autorizacao_alunos','fr_agenda_autorizacao.id','fr_agenda_autorizacao_alunos.autorizacao_id')
            ->where('fr_agenda_autorizacao.id', $dados['id'])
            ->groupBy('fr_agenda_autorizacao_alunos.escola_id')
            ->pluck('fr_agenda_autorizacao_alunos.escola_id')
            ->toArray();

        $dados['consulta'] = $retorno;
        dispatch((new UsuariosAutorizacao($dados)));
    }

    public function usuariosAutorizacao($dados){
        $inst = $dados['consulta']['inst'];
        $escola = $dados['consulta']['escola'];
        unset($dados['consulta']);

        $usuario = User::where(function($query) use($inst,$escola){
            $query->orWhere(function($q) use($inst){
                $q->where('permissao','I')->whereIn('instituicao_id', $inst);
            })->orWhere(function($q) use($escola){
                $q->where('permissao','C')->whereIn('escola_id', $escola);
            })->orWhere(function($q) use($escola){
                $q->where('permissao','P')->whereIn('escola_id', $escola);
            });
        })
            ->whereNotNull('device_key_agenda')
            ->selectRaw('users.device_key_agenda');

        $usuarioPermissao = User::join('user_permissao','user_permissao.user_id','users.id')
            ->where(function($query) use($inst,$escola){
                $query->orWhere(function($q) use($inst){
                    $q->where('user_permissao.permissao','I')->whereIn('user_permissao.instituicao_id', $inst);
                })->orWhere(function($q) use($escola){
                    $q->where('user_permissao.permissao','C')->whereIn('user_permissao.escola_id', $escola);
                })->orWhere(function($q) use($escola){
                    $q->where('user_permissao.permissao','P')->whereIn('user_permissao.escola_id', $escola);
                });
            })
            ->whereNotNull('users.device_key_agenda')
            ->selectRaw('users.device_key_agenda');

        $usuarioResponsavel = User::join('fr_responsavel_aluno','users.id','fr_responsavel_aluno.responsavel_id')
            ->join('fr_agenda_autorizacao_alunos',function($q){
                $q->on('fr_agenda_autorizacao_alunos.aluno_id','fr_responsavel_aluno.aluno_id');
                $q->on('fr_agenda_autorizacao_alunos.instituicao_id','fr_responsavel_aluno.instituicao_id');
                $q->on('fr_agenda_autorizacao_alunos.escola_id','fr_responsavel_aluno.escola_id');
            })
            ->where('fr_agenda_autorizacao_alunos.autorizacao_id', $dados['id'])
            ->whereNotNull('users.device_key_agenda')
            ->selectRaw('users.device_key_agenda');

        $send = $usuario->union($usuarioPermissao)
            ->union($usuarioResponsavel)
            ->pluck('device_key_agenda')
            ->toArray();

        if(count($send)>0){
            $dados['send'] = $send;
            dispatch((new SendNotificacao($dados)));
        }
    }

    public function addNotificacaoEnquete($dados){
        $retorno =[
            'inst' => [],
            'escola' => [],
            'turma' => [],
        ];
        $retorno['inst'] = FrAgendaEnquete::join('fr_agenda_enquetes_turmas','fr_agenda_enquetes.id','fr_agenda_enquetes_turmas.enquete_id')
            ->where('fr_agenda_enquetes.id', $dados['id'])
            ->groupBy('fr_agenda_enquetes_turmas.instituicao_id')
            ->selectRaw('fr_agenda_enquetes_turmas.instituicao_id')
            ->pluck('fr_agenda_enquetes_turmas.instituicao_id')
            ->toArray();

        $retorno['escola'] = FrAgendaEnquete::join('fr_agenda_enquetes_turmas','fr_agenda_enquetes.id','fr_agenda_enquetes_turmas.enquete_id')
            ->where('fr_agenda_enquetes.id', $dados['id'])
            ->groupBy('fr_agenda_enquetes_turmas.escola_id')
            ->pluck('fr_agenda_enquetes_turmas.escola_id')
            ->toArray();

        $dados['consulta'] = $retorno;
        dispatch((new UsuariosEnquete($dados)));
    }



    public function usuariosEnquete($dados){
        $inst = $dados['consulta']['inst'];
        $escola = $dados['consulta']['escola'];
        unset($dados['consulta']);

        $usuario = User::where(function($query) use($inst,$escola){
            $query->orWhere(function($q) use($inst){
                $q->where('permissao','I')->whereIn('instituicao_id', $inst);
            })->orWhere(function($q) use($escola){
                $q->where('permissao','C')->whereIn('escola_id', $escola);
            })->orWhere(function($q) use($escola){
                $q->where('permissao','P')->whereIn('escola_id', $escola);
            });
        })
            ->whereNotNull('device_key_agenda')
            ->selectRaw('users.device_key_agenda');

        $usuarioPermissao = User::join('user_permissao','user_permissao.user_id','users.id')
            ->where(function($query) use($inst,$escola){
                $query->orWhere(function($q) use($inst){
                    $q->where('user_permissao.permissao','I')->whereIn('user_permissao.instituicao_id', $inst);
                })->orWhere(function($q) use($escola){
                    $q->where('user_permissao.permissao','C')->whereIn('user_permissao.escola_id', $escola);
                });
            })
            ->whereNotNull('users.device_key_agenda')
            ->selectRaw('users.device_key_agenda');

        $usuarioResponsavel = User::join('fr_responsavel_aluno','users.id','fr_responsavel_aluno.responsavel_id')
            ->join('fr_agenda_enquetes_turmas',function($q){
                $q->on('fr_agenda_enquetes_turmas.aluno_id','fr_responsavel_aluno.aluno_id');
                $q->on('fr_agenda_enquetes_turmas.instituicao_id','fr_responsavel_aluno.instituicao_id');
                $q->on('fr_agenda_enquetes_turmas.escola_id','fr_responsavel_aluno.escola_id');
            })
            ->where('fr_agenda_enquetes_turmas.enquete_id', $dados['id'])
            ->whereNotNull('users.device_key_agenda')
            ->selectRaw('users.device_key_agenda');

        $send = $usuario->union($usuarioPermissao)
            ->union($usuarioResponsavel)
            ->pluck('device_key_agenda')
            ->toArray();

        if(count($send)>0){
            $dados['send'] = $send;
            dispatch((new SendNotificacao($dados)));
        }
    }

    public function addNotificacaoTarefa($dados){
        $retorno =[
            'inst' => [],
            'escola' => [],
            'turma' => [],
        ];
        $retorno['inst'] = FrAgendaTarefa::join('fr_agenda_tarefa_alunos','fr_agenda_tarefa.id','fr_agenda_tarefa_alunos.tarefa_id')
            ->where('fr_agenda_tarefa.id', $dados['id'])
            ->groupBy('fr_agenda_tarefa_alunos.instituicao_id')
            ->selectRaw('fr_agenda_tarefa_alunos.instituicao_id')
            ->pluck('fr_agenda_tarefa_alunos.instituicao_id')
            ->toArray();

        $retorno['escola'] = FrAgendaTarefa::join('fr_agenda_tarefa_alunos','fr_agenda_tarefa.id','fr_agenda_tarefa_alunos.tarefa_id')
            ->where('fr_agenda_tarefa.id', $dados['id'])
            ->groupBy('fr_agenda_tarefa_alunos.escola_id')
            ->pluck('fr_agenda_tarefa_alunos.escola_id')
            ->toArray();

        $dados['consulta'] = $retorno;
        dispatch((new UsuariosTarefa($dados)));
    }

    public function usuariosTarefa($dados){
        $inst = $dados['consulta']['inst'];
        $escola = $dados['consulta']['escola'];
        unset($dados['consulta']);

        $usuario = User::where(function($query) use($inst,$escola){
            $query->orWhere(function($q) use($inst){
                $q->where('permissao','I')->whereIn('instituicao_id', $inst);
            })->orWhere(function($q) use($escola){
                $q->where('permissao','C')->whereIn('escola_id', $escola);
            })->orWhere(function($q) use($escola){
                $q->where('permissao','P')->whereIn('escola_id', $escola);
            });
        })
            ->whereNotNull('device_key_agenda')
            ->selectRaw('users.device_key_agenda');

        $usuarioPermissao = User::join('user_permissao','user_permissao.user_id','users.id')
            ->where(function($query) use($inst,$escola,$dados){
                $query->orWhere(function($q) use($inst){
                    $q->where('user_permissao.permissao','I')->whereIn('user_permissao.instituicao_id', $inst);
                })->orWhere(function($q) use($escola){
                    $q->where('user_permissao.permissao','C')->whereIn('user_permissao.escola_id', $escola);
                });
                foreach($escola as $e) {
                    $query = $query->orWhere(function ($q) use ($e, $dados) {
                        $q->where('user_permissao.permissao', 'P')
                            ->where('user_permissao.escola_id', $e)
                            ->where('users.id', $dados['dono_tarefa']);
                    });
                }
            })
            ->whereNotNull('users.device_key_agenda')
            ->selectRaw('users.device_key_agenda');

        $usuarioResponsavel = User::join('fr_responsavel_aluno','users.id','fr_responsavel_aluno.responsavel_id')
            ->join('fr_agenda_tarefa_alunos',function($q){
                $q->on('fr_agenda_tarefa_alunos.aluno_id','fr_responsavel_aluno.aluno_id');
                $q->on('fr_agenda_tarefa_alunos.instituicao_id','fr_responsavel_aluno.instituicao_id');
                $q->on('fr_agenda_tarefa_alunos.escola_id','fr_responsavel_aluno.escola_id');
            })
            ->where('fr_agenda_tarefa_alunos.tarefa_id', $dados['id'])
            ->whereNotNull('users.device_key_agenda')
            ->selectRaw('users.device_key_agenda');

        $send = $usuario->union($usuarioPermissao)
            ->union($usuarioResponsavel)
            ->pluck('device_key_agenda')
            ->toArray();

        if(count($send)>0){
            $dados['send'] = $send;
            dispatch((new SendNotificacao($dados)));
        }
    }

    public function todosUsuariosDaInstituicao($dados){
        $inst = $dados['consulta']['inst'];
        $escola = $dados['consulta']['escola'];
        $turma = $dados['consulta']['turma'];
        unset($dados['consulta']);
        $usuario = User::where('permissao','<>','A')
                        ->where('permissao','<>','R')
                        ->where(function($query) use ($inst, $escola){
                            if(count($inst)>0) {
                                $query->orWhere(function ($q) use ($inst) {
                                    $q->whereIn('instituicao_id', $inst);
                                });
                            }
                            if(count($escola)>0) {
                                foreach($escola as $e) {
                                    $query->orWhere(function ($q) use ($inst, $e) {
                                        $q->where('escola_id', $e['escola_id']);
                                    });
                                    $query->orWhere(function ($q) use ($inst, $e) {
                                        $q->where('instituicao_id', $e['instituicao_id'])
                                            ->where('permissao', 'I');
                                    });
                                }
                            }
                        })
                        ->whereNotNull('device_key_agenda')
                        ->selectRaw('users.device_key_agenda');

        $usuarioPermissao = User::join('user_permissao','user_permissao.user_id','users.id')
                        ->where('user_permissao.permissao','<>','A')
                        ->where('user_permissao.permissao','<>','R')
                        ->where(function($query) use ($inst, $escola){
                            if(count($inst)>0) {
                                $query->orWhere(function ($q) use ($inst) {
                                    $q->whereIn('user_permissao.instituicao_id', $inst);
                                });
                            }
                            if(count($escola)>0) {
                                foreach($escola as $e) {
                                    $query->orWhere(function ($q) use ($inst, $e) {
                                        $q->where('user_permissao.escola_id', $e['escola_id']);
                                    });
                                    $query->orWhere(function ($q) use ($inst, $e) {
                                        $q->where('user_permissao.instituicao_id', $e['instituicao_id'])
                                            ->where('user_permissao.permissao', 'I');
                                    });
                                }
                            }
                        })
                        ->whereNotNull('users.device_key_agenda')
                        ->selectRaw('users.device_key_agenda');

        $usuarioResponsavel = User::join('fr_responsavel_aluno','users.id','fr_responsavel_aluno.responsavel_id')
                                    ->where(function($query) use ($inst, $escola, $turma){
                                        if(count($inst)>0) {
                                            $query->orWhere(function ($q) use ($inst) {
                                                $q->whereIn('fr_responsavel_aluno.instituicao_id', $inst);
                                            });
                                        }
                                        if(count($escola)>0) {
                                            foreach($escola as $e) {
                                                $query->orWhere(function ($q) use ($inst, $e) {
                                                    $q->where('fr_responsavel_aluno.escola_id', $e['escola_id']);
                                                });
                                            }
                                        }
                                        if(count($turma)>0) {
                                            foreach($turma as $e) {
                                                $query->orWhere(function ($q) use ($inst, $e) {
                                                    $q->where('fr_responsavel_aluno.escola_id', $e['escola_id']);
                                                    $q->where('fr_responsavel_aluno.turma_id', $e['turma_id']);
                                                });
                                            }
                                        }
                                    })
                                    ->whereNotNull('users.device_key_agenda')
                                    ->selectRaw('users.device_key_agenda');
        $send = $usuario->union($usuarioPermissao)
            ->union($usuarioResponsavel)
            ->pluck('device_key_agenda')
            ->toArray();

        if(count($send)>0){
            $dados['send'] = $send;
            dispatch((new SendNotificacao($dados)));
        }
    }


    public function sendNotificacao($dados){
        $url = 'https://fcm.googleapis.com/fcm/send';

        $FcmToken = $dados['send'];

        $serverKey = 'AAAADfipXmk:APA91bG1_-a2JQw_C-vlf1HhyZo1h2hvKlGQwvw_vSZ3p1f9j8IbDRHhcDy226U_QmNpBw0VTm6OfeT8vJHgI7ySj1Nx9AR0G2eJ-7GvQFKbXpTsIByk4egBHCXMsOuIFTSn3xOVvW8n';

        $data = [
            "registration_ids" => $FcmToken,
            "notification" => [
                "title" => $dados['titulo'],
                "body" => $dados['corpo'],
            ]
        ];
        $encodedData = json_encode($data);

        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);

        // Execute post
        $result = curl_exec($ch);

        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);

        // FCM response
        //dd($result);
    }

}
