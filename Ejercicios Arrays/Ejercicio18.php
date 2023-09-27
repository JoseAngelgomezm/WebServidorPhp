<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        $deportes = array("futbol", "baloncesto", "natacion", "tenis");

        for($i = 0; $i<count($deportes); $i++){
            echo $deportes[$i]."<br/>";
        }

        echo "<br>";

        echo "El array tiene ".count($deportes)." elementos";
        echo "<br>";
        echo "<br>";
        echo "El elemento actual es ".current($deportes);
        echo "<br>";
        echo "El elemento siguiente es ".next($deportes);
        echo "<br>";
        echo "El ultimo elemento es ".end($deportes);
        echo "<br>";
        echo "El elemento anterior es ".prev($deportes);
        
    ?>
</body>
</html>