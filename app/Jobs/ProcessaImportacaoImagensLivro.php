<?php

namespace App\Jobs;

use App\Services\Fr\ImportaUsuarioService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;

class ProcessaImportacaoImagensLivro implements ShouldQueue
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
        Storage::copy($this->linha['de'], $this->linha['para']);
    }
}
