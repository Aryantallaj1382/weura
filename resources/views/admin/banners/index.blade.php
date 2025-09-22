@extends('layouts.admin')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100 mb-6">🎬 مدیریت بنرها</h1>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Add Banner Button -->
        <button onclick="openAddModal()"
                class="mb-4 px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition">
            ➕ اضافه کردن بنر
        </button>

        <!-- Banner Table -->
        <div class="overflow-x-auto bg-white dark:bg-slate-800 rounded-xl shadow-lg">
            <table class="w-full text-left text-slate-800 dark:text-slate-100">
                <thead class="bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-200">
                <tr>
                    <th class="px-4 py-3">تصویر</th>
                    <th class="px-4 py-3">عنوان مانهوا</th>
                    <th class="px-4 py-3">عملیات</th>
                </tr>
                </thead>
                <tbody>
                @foreach($banners as $banner)
                    <tr class="border-b border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-600 transition">
                        <td class="px-4 py-3">
                            @if($banner->manhuas->cover_image)
                                <img src="{{ $banner->manhuas->cover_image }}" alt="{{ $banner->manhuas->title }}" class="w-20 h-20 object-cover rounded mx-auto">
                            @else
                                <span class="text-gray-500 dark:text-gray-300">بدون تصویر</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 font-medium">{{ $banner->manhuas->title }}</td>
                        <td class="px-4 py-3 space-x-2">
                            <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" onsubmit="return confirm('آیا مطمئن هستید؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded transition">حذف</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Modal -->
    <div id="addModal" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6 w-96 relative text-slate-800 dark:text-slate-100">
            <h2 class="text-xl font-bold mb-4">➕ اضافه کردن بنر</h2>
            <form action="{{ route('admin.banners.store') }}" method="POST">
                @csrf
                <label class="block mb-2">انتخاب مانهوا</label>
                <select name="manhwa_id" class="w-full mb-4 p-2 border rounded bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100" required>
                    <option value="">انتخاب مانهوا</option>
                    @foreach($manhwas as $manhwa)
                        <option value="{{ $manhwa->id }}">{{ $manhwa->title }}</option>
                    @endforeach
                </select>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeAddModal()" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded transition">انصراف</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded transition">ذخیره</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
            document.getElementById('addModal').classList.add('flex');
        }
        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
            document.getElementById('addModal').classList.remove('flex');
        }
    </script>
@endsection
