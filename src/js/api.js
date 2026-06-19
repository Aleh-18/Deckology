class api {

    // 1. Función para pedir CARTAS
    static async obtenerCartas(zona) {
        try {
            const respuesta = await fetch("index.php?c=Carta&m=obtenerCartasJuego&zona=" + zona);

            // Convertimos lo que llega a JSON y lo devolvemos
            const datos = await respuesta.json();
            return datos;

        } catch (error) {
            console.log("Error buscando cartas:", error);
            return []; // Si falla, devolvemos una lista vacía para que no se rompa
        }
    }

    // 2. Función para pedir EVENTOS 
    static async obtenerEventos(zona) {
        try {
            const respuesta = await fetch("index.php?c=Evento&m=obtenerEventosJuego&zona=" + zona);

            // Convertimos y devolvemos
            const datos = await respuesta.json();
            return datos;

        } catch (error) {
            console.log("Error buscando eventos:", error);
            return [];
        }
    }

    static async obtenerZonas() {
        try {
            const respuesta = await fetch("index.php?c=Zona&m=obtenerZonas");
            return await respuesta.json();
        } catch (error) {
            console.log(error); return [];
        }
    }

    // 4. Guardar Puntuación (POST)
    static async guardarPuntuacion(puntos) {
        try {
            const formData = new FormData();
            formData.append('puntos', puntos);

            const respuesta = await fetch("index.php?c=Usuario&m=guardarPuntuacion", {
                method: 'POST',
                body: formData
            });

            return await respuesta.json();
        } catch (error) {
            console.log("Error guardando puntuacion:", error);
            return { error: true };
        }
    }
}