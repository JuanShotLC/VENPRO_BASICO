<?php

  session_start();
  if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) {
        header("location: login.php");
    exit;
        }

  /* Connect To Database*/
  require_once ("config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
  require_once ("config/conexion.php");//Contiene funcion que conecta a la base de datos
  
  $active_facturas="";
  $active_productos="";
  $active_reportes="active";
  $active_clientes="";
  $active_usuarios="";  
  $title="Reportes | SIFONELC";
?>

<?php if($_SESSION['user_name'] == 'baraka') { ?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <?php include("head.php");?>


    <style type="text/css">
            #container {
                height: 400px;
                min-width: 310px;
                max-width: 800px;
                margin: 0 auto;
            }
<style type="text/css">
#container {
    height: 400px; 
    min-width: 310px; 
    max-width: 800px;
    margin: 0 auto;
}
        </style>
 

        
  </head>
  <body>
  <?php
  include("navbar.php");
    include("OTAS.php");
    include("modal/reporte_fecha.php");
    include("modal/reporte_nota.php");

        $ventas=new Ventas();

    $datos= $ventas->get_ventas_reporte_general();


    $datos_ano= $ventas->suma_ventas_total_ano();
  ?>
  
    <div class="container">
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

   <div class="page-content">
      <div class="row">
        <div class="col-sm-12">
          <div class="widget-box">
            <div class="widget-body">
              <div class="widget-main">
                <div class="row">
                  <div class="col-xs-12">

            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#reporteVenta" >
             VENTA GENERAL
            </button>
             <button type="button" class="btn btn-default" data-toggle="modal" data-target="#reporteNota" >
             VENTA GENERAL 2
            </button>
                    
                 
   
              
                  </div>
                </div>            
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
 
    
    <div class="row">

     <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">

        <div class="box">

           <div class="">

                  <h2 class="reporte_compras_general bg-success container-fluid text-white col-lg-12 text-center mh-50">REPORTE GENERAL DE VENTAS</h2>
                              
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th class="midnight-blue">AÑO</th>
                        <th class="midnight-blue">N° MES</th>
                        <th class="midnight-blue">NOMBRE MES</th>
                        <th class="midnight-blue">TOTAL</th>
                      </tr>
                    </thead>
                    <tbody>
                     
 <?php
                    
         
                   
                    for($i=0;$i<count($datos);$i++){


                    //imprime la fecha por separado ejemplo: dia, mes y año
                      $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
 
                       $fecha= $datos[$i]["mes"];

                       $fecha_mes = $meses[date("n", strtotime($fecha))-1];


                     ?>


                          <tr>
                            <td><?php echo $datos[$i]["ano"]?></td>
                            <td><?php echo $datos[$i]["numero_mes"]?></td>
                            <td><?php echo $fecha_mes?></td>
                         
                            <td>BS <?php  echo number_format ($datos[$i]["total_ventas"],2,',','.')?></td>
                          </tr>
                          
                      <?php

                       
                       }//cierre del for
                   

                      ?>
                      
                      
                  
                    </tbody>
                  </table>

           </div><!--fin box-body-->
      </div><!--fin box-->
            
        </div><!--fin col-xs-12-->

          <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
              <div class="box">

                 <div class="">

                     <h2 class="reporte_compras_general container-fluid bg-success text-white col-lg-12 text-center mh-50">PORCENTAJE POR AÑO</h2>
                    
                 
                 <table class="table table-bordered">
                     <thead>

                        <th class="midnight-blue">AÑO</th>
                        <th class="midnight-blue">TOTAL</th>
                        <th class="midnight-blue">PORCENTAJE %</th>
                        
                     </thead>

                     <tbody>

                    <?php
                       $arregloReg = array();
            
                     ?>
                 
                    <?php for($i=0; $i<count($datos_ano); $i++){
                  

                       array_push($arregloReg, 
                                array(
                    
                      
                     'ano' => $datos_ano[$i]["ano"],

                     'total_venta_ano' => $datos_ano[$i]["total_venta_ano"]
                               
                            )
                        );
               

                   }//cierre del primer ciclo for


                   //segundo for
                   $sumaTotal = 0;

                   for($j=0;$j<count($arregloReg);$j++){
                     
                     //sumo el total de los años
                     $sumaTotal = $sumaTotal + $datos_ano[$j]["total_venta_ano"];

                   }
                   
                  
                    $porcentaje_total=0;

                    for($i=0;$i<count($arregloReg);$i++) {

             //CALCULO DEL PORCENTAJE
              $dato_por_ano=$arregloReg[$i]["total_venta_ano"];

             
             $porcentaje_por_ano= round(($dato_por_ano/$sumaTotal)*100,2);  

              $porcentaje_total= $porcentaje_total+ $porcentaje_por_ano;
              


                        ?>

                     <tr>
                        <td><?php echo $arregloReg[$i]["ano"];?></td>
                        <td><?php echo number_format ($arregloReg[$i]["total_venta_ano"],2,',','.');?></td>
                        <td><?php echo $porcentaje_por_ano?>%</td>
                     </tr>

                     <?php 

                     } 

         
                    ?>

                    <tr>
                        <td><strong>Total:</strong>  </td>
                        <td><strong> <?php echo number_format ($sumaTotal,2,',','.')?> </strong></td>
                        <td> <strong> <?php echo $porcentaje_total?>% </strong></td>
                    </tr>

                    
                        
                     </tbody>

                 </table>


                 </div><!--fin box-body-->
               </div><!--fin box-->
          </div><!--fin col-xs-6-->
 
  <!--SEGUNDA FILA DE LA GRAFIA-->
       <!--  <div class="row"> -->

             <!--VENTAS HECHAS-->
<!-- 
             <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">

                <div class="box">

                   <div class="">

                    <h2 class="reporte_compras_general container-fluid bg-success text-white col-lg-12 text-center mh-50">REPORTE DE VENTAS MENSUALES</h2>
 -->
      
              <!--GRAFICA-->
               <!-- <div id="container" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div> -->



                    <!-- </div> --><!--fin box-body-->
               <!--  </div> --><!--fin box-->
           <!--  </div> --><!--fin col-lg-6-->

            
   





      <!--   </div> --><!--fin row-->
  <!-- </div> --><!--fin row-->
        
     


</div>
      </div>
  <hr>
  <?php
  include("footer.php");
  ?>

       
  </body>
</html>

<?php }         ?>