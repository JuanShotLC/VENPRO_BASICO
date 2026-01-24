<?php        
	//include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
	/* Connect To Database*/
	require_once ("../../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../../config/conexion.php");//Contiene funcion que conecta a la base de datos

	session_start(); 
	error_reporting(0);

	if ($_POST["select_tipo_comprobante"] == 1) {

		if (isset($_POST['btn_guardar']) == "btn_guardar") {

			$fecha = date('Y-m-d H:i:s', time());
			$fecha_corta = date('Y-m-d');

			// contador factura
			$id_factura = 0;
			$resultado = mysqli_query($con,"SELECT max(id) FROM facturas");
			while ($row = mysqli_fetch_array($resultado)) {
				$id_factura = $row[0];
			}
			$id_factura++;
			// fin

			// comparar clientes
			if ($_POST['id_cliente'] == "") {
				// contador clientes
				$id_cliente = 0;
				$resultado = mysqli_query($con,"SELECT max(id) FROM clientes");
				while ($row = mysqli_fetch_array($resultado)) {
					$id_cliente = $row[0];
				}
				$id_cliente++;
				// fin
				if (strlen($_POST['ruc']) == 10) {
					// guardar cliente cedula
						$resp = mysqli_query($con,"INSERT INTO clientes VALUES  (	'$id_cliente',
																					'CEDULA',
																					'$_POST[ruc]',
																					'$_POST[cliente]',
																					'',
																					'$_POST[telefono]',
																					'',
																					'$_POST[direccion]',
																					'$_POST[correo]',
																					'0.00',
																					'',
																					'1', 
																					'$fecha')");
					// fin
					// fin
				} else {
					if (strlen($_POST['ruc']) == 12) 
					{
					  // guardar cliente ruc
						$resp = mysqli_query($con,"INSERT INTO clientes VALUES  (	'$id_cliente',
																					'RIF',
																					'$_POST[ruc]',
																					'$_POST[cliente]',
																					'',
																					'$_POST[telefono]',
																					'',
																					'$_POST[direccion]',
																					'$_POST[correo]',
																					'0.00',
																					'',
																					'1', 
																					'$fecha')");
					  // fin
					}	
				}
				// guardar factura con nuevo cliente
				$resp =  mysqli_query($con,"INSERT INTO facturas VALUES  (	'$id_factura',
																				'$id_cliente',
																				'".$_SESSION['user_id']."',
																				'$id_factura',
																				'$_POST[serie]',
																				'$_POST[fecha_actual]',
																				'$_POST[hora_actual]',
																				'',
																				'$_POST[select_tipo_comprobante]',
																				'$_POST[select_tipo_precio]',
																				'$_POST[select_forma_pago]',
																				'',
																				'',
																				'',
																				'$_POST[subtotal]',
																				'$_POST[tarifa_0]',
																				'$_POST[tarifa]',
																				'$_POST[iva]',
																				'$_POST[otros]',
																				'$_POST[total_pagar]',
																				'$_POST[efectivo]',
																				'$_POST[cambio]',
																				'',
																				'',
																				'1', 
																				'$fecha',
																				'$_POST[precinto_nro]',
																				'$_POST[factor]',
																				'$_POST[divisas]',
																				'$_POST[iva_igtf]',																				
																				'$_POST[total_pagar_dolar]')");

			} else {
				// guardar factura
				$resp =  mysqli_query($con,"INSERT INTO facturas VALUES  (		'$id_factura',
																				'$_POST[id_cliente]',
																				'".$_SESSION['user_id']."',
																				'$id_factura',
																				'$_POST[serie]',
																				'$_POST[fecha_actual]',
																				'$_POST[hora_actual]',
																				'',
																				'$_POST[select_tipo_comprobante]',
																				'$_POST[select_tipo_precio]',
																				'$_POST[select_forma_pago]',
																				'',
																				'',
																				'',
																				'$_POST[subtotal]',
																				'$_POST[tarifa_0]',
																				'$_POST[tarifa]',
																				'$_POST[iva]',
																				'$_POST[otros]',
																				'$_POST[total_pagar]',
																				'$_POST[efectivo]',
																				'$_POST[cambio]',
																				'',
																				'',
																				'1', 
																				'$fecha',
																				'$_POST[precinto_nro]',
																				'$_POST[factor]',
																				'$_POST[divisas]',
																				'$_POST[iva_igtf]',																				
																				'$_POST[total_pagar_dolar]')");
			}
			// fin

			
			// modificar proformas
	        if ($_POST['id_proforma'] != "") {
	        	$class->consulta("UPDATE proforma SET estado = '2' WHERE id = '".$_POST['id_proforma']."'");
	        }
	        // // fin

			// datos detalle factura
			$campo1 = $_POST['campo1'];
		    $campo2 = $_POST['campo2'];
		    $campo3 = $_POST['campo3'];
		    $campo4 = $_POST['campo4'];
		    $campo5 = $_POST['campo5'];
		    $campo6 = $_POST['campo6'];
		    // Fin

		    // descomponer detalle factura
			$arreglo1 = explode('|', $campo1);
		    $arreglo2 = explode('|', $campo2);
		    $arreglo3 = explode('|', $campo3);
		    $arreglo4 = explode('|', $campo4);
		    $arreglo5 = explode('|', $campo5);
		    $arreglo6 = explode('|', $campo6);
		    $nelem = count($arreglo1);
		    // fin

		    for ($i = 1; $i < $nelem; $i++) {
		    	$id_detalle_proforma = uniqid();

		    	// contador detalle factura
				$id_detalle_factura = 0;
				$resultado = mysqli_query($con,"SELECT max(id_detalle) FROM detalle_factura");
				while ($row = mysqli_fetch_array($resultado)) {

					$id_detalle_factura = $row[0];
				}

				$id_detalle_factura++;
				// fin

				 $resp =mysqli_query($con,"INSERT INTO detalle_factura VALUES ( 	'$id_detalle_factura',
																						'$id_factura',
																						'".$arreglo1[$i]."',
																						'".$arreglo2[$i]."',
																						'".$arreglo3[$i]."',
																						'".$arreglo4[$i]."',
																						'".$arreglo5[$i]."',
																						'".$arreglo6[$i]."',
																						'1', 
																						'$fecha')");
					
				// modificar productos
	           	$consulta = mysqli_query($con,"SELECT * FROM products WHERE id_producto = '".$arreglo1[$i]."'");
	           	while ($row =mysqli_fetch_array($consulta)) {
	                $stock = $row[7];
	            }

	            $cal = $stock - $arreglo2[$i];
	            $consulta2 =mysqli_query($con,"UPDATE products SET stock = '$cal' WHERE id_producto = '".$arreglo1[$i]."'");
	            // fin

	           
	            // contador kardex
				$id_kardex = 0;
				$resultado =mysqli_query($con,"SELECT max(id_historial) FROM historial");
				while ($row = mysqli_fetch_array($resultado)) {
					$id_kardex = $row[0];
				}
				$id_kardex++;
				// fin

				// guardar kardex
				$consula3 = mysqli_query($con,"INSERT INTO historial VALUES ('$id_kardex',
																		'".$arreglo1[$i]."',
																		'".$_SESSION['user_id']."',
																		'$fecha',
																		'".'F.V:'.$_POST[serie]."',
																		'".$arreglo1[$i]."',
																		'".$arreglo2[$i]."')");
	

				// // fin
		    }
			echo $id_factura;
		}

		// anular facturas
		if (isset($_POST['btn_anular']) == "btn_anular") {

		

			$fecha = date('Y-m-d H:i:s', time());
			$fecha_corta = date('Y-m-d');

			$anular=mysqli_query($con,"UPDATE facturas SET fecha_anulacion = '$_POST[fecha_actual]', estado = '2'  WHERE id = '$_POST[id_factura]'");
			$anular1=mysqli_query($con,"UPDATE detalle_factura SET  estado = '2'  WHERE id_factura = '$_POST[id_factura]'");

			// datos detalle factura
			$campo1 = $_POST['campo1'];
		    $campo2 = $_POST['campo2'];
		    // Fin

		    // descomponer detalle factura
			$arreglo1 = explode('|', $campo1);
		    $arreglo2 = explode('|', $campo2);
		    $nelem = count($arreglo1);
		    // fin

		    for ($i = 1; $i < $nelem; $i++) {
		    	// modificar productos
	           	$consulta = mysqli_query($con,"SELECT * FROM products WHERE id_producto = '".$arreglo1[$i]."'");
	           	while ($row = mysqli_fetch_array($consulta)) {
	                $stock = $row[7];
	            }

	            $cal = $stock + $arreglo2[$i];
	            $anular=mysqli_query($con,"UPDATE products SET stock = '$cal' WHERE id_producto = '".$arreglo1[$i]."'");
	            // fin

	             // contador kardex
				$id_kardex = 0;
				$resultado =mysqli_query($con,"SELECT max(id_historial) FROM historial");
				while ($row = mysqli_fetch_array($resultado)) {
					$id_kardex = $row[0];
				}
				$id_kardex++;
				// fin

				// guardar kardex
				$consula3 = mysqli_query($con,"INSERT INTO historial VALUES (	'$id_kardex',
																				'".$arreglo1[$i]."',
																				'".$_SESSION['user_id']."',
																				'$fecha',
																				'".'Anulada F.V:'.$_POST[serie]."',
																				'".$arreglo1[$i]."',
																				'".$arreglo2[$i]."')");
	
		    }

		    $data = 1;
			echo $data;
		}

		// fin
	} elseif ($_POST["select_tipo_comprobante"] == 2 ) {

		
			
			// guardar notas venta
		if (isset($_POST['btn_guardar']) == "btn_guardar") {
				$fecha = date('Y-m-d H:i:s', time());
				$fecha_corta = date('Y-m-d');

				// contador nota venta
				$id_nota = 0;
				$resultado = mysqli_query($con,"SELECT max(id) FROM nota_venta");
				while ($row = mysqli_fetch_array($resultado)) {
					$id_nota = $row[0];
				}
				$id_nota++;
				// fin

			    // comparar clientes
                if ($_POST['id_cliente'] == "") {
                    // contador clientes
                    $id_cliente = 0;
                    $resultado = mysqli_query($con,"SELECT max(id) FROM clientes");
						while ($row = mysqli_fetch_array($resultado)) {
							$id_cliente = $row[0];
						}
                    $id_cliente++;
                    // fin
                    if (strlen($_POST['ruc']) == 10) {
                        // guardar cliente cedula
                        $resp = mysqli_query($con,"INSERT INTO clientes VALUES  (	    '$id_cliente',
                                                                                        'CEDULA',
                                                                                        '$_POST[ruc]',
                                                                                        '$_POST[cliente]',
                                                                                        '',
                                                                                        '$_POST[telefono]',
                                                                                        '',
                                                                                        '$_POST[direccion]',
                                                                                        '$_POST[correo]',
                                                                                        '0.00',
                                                                                        '',
                                                                                        '1', 
                                                                                        '$fecha')");
                        // fin

                    } elseif(strlen($_POST['ruc']) == 12) {
                        	// guardar cliente ruc
                            $resp = mysqli_query($con,"INSERT INTO clientes VALUES  (	'$id_cliente',
                                                                                        'RIF',
                                                                                        '$_POST[ruc]',
                                                                                        '$_POST[cliente]',
                                                                                        '',
                                                                                        '$_POST[telefono]',
                                                                                        '',
                                                                                        '$_POST[direccion]',
                                                                                        '$_POST[correo]',
                                                                                        '0.00',
                                                                                        '',
                                                                                        '1', 
                                                                                        '$fecha')");
                        	// fin
                  
                    }

                    // guardar nota con nuevo cliente
					$resp = mysqli_query($con,"INSERT INTO nota_venta VALUES (		'$id_nota',
																					'$id_cliente',
																					'".$_SESSION['user_id']."',
																					'$id_nota',
																					'$id_nota',
																					'$_POST[fecha_actual]',
																					'$_POST[hora_actual]',
																					'',
																					'$_POST[select_tipo_comprobante]',
																					'$_POST[select_tipo_precio]',
																					'$_POST[select_forma_pago]',
																					'',
																					'',
																					'',
																					'$_POST[subtotal]',
																					'$_POST[tarifa_0]',
																					'$_POST[tarifa]',
																					'$_POST[iva]',
																					'$_POST[otros]',
																					'$_POST[total_pagar]',
																					'$_POST[efectivo]',
																					'$_POST[cambio]',
																					'',
																					'',
																					'1', 
																					'$fecha',
																					'$_POST[precinto_nro]',
																					'$_POST[factor]',
																					'$_POST[divisas]',
																					'$_POST[iva_igtf]',																				
																					'$_POST[total_pagar_dolar]')");
                    // fin
                        
                } else {
                    // guardar nota con cliente existente
                    $resp = mysqli_query($con,"INSERT INTO nota_venta VALUES (		'$id_nota',
                                                                                    '$_POST[id_cliente]',
                                                                                    '".$_SESSION['user_id']."',
                                                                                    '$id_nota',
                                                                                    '$id_nota',
                                                                                    '$_POST[fecha_actual]',
                                                                                    '$_POST[hora_actual]',
                                                                                    '',
                                                                                    '$_POST[select_tipo_comprobante]',
                                                                                    '$_POST[select_tipo_precio]',
                                                                                    '$_POST[select_forma_pago]',
                                                                                    '',
                                                                                    '',
                                                                                    '',
                                                                                    '$_POST[subtotal]',
                                                                                    '$_POST[tarifa_0]',
                                                                                    '$_POST[tarifa]',
                                                                                    '$_POST[iva]',
                                                                                    '$_POST[otros]',
                                                                                    '$_POST[total_pagar]',
                                                                                    '$_POST[efectivo]',
                                                                                    '$_POST[cambio]',
                                                                                    '',
                                                                                    '',
                                                                                    '1', 
                                                                                    '$fecha',
                                                                                    '$_POST[precinto_nro]',
                                                                                    '$_POST[factor]',
                                                                                    '$_POST[divisas]',
                                                                                    '$_POST[iva_igtf]',																				
                                                                                    '$_POST[total_pagar_dolar]')");
                    // fin	
                }
                	// modificar proformas
                if ($_POST['id_proforma'] != "") {
                    $class->consulta("UPDATE proforma SET estado = '2' WHERE id = '".$_POST['id_proforma']."'");
                }
                	// fin

                    // datos detalle nota
                    $campo1 = $_POST['campo1'];
                    $campo2 = $_POST['campo2'];
                    $campo3 = $_POST['campo3'];
                    $campo4 = $_POST['campo4'];
                    $campo5 = $_POST['campo5'];
                    $campo6 = $_POST['campo6'];
                    // Fin

                    // descomponer detalle nota
                    $arreglo1 = explode('|', $campo1);
                    $arreglo2 = explode('|', $campo2);
                    $arreglo3 = explode('|', $campo3);
                    $arreglo4 = explode('|', $campo4);
                    $arreglo5 = explode('|', $campo5);
                    $arreglo6 = explode('|', $campo6);
                    $nelem = count($arreglo1);
                    // fin

                    for ($i = 1; $i < $nelem; $i++) {
                                $id_detalle_proforma = uniqid();

                                // contador detalle factura
                                $id_detalle_nota = 0;
                                $resultado = mysqli_query($con,"SELECT max(id_detalle) FROM detalle_nota");
                                while ($row = mysqli_fetch_array($resultado)) {

                                    $id_detalle_nota = $row[0];
                                }

                                $id_detalle_nota++;
                                // fin

                                $resp =mysqli_query($con,"INSERT INTO detalle_nota VALUES ( 			'$id_detalle_nota',
                                                                                                        '$id_nota',
                                                                                                        '".$arreglo1[$i]."',
                                                                                                        '".$arreglo2[$i]."',
                                                                                                        '".$arreglo3[$i]."',
                                                                                                        '".$arreglo4[$i]."',
                                                                                                        '".$arreglo5[$i]."',
                                                                                                        '".$arreglo6[$i]."',
                                                                                                        '1', 
                                                                                                        '$fecha')");
                                    
                                // modificar productos
                                $consulta = mysqli_query($con,"SELECT * FROM products WHERE id_producto = '".$arreglo1[$i]."'");
                                while ($row =mysqli_fetch_array($consulta)) {
                                    $stock = $row[7];
                                }

                                $cal = $stock - $arreglo2[$i];
                                $consulta2 =mysqli_query($con,"UPDATE products SET stock = '$cal' WHERE id_producto = '".$arreglo1[$i]."'");
                                // fin

                            
                                // contador kardex
                                $id_kardex = 0;
                                $resultado =mysqli_query($con,"SELECT max(id_historial) FROM historial");
                                while ($row = mysqli_fetch_array($resultado)) {
                                    $id_kardex = $row[0];
                                }
                                $id_kardex++;
                                // fin

                                // guardar kardex
                                $consula3 = mysqli_query($con,"INSERT INTO historial VALUES ('$id_kardex',
                                                                                        '".$arreglo1[$i]."',
                                                                                        '".$_SESSION['user_id']."',
                                                                                        '$fecha',
                                                                                        '".'N.'.$id_nota."',
                                                                                        '".$arreglo1[$i]."',
                                                                                        '".$arreglo2[$i]."')");				



                    }


                echo $id_nota;
                    
		}
			// fin
		// anular nota
		if (isset($_POST['btn_anular_2']) == "btn_anular_2") {

            $fecha = date('Y-m-d H:i:s', time());
            $fecha_corta = date('Y-m-d');

            $anular=mysqli_query($con,"UPDATE nota_venta SET fecha_anulacion = '$_POST[fecha_actual]', estado = '2'  WHERE id = '$_POST[id_nota]'");
            $anular1=mysqli_query($con,"UPDATE detalle_nota SET  estado = '2'  WHERE id_nota = '$_POST[id_nota]'");

            // datos detalle factura
            $campo1 = $_POST['campo1'];
            $campo2 = $_POST['campo2'];
            // Fin

            // descomponer detalle factura
            $arreglo1 = explode('|', $campo1);
            $arreglo2 = explode('|', $campo2);
            $nelem = count($arreglo1);
            // fin

            for ($i = 1; $i < $nelem; $i++) {
                // modificar productos
                $consulta = mysqli_query($con,"SELECT * FROM products WHERE id_producto = '".$arreglo1[$i]."'");
                while ($row = mysqli_fetch_array($consulta)) {
                    $stock = $row[7];
                }

                $cal = $stock + $arreglo2[$i];
                $anular=mysqli_query($con,"UPDATE products SET stock = '$cal' WHERE id_producto = '".$arreglo1[$i]."'");
                // fin

                // contador kardex
                $id_kardex = 0;
                $resultado =mysqli_query($con,"SELECT max(id_historial) FROM historial");
                while ($row = mysqli_fetch_array($resultado)) {
                    $id_kardex = $row[0];
                }
                $id_kardex++;
                // fin

                // guardar kardex
                $consula3 = mysqli_query($con,"INSERT INTO historial VALUES (	'$id_kardex',
                                                                                '".$arreglo1[$i]."',
                                                                                '".$_SESSION['user_id']."',
                                                                                '$fecha',
                                                                                '".'A.N:'.$_POST[serie]."',
                                                                                '$_POST[serie]',
                                                                                '".$arreglo2[$i]."')");
    
            }

            $data = 1;
            echo $data;
        

    	}

		
	} elseif ($_POST["select_tipo_comprobante"] == 3 ) {

		
			
			// guardar notas venta
		if (isset($_POST['btn_guardar']) == "btn_guardar") {
				$fecha = date('Y-m-d H:i:s', time());
				$fecha_corta = date('Y-m-d');

				// contador nota venta
				$id_nota = 0;
				$resultado = mysqli_query($con,"SELECT max(id) FROM nota_venta");
				while ($row = mysqli_fetch_array($resultado)) {
					$id_nota = $row[0];
				}
				$id_nota++;
				// fin

			    // comparar clientes
                if ($_POST['id_cliente'] == "") {
                    // contador clientes
                    $id_cliente = 0;
                    $resultado = mysqli_query($con,"SELECT max(id) FROM clientes");
						while ($row = mysqli_fetch_array($resultado)) {
							$id_cliente = $row[0];
						}
                    $id_cliente++;
                    // fin
                    if (strlen($_POST['ruc']) == 10) {
                        // guardar cliente cedula
                        $resp = mysqli_query($con,"INSERT INTO clientes VALUES  (	    '$id_cliente',
                                                                                        'CEDULA',
                                                                                        '$_POST[ruc]',
                                                                                        '$_POST[cliente]',
                                                                                        '',
                                                                                        '$_POST[telefono]',
                                                                                        '',
                                                                                        '$_POST[direccion]',
                                                                                        '$_POST[correo]',
                                                                                        '0.00',
                                                                                        '',
                                                                                        '1', 
                                                                                        '$fecha')");
                        // fin

                    } elseif(strlen($_POST['ruc']) == 12) {
                        	// guardar cliente ruc
                            $resp = mysqli_query($con,"INSERT INTO clientes VALUES  (	'$id_cliente',
                                                                                        'RIF',
                                                                                        '$_POST[ruc]',
                                                                                        '$_POST[cliente]',
                                                                                        '',
                                                                                        '$_POST[telefono]',
                                                                                        '',
                                                                                        '$_POST[direccion]',
                                                                                        '$_POST[correo]',
                                                                                        '0.00',
                                                                                        '',
                                                                                        '1', 
                                                                                        '$fecha')");
                        	// fin
                  
                    }

                    // guardar nota con nuevo cliente
					$resp = mysqli_query($con,"INSERT INTO nota_venta VALUES (		'$id_nota',
																					'$id_cliente',
																					'".$_SESSION['user_id']."',
																					'$id_nota',
																					'$id_nota',
																					'$_POST[fecha_actual]',
																					'$_POST[hora_actual]',
																					'',
																					'$_POST[select_tipo_comprobante]',
																					'$_POST[select_tipo_precio]',
																					'$_POST[select_forma_pago]',
																					'',
																					'',
																					'',
																					'$_POST[subtotal]',
																					'$_POST[tarifa_0]',
																					'$_POST[tarifa]',
																					'$_POST[iva]',
																					'$_POST[otros]',
																					'$_POST[total_pagar]',
																					'$_POST[efectivo]',
																					'$_POST[cambio]',
																					'',
																					'',
																					'1', 
																					'$fecha',
																					'$_POST[precinto_nro]',
																					'$_POST[factor]',
																					'$_POST[divisas]',
																					'$_POST[iva_igtf]',																				
																					'$_POST[total_pagar_dolar]')");
                    // fin
                        
                } else {
                    // guardar nota con cliente existente
                    $resp = mysqli_query($con,"INSERT INTO nota_venta VALUES (		'$id_nota',
                                                                                    '$_POST[id_cliente]',
                                                                                    '".$_SESSION['user_id']."',
                                                                                    '$id_nota',
                                                                                    '$id_nota',
                                                                                    '$_POST[fecha_actual]',
                                                                                    '$_POST[hora_actual]',
                                                                                    '',
                                                                                    '$_POST[select_tipo_comprobante]',
                                                                                    '$_POST[select_tipo_precio]',
                                                                                    '$_POST[select_forma_pago]',
                                                                                    '',
                                                                                    '',
                                                                                    '',
                                                                                    '$_POST[subtotal]',
                                                                                    '$_POST[tarifa_0]',
                                                                                    '$_POST[tarifa]',
                                                                                    '$_POST[iva]',
                                                                                    '$_POST[otros]',
                                                                                    '$_POST[total_pagar]',
                                                                                    '$_POST[efectivo]',
                                                                                    '$_POST[cambio]',
                                                                                    '',
                                                                                    '',
                                                                                    '1', 
                                                                                    '$fecha',
                                                                                    '$_POST[precinto_nro]',
                                                                                    '$_POST[factor]',
                                                                                    '$_POST[divisas]',
                                                                                    '$_POST[iva_igtf]',																				
                                                                                    '$_POST[total_pagar_dolar]')");
                    // fin	
                }
                	// modificar proformas
                if ($_POST['id_proforma'] != "") {
                    $class->consulta("UPDATE proforma SET estado = '2' WHERE id = '".$_POST['id_proforma']."'");
                }
                	// fin

                    // datos detalle nota
                    $campo1 = $_POST['campo1'];
                    $campo2 = $_POST['campo2'];
                    $campo3 = $_POST['campo3'];
                    $campo4 = $_POST['campo4'];
                    $campo5 = $_POST['campo5'];
                    $campo6 = $_POST['campo6'];
                    // Fin

                    // descomponer detalle nota
                    $arreglo1 = explode('|', $campo1);
                    $arreglo2 = explode('|', $campo2);
                    $arreglo3 = explode('|', $campo3);
                    $arreglo4 = explode('|', $campo4);
                    $arreglo5 = explode('|', $campo5);
                    $arreglo6 = explode('|', $campo6);
                    $nelem = count($arreglo1);
                    // fin

                    for ($i = 1; $i < $nelem; $i++) {
                                $id_detalle_proforma = uniqid();

                                // contador detalle factura
                                $id_detalle_nota = 0;
                                $resultado = mysqli_query($con,"SELECT max(id_detalle) FROM detalle_nota");
                                while ($row = mysqli_fetch_array($resultado)) {

                                    $id_detalle_nota = $row[0];
                                }

                                $id_detalle_nota++;
                                // fin

                                $resp =mysqli_query($con,"INSERT INTO detalle_nota VALUES ( 			'$id_detalle_nota',
                                                                                                        '$id_nota',
                                                                                                        '".$arreglo1[$i]."',
                                                                                                        '".$arreglo2[$i]."',
                                                                                                        '".$arreglo3[$i]."',
                                                                                                        '".$arreglo4[$i]."',
                                                                                                        '".$arreglo5[$i]."',
                                                                                                        '".$arreglo6[$i]."',
                                                                                                        '1', 
                                                                                                        '$fecha')");
                                    
                                // modificar productos
                                $consulta = mysqli_query($con,"SELECT * FROM products WHERE id_producto = '".$arreglo1[$i]."'");
                                while ($row =mysqli_fetch_array($consulta)) {
                                    $stock = $row[7];
                                }

                                $cal = $stock - $arreglo2[$i];
                                $consulta2 =mysqli_query($con,"UPDATE products SET stock = '$cal' WHERE id_producto = '".$arreglo1[$i]."'");
                                // fin

                            
                                // contador kardex
                                $id_kardex = 0;
                                $resultado =mysqli_query($con,"SELECT max(id_historial) FROM historial");
                                while ($row = mysqli_fetch_array($resultado)) {
                                    $id_kardex = $row[0];
                                }
                                $id_kardex++;
                                // fin

                                // guardar kardex
                                $consula3 = mysqli_query($con,"INSERT INTO historial VALUES ('$id_kardex',
                                                                                        '".$arreglo1[$i]."',
                                                                                        '".$_SESSION['user_id']."',
                                                                                        '$fecha',
                                                                                        '".'N.'.$id_nota."',
                                                                                        '".$arreglo1[$i]."',
                                                                                        '".$arreglo2[$i]."')");				



                    }


                echo $id_nota;
                    
		}
			// fin
		// anular nota
		if (isset($_POST['btn_anular_2']) == "btn_anular_2") {

            $fecha = date('Y-m-d H:i:s', time());
            $fecha_corta = date('Y-m-d');

            $anular=mysqli_query($con,"UPDATE nota_venta SET fecha_anulacion = '$_POST[fecha_actual]', estado = '2'  WHERE id = '$_POST[id_nota]'");
            $anular1=mysqli_query($con,"UPDATE detalle_nota SET  estado = '2'  WHERE id_nota = '$_POST[id_nota]'");

            // datos detalle factura
            $campo1 = $_POST['campo1'];
            $campo2 = $_POST['campo2'];
            // Fin

            // descomponer detalle factura
            $arreglo1 = explode('|', $campo1);
            $arreglo2 = explode('|', $campo2);
            $nelem = count($arreglo1);
            // fin

            for ($i = 1; $i < $nelem; $i++) {
                // modificar productos
                $consulta = mysqli_query($con,"SELECT * FROM products WHERE id_producto = '".$arreglo1[$i]."'");
                while ($row = mysqli_fetch_array($consulta)) {
                    $stock = $row[7];
                }

                $cal = $stock + $arreglo2[$i];
                $anular=mysqli_query($con,"UPDATE products SET stock = '$cal' WHERE id_producto = '".$arreglo1[$i]."'");
                // fin

                // contador kardex
                $id_kardex = 0;
                $resultado =mysqli_query($con,"SELECT max(id_historial) FROM historial");
                while ($row = mysqli_fetch_array($resultado)) {
                    $id_kardex = $row[0];
                }
                $id_kardex++;
                // fin

                // guardar kardex
                $consula3 = mysqli_query($con,"INSERT INTO historial VALUES (	'$id_kardex',
                                                                                '".$arreglo1[$i]."',
                                                                                '".$_SESSION['user_id']."',
                                                                                '$fecha',
                                                                                '".'A.N:'.$_POST[serie]."',
                                                                                '$_POST[serie]',
                                                                                '".$arreglo2[$i]."')");
    
            }

            $data = 1;
            echo $data;
        

    	}

		
	}

	//cargar facturero
	if (isset($_POST['cargar_facturero'])) {
		$resultado =  mysqli_query($con,"SELECT * FROM facturero WHERE estado = '1'");

		while ($row = mysqli_fetch_array($resultado)) {

			$data_facturero = array(
							'fecha_inicio' => $row[1],
							'fecha_caducidad' => $row[2],
							'inicio_facturero' => $row[3],
							'finaliza_facturero' => $row[4],
							'num_items' => $row[6]);
		}
		print_r(json_encode($data_facturero));
	}
	//fin

	//cargar ultima serie factura venta
	if (isset($_POST['cargar_series'])) {

		$resultado =  mysqli_query($con,"SELECT MAX(serie) FROM facturas GROUP BY id ORDER BY id asc");
		while ($row = mysqli_fetch_array($resultado)) {

			$data = array('serie' => $row[0]);
		}
		print_r(json_encode($data));
	}
	//fin

		//cargar IVA
	if (isset($_POST['cargar_iva'])) {

		$resultado =  mysqli_query($con,"SELECT * FROM perfil ");
		while ($row = mysqli_fetch_array($resultado)) {

			$data = array('iva' => $row[9],
						  'igtf' => $row[12]
		
						);
		}
		print_r(json_encode($data));
	}
	//fin

	//Cargar Factor
	if (isset($_POST['consultar_factor'])) {

		$resultado =  mysqli_query($con,"SELECT * FROM cotizacion");
			while ($row = mysqli_fetch_array($resultado)) {
			$data = array('factor' => $row[1]);
		}
		print_r(json_encode($data));
	}
	//fin

	// LLenar tipo comprobante
	if (isset($_POST['llenar_tipo_comprobante'])) {

		$resultado = mysqli_query($con,"SELECT id, codigo ,nombre_tipo_comprobante, principal FROM tipo_comprobante WHERE estado = '1' order by id asc");
		print'<option value="">&nbsp;</option>';
		while ($row = mysqli_fetch_array($resultado)) {
			if($row['principal'] == 'Si') {
				print '<option value="'.$row['id'].'" selected>'.$row['codigo'].' - '.$row['nombre_tipo_comprobante'].'</option>';	
			} else {
				print '<option value="'.$row['id'].'">'.$row['codigo'].' - '.$row['nombre_tipo_comprobante'].'</option>';	
			}
		}
	}
	// fin

		// LLenar tipo factura
	if (isset($_POST['llenar_tipo_factura'])) {

		$resultado = mysqli_query($con,"SELECT id, codigo ,nombre_tipo_factura, principal FROM tipos_facturas WHERE estado = '1' order by id asc");
		print'<option value="">&nbsp;</option>';
		while ($row = mysqli_fetch_array($resultado)) {
			if($row['principal'] == 'Si') {
				print '<option value="'.$row['id'].'" selected>'.$row['codigo'].' - '.$row['nombre_tipo_factura'].'</option>';	
			} else {
				print '<option value="'.$row['id'].'">'.$row['codigo'].' - '.$row['nombre_tipo_factura'].'</option>';	
			}
		}
	}
	// fin

	// llenar cabezera factura venta
	if (isset($_POST['llenar_cabezera_factura'])) {
		$resultado = mysqli_query($con,"SELECT F.id, F.fecha_actual, F.hora_actual, F.serie,  F.id_cliente, C.identificacion, C.nombres_completos, C.direccion, C.telefono2, C.correo, F.tipo_comprobante, F.forma_pago, F.tipo_precio, F.subtotal, F.tarifa0, F.tarifa, F.iva_venta, F.descuento_venta, F.total_venta, F.efectivo, F.cambio, F.estado  FROM facturas F, clientes C WHERE F.id_cliente = C.id AND F.id = '$_POST[id]'");
		while ($row = mysqli_fetch_array($resultado)) {
			$data = array(  'id_factura' => $row[0],
							'fecha_actual' => $row[1],
							'hora_actual' => $row[2],
							'serie' => $row[3],
							'id_cliente' => $row[4],
							'identificacion' => $row[5],
							'nombres_completos' => $row[6],
							'direccion' => $row[7],
							'telefono2' => $row[8],
							'correo' => $row[9],
							'tipo_comprobante' => $row[10],
							'forma_pago' => $row[11],
							'tipo_precio' => $row[12],
							'subtotal' => $row[13],
							'tarifa0' => $row[14],
							'tarifa' => $row[15],
							'iva' => $row[16],
							'descuento' => $row[17],
							'total_pagar' => $row[18],
							'efectivo' => $row[19],
							'cambio' => $row[20],
							'estado' => $row[21]);
		}
		print_r(json_encode($data));
	}
	//fin

	// llenar detalle factura venta
	if (isset($_POST['llenar_detalle_factura'])) {
		$resultado = mysqli_query($con,"SELECT D.id_producto, U.codigo_producto, U.nombre_producto, D.cantidad, D.precio, D.descuento, D.total,U.precio_dolar FROM detalle_factura D, facturas F, products U WHERE D.id_producto = U.id_producto AND D.id_factura = F.id AND F.id ='".$_POST['id']."' ORDER BY D.id_producto ASC");
		while ($row = mysqli_fetch_array($resultado)) {
			
			$arr_data[] = $row['0'];
		    $arr_data[] = $row['1'];
		    $arr_data[] = $row['2'];
		    $arr_data[] = $row['3'];
		    $arr_data[] = $row['4'];
		    $arr_data[] = $row['5'];
		    $arr_data[] = $row['6'];
		    $arr_data[] = $row['7'];
		    $arr_data[] = $row['8'];
		    $arr_data[] = $row['9'];
		}
		echo json_encode($arr_data);
	}
	//fin

	// llenar cabezera nota
	if (isset($_POST['llenar_cabezera_nota'])) {
		$resultado = mysqli_query($con,"SELECT F.id, F.fecha_actual, F.hora_actual, F.serie,  F.id_cliente, C.identificacion, C.nombres_completos, C.direccion, C.telefono2, C.correo, F.tipo_comprobante, F.forma_pago, F.tipo_precio, F.subtotal, F.tarifa0, F.tarifa, F.iva_venta, F.descuento_venta, F.total_venta, F.efectivo, F.cambio, F.estado  FROM nota_venta F, clientes C WHERE F.id_cliente = C.id AND F.id = '$_POST[id]'");
		while ($row = mysqli_fetch_array($resultado)) {
			$data = array(  'id_nota' => $row[0],
							'fecha_actual' => $row[1],
							'hora_actual' => $row[2],
							'serie' => $row[3],
							'id_cliente' => $row[4],
							'identificacion' => $row[5],
							'nombres_completos' => $row[6],
							'direccion' => $row[7],
							'telefono2' => $row[8],
							'correo' => $row[9],
							'tipo_comprobante' => $row[10],
							'forma_pago' => $row[11],
							'tipo_precio' => $row[12],
							'subtotal' => $row[13],
							'tarifa0' => $row[14],
							'tarifa' => $row[15],
							'iva' => $row[16],
							'descuento' => $row[17],
							'total_pagar' => $row[18],
							'efectivo' => $row[19],
							'cambio' => $row[20],
							'estado' => $row[21]);
		}
		print_r(json_encode($data));
	}
	//fin

	// llenar detalle nota
	if (isset($_POST['llenar_detalle_nota'])) {
		$resultado = mysqli_query($con,"SELECT D.id_producto, U.codigo_producto, U.nombre_producto, D.cantidad, D.precio, D.descuento, D.total,U.precio_dolar FROM detalle_nota D, nota_venta F, products U WHERE D.id_producto = U.id_producto AND D.id_nota = F.id AND F.id ='".$_POST['id']."' ORDER BY D.id_producto ASC");
		while ($row = mysqli_fetch_array($resultado)) {
			$arr_data[] = $row['0'];
		    $arr_data[] = $row['1'];
		    $arr_data[] = $row['2'];
		    $arr_data[] = $row['3'];
		    $arr_data[] = $row['4'];
		    $arr_data[] = $row['5'];
		    $arr_data[] = $row['6'];
		    $arr_data[] = $row['7'];
		    $arr_data[] = $row['8'];
		    $arr_data[] = $row['9'];
		}
		echo json_encode($arr_data);
	}
	//fin

	// buscar clientes
	if (isset($_POST['buscador_clientes'])) {
		$resultado = mysqli_query($con,"SELECT C.id, C.identificacion, C.nombres_completos, C.telefono2, C.direccion, C.correo FROM clientes C WHERE C.estado = '1'");
		while ($row = mysqli_fetch_array($resultado)) {
		
				if($_POST['tipo_busqueda'] == 'ruc') {
				$data[] = array(
		            'id' => $row[0],
		            'value' => $row[1],
		            'cliente' => $row[2],
		            'telefono' => $row[3],
		            'direccion' => $row[4],
		            'correo' => $row[5] 
		        );			
			} else {
				if($_POST['tipo_busqueda'] == 'cliente') {
					$data[] = array(
			            'id' => $row[0],
			            'value' => $row[2],
			            'ruc' => $row[1],
			            'telefono' => $row[3],
			            'direccion' => $row[4],
			            'correo' => $row[5] 
			        );	
				}
			}
		}
		
		echo $data = json_encode($data);	
	}
	// fin

	// buscar productos
	if (isset($_POST['buscador_productos'])) {
	$resultado = mysqli_query($con,"SELECT * FROM products");
		while ($row = mysqli_fetch_array($resultado)) {
			if($_POST['tipo_busqueda'] == 'codigo') {
			
	        $data[] = array(
	        	'id' => $row[0],
	            'value' => $row[1],
	            'producto' => $row[2],
	            'precio_dolar' => $row[4],
	            'precio_boli' => $row[5],
	            'precio_producto' => $row[6],
	            'stock' => $row[7]
	       
	        );
	    
		
			} 
			else {
				if($_POST['tipo_busqueda'] == 'producto') {
					
				        $data[] = array(
				        	'id' => $row[0],
					            'codigo_producto' => $row[1],
					            'value' => $row[2],
					            'precio_dolar' => $row[4],
					            'precio_boli' => $row[5],
					            'precio_producto' => $row[6],
					            'stock' => $row[7]
				        );
			
				}
			}
		}
		echo $data = json_encode($data);	
	}
	// fin
?>