<?php

//declaro variables para la conexión
$sevidor = "localhost"; //el servidor que se utiliza --> phpMyAdmin utiliza localhost
$usuario = "root"; //por default, es 'root'
$contraseña = ""; //por default, la contraseña está vacía
$BD = "snkdle"; //nombre de la BD a la que me quiero vincular

//Conexión con la BD
$conexion = mysqli_connect($sevidor, $usuario, $contraseña, $BD);
//se pueden cargar los parámetros directamente, pero para que sea más entendible se implementan variables
//en ese orden, se cargan los datos
//----------------------------------

$paso=0;

//Verificar conexión
if ($conexion){ //si el valor que devuelve la función "mysqli_connect" es true...
    /*
    echo "Conexión exitosa papu!";
    
    ?>
        <!DOCTYPE html>
        <html lang="en">

            <h1> BASE DE DATOS: <?php echo $BD ?> </h1>

        </html>

    <?php
    */
} else { //si no es true, entonces es false
    echo "NT papu :(";
    die("Conexión fallida por: ". mysqli_connect_error());
}

?>