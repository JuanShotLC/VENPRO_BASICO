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
	$active_cotizacion="active";
	$active_clientes="";
	$active_usuarios="";	
	$title="Cotizacion | SIFONELC";

?>

<?php if($_SESSION['user_name'] == 'baraka') { ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php include("head.php");?>

		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>

        
  </head>
  <body>
	<?php
	include("navbar.php");


      
	?>
    <div class="container">
    <div class="panel panel-success">
    <div class="panel-heading">

      <h4><i class='glyphicon glyphicon-search'></i> Cotización </h4>
    </div>      
      <div class="panel-body">
      <?php
      include("modal/editar_cotizacion.php");

      ?>
  

      <form class="form-horizontal" role="form" id="datos_usuarios">
        
            <div class="form-group row">

              <div class="col-md-5">
                <input type="text" class="form-control" id="q" placeholder="Nombre" onkeyup='load(1);'>
              </div>
              
              
              
              <div class="col-md-3">
                <button type="button" class="btn btn-success" onclick='load(1);'>
                  <span class="glyphicon glyphicon-search" ></span> Buscar</button>
                <span id="loader"></span>
              </div>
              
            </div>
        
        
        
      </form>
        <div id="resultados"></div><!-- Carga los datos ajax -->
        <div class='outer_div'></div><!-- Carga los datos ajax -->

</div>

    </div>

  </div>
	<hr>
	<?php
	include("footer.php");
	?>

	 <script type="text/javascript" src="js/cotizacion.js"></script>

  </body>
</html>
<script>

$( "#editar_cotizacion" ).submit(function( event ) {
  $('#actualizar_datos2').attr("disabled", true);
  
 var parametros = $(this).serialize();
   $.ajax({
      type: "POST",
      url: "ajax/editar_cotizacion.php",
      data: parametros,
       beforeSend: function(objeto){
        $("#resultados_ajax2").html("Mensaje: Cargando...");
        },
      success: function(datos){
      $("#resultados_ajax2").html(datos);
      $('#actualizar_datos2').attr("disabled", false);
      load(1);
      }
  });
  event.preventDefault();
})


      
  
</script>
<?php }         ?>