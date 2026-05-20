<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MedCare</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 flex flex-col min-h-screen">
    <div class="flex-grow flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 text-white rounded-2xl mb-4 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-slate-800">Sistema MedCare</h1>
                <p class="text-slate-500 mt-2 text-lg">Gestión de Citas Médicas</p>
            </div>

            <div class="bg-white rounded-2xl shadow-xl p-8 border border-slate-100">
                <h2 class="text-2xl font-semibold text-slate-800 mb-2">Iniciar Sesión</h2>
                <p class="text-slate-500 mb-6">Ingrese sus credenciales para acceder al panel</p>

                <?php if(isset($error)): ?>
                    <div class="bg-red-50 text-red-600 p-3 rounded-lg mb-4 text-sm border border-red-100">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form action="index.php?action=authenticate" method="POST" class="space-y-5">
                    <div>
                        <label for="correo" class="block text-sm font-medium text-slate-700 mb-1">Correo Electrónico</label>
                        <input type="email" id="correo" name="correo" placeholder="doctor@hospital.com" required 
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    </div>
                    
                    <div>
                        <label for="contrasena" class="block text-sm font-medium text-slate-700 mb-1">Contraseña</label>
                        <input type="password" id="contrasena" name="contrasena" placeholder="Ingrese su contraseña" required 
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition-colors duration-200">
                        Ingresar
                    </button>
                </form>



                <div class="text-center mt-6">
                    <p class="text-slate-600 text-sm">
                        ¿Eres un paciente nuevo? 
                        <a href="index.php?action=register" class="text-blue-600 hover:text-blue-700 font-semibold transition-colors">Regístrate aquí</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <footer class="text-center py-6 text-slate-500 text-sm">
        &copy; 2026 Sistema MedCare. Todos los derechos reservados.
    </footer>
</body>
</html>
