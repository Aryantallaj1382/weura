<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="{{ asset('build/assets/app-BGsktFXB.css') }}">
    <script type="module" src="{{ asset('build/assets/app-DlYOw6CL.js') }}"></script></head>
<body class="bg-slate-50 dark:bg-slate-900 flex items-center justify-center min-h-screen px-4">
{{ $slot }}
</body>
</html>
