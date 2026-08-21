<div class="space-y-4 max-w-xl">
  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
      <label for="first_name" class="block text-xs font-semibold uppercase tracking-wider text-white/40 mb-1.5">Nombre *</label>
      <input type="text" name="first_name" id="first_name" required
             value="{{ old('first_name', $picker->first_name) }}"
             class="w-full px-3 py-2 bg-gray-900 border border-white/[0.08] rounded-lg text-sm text-white focus:outline-none focus:border-purple-500/50">
      @error('first_name') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
      <label for="last_name" class="block text-xs font-semibold uppercase tracking-wider text-white/40 mb-1.5">Apellido *</label>
      <input type="text" name="last_name" id="last_name" required
             value="{{ old('last_name', $picker->last_name) }}"
             class="w-full px-3 py-2 bg-gray-900 border border-white/[0.08] rounded-lg text-sm text-white focus:outline-none focus:border-purple-500/50">
      @error('last_name') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
    </div>
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
      <label for="display_name" class="block text-xs font-semibold uppercase tracking-wider text-white/40 mb-1.5">Nombre para Mostrar</label>
      <input type="text" name="display_name" id="display_name" placeholder="Ej: Alan M."
             value="{{ old('display_name', $picker->getRawOriginal('display_name')) }}"
             class="w-full px-3 py-2 bg-gray-900 border border-white/[0.08] rounded-lg text-sm text-white focus:outline-none focus:border-purple-500/50">
      @error('display_name') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
      <label for="employee_code" class="block text-xs font-semibold uppercase tracking-wider text-white/40 mb-1.5">Código Empleado</label>
      <input type="text" name="employee_code" id="employee_code" placeholder="Ej: PCK-001"
             value="{{ old('employee_code', $picker->employee_code) }}"
             class="w-full px-3 py-2 bg-gray-900 border border-white/[0.08] rounded-lg text-sm text-white font-mono focus:outline-none focus:border-purple-500/50">
      @error('employee_code') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
    </div>
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
      <label for="zone_assigned" class="block text-xs font-semibold uppercase tracking-wider text-white/40 mb-1.5">Zona Asignada</label>
      <input type="text" name="zone_assigned" id="zone_assigned" placeholder="Ej: Pasillo A / Central"
             value="{{ old('zone_assigned', $picker->zone_assigned) }}"
             class="w-full px-3 py-2 bg-gray-900 border border-white/[0.08] rounded-lg text-sm text-white focus:outline-none focus:border-purple-500/50">
      @error('zone_assigned') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
      <label for="status" class="block text-xs font-semibold uppercase tracking-wider text-white/40 mb-1.5">Estado Operativo</label>
      <select name="status" id="status" class="w-full px-3 py-2 bg-gray-900 border border-white/[0.08] rounded-lg text-sm text-white focus:outline-none focus:border-purple-500/50">
        <option value="active" @selected(old('status', $picker->status) === 'active')>Activo / Disponible</option>
        <option value="busy" @selected(old('status', $picker->status) === 'busy')>En Picking</option>
        <option value="break" @selected(old('status', $picker->status) === 'break')>En Pausa</option>
        <option value="inactive" @selected(old('status', $picker->status) === 'inactive')>Inactivo</option>
      </select>
      @error('status') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
    </div>
  </div>

  <div class="pt-2">
    <label class="flex items-center gap-2.5 cursor-pointer">
      <input type="checkbox" name="is_active" value="1" 
             @checked(old('is_active', $picker->is_active ?? true))
             class="w-4 h-4 rounded bg-gray-900 border-white/20 text-purple-600 focus:ring-0 focus:ring-offset-0">
      <span class="text-xs text-white/70 select-none">Habilitado en el sistema</span>
    </label>
  </div>

  <div class="flex items-center gap-3 pt-4 border-t border-white/[0.06]">
    <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-500 text-white text-sm font-medium rounded-lg transition-colors">
      Guardar Cambios
    </button>
    <a href="{{ route('pickers.index') }}" class="px-4 py-2 bg-white/[0.04] hover:bg-white/[0.08] text-white/60 hover:text-white text-sm rounded-lg transition-colors">
      Cancelar
    </a>
  </div>
</div>