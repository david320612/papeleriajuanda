<?php
include 'db_connection.php';
session_start();

// Función para eliminar un producto
if (isset($_GET['eliminar'])) {
    $id_producto = $_GET['eliminar'];

    // Preparar la consulta SQL para eliminar el producto
    $sql_delete = "DELETE FROM productos WHERE id = $id_producto";

    if ($conn->query($sql_delete) === TRUE) {
        echo "Producto eliminado correctamente.";
    } else {
        echo "Error al eliminar el producto: " . $conn->error;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir los datos del formulario
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];

    // Guardar la imagen en el servidor
    $imagen_dir = "imagenes/";
    $imagen_nombre = $_FILES["imagen"]["name"];
    $imagen_tmp = $_FILES["imagen"]["tmp_name"];
    $ruta_imagen = $imagen_dir . $imagen_nombre;

    if (move_uploaded_file($imagen_tmp, $ruta_imagen)) {
        // Conectar a la base de datos (código de conexión omitido)

        // Preparar la consulta SQL para insertar el producto
        $sql = "INSERT INTO productos (nombre, precio, imagen) VALUES ('$nombre', $precio, '$ruta_imagen')";

        if ($conn->query($sql) === TRUE) {
            echo "Producto agregado correctamente.";
        } else {
            echo "Error al agregar el producto: " . $conn->error;
        }

        // Cerrar la conexión a la base de datos
        $conn->close();
    } else {
        echo "Error al subir la imagen.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
</head>
<body>
    <h1>Productos</h1>

    <!-- Formulario para agregar un nuevo producto -->
    <h2>Agregar Nuevo Producto</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
        <label for="nombre">Nombre del Producto:</label>
        <input type="text" id="nombre" name="nombre" required><br><br>

        <label for="precio">Precio del Producto:</label>
        <input type="number" id="precio" name="precio" step="0.01" min="0" required><br><br>

        <label for="imagen">Imagen del Producto:</label>
        <input type="file" id="imagen" name="imagen" accept="image/*" required><br><br>

        <button type="submit">Agregar Producto</button>
    </form>

    <!-- Mostrar productos existentes y recién agregados -->
    <h2>Lista de Productos</h2>
    <?php
    // Consulta SQL para seleccionar todos los productos
    $sql = "SELECT * FROM productos";
    $result = $conn->query($sql);

    // Verificar si hay productos en la base de datos
    if ($result->num_rows > 0) {
        // Mostrar los productos
        while($row = $result->fetch_assoc()) {
            echo "<div>";
            echo "<strong>" . $row['nombre'] . "</strong>";
            echo "<br>";
            echo "Precio: $" . number_format($row['precio'], 2);
            echo "<br>";
            echo "<img src='" . $row['imagen'] . "' alt='" . $row['nombre'] . "' style='max-width: 200px;'>";
            echo "<br>";
            echo "<a href='" . $_SERVER["PHP_SELF"] . "?eliminar=" . $row['id'] . "'>Eliminar Producto</a>";
            echo "</div>";
        }
    } else {
        echo "No hay productos disponibles.";
    }
    ?>
    
    <!-- Botón para volver atrás -->
    <button onclick="window.history.back()">Volver</button>
</body>
</html>