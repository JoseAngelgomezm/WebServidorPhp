<?php
// Ruta del archivo JSON
$archivo = 'resultadosFototipos.json';

// Permitir cualquier origen (debes restringir esto según tus necesidades de seguridad)
header("Access-Control-Allow-Origin: *");

// Permitir ciertos métodos HTTP
header("Access-Control-Allow-Methods: POST");

// Permitir ciertos encabezados en las solicitudes
header("Access-Control-Allow-Headers: Content-Type");

// Manejo de la solicitud POST para aumentar el número en el archivo JSON
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Obtiene el número enviado por POST
    $datos = json_decode(file_get_contents('php://input'));
    $numero = $datos->cantidad;
    
    // obtener el json del fichero
    $contenido = file_get_contents($archivo);
    $archivo = json_decode($contenido);

    //devolver la cantidad de persona que han hecho el test que tienen el fototipo
    echo $archivo->$numero->cantidad;

    // actualizar el archivo
    $archivo->$numero->cantidad++;

    // volver a codificar el archivo
    $archivoActualizado = json_encode($archivo);
    
    // actualizar los datos en el archivo de respuestas
    file_put_contents("resultadosFototipos.json",$archivoActualizado);

}
?>