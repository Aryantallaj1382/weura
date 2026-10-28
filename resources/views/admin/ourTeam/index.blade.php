@extends('layouts.admin')

@section('content')
    <div class="p-6">

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">📚 تمام تیم ما</h1>

            <a href="{{ route('admin.team.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white text-sm font-medium rounded-lg shadow-sm transition transform hover:scale-105">
                ایجاد
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2  lg:grid-cols-3 gap-6">
            @foreach($manhwas as $manhwa)
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <img src="{{ asset($manhwa->image) }}" alt="{{ $manhwa->name }}" class="w-full h-48 object-cover">
                    <div class="p-4 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-slate-800">{{ $manhwa->name }}</h2>

                        <!-- دکمه حذف -->
                        <form action="{{ route('admin.team.destroy', $manhwa->id) }}" method="POST" onsubmit="return confirm('آیا مطمئن هستید؟');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700">
                                حذف
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach

        </div>

        <div class="mt-6">
            {{ $manhwas->links() }}
        </div>
    </div>
@endsection
