@extends('layouts.admin')

@section('content')
    <div class="p-6 bg-slate-900 rounded-lg max-w-3xl mx-auto shadow-lg">
        <h1 class="text-2xl font-bold text-white mb-6 text-center">ویرایش بلاگ</h1>

        <form action="{{ route('admin.blog.update', $blog->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- دسته‌بندی --}}
            <div>
                <label class="text-white block mb-2 font-medium">دسته‌بندی</label>
                <select name="category_id" class="w-full p-3 rounded-lg bg-slate-800 text-white border border-slate-700 focus:outline-none focus:ring-2 focus:ring-yellow-400 transition" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $blog->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- عنوان --}}
            <div>
                <label class="text-white block mb-2 font-medium">عنوان</label>
                <input type="text" name="title" class="w-full p-3 rounded-lg bg-slate-800 text-white border border-slate-700 focus:outline-none focus:ring-2 focus:ring-yellow-400 transition" value="{{ $blog->title }}" required>
            </div>

            {{-- نویسنده --}}
            <div>
                <label class="text-white block mb-2 font-medium">نویسنده</label>
                <input type="text" name="author" class="w-full p-3 rounded-lg bg-slate-800 text-white border border-slate-700 focus:outline-none focus:ring-2 focus:ring-yellow-400 transition" value="{{ $blog->author }}" required>
            </div>

            {{-- محتوا --}}
            <div>
                <label class="text-white block mb-2 font-medium">محتوا</label>
                <textarea name="content" class="w-full p-3 rounded-lg bg-slate-800 text-white border border-slate-700 focus:outline-none focus:ring-2 focus:ring-yellow-400 transition h-48" required>{{ $blog->content }}</textarea>
            </div>

            {{-- تصویر --}}
            <div>
                <label class="text-white block mb-2 font-medium">تصویر</label>
                <input type="file" name="image" class="w-full text-white rounded-lg bg-slate-800 border border-slate-700 p-2 file:bg-yellow-500 file:text-white file:rounded-lg file:px-3 file:py-1 hover:file:bg-yellow-600 transition">

                @if($blog->image)
                    <img src="{{ asset('storage/'.$blog->image) }}" class="w-32 mt-3 rounded shadow-md" alt="تصویر بلاگ">
                @endif
            </div>

            {{-- دکمه ارسال --}}
            <div class="text-center">
                <button type="submit" class="px-6 py-3 bg-yellow-500 text-white font-semibold rounded-lg hover:bg-yellow-600 focus:ring-2 focus:ring-yellow-400 transition">
                    ویرایش بلاگ
                </button>
            </div>
        </form>
    </div>
@endsection
