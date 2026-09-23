<?php

namespace App\Http\Controllers;

use App\Services\CommandQueueService;

class CommandQueueController extends Controller
{
    public function index(CommandQueueService $service)
    {
        return response()->json([
            'data' => $service->getQueue(),
        ]);
    }
}
