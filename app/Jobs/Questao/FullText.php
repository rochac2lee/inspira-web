<?php

namespace App\Jobs\Questao;

use App\Services\Fr\QuestaoService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FullText implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    private $dados;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $dados)
    {
        $this->dados = $dados;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $service = new QuestaoService();
        $service->preparaFullText($this->dados['dados']);
    }
}
