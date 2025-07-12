<?php

// Asegúrate de que las variables estén definidas para evitar errores si la vista se carga directamente
if (!isset($empleado_data)) $empleado_data = (object)[]; // Objeto vacío para campos nuevos
if (!isset($areas)) $areas = [];
if (!isset($roles)) $roles = [];
if (!isset($empleado_roles)) $empleado_roles = [];
if (!isset($errors)) $errors = [];

$is_edit_mode = isset($empleado_data->id) && $empleado_data->id !== '';
$form_action = $is_edit_mode ? "index.php?action=edit&id=" . htmlspecialchars($empleado_data->id) : "index.php?action=create";

// Función auxiliar para obtener el valor de un campo, con prioridad al POST si existe error
function get_field_value($data, $field_name, $default = '') {
    if (is_array($data) && isset($data[$field_name])) {
        return htmlspecialchars($data[$field_name]);
    } elseif (is_object($data) && isset($data->$field_name)) {
        return htmlspecialchars($data->$field_name);
    }
    return htmlspecialchars($default);
}
?>

<div class="container">
    <h2><?php echo $is_edit_mode ? 'Modificar Empleado' : 'Crear Empleado'; ?></h2>
    <p>Los campos con asteriscos (*) son obligatorios</p>

    <form action="<?php echo $form_action; ?>" method="POST" id="empleadoForm">
        <?php if ($is_edit_mode): ?>
            <input type="hidden" name="id" value="<?php echo get_field_value($empleado_data, 'id'); ?>">
        <?php endif; ?>

        <div class="form-group">
            <label for="nombre">Nombre completo *</label>
            <input type="text" id="nombre" name="nombre" placeholder="Nombre completo del empleado"
                   value="<?php echo get_field_value($empleado_data, 'nombre'); ?>" required>
            <?php if (isset($errors['nombre'])): ?><span class="error"><?php echo $errors['nombre']; ?></span><?php endif; ?>
        </div>

        <div class="form-group">
            <label for="email">Correo electrónico *</label>
            <input type="email" id="email" name="email" placeholder="Correo electrónico"
                   value="<?php echo get_field_value($empleado_data, 'email'); ?>" required>
            <?php if (isset($errors['email'])): ?><span class="error"><?php echo $errors['email']; ?></span><?php endif; ?>
        </div>

        <div class="form-group">
            <label>Sexo *</label>
            <div class="radio-group">
                <input type="radio" id="sexoM" name="sexo" value="M"
                       <?php echo (get_field_value($empleado_data, 'sexo') == 'M') ? 'checked' : ''; ?> required>
                <label for="sexoM">Masculino</label>

                <input type="radio" id="sexoF" name="sexo" value="F"
                       <?php echo (get_field_value($empleado_data, 'sexo') == 'F') ? 'checked' : ''; ?> required>
                <label for="sexoF">Femenino</label>
            </div>
            <?php if (isset($errors['sexo'])): ?><span class="error"><?php echo $errors['sexo']; ?></span><?php endif; ?>
        </div>

        <div class="form-group">
            <label for="area_id">Área *</label>
            <select id="area_id" name="area_id" required>
                <option value="">Seleccione un área</option>
                <?php foreach ($areas as $area): ?>
                    <option value="<?php echo htmlspecialchars($area['id']); ?>"
                            <?php echo (get_field_value($empleado_data, 'area_id') == $area['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($area['nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errors['area_id'])): ?><span class="error"><?php echo $errors['area_id']; ?></span><?php endif; ?>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción *</label>
            <textarea id="descripcion" name="descripcion" rows="5" placeholder="Descripción de la experiencia del empleado" required><?php echo get_field_value($empleado_data, 'descripcion'); ?></textarea>
            <?php if (isset($errors['descripcion'])): ?><span class="error"><?php echo $errors['descripcion']; ?></span><?php endif; ?>
        </div>

        <div class="form-group checkbox-group">
            <input type="checkbox" id="boletin" name="boletin" value="1"
                   <?php echo (get_field_value($empleado_data, 'boletin') == 1) ? 'checked' : ''; ?>>
            <label for="boletin">Deseo recibir boletín informativo</label>
        </div>

        <div class="form-group">
            <label>Roles *</label>
            <div class="checkbox-group">
                <?php foreach ($roles as $rol): ?>
                    <input type="checkbox" id="rol_<?php echo htmlspecialchars($rol['id']); ?>" name="roles[]"
                           value="<?php echo htmlspecialchars($rol['id']); ?>"
                           <?php echo in_array($rol['id'], $empleado_roles) ? 'checked' : ''; ?>>
                    <label for="rol_<?php echo htmlspecialchars($rol['id']); ?>"><?php echo htmlspecialchars($rol['nombre']); ?></label><br>
                <?php endforeach; ?>
            </div>
            <?php if (isset($errors['roles'])): ?><span class="error"><?php echo $errors['roles']; ?></span><?php endif; ?>
        </div>

        <div class="form-actions">
            <button type="submit" class="button button-primary">Guardar</button>
            <a href="index.php?action=list" class="button">Cancelar</a>
        </div>
    </form>
</div>

<style>
/* Estilos para el formulario - puedes mover esto a style.css */
.container {
    max-width: 600px;
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
.form-group {
    margin-bottom: 15px;
}
.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #555;
}
.form-group input[type="text"],
.form-group input[type="email"],
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box; /* Asegura que el padding no añada ancho extra */
    font-size: 1em;
}
.form-group input[type="radio"],
.form-group input[type="checkbox"] {
    margin-right: 5px;
}
.radio-group label, .checkbox-group label {
    display: inline-block;
    margin-right: 15px;
    font-weight: normal; /* Para que la etiqueta del radio/checkbox no sea tan negrita */
}
.error {
    color: #dc3545;
    font-size: 0.85em;
    margin-top: 5px;
    display: block; /* Para que el error aparezca debajo del campo */
}
.form-actions {
    text-align: right;
    margin-top: 20px;
}
.button {
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    color: white;
    background-color: #6c757d; /* Gris para el botón Cancelar */
    border: none;
    cursor: pointer;
    font-size: 1em;
    margin-left: 10px;
}
.button-primary {
    background-color: #007bff;
}
.button:hover {
    opacity: 0.9;
}
</style>

<script src="js/validation.js"></script>