<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Citas Médicas</h1>
        <p class="text-slate-500 mt-1">Gestionar y agendar citas</p>
    </div>
    <?php if($_SESSION['rol'] != 1): ?>
    <a href="index.php?action=agendar_cita" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
        Agendar Cita
    </a>
    <?php endif; ?>
</div>

<?php if(isset($_GET['success'])): ?>
<div class="bg-emerald-50 text-emerald-600 p-4 rounded-lg mb-6 border border-emerald-100 flex items-center gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
    <?php
        $msgs = [
            'updated'        => 'Cita actualizada correctamente.',
            'cancelled'       => 'Cita cancelada. Se notificó al destinatario.',
            '1'               => 'Cita agendada. Se envió confirmación por correo.',
            'rated'           => 'Calificación enviada. ¡Gracias!',
            'rescheduled'     => 'Cita reprogramada. Se notificó al destinatario.',
            'completed'       => 'Cita completada. El paciente recibirá un correo para calificar.',
            'reminder_sent'   => 'Recordatorio enviado al paciente correctamente.',
            'reminder_failed' => 'Recordatorio registrado, pero no se pudo enviar el correo (correo no disponible).',
            'attendance_confirmed' => '✅ Asistencia confirmada. El doctor fue notificado en el sistema.',
        ];
        echo $msgs[$_GET['success']] ?? 'Operación realizada correctamente.';
    ?>
</div>
<?php endif; ?>

