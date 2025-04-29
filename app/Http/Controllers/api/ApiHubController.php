<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;

use App\Services\Api\HubService;
use Illuminate\Http\Request;

class ApiHubController extends Controller
{
    public function __construct(HubService $hubService)
    {
        $this->middleware('auth:api');
        $this->hubService = $hubService;
    }

    public function categorias(){
        $cat = $this->hubService->categorias();
        return response()->json($cat);
    }

    public function disciplinas(){
        $cat = $this->hubService->disciplinas();
        return response()->json($cat);
    }
}
