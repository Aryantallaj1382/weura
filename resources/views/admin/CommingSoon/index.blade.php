@extends('layouts.admin')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100 mb-6">📖 لیست مانهوی‌های در حال انتشار</h1>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 dark:bg-green-800 text-green-700 dark:text-green-100 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Add New Button -->
        <button onclick="openAddModal()"
                class="mb-4 px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg transition">
            ➕ اضافه کردن
        </button>

        <!-- Table -->
        <div class="overflow-x-auto bg-white dark:bg-slate-800 rounded-xl shadow-lg">
            <table class="w-full text-sm text-left text-slate-800 dark:text-slate-100">
                <thead class="bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-200">
                <tr>
                    <th class="px-4 py-3">عنوان</th>
                    <th class="px-4 py-3">توضیحات</th>
                    <th class="px-4 py-3">دسته‌بندی</th>
                    <th class="px-4 py-3">تصویر</th>
                    <th class="px-4 py-3">عملیات</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($manhuas as $manhua)
                    <tr class="border-b border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-600 transition">
                        <td class="px-4 py-3 font-medium">{{ $manhua->title }}</td>
                        <td class="px-4 py-3">{{ $manhua->description }}</td>
                        <td class="px-4 py-3">{{ $manhua->category }}</td>
                        <td class="px-4 py-3">
                            <div class="flex flex-col items-center">
                                @if($manhua->image)
                                    <img src="{{ asset($manhua->image) }}" alt="" class="w-16 h-16 object-cover rounded mt-1">
                                @endif
                            </div>
                        </td>

                        <td class="px-4 py-3 space-x-2">

                            <form action="{{ route('admin.coming_soon_manhuas.destroy', $manhua->id) }}" method="POST" class="inline-block" onsubmit="return confirm('آیا مطمئن هستید؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded">حذف</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $manhuas->links() }}
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6 w-96 relative text-slate-800 dark:text-slate-100">
            <h2 class="text-xl font-bold mb-4">✏️ ویرایش مانهوی</h2>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="text" name="title" id="editTitle" placeholder="عنوان" class="w-full mb-2 p-2 border rounded bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100" required>
                <textarea name="description" id="editDescription" placeholder="توضیحات" class="w-full mb-2 p-2 border rounded bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100" required></textarea>
                <input type="text" name="category" id="editCategory" placeholder="دسته‌بندی" class="w-full mb-2 p-2 border rounded bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100" required>
                <input type="file" name="image" accept="image/*" class="w-full mb-4 p-2 border rounded bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100">
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded">انصراف</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded">ذخیره تغییرات</button>
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

        function openEditModal(id, title, description, category) {
            document.getElementById('editForm').action = `/admin/coming-soon-manhuas/${id}`;
            document.getElementById('editTitle').value = title;
            document.getElementById('editDescription').value = description;
            document.getElementById('editCategory').value = category;
            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('editModal').classList.add('flex');
        }
        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('editModal').classList.remove('flex');
        }
    </script>
@endsection
