<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
    <h1>Teoria de fechas</h1>
<body>
    <?php
        // devuelve cantidad segundos que han pasado desde 1 enero 1970 hasta ahora
        echo "<p>" .time(). "</p>";

        // primer parametro, el formato de fecha
        // segundo parametro, si no se da, te da el time (segundos que pasaron desde 1970 hasta ahora)
        // si se da, dara la fecha de 1 enero 1970 + los segundos que pasamos
        echo "<p>" .date("d/m/Y h:i:s",2000000000). "</p>";

        // devuelve true o false, comprobacion de fechas, si existe o no
        // se le pasa mes dia año
        // comprueba 29 de feberero de 2023
        if(checkdate(2,29,2023)){
            echo "<p>Fecha válida</p>";
        }else{
            echo "<p>Fecha mala</p>";
        }
        
        // devuelve los segundos que han pasado desde 1 enero 1970
        // hasta la fecha que le pasamos en formato hora,minuto,segundo,mes,dia,año
        echo mktime(0,0,0,9,16,1998);
        // ahora, si hacemos el date , y pasamos la fecha anterior
        // nos tiene que aparecer la fecha que hemos pasado
        echo "<p>". date("d/m/Y", mktime(0,0,0,9,16,1998)) . "</p>";

        // se le pasa una fecha y te da un string con los segundos que han pasado
        // hasta ese dia
        echo "<p>".strtotime("09/16/1998")."</p>";
        
    ?>
</body>

</html>