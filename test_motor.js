var fs = require('fs');
var code = fs.readFileSync('C:/xampp/htdocs/Deckology/src/js/modelo/motor.js', 'utf8');
var MotorJuego;
eval(code.replace('class MotorJuego', 'MotorJuego = class MotorJuego'));
try {
  var m = new MotorJuego({
    zonaInicial: "0",
    zonaObj: null,
    cartas: { "0": [{ id_carta: 1, nombre: "Test", curacion: 10, elimina_id_evento: 1, codigo_icono: "X" }] },
    eventos: { "0": [{ id_evento: 1, nombre: "E1", dano: 5, turnos_duracion: 3, codigo_icono: "Y" }] }
  });
  m.robarCarta();
  console.log("mano:", m.mano.length, "energia:", m.energia);
  var r = m.usarCarta(m.mano[0]);
  console.log("usarCarta:", JSON.stringify(r));
  var t = m.avanzarTurno();
  console.log("avanzar:", JSON.stringify({ dano: t.dano, vida: t.vida, energia: t.energia }));
  console.log("SUCCESS");
} catch(e) {
  console.error("ERROR:", e.message);
  console.error(e.stack);
}
