<?php
    require('../fpdf/fpdf.php');
        /* Connect To Database*/
    require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
    require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
    //Archivo de funciones PHP
    include('../ajax/is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
    include("../funciones.php");

 
    date_default_timezone_set('America/Caracas'); 
    setlocale(LC_ALL, 'es_ES');



    class PDF extends FPDF {   
        var $widths;
        var $aligns; 

        function SetWidths($w) {            
            $this->widths=$w;
        }

        function Header() {                         
            $this->AddFont('Amble-Regular','','Amble-Regular.php');
            $this->SetFont('Amble-Regular','',10);        
            $fecha = date('Y-m-d', time());
            $this->SetX(1);
            $this->SetY(1);
            // $this->Cell(20, 5, $fecha, 0,0, 'C', 0);                         
            // $this->Cell(150, 5, "ota de ", 0,1, 'R', 0);      
            $this->SetFont('Arial','B',14);                                                    
            //$this->Cell(190, 8, 'Rif.: '.$_SESSION['empresa']['ruc'], 0,1, 'C',0); 
            $this->Cell(190, 8,'Rif.:'.get_row('perfil','rif', 'id_perfil', 1), 0,1, 'C',0); 
            $this->Cell(190, 8,  get_row('perfil','nombre_empresa', 'id_perfil', 1), 0,1, 'C',0);                                
            $this->Image('../'.get_row('perfil','logo_url', 'id_perfil', 1),2,2,40,30);
             $this->SetFont('Amble-Regular','',8);        
            // $this->Cell(180, 5, "PROPIETARIO: ".utf8_decode($_SESSION['empresa']['propietario']),0,1, 'C',0);                                
            $this->Cell(170, 5,utf8_decode( get_row('perfil','direccion', 'id_perfil', 1)),0,5, 'R',0);   
            $this->SetFont('Amble-Regular','',10);                              
            $this->Cell(190, 5, utf8_decode("Teléfono: ").utf8_decode(get_row('perfil','telefono', 'id_perfil', 1)),0,1, 'C',0);                                
             $this->Cell(190, 5, "Correo: ".utf8_decode(get_row('perfil','email', 'id_perfil', 1)),0,1, 'C',0);                                
                                   
                                          
            $this->SetDrawColor(0,0,0);
            $this->SetLineWidth(0.4);            
            //$this->Line(1,50,210,50);            
            $this->SetFont('Arial','B',12);                                                                
                                                                               
            $this->Cell(190, 12, utf8_decode("Reporte de Ventas"),0,1, 'C',0);                                                                                                                            
            $this->SetFont('Amble-Regular','',10);        
            //$this->Ln(3);
            $this->SetFillColor(255,255,225);            
            $this->SetLineWidth(0.2);                                        
        }

        function Footer() {            
            $this->SetY(-15);            
            $this->SetFont('Arial','I',8);            
            $this->Cell(0,10,'Pag. '.$this->PageNo().'/{nb}',0,0,'C');
        }               
    }

    $pdf = new PDF('P','mm','a4');
    $pdf->AddPage();
    $pdf->SetMargins(0,0,0,0);
    $pdf->AliasNbPages();
    $pdf->AddFont('Amble-Regular');                    
    $pdf->SetFont('Amble-Regular','',10);       
    $pdf->SetFont('Arial','B',9);   
    $pdf->SetX(5);    
    $pdf->SetFont('Amble-Regular','',9); 
    $total=0;
    $sub=0;
    $calIva=0;
    $ivaT=0;
    $t0 = 0;
    $impuesto= get_row('perfil','impuesto', 'id_perfil', 1);
    $convertir = strtotime($_GET['inicio']);
    $convertir2 = strtotime($_GET['fin']);
    $inicio = strftime("%d-%m-%Y", $convertir);
    $fin = strftime("%d-%m-%Y", $convertir2);


    $pdf->SetX(20); 
    $pdf->Cell(22, 6, utf8_decode('Desde:'),1,0, 'C',0);                                     
    $pdf->Cell(25, 6, utf8_decode(''.$inicio),1,0, 'C',0);                 
    $pdf->Cell(40, 6, utf8_decode('Hasta:'),1,0, 'C',0);
    $pdf->Cell(45, 6, utf8_decode(''.$fin),1,0, 'C',0);                                     
    $pdf->Cell(40, 6, utf8_decode(''),1,1, 'C',0);           
    $pdf->SetX(20); 
    $pdf->Cell(22, 6, utf8_decode('Nro Factura'),1,0, 'C',0);                                     
    $pdf->Cell(25, 6, utf8_decode('Fecha'),1,0, 'C',0);                 
    $pdf->Cell(40, 6, utf8_decode('Subtotal'),1,0, 'C',0);
    $pdf->Cell(45, 6, utf8_decode('Iva '.get_row('perfil','impuesto', 'id_perfil', 1).'%'),1,0, 'C',0);                                     
    $pdf->Cell(40, 6, utf8_decode('Total'),1,1, 'C',0);      
 
   
    
        $resultado1 = mysqli_query($con,"SELECT F.serie, F.fecha_actual, F.total_venta, F.id,F.estado,F.iva_venta,F.subtotal FROM facturas F  WHERE F.estado='1' and F.fecha_actual between '$inicio' and '$fin' order by F.id asc");   
        while ($row1 = mysqli_fetch_array($resultado1)) {

            $pdf->SetX(20); 
            $pdf->Cell(22, 6, utf8_decode($row1[0]),0,0, 'C',0);                                     
            $pdf->Cell(24, 6, utf8_decode($row1[1]),0,0, 'C',0);                   

            $sub=$sub+$row1[6];
            $pdf->Cell(45, 6, number_format($row1[6],2,',','.'),0,0, 'C',0);                    
           // $pdf->Cell(16, 6, $row1[6],0,0, 'C',0);                    
           // $pdf->Cell(17, 6, $row1[7],0,0, 'C',0);    

            
            //$ivaT=$ivaT+$calIva;
            ///$calIva=($row1[2]*$impuesto)/100;
            $calIva=$calIva+$row1[5];
            $pdf->Cell(40, 6,number_format($row1[5],2,',','.'),0,0, 'C',0); 

            //$totalconIva=$calIva+$row1[2];
            $total=$total+$row1[2];
            //$t0 = $row1[6];
            $pdf->Cell(40, 6, number_format($row1[2],2,',','.'),0,0, 'C',0);                    
            //$pdf->Cell(20, 6, $row1[3],0,0, 'C',0);                    
            //$pdf->Cell(20, 6, $row1[5],0,0, 'C',0);                         
            $pdf->Ln(6);                            
        }                       
                       
    $pdf->SetX(1);                                             
    $pdf->Cell(207, 0, utf8_decode(""),1,1, 'R',0);     
    $pdf->SetX(19);                                            
    $pdf->Cell(50, 6, utf8_decode("Totales: "),0,0, 'R',0);
    $pdf->Cell(40, 6, number_format($sub,2,',','.'),0,0, 'C',0);                                                        
    $pdf->Cell(45, 6, number_format($calIva,2,',','.'),0,0, 'C',0);                        
    $pdf->Cell(35, 6, number_format($total,2,',','.'),0,0, 'C',0);                        
    $pdf->Ln(8);           
    $pdf->Output();
?>