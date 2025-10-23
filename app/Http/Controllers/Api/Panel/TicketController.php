<?php

namespace App\Http\Controllers\Api\Panel;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        $user = auth()->user()->id;
        $ticket = Ticket::where('user_id', $user)->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'issue_type' => $item->issue_type,
                'status' => $item->status,
                'time' => $item->created_at->format('H:i'),
            ];
        });
        return api_response($ticket);
    }
    public  function show($id)
    {
        $ticket = Ticket::find($id);
        $a = $ticket->messages->map(function ($item) {
            return [
                'id' => $item->id,
                'message' => $item->message_text,
                'sender_type' => $item->sender_type,
                'created_at' => $item->created_at,
            ];
        });
        return api_response($a);


    }
    public function store(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'message_text' => 'required',
            'issue_type' => 'required',
        ]);
        $ticket = Ticket::create([
            'user_id' => $user->id,
            'issue_type' => $request->issue_type,

        ]);
        Message::create([
            'ticket_id' => $ticket->id,
            'message_text' => $request->message_text,
            'sender_type' => 'user',
        ]);
        return api_response($ticket);
    }
    public function update(Request $request, $id)
    {
        $ticket = Ticket::find($id);
        $user = auth()->user();
        $request->validate([
            'message_text' => 'required',
        ]);
        $ticket->messages()->create([
            'ticket_id' => $ticket->id,
            'message_text' => $request->message_text,
            'sender_type' => 'user',
        ]);
        return api_response($ticket);
    }
}
