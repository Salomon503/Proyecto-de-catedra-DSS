<?php require_once __DIR__ . '/../layout/header.php'; ?>

<?php
$tab = $_GET['tab'] ?? 'personal';
$mensajes = [
    'profile_updated'  => ['tipo'=>'ok',  'texto'=>'Teléfono actualizado correctamente.'],
    'password_changed' => ['tipo'=>'ok',  'texto'=>'Contraseña actualizada. Se envió un correo de confirmación.'],
    'wrong_password'   => ['tipo'=>'err', 'texto'=>'La contraseña actual ingresada es incorrecta.'],
    'password_mismatch'=> ['tipo'=>'err', 'texto'=>'La nueva contraseña y su confirmación no coinciden.'],
    'password_short'   => ['tipo'=>'err', 'texto'=>'La nueva contraseña debe tener al menos 8 caracteres.'],
];
$msg = isset($_GET['success']) ? ($mensajes[$_GET['success']] ?? null) : (isset($_GET['error']) ? ($mensajes[$_GET['error']] ?? null) : null);

// Obtener notificaciones
require_once __DIR__ . '/../../models/Notificacion.php';
$notifModel = new Notificacion();
$notificaciones = $notifModel->obtenerPorUsuario($_SESSION['user_id']);
$noLeidas = $notifModel->contarNoLeidas($_SESSION['user_id']);
?>

<div class="mb-8">
    <h1 class="text-2xl font-bold text-slate-800">Mi Perfil</h1>
    <p class="text-slate-500 mt-1">Gestiona tu información y notificaciones</p>
</div>

<?php if($msg): ?>
<div class="mb-6 p-4 rounded-lg border flex items-center gap-2 <?php echo $msg['tipo']=='ok' ? 'bg-emerald-50 border-emerald-100 text-emerald-700' : 'bg-red-50 border-red-100 text-red-700'; ?>">
    <?php if($msg['tipo']=='ok'): ?>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
    <?php else: ?>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
    <?php endif; ?>
    <?php echo $msg['texto']; ?>
</div>
<?php endif; ?>

<!-- Avatar -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-6 flex items-center gap-5">
    <div class="w-16 h-16 rounded-2xl bg-emerald-600 flex items-center justify-center text-white text-2xl font-bold uppercase shrink-0">
        <?php echo substr($usuario['primer_nombre'] ?? 'D', 0, 1); ?>
    </div>
    <div>
        <p class="text-xl font-bold text-slate-800">Dr. <?php echo htmlspecialchars(($usuario['primer_nombre'] ?? '') . ' ' . ($usuario['primer_apellido'] ?? '')); ?></p>
        <p class="text-slate-500 text-sm"><?php echo htmlspecialchars($usuario['correo'] ?? ''); ?></p>
        <div class="flex items-center gap-2 mt-1">
            <span class="px-3 py-0.5 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-full">Médico</span>
            <?php if($especialidad): ?>
            <span class="px-3 py-0.5 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full"><?php echo htmlspecialchars($especialidad['nombre_especialidad']); ?></span>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Tabs -->
<div class="flex gap-2 bg-slate-100 p-1.5 rounded-xl mb-6 w-fit">
    <button onclick="cambiarTab('personal')" id="tab-personal" class="px-5 py-2 rounded-lg text-sm font-medium transition-colors">Datos Personales</button>
    <button onclick="cambiarTab('notificaciones')" id="tab-notificaciones" class="px-5 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
        Notificaciones
        <?php if($noLeidas > 0): ?>
        <span class="bg-red-500 text-white text-xs w-4 h-4 flex items-center justify-center rounded-full font-bold"><?php echo min($noLeidas, 9); ?></span>
        <?php endif; ?>
    </button>
    <button onclick="cambiarTab('contrasena')" id="tab-contrasena" class="px-5 py-2 rounded-lg text-sm font-medium transition-colors">Cambiar Contraseña</button>
</div>

<!-- ── TAB: Datos Personales (solo teléfono editable) ── -->
<div id="contenido-personal">
    <form action="index.php?action=actualizar_perfil" method="POST">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h2 class="text-base font-semibold text-slate-800 mb-2">Datos Personales</h2>
            <p class="text-sm text-slate-500 mb-5">Los campos en gris son administrados por el sistema y no pueden modificarse.</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Primer Nombre</label>
                    <input type="text" value="<?php echo htmlspecialchars($usuario['primer_nombre'] ?? ''); ?>" disabled class="w-full px-4 py-2.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-400 text-sm cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Segundo Nombre</label>
                    <input type="text" value="<?php echo htmlspecialchars($usuario['segundo_nombre'] ?? ''); ?>" disabled class="w-full px-4 py-2.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-400 text-sm cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Primer Apellido</label>
                    <input type="text" value="<?php echo htmlspecialchars($usuario['primer_apellido'] ?? ''); ?>" disabled class="w-full px-4 py-2.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-400 text-sm cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Segundo Apellido</label>
                    <input type="text" value="<?php echo htmlspecialchars($usuario['segundo_apellido'] ?? ''); ?>" disabled class="w-full px-4 py-2.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-400 text-sm cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Especialidad</label>
                    <input type="text" value="<?php echo htmlspecialchars($especialidad['nombre_especialidad'] ?? 'Sin asignar'); ?>" disabled class="w-full px-4 py-2.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-400 text-sm cursor-not-allowed">
                </div>
                <?php if($medico): ?>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Número de Colegiado</label>
                    <input type="text" value="<?php echo htmlspecialchars($medico['numero_colegiado'] ?? ''); ?>" disabled class="w-full px-4 py-2.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-400 text-sm cursor-not-allowed">
                </div>
                <?php endif; ?>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Correo Electrónico</label>
                    <input type="email" value="<?php echo htmlspecialchars($usuario['correo'] ?? ''); ?>" disabled class="w-full px-4 py-2.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-400 text-sm cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Teléfono <span class="text-blue-500 font-normal">(editable)</span></label>
                    <input type="tel" name="telefono" value="<?php echo htmlspecialchars($usuario['telefono'] ?? ''); ?>" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none text-sm" placeholder="Ej. 7890-1234">
                    <!-- Campos ocultos necesarios para el update -->
                    <input type="hidden" name="primer_nombre"    value="<?php echo htmlspecialchars($usuario['primer_nombre'] ?? ''); ?>">
                    <input type="hidden" name="segundo_nombre"   value="<?php echo htmlspecialchars($usuario['segundo_nombre'] ?? ''); ?>">
                    <input type="hidden" name="primer_apellido"  value="<?php echo htmlspecialchars($usuario['primer_apellido'] ?? ''); ?>">
                    <input type="hidden" name="segundo_apellido" value="<?php echo htmlspecialchars($usuario['segundo_apellido'] ?? ''); ?>">
                </div>
            </div>
            <div class="flex justify-end mt-6">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm transition-colors">Actualizar Teléfono</button>
            </div>
        </div>
    </form>
