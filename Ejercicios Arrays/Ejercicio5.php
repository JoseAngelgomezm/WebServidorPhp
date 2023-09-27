<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <?php
  $persona["nombre"] = "Pedro Torres";
  $persona["direccion"] = "Calle Mayor, 37";
  $persona["telefono"] = "123456789";



  foreach ($persona as $indice => $valor) {
    echo "$indice : " . $valor . "";
    echo "<br>";
  }

  ?>
</body>

</html>