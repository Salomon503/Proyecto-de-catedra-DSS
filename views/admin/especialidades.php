<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Gestión de Especialidades</h1>
        <p class="text-slate-500 mt-1">Administre las especialidades médicas del sistema.</p>
    </div>
    <button onclick="document.getElementById('modal-nueva-especialidad').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
        Nueva Especialidad
    </button>
</div>

<?php if(isset($_GET['success'])): ?>
    <div class="bg-emerald-50 text-emerald-600 p-4 rounded-lg mb-6 border border-emerald-100 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        <?php 
            if($_GET['success'] == 'created') echo 'Especialidad creada correctamente.';
            if($_GET['success'] == 'updated') echo 'Especialidad actualizada correctamente.';
            if($_GET['success'] == 'deleted') echo 'Especialidad eliminada correctamente.';
        ?>
    </div>
<?php endif; ?>

<?php if(isset($_GET['error'])): ?>
    <div class="bg-red-50 text-red-600 p-4 rounded-lg mb-6 border border-red-100 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
        </svg>
        <?php 
            if($_GET['error'] == 'fk_violation') echo 'No se puede eliminar esta especialidad porque hay doctores asignados a ella.';
        ?>
    </div>
<?php endif; ?>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100 text-sm text-slate-500">
                    <th class="p-4 font-medium">ID</th>
                    <th class="p-4 font-medium">Nombre de la Especialidad</th>
                    <th class="p-4 font-medium text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                <?php foreach($especialidades as $e): ?>
                    <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors">
                        <td class="p-4 text-slate-500">#<?php echo $e['id_especialidad']; ?></td>
                        <td class="p-4 font-medium text-slate-800">
                            <?php echo htmlspecialchars($e['nombre_especialidad']); ?>
                        </td>
                        <td class="p-4 text-right flex justify-end gap-2">
                            <button onclick="editarEspecialidad(<?php echo $e['id_especialidad']; ?>, '<?php echo htmlspecialchars($e['nombre_especialidad'], ENT_QUOTES); ?>')" class="p-2 text-slate-400 hover:text-blue-600 transition-colors" title="Editar">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                            </button>
                            <form action="index.php?action=eliminar_especialidad" method="POST" class="inline" onsubmit="return confirm('¿Está seguro de que desea eliminar esta especialidad?');">
                                <input type="hidden" name="id" value="<?php echo $e['id_especialidad']; ?>">
                                <button type="submit" class="p-2 text-slate-400 hover:text-red-600 transition-colors" title="Eliminar">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if(empty($especialidades)): ?>
                    <tr>
                        <td colspan="3" class="p-8 text-center text-slate-500">No hay especialidades registradas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Nueva Especialidad -->
<div id="modal-nueva-especialidad" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 class="font-bold text-slate-800">Nueva Especialidad</h3>
            <button onclick="document.getElementById('modal-nueva-especialidad').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <form action="index.php?action=guardar_especialidad" method="POST" class="p-6">
            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 mb-1">Nombre <span class="text-red-500">*</span></label>
                <input type="text" name="nombre_especialidad" required class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-slate-700" placeholder="Ej. Pediatría">
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modal-nueva-especialidad').classList.add('hidden')" class="px-4 py-2 text-slate-600 hover:bg-slate-100 font-medium rounded-lg transition-colors">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Editar Especialidad -->
<div id="modal-editar-especialidad" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 class="font-bold text-slate-800">Editar Especialidad</h3>
            <button onclick="document.getElementById('modal-editar-especialidad').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <form action="index.php?action=actualizar_especialidad" method="POST" class="p-6">
            <input type="hidden" name="id_especialidad" id="edit_id_especialidad">
            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 mb-1">Nombre <span class="text-red-500">*</span></label>
                <input type="text" name="nombre_especialidad" id="edit_nombre_especialidad" required class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-slate-700">
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modal-editar-especialidad').classList.add('hidden')" class="px-4 py-2 text-slate-600 hover:bg-slate-100 font-medium rounded-lg transition-colors">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">Actualizar</button>
            </div>
        </form>
    </div>
</div>

<script>
function editarEspecialidad(id, nombre) {
    document.getElementById('edit_id_especialidad').value = id;
    document.getElementById('edit_nombre_especialidad').value = nombre;
    document.getElementById('modal-editar-especialidad').classList.remove('hidden');
}
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
