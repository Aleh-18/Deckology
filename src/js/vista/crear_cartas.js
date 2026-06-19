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
let creandoEventoNuevo = document.getElementById("creandoEventoNuevo");

// FUNCIONES 

// Alternar entre crear evento nuevo o seleccionar existente
btnCrearEvento.addEventListener('click', function() {
    
    //Compobar el valor del campo par la accion del boton 0 No se esta creando evento 1 Si se esta creando

    if (creandoEventoNuevo.value === "0") {
        creandoEventoNuevo.value = "1";
        // Mostrar formulario de evento nuevo
        formularioEventoNuevo.style.display="block";
        btnCrearEvento.textContent = 'Cancelar';
        btnCrearEvento.style.backgroundColor="red";
        
        // Deshabilitar el select de eventos 
        eventoSelect.disabled = true;
        eventoSelect.value = '';

        
        
    } else {
        creandoEventoNuevo.value = "0";
        // Ocultar formulario
        formularioEventoNuevo.style.display="none";
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
opcionZona.addEventListener('change',  async function(){

    let zona = this.value; //Obiene el valor del elemento
    
    let response = await fetch(`index.php?c=Evento&m=obtenerEventos&zona=${zona}`);
    
    let eventos = await response.json();

    if(eventos.length === 0){
        mostrar("No hay eventos para esta zona");
    }else{
        añadirOpciones(eventoSelect,eventos);
    }
});


function añadirOpciones(select, datos) { 
    
    //Limpiar todas las opciones existentes
    select.innerHTML = '';
    
    // Re-añadir la opción por defecto
    select.add(new Option('Seleccionar evento', ''));
    
    //  Añadir los nuevos datos
    datos.forEach((dato) => {

        select.add(new Option(
            dato["nombre"],  //Dato visible
            dato["id"]));    //Valor
    });
}

//Mostrar errores
function mostrar(mensaje) {
    alert(mensaje);
}