<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>shingekinokyojindle</title>
    <link rel="stylesheet" href="../../css/styles.css">
    <link rel="icon" type="image/jpg" href="../../img/icon.png"/>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

</head>
<body>
    
    <?php
    include ("../conexion.php");
    session_start();

    /*--FUNCIONES--------------------------------*/
    
    /*------------------------------------------*/
    
    /*--ADMIN / USUARIO------------------------------------*/
    if (isset($_POST['admin'])) {
        // Capturar los datos del formulario
        $_SESSION['admin'] = $_POST['admin'];
    }
    if(!isset($_SESSION['admin'])){
        $_SESSION['admin'] = "admin_false";
    }
    
    if(isset($_SESSION['admin'])){
        if($_SESSION['admin'] == "admin_false"){
        ?>
        <form action="frase.php" method="post">
            <button>
                ADMIN
            </button>
            <input type="hidden" name="admin" value="admin_true">
        </form>
        <?php  
        }
        if($_SESSION['admin'] == "admin_true"){
        ?>
        <form action="frase.php" method="post">
            <button>
                USUARIO
            </button>
            <input type="hidden" name="admin" value="admin_false">
        </form>
        <?php
        }
    }
    /*------------------------------------------------------*/


    if(!isset($_SESSION['frase_encontrarPJ'])){
        $consulta_frase_encontrarPJ = "SELECT Nombre, frase FROM personajes WHERE Frase != '' ORDER BY RAND() LIMIT 1;";
        $consulta_frase_encontrarPJ = mysqli_query($conexion, $consulta_frase_encontrarPJ);
        $consulta_frase_encontrarPJ = mysqli_fetch_assoc($consulta_frase_encontrarPJ);
                            
        $frase_encontrarPJ = [];

        foreach ($consulta_frase_encontrarPJ as $campo => $valor) {
            $_SESSION['$frase_encontrarPJ'][$campo] = $consulta_frase_encontrarPJ[$campo];
        }
    }
    ?>
    
    <div class="contenedor">
        <button class="titulo" style="background-color: transparent; border: transparent">
            <a href="../../index.php">
                <img src="../../img/titulo.png" alt="titulo" width="450px" height="120px">
            </a>
        </button>

        <div class="rectanguloFrase">
            <h2>¿Qué personaje dice...?</h2>
            <p><i>"<?php echo $_SESSION['frase_encontrarPJ']['Frase']?>"</i></p>        
        </div>
        <br>
        <nav class="botonBusqueda">
            <form action="frase.php" method="post">
                <input class="botonInput" type="search" name="frase_busqueda_pj" id="frase_busqueda_pj" placeholder="Nombre del personaje" required>
                <button class="botonBuscar" type="submit"><i class='bx bx-chevrons-right'></i></button>
                <input type="hidden" name="pj_no_encontrado_alert" value="alert">
            </form>
        </nav>
        <br>
        <?php
        if (isset($_POST['frase_busqueda_pj'])) {
            // Capturar los datos del formulario
            $frase_busqueda_pj = $_POST['frase_busqueda_pj'];

            //CONSULTA PARA OBTENER LOS DATOS DEL PERSONAJE INGRESADO
            $consulta_frase_busquedaPJ = "SELECT Personaje, Nombre, id_personaje FROM personajes WHERE Nombre LIKE '%$frase_busqueda_pj%'";
            $consulta_frase_busquedaPJ = mysqli_query($conexion, $consulta_frase_busquedaPJ);
            $numeroFilas = mysqli_num_rows($consulta_frase_busquedaPJ);

            $consulta_frase_busquedaPJ = mysqli_fetch_assoc($consulta_frase_busquedaPJ);

            if(isset($consulta_frase_busquedaPJ)>0){
                
                //Define el array a mostrar al mismo tiempo que ordena cada campo según "$campos"
                $frase_busquedaPJ = [];
                
                foreach ($consulta_frase_busquedaPJ as $campo => $valor) {
                    $frase_busquedaPJ[$campo] = $consulta_frase_busquedaPJ[$campo];
                }
                
                if(!isset($_SESSION['listaIngresados_frase'])){
                    
                    $_SESSION['listaIngresados_frase'][] = $frase_busquedaPJ;
                    
                }else{

                    echo "<br>";
                    $coincidencia = false;
                    foreach($_SESSION['listaIngresados_frase'] as $idPJ => $filaPJ) {
                     
                        foreach($filaPJ as $campoBusquedaPJ => $valorBusquedaPJ){
                            if(($campoBusquedaPJ == "id_personaje")&&($frase_busquedaPJ['id_personaje'] == $valorBusquedaPJ)){                                      
                                $coincidencia = true;
                            }
                          
                        }    
                    }

                    if($coincidencia == false){
                        $_SESSION['listaIngresados_frase'][] = $frase_busquedaPJ;
                    }
                    ?>
                <?php
                }
                
            }
            else if(($numeroFilas < 1) && (($_SESSION['pj_no_encontrado_alert']) == "alert")){
                ?>
                <script>
                    alert('No se ha encontrado ningún personaje con el nombre ingresado.');
                </script>
                <?php

                $_SESSION['pj_no_encontrado_alert']="no_alert";
                ?>
                <meta http-equiv="refresh" content="0">
                <?php
                
            }
        }

        /*--BORRAR PERSONAJES INGRESADOS-------------------------------------*/
        if(isset($_SESSION['admin']) && $_SESSION['admin'] == "admin_true"){
            ?>
            <form action="frase.php" method="post">
                <button type="submit" class="contboton"><h3>BORRAR PERSONAJES INGRESADOS</h3></button>
                <input type="hidden" name="borrarPJIngresado">
            </form>
            <br>
            <?php
            if (isset($_POST['borrarPJIngresado'])) {
                session_start();
                unset($_SESSION['listaIngresados_frase']); //Elimina ESPECÍFICAMENTE esa variable
                            
                header(header: "Location: frase.php");
                ?>
                <meta http-equiv="refresh" content="0">
                <?php
                exit;
            }
        }
        /*-------------------------------------------------------------------*/
        
        if (isset($_SESSION['listaIngresados_frase'])) {
            
            //CONSULTA PARA OBTENER LOS DATOS DEL PERSONAJE A BUSCAR
                ?>
                <div class="subcontenedor">
                    <table>
                        <tr>
                            <td>Personaje</td>
                            <td>Nombre</td>
                        </tr>

                        <!-- COMPARACIÓN ENTRE PERSONAJE INGRESADO y PERSONAJE A BUSCAR -->
                        <?php
                        foreach(array_reverse($_SESSION['listaIngresados_frase']) as $filaPJ) {
                        ?>
                        <tr>
                            <?php
                            foreach($filaPJ as $campoBusquedaPJ => $valorBusquedaPJ){
                                
                                $campoActual = $campoBusquedaPJ;
                                $valorEncontrarPJ = $_SESSION['frase_encontrarPJ']['Nombre'];
                                
                                $coincidencia = false;
                                if($valorBusquedaPJ==$valorEncontrarPJ){
                                    $coincidencia = true;
                                }
                            
                                //Para evitar que la img se muestre con algún color
                                if($campoBusquedaPJ != "id_personaje"){
                                    if($campoBusquedaPJ != "Personaje"){
                                        if($coincidencia==true){
                                            ?>
                                            <td style="background-color: greenyellow;"><?php echo $valorBusquedaPJ?></td>
                                            <?php
                                        }else{
                                            ?>
                                            <td style="background-color: red;"><?php echo $valorBusquedaPJ?></td>
                                            <?php
                                        }  
                                    }else{
                                        ?>
                                        <td style="background-color: black;">
                                            <img src="<?php echo $valorBusquedaPJ?>" alt="foto_PJ" width="60px" height="60px">
                                        </td>
                                        <?php
                                    }
                                }
                            }
                        ?>
                        </tr>
                        <?php    
                        }
                        ?>
                    </table>
                </div>
            <?php
            
        }
        ?>
    </div>
</body>
</html>