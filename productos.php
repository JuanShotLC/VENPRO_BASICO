<?php
/*----------------------------------------------------------------------------------------------------------------*
 * MÓDULO: Gestión de Productos (Inventario)
 * DISEÑO: Moderno / Clean UI
 *----------------------------------------------------------------------------------------------------------------*/
session_start();
if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) {
	header("location: login.php");
	exit;
}

require_once("config/db.php");
require_once("config/conexion.php");

$active_facturas = "";
$active_productos = "active";
$active_clientes = "";
$active_usuarios = "";
$title = "Inventario | Sistema";
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<?php include("head.php"); ?>

	<style>
		/* ESTILOS ESPECÍFICOS PARA EL MÓDULO DE PRODUCTOS */
		body {
			background-color: #f4f6f9;
			/* Fondo gris claro general */
		}

		.main-header-area {
			margin-bottom: 25px;
			display: flex;
			justify-content: space-between;
			align-items: center;
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
			margin-right: 10px;
			color: #f39c12;
			/* Color naranja para productos */
			background: #fff8e1;
			padding: 10px;
			border-radius: 8px;
			font-size: 20px;
		}

		/* Tarjeta Principal */
		.card-modern {
			background: #fff;
			border-radius: 10px;
			box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
			border: none;
			overflow: hidden;
			margin-bottom: 30px;
		}

		/* Barra de Herramientas (Search + Filters) */
		.toolbar-area {
			padding: 20px 25px;
			background-color: #fff;
			border-bottom: 1px solid #f0f0f0;
		}

		.search-modern {
			position: relative;
		}

		.search-modern input {
			height: 48px;
			border-radius: 25px;
			padding-left: 45px;
			background-color: #f8f9fa;
			border: 1px solid #e9ecef;
			box-shadow: none;
			transition: all 0.3s;
			font-size: 15px;
		}

		.search-modern input:focus {
			background-color: #fff;
			border-color: #f39c12;
			box-shadow: 0 4px 10px rgba(243, 156, 18, 0.1);
		}

		.search-modern i {
			position: absolute;
			left: 18px;
			top: 16px;
			color: #adb5bd;
			font-size: 16px;
		}

		/* Botones de Acción */
		.btn-action-group {
			text-align: right;
		}

		.btn-modern-primary {
			background-color: #3498db;
			color: white;
			border-radius: 6px;
			padding: 10px 20px;
			font-weight: 600;
			border: none;
			box-shadow: 0 4px 6px rgba(52, 152, 219, 0.2);
			transition: transform 0.2s;
		}

		.btn-modern-primary:hover {
			background-color: #2980b9;
			transform: translateY(-2px);
			color: white;
		}

		.btn-report {
			border-radius: 6px;
			padding: 10px 15px;
			margin-left: 5px;
			border: 1px solid #dee2e6;
			background: white;
			color: #6c757d;
			font-weight: 500;
		}

		.btn-report:hover {
			background-color: #f8f9fa;
			color: #343a40;
		}

		/* Contenedor de la Tabla */
		.table-responsive-wrapper {
			padding: 0 15px;
		}

		#loader {
			padding: 20px;
		}
	</style>
</head>

<body>
	<?php include("navbar.php"); ?>

	<div class="container-fluid" style="margin-top: 80px;">
		<div class="row main-header-area">
			<div class="col-md-6 col-sm-6 col-xs-12">
				<h1 class="page-title">
					<i class="glyphicon glyphicon-barcode"></i> Gestión de Productos
				</h1>
			</div>
			<div class="col-md-6 col-sm-6 col-xs-12 text-right">
				<button type="button" class="btn btn-modern-primary" data-toggle="modal" data-target="#nuevoProducto">
					<i class="glyphicon glyphicon-plus"></i> Nuevo Producto
				</button>
			</div>
		</div>

		<div class="card-modern">

			<div class="toolbar-area">
				<form class="form-horizontal" role="form" id="datos_cotizacion">
					<div class="row">
						<div class="col-md-5 col-sm-6">
							<div class="form-group search-modern" style="margin-bottom: 0;">
								<i class="glyphicon glyphicon-search"></i>
								<input type="text" class="form-control" id="q"
									placeholder="Buscar por código o nombre del producto..." onkeyup='load(1);'>
							</div>
						</div>

						<div class="col-md-7 col-sm-6 text-right btn-action-group hidden-xs">
							<span class="text-muted" style="margin-right: 10px;">Reportes:</span>

							<a href="reportes/reporte_inventario.php" target="_blank" class="btn btn-report"
								title="Descargar Inventario PDF">
								<i class="glyphicon glyphicon-file"></i> Stock
							</a>

							<a href="reportes/reporte_existencia_minima.php" target="_blank" class="btn btn-report"
								title="Productos con Stock Bajo">
								<i class="glyphicon glyphicon-alert text-danger"></i> Stock Bajo
							</a>
						</div>
					</div>
				</form>
			</div>

			<div class="table-responsive-wrapper">
				<div id="loader" class="text-center"></div>
				<div class='outer_div'></div>
			</div>

		</div>
	</div>

	<?php
	include("footer.php");
	// Modales
	include("modal/registro_productos.php");
	include("modal/editar_productos.php");
	?>

	<script type="text/javascript" src="js/productos.js"></script>
</body>

</html>