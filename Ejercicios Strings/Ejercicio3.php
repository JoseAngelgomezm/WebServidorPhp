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
    <title>Ejercicio3</title>
</head>

<body>
    <div id="formulario">
        <h1>Frase palíndromas - Formulario</h1>
        <p>Dime una frase y te diré si es un palíndromo o un número capicúa.</p>
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
        echo "<h1>Frases Palindromas - Resultado</h1>";
        
        // primero quitar los espacios entre las palabras
        $fraseSinEspacios = strtolower(quitarEspacios($textoIntroducido));
        $i = 0;
        $j = strlen($fraseSinEspacios) -1;
        $resultado = true;
        // comprobar que cada una de las posiciones de la frase son iguales
        while ($i <= $j && $resultado) {
            if ($fraseSinEspacios[$i] == $fraseSinEspacios[$j]) {
                $resultado = true;
                $i++;
                $j--;
            } else {
                $resultado = false;
            }
        }

        // una vez tenemos el resultado
        if($resultado){
            echo "<p>La frase <strong>".$textoIntroducido."</strong> es palindroma</p>";
        }else{
            echo "<p>La frase <strong>".$textoIntroducido."</strong> NO es palindroma</p>";
        }
        echo "</div>";
    }

    function quitarEspacios($unaFrase){
        $textoSinEspacios = "";
        for($i = 0; $i < strlen($unaFrase) -1; $i++){
            if($unaFrase[$i] == " "){
                $textoSinEspacios .= "";
            }else{
                $textoSinEspacios .= $unaFrase[$i];
            }
        }
        return $textoSinEspacios;
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