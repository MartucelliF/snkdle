<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>shingekinokyojindle</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" type="image/jpg" href="img/icon.png"/>

</head>
<body>
    <?php
    include ("php/conexion.php");
    session_start();
    ?>
    <?php

    //CONSULTA PARA OBTENER TODOS LOS PERSONAJES
        //GUARDÁNDOLOS EN UN ARRAY PARA EL MUESTREO DE TODOS LOS PERSONAJES
        $consulta = "SELECT * FROM personajes";
        $consultaListaPJ = mysqli_query($conexion, $consulta);
        
        // Almacenar todas las filas en un array
        $listaPersonajes = [];
        while ($fila = mysqli_fetch_assoc(result: $consultaListaPJ)) {
            $listaPersonajes[] = $fila;
        }

        //CONSEGUIR LOS CAMPOS
        $consultaCampos = "DESCRIBE personajes";
        $consultaCampos = mysqli_query($conexion, $consultaCampos);
        
        //--VARIAS FILAS POR CÓMO DEVUELVE LA CONSULTA LA BD --> Ejecutarla en el phpMyAdmin y se va a entender TODO
        $consultaCamposResultado = [];
        while ($fila = mysqli_fetch_assoc($consultaCampos)) {
            $consultaCamposResultado[] = $fila;
        }

        //--Guardo únicamente el nombre de los campos
        $_SESSION['campos'] = [];
        foreach($consultaCamposResultado as $pj){
            foreach($pj as $campopj => $valorpj){
                if($campopj == "Field"){
                    $_SESSION['campos'][] = $valorpj;
                }
            }
        }

        /*
        print_r($consultaCamposPJ);
        echo "<br><br>";
        foreach($consultaCamposPJ as $campo => $valor){
            echo "| [".$campo."] |";
        }

        echo "<br><br>";
        foreach($listaPersonajes as $pj){
            foreach($pj as $campopj => $valorpj){
                echo "| [".$campopj."] => ".$valorpj." |"; 
            }
            echo "<br>";
        }
        */
    //---------------------------------------------

    ?>

    <div class="contenedor">
        
        <!-- 1° INGRESA PERSONAJE A COMPARAR -->

        <?php
        if (!isset($_SESSION['array_encontrarPJ'])) {
            $array_encontrarPJ = "SELECT * FROM personajes ORDER BY RAND() LIMIT 1;";
            $array_encontrarPJ = mysqli_query($conexion, $array_encontrarPJ);
            $array_encontrarPJ = mysqli_fetch_assoc($array_encontrarPJ);
            
            $_SESSION['array_encontrarPJ'] = $array_encontrarPJ;
        }

        ?>
        <br>
        <span class="BUSCAR">
            <form action="index.php" method="post">
                <input type="text" name="nombre_busqueda_pj" id="nombre_busqueda_pj" placeholder="Nombre del personaje" required>
                <button type="submit" class="contboton">BUSCAR</button>
                <input type="hidden" name="pj_no_encontrado_alert" value="alert">
            </form>
            <?php
            //CONTROL PARA QUE EL ALERT DEL PJ NO ENCONTRADO APAREZCA SÓLO CUANDO SE PRESIONA "BUSCAR" Y NO CADA VEZ QUE F5
            if (isset($_POST['pj_no_encontrado_alert'])) {
                // Capturar los datos del formulario
                $_SESSION['pj_no_encontrado_alert'] = "alert";
            }
        ?>
        </span>
        
        <?php
        //BUSQUEDA DE UN PERSONAJE
        ?>
        <br>
        <form action="index.php" method="post">
            <button type="submit" class="contboton"><h3>BORRAR PERSONAJE INGRESADO</h3></button>
            <input type="hidden" name="borrarPJIngresado">
        </form>
        <br>
        <?php

        if (isset($_POST['nombre_busqueda_pj'])) {
            // Capturar los datos del formulario
            $nombre_busqueda_pj = $_POST['nombre_busqueda_pj'];

            //CONSULTA PARA OBTENER LOS DATOS DEL PERSONAJE INGRESADO
            $consulta_array_busquedaPJ = "SELECT * FROM personajes WHERE nombre_personaje LIKE '%$nombre_busqueda_pj%'";
            $consulta_array_busquedaPJ = mysqli_query($conexion, $consulta_array_busquedaPJ);
            $numeroFilas = mysqli_num_rows($consulta_array_busquedaPJ);

            $consulta_array_busquedaPJ = mysqli_fetch_assoc($consulta_array_busquedaPJ);

            if(isset($consulta_array_busquedaPJ)>0){
                $array_busquedaPJ = $consulta_array_busquedaPJ;

                if(!isset($_SESSION['listaIngresados'])){
                    
                    $_SESSION['listaIngresados'][] = $array_busquedaPJ;
                    
                }else{

                    echo "<br>";
                    $coincidencia = false;
                    foreach($_SESSION['listaIngresados'] as $idPJ => $filaPJ) {
                     
                        foreach($filaPJ as $campoBusquedaPJ => $valorBusquedaPJ){
                            if(($campoBusquedaPJ == "id_personaje")&&($array_busquedaPJ['id_personaje'] == $valorBusquedaPJ)){                                      
                                $coincidencia = true;
                            }
                          
                        }    
                    }

                    if($coincidencia == false){
                        $_SESSION['listaIngresados'][] = $array_busquedaPJ;
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

        /*
            ?>
            <h3 style="background-color: yellow;">
            <u>COMPARACIÓN 'listaIngresados' CON 'arrayBusquedaPJ'</u>
            <br><br>
            <?php

                $cont = count($_SESSION['listaIngresados']) -1;
                foreach(array_reverse($_SESSION['listaIngresados']) as $filaPJ) {
                    echo "[".($cont--)."] (";
                    echo "<br>";
                    foreach($filaPJ as $campoBusquedaPJ => $valorBusquedaPJ){
                        echo "[".$campoBusquedaPJ."] => '".$valorBusquedaPJ."'"; 
                        echo "<br>";
                    }
                    echo ")";
                    echo "<br>";
                    echo "<br>";
                }
            ?>
            </h3>
        */

        if (isset($_SESSION['listaIngresados'])) {
            
            
            //CONSULTA PARA OBTENER LOS DATOS DEL PERSONAJE A BUSCAR

                ?>
                <br>

                <div class="subcontenedorBUSCAR">
                    <div>
                        <h2>PERSONAJE INGRESADO</h2>
                    </div>

                    <br>
                    <div class="tabla">
                        <table>
                            <tr>
                                <?php                       
                                foreach($_SESSION['campos'] as $campoPJ) { 
                                    ?>
                                    <td><?php echo $campoPJ?></td>
                                    <?php
                                }
                                ?>
                            </tr>

                            <!-- COMPARACIÓN ENTRE PERSONAJE INGRESADO y PERSONAJE A BUSCAR -->
                            
                            <?php
                            foreach(array_reverse($_SESSION['listaIngresados']) as $filaPJ) {
                            ?>
                            <tr>
                                <?php
                                foreach($filaPJ as $campoBusquedaPJ => $valorBusquedaPJ){
                                    
                                    $campoActual = $campoBusquedaPJ;
                                    $valorEncontrarPJ = $_SESSION['array_encontrarPJ'][$campoActual];

                                    $coincidencia = false;
                                    if($valorBusquedaPJ==$valorEncontrarPJ){
                                        $coincidencia = true;
                                    }
                                
                                    /*
                                    echo "CampoBUSQUEDAPj [".$campoBusquedaPJ."] => ".$valorBusquedaPJ;
                                    echo "<br> campoActual = ".$campoActual;
                                    echo "<br><br>CampoENCONTRARPj [".$campoActual."] => ".$valorEncontrarPJ;
                                    echo "<br>-------------------------------------<br>";
                                    */


                                    //Para evitar que la img se muestre con algún color
                                    if($campoBusquedaPJ != "img"){
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
                            ?>
                            </tr>
                            <?php    
                            }
                            ?>
                        </table>
                    </div>
                    <br>
                </div>
            <?php
            
        }    

        
        ?>
        <br><br>

        <!-- IMPRIMIR DATOS DEL PERSONAJE A BUSCAR ----->

        
        <?php

        if (isset($_POST['borrarPJIngresado'])) {
            session_start();
            unset($_SESSION['listaIngresados']); //Elimina ESPECÍFICAMENTE esa variable
                        
            header(header: "Location: index.php");
            ?>
            <meta http-equiv="refresh" content="0">
            <?php
            exit;
        }

        ?>
        <div class="subcontenedorENCONTRAR">
            
            <h2>PERSONAJE A BUSCAR</h2>
            <table>
                <thead>
                    <tr>
                        <?php                       
                        foreach($_SESSION['campos'] as $campoPJ) { 
                            
                            ?>
                            <th><?php echo $campoPJ?></th>
                            <?php
                        }
                        ?>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <?php                       
                        foreach($_SESSION['array_encontrarPJ'] as $campoEncontrarPJ => $valorEncontrarPJ) { 
                            if($campoEncontrarPJ=="img"){
                                ?>
                                <td style="background-color: black;">
                                    <img src="<?php echo $valorEncontrarPJ?>" alt="foto_PJ" width="60px" height="60px">
                                </td>
                                <?php
                            }else{
                                ?>
                                    <td><?php echo $valorEncontrarPJ?></td>
                                <?php    
                            }
                            
                        }
                        ?>
                    </tr>
                </tbody>
            </table>
            <form action="index.php" method="post">
                <button type="submit" class="contboton">ACTUALIZAR</button>
                <input type="hidden" name="logout" value="logout">
            </form>
            
            <?php
            //ACTUALIZAR EL PERSONAJE DEL DÍA
            if (isset($_POST['logout'])) {
                session_start();
                unset($_SESSION['array_encontrarPJ']); //Elimina ESPECÍFICAMENTE esa variable
                header("Location: index.php");
                exit;
            }
            ?>

        </div>
        <input?php
        
        ?>
        <!-------------------------------------->


        <br><br>

        <!-- 4° LISTA PERSONAJES ------------------>
        <div class="subcontenedor">
            <h2 id="listaPersonajes">LISTA PERSONAJES</h2>
            <form action="index.php#listaPersonajes" method="post">
                <button type="submit" class="contboton">+</button>
                <input type="hidden" name="agregar_pj" value="true">
            </form>
            <br>
            <?php
            
            //MANEJO DE LA SUBIDA DE PERSONAJES
            if (isset($_POST['agregar_pj'])) {
                // Capturar los datos del formulario
                $agregar_pj = $_POST['agregar_pj'];
                
            }
            //Le indica a la página que se desea agregar un personaje y si la variable es "true", entonces se despliega un menú
  
            if (isset($agregar_pj) && $agregar_pj == "true") {
                echo "['agregar_pj'] = ".$agregar_pj;
                ?>

                <form action="php/gestionPersonajes.php?paso=0" method="post">
                    <fieldset>
                        
                        <?php
                        foreach($_SESSION['campos'] as $campoPJ){
                            if($campoPJ != "id_personaje"){
                                ?>      
                                <br>
                                <label for="<?php echo $campoPJ; ?>"><b><?php echo $campoPJ; ?>: </b></label>
                                <input type="text" name="<?php echo $campoPJ; ?>" id="<?php echo $campoPJ; ?>">
                                <br>
                                <input type="hidden" name="agregar_pj" value="false">
                                <input type="hidden" name="array_NuevoPJ" value="true">
                                <?php
                            }else{
                                //Defino el valor del "id_personaje" como vacio para que luego la BD lo asigne AUTO_INCREMENT
                                //Así no da error como "no definido"
                                ?>
                                <input type="hidden" name="id_personaje" value="">
                                <?php
                            }
                        }
                        ?>
                    </fieldset>
                    <?php
                    ?>
                    <br>
                    <button type="submit" class="contboton">AGREGAR</button>
                    </button> 
                </form>
                <br>
                <form action="index.php#listaPersonajes" method="post">
                    <button type="submit" class="contboton">CANCELAR</button>
                    <input type="hidden" name="agregar_pj" value="false">
                </form>
                <br>
                
                <form action="php/gestionPersonajes.php?paso=3" method="post">
                    <fieldset>
                        <h3>ALTER TABLE personajes ADD COLUMN <br>
                            <input type="text" name="nombre_columna" id="nombre_columna" placeholder="Nombre de la columna">
                            <input type="radio" name="tipo_dato" value="VARCHAR"> VARCHAR 
                            <input type="radio" name="tipo_dato" value="INT"> INT
                            <input type="text" name="longitud_dato" placeholder="Longitud del dato">
                        </h3>
                    </fieldset>
                    <?php
                    ?>
                    <button type="submit" class="contboton">AGREGAR COLUMNA</button>
                    </button> 
                    <br>
                    <br>
                </form>

                <form action="php/gestionPersonajes.php?paso=4" method="post">
                <fieldset>
                    <h3>ALTER TABLE personajes DROP COLUMN <br>
                        <input type="text" name="nombre_columna" id="nombre_columna" placeholder="Nombre de la columna">

                    </h3>
                </fieldset>
                <?php
                ?>
                <button type="submit" class="contboton">ELIMINAR COLUMNA</button>
                </button> 
                <br>
                <br>
                </form>
                <?php

            }

            
    
            ?>
            <div class="personajes">
                <table>
                    <thead>
                        <tr>
                            <?php                       
                            foreach($_SESSION['campos'] as $campoPJ) { 
                                if($campoPJ != "id_personaje"){
                                    ?>
                                    <th><?php echo $campoPJ?></th>
                                    <?php                                
                                }
                            }
                            ?>
                        </tr>
                    </thead>

                    <tbody>
                        <?php

                        // Generar las filas de la tabla

                        foreach ($listaPersonajes as $fila) {
                            ?>
                            <tr>
                                <?php
                                foreach ($fila as $campoPJ => $valorPJ) {
                                    ?>
                                    <?php
                                    if($campoPJ == "id_personaje"){
                                        $id_personaje = $valorPJ;
                                    }else{
                                    ?>
                                    <td>
                                        <form action="php/gestionPersonajes.php?paso=2" method="post">
                                            <input type="text" name="nuevo_valor" id="nuevo_valor" placeholder="<?php echo $valorPJ; ?>" required>
                                            <br>
                                            <button type="submit" value="Modificar" class="contboton">Modificar</button>
                                            <input type="hidden" name="campo" value="<?php echo $campoPJ; ?>">
                                            <input type="hidden" name="valor" value="<?php echo $valorPJ; ?>">
                                            <input type="hidden" name="id_personaje" value="<?php echo $id_personaje; ?>">
                                        </form>
                                    </td>
                                    <?php
                                    }
                                }
                            ?>
                            </tr>
                            
                            <td>
                                <form action="php/gestionPersonajes.php?paso=1" method="post">
                                    <button type="submit" value="Eliminar" class="contboton">Eliminar</button>
                                    <br><br>
                                    <input type="hidden" name="id_personaje" value="<?php echo $id_personaje?>">
                                </form>
                            </td> 
                        
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!----------------------------------->
    </div>
</body>
</html>