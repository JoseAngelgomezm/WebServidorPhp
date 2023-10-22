<?php
// control errores
if (isset($_POST["subir"])) {
    $errorFichero = $_FILES["fichero"]["name"] == "" || $_FILES["fichero"]["type"] != "text/plain" || $_FILES["fichero"]["size"] > 1000 || $_FILES["fichero"]["error"];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <form method="post" action="#" enctype="multipart/form-data">
        <p>
            <label for="fichero">selecciona un fichero</label>
            <input type="file" name="fichero" id="fichero"></input>
            <?php
            if ($errorFichero) {
                if ($_FILES["fichero"]["type"] != "") {
                    if ($_FILES["fichero"]["type"] != "text/plain") {
                        echo "<span class='error'>el fichero no es de texto</span>";
                    } else if ($_FILES["fichero"]["size"] > 1000) {
                        echo "<span class='error'>el fichero supera el tamaño de 1MB</span>";
                    } else if ($_FILES["fichero"]["error"]) {
                        echo "<span class='error'>el fichero no ha podido subirse</span>";
                    }
                }
            }

            ?>
        </p>
        <button type="subir" id="subir" name="subir">Subir</button>
    </form>


    <?php
    if (!$errorFichero) {
        @$var = move_uploaded_file($_FILES["fichero"]["tmp_name"], "Ficheros/fichero.txt");
        if ($var) {
            echo "<p>El fichero se ha subido con éxito<p>";
        } else {
            echo "<p><span class='error'>Error al mover el fichero</span></p>";
        }

    }

    ?>
</head>

<body>

</body>
<style>
    .error {
        color: red;
    }
</style>

</html>