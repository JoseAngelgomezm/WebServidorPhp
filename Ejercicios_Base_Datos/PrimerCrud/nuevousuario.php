<?php
if(isset($_POST["continuar"])) {
    $errorNombre = $_POST["nombre"] != "";
    $errorUsuario = $_POST["usuario"] != "";
    $errorContraseña = $_POST["contraseña"] != "";
    $errorEmail = $_POST["email"] != "" || !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL); 

    $errorFormulario = $errorNombre || $errorUsuario || $errorContraseña || $errorEmail;

    if(!$errorFormulario){
        header("location:index.php");
    }
}

if (isset($_POST["volver"])) {
    header("location:index.php");
}
if (isset($_POST["nuevousuario"])) {
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>nuevo usuario</title>
    </head>

    <body>
        <h1>Nuevo usuario</h1>
        <form action="" method="post">
            <p>
                <label for="nombre">Nombre: </label>
                <input type="text" name="nombre" value="" maxlength="30">
            </p>
            <p>
                <label for="usuario">Usuario: </label>
                <input type="text" name="usuario" value="" maxlength="20">
            </p>
            <p>
                <label for="contraseña">Contraseña: </label>
                <input type="password" name="contraseña" value="" maxlength="15">
            </p>
            <p>
                <label for="email">Email: </label>
                <input type="email" name="email" value="" maxlength="50">
            </p>
            <p>
                <button type="submit" name="continuar">Continuar</button>
                <button type="submit" name="volver">Volver</button>
            </p>

    </body>

    </html>

    <?php
}
?>