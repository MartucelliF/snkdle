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
    //ACTUALIZAR EL PERSONAJE DEL DÍA
    if (isset($_GET['logout'])) {
        session_start();
        unset($_SESSION['array_encontrarPJ']); //Elimina ESPECÍFICAMENTE esa variable
        header("Location: index.php"); // Redirigir con indicador de sesión cerrada
    }

    //CONSULTA PARA OBTENER TODOS LOS PERSONAJES
        //GUARDÁNDOLOS EN UN ARRAY PARA EL MUESTREO DE TODOS LOS PERSONAJES
        $consulta = "SELECT * FROM personajes";
        $consultaListaPJ = mysqli_query($conexion, $consulta);
        // Almacenar todas las filas en un array
        $listaPersonajes = [];
        while ($fila = mysqli_fetch_assoc($consultaListaPJ)) {
            $listaPersonajes[] = $fila;
        }

        //SIN GUARDARLOS EN UN ARRAY
        $consultaCamposPJ = mysqli_query($conexion, $consulta);
        $consultaCamposPJ = mysqli_fetch_assoc($consultaCamposPJ);
    //---------------------------------------------

    //DEFINO VARIABLES 
        //MANEJO DE LA SUBIDA DE PERSONAJES
        $agregar_pj="";
        if (isset($_POST['agregar_pj'])) {
            // Capturar los datos del formulario
            $agregar_pj = $_POST['agregar_pj'];
        }
        //Le indica a la página que se desea agregar un personaje y si la variable es "true", entonces se despliega un menú 

        //BUSQUEDA DE UN PERSONAJE

        $nombre_busqueda_pj="";
        if (isset($_POST['nombre_busqueda_pj'])) {
            // Capturar los datos del formulario
            $nombre_busqueda_pj = $_POST['nombre_busqueda_pj'];
            $_SESSION['nombre_busqueda_pj'] = $nombre_busqueda_pj;
        }

        //En la primera variable guardo el valor del personaje que ingresó el usuario.
        //En la segunda variable le avisa a la página que se buscó X personaje, para habilitar el muestreo de la interfaz
    //---------------

    ?>

    <div class="contenedor">
        
        <!-- 1° INGRESA PERSONAJE A COMPARAR -->

        <?php

        ?>
        <h1 style="color:red; background-color: black;">
            <?php echo $_SESSION['nombre_busqueda_pj'] ?>
        </h1>
        <?php ;
        ?>
        <form action="index.php" method="post">
            <input type="text" name="nombre_busqueda_pj" id="nombre_busqueda_pj" placeholder="Nombre del personaje" required>
            <button type="submit" class="contboton">BUSCAR</button>
        </form>

        <br>

        <?php

        if (isset($_SESSION['nombre_busqueda_pj'])) {
            ?>
            <h1 style="color:red; background-color: black;">
                <?php echo $_SESSION['nombre_busqueda_pj'] ?>
                <?php echo "ENTRA EN EL PRIMER IF" ?>
            </h1>
            <?php ;

            //CONSULTA PARA OBTENER LOS DATOS DEL PERSONAJE INGRESADO
            $nombre_busqueda_pj = $_SESSION['nombre_busqueda_pj'];

            $array_busquedaPJ = "SELECT * FROM personajes WHERE nombre_personaje LIKE '%$nombre_busqueda_pj%'";
            $array_busquedaPJ = mysqli_query($conexion, $array_busquedaPJ);
            $array_busquedaPJ = mysqli_fetch_assoc($array_busquedaPJ);

            $_SESSION['array_busquedaPJ'] = $array_busquedaPJ;

            //CONSULTA PARA OBTENER LOS DATOS DEL PERSONAJE A BUSCAR
            if (!isset($_SESSION['array_encontrarPJ'])) {
                $array_encontrarPJ = "SELECT * FROM personajes ORDER BY RAND() LIMIT 1;";
                $array_encontrarPJ = mysqli_query($conexion, $array_encontrarPJ);
                $array_encontrarPJ = mysqli_fetch_assoc($array_encontrarPJ);
                
                $_SESSION['array_encontrarPJ'] = $array_encontrarPJ;
                
            }

            if(($_SESSION['array_busquedaPJ'])>0){
            ?>
                <div class="subcontenedorBUSCAR">
                    <h2>PERSONAJE INGRESADO</h2>

                    <table class="busqueda">
                        <thead>
                        <tr>
                            <?php                       
                            foreach($consultaCamposPJ as $campoPJ => $valorPJ) { 
                                ?>
                                <th><?php echo $campoPJ?></th>
                                <?php
                            }
                            ?>
                        </tr>
                        </thead>

                        <tbody>
                                
                            <tr>
                                <!-- COMPARACIÓN ENTRE PERSONAJE INGRESADO y PERSONAJE A BUSCAR -->
                                <?php
                               
                                foreach($_SESSION['array_busquedaPJ'] as $campoBusquedaPJ => $valorBusquedaPJ) {        

                                    $coincidencia = false;

                                    foreach($_SESSION['array_encontrarPJ'] as $campoEncontrarPJ => $valorEncontrarPJ) { 

                                        if($valorBusquedaPJ==$valorEncontrarPJ){
                                            
                                            $coincidencia =true;
 
                                        }else{
                                            ?>
                                            <?php
                                        }
                                    }
                                    

                                    if($coincidencia==true){
                                        if($campoBusquedaPJ == "img"){
                                            ?>
                                            <td><img src="<?php echo $valorBusquedaPJ?>" alt="foto_PJ" width="50px" height="50px"></td>
                                            <?php
                                        }else{
                                            ?>
                                            <td style="background-color: greenyellow;"><?php echo $valorBusquedaPJ?></td>
                                            <?php
                                        }  
                                    }else{
                                        if($campoBusquedaPJ == "img"){
                                            ?>
                                            <td><img src="<?php echo $valorBusquedaPJ?>" alt="foto_PJ" width="50px" height="50px"></td>
                                            <?php
                                        }else{
                                            ?>
                                            <td style="background-color: red;"><?php echo $valorBusquedaPJ?></td>
                                            <?php
                                        }  
                                    }
                                    ?>
                                    <?php
                                }
                                
                                ?>
                            </tr>
                                
                        </tbody>
                    </table>
                </div>
            <?php
            }else{
                ?>
                <script>
                    alert('No se ha encontrado ningún personaje con el nombre ingresado.');
                </script>
                <?php
            }
        }    
        ?>
        <br><br>

        <!-- IMPRIMIR DATOS DEL PERSONAJE A BUSCAR ----->
        <?php
        ?>
        <div class="subcontenedorENCONTRAR">
            
            <h2>PERSONAJE A BUSCAR</h2>
            <table class="busqueda">
                <thead>
                    <tr>
                        <?php                       
                        foreach($consultaCamposPJ as $campoPJ => $valorPJ) { 
                            
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
                            ?>
                            <td><?php echo $valorEncontrarPJ?></td>
                            <?php
                        }
                        ?>
                    </tr>
                </tbody>
            </table>
            <form action="index.php" method="get">
                <button type="submit" class="contboton">ACTUALIZAR</button>
                <input type="hidden" name="logout" value="logout">
            </form>

        </div>
        <?php
        
        ?>
        <!-------------------------------------->


        <br><br>

        <!-- 4° LISTA PERSONAJES ------------------>
        <div class="subcontenedor">
            <h2 id="listaPersonajes">LISTA PERSONAJES</h2>
            <form action="index.php" method="post">
                    <button type="submit" class="contboton">+</button>
                    <input type="hidden" name="agregar_pj" value="true">
                </form>
            <?php
                
            if($agregar_pj=="true"){
            ?>
                <form action="php/gestionPersonajes.php?paso=0" method="post">
                    <fieldset>
                        <label for="correo_usuario"><b>Nombre: </b></label>
                        <input type="text" name="nombre_personaje" id="nombre_personaje" placeholder="Nombre Apellido" required>
                        <br>
                        <label><b>Género: </b></label>
                        <label>Masculino</label>
                        <input type="radio" name="genero_personaje" value="Masculino">
                        <label>Femenino</label>
                        <input type="radio" name="genero_personaje" value="Femenino">

                        <input type="hidden" name="agregar_pj" value="false">
                        <br>
                    </fieldset>
                    <br>               
                    <button type="submit" class="contboton">AGREGAR</button>
                </form>
            <?php    
            }
                
                ?>
            <div class="personajes">
                <table>
                    <thead>
                        <tr>
                            <?php                       
                            foreach($consultaCamposPJ as $campoPJ => $valorPJ) { 
                                
                                ?>
                                <th><?php echo $campoPJ?></th>
                                <?php
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
                                    }
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