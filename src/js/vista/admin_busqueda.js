document.addEventListener('DOMContentLoaded', function () {
    var busqueda = document.getElementById('busqueda');
    var tabla = document.querySelector('.tabla');
    if (busqueda && tabla) {
        var filas = Array.from(tabla.querySelectorAll('tbody tr'));
        busqueda.addEventListener('input', function () {
            var texto = busqueda.value.toLowerCase();
            filas.forEach(function (fila) {
                var celdas = Array.from(fila.children);
                var coincide = celdas.some(function (celda) {
                    return celda.textContent.toLowerCase().includes(texto);
                });
                fila.style.display = coincide ? '' : 'none';
            });
        });
    }
});
