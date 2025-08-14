@extends('layouts.admin')

@section('content')
    <div class="p-6 space-y-6">
        <div class="flex items-center px-6 py-4 border-b border-slate-700 bg-slate-900 rounded-t-lg">
            <h1 class="text-lg font-bold text-white mr-auto">
            </h1>

            <div class="flex items-center gap-2">
                {{-- بازگشت --}}
                <a href="{{ route('admin.manhwa.show', $chapter->manhua->id) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition transform hover:scale-105">
                    بازگشت
                </a>

                {{-- ویرایش --}}
                <a href="{{ route('admin.chapters.edit', $chapter->id) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition transform hover:scale-105">
                    ویرایش
                </a>

                {{-- حذف --}}
                <form action="{{ route('admin.chapters.destroy', $chapter->id) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg shadow-sm transition transform hover:scale-105">
                        حذف
                    </button>
                </form>
            </div>
        </div>


        <div class="flex flex-col lg:flex-row gap-6 bg-white dark:bg-slate-800 rounded-lg shadow p-4">
            {{-- کاور مانهوا --}}
            <div class="w-full lg:w-1/4 flex justify-center items-center bg-gray-100 rounded-lg overflow-hidden">
                <img src="{{ asset($chapter->manhua->cover_image) }}"
                     alt="{{ $chapter->manhua->title }}"
                     class="max-w-full max-h-full object-contain">
            </div>

            {{-- اطلاعات چپتر --}}
            <div class="flex-1 space-y-2">
                <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">{{ $chapter->title }}</h1>
                <p class="text-slate-600 dark:text-slate-300">
                    شماره چپتر: {{ $chapter->chapter_number }}
                </p>
                <p class="text-slate-600 dark:text-slate-300">
                    وضعیت پرداخت: {{ $chapter->is_paid ? 'پرداخت شده' : 'رایگان' }}
                </p>
                <p class="text-slate-600 dark:text-slate-300">
                    مانهوا: {{ $chapter->manhua->title }}
                </p>
                @if($chapter->description)
                    <p class="text-slate-500 dark:text-slate-400 mt-2">{{ $chapter->description }}</p>
                @endif
            </div>
        </div>

        {{-- تصویر چپتر (در صورت وجود) --}}
        @if($chapter->image)
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-4 flex justify-center">
                <img src="{{ asset($chapter->image) }}" alt="{{ $chapter->title }}" class="max-w-full max-h-full object-contain">
            </div>
        @endif
    </div>
@endsection
