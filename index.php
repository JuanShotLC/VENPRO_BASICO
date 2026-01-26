<?php
session_start();
if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) {
	header("location: login.php");
	exit;
}

/* Connect To Database*/
require_once("config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
require_once("config/conexion.php");//Contiene funcion que conecta a la base de datos

// Activamos la pestaña de facturas en el navbar
$active_facturas = "active";
$active_productos = "";
$active_clientes = "";
$active_usuarios = "";
$title = "Facturación | Sistema";
?>
<!DOCTYPE html>
<html ng-app="scotchApp" lang="es">

<head>
	<?php include("head.php"); ?>
</head>

<body ng-controller="mainController">
	<?php include("navbar.php"); ?>

	<div>
		<script type="text/javascript">
			try { ace.settings.check('main-container', 'fixed') } catch (e) { }
		</script>

		<!-- MOSTAR MODULOS  -->

		<div class="main-content ng-view" id="main-container"></div>

		<!-- MOSTAR MODULOS  -->


		<script type="text/javascript">
			window.jQuery || document.write("<script src='dist/js/jquery.min.js'>" + "<" + "/script>");

		</script>

		<script type="text/javascript">
			if ('ontouchstart' in document.documentElement) document.write("<script src='dist/js/jquery.mobile.custom.min.js'>" + "<" + "/script>");
		</script>



		<script src="dist/js/jquery-ui.min.js"></script>
		<script src="dist/js/jquery.ui.touch-punch.min.js"></script>
		<script src="dist/js/jquery.easypiechart.min.js"></script>
		<script src="dist/js/jquery.sparkline.min.js"></script>
		<script type="text/javascript" src="https://code.highcharts.com/highcharts.js"></script>
		<script type="text/javascript" src="https://code.highcharts.com/modules/exporting.js"></script>

		<script src="dist/js/fileinput.js" type="text/javascript"></script>
		<script src="dist/js/bootstrap.min.js"></script>
		<script src="dist/js/jquery.form.js"></script>
		<script src="dist/js/chosen.jquery.min.js"></script>

		<script src="dist/js/jquery.validate.min.js"></script>
		<script src="dist/js/jquery.gritter.min.js"></script>
		<script src="dist/js/bootbox.min.js"></script>
		<script src="dist/js/fuelux/fuelux.wizard.min.js"></script>
		<script src="dist/js/additional-methods.min.js"></script>

		<script src="dist/js/jquery.hotkeys.min.js"></script>
		<script src="dist/js/bootstrap-wysiwyg.min.js"></script>
		<script src="dist/js/select2.min.js"></script>
		<script src="dist/js/fuelux/fuelux.spinner.min.js"></script>
		<script src="dist/js/fuelux/fuelux.tree.min.js"></script>
		<script src="dist/js/x-editable/bootstrap-editable.min.js"></script>
		<script src="dist/js/x-editable/ace-editable.min.js"></script>
		<script src="dist/js/jquery.maskedinput.min.js"></script>
		<script src="dist/js/bootbox.min.js"></script>
		<script src="dist/js/date-time/bootstrap-datepicker.min.js"></script>
		<script src="dist/js/date-time/bootstrap-timepicker.min.js"></script>
		<script src="dist/js/date-time/moment.min.js"></script>
		<script src="dist/js/date-time/daterangepicker.min.js"></script>
		<script src="dist/js/date-time/bootstrap-datetimepicker.min.js"></script>

		<!-- script de las tablas -->
		<script src="dist/js/jqGrid/jquery.jqGrid.min.js"></script>
		<script src="dist/js/jqGrid/i18n/grid.locale-en.js"></script>
		<script src="dist/js/dataTables/jquery.dataTables.min.js"></script>
		<script src="dist/js/dataTables/jquery.dataTables.bootstrap.min.js"></script>
		<script src="dist/js/dataTables/dataTables.tableTools.min.js"></script>
		<script src="dist/js/dataTables/dataTables.colVis.min.js"></script>

		<!-- ace scripts -->
		<script src="dist/js/ace-elements.min.js"></script>
		<script src="dist/js/ace.min.js"></script>
		<script src="dist/js/lockr.min.js"></script>
		<script src="dist/js/sweetalert.min.js"></script>
		<script src="dist/js/jquery.blockUI.js"></script>
</body>

</html>