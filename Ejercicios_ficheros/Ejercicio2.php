<?php
// controle errores
if (isset($_POST["guardar"])) {

    $errorNumeroVacio = $_POST["numero"] == "";
    $errorRangoNumero = !is_numeric($_POST["numero"]) || $_POST["numero"] < 0 || $_POST["numero"] > 10;

    $errorFormulario = $errorNumeroVacio || $errorRangoNumero;
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
    <h1>Ejercicio2 - Ficheros</h1>
    <form action="Ejercicio2.php" method="post">
        <label for="numero">Introduce un numero entre 1 y 10</label>
        <input tpye="text" name="numero" id="numero" value="<?php if (isset($_POST["guardar"]))
            echo $_POST["numero"] ?>"></input>
            <?php
        if (isset($_POST["guardar"]) && $errorNumeroVacio) {
            echo "<p>Numero vacio, introduce uno</p>";
        } else if (isset($_POST["guardar"]) && $errorRangoNumero) {
            echo "<p>Introduce un numero del 1 al 10</p>";
        }
        ?>
        <br>
        <button type="submit" name="guardar" id="guardar">Guardar</button>
    </form>

    <?php
    // mostrar el contenido del fichero
    // preguntar si existe el fichero
    if (file_exists("ficheros/Tablas" . $_POST["numero"] . ".txt")) {
        // intentar abrir en modo lectura controlando errores
        @$fd = fopen("ficheros/Tablas" . $_POST["numero"] . ".txt", "r");
        if (!$fd) {
            die("error al abrir el archivo");
        } else {
            // mientras el fichero tenga lineas, ir sacando cada una en un p
            while ($linea = fgets($fd))
                echo "<p>" . $linea . "</p>";

        }
        fclose($fd);
    } else {
        echo "<p>El fichero no existe</p>";
    }


    ?>
</body>

</html>