<?php
    require "../../includes/funciones.php";
    $auth = userLogued();
    if(!$auth)
        header("Location: /bienesraices");
    /*
    phpinfo(); // funcion que muestra info sensible de nuestro proyecto (nunca debe estar disponible en produccion)
    exit;
    */

    /*
    $numero_10_digitos_aleatorio = rand(); 
    $nombre_imagen = md5( uniqid( $numero_10_digitos_aleatorio, true ) );
    
    echo $numero_10_digitos_aleatorio;
    echo "<hr>";
    echo $nombre_imagen;
    
    echo "<hr>";

    $aleatorio = rand(1,365);
    echo $aleatorio;

    exit;
    */

    // Base de datos
    require "../../includes/config/database.php";
    $db = conectarDB();

    // consultar para obtener los vendedores
    $query2 = "SELECT * FROM vendedores";
    $resultado2 = mysqli_query($db, $query2); 

    $errores = []; // aqui se almacenaran los posibles errores de validacion en los inputs del form

    $titulo = "";
    $precio = "";
    $descripcion = "";
    $habitaciones = "";
    $wc = "";
    $estacionamientos = "";
    $vendedores_id = "";
    $creado = date("Y/m/d");
    
    if($_SERVER["REQUEST_METHOD"] === "POST") {

        /*
        $numero = "1HOLA@7";
        $numero2 = 1;
        // sanitizar -> es limpiar de caracteres indeseados el valor de una variable, según el filtro de saneamiento que le pasemos
        // para eso usamos la funcion filter_var($variable, filtro)
        // https://www.php.net/manual/es/filter.filters.php
        $resultado = filter_var($numero, FILTER_SANITIZE_NUMBER_INT); // Elimina todos los caracteres excepto dígitos y los signos de suma y resta.
        var_dump($resultado);
        echo "<hr>";
        $resultado2 = filter_var($numero, FILTER_SANITIZE_STRING); // Elimina etiquetas, opcionalmente elimina o codifica caracteres especiales.
        var_dump($resultado2);
        echo "<hr>";
        $mail = "lionel@correo/.com";
        var_dump(filter_var($mail, FILTER_SANITIZE_EMAIL));
        echo "<hr>";
        exit;
        */

        
        /* echo "<pre>";
        print_r($_POST);
        print_r($_FILES);
        print_r($_FILES["imagen"]);
        echo "</pre>";
        exit; */
       

        // asignar files a una variable 
        $imagen = $_FILES["imagen"];

        // con la funcion mysqli_real_escape_string() evitamos la inyeccion SQL
        $titulo = mysqli_real_escape_string($db, $_POST["titulo"]);
        $precio = mysqli_real_escape_string($db, $_POST["precio"]);
        $descripcion = mysqli_real_escape_string($db, $_POST["descripcion"]);
        $habitaciones = mysqli_real_escape_string($db, $_POST["habitaciones"]);
        $wc = mysqli_real_escape_string($db, $_POST["wc"]);
        $estacionamientos = mysqli_real_escape_string($db, $_POST["estacionamientos"]);
        if(isset($_POST["vendedores_id"]))
            $vendedores_id = mysqli_real_escape_string($db, $_POST["vendedores_id"]);

        //echo strlen($descripcion);
        //echo $descripcion;
        //exit; 

        /* 
        Departamento amplio.
        Vista al mar.
        Excelente ubicación.
        
        Departamento amplio.\r\nVista al mar.\r\nExcelente ubicación.
        */
        if(!$titulo) {
            $errores[] = "Debes añadir un título";
        }
        if(!$precio) {
            $errores[] = "El precio es obligatorio";
        }
        if(strlen($descripcion) < 50) {
            $errores[] = "La descripción es obligatoria y debe ser de al menos 50 caracteres";
        }
        if($habitaciones === "") {
            $errores[] = "El numero de habitaciones es obligatorio";
        }
        if($wc === "") {
            $errores[] = "El numero de baños es obligatorio";
        }
        if($estacionamientos === "") {
            $errores[] = "El numero de estacionamientos es obligatorio";
        }
        if(!$vendedores_id) {
            $errores[] = "Elige un vendedor";
        } 

        /* validacion imagen */
        $types_image_allowed = ['image/jpg', 'image/jpeg','image/png', 'image/webp'];
        $type_allowed = false;
        if(!$imagen["name"] || $imagen["error"]) 
            $errores[] = "La imagen es obligatoria";
        else {
            foreach($types_image_allowed as $type){
                if($imagen["type"] == $type) {
                    $type_allowed = true;
                    break;
                }
            }
            if(!$type_allowed)
                $errores[] = "El formato de archivo no es válido"; 
            else {
                $peso_maximo_imagen = 1000 * 400; // 1kb == 1000 bytes -> tamaño maximo permitido para imagen == 100kb
                if($imagen["size"] > $peso_maximo_imagen)
                    $errores[] = "La imagen es muy pesada"; 
            }
        }   

        if(empty($errores)) {

            // subida de imagenes al servidor

            
            // BLOQUE PARA SUBIR IMAGENES AL SERVER (DESDE EL VIDEO 319)
            // ESTO ES LO QUE EXPLICA EL PROFESOR
            // GUARDAR LAS IMAGENES EN UNA CARPETA imagenes Y RENDERIZARLAS EN LAS DISTINTAS VISTAS DESDE AHI
            // YO ELEGI UNA FORMA ALTERNATIVA PARA HACER USO DE LAS OPTIMIZACIONES CONFIGURADAS EN EL .gulpfile.js
            
            // crear carpeta
            $carpeta_imagenes = "../../imagenes/";
            if(!is_dir($carpeta_imagenes)) // is_dir('path/nombre_carpeta') -> funcion php que nos indica si existe o no la carpeta especificada dentro del proyecto
                mkdir($carpeta_imagenes); // mkdir() -> funcion php para crear una carpeta dentro del proyecto, donde le especifiquemos (en este caso, creamos la carpeta imagenes en la raiz, por eso nos movemos 2 instancias para atras)
            
            // generar un nombre unico para las imagenes 
            $extension_image = substr($imagen["type"], 6);
            $numero_10_digitos_aleatorio = rand(); // funcion php que por default genera un numero aleatorio de 10 digitos, pero podemos pasarle minimo y maximo como argumentos 
            $nombre_imagen = md5( uniqid( $numero_10_digitos_aleatorio, true ) ) . "." . $extension_image; // generamos un nombre aleatorio para cada imagen que subamos al servidor

            // subir imagen
            move_uploaded_file($imagen["tmp_name"], $carpeta_imagenes . $nombre_imagen); 
            // move_uploaded_file() -> funcion php para guardar un archivo en el servidor 
            // como 1er parametro le pasamos la ubicacion temporal en la que esta almacenada la imagen (viene en el superglobal $_FILES, cuando un usuario selecciona un archivo en un <input type="file">)
            // como 2do parametro le pasamos el path definitivo dentro del proyecto, incluyendo el nombre que le daremos a la imagen en el servidor (en nuestro caso, almacenado en $nombre_imagen)

            // FIN DEL BLOQUE PARA SUBIR IMAGENES AL SERVER (DESDE EL VIDEO 319)
           

            /* $final_location_image = "../../src/img/";
            $extension_image = substr($imagen["type"], 6);
            $numero_10_digitos_aleatorio = rand();
            $final_name_image = md5( uniqid( $numero_10_digitos_aleatorio, true ) ) . "." . $extension_image;
            move_uploaded_file($imagen["tmp_name"], $final_location_image . $final_name_image); */

            // insertar en la base de datos 
            $query= "INSERT INTO propiedades (titulo, precio, imagen, descripcion, habitaciones, wc, estacionamiento, creado, vendedores_id, modificado) VALUES ";
            
            $query = $query . "('$titulo','$precio', '$nombre_imagen', '$descripcion','$habitaciones','$wc','$estacionamientos', '$creado', '$vendedores_id', '$creado')";
           
            /* $query = $query . "('$titulo','$precio', '$final_name_image', '$descripcion','$habitaciones','$wc','$estacionamientos', '$creado', '$vendedores_id', '$creado')"; */

            $resultado = mysqli_query($db, $query); 
            // le paso la instancia de la conexion y la query
            // la ejecucion de mysql_query arroja un bool -> true si el insert se ejecuto correctamente 

            if($resultado){
                // redireccionar al usuario luego de creado el registro 
                // esta funcion sirve para enviar datos en el encabezado de una peticion HTTP
                header("Location: /bienesraices/admin?result=1");
            }
        }
    }

    incluirTemplate("header");
