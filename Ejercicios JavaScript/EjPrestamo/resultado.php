<?php
$interes = $_GET["interes"];
$interes = $interes / 10;
$resultado = ($_GET["capital"] * $interes) / $_GET["plazo"];
echo $resultado;
?>