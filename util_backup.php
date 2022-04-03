<?php

  session_start();
  if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) {
        header("location: login.php");
    exit;
        }

  /* Connect To Database*/
  require_once ("config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
  require_once ("config/conexion.php");//Contiene funcion que conecta a la base de datos
  $active_facturas="";
  $active_productos="";
  $active_clientes="";
  $active_usuarios="active";  
  $title="Usuarios | SIFONELC";
?>
<?php if($_SESSION['user_name'] == 'baraka') { ?>

<!DOCTYPE html>
<html lang="en">
  <head>
  <?php include("head.php");?>
  </head>
  <body>
  <?php
  include("navbar.php");
  ?> 

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Respaldo de la Base de Datos.
            <small></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="inicio.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Respaldo de BD</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
         <!-- Your Page Content Here -->
         <div class='col-md-12'>
         <div class='box box-primary'>
         <div class='box-header'>
          <h3>Utilidad para generar respaldo de la Base de Datos.</h3>
         </div>
         <div class='box-footer'>
         <button type='button' class='btn btn-primary pull-right' onclick="respalda();" id='btn-genera'><i class='fa fa-thumbs-up'></i> Generar Respaldo.</button>
         </div>
         </div>
         </div>
         <div class='col-md-6'>
         <div id='respuesta'></div>
         </div>
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
  <hr>

    <script src="js/utilidades.js"></script>
  <?php
  include("footer.php");
  ?>


  </body>
</html><?php }         ?>