<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Chat;
use Illuminate\Support\Str;
use App\Services\OllamaService;
use App\Jobs\ProcessChatMessage;

class ChatbotController extends Controller
{
    protected $ollamaService;

    public function __construct(OllamaService $ollamaService)
    {
        $this->ollamaService = $ollamaService;
    }

    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        $userId = Auth::id() ?? 'guest';
        $answer = $this->ollamaService->sendMessage($validated['message']);

        // Fallback detection
        $isFallback = false;
        if (str_contains(strtolower($answer), 'sorry') || str_contains(strtolower($answer), "don't understand")) {
            $isFallback = true;
        }

        // Explicit user request for support
        $explicitRequest = false;
        $msgLower = strtolower(trim($validated['message']));
        if ($msgLower === 'create ticket' || $msgLower === 'support') {
            $explicitRequest = true;
        }

        $shouldCreateTicket = ($isFallback || $explicitRequest) && Auth::check();
        $ticket = null;
        if ($shouldCreateTicket) {
            $ticket = \App\Models\Ticket::create([
                'user_id' => $userId,
                'message' => $validated['message'],
                'rasa_response' => null,
                'status' => 'open',
                'answer' => $answer,
            ]);
        }

        $conversationId = $request->input('conversation_id') ?? (string) Str::uuid();
        // Store user message
        Chat::create([
            'user_id' => $userId,
            'sender' => 'user',
            'message' => $validated['message'],
            'conversation_id' => $conversationId,
        ]);
        // Store bot answer
        Chat::create([
            'user_id' => $userId,
            'sender' => 'bot',
            'message' => $answer,
            'conversation_id' => $conversationId,
        ]);
        return response()->json([
            'success' => true,
            'data' => [
                'answer' => $answer,
                'ticket_created' => $ticket ? true : false,
                'ticket_id' => $ticket?->id,
                'conversation_id' => $conversationId,
            ]
        ]);
    }

    public function getChatHistory(Request $request)
    {
        $userId = Auth::id();
        $conversationId = $request->input('conversation_id');
        if ($conversationId && !Str::isUuid($conversationId)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid conversation_id.',
                'data' => null
            ], 422);
        }
        $query = Chat::where('user_id', $userId);
        if ($conversationId) {
            $query->where('conversation_id', $conversationId);
        }
        $chats = $query->orderBy('created_at')->get();
        return response()->json([
            'success' => true,
            'data' => ['chats' => $chats]
        ]);
    }
}
