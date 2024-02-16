<?php


if (isset($_POST["ver"])) {

    $url = URLBASE . "/obtenerUsuario/" . $_POST["ver"] . "";
    $datos["api_session"] = $_SESSION["api_session"];
    $respuesta = consumir_servicios_REST($url, "get", $datos);
    $archivo = json_decode($respuesta);

    if (!$archivo) {
        session_destroy();
        die(error_page("Error", "No se ha obtenido respuesta en " . $url . ""));
    }

    if (isset($archivo->error)) {
        session_unset();
        $_SESSION["mensajeError"] = $archivo->error . " en obtener horarios";
        header("location:index.php");
        exit();
    }

    $datosDeUsuario = $archivo->usuario;
    $hora = $_POST["hora"];

}


$url = URLBASE . "/obtenerIdGrupo/" . urlencode("GUARD") . "";
$datos["api_session"] = $_SESSION["api_session"];
$respuesta = consumir_servicios_REST($url, "get", $datos);
$archivo = json_decode($respuesta);

if (!$archivo) {
    session_destroy();
    die(error_page("Error", "No se ha obtenido respuesta en " . $url . ""));
}

if (isset($archivo->error)) {
    session_unset();
    $_SESSION["mensajeError"] = $archivo->error . " en obtener horarios";
    header("location:index.php");
    exit();
}

$idObtenida = $archivo->id_grupo->id_grupo;

$url = URLBASE . "/obtenerHorariosGuardia";
$datos["api_session"] = $_SESSION["api_session"];
$datos["dia"] = date("w");
$datos["id_grupo"] = $idObtenida;
$respuesta = consumir_servicios_REST($url, "get", $datos);
$archivo = json_decode($respuesta);

if (!$archivo) {
    session_destroy();
    die(error_page("Error", "No se ha obtenido respuesta en " . $url . ""));
}

if (isset($archivo->error)) {
    session_unset();
    $_SESSION["mensajeError"] = $archivo->error . " en obtener horarios";
    header("location:index.php");
    exit();
}

$datosHorarioGuardia = $archivo->horario;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h2>Bienvenido
        <?php echo $_SESSION["usuario"] ?>
    </h2>
    <form action="index.php" method="post">
        <button name="salir" type="submit">Salir</button>
    </form>

    <?php
    $indice = date("w");
    $dias = ["Lunes", "Martes", "Miercoles", "Jueves", "Viernes"];
    $horas = [" ", "8:15 - 9:15", "9:15 - 10:15", "10:15 - 11:15", "11:15 - 11:45", "11:45 - 12:45", "12:45 - 13:45", "13:15 - 14:15"];

    if(isset($_POST["ver"])){
        $idUsuario = $_POST["ver"];
    }else{
        $idUsuario ="";
    }

    echo "<h2>Hoy es " . $dias[$indice - 1] . "</h2>";
    echo "<table width='100%' border='solid 1px black'>";

    echo "<tr>";

    echo "<td>HORAS</td>";
    echo "<td>PROFESOR DE GUARDIA</td>";
    echo "<td>Informacion del profesor con id ".$idUsuario."</td>";

    echo "<tr>";
    for ($i = 1; $i < 8; $i++) {
        echo "<tr>";
        echo "<td>" . $horas[$i] . "</td>";

        echo "<td>";
        echo "<ol>";
        foreach ($datosHorarioGuardia as $value) {
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
        if (isset($_POST["ver"]) && $hora == $i) {
            
            echo "<p>Nombre: ".$datosDeUsuario->nombre."</p>";
            echo "<p>Apellido: ".$datosDeUsuario->usuario."</p>";
            echo "<p>Contraseña: ".$datosDeUsuario->clave."</p>";
            if(isset($datosDeUsuario->email)){
                echo "<p>Email: ".$datosDeUsuario->email."</p>";
            }else{
                echo "<p>Email: EMAIL NO DISPOINIBLE</p>";
            }
           
        }
        echo "</td>";

        echo "<tr>";
    }

    echo "</table>";
    ?>
</body>

</html>