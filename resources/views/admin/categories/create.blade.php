@extends('layouts.admin')

@section('content')
    <div class="max-w-lg mx-auto p-6 bg-slate-800 rounded-lg">
        <h1 class="text-xl text-white mb-4">➕ افزودن دسته‌بندی</h1>

        <form action="{{ route('admin.categories.store') }}"
              method="POST"
              enctype="multipart/form-data"
              class="space-y-4">
            @csrf

            <div>
                <label class="text-white block mb-2">نام دسته‌بندی</label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    class="w-full p-2 rounded bg-slate-900 text-white border border-slate-600"
                >

                @error('name')
                <p class="text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="text-white block mb-2">آیکون</label>

                <input
                    type="file"
                    name="icon"
                    accept="image/*"
                    class="w-full p-2 rounded bg-slate-900 text-white border border-slate-600"
                >

                @error('icon')
                <p class="text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button class="px-4 py-2 bg-indigo-500 rounded text-white">
                ثبت
            </button>
        </form>
    </div>
@endsection
