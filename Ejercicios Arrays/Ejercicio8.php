<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    $personas = array("Pedro", "Ismael", "Sonia", "Clara", "Susana", "Alfonso");

    echo "<ul>";
    echo "El array de personas contiene " . count($personas) . " elementos que son: ";
    for ($i = 0; $i < count($personas); $i++) {
        echo "<li>" . $personas[$i] . "</li>";
    }
    echo "</ul>";

    ?>
</body>

</html>