@extends('layouts.admin')

@section('title', 'درخواست‌های نویسندگی')

@section('content')
    <div class="p-6 bg-gray-900 text-gray-200 rounded-lg shadow-lg">
        <h2 class="text-xl font-semibold mb-4">📋 لیست درخواست‌های نویسندگی</h2>

        <div class="overflow-x-auto rounded-lg bg-gray-900 p-4">
            <table class="min-w-full table-auto text-sm text-left text-gray-200">
                <thead class="bg-gray-800">
                <tr>
                    <th class="w-2/6 px-4 py-3 text-left font-semibold">نام</th>
                    <th class="w-2/6 px-4 py-3 text-left font-semibold">شماره تماس</th>
                    <th class="w-2/6 px-4 py-3 text-center font-semibold">عملیات</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                @foreach($requests as $req)
                    <tr class="hover:bg-gray-700 transition">
                        <td class="px-4 py-3 break-words">{{ $req->name }}</td>
                        <td class="px-4 py-3 break-words">{{ $req->phone }}</td>
                        <td class="px-4 py-3 text-center">
                            <button
                                type="button"
                                class="bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded text-white"
                                onclick="openModal({{ $req->toJson() }})"
                            >
                                بیشتر
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $requests->links() }}
        </div>
    </div>

    <!-- Modal -->
    <div id="detailsModal" class="hidden fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50">
        <div class="bg-gray-800 p-6 rounded-lg shadow-lg max-w-lg w-full text-gray-200 relative">
            <h3 class="text-xl font-bold mb-4">جزئیات درخواست</h3>
            <div id="modalContent" class="space-y-2"></div>

            <div class="flex justify-end mt-6">
                <button onclick="closeModal()"
                        class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg text-white">
                    بستن
                </button>
            </div>
        </div>
    </div>

    <script>
        function openModal(data) {
            let html = `
        <p><strong>نام:</strong> ${data.name}</p>
        <p><strong>شماره:</strong> ${data.phone}</p>
        <p><strong>مهارت نقاشی دیجیتال:</strong> ${data.digital_painting_skill}</p>
        <p><strong>مهارت نویسندگی:</strong> ${data.writing_skill}</p>
        <p><strong>نرم‌افزار:</strong> ${data.software ?? '-'}</p>
        <p><strong>نیاز به پشتیبانی:</strong> ${data.need_support == 1 ? 'بله' : 'خیر'}</p>
        ${data.sample_file ? `<p><strong>نمونه فایل:</strong> <a href="/storage/${data.sample_file}" target="_blank" class="text-blue-400 underline">دانلود</a></p>` : ''}
    `;
            document.getElementById('modalContent').innerHTML = html;

            document.getElementById('detailsModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('detailsModal').classList.add('hidden');
        }
    </script>
@endsection
