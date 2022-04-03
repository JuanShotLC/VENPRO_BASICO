var dcapp = angular.module('scotchApp', ['ngRoute','ngResource','ngStorage']);

dcapp.directive('hcChart', function () {
    return {
        restrict: 'E',
        template: '<div></div>',
        scope: {
            options: '='
        },
        link: function (scope, element) {
            Highcharts.chart(element[0], scope.options);
        }
    };
})

dcapp.directive('hcPieChart', function () {
    return {
        restrict: 'E',
        template: '<div></div>',
        scope: {
            title: '@',
            data: '='
        },
        link: function (scope, element) {
            Highcharts.chart(element[0], {
                chart: {
                    type: 'pie'
                },
                title: {
                    text: scope.title
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                        }
                    }
                },
                series: [{
                    data: scope.data
                }]
            });
        }
    };
})

// configure our routes
dcapp.config(function($routeProvider) {
    $routeProvider
        // route page initial
        .when('/', {
            templateUrl : 'data/home/app.html',
            // controller  : 'mainController',
            activetab: 'inicio'
        })
        // route facturero
        .when('/facturero', {
            templateUrl : 'data/facturero/app.html',
            controller  : 'factureroController',
            activetab: 'facturero'
        })
        // route talonario
        .when('/talonario', {
            templateUrl : 'data/talonario/app.html',
            controller  : 'talonarioController',
            activetab: 'talonario'
        })
        // route cargos
        .when('/cargos', {
            templateUrl : 'data/cargos/app.html',
            controller  : 'cargosController',
            activetab: 'cargos'
        })
        // route privilegios
        .when('/privilegios', {
            templateUrl : 'data/privilegios/app.html',
            controller  : 'privilegiosController',
            activetab: 'privilegios'
        })
        // route empresa
        .when('/empresa', {
            templateUrl : 'data/empresa/app.html',
            controller  : 'empresaController',
            activetab: 'empresa'
        })
        // route logo empresa
        .when('/logo_empresa', {
            templateUrl : 'data/logo_empresa/app.html',
            controller  : 'logo_empresaController',
            activetab: 'logo_empresa'
        })
        // route tipo_productos
        .when('/tipo_transaccion', {
            templateUrl : 'data/tipo_transaccion/app.html',
            controller  : 'tipo_transaccionController',
            activetab: 'tipo_transaccion'
        })
        // route tipo_productos
        .when('/tipo_comprobante', {
            templateUrl : 'data/tipo_comprobante/app.html',
            controller  : 'tipo_comprobanteController',
            activetab: 'tipo_comprobante'
        })
        // route tipo_productos
        .when('/formas_pago', {
            templateUrl : 'data/formas_pago/app.html',
            controller  : 'formas_pagoController',
            activetab: 'formas_pago'
        })
        // route porcentajes
        .when('/porcentaje', {
            templateUrl : 'data/porcentaje/app.html',
            controller  : 'porcentajeController',
            activetab: 'porcentaje'
        })
        // route tipo_productos
        .when('/retencion_fuente', {
            templateUrl : 'data/retencion_fuente/app.html',
            controller  : 'retencion_fuenteController',
            activetab: 'retencion_fuente'
        })
        // route tipo_productos
        .when('/retencion_iva', {
            templateUrl : 'data/retencion_iva/app.html',
            controller  : 'retencion_ivaController',
            activetab: 'retencion_iva'
        })
        // route tipo_productos
        .when('/tipo_productos', {
            templateUrl : 'data/tipo_productos/app.html',
            controller  : 'tipo_productosController',
            activetab: 'tipo_productos'
        })
        // route categorias
        .when('/categorias', {
            templateUrl : 'data/categorias/app.html',
            controller  : 'categoriasController',
            activetab: 'categorias'
        })
        // route marcas
        .when('/marcas', {
            templateUrl : 'data/marcas/app.html',
            controller  : 'marcasController',
            activetab: 'marcas'
        })
        // route unidades de medida
        .when('/medida', {
            templateUrl : 'data/medida/app.html',
            controller  : 'medidaController',
            activetab: 'medida'
        })
        // route bodegas
        .when('/bodegas', {
            templateUrl : 'data/bodegas/app.html',
            controller  : 'bodegasController',
            activetab: 'bodegas'
        })
        // route clientes
        .when('/clientes', {
            templateUrl : 'data/clientes/app.html',
            controller  : 'clientesController',
            activetab: 'clientes'
        })
        // route proveedores
        .when('/proveedores', {
            templateUrl : 'data/proveedores/app.html',
            controller  : 'proveedoresController',
            activetab: 'proveedores'
        })
        // route productos
        .when('/productos', {
            templateUrl : 'data/productos/app.html',
            controller  : 'productosController',
            activetab: 'productos'
        })
        // route usuarios
        .when('/fotos_articulo', {
            templateUrl : 'data/fotos_articulo/app.html',
            controller  : 'fotos_articuloController',
            activetab: 'fotos_articulo'
        })
        // route importar
        .when('/importar', {
            templateUrl : 'data/importar/app.html',
            controller  : 'importarController',
            activetab: 'importar'
        })
         // route importar
        .when('/inventario', {
            templateUrl : 'data/inventario/app.html',
            controller  : 'inventarioController',
            activetab: 'inventario'
        })
        // route para los movimientos
        .when('/movimientos', {
            templateUrl : 'data/movimientos/app.html',
            controller  : 'movimientosController',
            activetab: 'movimientos'
        })
        // route para los kardex
        .when('/kardex', {
            templateUrl : 'data/kardex/app.html',
            controller  : 'kardexController',
            activetab: 'kardex'
        })
          // route para el login
        .when('/login', {
            templateUrl : 'data/login/app.html',
            controller  : 'loginController',
        })
        // proceso proformas
        .when('/proformas', {
            templateUrl : 'data/proformas/app.html',
            controller  : 'proformasController',
            activetab: 'proformas'
        })
        // proceso proformas
        .when('/factura_venta', {
            templateUrl : 'data/factura_venta/app.html',
            controller  : 'factura_ventaController',
            activetab: 'factura_venta'
        })
        // proceso factura compra
        .when('/factura_compra', {
            templateUrl : 'data/factura_compra/app.html',
            controller  : 'factura_compraController',
            activetab: 'factura_compra'
        })
        // proceso devolucion compra
        .when('/devolucion_compra', {
            templateUrl : 'data/devolucion_compra/app.html',
            controller  : 'devolucion_compraController',
            activetab: 'devolucion_compra'
        })
        // proceso factura venta
        .when('/factura_venta', {
            templateUrl : 'data/factura_venta/app.html',
            controller  : 'factura_ventaController',
            activetab: 'factura_venta'
        })
        // proceso nota credito
        .when('/nota_credito', {
            templateUrl : 'data/nota_credito/app.html',
            controller  : 'nota_creditoController',
            activetab: 'nota_credito'
        })
        // proceso ingresos
        .when('/ingresos', {
            templateUrl : 'data/ingresos/app.html',
            controller  : 'ingresosController',
            activetab: 'ingresos'
        })
        // proceso egresos
        .when('/egresos', {
            templateUrl : 'data/egresos/app.html',
            controller  : 'egresosController',
            activetab: 'egresos'
        })
        // proceso cuentas cobrar
        .when('/cuentas_cobrar', {
            templateUrl : 'data/cuentas_cobrar/app.html',
            controller  : 'cuentas_cobrarController',
            activetab: 'cuentas_cobrar'
        })
        // proceso cuentas pagar
        .when('/cuentas_pagar', {
            templateUrl : 'data/cuentas_pagar/app.html',
            controller  : 'cuentas_pagarController',
            activetab: 'cuentas_pagar'
        })
        // route usuarios
        .when('/usuarios', {
            templateUrl : 'data/usuarios/app.html',
            controller  : 'usuariosController',
            activetab: 'usuarios'
        })
        // route fotos usuario
        .when('/fotos_usuario', {
            templateUrl : 'data/fotos_usuario/app.html',
            controller  : 'fotos_usuarioController',
            activetab: 'fotos_usuario'
        })
        // route cuenta
        .when('/cuenta', {
            templateUrl : 'data/cuenta/app.html',
            controller  : 'cuentaController',
            activetab: 'cuenta'
        })
        // proceso reportes varios
        .when('/reporte_directores', {
            templateUrl : 'data/reporte_directores/app.html',
            controller  : 'reporte_directoresController',
            activetab: 'reporte_directores'
        })
        // proceso reportes productos
        .when('/reporte_productos', {
            templateUrl : 'data/reporte_productos/app.html',
            controller  : 'reporte_productosController',
            activetab: 'reporte_productos'
        })
        // proceso reportes productos
        .when('/reporte_compras', {
            templateUrl : 'data/reporte_compras/app.html',
            controller  : 'reporte_comprasController',
            activetab: 'reporte_compras'
        })
        // proceso reportes productos
        .when('/reporte_ventas', {
            templateUrl : 'data/reporte_ventas/app.html',
            controller  : 'reporte_ventasController',
            activetab: 'reporte_ventas'
        })
        // proceso reportes productos
        .when('/reporte_proformas', {
            templateUrl : 'data/reporte_proformas/app.html',
            controller  : 'reporte_proformasController',
            activetab: 'reporte_proformas'
        })
        // proceso reportes estadisticos
        .when('/reportes_estadisticos', {
            templateUrl : 'data/reportes_estadisticos/app.html',
            controller  : 'reportes_estadisticosController',
            activetab: 'reportes_estadisticos'
        })
});

dcapp.factory('Auth', function($location) {
    var user;
    return {
        setUser : function(aUser) {
            user = aUser;
        },
        isLoggedIn : function() {
            var ruta = $location.path();
            var ruta = ruta.replace("/","");
            var accesos = JSON.parse(Lockr.get('users'));
                accesos.push('inicio');
                accesos.push('');

            var a = accesos.lastIndexOf(ruta);
            if (a < 0) {
                return false;    
            } else {
                return true;
            }
        }
    }
});


dcapp.run(['$rootScope', '$location', 'Auth', function ($rootScope, $location, Auth) {
    $rootScope.$on('$routeChangeStart', function (event) {
        var rutablock = $location.path();
        if (!Auth.isLoggedIn()) {
            event.preventDefault();
            swal({
                title: "Lo sentimos acceso denegado",
                type: "warning",
            });
        } else { }
    });
}]);

// consumir servicios sri
dcapp.factory('loaddatosSRI', function($resource) {
    return $resource("http://", {
        id: "@id"
    });
});
// fin

    