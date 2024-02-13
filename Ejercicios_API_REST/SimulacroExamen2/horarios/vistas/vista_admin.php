<?php
// obtener todos los profesores
$url = URLBASE . "/obtenerProfesores";
$datos["api_session"] = $_SESSION["api_session"];
$respuesta = consumir_servicios_REST($url, "get", $datos);
$archivo = json_decode($respuesta);

if (!$archivo) {
    session_destroy();
    die(error_page("Error", "No se ha obtenido respuesta en " . $url . ""));
}

if (isset($archivo->error)) {
    session_destroy();
    die(error_page("Error", ".$archivo->error."));
}

if (isset($archivo->no_auth)) {
    session_unset();
    $_SESSION["mensajeSeguridad"] = "no tiene autorizacion, vuelve a loguearte";
    header("Location:index.php");
    exit();
}

$datosProfesores = $archivo->usuarios;


if (isset($_POST["profesor"])) {

    // obtener el horario del profesor
    $url = URLBASE . "/obtenerHorarioProfesor/" . $_POST["profesor"] . "";
    $datos["api_session"] = $_SESSION["api_session"];
    $respuesta = consumir_servicios_REST($url, "get", $datos);
    $archivo = json_decode($respuesta);

    if (!$archivo) {
        session_destroy();
        die(error_page("Error", "No se ha obtenido respuesta en " . $url . ""));
    }

    if (isset($archivo->error)) {
        session_destroy();
        die(error_page("Error", ".$archivo->error."));
    }

    if (isset($archivo->no_auth)) {
        session_unset();
        $_SESSION["mensajeSeguridad"] = $archivo->no_auth;
        header("location:index.php");
        exit();
    }

    if (isset($archivo->horario)) {
        $datosHorario = $archivo->horario;
    }
}

if (isset($_POST["editar"])) {
    
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
            width: 100%;
            text-align: center;
        }
    </style>
</head>

<body>
    <h2>Horario de los profesores</h2>
    <h3>Bienvenido
        <?php echo $datosUsuario->usuario . " - ";
        echo $datosUsuario->tipo ?>
    </h3>

    <form action="index.php" method="post">

        <select name="profesor" id="profesor">
            <?php
            foreach ($datosProfesores as $value) {
                if (isset($_POST["profesor"]) && $_POST["profesor"] == $value->id_usuario) {
                    $nombre = $value->nombre;
                    echo "<option selected value='$value->id_usuario'>" . $value->nombre . "</option>";
                } else {
                    echo "<option value='$value->id_usuario'>" . $value->nombre . "</option>";
                }
            }
            ?>
        </select>
        <button type="submit" name="verHorario">Ver Horario</button>
        <button type="submit" name="salir">Salir</button>
    </form>

    <?php
    if (isset($_POST["profesor"])) {

        echo "<p>Horario del profesor: " . $nombre . "</p>";

        // quedarme con el horario
        if (isset($datosHorario)) {
            foreach ($datosHorario as $value) {
                if (isset($horarioProfesor[$value->dia][$value->hora])) {
                    $horarioProfesor[$value->dia][$value->hora] .= " / " . $value->nombre;
                } else {
                    $horarioProfesor[$value->dia][$value->hora] = $value->nombre;
                }

            }
        }

        $horas[1] = "8:15-9:15";
        $horas[] = "9:15-10:15";
        $horas[] = "10:15-11:15";
        $horas[] = "11:15-11:45";
        $horas[] = "11:45-12:45";
        $horas[] = "12:45-13:45";
        $horas[] = "13:45-14:45";

        echo "<table border='solid 1px black' >";

        echo "<tr>";
        echo "<td></td>";
        echo "<td>Lunes</td>";
        echo "<td>Martes</td>";
        echo "<td>Miercoles</td>";
        echo "<td>Jueves</td>";
        echo "<td>Viernes</td>";
        echo "</tr>";

        for ($i = 1; $i < 8; $i++) {
            echo "<tr>";

            echo "<td>" . $horas[$i] . "</td>";

            for ($j = 1; $j < 6; $j++) {

                if ($i === 4) {

                    echo "<td colspan='5'>RECREO</td>";
                    break;

                } else {

                    if (isset($horarioProfesor[$j][$i])) {

                        echo "<td><form action='index.php' method='post'>" . $horarioProfesor[$j][$i] . "<button type='submit' name='editar' value='".$_POST["profesor"]."'>Editar</button> </form></td>";

                    } else {
                        echo "<td><form action='index.php' method='post'><button name='editar' value='".$_POST["profesor"]."'>Editar</button> </form></td>";
                    }

                }
            }

            echo "</tr>";
        }
        echo "</table>";

        if (isset($_POST["editar"])) {
            echo "<table>";

            echo "</table>";
        }

    }

    ?>
</body>

</html>