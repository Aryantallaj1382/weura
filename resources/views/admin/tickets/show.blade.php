@extends('layouts.admin')

@section('content')
    <div class="p-0 min-h-screen flex flex-col bg-gradient-to-b from-slate-900 to-slate-800">

        {{-- هدر --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-700 bg-slate-900">
            <h1 class="text-lg font-bold text-white">
                📨 گفت‌وگو با <span class="text-indigo-400">{{ $ticket->user->name }}</span>
            </h1>
            <span class="text-sm text-slate-400">
            وضعیت:
            @if ($ticket->status === 'open')
                    <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 dark:bg-green-800 dark:text-green-100 rounded-lg">باز</span>
                @else
                    <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 dark:bg-red-800 dark:text-red-100 rounded-lg">بسته</span>
                @endif
        </span>
            <a href="{{ route('admin.tickets.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white text-sm font-medium rounded-lg shadow-sm transition transform hover:scale-105">
                بازگشت
            </a>
        </div>

        {{-- پیام‌ها (اسکرول) --}}
        <div class="flex-1 overflow-y-auto px-4 py-6 space-y-4">
            @foreach ($ticket->messages as $message)
                @if ($message->sender_type == 'support')
                    <div class="flex items-end gap-2">
                        <div class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center text-white text-sm shadow-md">A</div>
                        <div class="max-w-md bg-indigo-600 text-white p-3 rounded-xl shadow-md break-words">
                            <p class="text-sm leading-relaxed">{{ $message->message_text }}</p>
                            <span class="block text-[10px] text-slate-200 opacity-70 mt-1">
                            {{ \Morilog\Jalali\Jalalian::fromDateTime($message->created_at)->format('H:i') }}
                        </span>
                        </div>
                    </div>
                @else
                    <div class="flex items-end gap-2 justify-end">
                        <div class="max-w-md bg-slate-700 text-white p-3 rounded-xl shadow-md break-words">
                            <p class="text-sm leading-relaxed">{{ $message->message_text }}</p>
                            <span class="block text-[10px] text-slate-300 opacity-70 mt-1 text-right">
                            {{ \Morilog\Jalali\Jalalian::fromDateTime($message->created_at)->format('H:i') }}
                        </span>
                        </div>
                        <div class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center text-white text-sm shadow-md">U</div>
                    </div>
                @endif
            @endforeach
        </div>

        {{-- فرم ارسال پیام (ثابت پایین صفحه) --}}
        <form action="{{ route('admin.tickets.reply', $ticket->id) }}" method="POST"
              class="sticky bottom-0 flex items-center gap-2 px-4 py-3 border-t border-slate-700 bg-slate-900">
            @csrf
            <textarea name="message_text"
                      class="flex-1 p-3 rounded-lg bg-slate-800 text-white placeholder-slate-400 border border-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"
                      rows="1"
                      placeholder="پیام خود را بنویسید..."></textarea>
            <button type="submit"
                    class="px-5 py-2 bg-indigo-500 hover:bg-indigo-600 text-white font-medium rounded-lg shadow-lg transition transform hover:scale-105">
                ارسال
            </button>
        </form>
    </div>
@endsection
