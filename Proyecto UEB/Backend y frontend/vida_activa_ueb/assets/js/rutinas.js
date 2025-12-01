let contadorEjercicios = 0;

function agregarEjercicio() {
    const select = document.getElementById('select-ejercicio');
    const idEjercicio = select.value;
    const nombreEjercicio = select.options[select.selectedIndex].getAttribute('data-nombre');

    if (!idEjercicio) {
        alert('Por favor selecciona un ejercicio');
        return;
    }

    const container = document.getElementById('ejercicios-agregados');
    
    const div = document.createElement('div');
    div.className = 'ejercicio-item';
    div.id = 'ejercicio-' + contadorEjercicios;
    div.innerHTML = `
        <div class="ejercicio-item-info">
            <strong>${nombreEjercicio}</strong>
            <input type="hidden" name="ejercicios[${contadorEjercicios}][id_ejercicio]" value="${idEjercicio}">
            
            <label>Series:</label>
            <input type="number" name="ejercicios[${contadorEjercicios}][series]" min="1" value="3" required>
            
            <label>Repeticiones:</label>
            <input type="text" name="ejercicios[${contadorEjercicios}][repeticiones]" value="10" required>
            
            <label>Duración (min):</label>
            <input type="number" name="ejercicios[${contadorEjercicios}][duracion_min]" min="0" value="0">
        </div>
        <button type="button" class="btn-quitar" onclick="quitarEjercicio(${contadorEjercicios})">Quitar</button>
    `;
    
    container.appendChild(div);
    contadorEjercicios++;
    select.value = '';
}

function quitarEjercicio(id) {
    const elemento = document.getElementById('ejercicio-' + id);
    elemento.remove();
}