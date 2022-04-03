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
	$active_precio="active";
	$active_cotizacion="";
	$active_clientes="";
	$active_usuarios="";	
	$title="Precios | SIFONELC";


$produ="SELECT * FROM products,cotizacion";
$resProduts=mysqli_query($con,$produ);
?>
<?php if($_SESSION['user_name'] == 'baraka') { ?>

<html lang="es">

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
		 <?php include("head.php");?>


		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>


	</head>

	<body>
		<?php
	include("navbar.php");


      
	?>

	 <div class="container">
		<header>
			<div class="alert alert-success">
			<h2>Actualizar precios masivo</h2>
			</div>
		</header>

		<section>


			<form method="post">

				<?php

			if(isset($_POST['actualizar']))
			{
				foreach ($_POST['idpro'] as $ids) 
				{
					$editID=mysqli_real_escape_string($con, $_POST['idpro2'][$ids]);
					$editCod=mysqli_real_escape_string($con, $_POST['cod'][$ids]);
					$editpre_d=mysqli_real_escape_string($con, $_POST['pre_d'][$ids]);
					$editdola_b=mysqli_real_escape_string($con, $_POST['dolar_b'][$ids]);
					$editprecio_b=mysqli_real_escape_string($con, $_POST['precio_b'][$ids]);



					$actualizar=mysqli_query($con,"UPDATE products SET id_producto='$editID',  codigo_producto='$editCod', precio_dolar='$editpre_d', dolar_boli='$editdola_b', precio_producto=($editpre_d*$editdola_b)  WHERE id_producto='$ids'");
				}

				if($actualizar==true)
				{

					 header("Location: " . $_SERVER['REQUEST_URI']);
   exit();
					//echo "<div class='alert alert-success'>
  //<strong>Precios Actualizados Actualizados!</strong> <a href='actualizar_precios.php'>CLICK AQUÍ</a>
//</div>    ";
				}

				else
				{
					echo "<div class='alert alert-danger'>
  <strong>Error Al actualizar los precios</strong> 
</div>";
				}
			}

		?>


<table class="table table-bordered" >
<tr>
					<th>
						<input type="submit" name="actualizar" value="Actualizar Precios" class="btn btn-success" />
					</th>

</tr>
				<tr>
					<th>ID_pruductos</th>
					<th>Codigo</th>
					<th>$</th>
					<th>Precio bs</th>
					<th>Precio Unitario</th>
					



				</tr>


				<?php

				while ($registroProductos = mysqli_fetch_array($resProduts))

				{

					echo'<tr>

						<td hidden><input name="idpro[]" value="'.$registroProductos['id_producto'].'" readonly/></td>


						 <td><input name="idpro2['.$registroProductos['id_producto'].']" value="'.$registroProductos['id_producto'].'" readonly/></td>

						 <td><input name="cod['.$registroProductos['id_producto'].']" value="'.$registroProductos['codigo_producto'].'" readonly/></td>

						 <td><input name="pre_d['.$registroProductos['id_producto'].']" value="'.$registroProductos['precio_dolar'].'"readonly /></td>

						

						 <td><input name="dolar_b['.$registroProductos['id_producto'].']" value="'.$registroProductos['precio'].'" readonly/></td>

						  <td><input name="precio_b['.$registroProductos['id_producto'].']" value="'.$registroProductos['precio_producto'].'" readonly/></td> 
							

						  </tr>';
						
						 
				}


				?>

			</table>

		</form>

		

		</section>
</div>
		<footer>
		</footer>
	</body>

</html>


<?php }         ?>