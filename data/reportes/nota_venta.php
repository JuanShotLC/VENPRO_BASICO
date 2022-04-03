<?php
    include_once('../../fpdf/rotation.php');
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

    // $class = new constante();
    // $letras = new EnLetras();

class PDF extends PDF_Rotate {
    var $widths;
    var $aligns;

          function Header() {                         
            $this->AddFont('Amble-Regular','','Amble-Regular.php');
            $this->SetFont('Amble-Regular','',10);        
            $fecha = date('Y-m-d', time());
            $this->SetX(4);
            $this->SetY(4);
            // $this->Cell(20, 5, $fecha, 0,0, 'C', 0);                         
            // $this->Cell(150, 5, "ota de ", 0,1, 'R', 0);      
            $this->SetFont('Arial','B',14);                                                    
            //$this->Cell(190, 8, 'Rif.: '.$_SESSION['empresa']['ruc'], 0,1, 'C',0); 
            $this->Cell(190, 8,'Rif.:'.get_row('perfil','rif', 'id_perfil', 1), 0,1, 'C',0);  
            $this->Cell(190, 8, get_row('perfil','nombre_empresa', 'id_perfil', 1), 0,1, 'C',0);                                
            $this->Image('../../'.get_row('perfil','logo_url', 'id_perfil', 1),2,2,40,30);

            //$this->Image($_SESSION['empresa']['logo_empresa'],1,8,40,30);
             $this->SetFont('Amble-Regular','',10);        
            // $this->Cell(180, 5, "PROPIETARIO: ".utf8_decode($_SESSION['empresa']['propietario']),0,1, 'C',0);                                
            $this->Cell(160, 5,utf8_decode( get_row('perfil','direccion', 'id_perfil', 1)),0,5, 'R',0);   
            $this->SetFont('Amble-Regular','',10);        

            $this->Cell(115, 5,utf8_decode( get_row('perfil','ciudad', 'id_perfil', 1)."-".get_row('perfil','estado', 'id_perfil', 1)),0,5, 'R',0);

            $this->Cell(195, 5, utf8_decode("Teléfono: ").utf8_decode(get_row('perfil','telefono', 'id_perfil', 1)),0,1, 'C',0);                                
            $this->Cell(190, 5, "Correo: ".utf8_decode(get_row('perfil','email', 'id_perfil', 1)),0,1, 'C',0);                                
           // $this->Cell(170, 5, "SLOGAN.: ".utf8_decode($_SESSION['empresa']['slogan']),0,1, 'C',0);                                
            //$this->Cell(170, 5, utf8_decode( $_SESSION['empresa']['ciudad']),0,1, 'C',0);                                                                                                    
            $this->SetDrawColor(0,0,0);
            $this->SetLineWidth(0.4);            
            //$this->Line(1,50,210,50);            
            $this->SetFont('Arial','B',12);                                                                
                                                                               
            //$this->Cell(190, 12, utf8_decode("Nota de entrega"),0,1, 'C',0);                                                                                                                            
            $this->SetFont('Amble-Regular','',10);        
            $this->Ln(3);
            $this->SetFillColor(255,255,225);            
            $this->SetLineWidth(0.2);                                        
        }

        function Footer() {            
            $this->SetY(-15);            
            $this->SetFont('Arial','I',8);            
            // $this->Cell(0,10,'Pag. '.$this->PageNo().'/{nb}',0,0,'C');
        }

    function SetWidths($w) {
        //Set the array of column widths
        $this->widths=$w;
    }

    function SetAligns($a) {
        //Set the array of column alignments
        $this->aligns=$a;
    }

    function Row($data) {
        //Calculate the height of the row
        $nb=0;
        for($i=0; $i < count($data); $i++)
            $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
        $h=5*$nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of the row
        for($i=0; $i<count($data); $i++) {
            $w=$this->widths[$i];
            $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            //Save the current position
            $x=$this->GetX();
            $y=$this->GetY();
            //Draw the border

            $this->MultiCell( $w,5,$data[$i],0,$a,false);
            //Put the position to the right of the cell
            $this->SetXY($x+$w,$y);
        }
        //Go to the next line
        $this->Ln($h);
    }
    
    function CheckPageBreak($h) {
        //If the height h would cause an overflow, add a new page immediately
        if($this->GetY()+$h>$this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }

    function NbLines($w, $txt) {
        //Computes the number of lines a MultiCell of width w will take
        $cw=&$this->CurrentFont['cw'];
        if($w==0)
            $w=$this->w-$this->rMargin-$this->x;
        $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
        $s=str_replace("\r", '', $txt);
        $nb=strlen($s);
        if($nb>0 and $s[$nb-1]=="\n")
            $nb--;
        $sep =-1;
        $i=0;
        $j=0;
        $l=0;
        $nl=1;
        while($i<$nb) {
            $c = $s[$i];
            if($c == "\n") {
                $i++;
                $sep=-1;
                $j=$i;
                $l=0;
                $nl++;
                continue;
            }
            if($c==' ') 
                $sep=$i;
            $l+=$cw[$c];
            if($l>$wmax) {
                if($sep==-1) {
                    if($i==$j)
                        $i++;
                }
                else
                    $i=$sep+1;
                $sep=-1;
                $j=$i;
                $l=0;
                $nl++;
            }
            else
                $i++;
        }
        return $nl;
    }  
}

    $pdf = new PDF('P','mm','A4');
    $pdf->AddPage();
    $pdf->SetMargins(0,0,0,0);
    $pdf->SetFont('Arial','',9);

  
    $resultado = mysqli_query($con,"SELECT C.nombres_completos, C.identificacion, C.direccion, C.telefono2, C.ciudad,c.direccion, F.fecha_actual, F.forma_pago,F.subtotal, F.tarifa0, F.tarifa, F.iva_venta, F.descuento_venta, F.total_venta, F.serie, F.precinto_nro from nota_venta F, clientes C WHERE F.id_cliente = C.id AND F.id = '$_GET[id]'");    

