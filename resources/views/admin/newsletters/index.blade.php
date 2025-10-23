@extends('layouts.admin')

@section('title', 'خبرنامه')

@section('content')
    <div class="p-6 bg-gray-900 text-gray-200 rounded-lg shadow-lg">
        <h2 class="text-xl font-semibold mb-4">📧 لیست ایمیل‌های خبرنامه</h2>

        @if(session('success'))
            <div class="bg-green-700 text-green-100 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto rounded-lg">
            <table class="min-w-full table-auto text-left text-sm text-gray-200">
                <thead class="bg-gray-800">
                <tr>
                    <th class="px-4 py-3 font-semibold w-2/12">#</th>
                    <th class="px-4 py-3 font-semibold w-8/12">ایمیل</th>
                    <th class="px-4 py-3 font-semibold w-2/12 text-center">عملیات</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                @foreach($emails as $index => $email)
                    <tr class="hover:bg-gray-700 transition">
                        <td class="px-4 py-3">{{ $index + 1 }}</td>
                        <td class="px-4 py-3 break-words">{{ $email->email }}</td>
                        <td class="px-4 py-3 text-center">
                            <form action="{{ route('admin.newsletters.destroy', $email->id) }}" method="POST" onsubmit="return confirm('آیا مطمئن هستید؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 hover:bg-red-700 px-3 py-1 rounded text-white">
                                    حذف
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $emails->links() }}
        </div>
    </div>
@endsection
