<?php

// Asegúrate de que $empleados esté definida
if (!isset($empleados)) {
    $empleados = []; // Evita errores si no se pasan datos
}
?>

<div class="container">
    <h2>Lista de Empleados</h2>
    <p>
        <a href="index.php?action=create" class="button button-primary">Crear Nuevo Empleado</a>
    </p>

    <?php if (empty($empleados)): ?>
        <p>No hay empleados registrados. ¡Crea uno nuevo!</p>
    <?php else: ?>
<div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Sexo</th>
                <th>Área</th>
                <th>Boletín</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($empleados as $empleado): ?>
                <tr>
                    <td><?php echo htmlspecialchars($empleado['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($empleado['email']); ?></td>
                    <td><?php echo htmlspecialchars($empleado['sexo'] == 'M' ? 'Masculino' : 'Femenino'); ?></td>
                    <td><?php echo htmlspecialchars($empleado['area_nombre']); ?></td>
                    <td><?php echo htmlspecialchars($empleado['boletin'] == 1 ? 'Sí' : 'No'); ?></td>
                    <td class="actions">
                        <a href="index.php?action=edit&id=<?php echo $empleado['id']; ?>" class="button button-edit">Modificar</a>
                        <a href="index.php?action=delete&id=<?php echo $empleado['id']; ?>" class="button button-delete" onclick="return confirm('¿Estás seguro de que quieres eliminar a este empleado?');">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
    <?php endif; ?>
</div>

<style>
.table-responsive {
    width: 100%
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
.container {
    max-width: 900px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}
h2 {
    color: #333;
    text-align: center;
    margin-bottom: 20px;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}
table, th, td {
    border: 1px solid #ddd;
}
th, td {
    padding: 10px;
    text-align: left;
}
th {
    background-color: #f2f2f2;
}
.actions {
    white-space: nowrap; /* Evita que los botones se rompan en varias líneas */
}
.button {
    display: inline-block;
    padding: 8px 12px;
    border-radius: 5px;
    text-decoration: none;
    color: white;
    text-align: center;
    border: none;
    cursor: pointer;
    font-size: 0.9em;
    margin-right: 5px;
}
.button-primary {
    background-color: #007bff;
}
.button-edit {
    background-color: #ffc107;
    color: #333;
}
.button-delete {
    background-color: #dc3545;
}
.button:hover {
    opacity: 0.9;
}
.message {
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 5px;
    font-weight: bold;
}
.message.success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}
.message.danger {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}
</style>