    while ($row = mysqli_fetch_array($resultado)) {        
        $pdf->SetX(5);
        $pdf->SetFillColor(216, 216, 231);  
        $pdf->Cell(35, 6, utf8_decode('Fecha: ' . strtoupper($row[6])),1,0, 'L',true); ///FECHA (X,Y)
        $pdf->Cell(40, 6, utf8_decode('Nota de Entrega N° ' . strtoupper($row[14])),1,0, 'L',true); //Numero de factura 
        $pdf->Cell(40, 6, utf8_decode('N° Precinto: ' . strtoupper($row[15])),1,0, 'L',true); //Numero de factura 
        $pdf->Cell(85, 6, utf8_decode('Forma de pago: ' . strtoupper($row[7])),1,0, 'L',true); ///RIF/CI(X,Y)
       // $pdf->Cell(60, 6, utf8_decode('CLIENTE: ' . strtoupper($row[0])),1,0, 'L',true); ////CLIENTE (X,Y)  
        $pdf->ln();
        $pdf->SetX(5);
        $pdf->Cell(35, 6, utf8_decode('Rif./C.I.: ' . strtoupper($row[1])),1,0, 'L',true); ///FECHA (X,Y)
        $pdf->Cell(80, 6, utf8_decode('Cliente: ' . strtoupper($row[0])),1,0, 'L',true); //Numero de factura 
        $pdf->Cell(85, 6, utf8_decode('Domicilio fiscal: ' . strtoupper($row[2])),1,0, 'L',true); ///RIF/CI(X,Y)
        //$pdf->Cell(60, 6, utf8_decode('' . strtoupper($row[0])),1,0, 'L',true); ////CLIENTE (X,Y)    
    

        $pdf->Text(150, 240, utf8_decode('Sub-Total: ' . number_format(($row[8]),2,',','.') ), 0, 'C', 0); ////SUBTOTAL (X,Y)  

        $pdf->Text(150, 245, utf8_decode('IVA(16%): ' . number_format(($row[11]),2,',','.')   ), 0, 'C', 0); ////IVA (X,Y) 

        $pdf->Text(150, 250, utf8_decode('Total: ' . number_format(($row[13]),2,',','.')  ), 0, 'C', 0); ///Total (X,Y)  
        
                 
        $pdf->Ln(1);
    }
    $pdf->SetY(55);///PARA LOS DETALLES

    $pdf->SetX(5);///PARA LOS DETALLES
    $pdf->SetFont('Arial','',8);       
    $pdf->SetWidths(array(10, 50, 10, 10,10));//TAMAÑOS DE LA TABLA DE DETALLES PRODUCTOS

    $pdf->SetFillColor(216, 216, 231); 
    $pdf->Cell(40, 5, utf8_decode('REF.'),1,0, 'C',true); 
    $pdf->Cell(45, 5, utf8_decode('PRODUCTOS'),1,0, 'C',true);
    $pdf->Cell(20, 5, utf8_decode('CANT.'),1,0, 'C',true);  
    $pdf->Cell(45, 5, utf8_decode('PRECIO UNIT.'),1,0, 'C',true);
    $pdf->Cell(50, 5, utf8_decode('TOTAL PRECIO'),1,0, 'C',true); 


    $pdf->SetY(1);///PARA LOS DETALLES
    $pdf->SetFont('Arial','',8);       
    $pdf->SetWidths(array(10, 50, 10, 10,10));//TAMAÑOS DE LA TABLA DE DETALLES PRODUCTOS
    //$pdf->SetFillColor(216, 216, 231); 


                
    $resultado =  mysqli_query($con,"SELECT D.cantidad, P.codigo_producto, P.nombre_producto, D.precio, D.total FROM nota_venta F, detalle_nota D, products P WHERE F.id = D.id_nota AND D.id_producto = P.id_producto AND D.id_nota = '$_GET[id]'");
    
    $total_items = 0;
    $caracteres = 20;
    $posiciony = 60;
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

        $pdf->SetY($posiciony);
        $pdf->SetX(5);        
        $pdf->multiCell(40, 5, utf8_decode($codigo),0,1);


        $pdf->SetY($posiciony);
        $pdf->SetX(45);
        $pdf->multiCell(50, 5, utf8_decode(substr($descripcion, 0, $caracteres)),0,1);


        $pdf->SetY($posiciony);
        $pdf->SetX(90);
        $pdf->multiCell(20, 5, utf8_decode($cantidad),0,"C",false);

        $pdf->SetY($posiciony);
        $pdf->SetX(90);
        $pdf->multiCell(50, 5, (number_format(($valor_unitario),2,',','.')),0,0);

        // $pdf->SetY($posiciony);
        // $pdf->SetX(130);
        // $pdf->multiCell(75, 5, utf8_decode($sub3),0);

        $pdf->SetY($posiciony);
        $pdf->SetX(130);
        $pdf->multiCell(50, 5, number_format(($total_venta),2,',','.'),0,0);


       $posiciony = $posiciony + 5;
    }

    $pdf->SetFont('Arial','',8);
   $pdf->Text(5, 250, utf8_decode('Nro. de Items: ' . $total_items ), 0, 'C', 0); ////SUBTOTAL (X,Y)
   //    $pdf->Image('../../dist/images/firma.png',15,235,40,30); ////SUBTOTAL (X,Y) 
   // $pdf->Image('../../dist/images/sello.png',50,235,40,30); ////SUBTOTAL (X,Y)  

    $pdf->Output('Nota_venta_'.$_GET['id'].'.pdf','I');
?>