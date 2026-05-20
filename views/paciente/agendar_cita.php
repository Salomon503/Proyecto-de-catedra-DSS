<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="mb-8 flex items-center gap-4">
    <a href="index.php?action=citas" class="flex items-center gap-2 text-slate-500 hover:text-slate-800 font-medium transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
        Volver
    </a>
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Agendar Cita</h1>
        <p class="text-slate-500 mt-1">Programar nueva cita médica</p>
    </div>
</div>

<?php if(isset($_GET['error'])): ?>
<div class="bg-red-50 text-red-600 p-4 rounded-lg mb-6 border border-red-100 flex items-center gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
    <?php
        if($_GET['error'] == 'overbooking') echo 'El horario seleccionado ya está ocupado. Por favor elige otro.';
        else echo 'Ocurrió un error al agendar la cita. Inténtalo de nuevo.';
    ?>
</div>
<?php endif; ?>

<form action="index.php?action=guardar_cita" method="POST" id="form-agendar">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Formulario Principal -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Paciente -->
            <?php if($_SESSION['rol'] != 3): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h2 class="text-base font-semibold text-slate-800 mb-4">Paciente</h2>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Seleccione el Paciente <span class="text-red-500">*</span></label>
                    <select name="id_paciente" required class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-slate-700">
                        <option value="">Seleccione paciente...</option>
                        <?php foreach($pacientes as $p): ?>
                            <option value="<?php echo $p['id_paciente']; ?>">
                                <?php echo htmlspecialchars($p['primer_nombre'] . ' ' . $p['primer_apellido'] . ' — ' . $p['correo']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <?php else: ?>
                <?php
                    require_once __DIR__ . '/../../models/Paciente.php';
                    $pacModel = new Paciente();
                    $pacData  = $pacModel->obtenerPacientePorUsuarioId($_SESSION['user_id']);
                ?>
                <input type="hidden" name="id_paciente" value="<?php echo $pacData ? $pacData['id_paciente'] : ''; ?>">
            <?php endif; ?>

            <!-- Especialidad y Doctor -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h2 class="text-base font-semibold text-slate-800 mb-4">Especialidad y Médico</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Especialidad <span class="text-red-500">*</span></label>
                        <?php if($_SESSION['rol'] == 2): ?>
                            <input type="hidden" name="id_medico" id="id_medico" value="">
                            <p class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-600 text-sm">Su especialidad asignada</p>
                        <?php else: ?>
                        <select id="id_especialidad" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-slate-700">
                            <option value="">Seleccione especialidad...</option>
                            <?php foreach($especialidades as $esp): ?>
                                <option value="<?php echo $esp['id_especialidad']; ?>"><?php echo htmlspecialchars($esp['nombre_especialidad']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Doctor <span class="text-red-500">*</span></label>
                        <?php if($_SESSION['rol'] == 2): ?>
                            <?php
                                require_once __DIR__ . '/../../models/Medico.php';
                                $medModel = new Medico();
                                $medData  = $medModel->obtenerMedicoPorUsuarioId($_SESSION['user_id']);
                            ?>
                            <input type="hidden" name="id_medico" id="id_medico" value="<?php echo $medData ? $medData['id_medico'] : ''; ?>">
                            <p class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-600 text-sm">Dr. <?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
                        <?php else: ?>
                        <select name="id_medico" id="id_medico" required class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-slate-700 disabled:bg-slate-50 disabled:text-slate-400" disabled>
                            <option value="">Primero seleccione especialidad</option>
                        </select>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Fecha y Hora -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h2 class="text-base font-semibold text-slate-800 mb-4">Fecha y Hora</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Fecha de la Cita <span class="text-red-500">*</span></label>
                        <input type="date" name="fecha" id="fecha" required
                            min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>"
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-slate-700">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Hora <span class="text-red-500">*</span></label>
                        <select name="hora" id="hora" required class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-slate-700 disabled:bg-slate-50 disabled:text-slate-400" disabled>
                            <option value="">Seleccione médico y fecha primero</option>
                        </select>
                    </div>
                </div>

                <!-- Aviso día descanso / sin horas -->
                <div id="aviso-disponibilidad" class="hidden mt-4 p-4 rounded-xl flex items-start gap-3">
                    <svg id="aviso-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 shrink-0 mt-0.5"></svg>
                    <p id="aviso-texto" class="text-sm font-medium"></p>
                </div>

                <!-- Indicador de carga -->
                <div id="loading-horas" class="hidden mt-4 flex items-center gap-2 text-slate-500 text-sm">
                    <svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    Verificando disponibilidad...
                </div>
            </div>

            <!-- Motivo y Notas -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h2 class="text-base font-semibold text-slate-800 mb-4">Detalles de la Consulta</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Motivo de la Visita <span class="text-red-500">*</span></label>
                        <input type="text" name="motivo" required maxlength="255"
                            placeholder="Ej. Dolor de cabeza persistente, control de presión..."
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-slate-700">
                        <p class="text-xs text-slate-400 mt-1">Sea específico para que el médico pueda prepararse mejor.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Notas Adicionales <span class="text-slate-400 font-normal">(opcional)</span></label>
                        <textarea name="notas_adicionales" rows="3" maxlength="1000"
                            placeholder="Síntomas adicionales, medicamentos que toma, alergias conocidas, antecedentes relevantes..."
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-slate-700 resize-none"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-4">
            <!-- Info duración -->
            <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-600"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <span class="font-semibold text-blue-800">Duración de la cita</span>
                </div>
                <p class="text-sm text-blue-700">Cada consulta tiene una duración máxima de <strong>1 hora</strong>. Los horarios se muestran con inicio y fin para su conveniencia.</p>
            </div>
            <!-- Info correo -->
            <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-emerald-600"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                    </div>
                    <span class="font-semibold text-emerald-800">Confirmación automática</span>
                </div>
                <p class="text-sm text-emerald-700">Al confirmar, recibirás un correo electrónico con todos los detalles de tu cita.</p>
            </div>
            <!-- Resumen dinámico -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5" id="resumen-cita">
                <h3 class="font-semibold text-slate-800 mb-3">Resumen</h3>
                <div class="space-y-2 text-sm text-slate-500" id="resumen-contenido">
                    <p class="italic">Complete el formulario para ver el resumen.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones -->
    <div class="flex justify-end gap-4 mt-6">
        <a href="index.php?action=citas" class="px-6 py-2.5 rounded-lg border border-slate-300 text-slate-700 font-medium hover:bg-slate-50 transition-colors">Cancelar</a>
        <button type="submit" id="btn-agendar" class="px-6 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-medium flex items-center gap-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            Confirmar Cita
        </button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const espSelect   = document.getElementById('id_especialidad');
    const medSelect   = document.getElementById('id_medico');
    const fechaInput  = document.getElementById('fecha');
    const horaSelect  = document.getElementById('hora');
    const avisoDiv    = document.getElementById('aviso-disponibilidad');
    const avisoTexto  = document.getElementById('aviso-texto');
    const avisoIcon   = document.getElementById('aviso-icon');
    const loadingDiv  = document.getElementById('loading-horas');
    const btnAgendar  = document.getElementById('btn-agendar');
    const resumenDiv  = document.getElementById('resumen-contenido');

    // ── Cargar médicos por especialidad ──────────────────────────────
    <?php if($_SESSION['rol'] != 2): ?>
    espSelect?.addEventListener('change', function () {
        const idEsp = this.value;
        medSelect.innerHTML = '<option value="">Cargando médicos...</option>';
        medSelect.disabled = true;
        horaSelect.innerHTML = '<option value="">Seleccione médico y fecha primero</option>';
        horaSelect.disabled = true;
        ocultarAviso();
        actualizarResumen();

        if (!idEsp) {
            medSelect.innerHTML = '<option value="">Primero seleccione especialidad</option>';
            return;
        }

        fetch(`api/especialidades_medicos.php?id_especialidad=${idEsp}`)
            .then(r => r.json())
            .then(medicos => {
                medSelect.innerHTML = '<option value="">Seleccione doctor...</option>';
                if (medicos.length === 0) {
                    medSelect.innerHTML = '<option value="">No hay médicos en esta especialidad</option>';
                } else {
                    medicos.forEach(m => {
                        const opt = document.createElement('option');
                        opt.value = m.id_medico;
                        opt.textContent = `Dr. ${m.primer_nombre} ${m.primer_apellido}`;
                        medSelect.appendChild(opt);
                    });
                    medSelect.disabled = false;
                }
                actualizarResumen();
            })
            .catch(() => {
                medSelect.innerHTML = '<option value="">Error al cargar médicos</option>';
            });
    });
    <?php endif; ?>

    // ── Cargar horas disponibles ──────────────────────────────────────
    function cargarHoras() {
        const idMedico = medSelect?.value;
        const fecha    = fechaInput?.value;
        ocultarAviso();

        horaSelect.innerHTML = '<option value="">Seleccione médico y fecha primero</option>';
        horaSelect.disabled = true;

        if (!idMedico || !fecha) return;

        loadingDiv.classList.remove('hidden');

        fetch(`api/horas_disponibles.php?id_medico=${idMedico}&fecha=${fecha}`)
            .then(r => r.json())
            .then(data => {
                loadingDiv.classList.add('hidden');

                if (data.dia_descanso) {
                    mostrarAviso('rojo', data.mensaje || 'El médico no está disponible este día.');
                    horaSelect.innerHTML = '<option value="">No disponible</option>';
                    return;
                }
                if (data.sin_horas) {
                    mostrarAviso('amarillo', data.mensaje || 'No hay horarios disponibles este día.');
                    horaSelect.innerHTML = '<option value="">Sin horas disponibles</option>';
                    return;
                }
                if (!Array.isArray(data) || data.length === 0) {
                    mostrarAviso('amarillo', 'No hay horarios disponibles para este día.');
                    horaSelect.innerHTML = '<option value="">Sin horas disponibles</option>';
                    return;
                }

                horaSelect.innerHTML = '<option value="">Seleccione una hora...</option>';
                data.forEach(h => {
                    const opt = document.createElement('option');
                    opt.value = h.value;
                    opt.textContent = h.label;
                    horaSelect.appendChild(opt);
                });
                horaSelect.disabled = false;
                actualizarResumen();
            })
            .catch(() => {
                loadingDiv.classList.add('hidden');
                mostrarAviso('rojo', 'Error al verificar disponibilidad. Intente de nuevo.');
            });
    }

    function mostrarAviso(tipo, mensaje) {
        avisoTexto.textContent = mensaje;
        avisoDiv.classList.remove('hidden', 'bg-red-50', 'text-red-700', 'border-red-100', 'bg-amber-50', 'text-amber-700', 'border-amber-100');
        if (tipo === 'rojo') {
            avisoDiv.classList.add('bg-red-50', 'text-red-700', 'border', 'border-red-100');
            avisoIcon.setAttribute('stroke', '#dc2626');
            avisoIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />';
        } else {
            avisoDiv.classList.add('bg-amber-50', 'text-amber-700', 'border', 'border-amber-100');
            avisoIcon.setAttribute('stroke', '#d97706');
            avisoIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />';
        }
    }

    function ocultarAviso() {
        avisoDiv.classList.add('hidden');
    }

    function actualizarResumen() {
        const medText   = medSelect?.options[medSelect?.selectedIndex]?.text;
        const fechaVal  = fechaInput?.value;
        const horaText  = horaSelect?.options[horaSelect?.selectedIndex]?.text;

        let html = '';
        if (medText && medSelect?.value) html += `<div class="flex gap-2"><span class="text-slate-400 w-5">🩺</span><span class="text-slate-700">${medText}</span></div>`;
        if (fechaVal) html += `<div class="flex gap-2"><span class="text-slate-400 w-5">📅</span><span class="text-slate-700">${fechaVal}</span></div>`;
        if (horaText && horaSelect?.value) html += `<div class="flex gap-2"><span class="text-slate-400 w-5">⏰</span><span class="text-slate-700">${horaText}</span></div>`;

        resumenDiv.innerHTML = html || '<p class="italic text-slate-400">Complete el formulario para ver el resumen.</p>';
    }

    <?php if($_SESSION['rol'] != 2): ?>
    medSelect?.addEventListener('change', function() { cargarHoras(); actualizarResumen(); });
    <?php endif; ?>
    fechaInput?.addEventListener('change', function() { cargarHoras(); actualizarResumen(); });
    horaSelect?.addEventListener('change', actualizarResumen);

    <?php if($_SESSION['rol'] == 2): ?>
    // Si es doctor, cargar horas si ya hay fecha
    if (fechaInput?.value) cargarHoras();
    <?php endif; ?>
});
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
