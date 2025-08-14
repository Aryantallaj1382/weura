@extends('layouts.admin')

@section('content')
    <div class="p-6 bg-slate-900 rounded-lg max-w-3xl mx-auto shadow-lg">
        <h1 class="text-2xl font-bold text-white mb-6 text-center">ایجاد بلاگ جدید</h1>

        <form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            {{-- دسته‌بندی --}}
            <div>
                <label class="text-white block mb-2 font-medium">دسته‌بندی</label>
                <select name="category_id" class="w-full p-3 rounded-lg bg-slate-800 text-white border border-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- عنوان --}}
            <div>
                <label class="text-white block mb-2 font-medium">عنوان</label>
                <input type="text" name="title" class="w-full p-3 rounded-lg bg-slate-800 text-white border border-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" placeholder="عنوان بلاگ" required>
            </div>

            {{-- نویسنده --}}
            <div>
                <label class="text-white block mb-2 font-medium">نویسنده</label>
                <input type="text" name="author" class="w-full p-3 rounded-lg bg-slate-800 text-white border border-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" placeholder="نام نویسنده" required>
            </div>

            {{-- محتوا --}}
            <div>
                <label class="text-white block mb-2 font-medium">محتوا</label>
                <textarea name="content" class="w-full p-3 rounded-lg bg-slate-800 text-white border border-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition h-48" placeholder="متن بلاگ..." required></textarea>
            </div>

            {{-- تصویر --}}
            <div>
                <label class="text-white block mb-2 font-medium">تصویر</label>
                <input type="file" name="image" class="w-full text-white rounded-lg bg-slate-800 border border-slate-700 p-2 file:bg-indigo-500 file:text-white file:rounded-lg file:px-3 file:py-1 hover:file:bg-indigo-600 transition">
            </div>

            {{-- دکمه ارسال --}}
            <div class="text-center">
                <button type="submit" class="px-6 py-3 bg-indigo-500 text-white font-semibold rounded-lg hover:bg-indigo-600 focus:ring-2 focus:ring-indigo-400 transition">
                    ایجاد بلاگ
                </button>
            </div>
        </form>
    </div>
@endsection
