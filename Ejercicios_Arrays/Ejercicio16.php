<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        $array = array("5" => "1", "12" => "2", "13" => "46", "x" => "42");

        foreach($array as $indice => $valor){
            echo "$valor <br>";
        }

        echo "<br>El array tiene: ".count($array) . " elementos";

        
        echo "<br>";
        unset($array["5"]);
        echo "<br>";
       
        foreach($array as $indice => $valor){
            echo "$valor <br>";
        }
        echo "<br>El array tiene: ".count($array) . " elementos";

        unset($array);

    ?>
</body>
</html>