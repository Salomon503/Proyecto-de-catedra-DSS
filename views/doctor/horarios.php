<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Mis Horarios</h1>
        <p class="text-slate-500 mt-1">Gestione su disponibilidad para las citas médicas.</p>
    </div>
</div>

<?php if(isset($_GET['success'])): ?>
    <div class="bg-emerald-50 text-emerald-600 p-4 rounded-lg mb-6 border border-emerald-100 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        <?php 
            if($_GET['success'] == 'added') echo 'Horario agregado exitosamente.';
            if($_GET['success'] == 'deleted') echo 'Horario eliminado exitosamente.';
        ?>
    </div>
<?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Formulario para agregar -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h2 class="text-lg font-bold text-slate-800 mb-4 border-b border-slate-100 pb-2">Agregar Disponibilidad</h2>
            <form action="index.php?action=guardar_horario" method="POST" class="space-y-4">
                <div>
                    <label for="dia_semana" class="block text-sm font-medium text-slate-700 mb-1">Día de la Semana</label>
                    <select id="dia_semana" name="dia_semana" required class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white">
                        <option value="Lunes">Lunes</option>
                        <option value="Martes">Martes</option>
                        <option value="Miércoles">Miércoles</option>
                        <option value="Jueves">Jueves</option>
                        <option value="Viernes">Viernes</option>
                        <option value="Sábado">Sábado</option>
                        <option value="Domingo">Domingo</option>
                    </select>
                </div>
                
                <div>
                    <label class="flex items-center gap-2 mb-4">
                        <input type="checkbox" name="es_dia_descanso" id="es_dia_descanso" class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                        <span class="text-sm font-medium text-slate-700">Día de Descanso / No disponible</span>
                    </label>
                </div>

                <div id="horas_normales" class="space-y-4">
                    <div>
                        <label for="hora_inicio" class="block text-sm font-medium text-slate-700 mb-1">Hora de Inicio</label>
                        <input type="time" id="hora_inicio" name="hora_inicio" required 
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    </div>

                    <div>
                        <label for="hora_fin" class="block text-sm font-medium text-slate-700 mb-1">Hora de Fin</label>
                        <input type="time" id="hora_fin" name="hora_fin" required 
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    </div>

                    <div>
                        <label for="descanso_inicio" class="block text-sm font-medium text-slate-700 mb-1">Inicio de Almuerzo/Descanso (Opcional)</label>
                        <input type="time" id="descanso_inicio" name="descanso_inicio" 
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    </div>

                    <div>
                        <label for="descanso_fin" class="block text-sm font-medium text-slate-700 mb-1">Fin de Almuerzo/Descanso (Opcional)</label>
                        <input type="time" id="descanso_fin" name="descanso_fin" 
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full px-4 py-2.5 rounded-lg font-medium bg-blue-600 hover:bg-blue-700 text-white transition-colors">
                        Agregar Horario
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de horarios -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h2 class="text-lg font-bold text-slate-800">Horarios Configurados</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-sm text-slate-500">
                            <th class="p-4 font-medium">Día</th>
                            <th class="p-4 font-medium">Hora Inicio</th>
                            <th class="p-4 font-medium">Hora Fin</th>
                            <th class="p-4 font-medium">Descanso</th>
                            <th class="p-4 font-medium text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        <?php foreach($horarios as $h): ?>
                            <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors <?php echo $h['es_dia_descanso'] ? 'bg-slate-100' : ''; ?>">
                                <td class="p-4 font-medium text-slate-800">
                                    <?php echo htmlspecialchars($h['dia_semana']); ?>
                                    <?php if($h['es_dia_descanso']): ?>
                                        <span class="ml-2 inline-block px-2 py-0.5 rounded text-xs bg-slate-200 text-slate-600 font-medium">Día Libre</span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-4 text-slate-600">
                                    <?php echo $h['es_dia_descanso'] ? '-' : date("g:i A", strtotime($h['hora_inicio'])); ?>
                                </td>
                                <td class="p-4 text-slate-600">
                                    <?php echo $h['es_dia_descanso'] ? '-' : date("g:i A", strtotime($h['hora_fin'])); ?>
                                </td>
                                <td class="p-4 text-slate-600">
                                    <?php 
                                        if ($h['es_dia_descanso']) {
                                            echo '-';
                                        } else if (!empty($h['descanso_inicio']) && !empty($h['descanso_fin'])) {
                                            echo date("g:i A", strtotime($h['descanso_inicio'])) . ' - ' . date("g:i A", strtotime($h['descanso_fin']));
                                        } else {
                                            echo 'Sin descanso';
                                        }
                                    ?>
                                </td>
                                <td class="p-4 text-right flex justify-end gap-2">
                                    <a href="index.php?action=editar_horario&id=<?php echo $h['id_horario']; ?>" class="p-2 text-slate-400 hover:text-blue-600 transition-colors" title="Editar">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.89 1.113l-3.122.781a.75.75 0 01-.926-.926l.781-3.122a4.5 4.5 0 011.113-1.89L16.862 4.487z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 7.125L16.875 4.5" /></svg>
                                    </a>
                                    <form action="index.php?action=eliminar_horario" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este bloque de horario?');">
                                        <input type="hidden" name="id" value="<?php echo $h['id_horario']; ?>">
                                        <button type="submit" class="p-2 text-slate-400 hover:text-red-600 transition-colors" title="Eliminar">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if(empty($horarios)): ?>
                            <tr>
                                <td colspan="5" class="p-8 text-center text-slate-500">No tiene horarios configurados.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    const checkboxDescanso = document.getElementById('es_dia_descanso');
    const horasNormales = document.getElementById('horas_normales');
    const horaInicio = document.getElementById('hora_inicio');
    const horaFin = document.getElementById('hora_fin');

    checkboxDescanso.addEventListener('change', function() {
        if(this.checked) {
            horasNormales.style.display = 'none';
            horaInicio.removeAttribute('required');
            horaFin.removeAttribute('required');
        } else {
            horasNormales.style.display = 'block';
            horaInicio.setAttribute('required', 'required');
            horaFin.setAttribute('required', 'required');
        }
    });
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
