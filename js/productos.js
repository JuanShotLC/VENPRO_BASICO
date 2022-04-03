		$(document).ready(function(){
			load(1);
			load_historial(1);
		});

		function load(page){
			var q= $("#q").val();
			var id_categoria= $("#id_categoria").val();
			var parametros={'action':'ajax','page':page,'q':q,'id_categoria':id_categoria};
			$("#loader").fadeIn('slow');
			$.ajax({
				data: parametros,
				url:'./ajax/buscar_productos.php',
				 beforeSend: function(objeto){
				 $('#loader').html('<img src="./img/ajax-loader.gif"> Cargando...');
			  },
				success:function(data){
					$(".outer_div").html(data).fadeIn('slow');
					$('#loader').html('');
					
				}
			})
		}


	function load_historial(page){
			var q= $("#q").val();
			var id_producto= $("#id_producto").val();
			var parametros={'action':'ajax','page':page,'q':q,'id_producto':id_producto};
			$("#loader_historial").fadeIn('slow');
			$.ajax({
				data: parametros,
				url:'./ajax/historial.php',
				 beforeSend: function(objeto){
				 $('#loader_historial').html('<img src="./img/ajax-loader.gif"> Cargando...');
			  },
				success:function(data){
					$(".outer_div_historial").html(data).fadeIn('slow');
					$('#loader_historial').html('');
					
				}
			})
		}

	
		
			function eliminar (id)
		{
			var q= $("#q").val();
		if (confirm("Realmente deseas eliminar el producto")){	
		$.ajax({
        type: "GET",
        url: "./ajax/buscar_productos.php",
        data: "id="+id,"q":q,
		 beforeSend: function(objeto){
			$("#resultados").html("Mensaje: Cargando...");
		  },
        success: function(datos){
		$("#resultados").html(datos);
		load(1);
		}
			});
		}
		}



		
		
		
		
		
		

