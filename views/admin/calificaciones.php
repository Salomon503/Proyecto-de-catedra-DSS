<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="mb-8">
    <h1 class="text-2xl font-bold text-slate-800">Control de Calificaciones</h1>
    <p class="text-slate-500 mt-1">Supervise la satisfacción de los pacientes con el servicio médico.</p>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col">
        <div class="text-sm font-medium text-slate-500 mb-2">Total Calificaciones</div>
        <div class="text-3xl font-bold text-slate-800 mb-1"><?php echo $stats['total']; ?></div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col">
        <div class="text-sm font-medium text-slate-500 mb-2">Promedio General</div>
        <div class="text-3xl font-bold text-amber-500 mb-1 flex items-center gap-2">
            <?php echo $stats['promedio']; ?>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-6 h-6"><path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd" /></svg>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col">
        <div class="text-sm font-medium text-slate-500 mb-2">Excelentes (4-5★)</div>
        <div class="text-3xl font-bold text-emerald-600 mb-1"><?php echo $stats['excelentes']; ?></div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col">
        <div class="text-sm font-medium text-slate-500 mb-2">Áreas de Mejora (1-2★)</div>
        <div class="text-3xl font-bold text-red-600 mb-1"><?php echo $stats['malas']; ?></div>
    </div>
</div>

<?php if(isset($_GET['success'])): ?>
    <div class="bg-emerald-50 text-emerald-600 p-4 rounded-lg mb-6 border border-emerald-100 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        <?php 
            if($_GET['success'] == 'deleted') echo 'Calificación eliminada correctamente.';
        ?>
    </div>
<?php endif; ?>

<?php if(isset($_GET['error'])): ?>
    <div class="bg-red-50 text-red-600 p-4 rounded-lg mb-6 border border-red-100 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
        </svg>
        <?php 
            if($_GET['error'] == 'delete_failed') echo 'Hubo un error al eliminar la calificación.';
        ?>
    </div>
<?php endif; ?>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100 text-sm text-slate-500">
                    <th class="p-4 font-medium">Fecha</th>
                    <th class="p-4 font-medium">Paciente</th>
                    <th class="p-4 font-medium">Doctor</th>
                    <th class="p-4 font-medium">Puntuación</th>
                    <th class="p-4 font-medium">Comentario</th>
                    <th class="p-4 font-medium text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                <?php foreach($calificaciones as $cal): ?>
                    <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors">
                        <td class="p-4 text-slate-500 whitespace-nowrap">
                            <?php echo date('d/m/Y', strtotime($cal['fecha_calificacion'])); ?>
                        </td>
                        <td class="p-4 font-medium text-slate-800">
                            <?php echo htmlspecialchars($cal['paciente_nombre']); ?>
                        </td>
                        <td class="p-4">
                            <div class="font-medium text-slate-800">Dr. <?php echo htmlspecialchars($cal['medico_nombre']); ?></div>
                            <div class="text-xs text-slate-500"><?php echo htmlspecialchars($cal['nombre_especialidad']); ?></div>
                        </td>
                        <td class="p-4">
                            <div class="flex items-center gap-0.5 text-amber-500">
                                <?php for($i=1; $i<=5; $i++): ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 <?php echo $i <= $cal['puntuacion'] ? 'text-amber-500' : 'text-slate-200'; ?>"><path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd" /></svg>
                                <?php endfor; ?>
                            </div>
                        </td>
                        <td class="p-4 text-slate-600 italic">
                            <?php echo !empty($cal['comentario']) ? '"' . htmlspecialchars($cal['comentario']) . '"' : '<span class="text-slate-400 not-italic">Sin comentario</span>'; ?>
                        </td>
                        <td class="p-4 text-right">
                            <form action="index.php?action=eliminar_calificacion" method="POST" class="inline" onsubmit="return confirm('¿Está seguro de que desea eliminar esta calificación por contenido indebido?');">
                                <input type="hidden" name="id" value="<?php echo $cal['id_calificacion']; ?>">
                                <button type="submit" class="p-2 text-slate-400 hover:text-red-600 transition-colors" title="Eliminar">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if(empty($calificaciones)): ?>
                    <tr>
                        <td colspan="6" class="p-8 text-center text-slate-500">Aún no hay calificaciones registradas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
