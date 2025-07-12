document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('empleadoForm');
    if (form) {
        form.addEventListener('submit', function(event) {
            let isValid = true;

            // Limpiar mensajes de error previos
            document.querySelectorAll('.error').forEach(e => e.remove());
            document.querySelectorAll('input, select, textarea').forEach(input => {
                input.classList.remove('is-invalid');
            });

            // --- Validación de Nombre ---
            const nombre = form.elements['nombre'];
            const nombreRegex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;
            if (nombre.value.trim() === '') {
                displayError(nombre, 'El nombre completo es obligatorio.');
                isValid = false;
            } else if (!nombreRegex.test(nombre.value.trim())) {
                displayError(nombre, 'El nombre solo debe contener letras, tildes y espacios.');
                isValid = false;
            }

            // --- Validación de Email ---
            const email = form.elements['email'];
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (email.value.trim() === '') {
                displayError(email, 'El correo electrónico es obligatorio.');
                isValid = false;
            } else if (!emailRegex.test(email.value.trim())) {
                displayError(email, 'El formato del correo electrónico no es válido.');
                isValid = false;
            }

            // --- Validación de Sexo ---
            const sexoM = form.elements['sexo'][0]; // Radio button masculino
            const sexoF = form.elements['sexo'][1]; // Radio button femenino
            if (!sexoM.checked && !sexoF.checked) {
                displayError(sexoM.parentNode, 'El sexo es obligatorio.');
                isValid = false;
            }

            // --- Validación de Área ---
            const area = form.elements['area_id'];
            if (area.value === '') {
                displayError(area, 'El área es obligatoria.');
                isValid = false;
            }

            // --- Validación de Descripción ---
            const descripcion = form.elements['descripcion'];
            if (descripcion.value.trim() === '') {
                displayError(descripcion, 'La descripción es obligatoria.');
                isValid = false;
            }

            if (!isValid) {
                event.preventDefault(); // Detiene el envío del formulario si hay errores
            }
        });
    }

    // Función para mostrar mensajes de error
    function displayError(element, message) {
        const errorSpan = document.createElement('span');
        errorSpan.classList.add('error');
        errorSpan.textContent = message;
        element.parentNode.insertBefore(errorSpan, element.nextSibling);
        element.classList.add('is-invalid');
    }
});