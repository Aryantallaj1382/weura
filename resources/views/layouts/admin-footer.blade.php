<footer class="mt-8 rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-4 shadow-sm text-sm flex flex-col sm:flex-row items-center justify-between gap-2">
    <p class="opacity-70">© {{ date('Y-M-D') }} </p>
    <div class="flex items-center gap-3">
        <a href="tel:09902866182" class="hover:underline"> تماس با پشتیبانی پنل ادمین</a>
        <span>·</span>
        <a href="#" class="hover:underline">قوانین</a>
        <span>·</span>
        <form method="POST" action="{{ route('logout22') }}">
            @csrf
            <form method="POST" action="{{ route('logout22') }}">
                @csrf
                <button type="submit" name="logout22"
                        class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 dark:border-slate-800 px-3 py-1.5 hover:bg-slate-50 dark:hover:bg-slate-800/60">
                    خروج
                </button>
            </form>

        </form>
    </div>
</footer>
