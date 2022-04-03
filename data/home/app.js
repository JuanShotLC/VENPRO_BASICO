angular.module('scotchApp').controller('mainController', function ($scope, $route) {
	$scope.$route = $route;

	jQuery(function($) {
		$('[data-toggle="tooltip"]').tooltip();

		// Sample options for first chart
        $scope.chartOptions = {
            title: {
                text: 'Temperature data'
            },
            xAxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 
                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            },

            series: [{
                data: [29.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]
            }]
        };


        // funcion producto mas vendido
	    function productos_vendidos() {
	        $.ajax({
	            type: "POST",
	            url: "data/home/app.php",
	            data: {cargar_productos_vendidos:'cargar_productos_vendidos'},
	            dataType: 'json',
	            async: false,
	            success: function(data) {
	            	if (data == null) {
	            		$scope.pieData = [{
			                name: "SIN FACTURAS",
			                y: 0
			        	}]
	            	} else {
	            		$scope.pieData = data;
	            	}
	            }
	        });
	    }
	    // fin

        // Sample data for pie chart
        // $scope.pieData = [{
        //         name: "Microsoft Internet Explorer",
        //         y: 56
        //     }, {
        //         name: "Chrome",
        //         y: 24,
        //         // sliced: true,
        //         // selected: true
        //     }, {
        //         name: "Firefox",
        //         y: 10
        //     }, {
        //         name: "Safari",
        //         y: 4
        //     }, {
        //         name: "Opera",
        //         y: 0
        //     }, {
        //         name: "Proprietary or Undetectable",
        //         y: 0
        // }]
		
		$('.easy-pie-chart.percentage').each(function(){
			var $box = $(this).closest('.infobox');
			var barColor = $(this).data('color') || (!$box.hasClass('infobox-dark') ? $box.css('color') : 'rgba(255,255,255,0.95)');
			var trackColor = barColor == 'rgba(255,255,255,0.95)' ? 'rgba(255,255,255,0.25)' : '#E2E2E2';
			var size = parseInt($(this).data('size')) || 50;
			$(this).easyPieChart({
				barColor: barColor,
				trackColor: trackColor,
				scaleColor: false,
				lineCap: 'butt',
				lineWidth: parseInt(size/10),
				animate: /msie\s*(8|7|6)/.test(navigator.userAgent.toLowerCase()) ? false : 1000,
				size: size
			});
		})
	
		$('.sparkline').each(function() {
			var $box = $(this).closest('.infobox');
			var barColor = !$box.hasClass('infobox-dark') ? $box.css('color') : '#FFF';
			$(this).sparkline('html',
							 {
								tagValuesAttribute:'data-values',
								type: 'bar',
								barColor: barColor ,
								chartRangeMin:$(this).data('min') || 0
							 });
		});




    // funcion total facturas venta
    function factura_venta() {
        $.ajax({
            type: "POST",
            url: "data/home/app.php",
            data: {cargar_facturas_venta:'cargar_facturas_venta'},
            dataType: 'json',
            async: false,
            success: function(data) {
            	if (data.total_venta == null) {
            		$scope.factura_venta = '0.00';
            	} else {
            		$scope.factura_venta = parseFloat(data.total_venta).toFixed(2);	
            	}                
            }
        });
    }
    // fin

  
    // incio funciones
    productos_vendidos();
    factura_venta();
    // fin

	})	
});