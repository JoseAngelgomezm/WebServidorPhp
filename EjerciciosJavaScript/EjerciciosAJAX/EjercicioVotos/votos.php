<?php
//Se abre el fichero deonde están almacenados los datos
$fichero = "resultados.txt";
$contenido = file($fichero);
//colocamos el contenido en un array y lo almacenamos en cuatro variables por equipos
$array = explode("||", $contenido[0]);
$real = $array[0];
$bar = $array[1];
$atl = $array[2];
$sev = $array[3];

//extraemos el voto de los participantes
$voto = $_GET['voto'];

//actualizamos los votos añadiendo el recibido a los anteriores
if ($voto == 0) {
    $real = $real + 1;
}
if ($voto == 1) {
    $bar = $bar + 1;
}
if ($voto == 2) {
    $atl = $atl + 1;
}
if ($voto == 3) {
    $sev = $sev + 1;
}
//creamos la cadena que se va a insertar en el fichero
$insertvoto = $real . "||" . $bar . "||" . $atl . "||" . $sev;
//se abre el fichero como escritura y se escriben los votos actualizados
$fp = fopen($fichero, "w");
fputs($fp, $insertvoto);
fclose($fp);

// se calcula el % del voto de cada uno de los equipos
$denominador = (int) $real + (int) $bar + (int) $atl + (int) $sev;
$tantoMadrid = 100 * round($real / $denominador, 2);
$tantoBarcelona = 100 * round($bar / $denominador, 2);
$tantoAtletico = 100 * round($atl / $denominador, 2);
$tantoSevilla = 100 * round($sev / $denominador, 2);
?>
<h2>Resultado:</h2>
<table>
    <tr>
        <td>Real Madrid:</td>
        <td>
            <img src="barrita.gif" width='<?php echo ($tantoMadrid); ?>' height='20'>
            <?php echo ($tantoMadrid); ?>%
        </td>
    </tr>
    <tr>
        <td>Barcelona:</td>
        <td>
            <img src="barrita.gif" width='<?php echo ($tantoBarcelona); ?>' height='20'>
            <?php echo ($tantoBarcelona); ?>%
        </td>
    </tr>
    <tr>
        <td>Atlético de Madrid:</td>
        <td>
            <img src="barrita.gif" width='<?php echo ($tantoAtletico); ?>' height='20'>
            <?php echo ($tantoAtletico); ?>%
        </td>
    </tr>
    <tr>
        <td>Sevilla:</td>
        <td>
            <img src="barrita.gif" width='<?php echo ($tantoSevilla); ?>' height='20'>
            <?php echo ($tantoSevilla); ?>%
        </td>
    </tr>
</table>