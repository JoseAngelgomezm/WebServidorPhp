<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        $numeros = array("p1" => "3", "p2" => "2", "p3" => "8", "p4" => "123", "p5" => "5", "p6"=>"1");
    
        sort($numeros);

        foreach($numeros as $indice => $valor){
            echo "$valor <br>";
        }
    
    ?>
</body>
</html>