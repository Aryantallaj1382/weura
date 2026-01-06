<!DOCTYPE html>
<html lang="fa" dir="rtl" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود مدیر | ویورا</title>

    <!-- آیکون‌ها و چارت -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- فایل‌های کامپایل‌شده Tailwind از Vite -->
    <link rel="stylesheet" href="{{ asset('build/assets/app-BGsktFXB.css') }}">
    <script type="module" src="{{ asset('build/assets/app-DlYOw6CL.js') }}"></script>

    <style>
        /* برای اینکه متن و placeholder در حالت تاریک واضح باشند */
        input::placeholder {
            color: #9CA3AF; /* خاکستری روشن در حالت روشن */
        }
        .dark input::placeholder {
            color: #CBD5E1; /* روشن‌تر در حالت تاریک */
        }
    </style>
</head>

<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 min-h-screen flex items-center justify-center font-sans">

<div class="bg-white dark:bg-slate-900 shadow-lg rounded-2xl w-full max-w-md p-8 border border-slate-200 dark:border-slate-700">
    <h1 class="text-2xl font-bold mb-6 text-center text-slate-900 dark:text-slate-100">
        ورود مدیر
    </h1>

    <form action="{{ route('admin.login.submit') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="email" class="block text-slate-700 dark:text-slate-300 mb-2">
                ایمیل
            </label>
            <input type="email" name="email" id="email"
                   placeholder="ایمیل خود را وارد کنید"
                   class="w-full border border-slate-300 dark:border-slate-700
                              bg-white dark:bg-slate-800
                              px-3 py-2 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none
                              text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-300"
                   required autofocus>
            @error('email')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="password" class="block text-slate-700 dark:text-slate-300 mb-2">
                رمز عبور
            </label>
            <input type="password" name="password" id="password"
                   placeholder="رمز عبور خود را وارد کنید"
                   class="w-full border border-slate-300 dark:border-slate-700
                              bg-white dark:bg-slate-800
                              px-3 py-2 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none
                              text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-300"
                   required>
            @error('password')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-lg transition font-semibold">
            ورود
        </button>
    </form>
</div>

</body>
</html>
