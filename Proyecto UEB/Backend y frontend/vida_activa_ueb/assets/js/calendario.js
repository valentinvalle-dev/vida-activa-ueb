// Función para mostrar el modal
function mostrarModal() {
    document.getElementById('modalAgregarRutina').style.display = 'block';
}

// Función para cerrar el modal
function cerrarModal() {
    document.getElementById('modalAgregarRutina').style.display = 'none';
}

// Cerrar modal al hacer clic fuera de él
window.onclick = function(event) {
    const modal = document.getElementById('modalAgregarRutina');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}

// Cerrar modal con la tecla ESC
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        cerrarModal();
    }
});