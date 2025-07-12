<?php
// public/index.php

// Inicia la sesión al principio del script para manejar mensajes flash
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Carga las clases necesarias
require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/Models/Empleado.php'; // Ya se incluyen en EmpleadoController
require_once __DIR__ . '/../src/Models/Area.php';     // Pero es buena práctica tenerlos aquí si no hay autoloading
require_once __DIR__ . '/../src/Models/Rol.php';
require_once __DIR__ . '/../src/Controllers/EmpleadoController.php';


// Crea una instancia de la base de datos
$database = new Database();
$db = $database->getConnection(); // Obtiene la conexión PDO

// Crea una instancia del controlador y maneja la solicitud
$controller = new EmpleadoController($db);
$controller->handleRequest();
?>