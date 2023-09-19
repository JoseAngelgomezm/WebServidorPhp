<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Teoria elemental de PHP</h1>
    <?php

        // incrustar html en php
        echo "<h1>Mi primera pagina</h1>";
        
        // declaracion de variables
        $a = 8;
        $b=9;
        $c = $a + $b;

        // declaracion de constantes
        define("PI",3.141516);

        // uso de constantes
        $d = $a * PI;
        echo "el resultado de ".$a." * ".PI." es ".$d."";
        
        // concatenar variables
        echo "<h1>Concatenar variables</h1>";
        echo "<p>el resultado de sumar ".$a." + ".$b." es: ".$c."</p>";
        
        // sentencias if
        echo "<h1>Sentencias if</h1>";
        if(3>$c)
        {
            echo "<p>3 es mayor que ".$c." </p>";
        }
        else if(3==$c)
        {
            echo "<p>3 es igual que ".$c." </p>";
        }
        else{
            echo "<p>3 es menor que ".$c." </p>";
        }

        // sentencias switch 
        echo "<h1>Sentencias switch</h1>";
        $d=3;
        switch($d){
            case 1: $c = $a-$b;
            break;
            case 2: $c = $a/$b;
            break;
            case 3: $c = $a*$b;
            break;
            default:$c= $a+$b;
            break;

        }

        echo "<p> el resultado del switch es ".$c." </p>";
        
        // bucles for
        // valor1: Asignacion inicial
        // valor2: Condicion de salida 
        // valor3: Incremento
        echo "<h1>Bucles for</h1>";
        for($i=0;$i<8;$i++)
        {
            echo "<p>hola numero ".$i."</p>";
        }
        
        // bucles while
        echo "<h1>Bucles while</h1>";
        $i=0;
        while ($i<=8)
        {
            echo "<p>hola numero ".$i."</p>";
            $i++;
        }
    ?>
</body>
</html>