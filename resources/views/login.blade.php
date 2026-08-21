<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-gray-950 min-h-screen flex items-center justify-center">

    <div class="bg-gray-900 border border-white/10 rounded-xl p-8 w-80 flex flex-col items-center text-center">

        <!-- Ícono de paquete limpio (sin transparencias alfa) -->
        <div class="mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="w-16 h-16 text-white">
                <path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"/>
                <path d="M12 22V12"/>
                <polyline points="3.29 7 12 12 20.71 7"/>
                <path d="m7.5 4.27 9 5.15"/>
            </svg>
        </div>

        <p class="text-white font-medium tracking-widest uppercase text-lg mb-4 w-full text-center">Gestión Picking</p>
        

        @if(session('error'))
            <p class="text-red-400 text-sm mb-4 w-full text-center">{{ session('error') }}</p>
        @endif

        <form action="/login" method="POST" class="flex flex-col gap-4 w-full text-left">
            @csrf

            <div class="flex flex-col gap-1.5">
                <label class="text-white/40 text-xs uppercase tracking-widest">Usuario</label>
                <div class="flex items-center gap-2 bg-gray-950 border border-white/10 rounded-md px-3 h-10">
                    <i data-lucide="user" class="w-4 h-4 text-white/30" style="stroke-width:1.5"></i>
                    <input type="text" name="user" placeholder="usuario"
                        class="bg-transparent border-none outline-none text-white text-sm w-full placeholder-white/20"
                        required>
                </div>
            </div>

            <div class="flex flex-col gap-1.5">
                <label class="text-white/40 text-xs uppercase tracking-widest">Contraseña</label>
                <div class="flex items-center gap-2 bg-gray-950 border border-white/10 rounded-md px-3 h-10">
                    <i data-lucide="lock" class="w-4 h-4 text-white/30" style="stroke-width:1.5"></i>
                    <input type="password" name="password" placeholder="••••••••"
                        class="bg-transparent border-none outline-none text-white text-sm w-full placeholder-white/20"
                        required>
                </div>
            </div>

            <button type="submit"
                class="w-full h-10 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-md transition-colors mt-2">
                Ingresar
            </button>

        </form>
    </div>

    <script>lucide.createIcons();</script>
</body>
</html>