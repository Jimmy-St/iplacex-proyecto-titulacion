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
<body class="h-full bg-gray-950 text-slate-200 antialiased overflow-hidden">

  <aside class="hidden md:flex flex-col fixed inset-y-0 left-0 w-56 bg-gray-900 border-r border-white/[0.06]">

    <div class="h-14 flex items-center px-4 border-b border-white/[0.06]">
      <span class="font-semibold text-sm tracking-widest uppercase text-white">Gestión</span>
    </div>

    <nav class="flex-1 py-4 px-3 flex flex-col gap-1">

      <a href="{{ route('tickets.index') }}"
        class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition-colors
          {{ request()->routeIs('tickets*')
            ? 'bg-slate-700/50 text-purple-400 font-medium'
            : 'text-white/55 hover:bg-slate-800 hover:text-white/85' }}">
        <i data-lucide="ticket" class="w-4 h-4" style="stroke-width:1.5"></i>
        Tickets
      </a>

      <a href="{{ route('pickers.index') }}"
        class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition-colors
          {{ request()->routeIs('pickers*')
            ? 'bg-slate-700/50 text-purple-400 font-medium'
            : 'text-white/55 hover:bg-slate-800 hover:text-white/85' }}">
        <i data-lucide="users" class="w-4 h-4" style="stroke-width:1.5"></i>
        Pickers
      </a>

      <a href="{{ route('productos.index') }}"
        class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition-colors
          {{ request()->routeIs('productos*')
            ? 'bg-slate-700/50 text-purple-400 font-medium'
            : 'text-white/55 hover:bg-slate-800 hover:text-white/85' }}">
        <i data-lucide="clock" class="w-4 h-4" style="stroke-width:1.5"></i>
        Turnos
      </a>

      <a href="{{ route('reportes.index') }}"
        class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition-colors
          {{ request()->routeIs('reportes*')
            ? 'bg-slate-700/50 text-purple-400 font-medium'
            : 'text-white/55 hover:bg-slate-800 hover:text-white/85' }}">
        <i data-lucide="bar-chart-2" class="w-4 h-4" style="stroke-width:1.5"></i>
        Reportes
      </a>

      <a href="{{ route('config.index') }}"
        class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition-colors
          {{ request()->routeIs('config*')
            ? 'bg-slate-700/50 text-purple-400 font-medium'
            : 'text-white/55 hover:bg-slate-800 hover:text-white/85' }}">
        <i data-lucide="settings" class="w-4 h-4" style="stroke-width:1.5"></i>
        Config
      </a>

    </nav>

    <div class="border-t border-white/[0.06] px-3 py-3">
      <div class="flex items-center gap-2.5 px-3 py-2 rounded-lg">
        <i data-lucide="user-circle" class="w-4 h-4 text-white/30 shrink-0" style="stroke-width:1.5"></i>
        <span class="text-xs text-white/40 truncate flex-1">Administrador</span>
      </div>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit"
          class="w-full flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm
                 text-white/40 hover:bg-red-500/10 hover:text-red-400 transition-colors">
          <i data-lucide="log-out" class="w-4 h-4 shrink-0" style="stroke-width:1.5"></i>
          Salir
        </button>
      </form>
    </div>

  </aside>

  <div class="flex flex-col h-full md:pl-56">

    <main class="flex-1 overflow-y-auto p-6 pb-20 md:pb-6">
      @yield('content')
    </main>

    <footer class="md:hidden fixed bottom-0 left-0 right-0 h-16
                   bg-gray-900 border-t border-white/[0.06]
                   flex justify-around items-center z-50">

      <a href="{{ route('tickets.index') }}"
        class="flex flex-col items-center gap-1 {{ request()->routeIs('tickets*') ? 'text-purple-400' : 'text-white/35' }}">
        <i data-lucide="ticket" class="w-5 h-5" style="stroke-width:1.5"></i>
        <span class="text-[10px]">Tickets</span>
      </a>

      <a href="{{ route('pickers.index') }}"
        class="flex flex-col items-center gap-1 {{ request()->routeIs('pickers*') ? 'text-purple-400' : 'text-white/35' }}">
        <i data-lucide="users" class="w-5 h-5" style="stroke-width:1.5"></i>
        <span class="text-[10px]">Pickers</span>
      </a>

      <a href="{{ route('productos.index') }}"
        class="flex flex-col items-center gap-1 {{ request()->routeIs('productos*') ? 'text-purple-400' : 'text-white/35' }}">
        <i data-lucide="clock" class="w-5 h-5" style="stroke-width:1.5"></i>
        <span class="text-[10px]">Turnos</span>
      </a>

      <a href="{{ route('reportes.index') }}"
        class="flex flex-col items-center gap-1 {{ request()->routeIs('reportes*') ? 'text-purple-400' : 'text-white/35' }}">
        <i data-lucide="bar-chart-2" class="w-5 h-5" style="stroke-width:1.5"></i>
        <span class="text-[10px]">Reportes</span>
      </a>

      <a href="{{ route('config.index') }}"
        class="flex flex-col items-center gap-1 {{ request()->routeIs('config*') ? 'text-purple-400' : 'text-white/35' }}">
        <i data-lucide="settings" class="w-5 h-5" style="stroke-width:1.5"></i>
        <span class="text-[10px]">Config</span>
      </a>

    </footer>

  </div>

  <script>lucide.createIcons();</script>
  @livewireScripts
</body>
</html>