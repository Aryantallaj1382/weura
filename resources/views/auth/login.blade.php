<x-guest-layout>
    <div class="w-full max-w-md bg-white dark:bg-slate-800 rounded-2xl shadow-lg p-8">
        <h2 class="text-2xl font-bold mb-6 text-center text-slate-800 dark:text-slate-100">ورود به پنل ادمین ویورا</h2>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- Email -->
            <div>
                <x-input-label for="email" :value="__('ایمیل')" />
                <x-text-input id="email" class="block mt-1 w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 dark:bg-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                              type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('رمز عبور')" />
                <x-text-input id="password" class="block mt-1 w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 dark:bg-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                              type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>



            <!-- Submit -->
            <div>
                <x-primary-button class="w-full py-2 mt-2  text-center text-white bg-indigo-500 hover:bg-indigo-600 rounded-lg transition">
                    ورود
                </x-primary-button>
            </div>
        </form>

        <div class="mt-6 text-center text-sm text-slate-500 dark:text-slate-400">
          Weura
        </div>
    </div>
</x-guest-layout>
