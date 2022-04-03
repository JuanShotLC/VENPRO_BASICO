<?php
    require('../fpdf/fpdf.php');
        /* Connect To Database*/
    require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
    require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
    //Archivo de funciones PHP
    include('../ajax/is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
    include("../funciones.php");

 
    date_default_timezone_set('America/Caracas'); 
    setlocale(LC_ALL, 'es_ES');

$sql=mysqli_query($con,"
INSERT INTO eliminado_n
SELECT * FROM nota_venta");

$sql1=mysqli_query($con,"
TRUNCATE nota_venta"); 




   header("location: ../perfil.php");


?>

