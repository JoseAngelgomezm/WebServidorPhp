<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    $mes["enero"] = "9";
    $mes["febrero"] = "12";
    $mes["marzo"] = "0";
    $mes["abril"] = "17";

    foreach ($mes as $indice => $valor) {
        if ($valor !== "0") {
            echo "<p>En el mes de " . $indice . " se han visto " . $valor . " películas </p>";
        }
    }

    ?>
</body>

</html>