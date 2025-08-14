<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Message;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    // لیست همه تیکت‌ها
    public function index(Request $request)
    {
        $query = Ticket::with('user')->orderBy('created_at', 'desc');

        // اگر پارامتر status ارسال شده بود، فیلتر کن
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tickets = $query->paginate(10);

        return view('admin.tickets.index', compact('tickets'));
    }


    // نمایش یک تیکت خاص
    public function show($id)
    {
        $ticket = Ticket::with(['user', 'messages'])->findOrFail($id);
        return view('admin.tickets.show', compact('ticket'));
    }

    // ارسال پاسخ به تیکت
    public function reply(Request $request, $id)
    {
        $request->validate([
            'message_text' => 'required|string|max:5000',
        ]);

        $ticket = Ticket::findOrFail($id);

        Message::create([
            'ticket_id' => $ticket->id,
            'message_text' => $request->message_text,
            'sender_type' => 'support',
        ]);

//        $ticket->update(['status' => 'answered']);

        return redirect()->route('admin.tickets.show', $ticket->id)
            ->with('success', 'پاسخ شما ارسال شد.');
    }
}
