<?php
    require "includes/funciones.php";
    incluirTemplate("header");
?>

    <main class="contenedor seccion ">
        <h2>Casas y departamentos en Venta</h2>
        <!-- <div class="contenedor-anuncios">
            <div class="anuncio">
                <picture>
                    <source srcset="build/img/anuncio1.webp" type="image/webp">
                    <source srcset="build/img/anuncio1.jpg" type="image/jpeg">
                    <img loading="lazy" src="build/img/anuncio1.jpg" alt="anuncio">
                </picture>
                <div class="contenido-anuncio">
                    <h3>Casa de Lujo en el Lago</h3>
                    <p>Casa en el lago con excelente vista, acabados de lujo a un excelente precio</p>
                    <p class="precio">$3,000,000</p>
                    <ul class="iconos-caracteristicas">
                        <li>
                            <img class="icono" src="build/img/icono_wc.svg" alt="icono wc">
                            <p>3</p>
                        </li>
                        <li>
                            <img class="icono" src="build/img/icono_estacionamiento.svg" alt="icono estacionamiento">
                            <p>3</p>
                        </li>
                        <li>
                            <img class="icono" src="build/img/icono_dormitorio.svg" alt="icono dormitorio">
                            <p>4</p>
                        </li>
                    </ul>
                    <a href="anuncio.html" class="boton-amarillo-block">Ver Propiedad</a>
                </div>
            </div>
        </div> -->
        <?php
            include 'includes/templates/anuncios.php'; 
        ?>

    </main>

<?php incluirTemplate("footer"); ?>