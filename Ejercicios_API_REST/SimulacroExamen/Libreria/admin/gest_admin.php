<?php
session_name("examenlibreriaSimulacro22-23");
session_start();
define("URLBASE", "http://localhost/Proyectos/WebServidorPhp/Ejercicios_API_REST/SimulacroExamen/servicios_rest");


function consumir_servicios_REST($url, $metodo, $datos = null)
{
    $llamada = curl_init();
    curl_setopt($llamada, CURLOPT_URL, $url);
    curl_setopt($llamada, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($llamada, CURLOPT_CUSTOMREQUEST, $metodo);
    if (isset($datos))
        curl_setopt($llamada, CURLOPT_POSTFIELDS, http_build_query($datos));
    $respuesta = curl_exec($llamada);
    curl_close($llamada);
    return $respuesta;
}

function error_page($title, $body)
{
    $html = '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="viewport" content="width=device-width, initial-scale=1.0">';
    $html .= '<title>' . $title . '</title></head>';
    $html .= '<body>' . $body . '</html>';
    return $html;
}



if (isset($_SESSION["usuario"])) {

    $salto = "../index.php";
    require("../vistas/vista_seguridad.php");

    if ($_SESSION["tipo"] === "admin") {

        if (isset($_POST["borrar"])) {
            $_SESSION["mensajeAccion"] = "Se ha borrado el libro " . $_POST["borrar"] . "";
            header("Location:gest_admin.php");
            exit();
        }

        if (isset($_POST["editar"])) {
            $_SESSION["mensajeAccion"] = "Se ha editado el libro " . $_POST["editar"] . "";
            header("Location:gest_admin.php");
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
            <h2>Bienvenido
                <?php echo $datosUsuario->lector . " tipo: " . $_SESSION["tipo"] . "" ?>
            </h2>
            <form action="../index.php" method="post">

                <button type="submit" name="salir">Salir</button>
            </form>

            <h2>Listado de libros</h2>
            <?php

            if (isset($_SESSION["mensajeAccion"])) {
                echo "<p>" . $_SESSION["mensajeAccion"] . "</p>";
                unset($_SESSION["mensajeAccion"]);
            }

            $url = URLBASE . "/obtenerLibros";
            $respuesta = consumir_servicios_REST($url, "get");
            $archivo = json_decode($respuesta);

            if (!$archivo) {
                session_destroy();
                die(error_page("Error obtencion", "No se ha obtenido respuesta"));
            }

            if (isset($archivo->error)) {
                session_destroy();
                die(error_page("Error obtencion", $archivo->error));
            }

            if (isset($archivo->mensaje)) {
                $_SESSION["mensajeLibros"] = $archivo->mensaje;
            }

            if (isset($archivo->libros)) {
                echo "<table border='border solid 1px'>";
                echo "<tr>";
                echo "<td>Ref</td>";
                echo "<td>Titulo</td>";
                echo "<td>Acción</td>";
                echo "</tr>";
                foreach ($archivo->libros as $value) {
                    echo "<tr>";
                    echo "<td>" . $value->referencia . "</td>";
                    echo "<td>" . $value->titulo . "</td>";
                    echo "<td><form method='post' action='gest_admin.php'><button type='submit' name='borrar' value='" . $value->referencia . "'>Borrar</button>
                    <button type='submit' name='editar' value='" . $value->referencia . "'>Editar</button></form></td>";
                    echo "</tr>";
                }
                echo "<table>";
            }

            ?>

            <h2>Insertar libro</h2>
            

        </body>

        </html>


        <?php

    } else {
        header("location:../index.php");
        exit();
    }

} else {
    header("location:../index.php");
    exit();
}

?>