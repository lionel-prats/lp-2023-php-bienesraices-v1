<?php
    // __DIR__ === C:\xampp\htdocs\bienesraices

    $id_property = $_GET["id"];
    $id_property =  filter_var($id_property, FILTER_VALIDATE_INT); 
    if(!$id_property)
        header("Location: /bienesraices/error.php");
        
    require __DIR__ . "/includes/config/database.php";
    $db = conectarDB();
    $query = "SELECT * FROM propiedades WHERE id = $id_property"; 
    $result_query = mysqli_query($db, $query);
    
    // si la cosulta viene vacia (si no se ecncontraron registros), redirecciono
    if(!$result_query->num_rows)
        header("Location: /bienesraices/error.php");

    $property_information = mysqli_fetch_assoc($result_query); 

    require "includes/funciones.php";
    incluirTemplate("header");
?>
    <main class="contenedor seccion contenido-centrado">
        <h1><?php echo $property_information["titulo"]; ?></h1>
        <!-- <picture>
            <source srcset="build/img/destacada.webp" type="image/webp">
            <source srcset="build/img/destacada.jpg" type="image/jpeg">
        </picture> -->
        <img loading="lazy" src="imagenes/<?php echo $property_information["imagen"]; ?>" alt="imagen de la propiedad">
        <div class="resumen-propiedad">
            <p class="precio">USD <?php echo number_format($property_information["precio"], 0, ",", ".")?></p>
            <ul class="iconos-caracteristicas">
                <li>
                    <img class="icono" src="build/img/icono_dormitorio.svg" alt="icono dormitorio">
                    <p><?php echo $property_information["habitaciones"]; ?></p>
                </li>    
                <li>
                    <img class="icono" src="build/img/icono_wc.svg" alt="icono wc">
                    <p><?php echo $property_information["wc"]; ?></p>
                </li>
                <li>
                    <img class="icono" src="build/img/icono_estacionamiento.svg" alt="icono estacionamiento">
                    <p><?php echo $property_information["estacionamiento"]; ?></p>
                </li>
            </ul>
            <p><?php echo $property_information["descripcion"]; ?></p>
        </div>
    </main>

<?php 
    mysqli_close($db);
    incluirTemplate("footer"); 
?>
