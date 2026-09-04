<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestión</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  @livewireStyles
</head>
<body class="h-full bg-slate-100 text-slate-900 antialiased overflow-hidden">

  <!-- Barra lateral (Sidebar en modo claro) -->
  <aside class="hidden md:flex flex-col fixed inset-y-0 left-0 w-56 bg-white border-r border-purple-100">

    <div class="h-24 flex flex-col justify-center items-center gap-1 px-4 border-b border-purple-100">
        <!-- Ícono de la caja más grande -->
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.0" stroke-linecap="round" stroke-linejoin="round" class="w-9 h-9 text-purple-600 shrink-0">
            <path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"/>
            <path d="M12 22V12"/>
            <polyline points="3.29 7 12 12 20.71 7"/>
            <path d="m7.5 4.27 9 5.15"/>
        </svg>
        <span class="font-bold text-xs tracking-widest uppercase text-slate-900">Gestión Picking</span>
    </div>

    <nav class="flex-1 py-4 px-3 flex flex-col gap-1.5">

      <a href="{{ route('tickets.index') }}"
        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors
          {{ request()->routeIs('tickets*')
            ? 'bg-purple-50 text-purple-700 font-semibold'
            : 'text-slate-700 font-medium hover:bg-slate-50 hover:text-slate-900' }}">
        <i data-lucide="ticket" class="w-5 h-5" style="stroke-width:1.75"></i>
        Tickets
      </a>

      <a href="{{ route('pickers.index') }}"
        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors
          {{ request()->routeIs('pickers*')
            ? 'bg-purple-50 text-purple-700 font-semibold'
            : 'text-slate-700 font-medium hover:bg-slate-50 hover:text-slate-900' }}">
        <i data-lucide="users" class="w-5 h-5" style="stroke-width:1.75"></i>
        Pickers
      </a>

      <a href="{{ route('productos.index') }}"
        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors
          {{ request()->routeIs('productos*')
            ? 'bg-purple-50 text-purple-700 font-semibold'
            : 'text-slate-700 font-medium hover:bg-slate-50 hover:text-slate-900' }}">
        <i data-lucide="clock" class="w-5 h-5" style="stroke-width:1.75"></i>
        Turnos
      </a>

      <a href="{{ route('reportes.index') }}"
        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors
          {{ request()->routeIs('reportes*')
            ? 'bg-purple-50 text-purple-700 font-semibold'
            : 'text-slate-700 font-medium hover:bg-slate-50 hover:text-slate-900' }}">
        <i data-lucide="bar-chart-2" class="w-5 h-5" style="stroke-width:1.75"></i>
        Reportes
      </a>

      <a href="{{ route('config.index') }}"
        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors
          {{ request()->routeIs('config*')
            ? 'bg-purple-50 text-purple-700 font-semibold'
            : 'text-slate-700 font-medium hover:bg-slate-50 hover:text-slate-900' }}">
        <i data-lucide="settings" class="w-5 h-5" style="stroke-width:1.75"></i>
        Config
      </a>

    </nav>

    <div class="border-t border-purple-100 px-3 py-3">
      <div class="flex items-center gap-3 px-3 py-2 rounded-lg">
        <i data-lucide="user-circle" class="w-5 h-5 text-slate-500 shrink-0" style="stroke-width:1.75"></i>
        <span class="text-xs text-slate-700 truncate flex-1 font-semibold">{{ Auth::user()->name }}</span>
      </div>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit"
          class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                 text-slate-700 hover:bg-red-50 hover:text-red-600 transition-colors">
          <i data-lucide="log-out" class="w-5 h-5 shrink-0" style="stroke-width:1.75"></i>
          Salir
        </button>
      </form>
    </div>

  </aside>

  <!-- Contenedor Principal -->
  <div class="flex flex-col h-full md:pl-56">

    <main class="flex-1 overflow-y-auto p-6 pb-20 md:pb-6">
      @yield('content')
    </main>

    <!-- Barra de navegación inferior móvil -->
    <footer class="md:hidden fixed bottom-0 left-0 right-0 h-16
                   bg-white border-t border-purple-100
                   flex justify-around items-center z-50">

      <a href="{{ route('tickets.index') }}"
        class="flex flex-col items-center gap-1 {{ request()->routeIs('tickets*') ? 'text-purple-600 font-semibold' : 'text-slate-500 hover:text-slate-800' }}">
        <i data-lucide="ticket" class="w-5 h-5" style="stroke-width:1.75"></i>
        <span class="text-[10px]">Tickets</span>
      </a>

      <a href="{{ route('pickers.index') }}"
        class="flex flex-col items-center gap-1 {{ request()->routeIs('pickers*') ? 'text-purple-600 font-semibold' : 'text-slate-500 hover:text-slate-800' }}">
        <i data-lucide="users" class="w-5 h-5" style="stroke-width:1.75"></i>
        <span class="text-[10px]">Pickers</span>
      </a>

      <a href="{{ route('productos.index') }}"
        class="flex flex-col items-center gap-1 {{ request()->routeIs('productos*') ? 'text-purple-600 font-semibold' : 'text-slate-500 hover:text-slate-800' }}">
        <i data-lucide="clock" class="w-5 h-5" style="stroke-width:1.75"></i>
        <span class="text-[10px]">Turnos</span>
      </a>

      <a href="{{ route('reportes.index') }}"
        class="flex flex-col items-center gap-1 {{ request()->routeIs('reportes*') ? 'text-purple-600 font-semibold' : 'text-slate-500 hover:text-slate-800' }}">
        <i data-lucide="bar-chart-2" class="w-5 h-5" style="stroke-width:1.75"></i>
        <span class="text-[10px]">Reportes</span>
      </a>

      <a href="{{ route('config.index') }}"
        class="flex flex-col items-center gap-1 {{ request()->routeIs('config*') ? 'text-purple-600 font-semibold' : 'text-slate-500 hover:text-slate-800' }}">
        <i data-lucide="settings" class="w-5 h-5" style="stroke-width:1.75"></i>
        <span class="text-[10px]">Config</span>
      </a>

    </footer>

  </div>

  <script>lucide.createIcons();</script>
  @livewireScripts
</body>
</html>