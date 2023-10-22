<?php
if (isset($_POST["contar"])) {
    $errorTexto = trim($_POST["texto"] == "");
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
    <form method="post" action="#" enctype="multipart/form-data">
        <p>
            <label for="texto">Introduce un texto con separadores</label>
            <input type="text" name="texto" id="texto"
                value="<?php if (isset($_POST["contar"]))
                    echo trim($_POST["texto"]) ?>"></input>
                <?php
                if (isset($_POST["contar"]) && $errorTexto) {
                    echo "texto vacio";
                }
                ?>
        <p>
            Selecciona un separador
            <select name="separador" id="separador">
                <?php
                $arraySeparadores = array(";", ",", " ", ":");
                for ($i = 0; $i < count($arraySeparadores); $i++) {
                    if ($arraySeparadores[$i] == $_POST["separador"]) {
                        echo "<option selected value='" . $arraySeparadores[$i] . "'>" . $arraySeparadores[$i] . "</option>";
                    } else {
                        echo "<option value='" . $arraySeparadores[$i] . "'>" . $arraySeparadores[$i] . "</option>";
                    }
                }
                ?>
            </select>
        </p>

        </p>
        <button type="contar" id="contar" name="contar">Contar</button>
    </form>

    <?php
    if (isset($_POST["contar"]) && !$errorTexto) {
        $texto = trim($_POST["texto"]);
        $nLetra = 0;
        $palabras = 0;
        while (isset($texto[$nLetra])) {
            if ($texto[$nLetra] == $_POST["separador"]) {
                $palabras++;
            }
            $nLetra++;
        }
        if ($nLetra == 0) {
            echo "El texto tiene un total de 0 palabras";
        } else {
            echo "El texto tiene un total de: " . ($palabras + 1) . " palabras";
        }


    }
    ?>
</body>

</html>