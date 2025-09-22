@extends('layouts.admin')

@section('content')
    <div class="p-6">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-700 bg-slate-900">
            <h1 class="text-lg font-bold text-white">
                <h1 class="text-2xl font-bold text-white mb-6">👤 جزئیات کاربر: {{ $user->name }}</h1>
            </h1>

            <a href="{{ route('admin.users.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white text-sm font-medium rounded-lg shadow-sm transition transform hover:scale-105">
                ← بازگشت
            </a>


        </div>

        <div class="mb-6 p-4 bg-slate-800 rounded-lg shadow text-center">
            <h2 class="text-xl font-semibold text-white mb-2">💰 کیف پول</h2>
            <p class="text-slate-300">موجودی: {{ number_format($user->wallet->balance ?? 0) }} تومان</p>
        </div>


        <div class="overflow-x-auto">
            <h2 class="text-xl font-semibold text-white mb-2">📄 تراکنش‌ها</h2>
            <table class="w-full text-sm text-left text-slate-700 dark:text-slate-300">
                <thead class="bg-slate-900">
                <tr>
                    <th class="px-4 py-2 text-white">#</th>
                    <th class="px-4 py-2 text-white">نوع تراکنش</th>
                    <th class="px-4 py-2 text-white">مبلغ</th>
                    <th class="px-4 py-2 text-white">شماره کارت</th>
                    <th class="px-4 py-2 text-white">وضعیت</th>
                    <th class="px-4 py-2 text-white">تاریخ و ساعت</th>
                    <th class="px-4 py-2 text-white">تغییر وضعیت</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                @forelse($user->wallet->transactions ?? [] as $index => $transaction)
                    <tr class="hover:bg-slate-700 transition">
                        <td class="px-4 py-2 text-slate-200">{{ $index + 1 }}</td>
                        <td class="px-4 py-2 text-slate-200">
                            @if ($transaction->operation_type === 'deposit')
                                <span
                                    class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 dark:bg-green-800 dark:text-green-100 rounded-lg">واریز</span>
                            @else
                                <span
                                    class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 dark:bg-red-800 dark:text-red-100 rounded-lg">برداشت</span>
                            @endif</td>
                        <td class="px-4 py-2 text-slate-200">{{ number_format($transaction->amount) }} تومان</td>
                        <td class="px-4 py-2 text-slate-200">{{ $transaction->cart_number ?? '---'}} </td>
                        <td class="px-4 py-2 text-slate-200">
                            @if ($transaction->status === 'successful')
                                <span
                                    class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 dark:bg-green-800 dark:text-green-100 rounded-lg">موفق</span>
                            @elseif($transaction->status === 'pending')
                                <span
                                    class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 dark:bg-yellow-800 dark:text-yellow-100 rounded-lg">در انتظار ادمین</span>
                            @else
                                <span
                                    class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 dark:bg-red-800 dark:text-red-100 rounded-lg">ناموفق</span>
                                @endif
                                </span></td>
                        <td class="px-4 py-2 text-slate-400">
                            {{ \Morilog\Jalali\Jalalian::fromDateTime($transaction->created_at)->format('H:i - Y/m/d ') }}
                        </td>
                        <td class="px-4 py-2 text-slate-200">
                            <form method="POST" action="{{ route('admin.transactions.updateStatus', $transaction->id) }}">
                                @csrf
                                @method('PATCH')

                                <select name="status" onchange="this.form.submit()"
                                        class="text-sm rounded-lg px-2 py-1 border border-slate-700 bg-slate-800 text-white focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                    <option value="successful" {{ $transaction->status === 'successful' ? 'selected' : '' }}>✅ موفق</option>
                                    <option value="pending" {{ $transaction->status === 'pending' ? 'selected' : '' }}>⏳ در انتظار</option>
                                    <option value="failed" {{ $transaction->status === 'failed' ? 'selected' : '' }}>❌ ناموفق</option>
                                </select>
                            </form>
                        </td>

                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-2 text-center text-slate-400">تراکنشی وجود ندارد.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection
