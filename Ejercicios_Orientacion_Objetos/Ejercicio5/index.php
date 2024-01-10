<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    require 'clase_empleado.php';

    $empleado1 = new Empleado("Ebaristo",2500);
    $empleado1->imprimirEmpleado();

    $empleado2 = new Empleado("Paquito",25000);
    $empleado2->imprimirEmpleado();

    ?>
</body>

</html>