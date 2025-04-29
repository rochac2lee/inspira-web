<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Swift_Mailer;
use Swift_SmtpTransport;

class SendEmailService {

    public static function contatoEmail($data)
    {
        return self::sendEmail([
            'template' => 'mail.contato',
            'data'  => $data,
            'from' => [
                'email' => config('app.MAIL_FROM_ADDRESS'),
                'name' => config('app.MAIL_FROM_NAME'),
            ],
            'subject' => 'Contato via plataforma Opet INspira'
        ]);
    }

    public static function sendEmailGmail($data){
        $dataArr = [
            'template' => 'mail.contato',
            'data'  => $data,
            'from' => [
                'email' => config('app.MAIL_FROM_ADDRESS'),
                'name' => config('app.MAIL_FROM_NAME'),
            ],
            'subject' => 'Contato via plataforma Opet INspira'
        ];

        $backup = Mail::getSwiftMailer();

        $transport = new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl');
        $transport->setUsername('naoresponda@opeteducation.com.br');
        //$transport->setPassword('qzsgbwgrzjhkdern'); /// senha oficial do email
        $transport->setPassword('krewcuzaimaocjro'); /// senha do APP

        $gmail = new Swift_Mailer($transport);

        Mail::setSwiftMailer($gmail);
        try
        {
            $data = $dataArr['data'];
            $data['from_email'] = $dataArr['from']['email'];
            $data['from_name'] = $dataArr['from']['name'];
            $data['subject'] = $dataArr['subject'];
            Mail::send($dataArr['template'], $data, function ($message) use ($data) {
                $message->from($data['email_contato'], $data['nome']);
                $message->to($data['email'], 'Contato OpetINspira')->subject($data['subject']);
            });

            if(Mail::failures())
            {
                Mail::setSwiftMailer($backup);
                return false;
            }
            else
            {
                Mail::setSwiftMailer($backup);
                return true;
            }
        }
        catch(\Exception $e)
        {
            Mail::setSwiftMailer($backup);
            return false;
        }
    }

    private static function sendEmail($dataArr)
    {
        return false;
        try
        {
            $data = $dataArr['data'];
            $data['from_email'] = $dataArr['from']['email'];
            $data['from_name'] = $dataArr['from']['name'];
            $data['subject'] = $dataArr['subject'];

            Mail::send($dataArr['template'], $data, function ($message) use ($data) {
                $message->from($data['from_email'], $data['from_name']);
                $message->to($data['email'], $data['name'])->subject($data['subject']);
            });

            if(Mail::failures())
            {
                return false;
            }
            else
            {
                return true;
            }
        }
        catch(\Exception $e)
        {
            return false;
        }
    }

}
