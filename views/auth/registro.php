<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Paciente - MedCare</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass-panel {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4 sm:p-8 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1551076805-e1869033e561?auto=format&fit=crop&q=80&w=2000');">
    
    <div class="absolute inset-0 bg-blue-900/40 backdrop-blur-sm"></div>

    <div class="relative w-full max-w-4xl glass-panel rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row">
        
        <!-- Contenido Lateral (Informativo) -->
        <div class="w-full md:w-1/3 bg-blue-600 p-8 text-white flex flex-col justify-between hidden md:flex">
            <div>
                <div class="flex items-center gap-2 mb-12">
                    <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight">MedCare</span>
                </div>
                
                <h2 class="text-3xl font-bold mb-4 leading-tight">Tu salud en<br>buenas manos.</h2>
                <p class="text-blue-100 text-sm leading-relaxed">
                    Únete a MedCare y gestiona tus citas médicas, accede a tu expediente digital y comunícate con nuestros especialistas de forma rápida y segura.
                </p>
            </div>
            
            <div class="text-blue-200 text-xs">
                &copy; <?php echo date('Y'); ?> MedCare System.
            </div>
        </div>

        <!-- Formulario de Registro -->
        <div class="w-full md:w-2/3 p-8 sm:p-12 h-[80vh] overflow-y-auto">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-slate-800">Crear cuenta</h1>
                <p class="text-slate-500 text-sm mt-1">Completa tus datos para registrarte como paciente.</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form action="index.php?action=register" method="POST" class="space-y-6">
                <!-- Información Personal -->
                <div>
                    <h3 class="text-sm font-semibold text-slate-800 border-b border-slate-200 pb-2 mb-4">Información Personal</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Primer Nombre *</label>
                            <input type="text" name="primer_nombre" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all bg-slate-50 focus:bg-white text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Segundo Nombre</label>
                            <input type="text" name="segundo_nombre" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all bg-slate-50 focus:bg-white text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Primer Apellido *</label>
                            <input type="text" name="primer_apellido" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all bg-slate-50 focus:bg-white text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Segundo Apellido</label>
                            <input type="text" name="segundo_apellido" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all bg-slate-50 focus:bg-white text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Fecha de Nacimiento *</label>
                            <input type="date" name="fecha_nacimiento" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all bg-slate-50 focus:bg-white text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Género</label>
                            <select name="genero" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all bg-slate-50 focus:bg-white text-sm">
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Contacto y Cuenta -->
                <div>
                    <h3 class="text-sm font-semibold text-slate-800 border-b border-slate-200 pb-2 mb-4 mt-6">Contacto y Cuenta</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Correo Electrónico *</label>
                            <input type="email" name="correo" required placeholder="ejemplo@correo.com" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all bg-slate-50 focus:bg-white text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Teléfono</label>
                            <input type="tel" name="telefono" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all bg-slate-50 focus:bg-white text-sm">
                        </div>
                        <div class="sm:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Contraseña *</label>
                                <input type="password" name="contrasena" required minlength="8" placeholder="Mínimo 8 caracteres" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all bg-slate-50 focus:bg-white text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Confirmar Contraseña *</label>
                                <input type="password" name="confirmar_contrasena" required minlength="8" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all bg-slate-50 focus:bg-white text-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dirección -->
                <div>
                    <h3 class="text-sm font-semibold text-slate-800 border-b border-slate-200 pb-2 mb-4 mt-6">Dirección</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Departamento *</label>
                            <input type="text" name="departamento" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all bg-slate-50 focus:bg-white text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Municipio *</label>
                            <input type="text" name="municipio" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all bg-slate-50 focus:bg-white text-sm">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Detalle (Colonia, Calle, Casa)</label>
                            <input type="text" name="residencia_detalle" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all bg-slate-50 focus:bg-white text-sm">
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-xl transition-all shadow-lg shadow-blue-200 focus:ring-4 focus:ring-blue-100">
                        Completar Registro
                    </button>
                </div>

                <div class="text-center mt-6">
                    <p class="text-slate-600 text-sm">
                        ¿Ya tienes cuenta? 
                        <a href="index.php?action=login" class="text-blue-600 hover:text-blue-700 font-semibold transition-colors">Inicia sesión aquí</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
