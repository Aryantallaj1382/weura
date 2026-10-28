@extends('layouts.admin')

@section('content')
    <div class="p-6">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">مدیریت مانهواها</h1>

            <a href="{{ route('admin.manhwa.create') }}"
               class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                ایجاد مانهوا
            </a>
        </div>

        <div class="flex gap-3 mb-6">

            <a href="{{ route('admin.manhwa.index2') }}"
               class="px-4 py-2 rounded-lg bg-blue-600">
                همه
            </a>

            <a href="{{ route('admin.manhwa.index2',['status'=>'pending']) }}"
               class="px-4 py-2 rounded-lg bg-yellow-500 text-white">
                در انتظار
            </a>

            <a href="{{ route('admin.manhwa.index2',['status'=>'approved']) }}"
               class="px-4 py-2 rounded-lg bg-green-600 text-white">
                تایید شده
            </a>

            <a href="{{ route('admin.manhwa.index2',['status'=>'rejected']) }}"
               class="px-4 py-2 rounded-lg bg-red-600 text-white">
                رد شده
            </a>

        </div>

        <div class="grid lg:grid-cols-3 md:grid-cols-2 gap-6">

            @foreach($manhwas as $manhwa)

                <div class="bg-zinc-900 border border-zinc-800 rounded-2xl overflow-hidden shadow-lg hover:border-indigo-500 hover:shadow-indigo-500/20 transition duration-300 flex flex-col h-full">

                    <!-- تصویر -->
                    <div class=" bg-zinc-800 overflow-hidden">

                        <img
                            src="{{ asset($manhwa->cover_image) }}"
                            alt="{{ $manhwa->title }}"
                            class="w-full h-64 object-cover hover:scale-105 transition duration-500 ">

                    </div>

                    <!-- محتوا -->
                    <div class="p-5 flex flex-col flex-1">

                        <!-- عنوان -->
                        <h2 class="text-lg font-bold text-white line-clamp-2 h-14 leading-7">
                            {{ $manhwa->title }}
                        </h2>

                        <!-- وضعیت -->
                        <div class="mt-4">

                            @if($manhwa->admin_status=='approved')

                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-400 text-sm">
                        ✅ تایید شده
                    </span>

                            @elseif($manhwa->admin_status=='rejected')

                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-red-500/20 text-red-400 text-sm">
                        ❌ رد شده
                    </span>

                            @else

                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-yellow-500/20 text-yellow-400 text-sm">
                        ⏳ در انتظار
                    </span>

                            @endif

                        </div>

                        <!-- دلیل رد -->
                        @if($manhwa->reject_reason)

                            <div class="mt-4 rounded-xl bg-red-500/10 border border-red-500/20 p-3 text-sm text-red-300 min-h-[85px]">

                                <span class="font-bold">دلیل رد:</span>

                                <p class="mt-2 leading-6">
                                    {{ $manhwa->reject_reason }}
                                </p>

                            </div>

                        @else

                            <div class="mt-4 min-h-[85px]"></div>

                        @endif

                        <!-- دکمه‌ها -->
                        <div class="mt-auto pt-5 flex gap-3">

                            @if($manhwa->admin_status!='approved')

                                <form method="POST"
                                      action="{{ route('admin.admin.manhwa.approve',$manhwa) }}"
                                      class="flex-1">

                                    @csrf
                                    @method('PATCH')

                                    <button
                                        class="w-full py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-medium transition">

                                        تایید

                                    </button>

                                </form>

                            @endif

                            <button
                                onclick="openReject({{ $manhwa->id }})"
                                class="flex-1 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white font-medium transition">

                                رد

                            </button>

                        </div>

                    </div>

                </div>

            @endforeach
        </div>

        <div class="mt-6">
            {{ $manhwas->links() }}
        </div>

    </div>


    <div id="rejectModal"
         class="hidden fixed inset-0 bg-black/50 flex justify-center items-center z-50">

        <div class="bg-white w-full max-w-md rounded-xl p-6">

            <h2 class="text-xl font-bold mb-4 text-blue-400">
                دلیل رد مانهوا
            </h2>

            <form id="rejectForm" method="POST">

                @csrf
                @method('PATCH')

                <textarea
                    name="reject_reason"
                    rows="5"
                    required
                    class="w-full border rounded-lg p-3 text-red-400"></textarea>

                <div class="flex justify-end gap-3 mt-5 text-blue-400">

                    <button type="button"
                            onclick="closeReject()"
                            class="px-4 py-2 bg-gray-300 rounded">

                        انصراف

                    </button>


                    <button
                        class="px-4 py-2 bg-red-600 text-white rounded">

                        ثبت رد

                    </button>

                </div>

            </form>

        </div>

    </div>

    <script>
        function openReject(id) {

            document.getElementById('rejectForm').action = '/admin/manhwa/' + id + '/reject';

            const modal = document.getElementById('rejectModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeReject() {

            const modal = document.getElementById('rejectModal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }

    </script>

@endsection
