<?php

$url = URLBASE . "/usuariosGuardia/" . date("w") . "/1";
$datos["api_session"] = $_SESSION["api_session"];
$respuesta = consumir_servicios_REST($url, "get", $datos);
$archivo = json_decode($respuesta);
if (!$archivo) {
    $datos["api_session"] = $_SESSION["api_session"];
    consumir_servicios_REST("/salir", "post", $datos);
    session_destroy();
    die(error_page("Error", "No se ha obtenido respuesta en " . $url . ""));
}

if (isset($archivo->error)) {
    $datos["api_session"] = $_SESSION["api_session"];
    consumir_servicios_REST("/salir", "post", $datos);
    session_destroy();
    die(error_page("Error", $archivo->error));
}

$datos_hora["1"] = $archivo->usuarios;


$url = URLBASE . "/usuariosGuardia/" . date("w") . "/2";
$datos["api_session"] = $_SESSION["api_session"];
$respuesta = consumir_servicios_REST($url, "get", $datos);
$archivo = json_decode($respuesta);
if (!$archivo) {
    $datos["api_session"] = $_SESSION["api_session"];
    consumir_servicios_REST("/salir", "post", $datos);
    session_destroy();
    die(error_page("Error", "No se ha obtenido respuesta en " . $url . ""));
}

if (isset($archivo->error)) {
    $datos["api_session"] = $_SESSION["api_session"];
    consumir_servicios_REST("/salir", "post", $datos);
    session_destroy();
    die(error_page("Error", $archivo->error));
}

$datos_hora["2"] = $archivo->usuarios;

$url = URLBASE . "/usuariosGuardia/" . date("w") . "/3";
$datos["api_session"] = $_SESSION["api_session"];
$respuesta = consumir_servicios_REST($url, "get", $datos);
$archivo = json_decode($respuesta);
if (!$archivo) {
    $datos["api_session"] = $_SESSION["api_session"];
    consumir_servicios_REST("/salir", "post", $datos);
    session_destroy();
    die(error_page("Error", "No se ha obtenido respuesta en " . $url . ""));
}

if (isset($archivo->error)) {
    $datos["api_session"] = $_SESSION["api_session"];
    consumir_servicios_REST("/salir", "post", $datos);
    session_destroy();
    die(error_page("Error", $archivo->error));
}

$datos_hora["3"] = $archivo->usuarios;



$url = URLBASE . "/usuariosGuardia/" . date("w") . "/5";
$datos["api_session"] = $_SESSION["api_session"];
$respuesta = consumir_servicios_REST($url, "get", $datos);
$archivo = json_decode($respuesta);
if (!$archivo) {
    $datos["api_session"] = $_SESSION["api_session"];
    consumir_servicios_REST("/salir", "post", $datos);
    session_destroy();
    die(error_page("Error", "No se ha obtenido respuesta en " . $url . ""));
}

if (isset($archivo->error)) {
    $datos["api_session"] = $_SESSION["api_session"];
    consumir_servicios_REST("/salir", "post", $datos);
    session_destroy();
    die(error_page("Error", $archivo->error));
}

$datos_hora["5"] = $archivo->usuarios;

$url = URLBASE . "/usuariosGuardia/" . date("w") . "/6";
$datos["api_session"] = $_SESSION["api_session"];
$respuesta = consumir_servicios_REST($url, "get", $datos);
$archivo = json_decode($respuesta);
if (!$archivo) {
    $datos["api_session"] = $_SESSION["api_session"];
    consumir_servicios_REST("/salir", "post", $datos);
    session_destroy();
    die(error_page("Error", "No se ha obtenido respuesta en " . $url . ""));
}

if (isset($archivo->error)) {
    $datos["api_session"] = $_SESSION["api_session"];
    consumir_servicios_REST("/salir", "post", $datos);
    session_destroy();
    die(error_page("Error", $archivo->error));
}

$datos_hora["6"] = $archivo->usuarios;

