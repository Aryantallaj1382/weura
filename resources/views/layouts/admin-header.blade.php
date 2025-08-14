<header class="sticky top-0 z-30 bg-white/80 dark:bg-slate-900/70 backdrop-blur border-b border-slate-200 dark:border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <!-- لوگو و عنوان -->
            <div class="flex items-center gap-2">
                <button class="p-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div class="flex items-center gap-2">
                    <span class="font-semibold text-slate-800 dark:text-slate-100">پنل ادمین</span>
                </div>
            </div>

            <!-- اکشن‌ها -->
            <div class="flex items-center gap-4">
                <!-- ساعت -->
                <div id="clock" class="text-white font-medium  px-4 py-2 rounded-lg shadow"></div>

                <script>
                    // تابع برای گرفتن زمان فعلی
                    function updateClock() {
                        const clock = document.getElementById('clock');
                        const now = new Date();
                        const hours = now.getHours().toString().padStart(2, '0'); // ساعت دو رقمی
                        const minutes = now.getMinutes().toString().padStart(2, '0'); // دقیقه دو رقمی
                        const seconds = now.getSeconds().toString().padStart(2, '0'); // ثانیه دو رقمی
                        clock.textContent = `${hours}:${minutes}:${seconds}`; // نمایش ساعت
                    }

                    // به‌روزرسانی ساعت هر ثانیه
                    setInterval(updateClock, 1000);

                    // اجرای اولیه تابع
                    updateClock();
                </script>

                <!-- اعلان -->
                <a href="{{ route('admin.tickets.index') }}">
                    <button class="relative p-2 rounded-2xl hover:bg-slate-100 dark:hover:bg-slate-800">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0M3.124 7.5A8.969 8.969 0 0 1 5.292 3m13.416 0a8.969 8.969 0 0 1 2.168 4.5" />
                        </svg>

                    </button>
                </a>

                <!-- نام کاربر -->
                <div class="flex items-center gap-2 rounded-2xl bg-slate-100 dark:bg-slate-800 px-3 py-1">
                    <span class="hidden sm:block text-sm text-slate-800 dark:text-slate-100">ادمین</span>
                </div>
            </div>
        </div>
    </div>
</header>
