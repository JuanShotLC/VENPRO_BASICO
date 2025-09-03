<?php
    require_once ("../../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
    require_once ("../../config/conexion.php");//Contiene funcion que conecta a la base de datos

    date_default_timezone_set('America/Caracas');
    setlocale (LC_TIME,"spanish");

    $page = $_GET['page'];
    $limit = $_GET['rows'];
    $sidx = $_GET['sidx'];
    $sord = $_GET['sord'];
    $search = $_GET['_search'];

    if (!$sidx)
        $sidx = 1;
    
    $count = 0;
    $resultado = mysqli_query($con,"SELECT  COUNT(*) AS count FROM nota_venta");         
    while ($row = mysqli_fetch_array($resultado)) {
        $count = $count + $row[0];    
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
    
    if ($search == 'false') {
        $SQL = "SELECT 
                    F.id,
                    F.fecha_actual,
                    CONCAT(T.nombre_tipo_comprobante, ' N°: ', F.serie) AS serie,
                    CONCAT( C.identificacion, '  ',C.nombres_completos) AS IDENTIFICACION,
                    F.total_venta
                FROM facturas F
                INNER JOIN clientes C 
                    ON F.id_cliente = C.id
                INNER JOIN tipo_comprobante T 
                    ON F.tipo_comprobante = T.codigo
        ORDER BY $sidx $sord limit $limit offset $start";
    } else {
        $campo = $_GET['searchField'];
      
        if ($_GET['searchOper'] == 'eq') {
            $SQL = "SELECT 
                    F.id,
                    F.fecha_actual,
                    CONCAT(T.nombre_tipo_comprobante, ' N°: ', F.serie) AS serie,
                    CONCAT( C.identificacion, '  ',C.nombres_completos) AS IDENTIFICACION,
                    C.nombres_completos,
                    F.total_venta
                FROM facturas F
                INNER JOIN clientes C 
                    ON F.id_cliente = C.id
                INNER JOIN tipo_comprobante T 
                    ON F.tipo_comprobante = T.codigo
            AND $campo = '$_GET[searchString]' ORDER BY $sidx $sord limit $limit offset $start";
        }         
        if ($_GET['searchOper'] == 'cn') {
            $SQL = "SELECT 
                    F.id,
                    F.fecha_actual,
                    CONCAT(T.nombre_tipo_comprobante, ' N°: ', F.serie) AS serie,
                    CONCAT( C.identificacion, '  ',C.nombres_completos) AS IDENTIFICACION,
                    C.nombres_completos,
                    F.total_venta
                FROM facturas F
                INNER JOIN clientes C 
                    ON F.id_cliente = C.id
                INNER JOIN tipo_comprobante T 
                    ON F.tipo_comprobante = T.codigo
            AND $campo like '%$_GET[searchString]%' ORDER BY $sidx $sord  limit $limit offset $start";
        }
    }  

    $resultado = mysqli_query($con,$SQL);  
    
    header("Content-Type: text/html;charset=utf-8");   
    $s = "<?xml version='1.0' encoding='utf-8'?>";
    $s .= "<rows>";
        $s .= "<page>" . $page . "</page>";
        $s .= "<total>" . $total_pages . "</total>";
        $s .= "<records>" . $count . "</records>";
        while ($row = mysqli_fetch_array($resultado)) {
            $s .= "<row id='" . $row[0] . "'>";
            $s .= "<cell>" . $row[0] . "</cell>";
            $s .= "<cell>" . $row[1] . "</cell>";
            $s .= "<cell>" . $row[2] . "</cell>";
            $s .= "<cell>" . $row[3] . "</cell>";
            $s .= "<cell>" . $row[4] . "</cell>";
            $s .= "<cell></cell>";
            $s .= "</row>";
        }
    $s .= "</rows>";
    echo $s;    
?>