<?php
    // si se ha mandado el formulario, comprobar errores
    if(isset($_POST["comparar"])){
        // si la palabra esta vacia o es corta
        $error_palabra1_vacia = $_POST["palabra1"] == "";
        $error_palabra2_vacia = $_POST["palabra2"] == "";
        $error_palabra1_corta =  strlen($_POST["palabra1"]) < 4;
        $error_palabra2_corta =  strlen($_POST["palabra2"]) < 4;
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
    <h1 style="text-align:center">Ripios - formulario</h1>
    <p>Díme dos palabras y te diré si riman o no</p>
    <form method="post" action="">
        <label for="palabra1">Primera palabra: </label>
        <input name="palabra1" id="palabra1" value="<?php if(isset($_POST["comparar"])  && !$error_palabra1_vacia &&  !$error_palabra1_corta ) echo $_POST["palabra1"] ?>">  </input>
        <?php if(isset($_POST["comparar"]) && $error_palabra1_vacia ) echo "La palabra esta vacia, rellanala"; else if (isset($_POST["comparar"]) && $error_palabra1_corta) echo "La palabra tiene que tener 4 caracteres o más" ?>
        <br>
        <br>
        <label for="palabra2">Segunda palabra: </label>
        <input name="palabra2" id="palabra2" value="<?php if(isset($_POST["comparar"])  && !$error_palabra2_vacia &&  !$error_palabra2_corta ) echo $_POST["palabra2"] ?>"></input>
        <?php if(isset($_POST["comparar"]) && $error_palabra2_vacia ) echo "La palabra esta vacia, rellanala"; else if (isset($_POST["comparar"]) && $error_palabra2_corta) echo "La palabra tiene que tener 4 caracteres o más" ?>
        <br>
        <br>
        <button type="submit" name="comparar" id="comparar">Comparar</button>
    </form>
    </div>
    <div id="respuesta">
    <h1 style="text-align:center">Ripios - resultado</h1>
    <?php

    if(isset($_POST["comparar"]) && substr($_POST["palabra1"],-3) == substr($_POST["palabra2"],-3)){
        echo "".$_POST["palabra1"]." y ".$_POST["palabra2"]." riman de escándalo";
    }else if(isset($_POST["comparar"]) && substr($_POST["palabra1"],-2) == substr($_POST["palabra2"],-2)){
        echo "".$_POST["palabra1"]." y ".$_POST["palabra2"]." riman un poco";
    }else {
        echo "".$_POST["palabra1"]." y ".$_POST["palabra2"]." NO riman";
    }
    
    ?>
    </div>
    <style>
        #formulario {background-color: lightblue; border:solid 2px;}
        #respuesta{}
        label{margin: 1rem;}
        p{margin-left: 1rem;}
        button{margin-left:1rem; margin-bottom: 1rem;}
        #respuesta{display:none;}
    </style>
</body>
</html>