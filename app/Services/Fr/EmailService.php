<?php

namespace App\Services\Fr;

use Auth;
use Mail;

use App\Models\User;
use App\Models\Notificacao;
use App\Helpers;

class EmailService {

    private static function sendEmail($dataArr)
    {

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

    public static function newUser($data)
    {
        return self::sendEmail([
            'template' => 'mail.new-user',
            'data'  => $data,
            'from' => [
                'email' => 'naoresponda@opetinspira.com.br',
                'name'  => 'Inspira Plataforma Educacional'
            ],
            'subject' => 'Cadastro efetuado com sucesso!'
        ]);
    }


}
