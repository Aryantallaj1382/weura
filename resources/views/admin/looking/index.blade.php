@extends('layouts.admin')

@section('content')

    @if($errors->any())
        <div class="bg-red-500 text-white p-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="min-h-screen bg-zinc-950 p-6">



        {{-- Header --}}
        <div class="flex justify-between items-center mb-8">

            <div>

                <h1 class="text-3xl font-bold text-white">
                    دنبال چه می‌گردی
                </h1>

            </div>


            <button
                onclick="openCreateModal()"
                class="px-5 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white transition">

                + ایجاد مورد جدید

            </button>


        </div>



        {{-- Alert --}}
        @if(session('success'))

            <div class="mb-6 bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 p-4 rounded-xl">

                {{ session('success') }}

            </div>

        @endif




        {{-- Cards --}}

        <div class="grid xl:grid-cols-4 lg:grid-cols-3 md:grid-cols-2 gap-6">

            @foreach($looking as $item)

                <div class="group bg-gradient-to-br from-zinc-900 to-zinc-800 border border-zinc-700/50 rounded-2xl p-6 shadow-lg hover:shadow-2xl hover:border-indigo-500/70 transition-all duration-300 transform hover:-translate-y-1">

                    <!-- بخش بالایی: لوگو و عنوان -->
                    <div class="flex items-center gap-4">

                        <div class="w-16 h-16 rounded-2xl overflow-hidden bg-zinc-700 ring-2 ring-zinc-600 group-hover:ring-indigo-500 transition-all duration-300 flex-shrink-0">
                            <img
                                src="{{ $item->logo }}"
                                alt="{{ $item->title }}"
                                class="w-full h-full object-cover"
                            >
                        </div>

                        <div class="min-w-0 flex-1">
                            <h2 class="text-white font-bold text-lg truncate group-hover:text-indigo-300 transition-colors">
                                {{ $item->title }}
                            </h2>
                            <p class="text-zinc-400 text-sm mt-0.5 font-mono">
                                <span class="text-zinc-500">ID:</span> {{ $item->id }}
                            </p>
                        </div>

                    </div>

                    <!-- بخش پایین: دکمه حذف -->
                    <div class="mt-6 pt-4 border-t border-zinc-700/50">

                        <form
                            method="POST"
                            action="{{ route('admin.admin.looking.destroy', $item) }}"
                            onsubmit="return confirm('آیا مطمئن هستید؟')"
                            class="block"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                class="w-full py-3 rounded-xl bg-red-600/80 hover:bg-red-600 text-white font-medium transition-all duration-300 flex items-center justify-center gap-2 hover:shadow-lg hover:shadow-red-600/30 group/btn"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform duration-300 group-hover/btn:scale-110" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                حذف
                            </button>

                        </form>

                    </div>

                </div>

            @endforeach

        </div>

    </div>





    {{-- Create Modal --}}

    <div id="createModal"
         class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm justify-center items-center z-50">


        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl w-full max-w-md p-6">


            <div class="flex justify-between items-center mb-6">


                <h2 class="text-xl font-bold text-white">

                    ایجاد دنبال چه می‌گردی

                </h2>


                <button
                    onclick="closeCreateModal()"
                    class="text-zinc-400 hover:text-white text-2xl">

                    ×

                </button>


            </div>



            <form
                method="POST"
                action="{{ route('admin.admin.looking.store') }}"
                enctype="multipart/form-data">


                @csrf



                <div class="mb-4">


                    <label class="text-zinc-300 block mb-2">

                        عنوان

                    </label>


                    <input
                        type="text"
                        name="title"
                        required
                        class="w-full bg-zinc-800 border border-zinc-700 rounded-xl p-3 text-red-600 outline-none focus:ring-2 focus:ring-indigo-500">


                </div>



                <div class="mb-5">


                    <label class="text-zinc-300 block mb-2">

                        لوگو

                    </label>


                    <input
                        type="file"
                        name="logo"
                        required
                        class="w-full bg-zinc-800 border border-zinc-700 rounded-xl p-3 text-white">


                </div>



                <div class="flex gap-3">


                    <button
                        type="button"
                        onclick="closeCreateModal()"
                        class="flex-1 py-3 rounded-xl bg-zinc-700 hover:bg-zinc-600 text-white">

                        انصراف

                    </button>



                    <button
                        class="flex-1 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white">

                        ذخیره

                    </button>


                </div>


            </form>


        </div>


    </div>





    <script>


        function openCreateModal(){

            let modal=document.getElementById('createModal');

            modal.classList.remove('hidden');

            modal.classList.add('flex');

        }



        function closeCreateModal(){

            let modal=document.getElementById('createModal');

            modal.classList.remove('flex');

            modal.classList.add('hidden');

        }



    </script>


@endsection
