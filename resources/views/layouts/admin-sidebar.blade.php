<aside class="hidden lg:block w-64">
    <div class="sticky top-20">
        <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm p-3">
            <span class="text-sm font-semibold opacity-70">منو</span>
            <nav class="flex flex-col gap-1 mt-2">

                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-2 px-4 py-2 rounded-2xl text-sm font-medium text-slate-800 dark:text-slate-200 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition">
                    {{-- آیکون --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor">
                        <path
                            d="M11.584 2.376a.75.75 0 0 1 .832 0l9 6a.75.75 0 1 1-.832 1.248L12 3.901 3.416 9.624a.75.75 0 0 1-.832-1.248l9-6Z"/>
                        <path fill-rule="evenodd"
                              d="M20.25 10.332v9.918H21a.75.75 0 0 1 0 1.5H3a.75.75 0 0 1 0-1.5h.75v-9.918a.75.75 0 0 1 .634-.74A49.109 49.109 0 0 1 12 9c2.59 0 5.134.202 7.616.592a.75.75 0 0 1 .634.74Zm-7.5 2.418a.75.75 0 0 0-1.5 0v6.75a.75.75 0 0 0 1.5 0v-6.75Zm3-.75a.75.75 0 0 1 .75.75v6.75a.75.75 0 0 1-1.5 0v-6.75a.75.75 0 0 1 .75-.75ZM9 12.75a.75.75 0 0 0-1.5 0v6.75a.75.75 0 0 0 1.5 0v-6.75Z"
                              clip-rule="evenodd"/>
                        <path d="M12 7.875a1.125 1.125 0 1 0 0-2.25 1.125 1.125 0 0 0 0 2.25Z"/>
                        پ
                    </svg>
                    داشبورد
                </a>
                <a href="{{ route('admin.tickets.index') }}"
                   class="flex items-center gap-2 px-4 py-2 rounded-2xl text-sm font-medium text-slate-800 dark:text-slate-200 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition">
                    {{-- آیکون --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 14h6M9 10h6M4 6h16v12H4V6z"/>
                    </svg>
                    تیکت‌ها
                </a>

                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center gap-2 px-4 py-2 rounded-2xl text-sm font-medium text-slate-800 dark:text-slate-200 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition">
                    {{-- آیکون --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a4 4 0 00-3-3.87M12 12a4 4 0 100-8 4 4 0 000 8zm0 0v6m5 4h-10a4 4 0 01-4-4v-2a4 4 0 013-3.87"/>
                    </svg>

                    کاربران
                </a>
                <a href="{{ route('admin.manhwa.index') }}"
                   class="flex items-center gap-2 px-4 py-2 rounded-2xl text-sm font-medium text-slate-800 dark:text-slate-200 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition">
                    {{-- آیکون --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="h-5 w-5 text-indigo-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/>
                    </svg>
                    کتاب ها و چپتر ها
                </a>

                <a href="{{ route('admin.categories.index') }}"
                   class="flex items-center gap-2 px-4 py-2 rounded-2xl text-sm font-medium text-slate-800 dark:text-slate-200 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition">
                    {{-- آیکون دسته‌بندی --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="h-5 w-5 text-indigo-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 0 0-1.883 2.542l.857 6a2.25 2.25 0 0 0 2.227 1.932H19.05a2.25 2.25 0 0 0 2.227-1.932l.857-6a2.25 2.25 0 0 0-1.883-2.542m-16.5 0V6A2.25 2.25 0 0 1 6 3.75h3.879a1.5 1.5 0 0 1 1.06.44l2.122 2.12a1.5 1.5 0 0 0 1.06.44H18A2.25 2.25 0 0 1 20.25 9v.776" />
                    </svg>

                    دسته‌بندی‌ها
                </a>
                <a href="{{ route('admin.blog.index') }}"
                   class="flex items-center gap-2 px-4 py-2 rounded-2xl text-sm font-medium text-slate-800 dark:text-slate-200 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition">
                    {{-- آیکون بلاگ --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="h-5 w-5 text-indigo-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-10.5A2.25 2.25 0 0 1 4.5 17.25V6.75m15 0A2.25 2.25 0 0 0 17.25 4.5h-10.5A2.25 2.25 0 0 0 4.5 6.75m15 0H4.5m0 0l3-3m13.5 3l-3-3" />
                    </svg>

                    بلاگ‌ها
                </a>
                <a href="{{ route('admin.blog_categories.index') }}"
                   class="flex items-center gap-2 px-4 py-2 rounded-2xl text-sm font-medium text-slate-800 dark:text-slate-200 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition">
                    {{-- آیکون بلاگ --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="h-5 w-5 text-indigo-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-10.5A2.25 2.25 0 0 1 4.5 17.25V6.75m15 0A2.25 2.25 0 0 0 17.25 4.5h-10.5A2.25 2.25 0 0 0 4.5 6.75m15 0H4.5m0 0l3-3m13.5 3l-3-3" />
                    </svg>
                    دسته بندی بلاگ ها
                </a>
                <a href="{{ route('admin.coming_soon_manhuas.index') }}"
                   class="flex items-center gap-2 px-4 py-2 rounded-2xl text-sm font-medium text-slate-800 dark:text-slate-200 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition">
                    {{-- آیکون بلاگ --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="h-5 w-5 text-indigo-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-10.5A2.25 2.25 0 0 1 4.5 17.25V6.75m15 0A2.25 2.25 0 0 0 17.25 4.5h-10.5A2.25 2.25 0 0 0 4.5 6.75m15 0H4.5m0 0l3-3m13.5 3l-3-3" />
                    </svg>
                    بخش بزودی ها
                </a>


                <a href="{{ route('admin.banners.index') }}"
                   class="flex items-center gap-2 px-4 py-2 rounded-2xl text-sm font-medium text-slate-800 dark:text-slate-200 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition">
                    {{-- آیکون بلاگ --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="h-5 w-5 text-indigo-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-10.5A2.25 2.25 0 0 1 4.5 17.25V6.75m15 0A2.25 2.25 0 0 0 17.25 4.5h-10.5A2.25 2.25 0 0 0 4.5 6.75m15 0H4.5m0 0l3-3m13.5 3l-3-3" />
                    </svg>
بنر ها                </a>



                <form method="POST" action="{{ route('logout22') }}">
                    @csrf
                    <button type="submit"
                            class="flex items-center gap-2 px-4 py-2 rounded-2xl text-sm font-medium text-slate-800 dark:text-slate-200 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="h-5 w-5 text-indigo-500">
                            <path fill-rule="evenodd"
                                  d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z"
                                  clip-rule="evenodd"/>
                        </svg>
                        خروج
                    </button>
                </form>





            </nav>
        </div>
    </div>
</aside>
