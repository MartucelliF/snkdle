<?php
include ("conexion.php");
session_start();
?>

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
                ?>
                <h3 style="background-color: yellow;"> 
                <?php
                    //MANEJO DE LA SUBIDA DE PERSONAJES
                    if (isset($_POST['array_NuevoPJ'])) {
                        // Capturar los datos del formulario
                        $array_NuevoPJ = $_POST['array_NuevoPJ'];
                        
                        $array_NuevoPJ = [];
                        
                        foreach($_SESSION['campos'] as $campoPJ){
                            $array_NuevoPJ[$campoPJ] = $_POST[$campoPJ];
                        }

                        $insertPJ_parte1= "INSERT INTO `personajes`(`id_personaje`, `nombre_personaje`, `genero_personaje`, `img`) VALUES ('','','','')";
                        $insertPJ_parte1 = mysqli_query($conexion, $insertPJ_parte1);

                        foreach($array_NuevoPJ as $campoPJ => $valorPJ) {
                            $insertPJ_parte2= "UPDATE `personajes` SET $campoPJ='$valorPJ' WHERE id_personaje = (SELECT MAX(id_personaje) FROM personajes)";
                            $insertPJ_parte2 = mysqli_query($conexion, $insertPJ_parte2);
                        }

                        echo "<br><br>";
                    }
                ?>
                </h3>
                <?php
                
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

                header("Location: ../index.php#listaPersonajes");

            break;

            case 3:
                $nombre_columna = $_POST['nombre_columna'];
                $tipo_dato = $_POST['tipo_dato'];
                $longitud_dato = $_POST['longitud_dato'];

                echo $nombre_columna;
                echo $tipo_dato;
                echo $longitud_dato;

                // Capturar los datos del formulario
                $agregarColumna= "ALTER TABLE personajes ADD COLUMN $nombre_columna $tipo_dato($longitud_dato) NOT NULL;";
                $agregarColumna = mysqli_query($conexion, $agregarColumna);

                header("Location: ../index.php");

            break;

            case 4:
                $nombre_columna = $_POST['nombre_columna'];

                // Capturar los datos del formulario
                $eliminarColumna= "ALTER TABLE personajes DROP COLUMN $nombre_columna;";
                $eliminarColumna = mysqli_query($conexion, $eliminarColumna);

                header("Location: ../index.php");

            break;
        }
        ?>
    </div>
</body>