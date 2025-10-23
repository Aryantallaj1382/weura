@extends('layouts.admin')

@section('title', 'تنظیم پیشنهاد ویژه')

@section('content')
    <div class="max-w-lg mx-auto bg-gray-900 p-6 rounded-lg shadow-lg text-gray-200">
        <h2 class="text-2xl font-semibold mb-6 text-center text-gray-100">🔮 انتخاب مانهوآ پیشنهادی</h2>

        @if(session('success'))
            <div class="bg-green-700 text-green-100 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.suggested.update') }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="manhua_id" class="block mb-2 text-gray-300">انتخاب مانهوآ</label>
                <select name="manhua_id" id="manhua_id"
                        class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="">-- انتخاب کنید --</option>
                    @foreach($manhuas as $id => $title)
                        <option value="{{ $id }}" {{ $setting->value == $id ? 'selected' : '' }}>
                            {{ $title }}
                        </option>
                    @endforeach
                </select>
                @error('manhua_id')
                <span class="text-red-400 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg shadow-md transition">
                ذخیره
            </button>
        </form>
    </div>
@endsection
