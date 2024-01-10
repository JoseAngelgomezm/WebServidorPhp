<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Ejercicio 3 </h1>
    <?php
        require 'clase_fruta.php';

        echo "<p>Hay un total de ".Fruta::numeroFrutas()." frutas creadas hasta ahora</p>";

        $pera = new Fruta("verde","mediano");
        echo $pera->imprimir("pera");

        $manzana = new Fruta("roja","mediano");
        echo $manzana->imprimir("manzana");


        $melon = new Fruta("amraillo","grande");
        echo $melon->imprimir("melon");
    
        $sandia = new Fruta("roja","gigante");
        echo $sandia->imprimir("sandia");
       
        echo "<p>Hay un total de ".Fruta::numeroFrutas()." frutas creadas hasta ahora</p>";

        echo "<p>Destruyendo la manzana.....</p>";
        unset($manzana);
        
        echo "<p>Hay un total de ".Fruta::numeroFrutas()." frutas creadas hasta ahora</p>";
    ?>
</body>

</html>