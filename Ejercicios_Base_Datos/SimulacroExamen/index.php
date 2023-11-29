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
        $consulta = "DELETE from notas WHERE cod_alu='" . $_POST["alumnos"] . "' AND cod_asig='" . $_POST["borrarNota"] . "'";
        $resultadoSelect = mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        mysqli_close($conexion);
        session_destroy();
        die(errorPagina("No se ha podido hacer la consulta de borrado"));
    }

    mysqli_close($conexion);

    $_SESSION["mensaje"] = "Nota borrada con exito";
    $_SESSION["cod_alu"] = $_POST["alumnos"];
    header("location:index.php");
    exit();

} else if (isset($_POST["confirmarEdicion"])) {
    // si se ha pulsado confirmar edicion, tenemos que ver el campo no sea erroneo
    $errorCampo = !is_numeric($_POST["notaModificar"]) || $_POST["notaModificar"] == "" || $_POST["notaModificar"] < 0 || $_POST["notaModificar"] > 10;

    // si no hay error de campo
    if (!$errorCampo) {
        // intentar la conexion con la bd,
        try {
            $conexion = mysqli_connect($sitio, $user, $passwd, $bd);
        } catch (Exception $e) {
            session_destroy();
            die(errorPagina("No se ha podido conectar al intentar modificar la nota"));
        }

        // necesitamos id_alu y id_asig
        try {
            $consulta = "UPDATE notas SET nota='" . $_POST["notaModificar"] . "' WHERE cod_alu='" . $_POST["alumnos"] . "' AND cod_asig='" . $_POST["confirmarEdicion"] . "'";
            mysqli_query($conexion, $consulta);
        } catch (Exception $e) {
            session_destroy();
            die(errorPagina("No se ha podido conectar al intentar modificar la nota"));
        }

        mysqli_close($conexion);
        $_SESSION["mensaje"] = "Se ha modificado una nota con exito";
        $_SESSION["cod_alu"] = $_POST["alumnos"];
        header("location:index.php");
        exit();
    }
   


} else if (isset($_POST["calificar"])) {
    // si se ha pulsado calificar
    // intentar la conexion con la bd,
    try {
        $conexion = mysqli_connect($sitio, $user, $passwd, $bd);
    } catch (Exception $e) {
        session_destroy();
        die(errorPagina("No se ha podido conectar al intentar calificar la nota"));
    }

    // necesitamos id_alu y id_asig
    try {
        $consulta = "UPDATE notas SET nota='" . $_POST["notaModificar"] . "' WHERE cod_alu='" . $_POST["alumnos"] . "' AND cod_asig='" . $_POST["confirmarEdicion"] . "'";
        mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        session_destroy();
        die(errorPagina("No se ha podido conectar al intentar modificar la nota"));
    }

    
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
                    if (
                        (isset($_POST["alumnos"]) && $_POST["alumnos"] == $datosAlumnos["cod_alu"]) ||
                        (isset($_SESSION["mensaje"]) && $_SESSION["cod_alu"] == $datosAlumnos["cod_alu"])
                    ) {
                        $nombreAlumno = $datosAlumnos["nombre"];
                        echo "<option selected value='" . $datosAlumnos["cod_alu"] . "'>" . $datosAlumnos["nombre"] . "</option>";

                    } else {
                        echo "<option value='" . $datosAlumnos["cod_alu"] . "'>" . $datosAlumnos["nombre"] . "</option>";
                    }
                    // siempre obtener el nombre del alumno para mostrarlo al pulsar cualquier boton
            
                }
                ?>
            </select>
            <button type="submit" name="verNotas" value="">Ver notas</button>
        </form>
        <?php
    }
    mysqli_close($conexion);

    // si se ha pulsado el boton de ver notas 
    if (isset($_POST["alumnos"]) || isset($_SESSION["mensaje"])) {

        // obtener el codigo de alumno del borrado o del select de alumonos
        if (isset($_SESSION["mensaje"])) {
            $cod_alu = $_SESSION["cod_alu"];
        } else {
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
                echo "<form action='#' method='post'>";
                echo "<tr>";

                echo "<td>" . $notasAlumnos["denominacion"] . "</td>";

                echo "<td>";
                if ((isset($_POST["editarNota"]) && $_POST["editarNota"] == $notasAlumnos["cod_asig"]) || (isset($_POST["confirmarEdicion"]) && $_POST["confirmarEdicion"]  == $notasAlumnos["cod_asig"])) {
                    if(isset($_POST["editarNota"])){
                        $nota =  $notasAlumnos["nota"];
                    }else{
                        $nota = $_POST["notaModificar"];
                    }
                    echo "<input type='text' name='notaModificar' value='".$nota."'></input>";
                    if(isset($_POST["confirmarEdicion"])){
                        echo "<br><span>nota no valida</span>";
                    }
                } else {
                    echo $notasAlumnos["nota"];
                }
                echo "</td>";

                echo "<td>";
                if (isset($_POST["editarNota"]) && $_POST["editarNota"] == $notasAlumnos["cod_asig"]) {
                    echo "<button type='submit' name='confirmarEdicion' value='" . $notasAlumnos["cod_asig"] . "'> ConfirmarEdicion </button>";
                } else {
                    echo "<button type='submit' name='editarNota' value='" . $notasAlumnos["cod_asig"] . "'> EditarNota </button>";
                }

                if (isset($_POST["editarNota"]) && $_POST["editarNota"] == $notasAlumnos["cod_asig"]) {
                    echo "    <button type='submit'> Atras </button>";
                } else {
                    echo "    <button type='submit' name='borrarNota' value='" . $notasAlumnos["cod_asig"] . "'> BorrarNota </button>";
                }
                echo "</td>";

                // montar el hidden con el mismo nombre del select para que mantenga todos los campos al pulsar en editar nota o en borrar nota
                echo "<input type='hidden' name='alumnos' value='" . $cod_alu . "'>";

                echo "</tr>";
                echo " </form>";
            }
            ?>
        </table>

        <?php
        if (isset($_SESSION["mensaje"])) {
            echo "<p class='mensajeError'>" . $_SESSION["mensaje"] . "</p>";
            session_destroy();
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


        } else { // sino poner el formulario para calificar las notas que le falten
    
            echo "<form method='post' action='#'>";

            echo "<label for='asignaturasSinCalificar'>Asignaturas que a " . $nombreAlumno . " aún le quedan por calificar: </label>";
            echo "<select name='asignaturasSinCalificar'>";

            while ($asignaturasSinCalificar = mysqli_fetch_assoc($resultadoSelect)) {
                echo "<option value='" . $asignaturasSinCalificar["cod_asig"] . "'>" . $asignaturasSinCalificar["denominacion"] . "</option>";
            }
            echo "</select>";

            echo "<button name='calificar' value='" . $cod_alu . "' type='submit'>Calificar nota</button>";

            echo "</form>";
        }
    }
    ?>
</body>

</html>