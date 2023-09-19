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
    
    ?>
</body>
</html>