</div>

<!-- ── TAB: Notificaciones del sistema ── -->
<div id="contenido-notificaciones" class="hidden">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-base font-semibold text-slate-800">Notificaciones del Sistema</h2>
            <?php if(!empty($notificaciones)): ?>
            <a href="index.php?action=marcar_todas_leidas" class="text-xs font-medium text-blue-600 hover:text-blue-700 flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" /></svg>
                Marcar todas como leídas
            </a>
            <?php endif; ?>
        </div>

        <?php if(empty($notificaciones)): ?>
        <div class="text-center py-12 text-slate-400">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 mx-auto mb-3 opacity-40"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" /></svg>
            <p class="font-medium">Sin notificaciones</p>
            <p class="text-sm mt-1">Las confirmaciones de asistencia de sus pacientes aparecerán aquí.</p>
        </div>
        <?php else: ?>
        <div class="space-y-3">
            <?php foreach($notificaciones as $notif): 
                $estaLeida = $notif['leida'] == 1;
            ?>
            <div class="flex items-start gap-4 p-4 <?php echo $estaLeida ? 'bg-white opacity-60' : 'bg-blue-50 border border-blue-100'; ?> rounded-xl transition-all group">
                <div class="w-9 h-9 <?php echo $estaLeida ? 'bg-slate-100 text-slate-400' : 'bg-emerald-100 text-emerald-600'; ?> rounded-full flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" /></svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm <?php echo $estaLeida ? 'text-slate-500' : 'text-slate-800 font-medium'; ?>"><?php echo htmlspecialchars($notif['mensaje_recordatorio']); ?></p>
                    <p class="text-xs text-slate-400 mt-1"><?php echo date('d/m/Y H:i', strtotime($notif['fecha'])); ?></p>
                </div>
                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                    <?php if(!$estaLeida): ?>
                    <a href="index.php?action=marcar_leida&id=<?php echo $notif['id_notificacion']; ?>" class="p-1.5 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors" title="Marcar como leída">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                    </a>
                    <?php endif; ?>
                    <a href="index.php?action=eliminar_notificacion&id=<?php echo $notif['id_notificacion']; ?>" onclick="return confirm('¿Eliminar esta notificación?')" class="p-1.5 text-red-600 hover:bg-red-100 rounded-lg transition-colors" title="Eliminar">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- ── TAB: Contraseña ── -->
<div id="contenido-contrasena" class="hidden">
    <form action="index.php?action=cambiar_contrasena" method="POST">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 max-w-lg">
            <h2 class="text-base font-semibold text-slate-800 mb-2">Cambiar Contraseña</h2>
            <p class="text-sm text-slate-500 mb-5">Recibirás un correo de alerta de seguridad al confirmar el cambio.</p>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Contraseña Actual <span class="text-red-500">*</span></label>
                    <input type="password" name="contrasena_actual" required class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nueva Contraseña <span class="text-red-500">*</span></label>
                    <input type="password" name="contrasena_nueva" required minlength="8" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                    <p class="text-xs text-slate-400 mt-1">Mínimo 8 caracteres.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Confirmar Nueva Contraseña <span class="text-red-500">*</span></label>
                    <input type="password" name="contrasena_confirmar" required minlength="8" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                </div>
            </div>
            <div class="bg-amber-50 border border-amber-100 rounded-xl p-4 mt-5 flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                <p class="text-sm text-amber-700">Se enviará un correo de alerta de seguridad a su cuenta registrada.</p>
            </div>
            <div class="flex justify-end mt-6">
                <button type="submit" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg text-sm transition-colors">Cambiar Contraseña</button>
            </div>
        </div>
    </form>
</div>

<script>
const TAB_ACTIVE   = 'bg-white shadow-sm text-slate-800 font-semibold';
const TAB_INACTIVE = 'text-slate-600 hover:text-slate-800';

function cambiarTab(nombre) {
    ['personal','notificaciones','contrasena'].forEach(t => {
        document.getElementById('contenido-' + t).classList.add('hidden');
        const btn = document.getElementById('tab-' + t);
        // Preservar contenido interno del botón (puede tener badge)
        btn.classList.remove('bg-white', 'shadow-sm', 'text-slate-800', 'font-semibold', 'text-slate-600');
        if(nombre === t) {
            btn.classList.add('bg-white', 'shadow-sm', 'text-slate-800', 'font-semibold');
        } else {
            btn.classList.add('text-slate-600');
        }
        document.getElementById('contenido-' + nombre).classList.remove('hidden');
    });
}

cambiarTab('<?php echo htmlspecialchars($tab); ?>');
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
