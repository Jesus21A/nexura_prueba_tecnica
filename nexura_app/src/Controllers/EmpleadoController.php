<?php

require_once __DIR__ . '/../Models/Empleado.php';
require_once __DIR__ . '/../Models/Area.php';
require_once __DIR__ . '/../Models/Rol.php';

class EmpleadoController {
    private $db;
    private $empleado;
    private $area;
    private $rol;

    public function __construct($db) {
        $this->db = $db;
        $this->empleado = new Empleado($db);
        $this->area = new Area($db);
        $this->rol = new Rol($db);
    }

    // Método principal para manejar las solicitudes (GET/POST)
    public function handleRequest() {
        $action = $_GET['action'] ?? 'list';

        try {
            switch ($action) {
                case 'create':
                    $this->createEmpleado();
                    break;
                case 'edit':
                    $this->editEmpleado();
                    break;
                case 'delete':
                    $this->deleteEmpleado();
                    break;
                case 'list':
                default:
                    $this->listEmpleados();
                    break;
            }
        } catch (PDOException $e) {
            $_SESSION['message'] = "Error en la base de datos: " . $e->getMessage();
            $_SESSION['message_type'] = "danger";
            $this->listEmpleados();
        } catch (Exception $e) {
            $_SESSION['message'] = "Ocurrió un error inesperado: " . $e->getMessage();
            $_SESSION['message_type'] = "danger";
            $this->listEmpleados();
        }
    }

    // Muestra la lista de empleados
    private function listEmpleados() {
        $stmt = $this->empleado->read();
        $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $page_title = "Lista de Empleados";
        ob_start();
        include __DIR__ . '/../Views/empleado_list.php';
        $content = ob_get_clean(); 
        include __DIR__ . '/../Views/layout.php';
    }

    // Muestra el formulario para crear un empleado o procesa su envío
    private function createEmpleado() {
        $empleado_data = [];
        $empleado_roles = [];
        $errors = [];
        $message = '';
        $message_type = '';

        // Si se envió el formulario (POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->empleado->nombre = trim($_POST['nombre'] ?? '');
            $this->empleado->email = trim($_POST['email'] ?? '');
            $this->empleado->sexo = $_POST['sexo'] ?? '';
            $this->empleado->area_id = $_POST['area_id'] ?? '';
            $this->empleado->boletin = isset($_POST['boletin']) ? 1 : 0;
            $this->empleado->descripcion = trim($_POST['descripcion'] ?? '');
            $this->empleado->roles = $_POST['roles'] ?? [];

            $result = $this->empleado->create();

            if (isset($result['errors'])) {
                $errors = $result['errors'];
                $message = "Hay errores en el formulario. Por favor, corríjalos.";
                $message_type = "danger";

                $empleado_data = $_POST;
            } elseif ($result === true) {
                $_SESSION['message'] = "Empleado creado exitosamente.";
                $_SESSION['message_type'] = "success";
                header('Location: index.php?action=list');
                exit;
            } else {
                $message = $result['error'] ?? "Error desconocido al crear el empleado.";
                $message_type = "danger";

                $empleado_data = $_POST;
            }
        }

        // Obtener áreas y roles para el formulario
        $areas_stmt = $this->area->readAll();
        $areas = $areas_stmt->fetchAll(PDO::FETCH_ASSOC);

        $roles_stmt = $this->rol->readAll();
        $roles = $roles_stmt->fetchAll(PDO::FETCH_ASSOC);

        $page_title = "Crear Empleado";
        ob_start();
        include __DIR__ . '/../Views/empleado_form.php';
        $content = ob_get_clean();
        include __DIR__ . '/../Views/layout.php';
    }

    // Muestra el formulario para editar un empleado o procesa su envío
    private function editEmpleado() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['message'] = "ID de empleado no especificado para editar.";
            $_SESSION['message_type'] = "danger";
            header('Location: index.php?action=list');
            exit;
        }

        $this->empleado->id = $id;
        $empleado_found = $this->empleado->readOne();
        $empleado_data = $this->empleado;
        $empleado_roles = $this->empleado->getEmployeeRoles($id);
        $errors = [];
        $message = '';
        $message_type = '';

        if (!$empleado_found && $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['message'] = "Empleado no encontrado.";
            $_SESSION['message_type'] = "danger";
            header('Location: index.php?action=list');
            exit;
        }

        // Si se envió el formulario (POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->empleado->id = $_POST['id'] ?? '';
            $this->empleado->nombre = trim($_POST['nombre'] ?? '');
            $this->empleado->email = trim($_POST['email'] ?? '');
            $this->empleado->sexo = $_POST['sexo'] ?? '';
            $this->empleado->area_id = $_POST['area_id'] ?? '';
            $this->empleado->boletin = isset($_POST['boletin']) ? 1 : 0;
            $this->empleado->descripcion = trim($_POST['descripcion'] ?? '');
            $this->empleado->roles = $_POST['roles'] ?? [];

            $result = $this->empleado->update();

            if (isset($result['errors'])) {
                $errors = $result['errors'];
                $message = "Hay errores en el formulario. Por favor, corríjalos.";
                $message_type = "danger";

                $empleado_data = (object) $_POST;
                $empleado_roles = $_POST['roles'] ?? [];
            } elseif ($result === true) {
                $_SESSION['message'] = "Empleado actualizado exitosamente.";
                $_SESSION['message_type'] = "success";
                header('Location: index.php?action=list');
                exit;
            } else {
                $message = $result['error'] ?? "Error desconocido al actualizar el empleado.";
                $message_type = "danger";

                $empleado_data = (object) $_POST;
                $empleado_roles = $_POST['roles'] ?? [];
            }
        }

        // Obtener áreas y roles para el formulario
        $areas_stmt = $this->area->readAll();
        $areas = $areas_stmt->fetchAll(PDO::FETCH_ASSOC);

        $roles_stmt = $this->rol->readAll();
        $roles = $roles_stmt->fetchAll(PDO::FETCH_ASSOC);

        $page_title = "Modificar Empleado";
        ob_start();
        include __DIR__ . '/../Views/empleado_form.php';
        $content = ob_get_clean();
        include __DIR__ . '/../Views/layout.php';
    }

    // Elimina un empleado
    private function deleteEmpleado() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['message'] = "ID de empleado no especificado para eliminar.";
            $_SESSION['message_type'] = "danger";
        } else {
            $this->empleado->id = $id;
            $result = $this->empleado->delete();

            if ($result === true) {
                $_SESSION['message'] = "Empleado eliminado exitosamente.";
                $_SESSION['message_type'] = "success";
            } else {
                $message = $result['error'] ?? "Error desconocido al eliminar el empleado.";
                $_SESSION['message'] = $message;
                $_SESSION['message_type'] = "danger";
            }
        }
        header('Location: index.php?action=list');
        exit;
    }
}
?>