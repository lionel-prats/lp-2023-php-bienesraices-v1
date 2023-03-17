<?php 
    // __DIR__ === C:\xampp\htdocs\bienesraices\includes\templates
    require __DIR__ . "/../config/database.php";
    // "los require son relativos a los documentos que los esta mandando llamar (VIDEO 331)"
    $db = conectarDB();

    if(isset($limit))
        $query = "SELECT * FROM propiedades ORDER BY modificado DESC LIMIT $limit"; 
    else 
        $query = "SELECT * FROM propiedades ORDER BY modificado DESC"; 
    $result_query = mysqli_query($db, $query);
?>

<div class="contenedor-anuncios">
    <?php while($row = mysqli_fetch_assoc($result_query) ): ?>        
        <!-- <div class="anuncio">
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
        </div> -->
        <div class="anuncio">
            <!-- <picture>
                <source srcset="build/img/anuncio1.webp" type="image/webp">
                <source srcset="build/img/anuncio1.jpg" type="image/jpeg">
                <img loading="lazy" src="build/img/anuncio1.jpg" alt="anuncio">
            </picture> -->
            <img loading="lazy" src="imagenes/<?php echo $row["imagen"]; ?>" alt="anuncio">
            <div class="contenido-anuncio">
                <h3><?php echo $row["titulo"]; ?></h3>
                <p><?php echo $row["descripcion"]; ?></p>
                <p class="precio">USD <?php echo number_format($row["precio"], 0, ",", ".")?></p>
                <ul class="iconos-caracteristicas">
                    <li>
                        <img class="icono" src="build/img/icono_dormitorio.svg" alt="icono dormitorio">
                        <p><?php echo $row["habitaciones"]; ?></p>
                    </li>
                    <li>
                        <img class="icono" src="build/img/icono_wc.svg" alt="icono wc">
                        <p><?php echo $row["wc"]; ?></p>
                    </li>
                    <li>
                        <img class="icono" src="build/img/icono_estacionamiento.svg" alt="icono estacionamiento">
                        <p><?php echo $row["estacionamiento"]; ?></p>
                    </li>
                </ul>
                <a href="anuncio.php?id=<?php echo $row["id"]; ?>" class="boton-amarillo-block">Ver Propiedad</a>
            </div>
        </div>
    <?php endwhile; ?>
</div>
<?php 
    mysqli_close($db);
?>