$url = URLBASE . "/usuariosGuardia/" . date("w") . "/7";
$datos["api_session"] = $_SESSION["api_session"];
$respuesta = consumir_servicios_REST($url, "get", $datos);
$archivo = json_decode($respuesta);
if (!$archivo) {
    $datos["api_session"] = $_SESSION["api_session"];
    consumir_servicios_REST("/salir", "post", $datos);
    session_destroy();
    die(error_page("Error", "No se ha obtenido respuesta en " . $url . ""));
}

if (isset($archivo->error)) {
    $datos["api_session"] = $_SESSION["api_session"];
    consumir_servicios_REST("/salir", "post", $datos);
    session_destroy();
    die(error_page("Error", $archivo->error));
}

$datos_hora["7"] = $archivo->usuarios;

if (isset($_POST["ver"])) {
    $url = URLBASE . "/usuario/" . $_POST["id_consultar"] . "";
    $datos["api_session"] = $_SESSION["api_session"];
    $respuesta = consumir_servicios_REST($url, "get", $datos);
    $archivo = json_decode($respuesta);
    if (!$archivo) {
        $datos["api_session"] = $_SESSION["api_session"];
        consumir_servicios_REST("/salir", "post", $datos);
        session_destroy();
        die(error_page("Error", "No se ha obtenido respuesta en " . $url . ""));
    }

    if (isset($archivo->error)) {
        $datos["api_session"] = $_SESSION["api_session"];
        consumir_servicios_REST("/salir", "post", $datos);
        session_destroy();
        die(error_page("Error", $archivo->error));
    }

    if (isset($archivo->mensaje)) {
        $datos_usuario_consultar = $archivo->mensaje;
    }

    $datos_usuario_consultar = $archivo->usuario;
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
            width: 100%;
        }

        table td {
            border: solid 1px black;
            border-collapse: collapse;
        }
    </style>
</head>

<body>
    <h2>Gestion de Guardias</h2>
    <?php echo "<h3>Bienvenido " . $datos_usuario_logueado->nombre . "<form action='index.php' method='post'><button name='salir' type='submit'>Salir</button></form></h3>"; ?>
    <?php
    $id_profesor = "";
    $dias = ["", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes"];
    $horas = ["", "8:15 - 9:15", "9:15 - 10:15", "10:15 - 11:15", "", "11:45 - 12:45", "12:45 - 13:45", "13:45 - 14:45"];
    echo "<h3>Hoy es " . $dias[date("w")] . "</h3>";

    echo "<table>";

    echo "<tr>";
    echo "<td>Hora</td>";
    echo "<td>Profesor de Guardia</td>";
    echo "<td>Informacion del profesor con id:" . $id_profesor . "</td>";
    echo "</tr>";

    for ($i = 1; $i <= 7; $i++) {
        if ($i == 4) {
            continue;
        }
        echo "<tr>";

        echo "<td>";
        echo $horas[$i];
        echo "</td>";

        echo "<td>";
        echo "<ol>";
        foreach ($datos_hora[$i] as $value) {
            echo "<li>";
            echo "<form action='index.php' method='post'>";
            echo "<button type='submit' name='ver'>" . $value->nombre . "</button>";
            echo "<input hidden type='text' name='id_consultar' value='" . $value->id_usuario . "'></input>";
            echo "</form>";
            echo "</li>";
        }
        echo "</ol>";
        echo "</td>";

        if (isset($_POST["ver"]) && $i == 1) {
            echo "<td>";
            echo "<p><strong>Nombre:</strong> " . $datos_usuario_consultar->nombre . "</p>";
            echo "<p><strong>Usuario: </strong>" . $datos_usuario_consultar->usuario . "</p>";
            echo "<p><strong>Contraseña:</strong></p>";
            if ($datos_usuario_consultar->email != "") {
                echo "<p><strong>Email:</strong> " . $datos_usuario_consultar->email . "</p>";
            } else {
                echo "<p><strong>Email:</strong> no disponible</p>";
            }

            echo "</td>";
        } else {
            echo "<td>";
            echo "</td>";
        }

        echo "</tr>";
    }
    echo "</table>";
    ?>
</body>

</html>