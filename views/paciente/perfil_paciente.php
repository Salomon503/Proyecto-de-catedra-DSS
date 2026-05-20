<?php require_once __DIR__ . '/../layout/header.php'; ?>

<?php
// Mensajes de éxito/error
$tab = $_GET['tab'] ?? 'personal';
$mensajes = [
    'profile_updated'  => ['tipo'=>'ok',  'texto'=>'Datos personales actualizados correctamente.'],
    'address_updated'  => ['tipo'=>'ok',  'texto'=>'Dirección actualizada correctamente.'],
    'password_changed' => ['tipo'=>'ok',  'texto'=>'Contraseña actualizada. Se envió un correo de confirmación.'],
    'wrong_password'   => ['tipo'=>'err', 'texto'=>'La contraseña actual ingresada es incorrecta.'],
    'password_mismatch'=> ['tipo'=>'err', 'texto'=>'La nueva contraseña y su confirmación no coinciden.'],
    'password_short'   => ['tipo'=>'err', 'texto'=>'La nueva contraseña debe tener al menos 8 caracteres.'],
];
$msg = isset($_GET['success']) ? ($mensajes[$_GET['success']] ?? null) : (isset($_GET['error']) ? ($mensajes[$_GET['error']] ?? null) : null);
?>

<div class="mb-8">
    <h1 class="text-2xl font-bold text-slate-800">Mi Perfil</h1>
    <p class="text-slate-500 mt-1">Gestiona tu información personal</p>
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

<!-- Avatar y nombre -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-6 flex items-center gap-5">
    <div class="w-16 h-16 rounded-2xl bg-blue-600 flex items-center justify-center text-white text-2xl font-bold uppercase shrink-0">
        <?php echo substr($usuario['primer_nombre'] ?? 'P', 0, 1); ?>
    </div>
    <div>
        <p class="text-xl font-bold text-slate-800"><?php echo htmlspecialchars(($usuario['primer_nombre'] ?? '') . ' ' . ($usuario['primer_apellido'] ?? '')); ?></p>
        <p class="text-slate-500 text-sm"><?php echo htmlspecialchars($usuario['correo'] ?? ''); ?></p>
        <span class="mt-1 inline-block px-3 py-0.5 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">Paciente</span>
    </div>
</div>

<!-- Tabs -->
<div class="flex gap-2 bg-slate-100 p-1.5 rounded-xl mb-6 w-fit">
    <button onclick="cambiarTab('personal')" id="tab-personal" class="px-5 py-2 rounded-lg text-sm font-medium transition-colors">Datos Personales</button>
    <button onclick="cambiarTab('direccion')" id="tab-direccion" class="px-5 py-2 rounded-lg text-sm font-medium transition-colors">Dirección</button>
    <button onclick="cambiarTab('contrasena')" id="tab-contrasena" class="px-5 py-2 rounded-lg text-sm font-medium transition-colors">Cambiar Contraseña</button>
</div>

<!-- ── TAB: Datos Personales ── -->
<div id="contenido-personal">
    <form action="index.php?action=actualizar_perfil" method="POST">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h2 class="text-base font-semibold text-slate-800 mb-5">Datos Personales</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Primer Nombre <span class="text-red-500">*</span></label>
                    <input type="text" name="primer_nombre" required value="<?php echo htmlspecialchars($usuario['primer_nombre'] ?? ''); ?>" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Segundo Nombre</label>
                    <input type="text" name="segundo_nombre" value="<?php echo htmlspecialchars($usuario['segundo_nombre'] ?? ''); ?>" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Primer Apellido <span class="text-red-500">*</span></label>
                    <input type="text" name="primer_apellido" required value="<?php echo htmlspecialchars($usuario['primer_apellido'] ?? ''); ?>" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Segundo Apellido</label>
                    <input type="text" name="segundo_apellido" value="<?php echo htmlspecialchars($usuario['segundo_apellido'] ?? ''); ?>" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Teléfono</label>
                    <input type="tel" name="telefono" value="<?php echo htmlspecialchars($usuario['telefono'] ?? ''); ?>" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none text-sm" placeholder="Ej. 7890-1234">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Correo Electrónico</label>
                    <input type="email" value="<?php echo htmlspecialchars($usuario['correo'] ?? ''); ?>" disabled class="w-full px-4 py-2.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-400 text-sm cursor-not-allowed">
                    <p class="text-xs text-slate-400 mt-1">El correo no puede modificarse. Contáctenos para cambios.</p>
                </div>
            </div>
            <div class="flex justify-end mt-6">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm transition-colors">Guardar Cambios</button>
            </div>
        </div>
    </form>
</div>

<!-- ── TAB: Dirección ── -->
<div id="contenido-direccion" class="hidden">
    <form action="index.php?action=actualizar_direccion" method="POST">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h2 class="text-base font-semibold text-slate-800 mb-5">Dirección de Residencia</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Departamento</label>
                    <input type="text" name="departamento" value="<?php echo htmlspecialchars($direccion['departamento'] ?? ''); ?>" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Municipio</label>
                    <input type="text" name="municipio" value="<?php echo htmlspecialchars($direccion['municipio'] ?? ''); ?>" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Distrito</label>
                    <input type="text" name="distrito" value="<?php echo htmlspecialchars($direccion['distrito'] ?? ''); ?>" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Residencial / Colonia</label>
                    <input type="text" name="residencia_detalle" value="<?php echo htmlspecialchars($direccion['residencia_detalle'] ?? ''); ?>" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none text-sm" placeholder="Nombre del residencial o colonia">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Pasaje / Polígono / Calle</label>
                    <input type="text" name="pasaje_poligono_calle" value="<?php echo htmlspecialchars($direccion['pasaje_poligono_calle'] ?? ''); ?>" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Número de Casa</label>
                    <input type="text" name="numero_casa" value="<?php echo htmlspecialchars($direccion['numero_casa'] ?? ''); ?>" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Punto de Referencia</label>
                    <input type="text" name="punto_referencia" value="<?php echo htmlspecialchars($direccion['punto_referencia'] ?? ''); ?>" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none text-sm" placeholder="Ej. Frente al parque central, a 100m del semáforo...">
                </div>
            </div>
            <div class="flex justify-end mt-6">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm transition-colors">Guardar Dirección</button>
            </div>
        </div>
    </form>
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
                <p class="text-sm text-amber-700">Al cambiar tu contraseña, se cerrará tu sesión en todos los dispositivos.</p>
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
    ['personal','direccion','contrasena'].forEach(t => {
        document.getElementById('contenido-' + t).classList.add('hidden');
        document.getElementById('tab-' + t).className = 'px-5 py-2 rounded-lg text-sm font-medium transition-colors ' + TAB_INACTIVE;
    });
    document.getElementById('contenido-' + nombre).classList.remove('hidden');
    document.getElementById('tab-' + nombre).className = 'px-5 py-2 rounded-lg text-sm font-medium transition-colors ' + TAB_ACTIVE;
}

// Activar el tab correcto al cargar
cambiarTab('<?php echo htmlspecialchars($tab); ?>');
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
