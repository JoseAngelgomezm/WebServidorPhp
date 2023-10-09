<?php
    // si se ha mandado el formulario, comprobar errores
    if(isset($_POST["comparar"])){
        // quitar los espacios en blanco de delante y detras con trim
        $palabra1 = trim($_POST["palabra1"]);
        $palabra2 = trim($_POST["palabra2"]);
         // si la palabra esta vacia o es corta
        $error_palabra1_vacia = $palabra1 == "";
        $error_palabra2_vacia = $palabra2 == "";
        $error_palabra1_corta =  strlen($palabra1) < 4;
        $error_palabra2_corta =  strlen($palabra2) < 4;
    }
   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio1</title>
    
    
</head>
<body>
    <div id="formulario">
    <h1>Ripios - formulario</h1>
    <p>Dime dos palabras y te diré si riman o no</p>
    <form method="post" action="">
        <label for="palabra1">Primera palabra: </label>
        <input name="palabra1" id="palabra1" value="<?php if(isset($_POST["comparar"])  && !$error_palabra1_vacia &&  !$error_palabra1_corta ) echo $palabra1 ?>">  </input>
        <?php if(isset($_POST["comparar"]) && $error_palabra1_vacia ) echo "La palabra esta vacia, rellanala"; else if (isset($_POST["comparar"]) && $error_palabra1_corta) echo "La palabra tiene que tener 4 caracteres o más" ?>
        <br>
        <br>
        <label for="palabra2">Segunda palabra: </label>
        <input name="palabra2" id="palabra2" value="<?php if(isset($_POST["comparar"])  && !$error_palabra2_vacia &&  !$error_palabra2_corta ) echo $palabra2 ?>"></input>
        <?php if(isset($_POST["comparar"]) && $error_palabra2_vacia ) echo "La palabra esta vacia, rellanala"; else if (isset($_POST["comparar"]) && $error_palabra2_corta) echo "La palabra tiene que tener 4 caracteres o más" ?>
        <br>
        <br>
        <button type="submit" name="comparar" id="comparar">Comparar</button>
    </form>
    </div>
    
    
    <?php
    if(isset($_POST["comparar"]) && !$error_palabra1_vacia &&  !$error_palabra1_corta && !$error_palabra2_vacia &&  !$error_palabra2_corta){
        echo "<div id='respuesta'>";
        echo "<h1>Ripios - resultado</h1>";
        // pasar a minuscula los campos antes de compararlos
        if(strtolower(substr($palabra1,-3)) == strtolower(substr($palabra2,-3))){
            echo "<p><strong>".$palabra1."</strong> y <strong>".$palabra2."</strong> riman de escándalo</p>";
        }else if(strtolower(substr($palabra1,-2)) == strtolower(substr($palabra2,-2))){
            echo "<p><strong>".$palabra1."</strong> y <strong>".$palabra2."</strong> riman un poco</p>";
        }else {
            echo "<p><strong>".$palabra1."</strong> y <strong>".$palabra2."</strong> NO riman</p>";
        }
    }
    ?>
    </div>
    <style>
        #formulario {background-color: lightblue; border:solid 2px;}
        label{margin: 1rem;}
        p{margin-left: 1rem;}
        button{margin-left:1rem; margin-bottom: 1rem;}
        h1{text-align: center;}
        #respuesta{background-color: lightgreen; border:solid 1px; margin-top: 1rem; padding:1rem}
    </style>
</body>
</html>