<?php

namespace App\Services;

use Auth;

//use App\Models\User;
//use App\Models\Notificacao;

use App\Models\Conteudo;
use App\Entities\Questoes\Questoes;
use App\Entities\Questoes\QuestaoConteudo;

class ConteudoService {

    public static function ajustarQuestoes($questoes)
    {
        $qtdQuestoes = count($questoes);

        if ($qtdQuestoes > 1) {
            $arrayQuestoes = "[";
        } else {
            $arrayQuestoes = "";
        }

        foreach ($questoes as $questao) {
            $arrayQuestoes .= '{"id": '.$questao->id.',"pergunta":"'.$questao->pergunta.'"';

            if($questao->tipo) {
                $arrayQuestoes .= ',"tipo":'.$questao->tipo;
            }

            if($questao->dica || $qtdQuestoes == 1) {
                $arrayQuestoes .= ',"dica":"'.$questao->dica.'"';
            }

            if($questao->explicacao || $qtdQuestoes == 1) {
                $arrayQuestoes .= ',"explicacao":"'.$questao->explicacao.'"';
            }

            if($questao->alternativas) {
                $alternativas = json_decode($questao->alternativas);

                $strAlternativa = "[";
                foreach ($alternativas as $value) {
                    $strAlternativa .= '"'.$value.'",';
                }

                $strAlternativa = substr($strAlternativa, 0, -1);
                $strAlternativa .= "]";
                $arrayQuestoes .= ',"alternativas":'.$strAlternativa;
            }

            if($questao->resposta_correta || $questao->tipo == 2) {
                $arrayQuestoes .= ',"correta":"'.$questao->resposta_correta.'"';
            }

            $arrayQuestoes .= ',"peso":"'.($questao->peso ? $questao->peso : 1).'"';

            $arrayQuestoes .= "},";
        }

        $arrayQuestoes = substr($arrayQuestoes, 0, -1);

        if ($qtdQuestoes > 1) {
            $arrayQuestoes .= "]";
        }

        return $arrayQuestoes;
    }

    public static function salvarQuestoes($request)
    {
        $count = 0;
        $questoes_id = [];
        if (isset($request->questao_id)) {
            $questoes_id = explode(',',$request->questao_id);
        }

        if (isset($request->conteudoProva)) {
            $questoes = json_decode($request->conteudoProva);
            foreach ($questoes as $prova) {
                $alternativas = [];
                if (isset($prova->alternativas)) {
                    foreach ($prova->alternativas as $k => $alt) {
                    $alternativas[$k+1] = $alt;
                    }
                }

                if (count($alternativas) == 0) {
                    $alternativas = '';
                } else {
                    $alternativas = json_encode($alternativas);
                }

                if (!isset($questoes_id[$count])) {
                    $questaoNova =
                    Questoes::create([
                        'pergunta' => $prova->pergunta,
                        'peso' => isset($prova->peso) ? $prova->peso : 1,
                        'tipo' => isset($prova->tipo) ? $prova->tipo : 1,
                        'alternativas' => $alternativas,
                        'resposta_correta' => isset($prova->correta) ? $prova->correta : null,
                        'user_id' => Auth::user()->id
                    ]);

                    QuestaoConteudo::create([
                        'questao_id' => $questaoNova->id,
                        'conteudo_id' => $request->novoConteudo
                    ]);
                } else {
                    $questaoNova =
                    Questoes::find($questoes_id[$count])->update([
                        'pergunta' => $prova->pergunta,
                        'peso' => isset($prova->peso) ? $prova->peso : 1,
                        'tipo' => isset($prova->tipo) ? $prova->tipo : 1,
                        'alternativas' => $alternativas,
                        'resposta_correta' => isset($prova->correta) ? $prova->correta : null
                    ]);
                }
                $count++;
            }
        } else {
            $alternativas = [];
            if (isset($request->alternativas)) {
                foreach ($request->alternativas as $k => $alt) {
                    $alternativas[$k+1] = $alt;
                }
            }

            if (count($alternativas) == 0) {
                $alternativas = '';
            } else {
                $alternativas = json_encode($alternativas);
            }

            if (!isset($request->idConteudo)) {
                $questaoNova =
                Questoes::create([
                    'pergunta' => $request->pergunta,
                    'dica' => isset($request->dica) ? $request->dica : null,
                    'explicacao' => isset($request->explicacao) ? $request->explicacao : null,
                    'alternativas' => $alternativas,
                    'resposta_correta' => isset($request->correta) ? $request->correta : null,
                    'tipo' => ($alternativas) ? 2 : 1,
                    'peso' => isset($request->peso) ? $request->peso : 1,
                    'user_id' => Auth::user()->id
                ]);

                QuestaoConteudo::create([
                    'questao_id' => $questaoNova->id,
                    'conteudo_id' => $request->novoConteudo
                ]);
            } else {
                $questaoNova =
                Questoes::find($questoes_id[0])->update([
                    'pergunta' => $request->pergunta,
                    'dica' => isset($request->dica) ? $request->dica : null,
                    'explicacao' => isset($request->explicacao) ? $request->explicacao : null,
                    'alternativas' => $alternativas,
                    'resposta_correta' => isset($request->correta) ? $request->correta : null,
                    'tipo' => ($alternativas) ? 2 : 1,
                    'peso' => isset($request->peso) ? $request->peso : 1
                ]);
            }
        }
    }
}
