<?php
if (isset($_SESSION["usuario"])) {

    if ($_SESSION["tipo"] === "admin") {

        if (isset($_POST["borrar"])) {
            $_SESSION["mensajeAccion"] = "Se ha borrado el libro " . $_POST["borrar"] . "";
        }

        if (isset($_POST["editar"])) {
            $_SESSION["mensajeAccion"] = "Se ha editado el libro " . $_POST["editar"] . "";
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
            <form action="#" method="post">

                <button type="submit" name="salir">Salir</button>
            </form>

            <h2>Listado de libros</h2>
            <?php

            if (isset($_SESSION["mensajeAccion"])) {
                echo "<p>" . $_SESSION["mensajeAccion"] . "</p>";
                session_unset();
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
                    echo "<td><form method='post' action='#'><button type='submit' name='borrar' value='" . $value->referencia . "'>Borrar</button><button type='submit' name='editar' value='" . $value->referencia . "'>Editar</button></form></td>";
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
    }

} else {
    header("location:../index.php");
}

?>