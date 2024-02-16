<?php

$url = URLBASE . "/obtenerHorarios";
$datos["api_session"] = $_SESSION["api_session"];
$datos["id_usuario"] = $datosUsuario->id_usuario;
$respuesta = consumir_servicios_REST($url, "get", $datos);
$archivo = json_decode($respuesta);

if (!$archivo) {
    session_destroy();
    die(error_page("Error", "No se ha obtenido respuesta"));
}

if (isset($archivo->error)) {
    session_unset();
    $_SESSION["mensajeError"] = $archivo->error . " en obtener horarios";
    header("location:index.php");
    exit();
}

$datosHorarioProfesor = $archivo->horario;

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

    <h2>Su horario</h2>

    <?php
    echo "<h2>Horario del profesor " . $datosUsuario->nombre . "</h2>";

    foreach ($datosHorarioProfesor as $value) {
        if (isset($horarioDiaHora[$value->dia][$value->hora])) {
            $horarioDiaHora[$value->dia][$value->hora] .= "/".$value->nombre;
        } else {
            $horarioDiaHora[$value->dia][$value->hora] = $value->nombre;
        }

    }


    $dias = [" ", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes"];
    $horas = [" " ,"8:15 - 9:15", "9:15 - 10:15", "10:15 - 11:15", "11:15 - 11:45", "11:45 - 12:45", "12:45 - 13:45", "13:15 - 14:15"];

    echo "<table border='solid 1px black' width='100%'>";
    echo "<tr>";
    echo "<td> </td>";
    echo "<td>Lunes</td>";
    echo "<td>Martes</td>";
    echo "<td>Miercoles</td>";
    echo "<td>Jueves</td>";
    echo "<td>Viernes</td>";
    echo "</tr>";
    
    for ($i=1; $i < 8 ; $i++) { 
        echo "<tr>";
        echo "<td>".$horas[$i]."</td>";
        if($i === 4){
            echo "<td colspan='5'>RECREO</td>";
        }else{

            for($j=1; $j < 6; $j++){
                if(isset($horarioDiaHora[$j][$i])){
                    echo "<td>".$horarioDiaHora[$j][$i]."</td>";
                }else{
                    echo "<td></td>";
                }
            }
           
        }
        echo "</tr>";
    }
    echo "<table>";
    ?>

</body>

</html>