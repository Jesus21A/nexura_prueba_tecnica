<?php

class Empleado {
    public $id;
    public $nombre;
    public $email;
    public $sexo;
    public $area_id;
    public $boletin;
    public $descripcion;
    public $roles;

    private $conn;
    private $table_name = "empleados";
    private $table_empleado_rol = "empleado_rol";

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Valida los datos del empleado antes de insertarlos o actualizarlos.
     * @return array Array con los errores de validación. Vacío si no hay errores.
     */
    public function validate() {
        $errors = [];

        if (empty($this->nombre)) {
            $errors['nombre'] = "El nombre completo es obligatorio.";
        } elseif (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $this->nombre)) {
            $errors['nombre'] = "El nombre solo debe contener letras, tildes y espacios.";
        }

        if (empty($this->email)) {
            $errors['email'] = "El correo electrónico es obligatorio.";
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "El formato del correo electrónico no es válido.";
        }

        if (empty($this->sexo) || !in_array($this->sexo, ['M', 'F'])) {
            $errors['sexo'] = "El sexo es obligatorio y debe ser 'Masculino' o 'Femenino'.";
        }

        if (empty($this->area_id)) {
            $errors['area_id'] = "El área es obligatoria.";
        } else {
            // Verificar si el area_id existe en la tabla 'areas'
            $query = "SELECT id FROM areas WHERE id = :area_id LIMIT 0,1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':area_id', $this->area_id);
            $stmt->execute();
            if ($stmt->rowCount() == 0) {
                $errors['area_id'] = "El área seleccionada no es válida.";
            }
        }

        if (empty($this->descripcion)) {
            $errors['descripcion'] = "La descripción es obligatoria.";
        }

