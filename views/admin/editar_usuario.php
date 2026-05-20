<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="mb-8 flex items-center gap-4">
    <a href="index.php?action=usuarios" class="p-2 text-slate-400 hover:text-slate-600 bg-white rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Editar Usuario</h1>
        <p class="text-slate-500 mt-1">Actualice la información del usuario en el sistema.</p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 max-w-2xl">
    <form action="index.php?action=actualizar_usuario" method="POST" class="space-y-6">
        <input type="hidden" name="id_usuario" value="<?php echo $usuario['id_usuario']; ?>">
        
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Correo Electrónico (Solo Lectura)</label>
            <input type="email" value="<?php echo htmlspecialchars($usuario['correo']); ?>" disabled
                class="w-full px-4 py-2.5 bg-slate-50 rounded-lg border border-slate-200 text-slate-500 cursor-not-allowed">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="primer_nombre" class="block text-sm font-medium text-slate-700 mb-1">Primer Nombre</label>
                <input type="text" id="primer_nombre" name="primer_nombre" value="<?php echo htmlspecialchars($usuario['primer_nombre']); ?>" required 
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
            </div>
            <div>
                <label for="segundo_nombre" class="block text-sm font-medium text-slate-700 mb-1">Segundo Nombre</label>
                <input type="text" id="segundo_nombre" name="segundo_nombre" value="<?php echo htmlspecialchars($usuario['segundo_nombre']); ?>" 
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="primer_apellido" class="block text-sm font-medium text-slate-700 mb-1">Primer Apellido</label>
                <input type="text" id="primer_apellido" name="primer_apellido" value="<?php echo htmlspecialchars($usuario['primer_apellido']); ?>" required 
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
            </div>
            <div>
                <label for="segundo_apellido" class="block text-sm font-medium text-slate-700 mb-1">Segundo Apellido</label>
                <input type="text" id="segundo_apellido" name="segundo_apellido" value="<?php echo htmlspecialchars($usuario['segundo_apellido']); ?>" 
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
            </div>
        </div>

        <div>
            <label for="id_rol" class="block text-sm font-medium text-slate-700 mb-1">Rol en el Sistema</label>
            <select id="id_rol" name="id_rol" required class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white">
                <?php foreach($roles as $rol): ?>
                    <option value="<?php echo $rol['id_rol']; ?>" <?php echo ($usuario['id_rol'] == $rol['id_rol']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($rol['nombre_rol']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <p class="text-xs text-slate-500 mt-1">Precaución: Cambiar el rol puede afectar los datos relacionados al usuario (Pacientes/Doctores).</p>
        </div>

        <div class="pt-4 flex justify-end gap-3">
            <a href="index.php?action=usuarios" class="px-6 py-2.5 rounded-lg font-medium text-slate-600 hover:bg-slate-100 transition-colors">Cancelar</a>
            <button type="submit" class="px-6 py-2.5 rounded-lg font-medium bg-blue-600 hover:bg-blue-700 text-white transition-colors">Guardar Cambios</button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
