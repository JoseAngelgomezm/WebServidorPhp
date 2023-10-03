<?php
    if(isset($_POST["enviar"])){
        $numeroIntroducido = trim($_POST["numero"]);
        $error_texto_vacio = $numeroIntroducido == "";
        $error_numero_mayor = $numeroIntroducido > 5000;
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
    <div id="formulario">
        <h1>Arabes a romanos - Formulario</h1>
        <p>Dime un número en arabe y lo convertire a cifras romanas</p>
        <form method="post" action="">
            <label for="numero">Numero: </label>
            <input type="text" name="numero" id="numero" value="<?php if (isset($_POST["enviar"])) echo $numeroIntroducido; ?>"></input>
            <?php if (isset($_POST["enviar"]) && $error_texto_vacio) echo "campo vacío"; elseif(isset($_POST["enviar"]) && $error_numero_mayor) echo "El numero es mayor a 5000"; ?>
            <br>
            <button name="enviar" id="enviar">Enviar</button>
        </form>
    </div>

    <?php

    // resultado
    if (isset($_POST["enviar"]) && !$error_texto_vacio && !$error_numero_mayor) {
        echo "<div id ='respuesta'>";
        echo "<h1>Romanos a árabes - Resultado</h1>";
    
        // recorrer el array
        for($i= 0; $i < strlen($numeroIntroducido); $i++){
            // empezar por los miles, si tiene 4 cifras
            if(strlen($numeroIntroducido) == 4){
                // obtener la cifra de los miles
            
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

        #respuesta {
            background-color: lightgreen;
            border: solid 1px;
            margin-top: 1rem
        }
    </style>
    
</body>
</html>