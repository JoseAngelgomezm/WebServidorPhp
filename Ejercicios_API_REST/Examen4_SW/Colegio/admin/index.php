<?php
session_name("Examen4_SW_23_24");
session_start();
require("../src/funciones_ctes.php");

if (isset($_SESSION["usuario"])) {


    require("../src/seguridad.php");

    if ($datos_usuario_log->tipo == "tutor") {

        // cuando pierdo el $_POST["alumno"]
        if (isset($_SESSION["alumno"])) {
            $_POST["alumno"] = $_SESSION["alumno"];
        }


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
            $mensaje = "En estos momentos no tiene alumnos en la bd";
        }

        $datosAlumnos = $archivo->alumnos;

        if (isset($_POST["alumno"])) {


            // obtener las notas que tiene calificadas
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

            if (isset($archivo->notas)) {
                $notasAlumnosCalificadas = $archivo->notas;
            } else {
                $notasAlumnosCalificadas = null;
            }

            // obtener las notas que le quedan por calificar
            // obtener las notas que tiene calificadas
            $url = DIR_SERV . "/NotasNoEvalAlumno/" . $_POST["alumno"] . "";
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


            if (isset($archivo->notas)) {
                $datosNotasNoEvaluadas = $archivo->notas;
            } else {
                $mensaje_no_asig = $archivo->no_asignaturas;
            }


        }

        if (isset($_POST["borrar"])) {
            $url = DIR_SERV . "/quitarNota/" . $_POST["alumno"] . "";
            $datos["api_session"] = $_SESSION["api_session"];
            $datos["cod_asig"] = $_POST["borrar"];
            $respuesta = consumir_servicios_REST($url, "DELETE", $datos);
            $archivo = json_decode($respuesta);

            if (!$archivo) {
                session_destroy();
                die(error_page("Examen4 DWESE Curso 23-24", "<h1>Notas de los alumnos</h1><p>Error consumiendo el servicio: " . $url . "</p>"));
            }

            if (isset($archivo->error)) {
                $_SESSION["mensajeError"] = $archivo->error;
                header("location:index.php");
                exit();
            }

            header("location:index.php");
            exit();
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
                        if (isset($_POST["alumno"]) && $value->cod_usu == $_POST["alumno"]) {
                            echo "<option selected value='" . $value->cod_usu . "'>";
                            echo $value->nombre;
                            echo "</option>";
                            $nombreAlumno = $value->nombre;
                        } else {
                            echo "<option value='" . $value->cod_usu . "'>";
                            echo $value->nombre;
                            echo "</option>";
                        }

                    }
                    ?>
                </select>
                <button type="submit" name="verNotas">Ver notas</button>
            </form>


            <?php
            if (isset($_POST["alumno"])) {

                echo "<h2>Notas del alumno $nombreAlumno</h2>";

                if ($notasAlumnosCalificadas !== null) {
                    echo " <table border='solid 1 px black'>";
                    echo "<tr>";
                    echo "<td>Asignatura</td>";
                    echo "<td>Nota</td>";
                    echo "<td>Accion</td>";
                    echo "</tr>";

                    foreach ($notasAlumnosCalificadas as $value) {
                        echo "<tr>";
                        echo "<td>" . $value->denominacion . "</td>";
                        echo "<td>" . $value->nota . "</td>";

                        echo "<td>";
                        echo "<form action='#' method='post'>";
                        echo "<input name='alumno' hidden value='" . $value->cod_usu . "'></input>";
                        echo "<button name='borrar' value='" . $value->cod_asig . "' type='submit'>Borrar</button>";
                        echo "<button name='editar' value='" . $value->cod_asig . "' type='submit'>Editar</button>";
                        echo "</form>";
                        echo "</td>";

                        echo "</tr>";
                    }

                    echo "</table>";
                } else {
                    echo "<p>No tiene notas aun</p>";
                }


                if (isset($_SESSION["mensajeAccion"])) {
                    echo "<p>" . $_SESSION["mensajeAccion"] . "</p>";
                    unset($_SESSION["mensajeAccion"]);
                }


                if (isset($mensaje_no_asig)) {
                    echo "<span>" . $mensaje_no_asig . "</span>";
                } else {
                    echo "<form action='#' method='post'>";
                    echo "<label>Asignaturas que a " . $nombreAlumno . " aun le quedan por calificar: </label>";
                    echo "<select name='asignaturaCalificar'>";
                    foreach ($datosNotasNoEvaluadas as $value) {
                        echo "<option value='" . $value->cod_asig . "'>";
                        echo $value->denominacion;
                        echo "</option>";
                    }
                    echo "</select>";
                }
                if (!isset($mensaje_no_asig)) {
                    echo "<button name='calificar' value='" . $_POST["alumno"] . "'>Calificar</button>";
                }
                echo "</form>";

            }
            ?>

        </body>

        </html>

        <?php
    } else {
        session_destroy();
        header("Location:../index.php");
        exit;
    }

} else {
    session_destroy();
    header("Location:../index.php");
    exit;
}

?>