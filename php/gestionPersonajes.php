<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
    <link rel="icon" type="image/jpg" href="../img/icon.png"/>
</head>

<body>
    <?php
    include("conexion.php");

    // Determinar el paso actual
    $paso = isset($_GET['paso']) ? intval($_GET['paso']) : 0;

    ?>
    <div class="display">
        <div class="centrar">
        <?php
        // Procesar lógica según el paso
        switch ($paso) {
            case 0:
             
                if (isset($_POST['nombre_personaje'],$_POST['genero_personaje'])) {
                    // Capturar los datos del formulario
                    $nombre_personaje = $_POST['nombre_personaje'];
                    $genero_personaje = $_POST['genero_personaje'];
                }

               
                $insertPersonaje = "INSERT INTO personajes(id_personaje, nombre_personaje, genero_personaje) VALUES ('','$nombre_personaje','$genero_personaje');";
                $insertPersonaje = mysqli_query($conexion, $insertPersonaje);
                
                header("Location: ../index.php");
            break;

            case 1:
                $id_personaje = $_POST['id_personaje'];
                 
                $eliminarPj= "DELETE FROM personajes WHERE id_personaje = $id_personaje;";
                $eliminarPj = mysqli_query($conexion,$eliminarPj);

                header("Location: ../index.php#listaPersonajes");
            break;

            case 2:
                $campoPJ = $_POST['campo'];
                $valorPJ = $_POST['valor'];
                $nuevo_valorPJ = $_POST['nuevo_valor'];
                $id_personaje = $_POST['id_personaje'];

                // Capturar los datos del formulario
                
                echo "$campoPJ: ". $valorPJ . "=>" . $nuevo_valorPJ;
                echo "<br>id_personaje: " . $id_personaje;

                $modificarPj= "UPDATE personajes SET $campoPJ='$nuevo_valorPJ' WHERE id_personaje = $id_personaje;";
                $modificarPj = mysqli_query($conexion, $modificarPj);

            break;
        }
        ?>
    </div>
</body>