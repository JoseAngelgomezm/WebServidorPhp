<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    $estadios_futbol = array(
        "Barcelona" => "Camp Nou",
        "Real Madrid" => "Santiago Bernabeu",
        "Valencia" => "Mesatalla",
        "Real Sociedad" => "Anoeta"
    );
    
    echo "<table border=1px;";
        // mostrar una fila con los indices
        echo "<tr>";
            echo"<th>Equipo</th>";
            foreach ($estadios_futbol as $indice => $valor) {
                echo "<td>$indice</td>";
            }
        echo "</tr>";
        // mostrar una fila con los valores
        echo "<tr>";
            echo"<th>Estadio</th>";
            foreach ($estadios_futbol as $indice => $valor) {
                echo "<td>$valor</td>";
            }
        echo "</tr>";

    echo "</table>";

    // eliminar del array el indice Real Madrid
    unset($estadios_futbol["Real Madrid"]);
    echo "<br>";

    echo "<table border=1px;";
        echo "<tr>";
        echo"<th>Equipo</th>";
            foreach ($estadios_futbol as $indice => $valor) {
                echo "<td>$indice</td>";
            }
        echo "</tr>";

        echo "<tr>";
        echo"<th>Estadio</th>";
            foreach ($estadios_futbol as $indice => $valor) {
                echo "<td>$valor</td>";
            }
        echo "</tr>";
        
    echo "</table>";
    ?>
</body>

</html>