@extends('layouts.admin')

@section('content')
    <div class="p-6 space-y-6">
        {{-- اطلاعات مانهوا --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-700 bg-slate-900 rounded-t-lg">
            <h1 class="text-lg font-bold text-white">
                <span class="text-indigo-400">{{ $manhwa->title }}</span>
            </h1>
            <div class="flex items-center gap-2">
                {{-- حذف --}}
                <form action="{{ route('admin.manhwa.destroy', $manhwa->id) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white text-sm font-medium rounded-2xl shadow-md transition transform hover:scale-105">
                        حذف
                    </button>
                </form>

                {{-- ویرایش --}}
                <a href="{{ route('admin.manhwa.edit', $manhwa->id) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white text-sm font-medium rounded-2xl shadow-md transition transform hover:scale-105">
                    ویرایش
                </a>

                {{-- چپتر جدید --}}
                <a href="{{ route('admin.chapters.create', $manhwa->id) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 text-white text-sm font-medium rounded-2xl shadow-md transition transform hover:scale-105">
                    چپتر جدید
                </a>

                {{-- بازگشت --}}
                <a href="{{ route('admin.manhwa.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white text-sm font-medium rounded-2xl shadow-md transition transform hover:scale-105">
                    بازگشت
                </a>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-6 bg-white dark:bg-slate-800 rounded-b-lg shadow p-6">
            <!-- تصویر -->
            <div class="w-full lg:w-1/3 flex justify-center items-center bg-gray-100 rounded-lg overflow-hidden">
                <img src="{{ asset($manhwa->cover_image) }}"
                     alt="{{ $manhwa->title }}"
                     class="max-w-full max-h-full object-contain">
            </div>

            <!-- توضیحات -->
            <div class="flex-1 space-y-4">
                <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">{{ $manhwa->title }}</h1>
                <p class="text-slate-600 dark:text-slate-300 line-clamp-5">{{ $manhwa->summary }}</p>
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    نویسنده: {{ $manhwa->author->name ?? '-' }} | هنرمند: {{ $manhwa->artist->name ?? '-' }}
                </p>
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    تعداد چپترها: {{ $manhwa->chapters->count() }}
                </p>
            </div>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                دسته‌بندی‌ها:
                @if($manhwa->categories->count())
                    @foreach($manhwa->categories as $category)
                        <span class="inline-block bg-indigo-500 text-white px-2 py-1 rounded-full text-xs mr-1">
                    {{ $category->name }}
                </span>
                    @endforeach
                @else
                    <span class="text-slate-400">ندارد</span>
                @endif
            </p>
        </div>



        {{-- لیست چپترها --}}
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-4">
            <h2 class="text-xl font-semibold text-slate-800 dark:text-slate-100 mb-4">📖 لیست چپترها</h2>
            @if($manhwa->chapters->count())
                <table class="w-full text-sm text-left text-slate-700 dark:text-slate-300">
                    <thead class="bg-slate-900">
                    <tr>
                        <th class="px-4 py-2 text-white">#</th>
                        <th class="px-4 py-2 text-white">عنوان چپتر</th>
                        <th class="px-4 py-2 text-white">شماره چپتر</th>
                        <th class="px-4 py-2 text-white">وضعیت پرداخت</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700">
                    @foreach($manhwa->chapters as $index => $chapter)
                        <tr onclick="window.location='{{ route('admin.chapter.show', $chapter->id) }}'" class="hover:bg-slate-700 transition cursor-pointer">
                            <td class="px-4 py-2 text-slate-200">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 text-slate-200">{{ $chapter->title }}</td>
                            <td class="px-4 py-2 text-slate-200">{{ $chapter->chapter_number }}</td>
                            <td class="px-4 py-2 text-slate-200">
                                {{ $chapter->is_paid ? 'پولی' : 'رایگان' }}
                            </td>
                        </tr>

                    @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-slate-400 text-center py-4">چپتری موجود نیست.</p>
            @endif
        </div>
    </div>
@endsection
