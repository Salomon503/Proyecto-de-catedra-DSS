<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="mb-8">
    <h1 class="text-2xl font-bold text-slate-800">Panel Médico</h1>
    <p class="text-slate-500 mt-1">¡Bienvenido Dr(a). <?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?>! Aquí está su resumen.</p>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col">
        <div class="text-sm font-medium text-slate-500 mb-2">Citas Hoy</div>
        <div class="text-3xl font-bold text-slate-800 mb-1"><?php echo $stats['citas_hoy']; ?></div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col">
        <div class="text-sm font-medium text-slate-500 mb-2">Total Programadas</div>
        <div class="text-3xl font-bold text-slate-800 mb-1"><?php echo $stats['citas_programadas']; ?></div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col">
        <div class="text-sm font-medium text-slate-500 mb-2">Completadas</div>
        <div class="text-3xl font-bold text-slate-800 mb-1"><?php echo $stats['citas_completadas']; ?></div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col">
        <div class="text-sm font-medium text-slate-500 mb-2">Pacientes Atendidos</div>
        <div class="text-3xl font-bold text-slate-800 mb-1"><?php echo $stats['pacientes_atendidos']; ?></div>
    </div>
</div>

<!-- Tarjeta puntuación -->
<?php if(!is_null($stats['puntuacion_promedio'])): ?>
<div class="bg-white rounded-2xl shadow-sm border border-amber-100 p-6 mb-8 flex items-center gap-6">
    <div class="w-14 h-14 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-7 h-7 text-amber-500"><path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd" /></svg>
    </div>
    <div>
        <div class="text-sm font-medium text-slate-500 mb-1">Tu Puntuación Promedio <span class="text-slate-400">(<?php echo $stats['total_calificaciones']; ?> reseñas)</span></div>
        <div class="flex items-center gap-3">
            <span class="text-3xl font-bold text-amber-500"><?php echo $stats['puntuacion_promedio']; ?></span>
            <div class="flex gap-0.5">
                <?php $prom = round($stats['puntuacion_promedio']); for($i=1; $i<=5; $i++): ?>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 <?php echo $i <= $prom ? 'text-amber-500' : 'text-slate-200'; ?>"><path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd" /></svg>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Próximas Citas -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-semibold text-slate-800">Mis Próximas Citas</h2>
            <a href="index.php?action=citas" class="text-sm font-medium text-blue-600 hover:text-blue-700">Ver Todas</a>
        </div>
        <div class="space-y-4">
            <?php if(empty($proximasCitas)): ?>
                <div class="text-slate-500 text-sm text-center py-4">No tiene citas programadas próximas.</div>
            <?php else: ?>
                <?php foreach($proximasCitas as $cita): ?>
                    <div class="flex justify-between items-center p-4 border border-slate-100 rounded-xl bg-slate-50/50">
                        <div>
                            <div class="font-medium text-slate-800"><?php echo htmlspecialchars($cita['paciente_nombre']); ?></div>
                            <div class="text-sm text-slate-500"><?php echo htmlspecialchars($cita['motivo']); ?></div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-medium text-slate-800"><?php echo htmlspecialchars($cita['fecha']); ?></div>
                            <div class="text-sm text-slate-500"><?php echo date("g:i A", strtotime($cita['hora'])); ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Accesos Rápidos -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <h2 class="text-lg font-semibold text-slate-800 mb-6">Accesos Rápidos</h2>
        <div class="grid grid-cols-1 gap-4">
            <a href="index.php?action=horarios" class="flex items-center gap-4 p-4 rounded-xl border border-slate-100 hover:bg-slate-50 transition-colors">
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <div class="font-medium text-slate-800">Gestionar mis Horarios</div>
                    <div class="text-sm text-slate-500">Definir disponibilidad para consultas</div>
                </div>
            </a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
