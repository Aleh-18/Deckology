let cartaIdParaBorrar = null;

function confirmarEliminar(nombre, id) {
    cartaIdParaBorrar = id;
    document.getElementById('nombreCartaBorrar').innerText = nombre;
    document.getElementById('modalConfirmacion').style.display = 'flex';
}

function cerrarModal() {
    document.getElementById('modalConfirmacion').style.display = 'none';
    cartaIdParaBorrar = null;
}

document.addEventListener('DOMContentLoaded', function () {
    // Lógica del Modal
    const btnConfirmar = document.getElementById('btnConfirmarBorrado');
    if (btnConfirmar) {
        btnConfirmar.addEventListener('click', function () {
            if (cartaIdParaBorrar) {
                window.location.href = "index.php?c=Carta&m=eliminarCarta&id=" + cartaIdParaBorrar;
            }
        });
    }

    // Lógica del Buscador
    const filtroInput = document.getElementById("filtroNombre");
    const tabla = document.getElementById("tablaCartas");

    if (filtroInput && tabla) {
        const filas = Array.from(tabla.querySelectorAll("tbody tr"));

        filtroInput.addEventListener("input", () => {
            const texto = filtroInput.value.toLowerCase();

            filas.forEach(fila => {
                // Buscamos en la primera columna (Nombre)
                // Ajustar índice si la columna nombre no es la primera, pero en lCartas.php es la primera.
                const nombre = fila.children[0].textContent.toLowerCase();
                fila.style.display = nombre.includes(texto) ? "" : "none";
            });
        });
    }
});
