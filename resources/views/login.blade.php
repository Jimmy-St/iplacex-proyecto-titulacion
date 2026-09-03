<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión - BICOM PICKERS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        /* Coincide exactamente con el tono bg-slate-50 (#f8fafc) de los inputs */
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px #f8fafc inset !important;
            -webkit-text-fill-color: #0f172a !important; /* Texto oscuro de alto contraste */
            transition: background-color 5000s ease-in-out 0s;
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center">

    <div class="bg-white border border-slate-200 shadow-xl rounded-xl p-8 w-80 flex flex-col items-center text-center">

        <!-- Ícono de paquete con tono morado -->
        <div class="mb-4 text-purple-600">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" class="w-16 h-16">
                <path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"/>
                <path d="M12 22V12"/>
                <polyline points="3.29 7 12 12 20.71 7"/>
                <path d="m7.5 4.27 9 5.15"/>
            </svg>
        </div>

        <p class="text-slate-900 font-bold tracking-widest uppercase text-sm mb-6 w-full text-center">Gestión Picking</p>

        @if(session('error'))
            <p class="text-red-600 font-medium text-xs mb-4 w-full text-center bg-red-50 border border-red-200 p-2 rounded-md">{{ session('error') }}</p>
        @endif

        <form action="{{ route('login.post') }}" method="POST" class="flex flex-col gap-4 w-full text-left">
            @csrf

            <div class="flex flex-col gap-1.5">
                <label class="text-slate-600 text-xs font-semibold uppercase tracking-wider">Usuario</label>
                <div class="flex items-center gap-2 bg-slate-50 border border-slate-300 rounded-md px-3 h-10 focus-within:border-purple-600 focus-within:bg-white transition-colors">
                    <i data-lucide="user" class="w-4 h-4 text-slate-400" style="stroke-width:1.5"></i>
                    <input type="text" 
                           name="username" 
                           value="{{ old('username') }}" 
                           placeholder="usuario"
                           class="bg-transparent border-none outline-none text-slate-900 text-sm w-full placeholder-slate-400"
                           required 
                           autofocus>
                </div>
            </div>

            <div class="flex flex-col gap-1.5">
                <label class="text-slate-600 text-xs font-semibold uppercase tracking-wider">Contraseña</label>
                <div class="flex items-center gap-2 bg-slate-50 border border-slate-300 rounded-md px-3 h-10 focus-within:border-purple-600 focus-within:bg-white transition-colors">
                    <i data-lucide="lock" class="w-4 h-4 text-slate-400" style="stroke-width:1.5"></i>
                    <input type="password" 
                           name="password" 
                           placeholder="••••••••"
                           class="bg-transparent border-none outline-none text-slate-900 text-sm w-full placeholder-slate-400"
                           required>
                </div>
            </div>

            <button type="submit"
                class="w-full h-10 bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold rounded-md shadow-md shadow-purple-600/20 transition-all mt-2 cursor-pointer">
                Ingresar
            </button>
        </form>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>