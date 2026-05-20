<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="mb-8 flex items-center gap-4">
    <a href="index.php?action=horarios" class="flex items-center gap-2 text-slate-500 hover:text-slate-800 font-medium transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
        Volver
    </a>
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Editar Horario</h1>
        <p class="text-slate-500 mt-1">Modificar bloque de disponibilidad médica.</p>
    </div>
</div>

<div class="max-w-xl bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
    <form action="index.php?action=actualizar_horario" method="POST" class="space-y-4">
        <input type="hidden" name="id_horario" value="<?php echo $horario['id_horario']; ?>">
        
        <div>
            <label for="dia_semana" class="block text-sm font-medium text-slate-700 mb-1">Día de la Semana</label>
            <select id="dia_semana" name="dia_semana" required class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white">
                <?php
                $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
                foreach($dias as $dia) {
                    $selected = ($horario['dia_semana'] == $dia) ? 'selected' : '';
                    echo "<option value=\"$dia\" $selected>$dia</option>";
                }
                ?>
            </select>
        </div>
        
        <div>
            <label class="flex items-center gap-2 mb-4 mt-6">
                <input type="checkbox" name="es_dia_descanso" id="es_dia_descanso" class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500" <?php echo $horario['es_dia_descanso'] ? 'checked' : ''; ?>>
                <span class="text-sm font-medium text-slate-700">Día de Descanso / No disponible</span>
            </label>
        </div>

        <div id="horas_normales" class="space-y-4" style="<?php echo $horario['es_dia_descanso'] ? 'display: none;' : ''; ?>">
            <div>
                <label for="hora_inicio" class="block text-sm font-medium text-slate-700 mb-1">Hora de Inicio</label>
                <input type="time" id="hora_inicio" name="hora_inicio" <?php echo !$horario['es_dia_descanso'] ? 'required' : ''; ?> value="<?php echo $horario['hora_inicio']; ?>"
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
            </div>

            <div>
                <label for="hora_fin" class="block text-sm font-medium text-slate-700 mb-1">Hora de Fin</label>
                <input type="time" id="hora_fin" name="hora_fin" <?php echo !$horario['es_dia_descanso'] ? 'required' : ''; ?> value="<?php echo $horario['hora_fin']; ?>"
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
            </div>

            <div>
                <label for="descanso_inicio" class="block text-sm font-medium text-slate-700 mb-1">Inicio de Almuerzo/Descanso (Opcional)</label>
                <input type="time" id="descanso_inicio" name="descanso_inicio" value="<?php echo $horario['descanso_inicio']; ?>"
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
            </div>

            <div>
                <label for="descanso_fin" class="block text-sm font-medium text-slate-700 mb-1">Fin de Almuerzo/Descanso (Opcional)</label>
                <input type="time" id="descanso_fin" name="descanso_fin" value="<?php echo $horario['descanso_fin']; ?>"
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
            </div>
        </div>

        <div class="pt-6 flex justify-end gap-4">
            <a href="index.php?action=horarios" class="px-6 py-2.5 rounded-lg border border-slate-300 text-slate-700 font-medium hover:bg-slate-50 transition-colors">Cancelar</a>
            <button type="submit" class="px-6 py-2.5 rounded-lg font-medium bg-blue-600 hover:bg-blue-700 text-white transition-colors">
                Guardar Cambios
            </button>
        </div>
    </form>
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
