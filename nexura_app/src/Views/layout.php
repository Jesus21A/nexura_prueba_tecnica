<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : "Aplicación de Empleados"; ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Gestión de Empleados</h1>
        <nav>
            <ul>
                <li><a href="index.php">Lista de Empleados</a></li>
                <li><a href="index.php?action=create">Crear Nuevo Empleado</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <?php
        
        if (isset($_SESSION['message'])) {
            echo '<div class="message ' . ($_SESSION['message_type'] ?? 'success') . '">';
            echo $_SESSION['message'];
            echo '</div>';
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
        }
        ?>

        <?php echo $content; ?>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Aplicación de Empleados. Todos los derechos reservados.</p>
    </footer>

    <script src="js/validation.js"></script>
</body>
</html>