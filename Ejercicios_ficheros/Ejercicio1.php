<?php
// comprobar errores
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
    <h1>Ejercicio1 - Ficheros</h1>
    <form action="index.php" method="post">
        <label for="numero">Introduce un numero entre 1 y 10</label>
        <input type="text" name="numero" id="numero" value="<?php if (isset($_POST["guardar"]))
            echo $_POST["numero"] ?>"></input>
            <br>
            <button type="submit" name="guardar" id="guardar">Guardar</button>
            <?php
        if (isset($_POST["guardar"]) && $errorNumeroVacio) {
            echo "<p>El numero está vacio</p>";
        } else if (isset($_POST["guardar"]) && $errorRangoNumero) {
            echo "<p>El numero tiene que ser entre 1 y 10</p>";
        }
        ?>
    </form>

    <?php
    if (isset($_POST["guardar"]) && !$errorFormulario) {
        echo "<h1>Creando fichero</h1>";
        // ver si el fichero existe, si no existe crearlo
        if (!file_exists("ficheros/Tablas" . $_POST["numero"] . ".txt")) {
            // abrir el fichero en modo escritura controlando errores
            @$fsd = fopen("ficheros/Tablas" . $_POST["numero"] . ".txt", "w");
            if (!$fsd) {
                die("No se ha podido crear el fichero");
            }


            // bucle para poner la tabla del numero en el fichero
            for ($i = 0; $i < 11; $i++) {
                fputs($fsd, $_POST["numero"] . " x " . $i . " = " . $_POST["numero"] * $i . PHP_EOL);

            }

            fclose($fsd);
        }else{
            echo "<p>El fichero ya existe</p>";
        }

    }
    ?>
</body>

</html>