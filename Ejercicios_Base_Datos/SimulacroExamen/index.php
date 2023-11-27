<?php

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
    $user = "jose";
    $bd = "bd_exam_colegio";
    $passwd = "josefa";
    $sitio = "localhost";
   
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
        die("No se ha podido hacer la consulta a la base de datos </body></html>");
    }

    // si no hay datos
    if (mysqli_num_rows($resultadoSelect) <= 0) {
        echo "En estos momentos no hay ningun alumno registrado en la BD";
    } else {
        // si los hay continuar mostrando un select
        ?>
        <form action='#' method='post'>
            <label for='alumnos'>Selecciona un alumno: </label>
            <select name="alumnos" id="alumnos">
                <?php
                while ($datosAlumnos = mysqli_fetch_assoc($resultadoSelect)) {
                    if (isset($_POST["verNotas"]) && $_POST["alumnos"] == $datosAlumnos["cod_alu"]) {
                        $nombreAlumno = $datosAlumnos["nombre"];
                        echo "<option selected value='" . $datosAlumnos["cod_alu"] . "'>" . $datosAlumnos["nombre"] . "</option>";
                    } else {
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

    // si se ha pulsado el boton de ver notas de un alumno
    if (isset($_POST["verNotas"])) {


        // abrir una conexion para consultar la base de datos
        try {
            $conexion = mysqli_connect($sitio, $user, $passwd, $bd);
        } catch (Exception $e) {
            die("No se ha podido conectar a la base de datos al ver las notas de un alumno</body></html>");
        }

        // intentar la consulta select para obtener todas las notas del alumno
        try {
            $consulta = "SELECT asignaturas.denominacion, notas.nota, notas.cod_alu, notas.cod_asig FROM asignaturas, notas WHERE asignaturas.cod_asig=notas.cod_asig AND notas.cod_alu='" . $_POST["alumnos"] . "'";
            $resultadoSelect = mysqli_query($conexion, $consulta);
        } catch (Exception $e) {
            mysqli_close($conexion);
            die("No se ha podido hacer la consulta a la base de datos al ver las notas</body></html>");
        }

        // montar la tabla con los resultados
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
                <button type='submit' name='editarNota' value='" . $_POST["verNotas"] . "'> EditarNota </button>
                <button type='submit' name='borrarNota' value='" . $_POST["verNotas"] . "'> BorrarNota </button>
                <input type='hidden' name='codigo_asignatura' value='" . $notasAlumnos["cod_asig"] . "'>
            </form></td>
        </tr>";
            }
            ?>
        </table>

        <?php
        // intentar la consulta select para obtener todas las notas del alumno en las que no ha sido calificado
        try {
            $consulta = "SELECT asignaturas.denominacion, cod_asig from asignaturas where cod_asig NOT IN
            (SELECT asignaturas.cod_asig
            FROM asignaturas
            INNER JOIN notas
            ON asignaturas.cod_asig=notas.cod_asig
            where notas.cod_alu='" . $_POST["alumnos"] . "')";
            $resultadoSelect = mysqli_query($conexion, $consulta);
        } catch (Exception $e) {
            mysqli_close($conexion);
            die("No se ha podido hacer la consulta a la base de datos al ver las notas no calificadas</body></html>");
        }

        // si tiene todas las notas, mostrar el mensaje de que ha sido calificado en todas las notas
        if (mysqli_num_rows($resultadoSelect) <= 0) {
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


            echo "<button name='calificar' value='" . $_POST['alumnos'] . "' type='submit'>Calificar nota</button>";
            echo "</form>";
        }



    } else if (isset($_POST["borrarNota"])) {
        // si se ha pulsado borrar nota, tenemos que borrar la nota y nos dara la opcion de volver a calificarla
        // abrir una conexion para consultar la base de datos
        try {
            $conexion = mysqli_connect($sitio, $user, $passwd, $bd);
        } catch (Exception $e) {
            die("No se ha podido conectar a la base de datos al borrar la nota del alumno</body></html>");
        }

       mysqli_close($conexion);

    }

    ?>
</body>

</html>