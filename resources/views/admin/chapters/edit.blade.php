@extends('layouts.admin')

@section('content')
    <div class="max-w-3xl mx-auto p-6 bg-slate-800 rounded-xl shadow">
        <h1 class="text-xl font-bold text-white mb-6 text-center">
            ویرایش چپتر: {{ $chapter->title }}
        </h1>

        @if(session('success'))
            <p class="bg-green-500 text-white p-2 rounded text-center">{{ session('success') }}</p>
        @endif

        <form action="{{ route('admin.chapters.update', $chapter->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT') <!-- تغییر متد به PUT برای ویرایش -->

            <div>
                <label class="block text-slate-200 mb-1">شماره چپتر</label>
                <input type="number" name="chapter_number" value="{{ old('chapter_number', $chapter->chapter_number) }}" class="w-full p-2 rounded bg-slate-900 border border-slate-600 text-white">
            </div>

            <div>
                <label class="block text-slate-200 mb-1">عنوان چپتر</label>
                <input type="text" name="title" value="{{ old('title', $chapter->title) }}" class="w-full p-2 rounded bg-slate-900 border border-slate-600 text-white">
            </div>

            <div>
                <label class="block text-slate-200 mb-1">توضیحات</label>
                <textarea name="description" rows="3" class="w-full p-2 rounded bg-slate-900 border border-slate-600 text-white">{{ old('description', $chapter->description) }}</textarea>
            </div>

            <div>
                <label class="block text-slate-200 mb-1">تصویر</label>
                <input type="file" name="image" class="w-full p-2 rounded bg-slate-900 border border-slate-600 text-white">
                @if($chapter->image)
                    <p class="text-slate-400 text-sm mt-1">تصویر فعلی: <a href="{{ asset('storage/' . $chapter->image) }}" target="_blank" class="text-blue-400 underline">مشاهده تصویر</a></p>
                @endif
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_paid" value="1" id="is_paid" class="w-4 h-4" {{ $chapter->is_paid ? 'checked' : '' }}>
                <label for="is_paid" class="text-slate-200">این چپتر پولی است</label>
            </div>

            <div class="text-center">
                <button type="submit" class="px-6 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg shadow">
                    ذخیره تغییرات
                </button>
            </div>
        </form>
    </div>
@endsection
