<?php
$url = URLREPASO . "/obtenerProfesoresGuardia/".date("w");
$datos["api_session"] = $_SESSION["api_session"];
$respuesta = consumir_servicios_REST($url, "get", $datos);
$archivo = json_decode($respuesta);

if (!$archivo) {
    session_destroy();
    die("No se ha obtenido respuesta en " . $url . "");
}

if (isset($archivo->error)) {
    session_destroy();
    die("No se ha obtenido respuesta en " . $archivo->error . "");
}

$datos_profesores_guardia = $archivo->profesores_guardia;


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h2>Bienvenido admin
        <?php echo $datos_usuario_logueado->nombre ?>
        <form method="post" action="index.php"><button name="salir">Salir</button></form>
    </h2>

    <?php
    // obtener el dia actual
    $dia = date("w");

    $dias = ["Lunes", "Martes", "Miercoles", "Jueves", "Viernes"];
    $horas = [" ", "8:15 - 9:15", "9:15 - 10:15", "10:15 - 11:15", "11:15 - 11:45", "11:45 - 12:45", "12:45 - 13:45", "13:45 - 14:45"];
    
    echo "<h2>Hoy es " . $dias[$dia - 1] . "</h2>";
    
    echo "<table width='100%' border='solid 1px black'>";

    echo "<tr>";

    echo "<td>HORAS</td>";
    echo "<td>PROFESOR DE GUARDIA</td>";
    echo "<td>Informacion del profesor</td>";

    echo "</tr>";

    for ($i = 1; $i < 8; $i++) {
        echo "<tr>";
        echo "<td>" . $horas[$i] . "</td>";

        echo "<td>";
        echo "<ol>";
        foreach ($datos_profesores_guardia as $value) {
            if ($value->hora == $i) {
                echo "<li>";
                echo "<form method='post' action='index.php'>";
                echo "<button name='ver' type='submit' value='" . $value->id_usuario . "'>" . $value->nombre . "</button>";
                echo "<input hidden name='hora' value='".$i."'></input>";
                echo "</form>";
                echo "</li>";
            }
        }
        echo "</ol>";
        echo "</td>";

        echo "<td>";
        
        echo "</td>";

        echo "<tr>";
    }

    echo "</table>";
        ?>

</body>

</html>