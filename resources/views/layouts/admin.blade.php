<!DOCTYPE html>
<html lang="fa" dir="rtl" class="dark">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ویورا</title>
    <link rel="stylesheet" href="{{ asset('build/assets/app-BGsktFXB.css') }}">
    <script type="module" src="{{ asset('build/assets/app-DlYOw6CL.js') }}"></script>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 min-h-screen flex flex-col">

@include('layouts.admin-header')

<div class="flex-1 flex">
    @include('layouts.admin-sidebar')

    <main class="flex-1 p-6">

        @yield('content')
    </main>
</div>

@include('layouts.admin-footer')

</body>
</html>
