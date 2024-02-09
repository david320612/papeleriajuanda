<?php
include 'db_connection.php';

// Inicializa la variable que almacenará el mensaje
$mensajeRegistro = "";

// Establecer la codificación de caracteres para la conexión
$conn->set_charset("utf8");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $userType = $_POST["userType"];

    // Verifica la longitud de la contraseña
    if (strlen($password) < 6) {
        $mensajeRegistro = "La contraseña debe tener al menos 6 caracteres.";
    } else {
        // Verifica si el nombre de usuario ya existe
        $sql_check_user = "SELECT * FROM usuarios WHERE nombre_usuario = ?";
        $stmt_check_user = $conn->prepare($sql_check_user);

        if ($stmt_check_user) {
            $stmt_check_user->bind_param("s", $username);
            $stmt_check_user->execute();
            $stmt_check_user->store_result();

            if ($stmt_check_user->num_rows > 0) {
                $mensajeRegistro = "El nombre de usuario ya está en uso. Por favor, elige otro.";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $sql_insert_user = "INSERT INTO usuarios (nombre_usuario, contraseña, tipo_usuario) VALUES (?, ?, ?)";
                $stmt_insert_user = $conn->prepare($sql_insert_user);

                if ($stmt_insert_user) {
                    $stmt_insert_user->bind_param("sss", $username, $hashedPassword, $userType);
                    $stmt_insert_user->execute();

                    // Verifica si la consulta fue exitosa
                    if ($stmt_insert_user->affected_rows > 0) {
                        $mensajeRegistro = "Registro exitoso. Ahora puedes iniciar sesión.";
                    } else {
                        $mensajeRegistro = "Error al registrar el usuario: " . $stmt_insert_user->error;
                    }

                    $stmt_insert_user->close();
                } else {
                    $mensajeRegistro = "Error en la preparación de la consulta: " . $conn->error;
                }
            }

            $stmt_check_user->close();
        } else {
            $mensajeRegistro = "Error en la preparación de la consulta: " . $conn->error;
        }
    }

    // Cierra la conexión
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <title>Crear Cuenta</title>
</head>
<body>
    <div class="container mt-5">
        <!-- Muestra el mensaje solo si se ha enviado el formulario -->
        <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
            <p><?php echo $mensajeRegistro; ?></p>
        <?php endif; ?>

        <form action="register.php" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Usuario:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña (mínimo 6 caracteres):</label>
                <input type="password" class="form-control" id="password" name="password" minlength="6" required>
            </div>
            <div class="mb-3">
                <label for="userType" class="form-label">Tipo de Usuario:</label>
                <select class="form-select" id="userType" name="userType" required>
                    <option value="casual">Casual</option>
                    <option value="administrador">Administrador</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Crear Cuenta</button> <a href="login.php">Iniciar sesion</a></p>
        </form>
    </div>
</body>
</html>