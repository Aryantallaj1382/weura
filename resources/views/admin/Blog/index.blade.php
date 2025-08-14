@extends('layouts.admin')

@section('content')
    <div class="p-6 bg-slate-800 rounded-lg">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-xl font-bold text-white">مدیریت بلاگ‌ها</h1>
            <a href="{{ route('admin.blog.create') }}" class="px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition">
                ایجاد بلاگ جدید
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($blogs as $blog)
                <div class="bg-slate-700 rounded-lg overflow-hidden relative group">
                    {{-- عکس بلاگ --}}
                    <img src="{{  asset($blog->image) }}" alt="{{ $blog->title }}" class="w-full h-48 object-cover">

                    {{-- اسم بلاگ --}}
                    <div class="p-4">
                        <h2 class="text-white font-semibold text-lg">{{ $blog->title }}</h2>
                    </div>

                    {{-- سه نقطه و منوی عملیات --}}
                    <div class="absolute top-2 right-2">
                        <div class="relative inline-block text-left">
                            <button class="text-white font-bold p-1 rounded hover:bg-slate-600 transition">•••</button>
                            <div class="hidden group-hover:block absolute right-0 mt-2 w-36 bg-slate-800 rounded shadow-lg z-10">
                                <a href="{{ route('admin.blog.show', $blog->id) }}" class="block px-4 py-2 text-sm text-white hover:bg-slate-600">نمایش</a>
                                <a href="{{ route('admin.blog.edit', $blog->id) }}" class="block px-4 py-2 text-sm text-white hover:bg-slate-600">ویرایش</a>
                                <form action="{{ route('admin.blog.destroy', $blog->id) }}" method="POST" onsubmit="return confirm('آیا مطمئن هستید؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-white hover:bg-slate-600">حذف</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- پیجینیشن --}}
        <div class="mt-6">
            {{ $blogs->links() }}
        </div>
    </div>
@endsection
