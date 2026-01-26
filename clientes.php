<?php
/*----------------------------------------------------------------------------------------------------------------*
 * MÓDULO: Gestión de Clientes
 * DISEÑO: Moderno / Professional UI
 *----------------------------------------------------------------------------------------------------------------*/
session_start();
if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) {
	header("location: login.php");
	exit;
}

require_once("config/db.php");
require_once("config/conexion.php");

$active_facturas = "";
$active_productos = "";
$active_clientes = "active";
$active_usuarios = "";
$title = "Clientes | VENPRO";
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<?php include("head.php"); ?>
	<style>
		/* Estilos modernos específicos para Clientes */
		body {
			background-color: #f4f6f9;
		}

		.main-header {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-bottom: 25px;
		}

		.page-title {
			font-size: 24px;
			font-weight: 700;
			color: #343a40;
			margin: 0;
			display: flex;
			align-items: center;
		}

		.page-title i {
			background-color: #e8f5e9;
			color: #2ecc71;
			padding: 10px;
			border-radius: 10px;
			margin-right: 15px;
			font-size: 20px;
		}

		.btn-add-client {
			background-color: #2ecc71;
			color: white;
			border: none;
			padding: 10px 25px;
			border-radius: 50px;
			font-weight: 600;
			box-shadow: 0 4px 6px rgba(46, 204, 113, 0.2);
			transition: all 0.3s;
		}

		.btn-add-client:hover {
			background-color: #27ae60;
			transform: translateY(-2px);
			color: white;
			text-decoration: none;
		}

		.card-modern {
			background: #fff;
			border-radius: 12px;
			box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
			border: none;
			overflow: hidden;
		}

		.toolbar {
			padding: 20px 25px;
			background: #fff;
			border-bottom: 1px solid #f1f1f1;
		}

		.search-box {
			position: relative;
		}

		.search-box input {
			height: 48px;
			border-radius: 30px;
			padding-left: 45px;
			border: 1px solid #e0e0e0;
			background-color: #f8f9fa;
			width: 100%;
			transition: all 0.3s;
		}

		.search-box input:focus {
			background-color: #fff;
			border-color: #2ecc71;
			box-shadow: 0 0 0 4px rgba(46, 204, 113, 0.1);
		}

		.search-icon {
			position: absolute;
			left: 18px;
			top: 16px;
			color: #aaa;
		}
	</style>
</head>

<body>
	<?php include("navbar.php"); ?>

	<div class="container-fluid">

		<div class="main-header">
			<h1 class="page-title">
				<i class="glyphicon glyphicon-user"></i> Directorio de Clientes
			</h1>
			<button type="button" class="btn btn-add-client" data-toggle="modal" data-target="#nuevoCliente">
				<i class="glyphicon glyphicon-plus"></i> Nuevo Cliente
			</button>
		</div>

		<div class="card-modern">

			<div class="toolbar">
				<form class="form-horizontal" role="form" id="datos_cotizacion">
					<div class="row">
						<div class="col-md-6">
							<div class="search-box">
								<i class="glyphicon glyphicon-search search-icon"></i>
								<input type="text" class="form-control" id="q"
									placeholder="Buscar por nombre, RIF o correo..." onkeyup='load(1);'>
							</div>
						</div>
						<div class="col-md-6 text-right hidden-xs">
							<span class="text-muted" style="line-height: 48px;">
								<i class="glyphicon glyphicon-info-sign"></i> Administra tu base de datos de contactos
							</span>
						</div>
					</div>
				</form>
			</div>

			<div class="card-body" style="padding: 0;">
				<div id="loader" class="text-center" style="padding: 20px; display:none;">
					<i class="fa fa-spinner fa-spin"></i> Cargando...
				</div>
				<div class="outer_div"></div>
			</div>
		</div>

	</div>

	<?php
	include("footer.php");
	// Incluir Modales
	include("modal/registro_clientes.php");
	include("modal/editar_clientes.php");
	?>

	<script type="text/javascript" src="js/clientes.js"></script>
	<script>
		// Carga inicial
		$(document).ready(function () {
			load(1);
		});
	</script>
</body>

</html>