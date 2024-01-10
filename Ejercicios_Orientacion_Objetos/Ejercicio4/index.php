<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Ejercicio 4 </h1>
    <?php
        
        require 'clase_Uva.php';

        $uva = new Uva("verde","pequeño",false);
        echo $uva->imprimir("uva");
        
        
        if($uva->tieneSemillas()){
            echo"<p>Esta uva tiene semilla</p>";
        }else{
            echo"<p>Esta uva NO tiene semilla</p>";
        }
       
    ?>
</body>

</html>