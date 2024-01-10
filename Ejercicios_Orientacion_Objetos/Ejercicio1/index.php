<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Ejercicio 1 </h1>
    <?php
        require 'clase_fruta.php';

        $pera = new Fruta();
        $pera->setTamaño(0.3);
        $pera->setColor("verde");

        echo "<h2>Mi furta pera tiene la siguientes propiedades</h2>";
        echo "<p><strong>Color: </strong> ".$pera->getColor()."</strong></p>";
        echo "<p><strong>Tamaño: </strong>".$pera->getTamaño()."</strong</p>";
    ?>
</body>

</html>