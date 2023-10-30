<?php
// control de errores
if (isset($_POST["contar"])) {
    $errorTexto = $_POST["texto"] == "";
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
    <?php
    if (isset($_POST["contar"])) {
        $numeroPalabras = 0;
        $texto = $_POST["texto"];
        $i = 0;
        while (isset($texto[$i])) {
            if ($texto[$i] == $_POST["separador"]) {
                $i++;
                $numeroPalabras ++;
            }
            $i++;
        }
    }
    ?>

    <h1>Ejercicio 2. Contar palabras sin las vocales (a,e,i,o,u,A,E,I,O,U)</h1>
    <form action="#" method="post">
        <p>
            <label for="texto"></label>
            <input type="text" name="texto" id="texto" value='<?php if (isset($_POST["contar"]))
                echo $_POST["texto"] ?>'>
                <?php
            if (isset($_POST["contar"]) && $_POST["texto"] == "") {
                echo "<span>El texto no puede estar vacio</span>";
            }
            ?>
        </p>
        <label for="texto">Elija un separador</label>
        <select name="separador" id="separador">
            <?php
            $arraySeparadores = array("Punto y coma" => ";", "Espacio" => " ", "Dos puntos" => ":", "Coma" => ",");
            foreach ($arraySeparadores as $indice => $valor) {
                if ($_POST["separador"] == $valor) {
                    echo "<option selected value='$valor'>" . $indice . "</option>";
                } else {
                    echo "<option value='$valor'>" . $indice . "</option>";
                }

            }
            ?>
        </select>
        <p>
            <button name="contar" id="contar">Contar</button>
        </p>
    </form>
    <?php
    if (isset($_POST["contar"]) && !$errorTexto) {
        echo "<h1>Respuesta</h1>";


        echo "Hay ".$numeroPalabras." Palabras";
    }
    ?>
</body>

</html>