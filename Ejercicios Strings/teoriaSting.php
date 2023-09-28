<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    $str1 = "hola";
    $str2 = "       Juan       ";

    echo "<h1>".$str1." ".$str2."</h1>";

    // devuelve la longitud del string, no es count, es strlen
    $longitud = strlen($str1);
    echo "<p>La longitud del String: ".$str1." es: ".$longitud."</p>";

    // los string se comportan como arrays, si pedimos la posicion 3, obtendremos la a de hola
    // pero no podemos usar count, porque no llega a ser un array
    $letra = $str1[3];

    echo "<p>La posicion 3 de Hola es: ".$letra."</p>";

    // podemos sobreescribir el caracter del string accediendo a su posicion
    $str1[3] = "o";
    echo "<p>".$str1."</p>";

    // pasar todo el string a mayuscula, pero no muta el string
    // solo devuelve el contenido que habia en mayuscula
    echo "<p>".strtoupper($str1)."</p>";
    echo "<p>".$str1."</p>";
    echo "<p>".strtolower($str1)."</p>";
    echo "<p>".$str1."</p>";

    // uso de trim, limpia espacios al principio y final de la cadena
    $longitud = strlen($str2);
    echo "<p>La longitud del String: ".$str2." es: ".$longitud."</p>";
    $longitud = strlen(trim($str2));
    echo "<p>La longitud del String: ".$str2." es: ".$longitud."</p>";

    // con explode podemos separar una frase por palabras 
    // primer parametro es el separador de la frase, 
    // segundo parametro el string que queremos dividir
   
    $prueba="Hola novea que Peazo de dia hace Joselito";
    $prueba_separado = explode(" ",$prueba);
    print_r($prueba_separado);

    // podemos sacar una extension de un archivo
    $prueba = "asdasdasdasdas.asdasd.png";
    $prueba_extension = explode(".",$prueba);
    echo "<p>La extension del archivo es: ".end($prueba_extension)."</p>";

    // con implode coger un array y convertirlo en string con un caracter delimitador que queramos
    $prueba = array("hola", "novea", "que" , "Peazo", "de", "dia", "hace", "Joselito");
    $prueba_array = implode(".",$prueba);
    echo "<p>El string del array con delimitador 'punto' es: ".$prueba_array."</p>";

    // con substring, podemos sacar un string en concreto de un string
    // se le pasa el string, desde donde empieza y donde termina
    echo "<p>".substr("hola que tal joselito", 4, 6)."</p>";
    // si solo se le pasa un parametro, te devuelve desde el parametro hasta el final
    echo "<p>".substr("hola que tal joselito", 4)."</p>";
    // si le das valor negativo, te da el numero de caracteres empezando por el final
    echo "<p>".substr("hola que tal joselito", -8)."</p>";

    ?>
</body>
</html>