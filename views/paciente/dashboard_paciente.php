<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="mb-8">
    <h1 class="text-2xl font-bold text-slate-800">Panel del Paciente</h1>
    <p class="text-slate-500 mt-1">¡Hola <?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?>! Bienvenido a MedCare.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col">
        <div class="text-sm font-medium text-slate-500 mb-2">Citas Pendientes</div>
        <div class="text-3xl font-bold text-slate-800 mb-1"><?php echo $stats['citas_programadas']; ?></div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col">
        <div class="text-sm font-medium text-slate-500 mb-2">Citas Históricas (Completadas)</div>
        <div class="text-3xl font-bold text-slate-800 mb-1"><?php echo $stats['citas_completadas']; ?></div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Próximas Citas -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-semibold text-slate-800">Mis Próximas Citas</h2>
            <a href="index.php?action=citas" class="text-sm font-medium text-blue-600 hover:text-blue-700">Ver Historial Completo</a>
        </div>
        <div class="space-y-4">
            <?php if(empty($proximasCitas)): ?>
                <div class="text-slate-500 text-sm text-center py-4">No tiene citas programadas próximas.</div>
            <?php else: ?>
                <?php foreach($proximasCitas as $cita): ?>
                    <div class="flex justify-between items-center p-4 border border-slate-100 rounded-xl bg-slate-50/50">
                        <div>
                            <div class="font-medium text-slate-800">Dr. <?php echo htmlspecialchars($cita['medico_nombre']); ?></div>
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
            <a href="index.php?action=agendar_cita" class="flex items-center gap-4 p-4 rounded-xl border border-slate-100 hover:bg-slate-50 transition-colors">
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                </div>
                <div>
                    <div class="font-medium text-slate-800">Agendar Nueva Cita</div>
                    <div class="text-sm text-slate-500">Programe una consulta con nuestros doctores</div>
                </div>
            </a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
