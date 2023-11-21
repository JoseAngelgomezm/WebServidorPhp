var anterior = 0;
var actual = 1;
var secuencia = "";
// funcion temporizador
function temporizador() {
    // si el anterior es la primera ejecucion y es un 0
    if (anterior == 0) {
        // añade a secuencia el primer numero de la secuencia sin guion
        secuencia += " " + actual;
    // sino concatena un guion y el elemento actual
    } else {
        secuencia += " - " + actual;
    }
    // envia al worker un mensaje con la secuencia que lleva
    postMessage(secuencia);
    // en auxiliar guardo el siguiente numero de la secuencia
    aux = anterior + actual;
    // el anterior ahora, sera el actual
    anterior = actual;
    // el actual sera el calculo de la sucesion
    actual = aux;
    // ejecutar temporizador cada medio segundo
    setTimeout("temporizador()", 500);
}
temporizador();