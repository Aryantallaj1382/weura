@extends('layouts.admin')

@section('content')
    <div class="max-w-lg mx-auto p-6 bg-slate-800 rounded-lg">
        <h1 class="text-xl text-white mb-4">✏️ ویرایش دسته‌بندی</h1>

        <form action="{{ route('admin.categories.update', $category) }}"
              method="POST"
              enctype="multipart/form-data"
              class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="text-white block mb-2">نام دسته‌بندی</label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name', $category->name) }}"
                    class="w-full p-2 rounded bg-slate-900 text-white border border-slate-600"
                >

                @error('name')
                <p class="text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="text-white block mb-2">آیکون فعلی</label>

                @if($category->icon)
                    <img
                        src="{{ asset($category->icon) }}"
                        alt="{{ $category->name }}"
                        class="w-24 h-24 object-cover rounded-lg border border-slate-600 mb-3"
                    >
                @else
                    <p class="text-slate-400 mb-3">آیکونی ثبت نشده است.</p>
                @endif

                <input
                    type="file"
                    name="icon"
                    accept="image/*"
                    class="w-full p-2 rounded bg-slate-900 text-white border border-slate-600"
                >

                <p class="text-slate-400 text-sm mt-1">
                    در صورت نیاز، آیکون جدید انتخاب کنید.
                </p>

                @error('icon')
                <p class="text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button class="px-4 py-2 bg-indigo-500 rounded text-white">
                ذخیره تغییرات
            </button>
        </form>
    </div>
@endsection
