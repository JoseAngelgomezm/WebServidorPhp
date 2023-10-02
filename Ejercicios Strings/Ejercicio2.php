<?php
// comprobar los errores
if (isset($_POST["comprobar"])) {
    $textoIntroducido = trim($_POST["texto"]);
    $error_texto_vacio = $textoIntroducido == "";
    $error_texto_corto = strlen($textoIntroducido) < 3;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio2</title>
</head>

<body>
    <div id="formulario">
        <h1>Palíndromos / capicuas - Formulario</h1>
        <p>Dime una palabra o un número y te diré si es un palíndromo o un número capicúa.</p>
        <form method="post" action="">
            <label for="texto">Palabra o número</label>
            <input type="text" name="texto" id="texto" value="<?php if (isset($_POST["comprobar"]) && !$error_texto_corto && !$error_texto_vacio)
                echo $textoIntroducido; ?>"></input>
            <?php if (isset($_POST["comprobar"]) && $error_texto_vacio)
                echo "campo vacío";
            elseif (isset($_POST["comprobar"]) && $error_texto_corto)
                echo "la longitud tiene que ser 3 o más"; ?>
            <br>
            <button name="comprobar" id="comprobar">Comprobar</button>
        </form>
    </div>

    <?php
    if (isset($_POST["comprobar"]) && !$error_texto_vacio && !$error_texto_corto) {
        echo "<div id ='respuesta'>";
        echo "<h1>Palindromos / capícuas - Resultado</h1>";
        $i = 0;
        $j = strlen($textoIntroducido) - 1;
        $correcto = true;
        // comprobar que cada posicion del string es igual
        while ($i <= $j && $correcto) {
            if ($textoIntroducido[$i] == $textoIntroducido[$j]) {
                $correcto = true;
                $i++;
                $j--;
            } else {
                $correcto = false;
            }
        }

        // una vez tenemos el resultado
        if ($correcto) {
            // comprobar si es un numero
            if (is_numeric($textoIntroducido)) {
                echo "<p>El numero es capicuo</p>";
            } else {
                echo "<p>La palabra es palíndroma</p>";
            }
        } else {
            if (is_numeric($textoIntroducido)) {
                echo "<p>El numero NO es capicuo</p>";
            } else {
                echo "<p>La palabra NO es palindroma</p>";
            }
        }
        echo "</div>";
    }
    ?>

    <style>
    h1 {
        text-align: center;
    }

    #formulario {
        background-color: lightblue;
        border: solid 1px;
    }

    p {
        margin: 1rem
    }

    label {
        margin: 1rem
    }

    button {
        margin: 1rem
    }

    #respuesta{
        background-color: lightgreen; border:solid 1px; margin-top:1rem
    }
    </style>
</body>

</html>