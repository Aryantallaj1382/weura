@extends('layouts.admin')

@section('content')
    <div class="p-6">

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">📚 تمام مانهواها</h1>

            <a href="{{ route('admin.manhwa.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white text-sm font-medium rounded-lg shadow-sm transition transform hover:scale-105">
                ایجاد کتاب جدید
            </a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2  lg:grid-cols-3 gap-6">
            @foreach($manhwas as $manhwa)

                <a href="{{ route('admin.manhwa.show', $manhwa->id) }}" class="block bg-white hover:bg-gray-700 dark:bg-slate-800 rounded-lg shadow hover:shadow-lg transition overflow-hidden">
                    <img src="{{  asset($manhwa->cover_image) }}" alt="{{ $manhwa->title }}" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h2 class="text-lg font-semibold text-slate-800 dark:text-slate-100">{{ $manhwa->title }}</h2>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $manhwas->links() }}
        </div>
    </div>
@endsection
