<?php
// 1. LIMPIEZA DE BUFFER (Vital para que no salgan espacios en blanco al inicio)
ob_start();

require_once("../../config/db.php");
require_once("../../config/conexion.php");

// Desactivar errores en pantalla
error_reporting(0);
ini_set('display_errors', 0);

date_default_timezone_set('America/Caracas');
setlocale(LC_TIME, "spanish");

$page = isset($_GET['page']) ? $_GET['page'] : 1;
$limit = isset($_GET['rows']) ? $_GET['rows'] : 10;
$sidx = isset($_GET['sidx']) ? $_GET['sidx'] : 1;
$sord = isset($_GET['sord']) ? $_GET['sord'] : 'asc';
$search = isset($_GET['_search']) ? $_GET['_search'] : 'false';

// --- CORRECCIÓN ORDENAMIENTO ---
if (!$sidx || $sidx == 'id') {
    $sidx = 'F.id';
}

if ($sidx == 'serie')
    $sidx = 'F.serie';
if ($sidx == 'IDENTIFICACION')
    $sidx = 'C.identificacion';
if ($sidx == 'fecha_actual')
    $sidx = 'F.fecha_actual';

// 2. CONTAR
$count = 0;
$sql_count = "SELECT COUNT(*) AS count FROM facturas";
$resultado_count = mysqli_query($con, $sql_count);

if ($resultado_count) {
    if ($row = mysqli_fetch_array($resultado_count)) {
        $count = $count + $row[0];
    }
}

if ($count > 0 && $limit > 0) {
    $total_pages = ceil($count / $limit);
} else {
    $total_pages = 0;
}
if ($page > $total_pages)
    $page = $total_pages;

$start = $limit * $page - $limit;
if ($start < 0)
    $start = 0;

// 3. CONSULTA
$select = "SELECT 
                F.id,
                F.fecha_actual,
                CONCAT(IFNULL(T.nombre_tipo_comprobante, 'DOC'), ' N°: ', F.serie) AS serie,
                CONCAT(IFNULL(C.identificacion,'S/N'), ' - ', IFNULL(C.nombres_completos,'Consumidor Final')) AS cliente,
                F.total_venta";

$from = "FROM facturas F
             LEFT JOIN clientes C ON F.id_cliente = C.id
             LEFT JOIN tipo_comprobante T ON F.tipo_comprobante = T.id";

if ($search == 'false') {
    $SQL = "$select $from ORDER BY $sidx $sord LIMIT $limit OFFSET $start";
} else {
    $campo = isset($_GET['searchField']) ? $_GET['searchField'] : '';
    $searchString = isset($_GET['searchString']) ? $_GET['searchString'] : '';

    // Mapeo
    if ($campo == 'id')
        $campo = "F.id";
    if ($campo == 'serie')
        $campo = "F.serie";
    if ($campo == 'IDENTIFICACION')
        $campo = "C.identificacion";

    if (isset($_GET['searchOper']) && $_GET['searchOper'] == 'eq') {
        $SQL = "$select $from WHERE $campo = '$searchString' ORDER BY $sidx $sord LIMIT $limit OFFSET $start";
    } else {
        $SQL = "$select $from WHERE $campo LIKE '%$searchString%' ORDER BY $sidx $sord LIMIT $limit OFFSET $start";
    }
}

$resultado = mysqli_query($con, $SQL);

// 4. GENERAR XML (Con Sanitización)
if (ob_get_length())
    ob_clean();
header("Content-Type: text/xml;charset=utf-8");

$s = "<?xml version='1.0' encoding='utf-8'?>";
$s .= "<rows>";
$s .= "<page>" . $page . "</page>";
$s .= "<total>" . $total_pages . "</total>";
$s .= "<records>" . $count . "</records>";

if ($resultado) {
    while ($row = mysqli_fetch_array($resultado)) {
        $s .= "<row id='" . $row[0] . "'>";
        $s .= "<cell>" . $row[0] . "</cell>"; // ID
        $s .= "<cell>" . $row[1] . "</cell>"; // Fecha

        // --- AQUÍ ESTÁ EL ARREGLO MÁGICO ---
        // Usamos htmlspecialchars para convertir '&' en '&amp;', etc.
        $s .= "<cell>" . htmlspecialchars($row[2], ENT_QUOTES, 'UTF-8') . "</cell>"; // Serie
        $s .= "<cell>" . htmlspecialchars($row[3], ENT_QUOTES, 'UTF-8') . "</cell>"; // Cliente
        // ------------------------------------

        $s .= "<cell>" . $row[4] . "</cell>"; // Total
        $s .= "<cell></cell>";
        $s .= "</row>";
    }
}
$s .= "</rows>";
echo $s;
?>