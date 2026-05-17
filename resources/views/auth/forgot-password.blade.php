<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Lupa Password</h2>
        <p class="mt-1 text-sm text-gray-500 leading-relaxed">
            Masukkan alamat email Anda dan kami akan mengirimkan link untuk mengatur ulang password.
        </p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-900 text-sm focus:border-blue-500 focus:ring-blue-500"
                   placeholder="nama@email.com">
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Submit -->
        <div class="mt-6">
            <button type="submit" class="w-full rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Kirim Link Reset Password
            </button>
        </div>

        <p class="mt-6 text-center text-sm text-gray-500">
            Ingat password Anda?
            <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-800">Kembali ke login</a>
        </p>
    </form>
</x-guest-layout>
