<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examen bd_colegio</title>
</head>

<body>
    <style>
        table, td{
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
                        $codAlumno = $datosAlumnos["cod_alu"];
                        echo "<option selected value='" . $datosAlumnos["cod_alu"] . "'>" . $datosAlumnos["nombre"] . "</option>";
                    } else {
                        echo "<option value='" . $datosAlumnos["cod_alu"] . "'>" . $datosAlumnos["nombre"] . "</option>";
                    }
                }
                ?>
            </select>
            <button type="submit" name="verNotas" value="<?php echo $codAlu ?>">Ver notas</button>
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
            $consulta = "SELECT asignaturas.denominacion, notas.nota FROM asignaturas, notas WHERE asignaturas.cod_asig=notas.cod_asig AND notas.cod_alu='" . $_POST["alumnos"] . "'";
            $resultadoSelect = mysqli_query($conexion, $consulta);
        } catch (Exception $e) {
            mysqli_close($conexion);
            die("No se ha podido hacer la consulta a la base de datos al ver las notas</body></html>");
        }

        // si no tenemos tuplas, mostrar mensaje de que no hay notas
        if (mysqli_num_rows($resultadoSelect) <= 0) {
            echo "<span>El alumno no contiene notas aún</span>";
            echo "<label for='asginatura'>Asignaturas que a ".$nombreAlumno." aun quedan por calificar</label>";
           ?>
            <form action="#" method="post">
                <select name="asignaturas" id="asignaturas">
                    <?php
                        
                    ?>
                </select>
            </form>
           <?php
        } else {
            
        

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
        while($notasAlumnos = mysqli_fetch_assoc($resultadoSelect)){
        echo "<tr>
            <td>".$notasAlumnos["denominacion"]."</td>
            <td>".$notasAlumnos["nota"]."</td>
            <td><form action='#' method='post'>
                <button type='submit' name='editarNota' value='".$_POST["verNotas"]."'> EditarAlumno </button>
                <button type='submit' name='borrarNota' value='".$_POST["verNotas"]."'> BorrarAlumno </button>
            </form></td>
        </tr>";
        }
        ?>
        </table>
        <?php
        }
        // en cualquier caso cerrar la conexion
        mysqli_close($conexion);

    }else if(isset($_POST["borrarNota"])){
        // si se ha pulsado borrar nota, tenemos que descalificar la nota y nos dara la opcion de volver a calificarla

    }
    ?>
</body>

</html>