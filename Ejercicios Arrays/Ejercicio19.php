<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        $amigos["Madrid"] = array("Amigo1" =>["nombre" => "Pedro", "edad" => "32", "telefono" => "9199999999"],"Amigo2" =>["nombre" => "Juan", "edad" => "30", "telefono" => "9199999999"]);
        $amigos["Barcelona"] = array("Amigo1" =>["nombre" => "Susana", "edad" => "34", "telefono" => "93000000"], "Amigo2" =>["nombre" => "Juan", "edad" => "30", "telefono" => "9199999999"]);
        $amigos["Toledo"] = array("Amigo1"=>["nombre" => "Sonia", "edad" => "42", "telefono" => "925090909"], "Amigo2"=>["nombre" => "Juan", "edad" => "30", "telefono" => "9199999999"]);

        foreach($amigos as $indice => $valor){
            echo "$indice";
            echo "<br/>";
            foreach($valor as $indice2 => $valor2){
                echo "<br/>";
                foreach($valor2 as $indice3 => $valor3){
                    echo "$indice3 :";
                    echo $valor3;
                    echo "<br/>";
                }
                echo "<br/>";
            }
            echo "<br/>";
        }
    ?>
</body>
</html>