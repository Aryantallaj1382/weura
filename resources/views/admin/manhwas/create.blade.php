@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl mx-auto p-6 space-y-6 bg-slate-800 rounded-2xl shadow">
        <h1 class="text-2xl font-bold text-white text-center">➕ افزودن کتاب جدید</h1>

        {{-- پیام موفقیت --}}
        @if(session('success'))
            <p class="bg-green-500 text-white p-2 rounded text-center">{{ session('success') }}</p>
        @endif

        {{-- پیام خطا --}}
        @if($errors->any())
            <div class="bg-red-500 text-white p-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.manhwa.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- عنوان --}}
                <div>
                    <label class="block mb-1 text-slate-200 font-medium">عنوان</label>
                    <input type="text" name="title" value="{{ old('title') }}"
                           class="w-full p-2 rounded-lg border border-slate-600 bg-slate-900 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                {{-- وضعیت --}}
                <div>
                    <label class="block mb-1 text-slate-200 font-medium">وضعیت</label>
                    <select name="status"
                            class="w-full p-2 rounded-lg border border-slate-600 bg-slate-900 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">انتخاب وضعیت</option>
                        <option value="ongoing" {{ old('status') === 'ongoing' ? 'selected' : '' }}>منتشر شده</option>
                        <option value="ongoing" {{ old('status') === 'ongoing' ? 'selected' : '' }}>پیش‌نویس</option>
                    </select>
                </div>

                {{-- خلاصه --}}
                <div class="md:col-span-2">
                    <label class="block mb-1 text-slate-200 font-medium">خلاصه</label>
                    <textarea name="summary" rows="3"
                              class="w-full p-2 rounded-lg border border-slate-600 bg-slate-900 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('summary') }}</textarea>
                </div>

                {{-- نویسنده --}}
                <div>
                    <label class="block mb-1 text-slate-200 font-medium">نویسنده</label>
                    <select name="author_id"
                            class="w-full p-2 rounded-lg border border-slate-600 bg-slate-900 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">انتخاب نویسنده</option>
                        @foreach(\App\Models\User::all() as $user)
                            <option value="{{ $user->id }}" {{ old('author_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>



                {{-- هنرمند --}}
                <div>
                    <label class="block mb-1 text-slate-200 font-medium">هنرمند</label>
                    <select name="artist_id"
                            class="w-full p-2 rounded-lg border border-slate-600 bg-slate-900 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">انتخاب هنرمند</option>
                        @foreach(\App\Models\User::all() as $user)
                            <option value="{{ $user->id }}" {{ old('artist_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- دسته‌بندی‌ها --}}
                <div class="flex-1">
                    <label class="block mb-1 text-slate-200 font-medium">دسته‌بندی‌ها</label>
                    <select id="categories" name="categories[]" multiple
                            class="w-full rounded-lg border border-slate-600 bg-slate-900 text-white">
                        @foreach(\App\Models\Category::all() as $category)
                            <option value="{{ $category->id }}" {{ collect(old('categories'))->contains($category->id) ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-slate-400 mt-1">می‌توانید چند دسته‌بندی انتخاب کنید یا جستجو کنید.</p>
                </div>

                {{-- استایل و اسکریپت Tom Select --}}
                @push('styles')
                    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
                @endpush

                @push('scripts')
                    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            new TomSelect("#categories", {
                                plugins: ['remove_button'],
                                placeholder: "دسته‌بندی‌ها را انتخاب کنید...",
                                persist: false,
                                create: false,
                                sortField: {field: "text", direction: "asc"}
                            });
                        });
                    </script>
                @endpush

                {{-- کاور --}}
                <div class="md:col-span-2">
                    <label class="block mb-1 text-slate-200 font-medium">کاور</label>
                    <input type="file" name="cover_image"
                           class="w-full p-2 rounded-lg border border-slate-600 bg-slate-900 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            {{-- دکمه ذخیره --}}
            <div class="text-center">
                <button type="submit"
                        class="px-6 py-2 mt-4 bg-indigo-500 hover:bg-indigo-600 text-white font-semibold rounded-lg shadow transition">
                    ذخیره کتاب
                </button>
            </div>
        </form>
    </div>
@endsection
