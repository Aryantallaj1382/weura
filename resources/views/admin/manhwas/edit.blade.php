@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl mx-auto p-6 space-y-6 bg-slate-800 rounded-2xl shadow">
        <h1 class="text-2xl font-bold text-white text-center">ویرایش کتاب: {{ $manhwa->title }}</h1>

        @if(session('success'))
            <p class="bg-green-500 text-white p-2 rounded text-center">{{ session('success') }}</p>
        @endif

        <form action="{{ route('admin.manhwa.update', $manhwa->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1 text-slate-200 font-medium">عنوان</label>
                    <input type="text" name="title" value="{{ old('title', $manhwa->title) }}"
                           class="w-full p-2 rounded-lg border border-slate-600 bg-slate-900 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block mb-1 text-slate-200 font-medium">وضعیت</label>
                    <select name="status"
                            class="w-full p-2 rounded-lg border border-slate-600 bg-slate-900 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="ongoing" {{ $manhwa->status === 'ongoing' ? 'selected' : '' }}>منتشر شده</option>
                        <option value="ongoing" {{ $manhwa->status === 'ongoing' ? 'selected' : '' }}>پیش‌نویس</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block mb-1 text-slate-200 font-medium">خلاصه</label>
                    <textarea name="summary" rows="3"
                              class="w-full p-2 rounded-lg border border-slate-600 bg-slate-900 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('summary', $manhwa->summary) }}</textarea>
                </div>

                <div>
                    <label class="block mb-1 text-slate-200 font-medium">نویسنده</label>
                    <select name="author_id"
                            class="w-full p-2 rounded-lg border border-slate-600 bg-slate-900 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">انتخاب نویسنده</option>
                        @foreach(\App\Models\User::all() as $user)
                            <option value="{{ $user->id }}" {{ $manhwa->author_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block mb-1 text-slate-200 font-medium">هنرمند</label>
                    <select name="artist_id"
                            class="w-full p-2 rounded-lg border border-slate-600 bg-slate-900 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">انتخاب هنرمند</option>
                        @foreach(\App\Models\User::all() as $user)
                            <option value="{{ $user->id }}" {{ $manhwa->artist_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- دسته‌بندی‌ها --}}
                <div class="md:col-span-2">
                    <label class="block mb-1 text-slate-200 font-medium">دسته‌بندی‌ها</label>
                    <select id="categories" name="categories[]" multiple
                            class="w-full rounded-lg border border-slate-600 bg-slate-900 text-white">
                        @foreach(\App\Models\Category::all() as $category)
                            <option value="{{ $category->id }}"
                                {{ $manhwa->categories->contains($category->id) ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-slate-400 mt-1">می‌توانید چند دسته‌بندی انتخاب کنید، انتخاب‌ها به بالا منتقل می‌شوند.</p>
                </div>

                @push('styles')
                    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
                    <style>
                        /* هماهنگ‌سازی با تم تیره */
                        .ts-control {
                            background-color: #0f172a !important; /* slate-900 */
                            border-color: #475569 !important; /* slate-600 */
                            color: white !important;
                        }
                        .ts-dropdown {
                            background-color: #1e293b !important; /* slate-800 */
                            color: white !important;
                        }
                        .ts-control .item {
                            background-color: #4f46e5; /* indigo-600 */
                            border-radius: 0.5rem;
                            padding: 0.25rem 0.5rem;
                            margin: 0.15rem;
                        }
                    </style>
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
                                sortField: {field: "text", direction: "asc"},
                                render: {
                                    option: function(data, escape) {
                                        return `<div>${escape(data.text)}</div>`;
                                    },
                                    item: function(data, escape) {
                                        return `<div class="bg-indigo-600 text-white rounded-lg px-2 py-1">${escape(data.text)}</div>`;
                                    }
                                }
                            });
                        });
                    </script>
                @endpush


                <div class="md:col-span-2">
                    <label class="block mb-1 text-slate-200 font-medium">کاور</label>
                    <input type="file" name="cover_image"
                           class="w-full p-2 rounded-lg border border-slate-600 bg-slate-900 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @if($manhwa->cover_image)
                        <img src="{{ asset($manhwa->cover_image) }}" alt="کاور" class="w-32 mt-2 rounded-lg border border-slate-600">
                    @endif
                </div>
            </div>

            <div class="text-center">
                <button type="submit"
                        class="px-6 py-2 mt-4 bg-indigo-500 hover:bg-indigo-600 text-white font-semibold rounded-lg shadow transition">ذخیره تغییرات</button>
            </div>

        </form>
    </div>
@endsection
