<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="index.php?action=citas" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Calificar Atención Médica</h1>
            <p class="text-slate-500 mt-1">Comparte tu experiencia para ayudarnos a mejorar</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
            <h2 class="font-semibold text-slate-800 mb-4">Detalles de la Cita</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                <div>
                    <div class="text-slate-500 mb-1">Doctor</div>
                    <div class="font-medium text-slate-800">Dr. <?php echo htmlspecialchars($cita['medico_nombre']); ?></div>
                </div>
                <div>
                    <div class="text-slate-500 mb-1">Especialidad</div>
                    <div class="font-medium text-slate-800"><?php echo htmlspecialchars($cita['nombre_especialidad']); ?></div>
                </div>
                <div>
                    <div class="text-slate-500 mb-1">Fecha</div>
                    <div class="font-medium text-slate-800"><?php echo htmlspecialchars($cita['fecha']); ?></div>
                </div>
                <div>
                    <div class="text-slate-500 mb-1">Motivo</div>
                    <div class="font-medium text-slate-800"><?php echo htmlspecialchars($cita['motivo']); ?></div>
                </div>
            </div>
        </div>

        <form action="index.php?action=guardar_calificacion" method="POST" class="p-6">
            <input type="hidden" name="id_cita" value="<?php echo $cita['id_cita']; ?>">

            <div class="mb-8 text-center">
                <label class="block text-sm font-medium text-slate-700 mb-4">¿Cómo calificarías la atención recibida?</label>
                <div class="flex justify-center gap-2" id="star-rating">
                    <?php for($i=1; $i<=5; $i++): ?>
                        <label class="cursor-pointer group">
                            <input type="radio" name="puntuacion" value="<?php echo $i; ?>" class="sr-only" required>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-10 h-10 text-slate-200 group-hover:text-amber-400 transition-colors star-icon" data-value="<?php echo $i; ?>"><path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd" /></svg>
                        </label>
                    <?php endfor; ?>
                </div>
                <div id="rating-text" class="text-sm font-medium text-slate-500 mt-3 h-5"></div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 mb-1">Comentario (Opcional)</label>
                <textarea name="comentario" rows="4" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-slate-700 resize-none" placeholder="Cuéntanos más sobre tu experiencia..."></textarea>
            </div>

            <div class="flex gap-4">
                <a href="index.php?action=citas" class="flex-1 px-4 py-2.5 border border-slate-300 text-slate-700 font-medium rounded-lg hover:bg-slate-50 transition-colors text-center">Cancelar</a>
                <button type="submit" class="flex-1 px-4 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">Enviar Calificación</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star-icon');
    const radios = document.querySelectorAll('input[name="puntuacion"]');
    const ratingText = document.getElementById('rating-text');
    
    const texts = {
        1: 'Muy mala',
        2: 'Mala',
        3: 'Regular',
        4: 'Buena',
        5: 'Excelente'
    };

    function updateStars(value) {
        stars.forEach(s => {
            if (parseInt(s.dataset.value) <= value) {
                s.classList.remove('text-slate-200');
                s.classList.add('text-amber-500');
            } else {
                s.classList.add('text-slate-200');
                s.classList.remove('text-amber-500');
            }
        });
        ratingText.textContent = value > 0 ? texts[value] : '';
    }

    stars.forEach(star => {
        star.addEventListener('mouseenter', function() {
            updateStars(parseInt(this.dataset.value));
        });
        
        star.addEventListener('mouseleave', function() {
            const checkedRadio = document.querySelector('input[name="puntuacion"]:checked');
            if (checkedRadio) {
                updateStars(parseInt(checkedRadio.value));
            } else {
                updateStars(0);
            }
        });
    });

    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            updateStars(parseInt(this.value));
        });
    });
});
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
