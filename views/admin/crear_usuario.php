<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="max-w-3xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="index.php?action=usuarios" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Nuevo Empleado</h1>
            <p class="text-slate-500 mt-1">Registre un nuevo administrador o médico en el sistema.</p>
        </div>
    </div>

    <?php if(isset($_GET['error']) && $_GET['error'] == 'duplicate'): ?>
    <div class="bg-red-50 text-red-600 p-4 rounded-lg mb-6 border border-red-100 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
        El correo electrónico ingresado o el número de colegiado ya están registrados en el sistema.
    </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <form action="index.php?action=guardar_usuario" method="POST" class="p-6 md:p-8">
            
            <h2 class="text-lg font-semibold text-slate-800 mb-6 pb-2 border-b border-slate-100">Información Personal</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Primer Nombre <span class="text-red-500">*</span></label>
                    <input type="text" name="primer_nombre" required class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-slate-700">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Segundo Nombre</label>
                    <input type="text" name="segundo_nombre" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-slate-700">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Primer Apellido <span class="text-red-500">*</span></label>
                    <input type="text" name="primer_apellido" required class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-slate-700">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Segundo Apellido</label>
                    <input type="text" name="segundo_apellido" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-slate-700">
                </div>
            </div>

            <h2 class="text-lg font-semibold text-slate-800 mb-6 pb-2 border-b border-slate-100">Credenciales de Acceso</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Correo Electrónico <span class="text-red-500">*</span></label>
                    <input type="email" name="correo" required class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-slate-700">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Contraseña Inicial <span class="text-red-500">*</span></label>
                    <input type="password" name="contrasena" required minlength="8" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-slate-700">
                    <p class="text-xs text-slate-500 mt-1">Mínimo 8 caracteres.</p>
                </div>
            </div>

            <h2 class="text-lg font-semibold text-slate-800 mb-6 pb-2 border-b border-slate-100">Rol en el Sistema</h2>
            <div class="grid grid-cols-1 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Asignar Rol <span class="text-red-500">*</span></label>
                    <select name="id_rol" id="id_rol" required class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-slate-700">
                        <option value="">Seleccione un rol...</option>
                        <?php foreach($roles as $rol): ?>
                            <option value="<?php echo $rol['id_rol']; ?>"><?php echo htmlspecialchars($rol['nombre_rol']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Campos exclusivos para Médicos -->
            <div id="campos_medico" class="hidden">
                <h2 class="text-lg font-semibold text-slate-800 mb-6 pb-2 border-b border-slate-100">Información Médica</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Especialidad <span class="text-red-500">*</span></label>
                        <select name="id_especialidad" id="id_especialidad" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-slate-700">
                            <option value="">Seleccione especialidad...</option>
                            <?php foreach($especialidades as $esp): ?>
                                <option value="<?php echo $esp['id_especialidad']; ?>"><?php echo htmlspecialchars($esp['nombre_especialidad']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Número de Colegiado <span class="text-red-500">*</span></label>
                        <input type="text" name="numero_colegiado" id="numero_colegiado" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-slate-700">
                    </div>
                </div>
            </div>

            <div class="flex gap-4 mt-10">
                <a href="index.php?action=usuarios" class="flex-1 px-4 py-3 border border-slate-300 text-slate-700 font-medium rounded-lg hover:bg-slate-50 transition-colors text-center">Cancelar</a>
                <button type="submit" class="flex-1 px-4 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">Registrar Empleado</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const rolSelect = document.getElementById('id_rol');
    const camposMedico = document.getElementById('campos_medico');
    const especialidadSelect = document.getElementById('id_especialidad');
    const colegiadoInput = document.getElementById('numero_colegiado');

    rolSelect.addEventListener('change', function() {
        // 2 es el ID de Doctor (ajustar si tu DB usa otro)
        if (this.value == '2') {
            camposMedico.classList.remove('hidden');
            especialidadSelect.required = true;
            colegiadoInput.required = true;
        } else {
            camposMedico.classList.add('hidden');
            especialidadSelect.required = false;
            colegiadoInput.required = false;
            // Limpiar valores
            especialidadSelect.value = '';
            colegiadoInput.value = '';
        }
    });
});
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