<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <?php
    $statItems = [
        ['label'=>'Total',         'value'=>$stats['total'],        'color'=>'bg-blue-500'],
        ['label'=>'Programadas',   'value'=>$stats['programadas'],  'color'=>'bg-emerald-500'],
        ['label'=>'Completadas',   'value'=>$stats['completadas'],  'color'=>'bg-purple-500'],
        ['label'=>'Canceladas',    'value'=>$stats['canceladas'],   'color'=>'bg-red-500'],
    ];
    foreach($statItems as $s): ?>
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
        <div class="text-sm font-medium text-slate-500 mb-2"><?php echo $s['label']; ?></div>
        <div class="flex items-center gap-3">
            <div class="w-3 h-3 rounded-full <?php echo $s['color']; ?>"></div>
            <div class="text-3xl font-bold text-slate-800"><?php echo $s['value']; ?></div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Main list -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <h2 class="text-lg font-semibold text-slate-800">Todas las Citas (<?php echo $stats['total']; ?>)</h2>
        <form method="GET" action="index.php" class="relative w-full md:w-64">
            <input type="hidden" name="action" value="citas">
            <?php if(isset($_GET['estado'])): ?><input type="hidden" name="estado" value="<?php echo htmlspecialchars($_GET['estado']); ?>"><?php endif; ?>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 absolute left-3 top-2.5 text-slate-400"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
            <input type="text" name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" placeholder="Buscar citas..." class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
        </form>
    </div>

    <!-- Tabs -->
    <?php $estadoActual = isset($_GET['estado']) ? $_GET['estado'] : 'Todas'; ?>
    <div class="flex overflow-x-auto gap-2 bg-slate-50 p-1.5 rounded-xl mb-6">
        <?php
        $tabs = ['Todas'=>'','Programada'=>'Programada','Completada'=>'Completada','Cancelada'=>'Cancelada','Reprogramada'=>'Reprogramada'];
        foreach($tabs as $label => $val): 
            $active = ($estadoActual == $label || ($label=='Todas' && $estadoActual=='Todas'));
            $href   = $val ? "index.php?action=citas&estado={$val}" : "index.php?action=citas";
        ?>
        <a href="<?php echo $href; ?>" class="px-5 py-2 rounded-lg <?php echo $active ? 'bg-white shadow-sm text-slate-800 font-semibold' : 'text-slate-600 hover:bg-slate-100/50'; ?> text-sm shrink-0"><?php echo $label; ?></a>
        <?php endforeach; ?>
    </div>

    <!-- Citas List -->
    <div class="space-y-4">
        <?php if(empty($citas)): ?>
            <div class="text-center py-12 text-slate-500">No se encontraron citas.</div>
        <?php else: ?>
            <?php foreach($citas as $cita): ?>
            <?php
                $badgeClass = 'bg-blue-100 text-blue-700';
                if($cita['estado']=='Completada')   $badgeClass = 'bg-emerald-100 text-emerald-700';
                if($cita['estado']=='Cancelada')    $badgeClass = 'bg-red-100 text-red-700';
                if($cita['estado']=='Reprogramada') $badgeClass = 'bg-purple-100 text-purple-700';
            ?>
            <div class="border border-slate-100 rounded-xl overflow-hidden">
                <div class="p-4 sm:p-5 flex flex-col md:flex-row md:items-center gap-4 border-b border-slate-50 bg-slate-50/30">
                    <!-- Fecha/Hora -->
                    <div class="flex flex-col text-slate-600 font-medium min-w-[130px]">
                        <div class="flex items-center gap-2 text-blue-600 font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                            <?php echo htmlspecialchars($cita['fecha']); ?>
                        </div>
                        <div class="flex items-center gap-2 mt-1 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <?php echo date("g:i A", strtotime($cita['hora'])); ?>
                            <?php if(!empty($cita['hora_fin'])): ?> – <?php echo date("g:i A", strtotime($cita['hora_fin'])); ?><?php endif; ?>
                        </div>
                    </div>

                    <!-- Paciente / Doctor / Motivo -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 flex-1">
                        <div>
                            <div class="text-xs text-slate-400 mb-0.5">Paciente</div>
                            <div class="font-semibold text-slate-800 text-sm"><?php echo htmlspecialchars($cita['paciente_nombre']); ?></div>
                        </div>
                        <div>
                            <div class="text-xs text-slate-400 mb-0.5">Doctor</div>
                            <div class="font-semibold text-slate-800 text-sm">Dr. <?php echo htmlspecialchars($cita['medico_nombre']); ?></div>
                            <div class="text-xs text-slate-500"><?php echo htmlspecialchars($cita['nombre_especialidad']); ?></div>
                        </div>
                        <div>
                            <div class="text-xs text-slate-400 mb-0.5">Motivo</div>
                            <div class="text-sm text-slate-700"><?php echo htmlspecialchars($cita['motivo']); ?></div>
                        </div>
                    </div>

                    <!-- Estado + Acciones -->
                    <div class="flex items-center gap-2 shrink-0 flex-wrap justify-end">
                        <span class="px-3 py-1 <?php echo $badgeClass; ?> text-xs font-semibold rounded-full"><?php echo htmlspecialchars($cita['estado']); ?></span>

                        <?php if(in_array($cita['estado'], ['Programada','Reprogramada'])): ?>
                            <!-- Reprogramar -->
                            <button 
                                onclick="abrirReprogramar(<?php echo $cita['id_cita']; ?>, <?php echo $cita['id_medico']; ?>)" 
                                data-idcita="<?php echo $cita['id_cita']; ?>"
                                data-idmedico="<?php echo $cita['id_medico']; ?>"
                                class="p-2 text-slate-400 hover:text-blue-600 transition-colors" title="Reprogramar">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008z" /></svg>
                            </button>
                            <!-- Cancelar -->
                            <button onclick="abrirCancelar(<?php echo $cita['id_cita']; ?>)" class="p-2 text-slate-400 hover:text-red-600 transition-colors" title="Cancelar Cita">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                            <!-- Completar (solo doctor/admin) -->
                            <?php if($_SESSION['rol'] != 3): ?>
                            <form action="index.php?action=completar_cita" method="POST" class="inline" onsubmit="return confirm('¿Marcar esta cita como completada?')">
                                <input type="hidden" name="id" value="<?php echo $cita['id_cita']; ?>">
                                <button type="submit" class="p-2 text-slate-400 hover:text-emerald-600 transition-colors" title="Completar Cita">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </button>
                            </form>
                            <!-- Recordatorio (solo doctor) -->
                            <?php if($_SESSION['rol'] == 2): ?>
                            <form action="index.php?action=enviar_recordatorio" method="POST" class="inline">
                                <input type="hidden" name="id" value="<?php echo $cita['id_cita']; ?>">
                                <button type="submit" class="p-2 text-slate-400 hover:text-amber-500 transition-colors" title="Enviar recordatorio al paciente">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                                </button>
                            </form>
                            <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>

                        <!-- Confirmar asistencia (solo paciente, cita programada/reprogramada) -->
                        <?php if($_SESSION['rol'] == 3 && in_array($cita['estado'], ['Programada','Reprogramada'])): ?>
                            <?php if(empty($cita['confirmacion_asistencia'])): ?>
                            <form action="index.php?action=confirmar_asistencia" method="POST" class="inline">
                                <input type="hidden" name="id" value="<?php echo $cita['id_cita']; ?>">
                                <button type="submit" class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-semibold rounded-full flex items-center gap-1 transition-colors" title="Confirmar que asistiré a esta cita">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" /></svg>
                                    Confirmar Asistencia
                                </button>
                            </form>
                            <?php else: ?>
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-full flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" /></svg>
                                Asistencia Confirmada
                            </span>
                            <?php endif; ?>
                        <?php endif; ?>

                        <!-- Para el doctor: ver si el paciente confirmó -->
                        <?php if($_SESSION['rol'] == 2 && !empty($cita['confirmacion_asistencia']) && in_array($cita['estado'], ['Programada','Reprogramada'])): ?>
                        <span class="px-2 py-1 bg-emerald-50 text-emerald-600 text-xs font-medium rounded-full border border-emerald-200" title="El paciente confirmó su asistencia">
                            ✓ Paciente confirmó
                        </span>
                        <?php endif; ?>

                        <!-- Calificar / Ver estrella (completadas) -->
                        <?php if($cita['estado'] == 'Completada'): ?>
                            <?php if($_SESSION['rol'] == 3 && empty($cita['puntuacion'])): ?>
                            <a href="index.php?action=calificar_cita&id=<?php echo $cita['id_cita']; ?>" class="px-3 py-1 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold rounded-full flex items-center gap-1 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3"><path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd" /></svg>
                                Calificar
                            </a>
                            <?php elseif(!empty($cita['puntuacion'])): ?>
                            <div class="flex items-center gap-0.5" title="<?php echo htmlspecialchars($cita['comentario_calificacion'] ?? ''); ?>">
                                <?php for($i=1; $i<=5; $i++): ?>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 <?php echo $i<=$cita['puntuacion'] ? 'text-amber-500' : 'text-slate-200'; ?>"><path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd" /></svg>
                                <?php endfor; ?>
                            </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Motivo de cambio (cancelada/reprogramada) -->
                <?php if(!empty($cita['motivo_cambio']) && in_array($cita['estado'], ['Cancelada','Reprogramada'])): ?>
                <div class="px-5 py-3 bg-<?php echo $cita['estado']=='Cancelada' ? 'red' : 'purple'; ?>-50 border-t border-<?php echo $cita['estado']=='Cancelada' ? 'red' : 'purple'; ?>-100 text-sm text-<?php echo $cita['estado']=='Cancelada' ? 'red' : 'purple'; ?>-700">
                    <strong>Motivo:</strong> <?php echo htmlspecialchars($cita['motivo_cambio']); ?>
                    <?php if(!empty($cita['reprogramada_por'])): ?>
                    <span class="ml-2 text-xs opacity-70">(por <?php echo $cita['reprogramada_por']; ?>)</span>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <!-- Notas adicionales -->
                <?php if(!empty($cita['notas_adicionales']) && $_SESSION['rol'] != 3): ?>
                <div class="px-5 py-3 bg-blue-50 border-t border-blue-100 text-sm text-blue-700">
                    <strong>Notas del paciente:</strong> <?php echo htmlspecialchars($cita['notas_adicionales']); ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Cancelar -->
