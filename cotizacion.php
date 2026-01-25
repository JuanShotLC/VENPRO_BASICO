<?php
session_start();
if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) {
  header("location: login.php");
  exit;
}

/* Connect To Database*/
require_once("config/db.php");
require_once("config/conexion.php");

$active_facturas = "";
$active_productos = "";
$active_cotizacion = "active";
$active_clientes = "";
$active_usuarios = "";
$title = "Vista Precios | Sistema";

// OBTENER LA TASA ACTUAL DE LA BASE DE DATOS
$sql_last = mysqli_query($con, "SELECT precio FROM cotizacion ORDER BY id_coti DESC LIMIT 1");
$row_last = mysqli_fetch_array($sql_last);
$precio_actual_header = isset($row_last['precio']) ? $row_last['precio'] : 0.00;
?>

<?php if ($_SESSION['user_name'] == 'baraka') { ?>
  <!DOCTYPE html>
  <html lang="es">

  <head>
    <?php include("head.php"); ?>
    <style>
      /* ESTILOS PERSONALIZADOS PARA EL DASHBOARD */
      body {
        background-color: #ecf0f5;
        /* Gris muy suave estilo AdminLTE */
      }

      .container-fluid {
        padding-top: 20px;
      }

      /* TARJETA DE TASA (Izquierda) */
      .card-tasa {
        background: white;
        border-top: 3px solid #3c8dbc;
        /* Azul profesional */
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
        border-radius: 3px;
        padding: 20px;
        margin-bottom: 20px;
        text-align: center;
      }

      .tasa-titulo {
        color: #777;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: bold;
      }

      .tasa-valor {
        font-size: 48px;
        font-weight: bold;
        color: #00a65a;
        /* Verde éxito */
        margin: 10px 0;
      }

      .calculator-box {
        background-color: #f9f9f9;
        padding: 15px;
        border-radius: 5px;
        margin-top: 20px;
        border: 1px solid #ddd;
      }


      /* Tarjeta de Tasa (KPI) */
      .rate-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        text-align: center;
        padding: 30px;
        border-top: 5px solid #00a65a;
        /* Linea verde arriba */
        margin-bottom: 30px;
      }

      .rate-title {
        color: #777;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 14px;
        margin-bottom: 10px;
      }

      .rate-value {
        font-size: 64px;
        font-weight: 700;
        color: #00a65a;
        margin: 0;
        line-height: 1;
      }

      .rate-currency {
        font-size: 24px;
        color: #999;
        vertical-align: top;
        margin-right: 5px;
      }

      .btn-update {
        margin-top: 20px;
        border-radius: 50px;
        padding: 10px 30px;
        font-weight: bold;
        text-transform: uppercase;
        box-shadow: 0 4px 6px rgba(0, 166, 90, 0.3);
        transition: all 0.3s;
      }

      .btn-update:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 166, 90, 0.4);
      }

      /* PANEL DE LISTA (Derecha) */
      .panel-lista {
        border: none;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
      }

      .panel-heading-custom {
        background-color: #fff !important;
        padding: 10px;
        border-bottom: 1px solid #f4f4f4;
      }

      .search-input {
        height: 45px;
        font-size: 16px;
        border-radius: 30px 0 0 30px !important;
      }

      .search-btn {
        height: 45px;
        border-radius: 0 30px 30px 0 !important;
        background-color: #3c8dbc;
        color: white;
        border: 1px solid #3c8dbc;
      }

      .search-btn:hover {
        background-color: #367fa9;
      }
    </style>
  </head>

  <body>
    <?php include("navbar.php"); ?>

    <div class="container-fluid">
      <div class="row">


        <div class="col-md-3 col-sm-4">
          <div class="rate-card">
            <div class="rate-title">Tasa de Cambio</div>
            <div class="rate-value">
              <span class="rate-currency">Bs.</span><span
                id="precio_actual_display"><?php echo number_format($precio_actual_header, 2); ?></span>
            </div>

            <button type="button" class="btn btn-success btn-update" data-toggle="modal" data-target="#myModal2">
              <i class="glyphicon glyphicon-refresh"></i> Actualizar Tasa
            </button>

            <p style="margin-top: 15px; color: #999; font-size: 12px;">
              <i class="glyphicon glyphicon-info-sign"></i> Al actualizar, todos los productos se recalcularán
              automáticamente.
            </p>
          </div>

          <div class="card-tasa">
            <div class="tasa-titulo"><i class="glyphicon glyphicon-phone"></i> Calculadora Rápida</div>
            <div class="calculator-box">
              <div class="form-group">
                <label for="calc_usd" class="sr-only">Dólares</label>
                <div class="input-group">
                  <span class="input-group-addon">$</span>
                  <input type="number" id="calc_usd" class="form-control" placeholder="Monto en USD">
                </div>
              </div>
              <div class="text-center" style="font-size: 20px; font-weight: bold; color: #333;">
                = <span id="calc_result">0.00</span> Bs.
              </div>
            </div>
          </div>

        </div>

        <div class="col-md-9 col-sm-8">
          <div class="panel panel-default panel-lista">
            <div class="panel-heading panel-heading-custom">
              <div class="row">
                <div class="col-md-7">
                  <h4 style=""><i class='glyphicon glyphicon-list-alt'></i> Lista de Precios</h4>
                </div>
                <div class="col-md-5">
                  <form class="form-horizontal" role="form" id="datos_cotizacion">
                    <div class="input-group">
                      <input type="text" class="form-control search-input" id="q"
                        placeholder="Buscar por código o nombre..." onkeyup='load(1);'>
                      <span class="input-group-btn">
                        <button type="button" class="btn btn-default search-btn" onclick='load(1);'>
                          <i class="glyphicon glyphicon-search"></i>
                        </button>
                      </span>
                    </div>
                  </form>
                </div>
              </div>
            </div>

            <div class="panel-body">
              <div id="loader" class="text-center"></div>
              <div id="resultados"></div>
              <div class='outer_div'></div>
            </div>
          </div>
        </div>

      </div>
    </div>

    <?php include("footer.php"); ?>
    <?php include("modal/editar_cotizacion.php"); ?>
    <script type="text/javascript" src="js/cotizacion.js"></script>

    <script>
      // Script para la calculadora rápida lateral (no afecta la base de datos, solo visual)
      $(document).ready(function () {
        var tasa = <?php echo $precio_actual_header; ?>;

        $('#calc_usd').on('keyup change', function () {
          var usd = $(this).val();
          var total = usd * tasa;
          // Formatear a moneda venezolana
          var formateado = new Intl.NumberFormat('es-VE', { minimumFractionDigits: 2 }).format(total);
          $('#calc_result').text(formateado);
        });
      });
    </script>
  </body>

  </html>
<?php } else {
  // Redirección si no es el usuario autorizado
  header("location: login.php");
  exit;
} ?>