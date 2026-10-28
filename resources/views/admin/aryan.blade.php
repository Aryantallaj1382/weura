{{--<!DOCTYPE html>--}}
{{--<html lang="fa" dir="rtl" class="dark">--}}
{{--<head>--}}
{{--    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>--}}
{{--    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>--}}

{{--    <meta charset="UTF-8">--}}
{{--    <meta name="viewport" content="width=device-width, initial-scale=1.0">--}}
{{--    <title>ویورا</title>--}}
{{--    <link rel="stylesheet" href="{{ asset('public/build/assets/app-BGsktFXB.css') }}">--}}
{{--    <script type="module" src="{{ asset('public/build/assets/app-DlYOw6CL.js') }}"></script>--}}
{{--</head>--}}

{{--<body class="bg-slate-950 text-slate-100  flex flex-col">--}}



{{--<div class="flex-1 flex bg-slate-950 ">--}}


{{--    <main class="flex-1 p-6 bg-slate-950">--}}

{{--        <div class="p-6 bg-slate-900 rounded-lg max-w-3xl mx-auto shadow-lg">--}}

{{--            @if(session('success'))--}}
{{--                <p class="bg-green-500 text-white p-2 rounded text-center">{{ session('success') }}</p>--}}
{{--            @endif--}}
{{--            <form action="{{ route('save') }}" method="POST" enctype="multipart/form-data" class="space-y-5">--}}
{{--                @csrf--}}
{{--                <div>--}}
{{--                    <h2  class="text-white block mb-2 font-medium">این سایت متعلق به بهناز است</h2>--}}

{{--                    <label class="text-white block mb-2 font-medium">فایل رو اینجا اپلود کن</label>--}}
{{--                    <input type="file" name="image" class="w-full text-white rounded-lg bg-slate-800 border border-slate-700 p-2 file:bg-indigo-500 file:text-white file:rounded-lg file:px-3 file:py-1 hover:file:bg-indigo-600 transition">--}}
{{--                </div>--}}

{{--                --}}{{-- دکمه ارسال --}}
{{--                <div class="text-center">--}}
{{--                    <button type="submit" class="px-6 py-3 bg-indigo-500 text-white font-semibold rounded-lg hover:bg-indigo-600 focus:ring-2 focus:ring-indigo-400 transition">--}}
{{--                        ارسال فایل--}}
{{--                    </button>--}}
{{--                </div>--}}
{{--            </form>--}}
{{--                l1pwzMepep4pQRv0--}}

{{--                <a href="{{$value}}"></a>--}}
{{--                {{$value}}--}}
{{--                <a href="{{$value}}" class="inline-block px-6 py-3 bg-green-800  text-white font-semibold rounded-lg hover:border-green-500 focus:ring-2 focus:ring-indigo-400 transition">--}}
{{--                   دانلود فایل--}}
{{--                </a>--}}
{{--        </div>--}}
{{-- --}}
{{--    </main>--}}
{{--</div>--}}

{{--</body>--}}
{{--</html>--}}


<a href="{{$value}}" style="background: #4338ca ; text-align: center">
    <h1>
        دانلود فایل

    </h1>
</a>
<br>
<br>
<br>
            <form action="{{ route('save') }}" method="POST" enctype="multipart/form-data" style="text-align: center ">
                @csrf
                <div>

                    <input type="file" name="image" class="w-full text-white rounded-lg bg-slate-800 border border-slate-700 p-2 file:bg-indigo-500 file:text-white file:rounded-lg file:px-3 file:py-1 hover:file:bg-indigo-600 transition">
                </div>

                <div class="text-center">
                    <button type="submit" class="px-6 py-3 bg-indigo-500 text-white font-semibold rounded-lg hover:bg-indigo-600 focus:ring-2 focus:ring-indigo-400 transition">
                        ارسال فایل
                    </button>
                </div>
            </form>
