<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sedang Dalam Pemeliharaan - SPK Bansos</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 flex items-center justify-center min-h-screen p-4 sm:p-6 md:p-8">
    <div class="max-w-md w-full bg-white rounded-2xl border border-slate-200/80 shadow-2xl p-6 sm:p-8 text-center flex flex-col items-center">
        <!-- Maintenance Icon -->
        <div class="w-16 h-16 rounded-2xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center mb-6 shadow-inner animate-pulse">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l-4.2-4.2m8.4 4.2a2.653 2.653 0 000-3.75l-4.2-4.2m-4.2 4.2L3 3m4.2 4.2a2.653 2.653 0 003.75 0l4.2-4.2M7.2 11.4H3m14.4 0h3.6M11.4 7.2V3m0 14.4v3.6" />
            </svg>
        </div>

        <!-- Heading -->
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight mb-3">
            Sedang Dalam Pemeliharaan
        </h1>

        <!-- Subheading -->
        <p class="text-sm sm:text-base text-slate-500 mb-6 leading-relaxed">
            {{ $message }}
        </p>

        <!-- Divider -->
        <div class="w-full h-px bg-slate-100 mb-6"></div>

        <!-- Support Info or App Name -->
        <div class="flex items-center justify-center gap-2">
            <div class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></div>
            <span class="text-xs font-semibold text-slate-400 tracking-wider uppercase">
                SPK Bansos
            </span>
        </div>
    </div>
</body>
</html>
