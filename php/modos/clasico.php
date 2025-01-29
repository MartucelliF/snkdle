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
    ?>
    <?php

    //FUNCIONES ---------------------------------------------------------------------------------------------------------
    function mostrarNombre($nombre_personaje){
        include ("php/conexion.php");
        
        $consulta_array_busquedaPJ = "SELECT * FROM personajes WHERE nombre_personaje LIKE '%$nombre_personaje%'";
        $consulta_array_busquedaPJ = mysqli_query($conexion, $consulta_array_busquedaPJ);
        $numeroFilas = mysqli_num_rows($consulta_array_busquedaPJ);

        $consulta_array_busquedaPJ = mysqli_fetch_assoc($consulta_array_busquedaPJ);

        return $consulta_array_busquedaPJ;
    }

    //$campos ==> array que guardará el nombre de los campos a mostrar
    //$array_personaje ==> valores del personaje a mostrar    

    function ordenarCampos($campos, $array_personaje, $consulta_personaje){
        foreach ($campos as $campo) {
            $array_personaje[$campo] = $consulta_personaje[$campo];
        }
    
        return $array_personaje;
    }
    //-------------------------------------------------------------------------------------------------------------------

    //CONSEGUIR LOS CAMPOS ----------------------------------------------------------------------------------------------
        $consultaCampos = "DESCRIBE personajes";
        $consultaCampos = mysqli_query($conexion, $consultaCampos);
        
        //--VARIAS FILAS POR CÓMO DEVUELVE LA CONSULTA LA BD --> Ejecutarla en el phpMyAdmin y se va a entender TODO
        $consultaCamposResultado = [];
        while ($fila = mysqli_fetch_assoc($consultaCampos)) {
            $consultaCamposResultado[] = $fila;
        }

        //--Guardo únicamente el nombre de los campos
        $_SESSION['campos'] = [];
        foreach($consultaCamposResultado as $filas){
            foreach($filas as $campo => $valor){
                if($campo == "Field"){
                    $_SESSION['campos'][] = $valor;
                }
            }
        }
        
        $campos_listaPersonajes = $_SESSION['campos'];

        //ORDENAMIENTO DE ARRAY $campos
        $camposlength = count($_SESSION['campos']);

        for($i=0 ; $i<$camposlength ; $i++){
            for($j=$i+1 ; $j<$camposlength ; $j++){
                
                //PARA QUE LA "img" ESTÉ A LA IZQUIERDA
                if($_SESSION['campos'][0] != "Personaje"){
                    if($_SESSION['campos'][$j] == "Personaje"){
                        //Intercambiamos valores
                        $variableauxiliar=$_SESSION['campos'][$i];
                        $_SESSION['campos'][$i]=$_SESSION['campos'][$j];
                        $_SESSION['campos'][$j]=$variableauxiliar;
                    }
                }

                //PARA QUE EL "id_personaje" ESTÉ A LA DERECHA
                if(($_SESSION['campos'][$camposlength - 1] != "id_personaje") && ($i != 0)){
                    if($_SESSION['campos'][$i] == "id_personaje"){
                        //Intercambiamos valores
                        $variableauxiliar=$_SESSION['campos'][$i];
                        $_SESSION['campos'][$i]=$_SESSION['campos'][$j];
                        $_SESSION['campos'][$j]=$variableauxiliar;
                    }
                }
            }
        }  
    //-------------------------------------------------------------------------------------------------------------------

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
        <form action="clasico.php" method="post">
            <button>
                ADMIN
            </button>
            <input type="hidden" name="admin" value="admin_true">
        </form>
        <?php  
        }
        if($_SESSION['admin'] == "admin_true"){
        ?>
        <form action="clasico.php" method="post">
            <button>
                USUARIO
            </button>
            <input type="hidden" name="admin" value="admin_false">
        </form>
        <?php
        }
    }
    ?>

    <div class="contenedor">
        <button class="titulo" style="background-color: transparent; border: transparent">
            <a href="../../index.php">
                <img src="../../img/titulo.png" alt="titulo" width="450px" height="120px">
            </a>
        </button>

        <!-- 1° INGRESA PERSONAJE A COMPARAR -->

        <?php
        if (!isset($_SESSION['array_encontrarPJ'])) {
            $consulta_array_encontrarPJ = "SELECT * FROM personajes ORDER BY RAND() LIMIT 1;";
            $consulta_array_encontrarPJ = mysqli_query($conexion, $consulta_array_encontrarPJ);
            $consulta_array_encontrarPJ = mysqli_fetch_assoc($consulta_array_encontrarPJ);
                                
            $array_encontrarPJ = [];
            
            $array_encontrarPJ = ordenarCampos($_SESSION['campos'], $array_encontrarPJ, $consulta_array_encontrarPJ);

            $_SESSION['array_encontrarPJ'] = $array_encontrarPJ;
        }

        ?>
        <br>

        <nav class="botonBusqueda">
            <form action="clasico.php" method="post">
                <input class="botonInput" type="search" name="nombre_busqueda_pj" id="nombre_busqueda_pj" placeholder="Nombre del personaje" required>
                <button class="botonBuscar" type="submit"><i class='bx bx-chevrons-right'></i></button>
                <input type="hidden" name="pj_no_encontrado_alert" value="alert">
            </form>
        </nav>
        <br>

        <?php
        //CONTROL PARA QUE EL ALERT DEL PJ NO ENCONTRADO APAREZCA SÓLO CUANDO SE PRESIONA "BUSCAR" Y NO CADA VEZ QUE F5
        if (isset($_POST['pj_no_encontrado_alert'])) {
            // Capturar los datos del formulario
            $_SESSION['pj_no_encontrado_alert'] = "alert";
        }
        ?>
        <?php

        if(isset($_SESSION['admin']) && $_SESSION['admin'] == "admin_true"){
            ?>
            <form action="clasico.php" method="post">
                <button type="submit" class="contboton"><h3>BORRAR PERSONAJES INGRESADOS</h3></button>
                <input type="hidden" name="borrarPJIngresado">
            </form>
            <br>
            <?php
            if (isset($_POST['borrarPJIngresado'])) {
                session_start();
                unset($_SESSION['listaIngresados']); //Elimina ESPECÍFICAMENTE esa variable
                            
                header(header: "Location: clasico.php");
                ?>
                <meta http-equiv="refresh" content="0">
                <?php
                exit;
            }
        }

        if (isset($_POST['nombre_busqueda_pj'])) {
            // Capturar los datos del formulario
            $nombre_busqueda_pj = $_POST['nombre_busqueda_pj'];

            //CONSULTA PARA OBTENER LOS DATOS DEL PERSONAJE INGRESADO
            $consulta_array_busquedaPJ = "SELECT * FROM personajes WHERE Nombre LIKE '%$nombre_busqueda_pj%'";
            $consulta_array_busquedaPJ = mysqli_query($conexion, $consulta_array_busquedaPJ);
            $numeroFilas = mysqli_num_rows($consulta_array_busquedaPJ);

            $consulta_array_busquedaPJ = mysqli_fetch_assoc($consulta_array_busquedaPJ);

            if(isset($consulta_array_busquedaPJ)>0){
                
                //Define el array a mostrar al mismo tiempo que ordena cada campo según "$campos"
                $array_busquedaPJ = [];
                
                $array_busquedaPJ = ordenarCampos($_SESSION['campos'], $array_busquedaPJ, $consulta_array_busquedaPJ);

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
                <div class="subcontenedor">
                    <table>
                        <tr>
                            <?php                       
                                foreach($_SESSION['campos'] as $campoPJ) {
                                    if($campoPJ != "id_personaje"){
                                        if($campoPJ == "Primera_aparición"){
                                            ?>
                                            <th> Primera aparición </th>
                                            <?php   
                                        }else{
                                            ?>
                                            <th><?php echo $campoPJ?></th>
                                            <?php 
                                        }
                                    }      
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
        <br><br>
        
        <?php
        if(isset($_SESSION['admin']) && $_SESSION['admin'] == "admin_true"){

            ?>
            <div class="subcontenedor">
                <h1 id="pjBuscar">Personaje a Buscar</h1>
                <table>
                    <tr>
                        <tr>
                            <?php                       
                            foreach($_SESSION['campos'] as $campoPJ) {
                                ?>
                                <th><?php echo $campoPJ?></th>
                                <?php
                            } 
                            ?>
                        </tr>
                    </tr>

                    <tr>
                        <?php                       
                        foreach($_SESSION['array_encontrarPJ'] as $campoEncontrarPJ => $valorEncontrarPJ) { 
                            if($campoEncontrarPJ=="Personaje"){
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
                </table>            
            </div>

            <form action="clasico.php#listaPersonajes" method="post">
                <button type="submit" class="contboton">ACTUALIZAR</button>
                <input type="hidden" name="logout" value="logout">
            </form>
            
            <?php
            //ACTUALIZAR EL PERSONAJE DEL DÍA
            if (isset($_POST['logout'])) {
                unset($_SESSION['array_encontrarPJ']); //Elimina ESPECÍFICAMENTE esa variable
                ?>
                <meta http-equiv="refresh" content="0; url='clasico.php#listaPersonajes'" />
                <?php                
                exit;
            }
            ?>
            <!-------------------------------------->


            <br><br>

            <!-- 4° LISTA PERSONAJES ------------------>

            <?php
            //CONSULTA PARA OBTENER TODOS LOS PERSONAJES
                //GUARDÁNDOLOS EN UN ARRAY PARA EL MUESTREO DE TODOS LOS PERSONAJES
                $consulta = "SELECT * FROM personajes";
                $consultaListaPJ = mysqli_query($conexion, query: $consulta);
                
                // Almacenar todas las filas en un array
                $i=0;
                $_SESSION['listaPersonajes'] = [];
                while ($fila = mysqli_fetch_assoc(result: $consultaListaPJ)) {
                    $_SESSION['listaPersonajes'][] = $fila;
                }
                
            //------------------------------------------------------------------------
            ?>
            <div style="background-color: rgba(251, 255, 14, 0.514);">
                <h1 id="listaPersonajes">Lista Personajes</h1>
                <form action="clasico.php#listaPersonajes" method="post">
                    <button type="submit" class="contboton">+</button>
                    <input type="hidden" name="agregar_pj" value="true">
                </form>
                <?php
                
                //MANEJO DE LA SUBIDA DE PERSONAJES
                if (isset($_POST['agregar_pj'])) {
                    // Capturar los datos del formulario
                    $agregar_pj = $_POST['agregar_pj'];
                    
                }
                //Le indica a la página que se desea agregar un personaje y si la variable es "true", entonces se despliega un menú
                
                if (isset($agregar_pj) && $agregar_pj == "true") {
                    ?>
                    <br>
                    <form action="clasico.php#listaPersonajes" method="post">
                        <button type="submit" class="contboton">CANCELAR</button>
                        <input type="hidden" name="agregar_pj" value="false">
                    </form>
                    <br>
                    <form action="../gestionPersonajes.php?paso=0" method="post">
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
                        <button type="submit" class="contboton">AGREGAR PERSONAJE</button>
                        </button> 
                    </form>                
                    <br>
                    
                    <form action="../gestionPersonajes.php?paso=3" method="post">
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

                    <form action="../gestionPersonajes.php?paso=4" method="post">
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
                                foreach($campos_listaPersonajes as $campoPJ) {
                                    if($campoPJ == "Primera_aparición"){
                                        ?>
                                        <th> Primera aparición </th>
                                        <?php   
                                    }else{
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
                            foreach ($_SESSION['listaPersonajes'] as $idPJ => $fila) {
                                ?>
                                <tr id="<?php echo $id_personaje?>">
                                    <?php
                                    foreach ($fila as $campoPJ => $valorPJ) {
                                        ?>
                                        <td>
                                            <?php
                                            if($campoPJ == "id_personaje"){
                                                $id_personaje = $valorPJ;
                                            }
                                            ?>
                                            <form action="../gestionPersonajes.php?paso=2" method="post">
                                                
                                                <input type="text" name="nuevo_valor" id="nuevo_valor" placeholder="<?php echo $valorPJ; ?>" required>
                                                <button type="submit" value="Modificar" class="contboton">Modificar</button>
                                                <input type="hidden" name="campo" value="<?php echo $campoPJ; ?>">
                                                <input type="hidden" name="valor" value="<?php echo $valorPJ; ?>">
                                                
                                                    <input type="hidden" name="id_personaje" value="<?php echo $id_personaje; ?>">
                                                <?php
                                                
                                                ?>
                                            </form>
                                        </td>
                                        <?php
                                        
                                    }
                                ?>
                                </tr>
                                
                                <td>
                                    <form action="../gestionPersonajes.php?paso=1" method="post">
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
        <?php
        }
        ?>
    </div>
</body>
</html>