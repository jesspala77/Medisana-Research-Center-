<?php

namespace App\Http\Controllers;

use App\Services\CommandQueueService;
use Illuminate\Http\Request;

class CommandQueueController extends Controller
{
    public function index(Request $request, CommandQueueService $service)
    {
        $queue = $service->getQueue();

        if ($request->expectsJson() || $request->query('format') === 'json') {
            return response()->json([
                'data' => $queue,
            ]);
        }

        return view('command-queue.index', [
            'queue' => $queue,
        ]);
    }
}
