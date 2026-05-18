<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Gestión de Usuarios</h1>
        <p class="text-slate-500 mt-1">Administre todos los usuarios del sistema.</p>
    </div>
    <a href="index.php?action=crear_usuario" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
        Nuevo Empleado
    </a>
</div>

<?php if(isset($_GET['success'])): ?>
    <div class="bg-emerald-50 text-emerald-600 p-4 rounded-lg mb-6 border border-emerald-100 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        <?php 
            if($_GET['success'] == 'updated') echo 'Usuario actualizado correctamente.';
            if($_GET['success'] == 'deleted') echo 'Usuario eliminado correctamente.';
            if($_GET['success'] == 'created') echo 'Empleado registrado correctamente en el sistema.';
        ?>
    </div>
<?php endif; ?>

<?php if(isset($_GET['error'])): ?>
    <div class="bg-red-50 text-red-600 p-4 rounded-lg mb-6 border border-red-100 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
        </svg>
        <?php 
            if($_GET['error'] == 'self_delete') echo 'No puedes eliminar tu propia cuenta.';
            if($_GET['error'] == 'fk_violation') echo 'No se puede eliminar este usuario porque tiene datos dependientes (citas, historial, etc).';
        ?>
    </div>
<?php endif; ?>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100 text-sm text-slate-500">
                    <th class="p-4 font-medium">ID</th>
                    <th class="p-4 font-medium">Nombre Completo</th>
                    <th class="p-4 font-medium">Correo Electrónico</th>
                    <th class="p-4 font-medium">Rol</th>
                    <th class="p-4 font-medium text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                <?php foreach($usuarios as $u): ?>
                    <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors">
                        <td class="p-4 text-slate-500">#<?php echo $u['id_usuario']; ?></td>
                        <td class="p-4 font-medium text-slate-800">
                            <?php echo htmlspecialchars($u['primer_nombre'] . ' ' . $u['primer_apellido']); ?>
                        </td>
                        <td class="p-4 text-slate-500"><?php echo htmlspecialchars($u['correo']); ?></td>
                        <td class="p-4">
                            <?php 
                                $badgeClass = 'bg-slate-100 text-slate-600';
                                if($u['nombre_rol'] == 'Administrador') $badgeClass = 'bg-purple-100 text-purple-700';
                                if($u['nombre_rol'] == 'Doctor') $badgeClass = 'bg-blue-100 text-blue-700';
                                if($u['nombre_rol'] == 'Paciente') $badgeClass = 'bg-emerald-100 text-emerald-700';
                            ?>
                            <span class="px-3 py-1 <?php echo $badgeClass; ?> text-xs font-medium rounded-full">
                                <?php echo htmlspecialchars($u['nombre_rol']); ?>
                            </span>
                        </td>
                        <td class="p-4 text-right flex justify-end gap-2">
                            <a href="index.php?action=editar_usuario&id=<?php echo $u['id_usuario']; ?>" class="p-2 text-slate-400 hover:text-blue-600 transition-colors" title="Editar">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                            </a>
                            <form action="index.php?action=eliminar_usuario" method="POST" class="inline" onsubmit="return confirm('¿Está seguro de que desea eliminar a este usuario? Esto no se puede deshacer.');">
                                <input type="hidden" name="id" value="<?php echo $u['id_usuario']; ?>">
                                <button type="submit" class="p-2 text-slate-400 hover:text-red-600 transition-colors" title="Eliminar">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if(empty($usuarios)): ?>
                    <tr>
                        <td colspan="5" class="p-8 text-center text-slate-500">No se encontraron usuarios en el sistema.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
