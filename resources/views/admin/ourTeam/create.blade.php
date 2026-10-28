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

        <form action="{{ route('admin.team.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- عنوان --}}
                <div>
                    <label class="block mb-1 text-slate-200 font-medium">اسم</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="w-full p-2 rounded-lg border border-slate-600 bg-slate-900 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block mb-1 text-slate-200 font-medium">نقش</label>
                    <input type="text" name="role" value="{{ old('role') }}"
                           class="w-full p-2 rounded-lg border border-slate-600 bg-slate-900 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>



                {{-- کاور --}}
                <div class="md:col-span-2">
                    <label class="block mb-1 text-slate-200 font-medium">کاور</label>
                    <input type="file" name="image"
                           class="w-full p-2 rounded-lg border border-slate-600 bg-slate-900 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            {{-- دکمه ذخیره --}}
            <div class="text-center">
                <button type="submit"
                        class="px-6 py-2 mt-4 bg-indigo-500 hover:bg-indigo-600 text-white font-semibold rounded-lg shadow transition">
                    ذخیره
                </button>
            </div>
        </form>
    </div>
@endsection
