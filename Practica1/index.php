<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rellena tu cv</title>
</head>

<body>
    <h1>Rellena tu CV</h1>
    <!-- enctype para enviar archivos y el metodo post porque lleva archivos y contraseña -->
    <form action="recogida.php" method="post" enctype="multipart/form-data">

        <p>
            <label for="nombre">Nombre</label>
            <br/>
            <input type="text" id="nombre" name="nombre"></input>
        </p>



        <p>
            <label for="apellidos">Apellidos</label>
            <br/>
            <input type="text" id="apellidos" size="50" name="apellidos"></input>
        </p>
    
        <p>
            <label for="contraseña">Contraseña</label>
            <br/>
            <input type="password" id="contraseña" name="contraseña"></input>

        </p>

        <p>
            <label for="dni">Dni</label>
            <br/>
            <input type="text" size="10" id="dni" name="dni"></input>
        </p>

        <p>
            <label for="sexo">Sexo</label>
            <br/>
            <input type="radio" name="sexo" value="hombre"></input><label for="hombre">Hombre</label>
            <input type="radio" name="sexo" value="mujer"></input><label for="mujer">Mujer</label>
        </p>


        <p>
            incluir mi foto: <input type="file" id="image" src=""></input>
        </p>

        <p>
            Nacido en:
            <select name="pais">
                <option value="malaga">Malaga</option>
                <option value="cadiz">Cadiz</option>
                <option value="almeria">Almeria</option>

            </select>
        </p>

        <p>
            Comentarios: <textarea name="comentarios" id="comentarios" cols="50" rows="10"></textarea>
        </p>

        <p>
            <input type="checkbox" name="suscrito">Subscribirse al boletín de novedades</input>
        </p>


        <p>
            <input type="submit" name="guardar" id="guardar"></input>
            <input type="reset" name="reset" id="reset"></input>
        </p>


    </form>



</body>

</html>