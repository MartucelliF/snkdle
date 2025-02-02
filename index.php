<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>shingekinokyojindle</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" type="image/jpg" href="img/icon.png"/>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

</head>
<body>
    
    <?php
    include ("php/conexion.php");
    session_start();
    ?>
    

    <div class="contenedor">
        <img src="img/titulo.png" alt="titulo" width="450px" height="120px">

        <div class="menuModos">
            <button>
                <a href="php/modos/clasico.php"><img src="img/botones/modoClasico.png" width="450px" heigth="450px" alt="modoClasico"></a>
            </button>
            <br>
            <button>
                <a href="php/modos/frase.php"><img src="img/botones/modoFrase.png" width="450px" heigth="450px" alt="modoFrase"></a>
            </button>
            <br>
            <button>
                <a href=""><img src="img/botones/modoSplash.png" width="450px" heigth="450px" alt="modoSplashArt"></a>
            </button>
        </div>
    </div>
</body>
</html>