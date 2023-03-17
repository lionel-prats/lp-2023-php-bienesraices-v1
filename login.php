<?php
    require "includes/config/database.php";
    $db = conectarDB();
    
    $errors = [];

    $email = "";

    // autenticacion de usuario
    if($_SERVER["REQUEST_METHOD"] === "POST") {
        $email = mysqli_real_escape_string($db, filter_var($_POST["email"], FILTER_VALIDATE_EMAIL));
        $password = mysqli_real_escape_string($db, $_POST["password"]);

        if(!$email) {
            $errors[] = "Debes ingresar un email válido";
        }
        if(!$password) {
            $errors[] = "Debes ingresar una contraseña";
        }
        if(empty($errors)) {
            $query= "SELECT * FROM usuarios WHERE email = '$email'";
            $result = mysqli_query($db, $query); 
            if($result->num_rows) {
                $result = mysqli_fetch_assoc($result);
                $auth = password_verify($password, $result["password"]);
                // password_verify(string sin hashear, string hasheado) compara dos strings (el 1ero sin hashear, el 2do hasheado) para verificar si sus valores originales coincidden
                // nos sirve para autenticacion de usuario
                if(!$auth)
                    $errors[] = "El password es incorrecto";
                else {
                    session_start();
                    $_SESSION['user'] = $result["email"];
                    $_SESSION['login'] = true;
                    header("Location: /bienesraices/admin");
                }
            } else {
                $errors[] = "El correo es incorrecto";
            }
        }
    }
    require "includes/funciones.php";
    incluirTemplate("header");
?>
    <main class="contenedor seccion contenido-centrado">
        <h1>Iniciar Sesión</h1>
        <?php foreach ($errors as $key => $error): ?>
            <div class="alerta error contenido-centrado mx-auto">
                <?php echo $error; ?> 
            </div>
        <?php endforeach ?>
        <form method="POST" class="formulario form-login" novalidate>
        <!-- el atributo novalidate en un <form> anula las validaciones de HTML5 -->
            <fieldset>
                <legend>Credenciales</legend>

                <label for="email">Email</label>
                <input type="email" placeholder="Tu email" id="email" name="email" value="<?php echo $email ?>" required>
                
                <label for="password">Password</label>
                <input class="input-pass" type="password" placeholder="Tu password" id="password" name="password" required>
                
                <input id="checkbox" type="checkbox" onclick="showPassword()">
                <label for="checkbox">Mostrar Contraseña</label>
            </fieldset>
            <input type="submit" value="Iniciar Sesión" class="boton-verde">
        </form>
    </main>
    <script>
        function showPassword() {
            let inputPassword = document.getElementById("password");
            if (inputPassword.type === "password") {
                inputPassword.type = "text";
            } else {
                inputPassword.type = "password";
            }
        }
    </script>
<?php incluirTemplate("footer"); ?>

