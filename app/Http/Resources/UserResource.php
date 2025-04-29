<?php

namespace App\Http\Resources;

use App\Exceptions\LoginInvalidException;
use App\Services\Api\AgendaService;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * @var AgendaService
     */
    private $agendaService;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {

        $this->agendaService = new AgendaService();
        $permissoes = $this->agendaService->listaPermissao();

        $deviceKey = '';
        $notificacaoDevice = '';
        if($request->input('tipo')=='agenda'){
            $permissoes = $this->restricaoAluno($permissoes);
            $deviceKey = $this->device_key_agenda;
            $notificacaoDevice = (int) $this->notificacao_ativa_agenda;
        }

        return [
            'nome'          =>  $this->name,
            'nome_completo' =>  $this->nome_completo,
            'email'         =>  $this->email,
            'avatar'        =>  auth('api')->user()->avatar,
            'permissoes'    =>  $permissoes,
            'device_key'    =>  $deviceKey,
            'notificacao_device'    =>  $notificacaoDevice,
        ];
    }

    private function restricaoAluno($per){
        $permissoes = [];
        foreach ($per as $p){
            if($p['permissao'] != 'A'){
                $permissoes[] = $p;
            }
        }
        if(count($permissoes)==0){
            throw new LoginInvalidException();
        }
        return $permissoes;
    }

}