<div id="modal-cancelar" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
        <h3 class="text-lg font-bold text-slate-800 mb-1">Cancelar Cita</h3>
        <p class="text-sm text-slate-500 mb-5">Por favor indique el motivo de la cancelación. El paciente será notificado.</p>
        <form action="index.php?action=cancelar_cita" method="POST">
            <input type="hidden" name="id" id="cancelar_id">
            <textarea name="motivo_cancelacion" rows="3" required placeholder="Ej: El médico tiene una urgencia, el paciente solicitó cambio..." class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-red-500 outline-none text-sm resize-none mb-4"></textarea>
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="cerrarModales()" class="px-5 py-2 rounded-lg border border-slate-300 text-slate-700 font-medium hover:bg-slate-50 text-sm">Volver</button>
                <button type="submit" class="px-5 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white font-medium text-sm">Confirmar Cancelación</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Reprogramar -->
<div id="modal-reprogramar" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-6">
        <h3 class="text-lg font-bold text-slate-800 mb-1">Reprogramar Cita</h3>
        <p class="text-sm text-slate-500 mb-5">Seleccione la nueva fecha y hora. El otro participante será notificado por correo.</p>
        <form action="index.php?action=reprogramar_cita" method="POST">
            <input type="hidden" name="id_cita" id="reprog_id">
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nueva Fecha <span class="text-red-500">*</span></label>
                    <input type="date" name="nueva_fecha" id="reprog_fecha" required
                        min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>"
                        class="w-full px-3 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nueva Hora <span class="text-red-500">*</span></label>
                    <select name="nueva_hora" id="reprog_hora" required class="w-full px-3 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none text-sm disabled:bg-slate-50" disabled>
                        <option value="">Seleccione fecha primero</option>
                    </select>
                </div>
            </div>
            <div class="mb-4" id="reprog_aviso_div" style="display:none">
                <p id="reprog_aviso" class="text-sm text-red-600 bg-red-50 p-3 rounded-lg"></p>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-1">Motivo de reprogramación <span class="text-red-500">*</span></label>
                <textarea name="motivo_reprogramacion" rows="2" required placeholder="Ej: Conflicto de horario, solicitud del paciente..." class="w-full px-3 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none text-sm resize-none"></textarea>
            </div>
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="cerrarModales()" class="px-5 py-2 rounded-lg border border-slate-300 text-slate-700 font-medium hover:bg-slate-50 text-sm">Cancelar</button>
                <button type="submit" class="px-5 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm">Confirmar Reprogramación</button>
            </div>
        </form>
    </div>
