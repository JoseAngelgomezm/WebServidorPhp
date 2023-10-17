<?php
// control de errores
if(isset($_POST["guardar"])){

    $errorNumero1Vacio = $_POST["numero1"] == "";
    $errorNumero2Vacio = $_POST["numero2"] == "";
    $errorRangoNumero1 = !is_numeric($_POST["numero1"]) || $_POST["numero1"] < 0 || $_POST["numero1"] > 10;
    $errorRangoNumero2 = !is_numeric($_POST["numero2"]) || $_POST["numero2"] < 0 || $_POST["numero2"] > 10;

    $errorFormulario = $errorNumero1Vacio || $errorRangoNumero1 || $errorNumero2Vacio || $errorRangoNumero2;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Ejercicio 3 - Ficheros</h1>
    <form action="Ejercicio3.php" method="post">

        <label for="numero1">Introduce un numero entre 1 y 10: </label>
        <input type="text" id="numero1" name="numero1" value="<?php if(isset($_POST["guardar"])) echo $_POST["numero1"] ?>"></input>
        <?php
            if(isset($_POST["guardar"]) && $errorNumero1Vacio)
            echo "El numero 1 está vacio";
            else if(isset($_POST["guardar"]) && $errorRangoNumero1)
            echo "El numero tiene que ser entre 1 y 10";
        ?>
        <br>
        <label for="numero2">Introduce un numero entre 1 y 10: </label>
        <input type="text" id="numero2" name="numero2" value="<?php if(isset($_POST["guardar"])) echo $_POST["numero2"] ?>"></input>
        <?php
            if(isset($_POST["guardar"]) && $errorNumero2Vacio)
            echo "El numero 2 está vacio";
            else if(isset($_POST["guardar"]) && $errorRangoNumero2)
            echo "El numero tiene que ser entre 1 y 10";
        ?>
        <br>
        <button type="submit" name="guardar" id="guardar">Guardar</button>

    </form>
    <?php
        if(isset($_POST["guardar"]) && !$errorFormulario){
            // leer el fichero tablas con el numero del numero1
            // si el fichero existe, leerlo
            if(file_exists("ficheros/Tablas".$_POST["numero1"].".txt")){
                @$fd=fopen("ficheros/Tablas".$_POST["numero1"].".txt", "r");
                if(!$fd){
                    die("error al abrir el archivo");
                }else{
                    // si se puede abrir
                    // mientras exista una linea
                    $contador = 0;
                    while($linea = fgets($fd)){
                        $contador++;
                        if($contador == $_POST["numero2"]){
                            echo "<p>La linea ".$_POST["numero2"]." del Fichero es: ".$linea."</p>";
                        }
                    }
                }
            }else{
                // no existe
                echo "<p>El fichero no existe</p>";
            }
            
        }
    ?>

</body>

</html>