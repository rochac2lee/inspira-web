<?php

namespace App\Jobs;

use App\Services\Fr\ImportaUsuarioService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessaArquivoUsuario implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    private $linha;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $linha)
    {
        $this->linha = $linha;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $importa = new ImportaUsuarioService;

        if($this->linha['tipoArquivo'] == 1)
        {
            $dadosTratados = $importa->trataDadosTipoGoogle($this->linha['linha'], $this->linha['instituicao_id'], $this->linha['escola_id'], $this->linha['tipoUsuario'], $this->linha['numero_linha']);
        }

        $importa->ValidaInsereDeletaUsuario($dadosTratados, $this->linha['log_id']);
    }
}
