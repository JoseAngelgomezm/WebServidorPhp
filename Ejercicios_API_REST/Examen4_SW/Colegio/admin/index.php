<?php
session_name("Examen4_SW_23_24");
session_start();
require("../src/funciones_ctes.php");

if (isset($_SESSION["usuario"])) {

    // obtener los alumnos
    $url = DIR_SERV . "/alumnos";
    $datos["api_session"] = $_SESSION["api_session"];
    $respuesta = consumir_servicios_REST($url, "get", $datos);

    $archivo = json_decode($respuesta);

    if (!$archivo) {
        session_destroy();
        die(error_page("Examen4 DWESE Curso 23-24", "<h1>Notas de los alumnos</h1><p>Error consumiendo el servicio: " . $url . "</p>"));
    }

    if (isset($archivo->error)) {
        session_destroy();
        die(error_page("Examen4 DWESE Curso 23-24", "<h1>Notas de los alumnos</h1><p>" . $archivo->error . "</p>"));
    }

    if (isset($archivo->mensaje)) {
        $mensaje = "En estos momentos no tiene asignaturas calificadas";
    }

    $datosAlumnos = $archivo->alumnos;

    if (isset($_POST["verNotas"])) {
        // obtener las notas por calificar
        $url = DIR_SERV . "/notasAlumno/" . $_POST["alumno"] . "";
        $datos["api_session"] = $_SESSION["api_session"];
        $respuesta = consumir_servicios_REST($url, "get", $datos);

        $archivo = json_decode($respuesta);

        if (!$archivo) {
            session_destroy();
            die(error_page("Examen4 DWESE Curso 23-24", "<h1>Notas de los alumnos</h1><p>Error consumiendo el servicio: " . $url . "</p>"));
        }

        if (isset($archivo->error)) {
            session_destroy();
            die(error_page("Examen4 DWESE Curso 23-24", "<h1>Notas de los alumnos</h1><p>" . $archivo->error . "</p>"));
        }

        if (isset($archivo->mensaje)) {
            $mensaje = "En estos momentos no tiene asignaturas calificadas";
        }

        if(isset($archivo->notas)){
            $notasAlumnosCalificadas = $archivo->notas;
        }else{
            $notasAlumnosCalificadas = null;
        }
        
    }


    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>

    <body>
        <h2>Notas de los alumnos</h2>
        <p>Bienvenido
            <?php echo $_SESSION["usuario"] ?>
        <form action="../index.php" method="post"><button type="submit" name="btnSalir">Salir</button></form>
        </p>

        <form action="#" method="post">

            <select name="alumno" id="alumno">
                <?php
                foreach ($datosAlumnos as $value) {
                    echo "<option value='" . $value->cod_usu . "'>";
                    echo $value->nombre;
                    echo "</option>";
                }
                ?>
            </select>
            <button type="submit" name="verNotas">Ver notas</button>
        </form>

        <?php
        if (isset($_POST["verNotas"])) {

            if ($notasAlumnosCalificadas !== null) {
                echo " <table border='solid 1 px black'>";

                echo "<tr>";
                echo "<td>Asignatura</td>";
                echo "<td>Nota</td>";
                echo "</tr>";

                foreach ($notasAlumnosCalificadas as $value) {
                    echo "<tr>";
                    echo "<td>" . $value->denominacion . "</td>";
                    echo "<td>" . $value->nota . "</td>";
                    echo "<td><form action='#' method='post'></td>";
                    echo "</tr>";
                }

                echo "</table>";
            } else {
                echo "<p>No tiene notas aun</p>";
            }


        }
        ?>

    </body>



    </html>

    <?php

} else {
    header("Location:../index.php");
    exit;
}

?>