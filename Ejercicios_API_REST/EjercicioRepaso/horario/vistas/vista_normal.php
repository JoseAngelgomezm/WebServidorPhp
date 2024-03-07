<?php
// obtener el horario del profesor que esta logueado

$url = URLREPASO . "/obtenerHorarioProfesor";
$datos["api_session"] = $_SESSION["api_session"];
$datos["id_usuario"] = $datos_usuario_logueado->id_usuario;
$respuesta = consumir_servicios_REST($url, "get", $datos);
$archivo = json_decode($respuesta);

if (!$archivo) {
    session_destroy();
    die("no se ha obtenido respuesta en la url " . $url . "");
}

if (isset($archivo->error)) {
    session_destroy();
    die("no se ha podido obtener los datos " . $archivo->error . "");
}

if (isset($archivo->mensaje)) {
    $error = $archivo->mensaje;
}

if (isset($archivo->horario)) {
    $datos_horarios = $archivo->horario;
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        table {
            border: solid 1px black;
            text-align: center;
        }

        table td {
            border: solid 1px black;
            border-collapse: collapse;
        }
    </style>
</head>

<body>
    <h2>Bienvenido usuario normal
        <?php echo $datos_usuario_logueado->nombre ?>
        <form method="post" action="index.php"><button name="salir">Salir</button></form>
    </h2>
    <h2>Su horario</h2>
    <?php
    if (isset($error)) {
        echo $error;
    } else {

        foreach ($datos_horarios as $value) {
            if (isset($horarioDiaHora[$value->dia][$value->hora])) {
                $horarioDiaHora[$value->dia][$value->hora] .= "/" . $value->nombre;
            } else {
                $horarioDiaHora[$value->dia][$value->hora] = $value->nombre;
            }

        }

        $dias = ["", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes"];
        $horas = ["", "8:15 - 9:15", "9:15 - 10:15", "10:15 - 11:15", "11:15 - 11:45", "11:45 - 12:45", "12:45 - 13:45", "13:45 - 14:45"];
        echo "<table>";
        echo "<tr>";
        for ($i = 0; $i <= 5; $i++) {
            echo "<td>";
            echo $dias[$i];
            echo "</td>";
        }
        echo "</tr>";
        for ($i = 1; $i <= 7; $i++) {
            echo "<tr>";
            if ($i == 4) {
                echo "<td>";
                echo $horas[$i];
                echo "</td>";
                echo "<td colspan='5'>";
                echo "RECREO";
                echo "</td>";
            } else {
                for ($j = 1; $j <= 5; $j++) {
                    if ($j == 1) {
                        echo "<td>";
                        echo $horas[$i];
                        echo "</td>";
                    }

                    if (isset($horarioDiaHora[$j][$i])) {
                        echo "<td>" . $horarioDiaHora[$j][$i] . "</td>";
                    } else {
                        echo "<td></td>";
                    }

                }
                echo "</tr>";
            }

        }

        echo "</table>";
    }
    ?>
</body>

</html>