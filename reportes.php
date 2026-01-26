<?php
/*----------------------------------------------------------------------------------------------------------------*
 * MÓDULO: Reportes & Business Intelligence
 * DISEÑO: Dashboard Completo (Mensual + Histórico Anual)
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
$active_usuarios = "";
$active_reportes = "active";
$title = "Reportes | Sistema";
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <?php include("head.php"); ?>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
    body {
      background-color: #f4f6f9;
    }

    .main-header {
      display: flex;
      justify-content: space-between;
      margin-bottom: 25px;
      align-items: center;
    }

    .kpi-card {
      background: white;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      transition: transform 0.3s;
    }

    .kpi-card:hover {
      transform: translateY(-3px);
    }

    .kpi-icon {
      width: 60px;
      height: 60px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 28px;
      margin-right: 20px;
      color: white;
    }

    .bg-blue {
      background: linear-gradient(45deg, #4099ff, #73b4ff);
    }

    .bg-green {
      background: linear-gradient(45deg, #2ed8b6, #59e0c5);
    }

    .bg-orange {
      background: linear-gradient(45deg, #FFB64D, #ffcb80);
    }

    .bg-purple {
      background: linear-gradient(45deg, #ab47bc, #df78ef);
    }

    .kpi-content h3 {
      margin: 0;
      font-size: 24px;
      font-weight: 700;
      color: #343a40;
    }

    .kpi-content p {
      margin: 0;
      color: #888;
      font-size: 13px;
      font-weight: 500;
    }

    .chart-container {
      background: white;
      border-radius: 12px;
      padding: 25px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
      margin-bottom: 25px;
      height: 100%;
      min-height: 350px;
      position: relative;
    }

    .chart-header {
      border-bottom: 1px solid #f0f0f0;
      padding-bottom: 15px;
      margin-bottom: 20px;
    }

    .chart-title {
      font-weight: 700;
      color: #555;
      font-size: 16px;
      margin: 0;
    }

    .report-btn {
      display: block;
      background: white;
      padding: 20px;
      border-radius: 10px;
      text-align: center;
      border: 1px solid #eee;
      text-decoration: none !important;
      transition: all 0.3s;
      color: #555;
      margin-bottom: 20px;
    }

    .report-btn:hover {
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
      border-color: #3498db;
      transform: translateY(-2px);
    }

    .report-btn i {
      font-size: 30px;
      display: block;
      margin-bottom: 10px;
      color: #3498db;
    }

    .date-filter {
      background: #fff;
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 20px;
    }
  </style>
</head>

<body>
  <?php include("navbar.php"); ?>

  <div class="container-fluid" style="margin-top: 80px;">

    <div class="main-header">
      <h2 style="margin:0; font-weight:700;"><i class="glyphicon glyphicon-stats"></i> Panel de Control</h2>
      <span class="text-muted">Resumen del <?php echo date("Y"); ?></span>
    </div>

    <div class="row">
      <div class="col-md-3 col-sm-6">
        <div class="kpi-card">
          <div class="kpi-icon bg-green"><i class="glyphicon glyphicon-usd"></i></div>
          <div class="kpi-content">
            <h3 id="kpi_ventas_hoy">0.00</h3>
            <p>Ventas del Día</p>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="kpi-card">
          <div class="kpi-icon bg-orange"><i class="glyphicon glyphicon-star"></i></div>
          <div class="kpi-content">
            <h3 id="kpi_producto_top" style="font-size: 18px;">Cargando...</h3>
            <p>Más Vendido</p>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="kpi-card">
          <div class="kpi-icon bg-blue"><i class="glyphicon glyphicon-user"></i></div>
          <div class="kpi-content">
            <?php
            $sql_c = mysqli_query($con, "SELECT count(*) as total FROM clientes");
            $rw_c = mysqli_fetch_array($sql_c);
            ?>
            <h3><?php echo $rw_c['total']; ?></h3>
            <p>Clientes</p>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="kpi-card">
          <div class="kpi-icon bg-purple"><i class="glyphicon glyphicon-list-alt"></i></div>
          <div class="kpi-content">
            <?php
            $sql_f = mysqli_query($con, "SELECT count(*) as total FROM facturas");
            $rw_f = mysqli_fetch_array($sql_f);
            ?>
            <h3><?php echo $rw_f['total']; ?></h3>
            <p>Facturas Emitidas</p>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-8">
        <div class="chart-container">
          <div class="chart-header">
            <h4 class="chart-title">Evolución Mensual (<?php echo date("Y"); ?>)</h4>
          </div>
          <div style="height: 300px;">
            <canvas id="salesChart"></canvas>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="chart-container">
          <div class="chart-header">
            <h4 class="chart-title">Top 5 Productos</h4>
          </div>
          <div style="height: 300px; display:flex; justify-content:center;">
            <canvas id="productsChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="chart-container">
          <div class="chart-header">
            <h4 class="chart-title"><i class="glyphicon glyphicon-calendar"></i> Histórico de Ventas por Año</h4>
          </div>
          <div style="height: 250px;">
            <canvas id="historyChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    <hr>

    <h3 class="text-primary"><i class="glyphicon glyphicon-print"></i> Reportes PDF</h3>
    <div class="date-filter">
      <form class="form-inline" id="reporte_form">
        <div class="form-group">
          <label>Desde: </label>
          <input type="date" class="form-control" id="fecha_inicio" value="<?php echo date('Y-m-01'); ?>">
        </div>
        <div class="form-group" style="margin-left: 10px;">
          <label>Hasta: </label>
          <input type="date" class="form-control" id="fecha_fin" value="<?php echo date('Y-m-d'); ?>">
        </div>
        <button type="button" class="btn btn-primary" onclick="imprimir_facturas()" style="margin-left: 10px;">
          <i class="glyphicon glyphicon-search"></i> Generar Reporte Ventas
        </button>
      </form>
    </div>

    <div class="row">
      <div class="col-md-3 col-sm-6">
        <a href="reportes/reporte_inventario.php" target="_blank" class="report-btn">
          <i class="glyphicon glyphicon-barcode" style="color:#f39c12"></i>
          Inventario General
        </a>
      </div>
      <div class="col-md-3 col-sm-6">
        <a href="reportes/reporte_existencia_minima.php" target="_blank" class="report-btn">
          <i class="glyphicon glyphicon-alert" style="color:#e74c3c"></i>
          Stock Bajo
        </a>
      </div>
    </div>

  </div>

  <?php include("footer.php"); ?>

  <script>
    $(document).ready(function () {

      // 1. KPI Ventas Hoy
      $.ajax({
        url: "ajax/dashboard_data.php", type: "POST",
        data: { action: 'ventas_hoy' }, dataType: "json",
        success: function (response) { $("#kpi_ventas_hoy").text(response); }
      });

      // 2. Gráfica Mensual (Línea)
      $.ajax({
        url: "ajax/dashboard_data.php", type: "POST",
        data: { action: 'ventas_anuales' }, dataType: "json",
        success: function (response) {
          new Chart(document.getElementById('salesChart'), {
            type: 'line',
            data: {
              labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
              datasets: [{
                label: 'Ventas (Bs)',
                data: response,
                borderColor: '#3498db', backgroundColor: 'rgba(52, 152, 219, 0.1)',
                borderWidth: 2, tension: 0.4, fill: true, pointRadius: 4
              }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
          });
        }
      });

      // 3. Gráfica Circular
      $.ajax({
        url: "ajax/dashboard_data.php", type: "POST",
        data: { action: 'top_productos' }, dataType: "json",
        success: function (response) {
          if (response.labels.length > 0 && response.labels[0] !== "Sin ventas") { $("#kpi_producto_top").text(response.labels[0]); }
          else { $("#kpi_producto_top").text("Sin datos"); }

          new Chart(document.getElementById('productsChart'), {
            type: 'doughnut',
            data: {
              labels: response.labels,
              datasets: [{
                data: response.data,
                backgroundColor: ['#3498db', '#2ecc71', '#f1c40f', '#e74c3c', '#9b59b6'], borderWidth: 0
              }]
            },
            options: { responsive: true, maintainAspectRatio: false, cutout: '75%', plugins: { legend: { position: 'bottom', labels: { boxWidth: 10 } } } }
          });
        }
      });

      // 4. NUEVA GRÁFICA HISTÓRICA (Barras)
      $.ajax({
        url: "ajax/dashboard_data.php", type: "POST",
        data: { action: 'historico_anual' }, dataType: "json",
        success: function (response) {
          new Chart(document.getElementById('historyChart'), {
            type: 'bar',
            data: {
              labels: response.labels, // Años [2022, 2023, 2024]
              datasets: [{
                label: 'Ventas Totales Anuales',
                data: response.data, // Totales
                backgroundColor: '#8e24aa', // Color morado moderno
                borderRadius: 5, // Bordes redondeados en las barras
                barThickness: 50 // Ancho máximo
              }]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: { legend: { display: false } },
              scales: { y: { beginAtZero: true } }
            }
          });
        }
      });

    });

    function imprimir_facturas() {
      var fi = $('#fecha_inicio').val();
      var ff = $('#fecha_fin').val();
      window.open('reportes/facturasVentas.php?fecha_inicio=' + fi + '&fecha_fin=' + ff, '_blank');
    }
  </script>
</body>

</html>