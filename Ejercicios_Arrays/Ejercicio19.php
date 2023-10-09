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
        $amigos["Barcelona"] = array("Amigo1" =>["nombre" => "Susana", "edad" => "34", "telefono" => "93000000"], "Amigo2" =>["nombre" => "Juan", "edad" => "30", "telefono" => "9199999999"], "Amigo3" => ["nombre" => "Juan", "edad" => "30", "telefono" => "9199999999"]);
        $amigos["Toledo"] = array("Amigo1"=>["nombre" => "Sonia", "edad" => "42", "telefono" => "925090909"], "Amigo2"=>["nombre" => "Juan", "edad" => "30", "telefono" => "9199999999"], "Amigo3"=>["nombre" => "Santiago", "edad" => "42", "telefono" => "925090909"], "Amigo4"=>["nombre" => "Pepe", "edad" => "30", "telefono" => "9199999999"]);


        foreach($amigos as $ciudad => $array_amigos){
            echo "Amigos en: $ciudad";
            echo "<br/>";
            echo "<ol>";
            foreach($array_amigos as $numero_amigo => $array_datos_amigos){
                echo "<br/>";
                echo "<li>";
                foreach($array_datos_amigos as $tipo_dato => $dato){
                    echo "<strong>$tipo_dato</strong>: $dato ";
                    
                }
                echo "</li>";
            }
            echo "<br/>";
            echo "</ol>";
        }
    ?>
</body>
</html>