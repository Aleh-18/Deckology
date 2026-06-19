document.addEventListener("DOMContentLoaded", () => {
    const tbody = document.querySelector("#tablaPuntuaciones tbody");
    if (!tbody) return;
    const filas = Array.from(tbody.querySelectorAll("tr"));
    const filtroInput = document.getElementById("filtroNombre");
    const botonOrdenar = document.getElementById("botonOrdenar");

    let ordenAscendente = false;

    filtroInput.addEventListener("input", () => {
        const texto = filtroInput.value.toLowerCase();
        filas.forEach(fila => {
            const nombre = fila.children[1].textContent.toLowerCase();
            fila.style.display = nombre.includes(texto) ? "" : "none";
        });
    });

    botonOrdenar.addEventListener("click", () => {
        const visibles = filas.filter(f => f.style.display !== "none");
        const ordenadas = visibles.sort((a, b) => {
            const scoreA = parseInt(a.children[2].textContent.replace(/,/g, ""));
            const scoreB = parseInt(b.children[2].textContent.replace(/,/g, ""));
            return ordenAscendente ? scoreA - scoreB : scoreB - scoreA;
        });

        ordenadas.forEach(f => tbody.appendChild(f));
        ordenAscendente = !ordenAscendente;
    });
});
