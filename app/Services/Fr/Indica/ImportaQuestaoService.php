<?php
namespace App\Services\Fr\Indica;
use App\Http\Requests\Fr\Indica\QuestaoRequest;
use App\Http\Requests\Fr\ProfessorRequest;
use App\Jobs\Indica\CadastraQuestao;
use App\Models\Indica\ImportaLogQuestao;
use App\Models\Indica\ImportaQuestao;
use App\Models\Indica\Questao;
use DB;
use App\Models\QuestaoTema;
use Str;
use Validator;
use function JmesPath\search;

class ImportaQuestaoService {

    public function listaImportados(){
        return ImportaQuestao::with('usuario')->orderBy('id','desc')->paginate(20);
    }


	public function importar($dados)
	{
		DB::beginTransaction();
        try
        {
          //  dd($dados);
        	$dados['user_id'] = auth()->user()->id;
            $dados['caminho'] = $dados['arquivo']->store(config('app.frStorage').'indica/importa_questao');
            $dados['nome_arquivo'] = $dados['arquivo']->getClientOriginalName();
			$arquivo = new ImportaQuestao($dados);
            $arquivo->save();
	        DB::commit();

            $nomeArquivoTMP = Str::random(30).'.txt';
            $dados['arquivo']->move(config('app.frTmp'), $nomeArquivoTMP);

            $this->importaArquivo($nomeArquivoTMP, $arquivo->id);

	        return true;
	    }
        catch (\Exception $e)
        {
            DB::rollback();
            dd($e);
            return false;
        }
	}

    private function importaArquivo($caminho, $arquivoID){
        $arquivo = fopen(config('app.frTmp').$caminho, 'r');
        $iniciar = 0;
        $count = 1;
        //// percorre o arquivo
        while (!feof($arquivo)) {
            $linha = fgetcsv($arquivo, 0, ',');
            if(!is_array($linha) || count($linha) == 0){
                continue;
            }
            $linha = array_map('trim',$linha);
            if($iniciar == 1){ //// se a primeira linha de usuarios for encontrada
                $dados = [
                    'linha'         => $linha,
                    'numero_linha'  => $count,
                    'arquivo_id'    => $arquivoID,
                    'user_id'    => auth()->user()->id,
                ];
                dispatch((new CadastraQuestao($dados)));
                //$this->processaLinha($dados);
            }
            elseif( mb_strtolower($linha[0]) == 'id item') /// encontrou o inicio da leitura do arquivo
            {
                $iniciar = 1;
            }
            $count++;
        }
        fclose($arquivo);
        return true;
    }

    public function processaLinha($dados){
        $erro =0;
        if(count($dados['linha']) < 23){
            $log=[
                'importacao_id' => $dados['arquivo_id'],
                'linha' => $dados['numero_linha'],
                'erro' => 1,
                'erro_banco' => 'A quantidade de colunas da linha está fora do padrão determinado.',
            ];
            $erro++;
        }else{
            $questao =[
                'user_id'           => $dados['user_id'],
                'disciplina_id'     => $dados['linha'][13],
                'dificuldade'       => $dados['linha'][11],
                'tipo'              => 'obj',
                'codigo'            => $dados['linha'][0],
                'pergunta'          => $dados['linha'][1],
                'alternativa_1'     => $dados['linha'][3],
                'alternativa_2'     => $dados['linha'][4],
                'alternativa_3'     => $dados['linha'][5],
                'alternativa_4'     => $dados['linha'][6],
                'alternativa_5'     => $dados['linha'][7],
                'alternativa_6'     => $dados['linha'][8],
                'alternativa_7'     => $dados['linha'][9],
                'qtd_alternativa'   => $dados['linha'][2],
                'correta'           => $dados['linha'][10],
                'resolucao'         => $dados['linha'][21],
                'link_resolucao'    => $dados['linha'][22],
                'unidade_tematica'  => $dados['linha'][14],
                'palavras_chaves'   => $dados['linha'][19],
                'fonte'             => $dados['linha'][20],
            ];
            if($dados['linha'][15] != ''){
                $questao['bncc_id'] = $dados['linha'][15];
            }
            if($dados['linha'][12] != ''){
                $questao['cicloetapa_id'] = $dados['linha'][12];
            }
            if($dados['linha'][17] != ''){
                $questao['suporte_id'] = $dados['linha'][17];
            }
            if($dados['linha'][16] != ''){
                $questao['formato_id'] = $dados['linha'][16];
            }
            if($dados['linha'][18] != ''){
                $questao['tema_id'] = $dados['linha'][18];
            }

            /// instacia o request
            $requestLote = new QuestaoRequest();
            /// popula o request com os dados a serem validados
            $requestLote->replace($questao);
            /// realiza a validação
            $csv_errors = Validator::make($questao, $requestLote->rules(), $requestLote->messages(), $requestLote->attributes())->errors();
            /// se teve erro
            if ($csv_errors->any()) {
                $log=[
                    'importacao_id' => $dados['arquivo_id'],
                    'linha' => $dados['numero_linha'],
                    'erro' => 1,
                    'erro_validacao' => serialize($csv_errors),
                ];
                $erro++;
            }else{
                try{
                    $questao = new Questao($questao);
                    $questao->save();
                    $log=[
                        'importacao_id' => $dados['arquivo_id'],
                        'linha' => $dados['numero_linha'],
                        'erro' => 0,
                    ];
                }catch(\Exception $e){
                    $erro++;
                    $log=[
                        'importacao_id' => $dados['arquivo_id'],
                        'linha' => $dados['numero_linha'],
                        'erro' => 1,
                        'erro_banco' => $e->getMessage(),
                    ];
                }

            }
        }
        $log = new ImportaLogQuestao($log);
        $log->save();
        if($erro == 0){
            ImportaQuestao::find($dados['arquivo_id'])
                ->increment('qtd_linhas_certas');
        }else{
            ImportaQuestao::find($dados['arquivo_id'])
                ->increment('qtd_linhas_erros');
        }
    }
}
