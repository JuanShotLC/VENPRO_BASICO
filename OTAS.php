<?PHP 
 require_once("config/conexion.php");

class Ventas extends Conectar{

public function numero_venta(){

		    $conectar=parent::conexion();
		    parent::set_names();

		 
		    $sql="SELECT numero_factura from facturas;";

		    $sql=$conectar->prepare($sql);

		    $sql->execute();
		    $resultado=$sql->fetchAll(PDO::FETCH_ASSOC);

		       //aqui selecciono solo un campo del array y lo recorro que es el campo numero_venta
		       foreach($resultado as $k=>$v){

		                 $numero_venta["numero"]=$v["numero_factura"];

		               
		          
		             }
		          //luego despues de tener seleccionado el numero_venta digo que si el campo numero_venta està vacio entonces se le asigna un F000001 de lo contrario ira sumando

		        

		                   if(empty($numero_venta["numero"]))
		                {
		                  echo 'F000001';
		                }else
		          
		                  {
		                    $num     = substr($numero_venta["numero"] , 1);
		                    $dig     = $num + 1;
		                    $fact = str_pad($dig, 6, "0", STR_PAD_LEFT);
		                    echo 'F'.$fact;
		                    //echo 'F'.$new_cod;
		                  } 

		       //return $data;
		  }


		   /*REPORTE VENTAS*/

        public function get_ventas_reporte_general(){

       $conectar=parent::conexion();
       parent::set_names();


      $sql="SELECT MONTHname(fecha_creacion) as mes, MONTH(fecha_creacion) as numero_mes, YEAR(fecha_creacion) as ano, SUM(total_venta) as total_ventas
        FROM facturas where estado='1' GROUP BY YEAR(fecha_creacion) desc, month(fecha_creacion) desc";

      
         $sql=$conectar->prepare($sql);

         $sql->execute();
         return $resultado=$sql->fetchAll(PDO::FETCH_ASSOC);

     }

     
     //suma el total de ventas por año

     public function suma_ventas_total_ano(){

      $conectar=parent::conexion();


       $sql="SELECT YEAR(fecha_creacion) as ano,SUM(total_venta) as total_venta_ano FROM facturas where estado='1' GROUP BY YEAR(fecha_creacion) desc";
           
           $sql=$conectar->prepare($sql);
           $sql->execute();

           return $resultado= $sql->fetchAll();


     }


///reportes de ventas diareas

       public function get_venta_por_fecha($fecha_inicial,$fecha_final){

        $conectar=parent::conexion();
        parent::set_names();
            
          
            $date_inicial = $_POST["datepicker"];
            $date = str_replace('/', '-', $date_inicial);
            $fecha_inicial = date("Y-m-d", strtotime($date));

          
            $date_final = $_POST["datepicker2"];
            $date = str_replace('/', '-', $date_final);
            $fecha_final = date("Y-m-d", strtotime($date));


        $sql="select * from facturas where  fecha_factura>=? and fecha_factura<=? and estado_factura='1';";

    
        $sql=$conectar->prepare($sql);

        $sql->bindValue(1,$fecha_inicial);
        $sql->bindValue(2,$fecha_final);
        $sql->execute();

        return $resultado=$sql->fetchAll(PDO::FETCH_ASSOC);
    }

   }
   ?>