<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión - BICOM PICKERS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        /* Neutraliza el fondo azul feo del autocompletado en Chrome */
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px white inset !important;
            -webkit-text-fill-color: #0f172a !important;
            transition: background-color 5000s ease-in-out 0s;
        }

        /* Animación de flotación suave (para aplicar solo al ícono) */
        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-8px); /* Sube 8px */
            }
        }

        .animate-float {
            animation: float 3s ease-in-out infinite;
            display: inline-block; /* Necesario para que funcione la transformación en SVG */
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen flex flex-col items-center justify-center p-4">

    <div class="bg-white border border-slate-200 shadow-xl rounded-xl p-8 w-80 flex flex-col items-center text-center">

        <div class="mb-2 text-purple-600 animate-float">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="w-20 h-20">
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
                class="w-full h-10 bg-gradient-to-r from-purple-700 via-purple-800 to-purple-900 hover:opacity-95 text-white text-sm font-semibold rounded-md shadow-lg shadow-purple-900/30 transition-all mt-2 cursor-pointer">
                Ingresar
            </button>
        </form>
    </div>

    <footer class="mt-4 text-slate-500 text-xs font-medium tracking-wide">
        Sistema Picking Pfau {{ date('Y') }}
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>