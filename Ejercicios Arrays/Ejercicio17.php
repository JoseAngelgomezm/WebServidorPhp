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
        foreach($familias as $indice => $valor){
            echo "<li>$indice</li>";

            echo "<ul>";
            foreach($valor as $indice2 => $valor2){
                
                foreach($valor2 as $indice3 => $valor3){
                    if(is_array($valor3)){
                        echo "<ul>";
                        foreach ($valor3 as $indice4 => $valor4){
                            echo "<li>$indice4: $valor4</li>";
                        }
                            
                        echo "</ul>";
                    }
                    echo "<li>$indice3: $valor3</li>";
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