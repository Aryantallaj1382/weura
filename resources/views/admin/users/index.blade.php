@extends('layouts.admin')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold text-white mb-6">👥 لیست کاربران</h1>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-700 dark:text-slate-300">
                <thead class="bg-slate-900">
                <tr>
                    <th class="w-1/12 px-4 py-3 text-left text-sm font-semibold text-white">#</th>
                    <th class="w-3/12 px-4 py-3 text-left text-sm font-semibold text-white">نام</th>
                    <th class="w-4/12 px-4 py-3 text-left text-sm font-semibold text-white">ایمیل</th>
                    <th class="w-4/12 px-4 py-3 text-left text-sm font-semibold text-white">تاریخ ثبت</th>
                    <th class="w-4/12 px-4 py-3 text-left text-sm font-semibold text-white">نمایش</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                @foreach($users as $index => $user)
                    <tr class="hover:bg-slate-700 transition">
                        <td class="px-4 py-3 text-sm text-slate-200">{{ $index + 1 }}</td>
                        <td class="px-4 py-3 text-sm text-slate-200 break-words">{{ $user->name }}</td>
                        <td class="px-4 py-3 text-sm text-slate-200 break-words">{{ $user->email }}</td>
                        <td class="px-4 py-3 text-sm text-slate-400 whitespace-nowrap">
                            {{ \Morilog\Jalali\Jalalian::fromDateTime($user->created_at)->format('Y/m/d') }}
                        </td>
                        <td class="px-4 py-3 text-sm text-slate-200 break-words">
                            <a href="{{ route('admin.users.show', $user->id) }}" class="text-indigo-400 hover:underline">
                               جزئیات
                            </a>
                        </td>

                    </tr>
                @endforeach
                </tbody>
            </table>


        </div>
        <div class="mt-6">
            {{ $users->links() }}
        </div>

    </div>
@endsection
