@extends('layouts.admin')

@section('content')
    <div class="p-6 bg-slate-900 rounded-lg max-w-3xl mx-auto shadow-lg">
        {{-- عنوان بلاگ --}}
        <h1 class="text-3xl font-extrabold text-white mb-4 text-center">{{ $blog->title }}</h1>

        {{-- اطلاعات نویسنده و دسته‌بندی --}}
        <div class="flex flex-col sm:flex-row justify-between mb-4 text-slate-300 text-sm sm:text-base gap-2">
            <p><strong>نویسنده:</strong> {{ $blog->author }}</p>
            <p><strong>دسته‌بندی:</strong> {{ $blog->category->name }}</p>
        </div>

        {{-- تصویر بلاگ --}}
        @if($blog->image)
            <img src="{{ asset($blog->image) }}" class="w-full rounded-lg shadow-md mb-6 object-cover max-h-96" alt="تصویر بلاگ">
        @endif

        {{-- محتوای بلاگ --}}
        <div class="text-white leading-relaxed whitespace-pre-wrap mb-6 border-l-4 border-indigo-500 pl-4">
            {{ $blog->content }}
        </div>

        {{-- دکمه بازگشت --}}
        <div class="text-center">
            <a href="{{ route('admin.blog.index') }}" class="inline-block px-6 py-3 bg-indigo-500 text-white font-semibold rounded-lg hover:bg-indigo-600 focus:ring-2 focus:ring-indigo-400 transition">
                بازگشت به لیست بلاگ‌ها
            </a>
        </div>
    </div>
@endsection
