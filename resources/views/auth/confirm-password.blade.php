<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-white">Konfirmasi Password</h2>
        <p class="mt-2 text-sm text-gray-400 leading-relaxed">
            Ini adalah area yang aman. Mohon konfirmasi password Anda sebelum melanjutkan.
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div>
            <label for="password" class="block text-sm font-medium text-gray-300 mb-1">Password</label>
            <input id="password"
                   type="password"
                   name="password"
                   required autocomplete="current-password"
                   class="w-full rounded-xl border-0 bg-white/10 px-4 py-3 text-white placeholder-gray-500 ring-1 ring-white/10 transition focus:bg-white/15 focus:ring-2 focus:ring-teal-500 sm:text-sm"
                   placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-6">
            <button type="submit"
                    class="w-full rounded-xl bg-teal-500 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-teal-500/25 transition hover:bg-teal-400 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 focus:ring-offset-gray-900">
                Konfirmasi
            </button>
        </div>
    </form>
</x-guest-layout>
