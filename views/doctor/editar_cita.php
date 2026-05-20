<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="mb-8 flex items-center gap-4">
    <a href="index.php?action=citas" class="flex items-center gap-2 text-slate-500 hover:text-slate-800 font-medium transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
        Volver
    </a>
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Editar Cita</h1>
        <p class="text-slate-500 mt-1">Actualizar detalles de la cita</p>
    </div>
</div>

<form action="index.php?action=actualizar_cita" method="POST">
    <input type="hidden" name="id_cita" value="<?php echo htmlspecialchars($cita['id_cita'] ?? ''); ?>">
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form Details -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h2 class="text-lg font-semibold text-slate-800 mb-6">Detalles de la Cita</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Paciente -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Paciente <span class="text-red-500">*</span></label>
                    <select name="id_paciente" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-slate-700 bg-slate-50" readonly>
                        <option value="1" selected><?php echo htmlspecialchars($cita['paciente_nombre'] ?? ''); ?> - <?php echo htmlspecialchars($cita['paciente_correo'] ?? ''); ?></option>
                    </select>
                </div>

                <!-- Doctor -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Doctor <span class="text-red-500">*</span></label>
                    <select name="id_medico" id="id_medico" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-slate-700 <?php echo ($_SESSION['rol'] == 2) ? 'bg-slate-50' : ''; ?>" <?php echo ($_SESSION['rol'] == 2) ? 'readonly style="pointer-events: none;"' : ''; ?>>
                        <?php foreach($medicos as $m): ?>
                            <?php 
                                $selected = '';
                                if ($_SESSION['rol'] == 2) {
                                    require_once __DIR__ . '/../../models/Medico.php';
                                    $medModel = new Medico();
                                    $medData = $medModel->obtenerMedicoPorUsuarioId($_SESSION['user_id']);
                                    if ($medData && $medData['id_medico'] == $m['id_medico']) {
                                        $selected = 'selected';
                                    }
                                } else {
                                    if(isset($cita['id_medico']) && $cita['id_medico'] == $m['id_medico']) {
                                        $selected = 'selected';
                                    }
                                }
                            ?>
                            <option value="<?php echo $m['id_medico']; ?>" <?php echo $selected; ?>>
                                Dr. <?php echo htmlspecialchars($m['primer_nombre'] . ' ' . $m['primer_apellido'] . ' - ' . $m['especialidad']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Fecha -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Fecha <span class="text-red-500">*</span></label>
                    <input type="date" name="fecha" id="fecha" value="<?php echo htmlspecialchars($cita['fecha'] ?? ''); ?>" required 
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-slate-700">
                </div>

                <!-- Hora -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Hora <span class="text-red-500">*</span></label>
                    <select name="hora" id="hora" required class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-slate-700">
                        <!-- Se llenará con JavaScript, pero guardamos la hora original como preseleccionada -->
                        <option value="<?php echo date('H:i', strtotime($cita['hora'])); ?>" selected>
                            <?php echo date('g:i A', strtotime($cita['hora'])); ?> (Hora Actual)
                        </option>
                    </select>
                    <!-- Variable oculta para JS -->
                    <input type="hidden" id="hora_original" value="<?php echo date('H:i', strtotime($cita['hora'])); ?>">
                </div>

                <!-- Tipo de Cita -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Tipo de Cita</label>
                    <select name="tipo_cita" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-slate-700">
                        <option value="Telemedicina" selected>Telemedicina</option>
                        <option value="Presencial">Presencial</option>
                    </select>
                </div>

                <!-- Estado -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Estado</label>
                    <select name="estado" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-slate-700">
                        <option value="Programada" <?php echo (isset($cita['estado']) && $cita['estado'] == 'Programada') ? 'selected' : ''; ?>>Programada</option>
                        <option value="Completada" <?php echo (isset($cita['estado']) && $cita['estado'] == 'Completada') ? 'selected' : ''; ?>>Completada</option>
                        <option value="Cancelada" <?php echo (isset($cita['estado']) && $cita['estado'] == 'Cancelada') ? 'selected' : ''; ?>>Cancelada</option>
                    </select>
                </div>

                <!-- Motivo -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Motivo de la Visita <span class="text-red-500">*</span></label>
                    <input type="text" name="motivo" value="<?php echo htmlspecialchars($cita['motivo'] ?? ''); ?>" required 
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-slate-700">
                </div>

                <!-- Notas -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Notas Adicionales</label>
                    <textarea name="notas" rows="3" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-slate-700 resize-none">Chequeo trimestral de presión</textarea>
                </div>
            </div>
        </div>

        <!-- Resumen Sidebar -->
        <div class="bg-slate-50/50 rounded-2xl border border-slate-200 p-6 h-fit">
            <h2 class="text-lg font-semibold text-slate-800 mb-6">Resumen</h2>
            
            <div class="space-y-4">
                <div>
                    <span class="text-sm text-slate-500 block mb-1">Paciente</span>
                    <div class="font-medium text-slate-800"><?php echo htmlspecialchars($cita['paciente_nombre'] ?? ''); ?></div>
                </div>
                
                <div>
                    <span class="text-sm text-slate-500 block mb-1">Doctor</span>
                    <div class="font-medium text-slate-800">Dr. <?php echo htmlspecialchars($cita['medico_nombre'] ?? ''); ?></div>
                    <div class="text-sm text-slate-500"><?php echo htmlspecialchars($cita['nombre_especialidad'] ?? ''); ?></div>
                </div>

                <div>
                    <span class="text-sm text-slate-500 block mb-1">Fecha y Hora</span>
                    <div class="font-medium text-slate-800"><?php echo htmlspecialchars($cita['fecha'] ?? ''); ?></div>
                </div>

                <div>
                    <span class="text-sm text-slate-500 block mb-1">Tipo</span>
                    <div class="font-medium text-slate-800">Telemedicina</div>
                </div>

                <div>
                    <span class="text-sm text-slate-500 block mb-1">Motivo</span>
                    <div class="font-medium text-slate-800"><?php echo htmlspecialchars($cita['motivo'] ?? ''); ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex justify-end gap-4 mt-8">
        <a href="index.php?action=citas" class="px-6 py-2.5 rounded-lg border border-slate-300 text-slate-700 font-medium hover:bg-slate-50 transition-colors">Cancelar</a>
        <button type="submit" class="px-6 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-medium flex items-center gap-2 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75m9 0H18A2.25 2.25 0 0120.25 6v12A2.25 2.25 0 0118 20.25H6A2.25 2.25 0 013.75 18V6A2.25 2.25 0 016 3.75h1.5m9 0h-9" /></svg>
            Actualizar Cita
        </button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const medicoSelect = document.getElementById('id_medico');
    const fechaInput = document.getElementById('fecha');
    const horaSelect = document.getElementById('hora');
    const horaOriginal = document.getElementById('hora_original').value;

    function cargarHorasDisponibles() {
        const idMedico = medicoSelect.value;
        const fecha = fechaInput.value;

        horaSelect.innerHTML = '<option value="">Cargando horas...</option>';
        horaSelect.disabled = true;

        if (!idMedico || !fecha) {
            horaSelect.innerHTML = '<option value="">Seleccione un doctor y una fecha</option>';
            return;
        }

        fetch(`api/horas_disponibles.php?id_medico=${idMedico}&fecha=${fecha}`)
            .then(response => response.json())
            .then(data => {
                horaSelect.innerHTML = '<option value="">Seleccionar hora</option>';
                
                let foundOriginal = false;

                if (data.length === 0 && !horaOriginal) {
                    horaSelect.innerHTML = '<option value="">No hay horas disponibles este día</option>';
                } else {
                    data.forEach(hora => {
                        const option = document.createElement('option');
                        option.value = hora.value;
                        option.textContent = hora.label;
                        
                        if (hora.value === horaOriginal) {
                            option.selected = true;
                            foundOriginal = true;
                        }
                        
                        horaSelect.appendChild(option);
                    });
                    
                    // Si la hora original no estaba en las disponibles (tal vez porque él mismo la tiene reservada), la añadimos manualmente
                    if (!foundOriginal && horaOriginal) {
                        const option = document.createElement('option');
                        option.value = horaOriginal;
                        // Formato de 12 horas:
                        let [h, m] = horaOriginal.split(':');
                        let ampm = h >= 12 ? 'PM' : 'AM';
                        h = h % 12 || 12;
                        option.textContent = `${h}:${m} ${ampm} (Hora Actual)`;
                        option.selected = true;
                        horaSelect.appendChild(option);
                    }

                    horaSelect.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error al cargar horas:', error);
                horaSelect.innerHTML = '<option value="">Error al cargar horas</option>';
            });
    }

    medicoSelect.addEventListener('change', cargarHorasDisponibles);
    fechaInput.addEventListener('change', cargarHorasDisponibles);
    
    // Cargar horas iniciales al abrir el modal/vista
    if (medicoSelect.value && fechaInput.value) {
        cargarHorasDisponibles();
    }
});
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
