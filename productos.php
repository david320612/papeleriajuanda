<?php
session_start();
require_once "db_connection.php";

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="productos" content="width=divice-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="estilos.css">
    <title>PJ: Nuestros productos</title>
</head>
<body>
<header class="menu-producto">
    <div class="logo">
        <img src="imagenes/imagen.png" alt="" style="width: 100%;">
        <h2 class="nombre-trabajo"> PAPELERIA JUANDA</h2>
    </div>
    <nav>
        <a href="index.html" class="inicio">Inicio</a>
        <a href="nosotros.html" class="nosotros">Nosotros</a>
        <a href="productos.php" class="productos">Productos</a>
        <div class="menu-categoria">
            <b><span><a href="categorias.html"> Categorias</a></span></b>
            <div class="categoria-contenido">
                <p><a href="categorias.html">Papeles</a></p>
                <p><a href="categorias.html">Lapices</a></p>
                <p><a href="categorias.html">Otros</a></p>
            </div>
        </div>
        <a href="trabaje-nosotros.html" class="trabaje-nosotros">Trabaje con nosotros</a>
        <a href="contactos.html" class="contacto">Contacto</a>
        <a href="login.php" class="index">Resgistrate</a>
    </nav>
</header>

<h1 align="center">Algunos de nuestros mejores productos</h1>
<div class="valor">
    <?php
    // Consulta SQL para seleccionar todos los productos
    $sql = "SELECT * FROM productos";
    $result = $conn->query($sql);

    // Verificar si hay productos en la base de datos
    if ($result->num_rows > 0) {
        // Mostrar los productos
        while($row = $result->fetch_assoc()) {
            echo "<div class='valor-producto'>";
            echo "<img src='" . $row['imagen'] . "' alt='" . $row['nombre'] . "' style='width: 100%;'>";
            echo "<h3>" . $row['nombre'] . "</h3>";
            echo "<p>Precio: <span>$" . $row['precio'] . "</span></p>";
            echo "<button>Comprar</button>";
            echo "</div>";
        }
    } else {
        echo "No hay productos disponibles.";
    }
    ?>
</div>

<!-- Botón "Agregar Producto" -->
<?php if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'administrador') : ?>
    <div class="container text-center mt-3">
        <a href='agregar-productos.php'>Agregar Producto</a>
    </div>
<?php endif; ?>

<footer>
    <div class="pie-pagina">
        <div class="nombre">JUAN DAVID RODRIGUEZ</div>
        <div class="mapa">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.1167011434904!2d-75.56783883148647!3d6.2483494124925025!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e442857e5c31f3f%3A0x1b5c977dda94e7bf!2sCENSA%20Medell%C3%ADn!5e0!3m2!1ses!2sco!4v1700176243992!5m2!1ses!2sco" width="200" height="200" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <div class="texto">CON FINES EDUCATIVOS</div>
    </div>
</footer>

</body>
</html>