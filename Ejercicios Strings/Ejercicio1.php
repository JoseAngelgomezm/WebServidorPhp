<?php
    // si se ha mandado el formulario, comprobar errores
    if(isset($_POST)){
        // si la palabra esta vacia o es corta
        $error_palabra1_vacia = $_POST["palabra1"] == "" ;
        $error_palabra2_vacia = $_POST["palabra2"] == "" || strlen($_POST["palabra2"]) < 4;
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
    <form method="post" action="">
        <h1 style="text-align:center">Ripios - formulario</h1>
        <p>Díme dos palabras y te diré si riman o no</p>

        <label for="palabra1">Primera palabra: </label>
        <input name="palabra1" id="palabra1"><?php if(isset($_POST)) echo"$_POST["palabra1"]" ?></input>
        <br>
        <br>
        <label for="palabra2">Segunda palabra: </label>
        <input name="palabra2" id="palabra2"></input>
        <br>
        <br>
        <button type="submit" nama="comparar" id="comparar">Comparar</button>
        

    </form>

</body>
</html>