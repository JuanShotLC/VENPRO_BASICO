$(document).ready(function () {
	load(1);
});

function load(page) {
	var q = $("#q").val();
	$("#loader").fadeIn('slow');
	$.ajax({
		url: './ajax/buscar_cotizacion.php?action=ajax&page=' + page + '&q=' + q,
		beforeSend: function (objeto) {
			$('#loader').html('<img src="./img/ajax-loader.gif"> Cargando...');
		},
		success: function (data) {
			$(".outer_div").html(data).fadeIn('slow');
			$('#loader').html('');
		}
	})
}

// ESTA ES LA PARTE CLAVE PARA EL REFRESCO AUTOMÁTICO
$("#editar_cotizacion").submit(function (event) {
	event.preventDefault(); // Evita que el formulario se envíe de forma tradicional

	$('#actualizar_datos2').attr("disabled", true);
	var parametros = $(this).serialize();

	$.ajax({
		type: "POST",
		url: "ajax/editar_cotizacion.php",
		data: parametros,
		beforeSend: function (objeto) {
			$("#resultados_ajax2").html("Mensaje: Actualizando precios y tasa...");
		},
		success: function (datos) {
			$("#resultados_ajax2").html(datos);
			$('#actualizar_datos2').attr("disabled", false);

			// Si la respuesta del PHP dice "éxito" (busca la clase alert-success)
			if (datos.indexOf('alert-success') > -1) {

				// Esperamos 1 segundo para que el usuario lea el mensaje y RECARGAMOS
				setTimeout(function () {
					window.location.reload();
				}, 1000);

			}
		}
	});
});

$('#myModal2').on('show.bs.modal', function (event) {
	var button = $(event.relatedTarget)
})