<?php

$usuario = $datos_usuario_log->cod_usu;

$url = DIR_SERV . "/notasAlumno/" . $usuario . "";
$datos["api_session"] = $_SESSION["api_session"];
$respuesta = consumir_servicios_REST($url, "get", $datos);

$archivo = json_decode($respuesta);

if (!$archivo) {
    session_destroy();
    die(error_page("Examen4 DWESE Curso 23-24", "<h1>Notas de los alumnos</h1><p>Error consumiendo el servicio: " . $url . "</p>"));
}

if (isset($archivo->error)) {
    session_destroy();
    die(error_page("Examen4 DWESE Curso 23-24", "<h1>Notas de los alumnos</h1><p>" . $archivo->error . "</p>"));
}

if (isset($archivo->mensaje)) {
    $mensaje = "En estos momentos no tiene asignaturas calificadas";
}





?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h2>Notas de los alumnos</h2>
    <p>Bienvenido
        <?php echo $datos_usuario_log->usuario ?>
    <form action="index.php" method="post"><button type="submit" name="btnSalir">Salir</button></form>
    </p>

    <h2>Notas del alumno
        <?php echo $datos_usuario_log->nombre ?>
    </h2>
   

        <?php
        if (isset($mensaje)) {
            echo $mensaje;
        } else {
            $datosNotas = $archivo->notas;
            echo " <table border='solid 1 px black'>";
            echo "<tr>";
            echo "<td>Asignatura</td>";
            echo "<td>Nota</td>";
            echo "</tr>";
            foreach ($datosNotas as $value) {
                echo "<tr>";
                echo "<td>" . $value->denominacion . "</td>";
                echo "<td>" . $value->nota . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }

        ?>

</body>

</html>