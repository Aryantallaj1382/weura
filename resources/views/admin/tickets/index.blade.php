@extends('layouts.admin')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100 mb-6">📩 لیست تیکت‌ها</h1>

        <div class="overflow-x-auto bg-white dark:bg-slate-800 rounded-xl shadow-lg">
            <div class="mb-4">
                <form method="GET" action="{{ route('admin.tickets.index') }}">
                    <select name="status"
                            onchange="this.form.submit()"
                            class="px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white">
                        <option value="">همه وضعیت‌ها</option>
                        <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>باز</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>بسته</option>
                    </select>
                </form>
            </div>


            <table class="w-full text-sm text-left text-slate-700 dark:text-slate-300">
                <thead class="bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-200">
                <tr>
                    <th class="px-4 py-3">کاربر</th>
                    <th class="px-4 py-3">موضوع</th>
                    <th class="px-4 py-3">وضعیت</th>
                    <th class="px-4 py-3">عملیات</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($tickets as $ticket)
                    <tr class="border-b border-slate-200 dark:border-slate-700  dark:hover:bg-slate-600 transition">
                        <td class="px-4 py-3 font-medium text-white">{{ $ticket->user->name }}</td>
                        <td class="px-4 py-3 font-medium text-white">{{ $ticket->issue_type }}</td>
                        <td class="px-4 py-3 font-medium text-white">
                            @if ($ticket->status === 'open')
                                <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 dark:bg-green-800 dark:text-green-100 rounded-lg">باز</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 dark:bg-red-800 dark:text-red-100 rounded-lg">بسته</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.tickets.show', $ticket->id) }}"
                               class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-indigo-500 hover:bg-indigo-600 rounded-lg shadow transition">
                                مشاهده
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $tickets->appends(request()->query())->links() }}
        </div>
    </div>
@endsection
