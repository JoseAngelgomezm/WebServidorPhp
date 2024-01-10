<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Ejercicio 6</h1>
    <?php
    require 'clase_menu.php'; 
    $menu = new Menu();
    $menu->cargar("https://www.nintendo.com","Nintendo");
    $menu->cargar("https://www.google.com","Google");
    $menu->cargar("https://www.twitch.com","Twitch");
    $menu->cargar("https://www.forocoches.com","Forocoches");
    $menu->vertical();
    $menu->horizontal();
    ?>
</body>

</html>