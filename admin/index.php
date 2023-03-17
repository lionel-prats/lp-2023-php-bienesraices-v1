<?php
    require "../includes/funciones.php";
    $auth = userLogued();
    if(!$auth)
        header("Location: /bienesraices");
    
    require "../includes/config/database.php";
    $db = conectarDB(); // instancia de la conexion a la BD
    
    // bloque para eliminar un registro de propiedades
    if($_SERVER["REQUEST_METHOD"] === "POST") {


        $id_property = $_POST["id_property"];
        $id_property = filter_var($id_property, FILTER_VALIDATE_INT);
        // verificamos que haya llegado un int (evitamos inyeccion SQL, ya que se puede modificar el value del input:hidden)

        if($id_property){            
            // eliminar la imagen asociada al registro a eliminar
            $query = "select imagen FROM propiedades WHERE id = $id_property";
            $resultado = mysqli_query($db, $query); 
            $resultado = mysqli_fetch_assoc($resultado)["imagen"];
            $carpeta_imagenes = "../imagenes/";
            unlink( $carpeta_imagenes . $resultado ); 
            
            // eliminar el registro de la BD
            $query = "DELETE FROM propiedades WHERE id = $id_property";
            $resultado = mysqli_query($db, $query); 
            if($resultado){
                header("Location: /bienesraices/admin?result=3");
            }
        }
    }
    
    $query = "SELECT * FROM propiedades";
    $result_query = mysqli_query($db, $query);

    // confirmacion de exito si una propiedad se cargo correctamente (se envia desde crear.php, como parte de la query string del header("Location":...))
    $result = $_GET["result"] ?? null;
    // "??" placeholder php que, si no existe lo que se le pasa antes de "??" a $result (en este caso $_GET["result"]), le asignará lo que especifiquemos despues de "??" a $result (en este caso null) 

    
    incluirTemplate("header");
?>

    <main class="contenedor seccion">
        <h1>Administrador de Bienes Raices</h1>

        <!-- mensaje de exito al crear una nueva propiedad correctamente -->
        <?php if(intval($result) === 1): // intval() devuelve el valor integer de una variable ?>  
            <p class="alerta exito">Publicación Creada Correctamente</p> 
        <?php elseif(intval($result) === 2): ?>  
            <p class="alerta exito">Publicación Actualizada Correctamente</p> 
        <?php elseif(intval($result) === 3): ?>  
            <p class="alerta exito">Publicación Eliminada Correctamente</p> 
        <?php endif; ?>
        
        <a href="/bienesraices/admin/propiedades/crear.php" class="boton boton-verde">Nueva Propiedad</a>
        <!-- 
        BOTONES PARA PRUEBAS DE INYECCION SQL (APUNTAN A ARCHIVOS DENTRO DE /admin/propiedades)
        <a href="/bienesraices/admin/propiedades/inyeccion.php" class="boton boton-amarillo">Buscar Vendedor</a>
        <a href="/bienesraices/admin/propiedades/inyeccion2.php" class="boton boton-verde">Login Devstagram</a>
        <a href="/bienesraices/admin/propiedades/inyeccion3.php" class="boton boton-amarillo">Baja de Usuario</a>
         -->

        <table class="propiedades">
            <thead>
                <th>ID</th>
                <th>Título</th>
                <th>Imagen</th>
                <th>Precio</th>
                <th>Acciones</th>
            </thead>
            <tbody>
                <?php 
                    while($row = mysqli_fetch_assoc($result_query) ): 
                        $image_name = explode('.', $row["imagen"])[0];
                ?>
                        <tr>
                            <td><?php echo $row["id"]; ?></td>
                            <td><?php echo $row["titulo"]; ?></td>
                            <td>
                                <img src="../imagenes/<?php echo $row["imagen"]; ?>" class="imagen-tabla" alt="imagen propiedad"> 
                            </td>
                            <!-- <td>
                                <picture>
                                    <source srcset="../build/img/<?php //echo $image_name; ?>.webp" type="image/webp" class="imagen-tabla" >
                                    <source srcset="../build/img/<?php //echo $image_name; ?>.jpg" type="image/jpeg" class="imagen-tabla" >
                                    <img loading="lazy" src="../build/img/<?php //echo $image_name; ?>.jpg" alt="imagen propiedad" class="imagen-tabla" >
                                </picture>
                            </td> -->
                            <td>$ <?php echo $row["precio"]; ?></td>
                            <td>
                                <a href="/bienesraices/admin/propiedades/actualizar.php?id=<?php echo $row['id']; ?>" class="boton-amarillo-block">Actualizar</a>
                                <form method="POST" class="w-100">
                                    <input type="hidden" name="id_property" value="<?php echo $row["id"]; ?>">
                                    <input type="submit" class="boton-rojo-block w-100 lh-default" value="Eliminar">
                                </form>
                            </td>
                        </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    </main>

<?php
    mysqli_close($db); // cierro la conexion a la DB
    incluirTemplate("footer"); 
?>