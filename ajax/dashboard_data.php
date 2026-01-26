<?php
/*----------------------------------------------------------------------------------------------------------------*
 * MÓDULO: Backend Dashboard (Unificado)
 * DESCRIPCIÓN: Procesa datos para gráficas usando la conexión nativa ($con)
 *----------------------------------------------------------------------------------------------------------------*/

// 1. Cabeceras obligatorias para JSON (Evita errores de "Unexpected token")
header('Content-Type: application/json');
error_reporting(0); // Silencia warnings de PHP para que no rompan el JSON

// 2. Conexión a la Base de Datos
// Ajustamos la ruta asumiendo que este archivo está en /ajax/
$db_file = "../config/db.php";
$con_file = "../config/conexion.php";

if (file_exists($db_file) && file_exists($con_file)) {
    require_once($db_file);
    require_once($con_file);
} else {
    echo json_encode(["error" => "No se encuentran archivos de configuración"]);
    exit;
}

// Verificar conexión
if (!$con) {
    echo json_encode(["error" => "Error de conexión a BD"]);
    exit;
}

$action = isset($_POST['action']) ? $_POST['action'] : '';

/* =======================================================
   ACCIÓN 1: VENTAS ANUALES (Gráfica de Líneas)
   ======================================================= */
if ($action == 'ventas_anuales') {
    $year = date('Y');
    // Array base: 12 meses en 0
    $ventas_mensuales = array_fill(0, 12, 0);

    // Consulta directa usando MySQLi (Más compatible con tu proyecto)
    $sql = "SELECT MONTH(fecha_creacion) as mes, SUM(total_venta) as total 
            FROM facturas 
            WHERE YEAR(fecha_creacion) = '$year' 
            GROUP BY MONTH(fecha_creacion)";

    $query = mysqli_query($con, $sql);

    if ($query) {
        while ($row = mysqli_fetch_array($query)) {
            // Restamos 1 al mes porque los arrays en JS/PHP empiezan en 0 (Enero=0, Feb=1...)
            $indice = intval($row['mes']) - 1;
            if ($indice >= 0 && $indice < 12) {
                $ventas_mensuales[$indice] = floatval($row['total']);
            }
        }
    }

    echo json_encode($ventas_mensuales);
    exit;
}

/* =======================================================
   ACCIÓN 2: TOP PRODUCTOS (Gráfica de Dona)
   ======================================================= */
if ($action == 'top_productos') {
    $sql = "SELECT P.nombre_producto, SUM(D.cantidad) as total
            FROM detalle_factura D
            JOIN products P ON D.id_producto = P.id_producto
            GROUP BY D.id_producto, P.nombre_producto 
            ORDER BY total DESC
            LIMIT 5";

    $query = mysqli_query($con, $sql);

    $labels = [];
    $data = [];

    if ($query) {
        while ($row = mysqli_fetch_array($query)) {
            $labels[] = $row['nombre_producto'];
            $data[] = floatval($row['total']);
        }
    }

    // Si no hay ventas, enviamos datos "dummy" para que no quede vacía la gráfica
    if (empty($data)) {
        $labels = ["Sin ventas"];
        $data = [1]; // Un valor para que pinte algo
    }

    echo json_encode(["labels" => $labels, "data" => $data]);
    exit;
}

/* =======================================================
   ACCIÓN 3: KPI VENTAS HOY
   ======================================================= */
if ($action == 'ventas_hoy') {
    // Usamos CURDATE() de MySQL para mayor precisión
    $sql = "SELECT SUM(total_venta) as total FROM facturas WHERE date(fecha_creacion) = CURDATE()";
    $query = mysqli_query($con, $sql);

    $total = 0;
    if ($query) {
        $row = mysqli_fetch_array($query);
        $total = $row['total'];
    }

    // Formato moneda
    echo json_encode(number_format((float) $total, 2, '.', ','));
    exit;
}

/* =======================================================
   ACCIÓN 4: HISTÓRICO DE VENTAS POR AÑO (Nueva)
   ======================================================= */
if ($action == 'historico_anual') {
    // Agrupamos por Año de la fecha de factura
    $sql = "SELECT YEAR(fecha_creacion) as anio, SUM(total_venta) as total 
            FROM facturas 
            GROUP BY YEAR(fecha_creacion) 
            ORDER BY anio ASC";

    $query = mysqli_query($con, $sql);

    $labels = [];
    $data = [];

    if ($query) {
        while ($row = mysqli_fetch_array($query)) {
            $labels[] = $row['anio'];
            $data[] = floatval($row['total']);
        }
    }

    // Si no hay datos, enviamos el año actual en 0 para que no falle la gráfica
    if (empty($labels)) {
        $labels[] = date("Y");
        $data[] = 0;
    }

    echo json_encode(["labels" => $labels, "data" => $data]);
    exit;
}

// Si llega aquí sin acción válida
echo json_encode(["status" => "error", "message" => "Acción no válida"]);
?>