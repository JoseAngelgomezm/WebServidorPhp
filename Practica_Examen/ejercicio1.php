<?php
function myStrlen($texto){
    $numeroCaracteres = 0;
    $i = 0;
    while(isset($texto[$i])) {
        $numeroCaracteres++;
        $i++;
    }
    return $numeroCaracteres;
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
    <h1>Ejercicio 1 - Practica Examen</h1>
    <form method="post" action="#">
    <label for="texto">Introduce un texto para saber su longitud</label>
    <input type="text" name="texto" id="texto"></input>
    <button type="submit" id="calcular" name="calcular">Calcular</button>
    </form>
    <?php
    if(isset($_POST["calcular"])) {
        $numeroCaracteres = myStrlen($_POST["texto"]);
        echo "la cadena contiene: ".$numeroCaracteres;
    }
    ?>
</body>

</html>