</div>

<script>
function abrirCancelar(id) {
    document.getElementById('cancelar_id').value = id;
    document.getElementById('modal-cancelar').classList.remove('hidden');
}
// Guardar idMedico al abrir modal
let _idMedicoReprog = null;
function abrirReprogramar(id, idMedico) {
    document.getElementById('reprog_id').value = id;
    _idMedicoReprog = idMedico;
    document.getElementById('reprog_hora').innerHTML = '<option value="">Seleccione fecha primero</option>';
    document.getElementById('reprog_hora').disabled = true;
    document.getElementById('reprog_aviso_div').style.display = 'none';
    document.getElementById('reprog_fecha').value = '';
    document.getElementById('modal-reprogramar').classList.remove('hidden');
}
function cerrarModales() {
    document.getElementById('modal-cancelar').classList.add('hidden');
    document.getElementById('modal-reprogramar').classList.add('hidden');
}
// Cerrar al click fuera del modal
['modal-cancelar','modal-reprogramar'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if(e.target === this) cerrarModales();
    });
});

// Cargar horas en modal reprogramar
document.getElementById('reprog_fecha').addEventListener('change', function() {
    const fecha = this.value;
    const horaSelect = document.getElementById('reprog_hora');
    const avisoDiv  = document.getElementById('reprog_aviso_div');
    const avisoP    = document.getElementById('reprog_aviso');
    const idMedico = _idMedicoReprog;
    if (!fecha || !idMedico) return;

    horaSelect.innerHTML = '<option value="">Cargando...</option>';
    horaSelect.disabled = true;
    avisoDiv.style.display = 'none';

    fetch(`api/horas_disponibles.php?id_medico=${idMedico}&fecha=${fecha}`)
        .then(r => r.json())
        .then(data => {
            if (data.dia_descanso || data.sin_horas || !Array.isArray(data) || data.length === 0) {
                const msg = data.mensaje || 'No hay horarios disponibles para este día.';
                avisoP.textContent = msg;
                avisoDiv.style.display = 'block';
                horaSelect.innerHTML = '<option value="">No disponible</option>';
                return;
            }
            horaSelect.innerHTML = '<option value="">Seleccione hora...</option>';
            data.forEach(h => {
                const opt = document.createElement('option');
                opt.value = h.value;
                opt.textContent = h.label;
                horaSelect.appendChild(opt);
            });
            horaSelect.disabled = false;
        });
});
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
