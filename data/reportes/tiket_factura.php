<?php
    include_once('../../fpdf/rotation.php');
        require '../../dist/qr/phpqrcode/qrlib.php';


    // include_once('../../admin/class.php');
    // include_once('../../admin/convertir.php');
    // include_once('../../admin/funciones_generales.php');
     //require('../../fpdf/fpdf.php');
        /* Connect To Database*/
    require_once ("../../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
    require_once ("../../config/conexion.php");//Contiene funcion que conecta a la base de datos
    //Archivo de funciones PHP
    include('../../ajax/is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
    include("../../funciones.php");


    date_default_timezone_set('America/Caracas');
    setlocale (LC_TIME,"spanish");
    //session_start();

    $fecha = date('Y-m-d H:i:s', time());  
    $fecha_ma = date('Y-m');  
    $fecha_cor = date('Y-m-d');  

    $dir = '../../dist/qr/temp/';
    
    if(!file_exists($dir))
        mkdir($dir);

   

    $pdf = new FPDF('P','mm',array(80,190));
    $pdf->SetMargins(4, 4 , 4);
    $pdf->SetAutoPageBreak(true,25); 
    $pdf->AddPage();

$pdf->SetFont('Helvetica','B',14);
$pdf->Cell(70,4,'SENIAT',0,1,'C');
    $pdf->SetFont('Helvetica','',8);
$pdf->Cell(70,4,'Rif.:'.get_row('perfil','rif', 'id_perfil', 1),0,1,'C');
$pdf->SetFont('Helvetica','',10);
$pdf->Cell(70,4,get_row('perfil','nombre_empresa', 'id_perfil', 1),0,1,'C');
    $pdf->SetFont('Helvetica','',8);

//$pdf->Cell(70,4,utf8_decode( get_row('perfil','direccion', 'id_perfil', 1)),0,1,'C');
$pdf->Cell(70,4,utf8_decode( "Boulevard Sabana Grand,"),0,1,'C');

$pdf->Cell(70,4,utf8_decode( "Edificio Galerías Bolívar,Torre A,Piso 9,Ofic.92"),0,1,'C');

$pdf->Cell(70,4,utf8_decode( get_row('perfil','ciudad', 'id_perfil', 1).", ".get_row('perfil','estado', 'id_perfil', 1)),0,1,'C');
$pdf->Cell(70,4,utf8_decode("Teléfono: ").utf8_decode(get_row('perfil','telefono', 'id_perfil', 1)),0,1,'C');
$pdf->Cell(70,4,utf8_decode(get_row('perfil','email', 'id_perfil', 1)),0,1,'C');

  
    $resultado = mysqli_query($con,"SELECT C.nombres_completos, C.identificacion, C.direccion, C.telefono2, C.ciudad,c.direccion, F.fecha_actual, F.forma_pago,F.subtotal, F.tarifa0, F.tarifa, F.iva_venta, F.descuento_venta, F.total_venta, F.serie,F.hora_actual from facturas F, clientes C WHERE F.id_cliente = C.id AND F.id = '$_GET[id]'");    

    while ($row = mysqli_fetch_array($resultado)) {  

 
// DATOS FACTURA        
$pdf->Ln(5);
$pdf->SetFont('Helvetica','',8);
$pdf->Cell(60,4,utf8_decode('N°. FACTURA:'. strtoupper($row[14])),0,1,'');
$pdf->Cell(60,4,utf8_decode('FECHA: '. strtoupper($row[6]).' HORA:'. strtoupper($row[15])),0,1,'');
$pdf->Cell(60,4,utf8_decode('RIF./C.I.: ' . strtoupper($row[1])),0,1,'');
$pdf->Cell(60,4,utf8_decode('CLIENTE: '. strtoupper($row[0])),0,1,'');
$pdf->Cell(60,4,utf8_decode('DIRECCIÓN: ' . strtoupper($row[2])),0,1,'');

    }

    $pdf->SetFont('Helvetica', 'B', 7);
    $pdf->Cell(20, 10, 'Articulo', 0);
    $pdf->Cell(5, 10, 'Cant.',0,0,'R');
    $pdf->Cell(15, 10, 'Precio',0,0,'R');
    $pdf->Cell(20, 10, 'Total',0,0,'R');
    $pdf->Ln(9);
    $pdf->Cell(70,0,'','T');
    $pdf->Ln(0);

    $resultado =  mysqli_query($con,"SELECT D.cantidad, P.codigo_producto, P.nombre_producto, D.precio, D.total FROM facturas F, detalle_factura D, products P WHERE F.id = D.id_factura AND D.id_producto = P.id_producto AND D.id_factura = '$_GET[id]'");
    
    $total_items = 0;
    $caracteres = 70;
    //$posiciony = 60;
    while ($row = mysqli_fetch_array($resultado)) {
        $cantidad = $row[0];
        $codigo = $row[1];
        $descripcion = $row[2];
        $valor_unitario = $row[3];
        $total_venta = $row[4];
        $total_items = $total_items + $cantidad;


        $sub1 = (number_format(($total_venta / 1.14) / $cantidad,2,',','.'));
        $sub2 = (number_format(($total_venta / 1.14),2,',','.')); 
        $sub3 = (number_format(($valor_unitario )* $cantidad,2,',','.')); 


$pdf->SetFont('Helvetica', '', 7);
$pdf->MultiCell(30,4,utf8_decode(substr($codigo, 0, $caracteres)),0,'L'); 
$pdf->Cell(25, -5, utf8_decode($cantidad),0,0,'R');
$pdf->Cell(20, -5, number_format(($valor_unitario),2,',','.'),0,0,'R');
$pdf->Cell(20, -5, utf8_decode($sub3),0,0,'R');
$pdf->Ln(2);


    }


$consl2 = mysqli_query($con,"SELECT C.nombres_completos, C.identificacion, C.direccion, C.telefono2, C.ciudad,c.direccion, F.fecha_actual, F.forma_pago,F.subtotal, F.tarifa0, F.tarifa, F.iva_venta, F.descuento_venta, F.total_venta, F.serie,F.precinto_nro,F.iva_igtf,F.divisas,F.factor from facturas F, clientes C WHERE F.id_cliente = C.id AND F.id = '$_GET[id]'");    

    while ($row = mysqli_fetch_array($consl2)) {  
$pdf->Ln(1);
$pdf->Cell(70,0,'','T');
$pdf->Ln(2);    
$pdf->Cell(35, 10, 'Base Impo.', 0);    
$pdf->Cell(20, 10, '', 0);
$pdf->Cell(15, 10, number_format(($row[8]),2,',','.'),0,0,'R');
$pdf->Ln(3);    
$pdf->Cell(35, 10, utf8_decode('IVA G '.get_row('perfil','impuesto', 'id_perfil', 1).'%:' ) ,0);    
$pdf->Cell(20, 10, '', 0);
$pdf->Cell(15, 10, number_format(($row[11]),2,',','.'),0,0,'R');
$pdf->Ln(3);  
$pdf->Cell(35, 10, utf8_decode('IGTF '.get_row('perfil','igtf', 'id_perfil', 1).'%:' ) ,0);    
$pdf->Cell(20, 10, '', 0);
$pdf->Cell(15, 10, number_format(($row[16]),2,',','.'),0,0,'R');
$pdf->Ln(3);  
$pdf->Cell(35, 10, utf8_decode('DIVISA ' ) ,0);    
$pdf->Cell(20, 10, '', 0);
$pdf->Cell(15, 10, number_format(($row[17]),2,',','.'),0,0,'R');
$pdf->Ln(3);   
$pdf->Cell(35, 10, 'TOTAL:', 0);    
$pdf->Cell(20, 10, '', 0);
$pdf->Cell(15, 10, number_format(($row[13]),2,',','.')  ,0,0,'R');


    $rif_em=get_row('perfil','rif', 'id_perfil', 1);
    $anno_mes=$fecha_ma;
    $fecha_factura=$fecha;
    $Tipo_F='C';
    $Cod_S='01';
    $cleinte_doc=$row[1];
    $rif_provedor='J1234567890';
    $nur_factura=$row[14];
    $numero_control_f='N-'.$row[14];
    $monto_total=number_format(($row[13]),2,',','');
    $base_retension=number_format($row[8],2,',','');
    $monto_retenido=number_format($row[11],2,',','');
    $nota_credito='0';
    $numero_comprobante='0';
    $monto_excto_factura='0';
    $iva_factura='0';
    $colp='0';

    $filename = $dir.'test.png';    
    $tamanio = 2;
    $level = 'H';
    $frameSize = 1;

    $contenido = $rif_em.' '.$anno_mes.'  '.$fecha_cor.'  '.$Tipo_F.'   '.$Cod_S.'  '.$cleinte_doc.'   '.$rif_provedor.'   '.$nur_factura.'  '.$numero_control_f.'    '.$monto_total.'    '.$base_retension.'    '.$monto_retenido.'   '.$nota_credito.'   '.$numero_comprobante.'   '.$monto_excto_factura.'   '.$iva_factura;
// Jxxxxxx 201105  2011-05-10  C   01  0   01  01  0,00    0,00    0,00    0   20110500000000  0,00    0,00    0
    QRcode::png($contenido, $filename, $level, $tamanio, $frameSize);
 




}
 
// PIE DE PAGINA
$pdf->Ln(10);
$pdf->SetFont('Helvetica','',7);
$pdf->Cell(70,0,'GRACIAS POR SU COMPRA.',0,1,'C');

// $pdf->Image($filename);
$pdf->Cell(70,0,$pdf->Image($filename, $pdf->GetX() + 20, $pdf->GetY() + 3,30),0,1,'C');
$pdf->Ln(3);


 
$pdf->Output('ticket_numero.pdf','i');
?>  