        return $errors;
    }


    /**
     * Crea un nuevo registro de empleado en la base de datos.
     * @return bool True en caso de éxito, false en caso contrario.
     */
    public function create() {
        // Valida primero los datos
        $validationErrors = $this->validate();
        if (!empty($validationErrors)) {
            return ['errors' => $validationErrors];
        }

        $query = "INSERT INTO " . $this->table_name . "
                  SET
                      nombre=:nombre,
                      email=:email,
                      sexo=:sexo,
                      area_id=:area_id,
                      boletin=:boletin,
                      descripcion=:descripcion";

        $stmt = $this->conn->prepare($query);

        // Limpiar y enlazar valores
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->sexo = htmlspecialchars(strip_tags($this->sexo));
        $this->area_id = htmlspecialchars(strip_tags($this->area_id));
        $this->boletin = htmlspecialchars(strip_tags($this->boletin));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));

        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':sexo', $this->sexo);
        $stmt->bindParam(':area_id', $this->area_id);
        $stmt->bindParam(':boletin', $this->boletin);
        $stmt->bindParam(':descripcion', $this->descripcion);

        try {
            $this->conn->beginTransaction();
            if ($stmt->execute()) {
                $this->id = $this->conn->lastInsertId();

                if (!empty($this->roles)) {
                    $this->assignRoles($this->id, $this->roles);
                }
                $this->conn->commit();
                return true;
            }
            $this->conn->rollBack();
            return false;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error al crear empleado: " . $e->getMessage());
            return ['error' => 'No se pudo crear el empleado debido a un error interno.'];
        }
    }

    /**
     * Lee todos los empleados de la base de datos, incluyendo área y roles.
     * @return PDOStatement Retorna el statement con los resultados.
     */
    public function read() {
        $query = "SELECT
                    e.id, e.nombre, e.email, e.sexo, e.boletin, e.descripcion,
                    a.nombre as area_nombre
                  FROM
                    " . $this->table_name . " e
                  LEFT JOIN
                    areas a ON e.area_id = a.id
                  ORDER BY
                    e.nombre ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Lee un solo empleado por su ID.
     * @param int $id El ID del empleado a leer.
     * @return array|bool Retorna los datos del empleado o false si no se encuentra.
     */
    public function readOne() {
        $query = "SELECT
                    e.id, e.nombre, e.email, e.sexo, e.boletin, e.descripcion, e.area_id
                  FROM
                    " . $this->table_name . " e
                  WHERE
                    e.id = ?
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->nombre = $row['nombre'];
            $this->email = $row['email'];
            $this->sexo = $row['sexo'];
            $this->area_id = $row['area_id'];
            $this->boletin = $row['boletin'];
            $this->descripcion = $row['descripcion'];
            $this->roles = $this->getEmployeeRoles($this->id);
            return true;
        }
        return false;
    }

    /**
     * Actualiza un registro de empleado existente.
     * @return bool True en caso de éxito, false en caso contrario.
     */
    public function update() {
        // Valida primero los datos
        $validationErrors = $this->validate();
        if (!empty($validationErrors)) {
            return ['errors' => $validationErrors];
        }

        $query = "UPDATE " . $this->table_name . "
                  SET
                      nombre=:nombre,
                      email=:email,
                      sexo=:sexo,
                      area_id=:area_id,
                      boletin=:boletin,
                      descripcion=:descripcion
                  WHERE
                      id = :id";

        $stmt = $this->conn->prepare($query);

        // Limpiar y enlazar valores
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->sexo = htmlspecialchars(strip_tags($this->sexo));
        $this->area_id = htmlspecialchars(strip_tags($this->area_id));
        $this->boletin = htmlspecialchars(strip_tags($this->boletin));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':sexo', $this->sexo);
        $stmt->bindParam(':area_id', $this->area_id);
        $stmt->bindParam(':boletin', $this->boletin);
        $stmt->bindParam(':descripcion', $this->descripcion);
        $stmt->bindParam(':id', $this->id);

        try {
            $this->conn->beginTransaction();
            if ($stmt->execute()) {
                // Actualizar la relación con los roles
                $this->clearEmployeeRoles($this->id); // Borrar roles existentes
                if (!empty($this->roles)) {
                    $this->assignRoles($this->id, $this->roles); // Asignar nuevos roles
                }
                $this->conn->commit();
                return true;
            }
            $this->conn->rollBack();
            return false;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error al actualizar empleado: " . $e->getMessage());
            return ['error' => 'No se pudo actualizar el empleado debido a un error interno.'];
        }
    }

    /**
     * Elimina un registro de empleado.
     * @return bool True en caso de éxito, false en caso contrario.
     */
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);

        try {
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error al eliminar empleado: " . $e->getMessage());
            return ['error' => 'No se pudo eliminar el empleado debido a un error interno.'];
        }
    }

    /**
     * Asigna roles a un empleado.
     * @param int $empleado_id ID del empleado.
     * @param array $roles_ids Array de IDs de los roles a asignar.
     */
    private function assignRoles($empleado_id, $roles_ids) {
        $query = "INSERT INTO " . $this->table_empleado_rol . " (empleado_id, rol_id) VALUES (:empleado_id, :rol_id)";
        $stmt = $this->conn->prepare($query);

        foreach ($roles_ids as $rol_id) {
            $stmt->bindParam(':empleado_id', $empleado_id);
            $stmt->bindParam(':rol_id', $rol_id);
            $stmt->execute();
        }
    }

    /**
     * Elimina todos los roles asociados a un empleado.
     * @param int $empleado_id ID del empleado.
     */
    private function clearEmployeeRoles($empleado_id) {
        $query = "DELETE FROM " . $this->table_empleado_rol . " WHERE empleado_id = :empleado_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':empleado_id', $empleado_id);
        $stmt->execute();
    }

    /**
     * Obtiene los IDs de los roles asociados a un empleado.
     * @param int $empleado_id ID del empleado.
     * @return array Array de IDs de roles.
     */
    public function getEmployeeRoles($empleado_id) {
        $query = "SELECT rol_id FROM " . $this->table_empleado_rol . " WHERE empleado_id = :empleado_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':empleado_id', $empleado_id);
        $stmt->execute();

        $roles = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $roles[] = $row['rol_id'];
        }
        return $roles;
    }
}
?>