<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;

class TicketController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $tickets = Ticket::where('user_id', Auth::id())->orderByDesc('created_at')->get();
        return response()->json([
            'success' => true,
            'data' => ['tickets' => $tickets]
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $ticket = Ticket::find($id);
        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket not found.',
                'data' => null
            ], 404);
        }
        if ($ticket->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'You are not allowed to view this ticket.',
                'data' => null
            ], 403);
        }
        return response()->json([
            'success' => true,
            'data' => $ticket
        ]);
    }
}
