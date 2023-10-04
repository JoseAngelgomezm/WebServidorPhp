<?php
if (isset($_POST["enviar"])) {
    // cuando se sube una imagen se genera una variable llamada $_FILES
    // que contiene a su vez los arrays name, error, size, type, tmp_name                      // usado para saber si es un archivo de imagen o no, si no devuelve nada , no es una imagen
    $error_imagen_vacia = $_FILES["archivo"]["name"] == "" || $_FILES["archivo"]["error"] || !getimagesize($_FILES["archivo"]["tmp_name"]) || $_FILES["archivo"]["size"] > 500 * 1024;
}

if (isset($_POST["enviar"]) && !$error_imagen_vacia) {
    echo "pagina recogida de datos";
} else {
    ?>


    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>

    <body>
        <form action="index.php" method="post" enctype="multipart/form-data">
            <p>
                <label for="archivo"> Seleccione un archivo de image (Max 500KB): </label>
                <input type="file" name="archivo" id="archivo" accept="image/*" />
                <?php
                // si se ha ppulsado enviar y hay un error de imagen vacia
                if (isset($_POST["enviar"]) && $error_imagen_vacia) {
                    // si el nombre no está vacio
                    if ($_FILES["archivo"]["name"] != "") {
                        // avisar por los distintos errores
                        if ($_FILES["archivo"]["error"]) {
                            echo "<span class='error'> no se ha podido subir el archivo al servidor</span>";
                        } else if (!getimagesize($_FILES["archivo"]["tmp_name"])) {
                            echo "<span class='error'> no se ha seleccionado un archivo de imagen</span>";
                        } else {
                            echo "<span class='error'> El archivo supera los 500KB, selecciona uno mas pequeño</span>";
                        }
                    }

                }// si el nombre no esta vacio, no avisar de ningun error, el type file ya avisa de que no hay sleecion de archivo
                ?>
            </p>

            <p>
                <button type="submit" name="enviar">Enviar</button>
            </p>
        </form>

        <style>
            .error {
                color: red;
            }
        </style>
    </body>

    </html>
    <?php
}
?>