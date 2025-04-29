<?php

namespace App\Jobs\PushAgenda;

use App\Services\Fr\Agenda\PushNotificationService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendNotificacao implements ShouldQueue
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
        $notificacao = new PushNotificationService;

        $notificacao->sendNotificacao($this->dados);
    }
}
