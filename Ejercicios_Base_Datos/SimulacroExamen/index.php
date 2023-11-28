<?php
session_name("simulacroExamen");
session_start();

$user = "jose";
$bd = "bd_exam_colegio";
$passwd = "josefa";
$sitio = "localhost";

function errorPagina($mensaje)
{
    echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Document</title>
</head>
<body>
    <p>" . $mensaje . "</p>
</body>
</html>";
}
if (isset($_POST["borrarNota"])) {
    // si se ha pulsado borrar nota, tenemos que borrar la nota y nos dara la opcion de volver a calificarla
    // abrir una conexion para consultar la base de datos
    try {
        $conexion = mysqli_connect($sitio, $user, $passwd, $bd);
    } catch (Exception $e) {
        session_destroy();
        die(errorPagina("No se ha podido conectar al intentar borrar"));
    }

    // intentar la consulta delete para borrar un alumno por codigo de alumno y codigo de asignatura
    try {
        $consulta = "DELETE from notas WHERE cod_alu='".$_POST["alumnos"]."' AND cod_asig='".$_POST["borrarNota"]."'";
        $resultadoSelect = mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        mysqli_close($conexion);
        session_destroy();
        die(errorPagina("No se ha podido hacer la consulta de borrado"));
    }

    mysqli_close($conexion);

    $_SESSION["mensajeBorrado"] = "Nota borrada con exito";
    $_SESSION["cod_alu"] = $_POST["alumnos"];
    header("location:index.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examen bd_colegio</title>
</head>

<body>
    <style>
        table,
        td {
            border: solid 1px black;
        }
    </style>
    <h1>Notas de los alumnos</h1>
    <?php
    // abrir una conexion para consultar la base de datos
    try {
        $conexion = mysqli_connect($sitio, $user, $passwd, $bd);
    } catch (Exception $e) {
        die("No se ha podido conectar a la base de datos </body></html>");
    }

    // intentar la consulta select para obtener todos los alumnos
    try {
        $consulta = "SELECT * from alumnos";
        $resultadoSelect = mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        mysqli_close($conexion);
        die("<p>No se ha podido hacer la consulta a la base de datos</p></body></html>");
    }

    // si no hay datos
    if (mysqli_num_rows($resultadoSelect) <= 0) {
        mysqli_close($conexion);
        echo "En estos momentos no hay ningun alumno registrado en la BD";
    } else {
        // si los hay continuar mostrando un select
        ?>
        <form action='#' method='post'>
            <label for='alumnos'>Selecciona un alumno: </label>
            <select name="alumnos" id="alumnos">
                <?php
                while ($datosAlumnos = mysqli_fetch_assoc($resultadoSelect)) {
                    if ((isset($_POST["verNotas"]) && $_POST["alumnos"] == $datosAlumnos["cod_alu"]) || (isset($_SESSION["mensajeBorrado"]) && $_SESSION["cod_alu"] == $datosAlumnos["cod_alu"])) {
                        $nombreAlumno = $datosAlumnos["nombre"];
                        echo "<option selected value='" . $datosAlumnos["cod_alu"] . "'>" . $datosAlumnos["nombre"] . "</option>";
                    }else {
                        echo "<option value='" . $datosAlumnos["cod_alu"] . "'>" . $datosAlumnos["nombre"] . "</option>";
                    }
                }
                ?>
            </select>
            <button type="submit" name="verNotas" value="">Ver notas</button>
        </form>
        <?php
    }
    mysqli_close($conexion);

    // si se ha pulsado el boton de ver notas 
    if (isset($_POST["verNotas"]) || isset($_POST["borrarNota"]) || isset($_POST["editarNota"]) || isset($_SESSION["mensajeBorrado"])) {

        // el nombre de alumno del hidden que nos traemos al pulsar borrar o editar
        if (isset($_POST["borrarNota"]) || isset($_POST["editarNota"])) {
            $nombreAlumno = $_POST["nombreAlumno"];
        }

        // obtener el codigo de alumno del borrado o del select de alumonos
        if(isset($_SESSION["mensajeBorrado"])){
            $cod_alu = $_SESSION["cod_alu"];
        }else{
            $cod_alu = $_POST["alumnos"];
        }

        // abrir una conexion para consultar la base de datos
        try {
            $conexion = mysqli_connect($sitio, $user, $passwd, $bd);
        } catch (Exception $e) {
            die("No se ha podido conectar a la base de datos al ver las notas de un alumno</body></html>");
        }

        // intentar la consulta select para obtener todas las notas del alumno
        try {
            $consulta = "SELECT asignaturas.denominacion, notas.nota, notas.cod_asig FROM asignaturas, notas WHERE asignaturas.cod_asig=notas.cod_asig AND notas.cod_alu='" . $cod_alu . "'";
            $resultadoSelect = mysqli_query($conexion, $consulta);
        } catch (Exception $e) {
            mysqli_close($conexion);
            die("No se ha podido hacer la consulta a la base de datos al ver las notas</body></html>");
        }

        // montar la TABLA con los resultados
        echo "<h2>Notas del alumno " . $nombreAlumno . "</h2>";
        ?>
        <table>
            <tr>
                <td>Asignatura</td>
                <td>Nota</td>
                <td>Acción</td>
            </tr>
            <?php
            // mientras tengamos tuplas en las notas del alumno
            while ($notasAlumnos = mysqli_fetch_assoc($resultadoSelect)) {
                echo "<tr>
            <td>" . $notasAlumnos["denominacion"] . "</td>
            <td>" . $notasAlumnos["nota"] . "</td>
            <td><form action='#' method='post'>
                <button type='submit' name='editarNota' value='" . $notasAlumnos["cod_asig"] . "'> EditarNota </button>
                <button type='submit' name='borrarNota' value='" . $notasAlumnos["cod_asig"] . "'> BorrarNota </button>
                <input type='hidden' name='alumnos' value='" . $cod_alu . "'>
                <input type='hidden' name='nombreAlumno' value='" . $nombreAlumno . "'> 
            </form></td>
        </tr>"; // montar el hidden con el mismo nombre del select para que mantenga todos los campos al pulsar en editar nota o en borrar nota
            }
            ?>
        </table>

        <?php
        if(isset($_SESSION["mensajeBorrado"])){
            echo "<p class='mensajeError'>".$_SESSION["mensajeError"]."</p>";
        }


        // intentar la consulta select para obtener todas las notas del alumno en las que no ha sido calificado
        try {
            $consulta = "SELECT asignaturas.denominacion, asignaturas.cod_asig from asignaturas where cod_asig NOT IN
            (SELECT asignaturas.cod_asig
            FROM asignaturas
            INNER JOIN notas
            ON asignaturas.cod_asig=notas.cod_asig
            where notas.cod_alu='" . $cod_alu . "')";
            $resultadoSelect = mysqli_query($conexion, $consulta);
        } catch (Exception $e) {
            mysqli_close($conexion);
            die("<p>No se ha podido hacer la consulta a la base de datos al ver las notas no calificadas</p></body></html>");
        }

        // si tiene todas las notas, mostrar el mensaje de que ha sido calificado en todas las notas
        if (mysqli_num_rows($resultadoSelect) == 0) {
            echo "<span>El usuario " . $nombreAlumno . " tiene calificadas todas las notas</span>";
            // sino poner el formulario para calificar las notas que le falten
        } else {
            echo "<form method='post' action='#'>";
            echo "<label for='asignaturasSinCalificar'>Asignaturas que a " . $nombreAlumno . " aún le quedan por calificar: </label>";
            echo " <select name='asignaturasSinCalificar'>";

            while ($asignaturasSinCalificar = mysqli_fetch_assoc($resultadoSelect)) {
                echo "<option value='" . $asignaturasSinCalificar["cod_asig"] . "'>" . $asignaturasSinCalificar["denominacion"] . "</option>";
            }
            echo "</select>";

            echo "<button name='alumnos' value='" . $cod_alu . "' type='submit'>Calificar nota</button>";
            echo "</form>";
        }
    }
    ?>
</body>

</html>