let vueFacturacionInstance = null;

window.initVueFacturacion = function() {
    const { createApp, ref, reactive, onMounted, computed, watch } = Vue;

    if (vueFacturacionInstance) {
        vueFacturacionInstance.unmount();
    }

    const app = createApp({
        setup() {
            // --- ESTADO ---
            const loading = ref(true);
            const fechaActual = ref(new Date().toLocaleDateString('es-ES'));
            const horaActual = ref("");
            const serieFactura = ref("000000");
            const factorCambio = ref(0);
            const ivaPorcentaje = ref(16);
            const igtfPorcentaje = ref(3);

            const cliente = reactive({ 
                id: "", 
                ruc: "", 
                nombre: "", 
                direccion: "", 
                telefono: "", 
                correo: "" 
            });
            
            const invoice = reactive({
                id: "",
                tipoComprobante: "1", // 1: Factura, 2: Nota, 3: Proforma
                formaPago: "CONTADO",
                tipoPrecio: "MINORISTA",
                tipoFactura: "2", // 1: Ticket, 2: Boleta
                pagoDivisas: "0.00",
                precinto: ""
            });

            const searchProduct = reactive({ 
                code: "", // Escáner
                manualCode: "", // Código manual
                name: "", 
                qty: 1, 
                price: 0, 
                stock: 0,
                discount: 0,
                selected: null 
            });

            const cart = ref([]);
            
            // --- AUTOCOMPLETE STATES ---
            const listaClientes = ref([]);
            const listaProductos = ref([]);
            const mostrarSugerenciasClientes = ref(false);
            const mostrarSugerenciasProductos = ref(false);

            // --- GESTIÓN DE PRECINTOS ---
            const precintosList = ref([]);
            const nuevoPrecintoStr = ref("");

            // --- COMPUTED ---
            const sugerenciasClientes = computed(() => {
                if (!cliente.ruc || cliente.ruc.length < 1) return [];
                const q = cliente.ruc.toLowerCase();
                return listaClientes.value.filter(c => {
                    const val = c.value || c.ruc || "";
                    const name = c.cliente || c.nombre || "";
                    return val.toLowerCase().includes(q) || name.toLowerCase().includes(q);
                }).slice(0, 8);
            });

            const sugerenciasProductos = computed(() => {
                if (!searchProduct.code || searchProduct.code.length < 1) return [];
                const q = searchProduct.code.toLowerCase();
                return listaProductos.value.filter(p => {
                    const val = p.value || p.codigo_producto || "";
                    const name = p.producto || p.nombre_producto || "";
                    return val.toLowerCase().includes(q) || name.toLowerCase().includes(q);
                }).slice(0, 10);
            });

            const resumenPrecintos = computed(() => {
                if (precintosList.value.length === 0) return "";
                if (precintosList.value.length === 1) return precintosList.value[0];
                return `${precintosList.value[0]} y ${precintosList.value.length - 1} más`;
            });

            const totalProductos = computed(() => {
                return cart.value.reduce((acc, item) => acc + (parseFloat(item.cantidad) || 0), 0);
            });

            const totals = computed(() => {
                const subtotal = cart.value.reduce((acc, item) => acc + (item.cantidad * item.precioUsd), 0);
                const iva = subtotal * (ivaPorcentaje.value / 100);
                
                const montoDivisas = parseFloat(invoice.pagoDivisas) || 0;
                const igtf = (montoDivisas * igtfPorcentaje.value) / 100;
                
                const totalUsd = subtotal + iva + igtf;
                const totalBs = totalUsd * factorCambio.value;

                return { subtotal, iva, igtf, totalUsd, totalBs };
            });

            // --- MÉTODOS ---
            const updateTime = () => {
                const now = new Date();
                horaActual.value = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true });
            };

            const fetchInitData = async () => {
                try {
                    const pIva = new URLSearchParams(); pIva.append('cargar_iva', 'cargar_iva');
                    const resIva = await axios.post('data/factura_venta/app.php', pIva);
                    if (resIva.data) {
                        ivaPorcentaje.value = parseFloat(resIva.data.iva) || 16;
                        igtfPorcentaje.value = parseFloat(resIva.data.igtf) || 3;
                    }

                    const pFactor = new URLSearchParams(); pFactor.append('consultar_factor', 'consultar_factor');
                    const resFactor = await axios.post('data/factura_venta/app.php', pFactor);
                    if (resFactor.data) factorCambio.value = parseFloat(resFactor.data.factor) || 1;

                    // Prefetch clients
                    const pC = new URLSearchParams(); pC.append('buscador_clientes', 'buscador_clientes'); pC.append('tipo_busqueda', 'ruc');
                    const resC = await axios.post('data/factura_venta/app.php', pC);
                    let clList = typeof resC.data === 'string' ? parseJSON(resC.data) : resC.data;
                    listaClientes.value = Array.isArray(clList) ? clList : [];

                    // Prefetch products
                    const pP = new URLSearchParams(); pP.append('buscador_productos', 'buscador_productos'); pP.append('tipo_busqueda', 'codigo');
                    const resP = await axios.post('data/factura_venta/app.php', pP);
                    let prList = typeof resP.data === 'string' ? parseJSON(resP.data) : resP.data;
                    listaProductos.value = Array.isArray(prList) ? prList : [];

                    actualizarSerie();
                } catch (e) { console.error('Error init:', e); }
                loading.value = false;
            };

            const parseJSON = (str) => { try { return JSON.parse(str); } catch { return []; } };

            const actualizarSerie = async () => {
                const pS = new URLSearchParams(); pS.append('cargar_series', 'cargar_series');
                const resSerie = await axios.post('data/factura_venta/app.php', pS);
                if (resSerie.data) serieFactura.value = resSerie.data.serie || "000001";
            };

            const seleccionarCliente = (item) => {
                Object.assign(cliente, { id: item.id, ruc: item.value, nombre: item.cliente, direccion: item.direccion, telefono: item.telefono, correo: item.correo });
                mostrarSugerenciasClientes.value = false;
            };

            const hideSugerenciasClientes = () => setTimeout(() => mostrarSugerenciasClientes.value = false, 200);

            const buscarClienteExacto = () => {
                if (sugerenciasClientes.value.length > 0) seleccionarCliente(sugerenciasClientes.value[0]);
                else Swal.fire({ title: 'Cliente no Existe', text: '¿Desea crearlo al guardar la factura?', icon: 'info' });
            };

            const seleccionarProducto = (item) => {
                searchProduct.selected = item;
                searchProduct.manualCode = item.value || item.codigo_producto;
                searchProduct.code = item.value || item.codigo_producto;
                searchProduct.name = item.producto;
                searchProduct.price = item.precio_dolar;
                searchProduct.stock = item.stock;
                mostrarSugerenciasProductos.value = false;
                agregarAlCarrito();
            };

            const hideSugerenciasProductos = () => setTimeout(() => mostrarSugerenciasProductos.value = false, 200);

            const buscarProductoExacto = () => {
                if (sugerenciasProductos.value.length > 0) seleccionarProducto(sugerenciasProductos.value[0]);
                else Swal.fire('Error', 'Producto no encontrado', 'error');
            };

            const agregarAlCarrito = () => {
                if (!searchProduct.selected) return;
                
                // Calcular precio de acuerdo al descuento aplicado
                let dscto = parseFloat(searchProduct.discount) || 0;
                let precioBaseUsd = parseFloat(searchProduct.price) || 0;
                let precioFinalUsd = precioBaseUsd - (precioBaseUsd * (dscto / 100));

                const existing = cart.value.find(item => item.id === searchProduct.selected.id && item.descuento === dscto);
                if (existing) {
                    existing.cantidad += searchProduct.qty;
                } else {
                    cart.value.push({
                        id: searchProduct.selected.id,
                        codigo: searchProduct.manualCode,
                        nombre: searchProduct.name,
                        cantidad: searchProduct.qty,
                        descuento: dscto,
                        precioUsd: precioFinalUsd,
                        precioBs: precioFinalUsd * factorCambio.value
                    });
                }
                // Limpiar
                searchProduct.code = ""; searchProduct.manualCode = ""; searchProduct.name = ""; searchProduct.qty = 1; searchProduct.selected = null;
                searchProduct.price = 0; searchProduct.stock = 0; searchProduct.discount = 0;
            };

            const removerItem = (idx) => cart.value.splice(idx, 1);

            const guardarFactura = async () => {
                if (cart.value.length == 0) return Swal.fire('Error', 'Carrito vacío', 'error');
                if (!cliente.nombre) return Swal.fire('Error', 'Faltan datos del cliente', 'error');

                try {
                    const params = new URLSearchParams();
                    params.append('btn_guardar', 'btn_guardar');
                    params.append('select_tipo_comprobante', invoice.tipoComprobante);
                    params.append('select_tipo_precio', invoice.tipoPrecio);
                    params.append('select_forma_pago', invoice.formaPago);
                    params.append('select_tipo_factura', invoice.tipoFactura);
                    
                    params.append('id_cliente', cliente.id);
                    params.append('ruc', cliente.ruc);
                    params.append('cliente', cliente.nombre);
                    params.append('direccion', cliente.direccion);
                    params.append('telefono', cliente.telefono);
                    params.append('correo', cliente.correo);
                    params.append('precinto_nro', precintosList.value.join(', '));
                    
                    params.append('serie', serieFactura.value);
                    params.append('fecha_actual', fechaActual.value);
                    params.append('hora_actual', horaActual.value);
                    
                    params.append('subtotal', totals.value.subtotal.toFixed(2));
                    params.append('iva', totals.value.iva.toFixed(2));
                    params.append('iva_igtf', totals.value.igtf.toFixed(2));
                    params.append('divisas', invoice.pagoDivisas);
                    params.append('total_pagar', totals.value.totalBs.toFixed(2));
                    params.append('total_pagar_dolar', totals.value.totalUsd.toFixed(2));
                    params.append('factor', factorCambio.value);

                    // Bridge format
                    params.append('campo1', '|' + cart.value.map(i => i.id).join('|'));
                    params.append('campo2', '|' + cart.value.map(i => i.cantidad).join('|'));
                    params.append('campo3', '|' + cart.value.map(i => i.precioBs.toFixed(2)).join('|'));
                    params.append('campo4', '|' + cart.value.map(i => '0').join('|'));
                    params.append('campo5', '|' + cart.value.map(i => (i.cantidad * i.precioBs).toFixed(2)).join('|'));
                    params.append('campo6', '|' + cart.value.map(i => (i.cantidad * i.precioUsd).toFixed(2)).join('|'));

                    const res = await axios.post('data/factura_venta/app.php', params);
                    if (res.data) {
                        Swal.fire('Guardado', `Venta registrada con éxito.`, 'success');
                        imprimir(res.data);
                        nuevaOperacion();
                    }
                } catch (e) {
                    Swal.fire('Error', 'Error al guardar', 'error');
                }
            };

            const imprimir = (id) => {
                const endpoint = invoice.tipoComprobante == 1 ? 'data/reportes/factura_venta.php' : 'data/reportes/nota_venta.php';
                window.open(`${endpoint}?hoja=A5&id=${id}`, 'popup', 'width=900,height=650');
            };

            const nuevaOperacion = () => location.reload();

            const abrirModalBusqueda = (tipo) => {
                const modalId = (tipo === 'facturas') ? '#myModal' : '#myModal2';
                jQuery(modalId).modal('show');
            };

            const abrirModalPrecintos = () => {
                jQuery('#modalPrecintosVue').modal('show');
            };

            const agregarPrecinto = () => {
                const val = nuevoPrecintoStr.value.trim();
                if (!val) return;
                if (precintosList.value.length >= 30) {
                    return Swal.fire('Límite Alcanzado', 'Solo se permiten hasta 30 precintos por operación.', 'warning');
                }
                if (precintosList.value.includes(val)) {
                    return Swal.fire('Duplicado', 'El precinto ya está en la lista.', 'info');
                }
                precintosList.value.push(val);
                nuevoPrecintoStr.value = "";
            };

            const quitarPrecinto = (idx) => precintosList.value.splice(idx, 1);

            const anularDocumento = () => {
                Swal.fire({ title: '¿Anular Documento?', icon: 'warning', showCancelButton: true, confirmButtonText: 'Sí, Anular' });
            };

            const reimprimirDocumento = () => {
                Swal.fire({ title: 'Reimprimir', text: 'Busque el documento en el listado para reimprimir.', icon: 'info' });
            };

            onMounted(() => {
                updateTime();
                setInterval(updateTime, 1000);
                fetchInitData();
            });

            return {
                loading, fechaActual, horaActual, serieFactura, factorCambio,
                cliente, invoice, searchProduct, cart, totals, totalProductos,
                precintosList, nuevoPrecintoStr, resumenPrecintos, abrirModalPrecintos, agregarPrecinto, quitarPrecinto,
                
                mostrarSugerenciasClientes, sugerenciasClientes, seleccionarCliente, hideSugerenciasClientes, buscarClienteExacto,
                mostrarSugerenciasProductos, sugerenciasProductos, seleccionarProducto, hideSugerenciasProductos, buscarProductoExacto,
                
                agregarAlCarrito, removerItem,
                guardarFactura, nuevaOperacion, abrirModalBusqueda, anularDocumento, reimprimirDocumento,
                formatCurrency: (val) => new Intl.NumberFormat('en-US', { minimumFractionDigits: 2 }).format(val)
            };
        }
    });

    vueFacturacionInstance = app.mount('#vue-facturacion');
};
