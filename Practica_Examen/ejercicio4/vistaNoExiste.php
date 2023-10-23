<?php
// control errores
if (isset($_POST["subir"])) {
    $errorFichero = $_FILES["fichero"]["name"] == "" || $_FILES["fichero"]["type"] != "text/plain" || $_FILES["fichero"]["size"] > 4000 || $_FILES["fichero"]["error"];
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
    <h1>Ejercicio 4</h1>
    <?php
    if (!file_exists("Ficheros/horario.txt")) {
        echo "<h2>No se encuentra el archivo Horario/horarios.txt</h2>";
    }
    ?>
    <form action="#" method="post" enctype="multipart/form-data">
        <p>
            <label for="file">Seleccione un archivo txt no superior a 4MB:</label>
            <input type="file" name="fichero" id="fichero"></input>
            <?php
            if (isset($_POST["subir"]) && $errorFichero) {
                if ($_FILES["fichero"]["type"] != "") {
                    if ($_FILES["fichero"]["type"] != "text/plain") {
                        echo "<span class='error'>el fichero no es de texto</span>";
                    } else if ($_FILES["fichero"]["size"] > 4000) {
                        echo "<span class='error'>el fichero supera el tamaño de 1MB</span>";
                    } else if ($_FILES["fichero"]["error"]) {
                        echo "<span class='error'>el fichero no ha podido subirse</span>";
                    }
                }
            }

            ?>
        </p>
        <p>
            <button name="subir" id="subir">Subir</button>
        </p>
    </form>
    <?php
    if (isset($_POST["subir"]) && !$errorFichero) {
        @$var = move_uploaded_file($_FILES["fichero"]["tmp_name"], "../Ficheros/horarios.txt");
        if ($var) {
            echo "<p>El fichero se ha subido con éxito<p>";
        } else {
            echo "<p><span class='error'>Error al mover el fichero</span></p>";
        }

        if(file_exists("../Ficheros/horarios.txt")) {
            header("location:index.php");
        }

    }

    ?>

</body>

</html>