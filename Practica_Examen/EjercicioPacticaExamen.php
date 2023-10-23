<?php
// control errores
if (isset($_POST["validar"])) {

    $errorCampo = $_POST["palabra"] == "" || is_numeric($_POST["palabra"]) || strlen($_POST["palabra"]) < 2;

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
    <h1>Practica Examen 1</h1>
    <form action="#" method="post">
        <p>
            <label for="palabra">Introduce una palabra</label>
            <input type="text" name="palabra" id="palabra" value="<?php if (isset($_POST["validar"]))
                echo $_POST["palabra"] ?>"></input>
                <?php
            if (isset($_POST["validar"]) && $errorCampo) {
                if (is_numeric($_POST["palabra"])) {
                    echo "<span class='error'>Pon una palabra</span>";
                } else if(strlen($_POST["palabra"]) < 2){
                    echo "<span class='error'>Minimo 2 caracteres</span>";
                }else{
                    echo "<span class='error'>Campo vacio</span>";
                }

            }
            ?>
        </p>

        <p>
            <button type="submit" name="validar" id="validar">Validar</button>
        </p>

        <?php
        if (isset($_POST["validar"]) && !$errorCampo) {
            $seRepite = false;
            $palabra = $_POST["palabra"];
            // recorrer la palabra completa
            for ($i = 0; $i < strlen($palabra); $i++) {
                // quedarnos con la letra
                $letra = $palabra[$i];
                // recorrer la palabra desde la posicion siguiente de la letra que tenemos 
                for ($j = $i+1; $j < strlen($palabra); $j++) {
                    // si la letra que tenemos se repite en alguna de las posiciones
                    if ($letra == $palabra[$j]) {
                        $seRepite = true;
                        break;
                    }
                    
                }
                // pararlo si no se ha iterado entero
                if($j<strlen($palabra)){
                    break;
                }
            }

            if($seRepite){
                echo "<span class='error'>Contiene caracteres repetidos</span>";
            }else{
                echo "<span class='resultado'>NO ontiene caracteres repetidas</span>";
            }
        }
        
        ?>
    </form>
</body>
<style>
    .error {
        color: red;
    }
    .resultado{
        color:green;
    }
</style>

</html>