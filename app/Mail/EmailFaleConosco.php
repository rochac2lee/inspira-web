<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailFaleConosco extends Mailable
{
    use Queueable, SerializesModels;

    public $dados;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($dados)
    {
        $this->dados = $dados;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Contato via plataforma Opet INspira')
            ->to('atendimentote@opeteducation.com.br', 'Atendimento')
            ->from('plataforma@opetinspira.com.br','Contato via plataforma Opet INspira')
            ->view('mail.contato',$this->dados);
    }
}
