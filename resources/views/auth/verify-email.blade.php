<x-guest-layout>
    <div class="text-center">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-teal-500/10">
            <svg class="h-7 w-7 text-teal-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-white mb-2">Verifikasi Email</h2>
        <p class="text-sm text-gray-400 leading-relaxed">
            Terima kasih telah mendaftar! Silakan verifikasi email Anda dengan mengklik link yang telah kami kirimkan. Jika belum menerima email, kami akan mengirim ulang.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mt-4 rounded-xl bg-green-500/10 border border-green-500/20 p-4 text-center text-sm text-green-400">
            Link verifikasi baru telah dikirim ke alamat email Anda.
        </div>
    @endif

    <div class="mt-6 flex items-center justify-between gap-4">
        <form method="POST" action="{{ route('verification.send') }}" class="flex-1">
            @csrf
            <button type="submit"
                    class="w-full rounded-xl bg-teal-500 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-teal-500/25 transition hover:bg-teal-400 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 focus:ring-offset-gray-900">
                Kirim Ulang Email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="rounded-xl px-4 py-3 text-sm font-medium text-gray-400 hover:text-white transition">
                Keluar
            </button>
        </form>
    </div>
</x-guest-layout>
