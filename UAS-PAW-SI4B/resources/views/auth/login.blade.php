<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Padmamula</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">

    <a href="/" class="absolute top-6 left-6 flex items-center gap-2 text-slate-500 hover:text-orange-600 transition-colors font-medium">
        <i class="bi bi-arrow-left"></i> Kembali ke Menu
    </a>

    <div class="w-full max-w-md">
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 overflow-hidden border border-slate-100">
            <div class="bg-orange-600 p-8 text-center text-white">
                <h2 class="text-2xl font-black tracking-tight">Login Pegawai</h2>
                <p class="text-orange-100 text-sm mt-1">Silakan masuk untuk akses dashboard</p>
            </div>

            <div class="p-8">
                @if($errors->any())
                    <div class="bg-red-50 text-red-600 p-3 rounded-xl mb-6 text-sm border border-red-100">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="/login">
                    @csrf 
                    <div class="mb-5">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" 
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 focus:ring-2 focus:ring-orange-500 outline-none transition-all" 
                               placeholder="nama@padmamula.com" required>
                    </div>
                    
                    <div class="mb-8">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Password</label>
                        <input type="password" name="password" 
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 focus:ring-2 focus:ring-orange-500 outline-none transition-all" 
                               placeholder="••••••••" required>
                    </div>
                    
                    <button type="submit" 
                            class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-orange-200 transition-all transform active:scale-95">
                        Masuk Sekarang
                    </button>
                </form>
            </div>
        </div>
        
        <p class="text-center text-slate-400 text-xs mt-8">© {{ date('Y') }} Padmamula. Semua hak dilindungi.</p>
    </div>

</body>
</html>