?>

    <main class="contenedor seccion">
        <h1>Crear</h1>

        <a href="/bienesraices/admin/" class="boton boton-verde">Volver</a>

        <?php foreach ($errores as $key => $error): ?>
            <div class="alerta error">
                <?php echo $error; ?> 
            </div>
        <?php endforeach ?>

        <form class="formulario" method="POST" action="/bienesraices/admin/propiedades/crear.php" enctype="multipart/form-data"> 
            <fieldset>
                <legend>Información General</legend>

                <label for="titulo">Título de la propiedad:</label>
                <input type="text" id="titulo" name="titulo" placeholder="Título Propiedad" value="<?php  echo $titulo; ?>">
                
                <label for="precio">Precio propiedad:</label>
                <input type="number" id="precio" name="precio" placeholder="Precio Propiedad" value="<?php  echo $precio; ?>">
                
                <label for="imagen">Imagen:</label>
                <input type="file" id="imagen" accept="image/jpeg, image/png" name="imagen">
                
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion"><?php  echo $descripcion; ?></textarea>
            </fieldset>

            <fieldset>
                <legend>Información de la propiedad</legend>

                <label for="habitaciones">Habitaciones:</label>
                <input type="number" id="habitaciones" name="habitaciones" placeholder="Ej: 3" min="0" max="9" value="<?php  echo $habitaciones; ?>">
                
                <label for="wc">Baños:</label>
                <input type="number" id="wc" name="wc" placeholder="Ej: 3" min="0" max="9" value="<?php  echo $wc; ?>">
                
                <label for="estacionamientos">Estacionamientos:</label>
                <input type="number" id="estacionamientos" name="estacionamientos" placeholder="Ej: 3" min="0" max="9" value="<?php  echo $estacionamientos; ?>"> 
            
            </fieldset>

            <fieldset>
                <legend>Vendedor</legend>
                <select name="vendedores_id">
                    <option value="" disabled selected>-- Seleccione --</option>
                    <?php while($row = mysqli_fetch_assoc($resultado2) ): ?>
                        <option 
                            value="<?php echo $row["id"]; ?>" 
                            <?php echo $vendedores_id == $row["id"] ? "selected" : "";  ?>
                        >
                            <?php echo $row["nombre"] . " " . $row["apellido"]; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </fieldset>
            <input type="submit" value="Crear Propiedad" class="boton boton-verde">
        </form>
    </main>
    <?php 
    ?>
<?php incluirTemplate("footer"); ?>
