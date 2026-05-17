<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Reset Password</h2>
        <p class="mt-1 text-sm text-gray-500">Masukkan password baru untuk akun Anda</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-900 text-sm focus:border-blue-500 focus:ring-blue-500"
                   placeholder="nama@email.com">
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-900 text-sm focus:border-blue-500 focus:ring-blue-500"
                   placeholder="Minimal 8 karakter">
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-900 text-sm focus:border-blue-500 focus:ring-blue-500"
                   placeholder="Ulangi password baru">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <!-- Submit -->
        <div class="mt-6">
            <button type="submit" class="w-full rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Reset Password
            </button>
        </div>
    </form>
</x-guest-layout>
