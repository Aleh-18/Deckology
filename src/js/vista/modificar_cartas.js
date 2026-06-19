// Referenciar a los elementos
//Boton crear evento
const btnCrearEvento = document.getElementById('btnCrearEvento');
//Formulario donde creo el evento nuevo
const formularioEventoNuevo = document.getElementById('formularioEventoNuevo');
//Select con los eventos disponibles
const eventoSelect = document.getElementById('eventoSelect');
//Zona seleccionada por usuario
const opcionZona = document.getElementById("zonaSelect");
// Variable para controlar el estado del formulario
const creandoEventoNuevo = document.getElementById("creandoEventoNuevo");


// FUNCIONES 

// Alternar entre crear evento nuevo o seleccionar existente
btnCrearEvento.addEventListener('click', function () {

    //Compobar el valor del campo par la accion del boton 0 No se esta creando evento 1 Si se esta creando

    if (creandoEventoNuevo.value === "0") {
        creandoEventoNuevo.value = "1";
        // Mostrar formulario de evento nuevo
        formularioEventoNuevo.style.display = "block";
        btnCrearEvento.textContent = 'Cancelar';
        btnCrearEvento.style.backgroundColor = "red";

        // Deshabilitar el select de eventos 
        eventoSelect.disabled = true;
        eventoSelect.value = '';



    } else {
        creandoEventoNuevo.value = "0";
        // Ocultar formulario
        formularioEventoNuevo.style.display = "none";
        btnCrearEvento.textContent = 'Crear evento';
        btnCrearEvento.style.backgroundColor = "#667eea";

        // Habilitar el select de eventos
        eventoSelect.disabled = false;

        // Reiniciar los campos al cerrarlo
        document.getElementById('nombreEvento').value = '';
        document.getElementById('descripcionEvento').value = '';
        document.getElementById('danoEvento').value = '';
        document.getElementById('rondasEvento').value = '';
        document.getElementById('emoticonoEvento').value = '';

    }
});

//Se activa al cambiar la opcion en el select
opcionZona.addEventListener('change', async function () {

    let zona = this.value; //Obiene el valor del elemento
    // Cuando el usuario cambia la zona, no queremos preseleccionar nada antiguo
    const idEventoAntiguo = '';

    //  Enviamos el idAntiguo vacío al servidor
    let response = await fetch(`index.php?c=Evento&m=obtenerEventos&zona=${zona}&idAntiguo=${idEventoAntiguo}`);

    let eventos = await response.json();

    if (eventos.length === 0) {
        mostrar("No hay eventos para esta zona");
    } else {
        //Pasamos el idAntiguo vaci a la función
        añadirOpciones(eventoSelect, eventos, idEventoAntiguo);
    }
});


document.addEventListener("DOMContentLoaded", async function () {

    //Obtener id de zona y evento de la carta
    const zona = opcionZona.value;
    const idEventoAntiguo = document.getElementById('idEventoAntiguo').value; // Obtenemos el ID

    // Comprobar que se selecciono zona
    if (zona) {

        // Envio la zona y el idAntiguo real para que el servidor lo incluya
        let response = await fetch(`index.php?c=Evento&m=obtenerEventos&zona=${zona}&idAntiguo=${idEventoAntiguo}`);

        let eventos = await response.json();

        if (eventos.length === 0) {
            mostrar("No hay eventos para esta zona");
        } else {
            // AÑADIR OPCIONES Y PRE-SELECCIONARr
            añadirOpciones(eventoSelect, eventos, idEventoAntiguo);
        }
    }
});


function añadirOpciones(select, datos, idEventoAntiguo) {

    // Elimina todas las opciones actuales para que el selector esté vacío.
    select.innerHTML = '';

    select.add(new Option('Seleccionar evento', ''));

    datos.forEach((dato) => {

        // Comprobamos el id con id de la carta para generar un boleano
        let isSelected = (dato["id"] == idEventoAntiguo);

        // Crea el nuevo objeto Option usando los 4 parámetros:
        let nuevaOpcion = new Option(
            dato["nombre"],     // Dato visible
            dato["id"],         // Valor 
            isSelected,         // defaultSelected opcion por defecto selecionada
            isSelected          // selected  Estar o no seleccionado
        );

        // Agrega la opción al selector.
        select.add(nuevaOpcion);
    });
}

//Mostrar errores
function mostrar(mensaje) {
    alert(mensaje);
}

///MODAL 


// MODAL LOGIC
const btnEliminar = document.getElementById("btnEliminarCarta");
const modalConfirmacion = document.getElementById("modalConfirmacion");
const btnCancelarModal = document.getElementById("btnCancelarModal");
const btnConfirmarBorrado = document.getElementById("btnConfirmarBorrado");

if (btnEliminar && modalConfirmacion) {
    btnEliminar.addEventListener("click", function () {
        modalConfirmacion.style.display = "flex";
    });
}

if (btnCancelarModal) {
    btnCancelarModal.addEventListener("click", function (e) {
        e.preventDefault(); // Prevent bubbling if inside form
        modalConfirmacion.style.display = "none";
    });
}

if (btnConfirmarBorrado) {
    btnConfirmarBorrado.addEventListener("click", function (e) {
        e.preventDefault(); // Prevent form submit
        // Redirect to delete URL
        const idCarta = new URLSearchParams(window.location.search).get("id");
        if (idCarta) {
            window.location.href = `index.php?c=Carta&m=eliminarCarta&id=${idCarta}`;
        }
    });
}
