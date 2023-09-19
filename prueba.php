<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php

        // incrustar html en php
        echo "<h1>Mi primera pagina</h1>";
        
        // declaracion de variables
        $a = 8;
        $b=9;
        $c = $a + $b;
        
        // concatenar variables
        echo "<p>el resultado de sumar ".$a." + ".$b." es: ".$c."</p>";
        
        // sentencias if
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
        for($i=0;$i<8;$i++)
        {
            echo "<p>hola numero ".$i."</p>";
        }
        
        // bucles while

    ?>
</body>
</html>