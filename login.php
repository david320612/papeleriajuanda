<?php
include 'db_connection.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Verifica las credenciales en la base de datos
    $sql_check_user = "SELECT * FROM usuarios WHERE nombre_usuario = ?";
    $stmt_check_user = $conn->prepare($sql_check_user);
    $stmt_check_user->bind_param("s", $username);
    $stmt_check_user->execute();
    $result_check_user = $stmt_check_user->get_result();

    // Verificar si el usuario existe
    if ($result_check_user->num_rows > 0) {
        $user_row = $result_check_user->fetch_assoc();
        $stored_password = $user_row["contraseña"];
        $tipo_usuario = $user_row["tipo_usuario"];

        // Verificar la contraseña utilizando password_verify
        if (password_verify($password, $stored_password)) {
            // Inicio de sesión exitoso, redirige a otra página
            $_SESSION["username"] = $username; // Guarda el nombre de usuario en la sesión
            $_SESSION["tipo_usuario"] = $tipo_usuario; // Guarda el tipo de usuario en la sesión
            if ($tipo_usuario === 'administrador') {
                $_SESSION["mensajeBienvenida"] = "Bienvenido, eres administrador.";
            }
            header("Location: index.html");
            exit();
        } else {
            $mensajeError = "Usuario o contraseña no válidos.";
        }
    } else {
        $mensajeError = "Usuario o contraseña no válidos.";
    }

    $stmt_check_user->close();
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <title>Iniciar Sesión</title>
</head>
<body>
    <div class="container mt-5">
        <?php if (isset($mensajeError)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $mensajeError; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION["mensajeBienvenida"])): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $_SESSION["mensajeBienvenida"]; ?>
            </div>
            <?php unset($_SESSION["mensajeBienvenida"]); ?>
        <?php endif; ?>

        <form action="login.php" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Usuario:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
        </form>

        <div class="mt-3">
            <p>¿No tienes cuenta? <a href="register.php">Crear cuenta</a></p>
        </div>
    </div>
</body>
</html>