<?php
if (isset($_POST["subir"])) {

    $errorFormulario = $_FILES["fichero"]["name"] == "" || $_FILES["fichero"]["error"] || $_FILES["fichero"]["type"] != "text/plain" || $_FILES["fichero"]["size"] > 2500 * 1024;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 4</title>
</head>

<body>
    <h1>Ejercicio 4 - Ficheros</h1>
    <form action="Ejercicio4.php" method="post" enctype="multipart/form-data">
        <p>
            <label for="fichero">Sube un fichero (Max.25MB)</label>
            <input type="file" name="fichero" id="fichero" accept=".txt"></input>
        </p>
        <?php
        if (isset($_POST["subir"]) && $errorFormulario) {
            if ($_FILES["fichero"]["name"] == "") {
                echo "<p class='error'>Fichero no seleccionado, porfavor selecciona uno</p>";
            } else if ($_FILES["fichero"]["type"] != "text/plain") {
                echo "<p class='error'>Tipo de fichero erroneo</p>";
            } else if ($_FILES["fichero"]["error"]) {
                echo "<p class='error'>Error de subida en el fichero</p>";
            } else {
                echo "<p class'error'>El fichero tiene mas de 2'5MB</p>";
            }

        }
        ?>
        <p>
            <button type="submit" name="subir" id="subir">Subir</button>
        </p>
    </form>

    <?php
    if (isset($_POST["subir"]) && !$errorFormulario) {
        // contar el contenido completo
        $contenidoDelFichero = file_get_contents($_FILES["fichero"]["tmp_name"]);
        echo "<h2>El fichero tiene un total de : " . str_word_count($contenidoDelFichero) . " palabras</h2>";

        // contear linea a linea
        $numeroPalabras = 0;
        @$fd = fopen($_FILES["fichero"]["tmp_name"], "r");
        if (!$fd) {
            die("No se ha podido abrir el archivo");
        }
        while ($lineas = fgets($fd)) {
            $numeroPalabras += str_word_count($lineas);
        }
        fclose($fd);
        echo "<h2>El fichero tiene un total de : ".$numeroPalabras." palabras</h2>";
    }

    ?>
</body>
<style>
    .error {
        color: red
    }
</style>

</html>