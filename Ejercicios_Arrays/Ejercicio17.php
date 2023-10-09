<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    $familias["Los Simpsons"] = [["padre" => "Homer","madre" => "Merge","hijos" => ["hijo1" => "Bart", "hijo2" => "Lisa","hijo3" => "Maggie"]]];
    $familias["Los Griffin"] = [["padre" => "Peter","madre" => "Lois","hijos" => ["hijo1" => "Crhis","hijo2" => "Meg","hijo3" => "Stewie"]]];
   
    echo "<ul>";
        foreach($familias as $nombre_familia => $array_datos){
            echo "<li>$nombre_familia</li>";

            echo "<ul>";
            foreach($array_datos as $indice => $arrayParentescoHijos){
    
                foreach($arrayParentescoHijos as $parentesco => $nombre){
                    echo "<li>$parentesco";
                    if(is_array($nombre)){
                        echo ":";

                        echo "<ul>";
                        foreach($nombre as $numeroHijo => $nombreHijo){
                            echo "<li>$numeroHijo: $nombreHijo</li>";
                        }
                        echo "</ul>";

                    }else{
                        echo ": $nombre";
                    }
                    echo "</li>";
                }
            }
            echo "</ul>";
        }
    echo "</ul>";



    /*  
        echo "<ul>";
        echo "</ul>";

        echo "<li>";
        echo "</li>";
    
    */

    ?>
</body>

</html>