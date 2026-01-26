<footer class="footer-modern">
    <div class="container-fluid">
        <div class="row">

            <div class="col-md-6 col-xs-12 text-left">
                <strong>Copyright &copy; <?php echo date('Y'); ?>
                    <a href="#">VENPRO</a>.</strong>
                Sistema Administrativo.
            </div>

            <div class="col-md-6 col-xs-12 text-right">
                <span class="hidden-xs">Diseñado por: </span>
                <a href="https://www.instagram.com/sistemasvenpro/" target="_blank" class="developer-link">
                    <i class="fa fa-instagram"></i> SISTEMAS VENPRO
                </a>
            </div>

        </div>
    </div>
</footer>

<a href="#" id="btn-scroll-up" class="btn-scroll-modern"
    onclick="$('html, body').animate({scrollTop:0}, 'slow'); return false;">
    <i class="glyphicon glyphicon-chevron-up"></i>
</a>

<script src="js/jquery-1.11.3.min.js"></script>

<script src="bootstrap-3.3.7/js/bootstrap.min.js"></script>

<style>
    /* Estilo del Footer Blanco */
    .footer-modern {
        background-color: #ffffff;
        border-top: 1px solid #e7e7e7;
        /* Línea sutil arriba */
        color: #555555;
        /* Texto gris oscuro para que se lea */
        padding: 20px 0;
        margin-top: 50px;
        /* Separación del contenido de arriba */
        font-size: 13px;
        position: relative;
        z-index: 100;
    }

    .footer-modern a {
        color: #3498db;
        /* Color azul para enlaces */
        text-decoration: none;
        font-weight: 600;
    }

    .footer-modern a:hover {
        color: #2980b9;
        text-decoration: underline;
    }

    .developer-link i {
        color: #E1306C;
        /* Color de Instagram */
        margin-right: 3px;
    }

    /* Botón flotante para subir */
    .btn-scroll-modern {
        position: fixed;
        bottom: 40px;
        right: 20px;
        background-color: #3498db;
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        text-align: center;
        line-height: 35px;
        /* Centrar icono verticalmente */
        font-size: 18px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        display: none;
        /* Se oculta por defecto, JS lo muestra al bajar */
        z-index: 999;
        transition: all 0.3s;
    }

    .btn-scroll-modern:hover {
        background-color: #2980b9;
        color: white;
        transform: translateY(-3px);
    }
</style>

<script>
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $('.btn-scroll-modern').fadeIn();
        } else {
            $('.btn-scroll-modern').fadeOut();
        }
    });
</script>