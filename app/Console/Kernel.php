<?php

namespace App\Console;

use App\Models\Conteudo;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        $schedule->call(function () {
            $quiz = Conteudo::join('disciplinas','disciplinas.id','conteudos.disciplina_id')
                ->join('ciclo_etapas','ciclo_etapas.id','conteudos.cicloetapa_id')
                ->join('ciclos','ciclos.id','ciclo_etapas.ciclo_id')
                ->selectRaw('conteudos.id, disciplinas.titulo as disciplina, ciclo_etapas.titulo as etapa, ciclos.titulo as ciclo')
                ->where(function($q){
                    $q->orWhere('tipo', 102)
                        ->orWhere('tipo', 103)
                        ->orWhere('tipo', 21)
                        ->orWhere('tipo', 100)
                        ->orWhere('tipo', 4)
                        ->orWhere('tipo', 3)
                        ->orWhere('tipo', 2)
                        ->orWhere('tipo', 101);
                })
                ->whereNull('full_text')
                ->get();
            foreach ($quiz as $q){

                $dados['full_text'] = $q->disciplina.' '.$q->ciclo.' '.$q->etapa;
                $editar = Conteudo::find($q->id);
                $editar->update($dados);
            }
        })->everySixHours();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
