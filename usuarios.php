<?php
/*----------------------------------------------------------------------------------------------------------------*
 * MÓDULO: Gestión de Usuarios
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
$active_clientes = "";
$active_usuarios = "active";
$title = "Usuarios | Sistema";
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<?php include("head.php"); ?>
	<style>
		/* Estilos modernos para el módulo de Usuarios */
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
			background-color: #e3f2fd;
			/* Fondo azul claro */
			color: #1976d2;
			/* Icono azul */
			padding: 10px;
			border-radius: 10px;
			margin-right: 15px;
			font-size: 20px;
		}

		.btn-add-user {
			background-color: #3498db;
			color: white;
			border: none;
			padding: 10px 25px;
			border-radius: 50px;
			font-weight: 600;
			box-shadow: 0 4px 6px rgba(52, 152, 219, 0.3);
			transition: all 0.3s;
		}

		.btn-add-user:hover {
			background-color: #2980b9;
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
			margin-bottom: 30px;
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
			border-color: #3498db;
			box-shadow: 0 0 0 4px rgba(52, 152, 219, 0.1);
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
				<i class="glyphicon glyphicon-lock"></i> Gestión de Usuarios
			</h1>

			<?php if ($_SESSION['user_name'] == 'baraka') { // O tu lógica de admin ?>
				<button type="button" class="btn btn-add-user" data-toggle="modal" data-target="#myModal">
					<i class="glyphicon glyphicon-plus"></i> Nuevo Usuario
				</button>
			<?php } ?>
		</div>

		<div class="card-modern">

			<div class="toolbar">
				<form class="form-horizontal" role="form" id="datos_cotizacion">
					<div class="row">
						<div class="col-md-5">
							<div class="search-box">
								<i class="glyphicon glyphicon-search search-icon"></i>
								<input type="text" class="form-control" id="q"
									placeholder="Buscar por nombre, usuario o email..." onkeyup='load(1);'>
							</div>
						</div>
						<div class="col-md-7 text-right hidden-xs">
							<span class="text-muted" style="line-height: 48px;">
								<i class="glyphicon glyphicon-info-sign"></i>
								Administra el acceso y roles del personal.
							</span>
						</div>
					</div>
				</form>
			</div>

			<div class="card-body" style="padding: 0;">
				<div id="loader" class="text-center" style="display:none; padding: 20px;">
					<i class="fa fa-spinner fa-spin"></i> Cargando...
				</div>
				<div id="resultados"></div>
				<div class='outer_div'></div>
			</div>

		</div>

	</div>

	<?php
	include("footer.php");
	// Modales
	include("modal/registro_usuarios.php");
	include("modal/editar_usuarios.php");
	include("modal/cambiar_password.php");
	?>

	<script type="text/javascript" src="js/usuarios.js"></script>
</body>

</html>