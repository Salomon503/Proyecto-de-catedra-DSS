<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="mb-8">
    <h1 class="text-2xl font-bold text-slate-800">Panel de Control</h1>
    <p class="text-slate-500 mt-1">¡Bienvenido de nuevo! Aquí está tu resumen.</p>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Stat 1 -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col">
        <div class="flex justify-between items-start mb-4">
            <span class="text-sm font-medium text-slate-500">Total Pacientes</span>
            <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
            </div>
        </div>
        <div class="text-3xl font-bold text-slate-800 mb-1"><?php echo $stats['total_pacientes']; ?></div>
        <div class="text-sm text-slate-500 mt-auto">
            <span class="text-emerald-500 font-medium">+<?php echo $stats['pacientes_mes_pasado_pct']; ?>%</span> del mes pasado
        </div>
    </div>

    <!-- Stat 2 -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col">
        <div class="flex justify-between items-start mb-4">
            <span class="text-sm font-medium text-slate-500">Total Doctores</span>
            <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12.75 19.5v-.75a7.5 7.5 0 00-7.5-7.5H4.5m0-6.75h.75c7.87 0 14.25 6.38 14.25 14.25v.75M6 18.75a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" /></svg>
            </div>
        </div>
        <div class="text-3xl font-bold text-slate-800 mb-1"><?php echo $stats['total_doctores']; ?></div>
        <div class="text-sm text-slate-500 mt-auto">
            <span class="text-emerald-500 font-medium">+<?php echo $stats['doctores_nuevos_mes']; ?></span> nuevos este mes
        </div>
    </div>

    <!-- Stat 3 -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col">
        <div class="flex justify-between items-start mb-4">
            <span class="text-sm font-medium text-slate-500">Programadas</span>
            <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" /></svg>
            </div>
        </div>
        <div class="text-3xl font-bold text-slate-800 mb-1"><?php echo $stats['citas_programadas']; ?></div>
        <div class="text-sm text-slate-500 mt-auto">
            <span class="text-emerald-500 font-medium">+<?php echo $stats['citas_semana_pasada_pct']; ?>%</span> de la semana pasada
        </div>
    </div>

    <!-- Stat 4 -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col">
        <div class="flex justify-between items-start mb-4">
            <span class="text-sm font-medium text-slate-500">Completadas</span>
            <div class="w-10 h-10 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
        </div>
        <div class="text-3xl font-bold text-slate-800 mb-1"><?php echo $stats['citas_completadas']; ?></div>
        <div class="text-sm text-slate-500 mt-auto">
            <span class="text-emerald-500 font-medium"><?php echo $stats['tasa_finalizacion']; ?>%</span> tasa de finalización
        </div>
    </div>
</div>

<!-- Lists section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Próximas Citas -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-lg font-semibold text-slate-800">Próximas Citas</h2>
                <p class="text-sm text-slate-500">Siguientes citas programadas</p>
            </div>
            <a href="index.php?action=citas" class="text-sm font-medium text-slate-700 bg-slate-50 hover:bg-slate-100 px-4 py-2 rounded-lg border border-slate-200 transition-colors">Ver Todas</a>
        </div>

        <div class="space-y-4 flex-1">
            <?php if(empty($proximasCitas)): ?>
                <div class="text-slate-500 text-sm text-center py-4">No hay citas programadas próximas.</div>
            <?php else: ?>
                <?php foreach($proximasCitas as $cita): ?>
                    <div class="flex items-start gap-4 p-4 rounded-xl border border-slate-100 bg-slate-50/50">
                        <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start gap-2">
                                <div class="truncate">
                                    <h3 class="font-semibold text-slate-800 truncate"><?php echo htmlspecialchars($cita['paciente_nombre']); ?></h3>
                                    <p class="text-sm text-slate-500 truncate">Dr. <?php echo htmlspecialchars($cita['medico_nombre']); ?></p>
                                </div>
                                <span class="px-3 py-1 bg-blue-600 text-white text-xs font-medium rounded-full shrink-0"><?php echo htmlspecialchars($cita['estado']); ?></span>
                            </div>
                            <div class="mt-2 text-sm text-slate-600">
                                <?php echo htmlspecialchars($cita['fecha']); ?> &bull; <?php echo date("g:i A", strtotime($cita['hora'])); ?><br>
                                <span class="text-slate-500"><?php echo htmlspecialchars($cita['motivo']); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Pacientes Recientes -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-lg font-semibold text-slate-800">Pacientes Recientes</h2>
                <p class="text-sm text-slate-500">Últimos registros de pacientes</p>
            </div>
            <a href="#" class="text-sm font-medium text-slate-700 bg-slate-50 hover:bg-slate-100 px-4 py-2 rounded-lg border border-slate-200 transition-colors">Ver Todos</a>
        </div>

            <?php if(empty($pacientesRecientes)): ?>
                <div class="text-slate-500 text-sm text-center py-4">No hay pacientes registrados.</div>
            <?php else: ?>
                <?php foreach($pacientesRecientes as $paciente): 
                    $iniciales = strtoupper(substr($paciente['primer_nombre'], 0, 1) . substr($paciente['primer_apellido'], 0, 1));
                ?>
                    <div class="flex items-center justify-between p-4 rounded-xl border border-slate-100 bg-white shadow-sm">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm border border-emerald-100 shrink-0">
                                <?php echo $iniciales; ?>
                            </div>
                            <div>
                                <h3 class="font-medium text-slate-800"><?php echo htmlspecialchars($paciente['primer_nombre'] . ' ' . $paciente['primer_apellido']); ?></h3>
                                <p class="text-xs text-slate-500"><?php echo htmlspecialchars($paciente['correo']); ?></p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-slate-400 block mb-0.5">ID</span>
                            <span class="text-sm font-medium text-slate-700">#<?php echo $paciente['id_paciente']; ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
