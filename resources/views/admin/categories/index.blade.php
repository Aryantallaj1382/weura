@extends('layouts.admin')

@section('content')
    <div class="p-6 space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-xl font-bold text-white">📂 مدیریت دسته‌بندی‌ها</h1>
            <a href="{{ route('admin.categories.create') }}" class="px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg">➕ افزودن</a>
        </div>

        @if(session('success'))
            <p class="bg-green-500 text-white p-2 rounded">{{ session('success') }}</p>
        @endif

        <table class="min-w-full border border-slate-600 rounded-lg overflow-hidden">
            <thead>
            <tr class="bg-slate-800 text-white">
                <th scope="col" class="px-4 py-2 text-center">#</th>
                <th scope="col" class="px-4 py-2 text-center">نام دسته‌بندی</th>
                <th scope="col" class="px-4 py-2 text-center">عملیات</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-700 bg-slate-900">
            @foreach($categories as $index => $category)
                <tr class="hover:bg-slate-700 transition">
                    <td class="px-4 py-2 text-center">{{ $categories->firstItem() + $index }}</td>
                    <td class="px-4 py-2 text-center">{{ $category->name }}</td>
                    <td class="px-4 py-2 flex justify-center gap-2">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="px-3 py-1 bg-yellow-500 rounded hover:bg-yellow-600">ویرایش</a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('حذف شود؟')">
                            @csrf
                            @method('DELETE')
                            <button class="px-3 py-1 bg-red-500 rounded hover:bg-red-600">حذف</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $categories->links() }}
    </div>
@endsection
