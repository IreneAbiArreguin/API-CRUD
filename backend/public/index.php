<?php
// ===================================================================================
// Este archivo es el punto de entrada principal para la aplicación backend del sistema
// de control académico. Aquí se configuran los encabezados CORS, se cargan los
// controladores y se realiza el ruteo manual de las solicitudes HTTP entrantes.
// =

// Configuración de CORS para permitir solicitudes desde el frontend
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if ($origin === 'http://localhost:5173') {
    // Permite solicitudes solo desde el frontend en desarrollo
    header("Access-Control-Allow-Origin: http://localhost:5173");
    header("Access-Control-Allow-Credentials: true");
} else {
    // Permite solicitudes desde cualquier origen (no recomendado en producción)
    header("Access-Control-Allow-Origin: *");
}

header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Autoload simple
require_once __DIR__ . '/../src/controllers/AlumnoController.php';
require_once __DIR__ . '/../src/controllers/MateriaController.php';
require_once __DIR__ . '/../src/controllers/CalificacionController.php';
require_once __DIR__ . '/../src/controllers/ProfesorController.php';
require_once __DIR__ . '/../src/controllers/UsuarioController.php';
require_once __DIR__ . '/../src/controllers/GrupoController.php';
require_once __DIR__ . '/../src/controllers/AuthController.php';
require_once __DIR__ . '/../src/controllers/ArchivoController.php';

// Responder a las peticiones OPTIONS (preflight) de CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Obtener la ruta y método HTTP
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];


// ==============================
// Ruta de autenticación
// ==============================

// Ruta para iniciar sesion
if ($uri === '/login' && $method === 'POST') {
    $controller = new AuthController();
    $controller->login();
    exit;
}

// Ruta para cerrar sesion
if ($uri === '/logout' && $method === 'POST') {
    $controller = new AuthController();
    $controller->logout();
    exit;
}

// ==============================
// Ruta para generar reporte PDF de calificaciones
// ==============================

if ($uri === '/calificaciones/reporte/pdf' && $method === 'GET') {
    $controller = new CalificacionController();
    $controller->handleReportePDF();
    exit;
}

// ==============================
// Ruta para generar reporte PDF de alumnos
// ==============================

if ($uri === '/alumnos/reporte/pdf' && $method === 'GET') {
    $controller = new AlumnoController();
    $controller->handleReportePDF();
    exit;
}

/* 
// ==============================
// Ruta para subir archivos
// ==============================

if ($uri === '/archivos/subir' && $method === 'POST') {
    $controller = new ArchivoController();
    $controller->subir();
    exit;
}

// ==============================
// Ruta para descargar archivos por nombre
// ==============================

if ($uri === '/archivos' && $method === 'POST'){
    $controller = new ArchivoController();
    $controller->descargar($matches[1]);
    exit;
}

// ==============================
// Ruta para eliminar archivos por nombre
// ==============================

if (preg_match('/\/archivos\/eliminar\/(.+)$/', $uri, $matches) && $method === 'DELETE') {
    $controller = new ArchivoController();
    $controller->handleDelete($matches[1]);
    exit;
}

// ==============================
// Ruta para obtener archivos
// ==============================

if (preg_match('/\/archivos\/descargar\/(.+)$/', $uri, $matches) && $method === 'GET') {
    $controller = new ArchivoController();
    $controller->descargar($matches[1]);
    exit;
}

// ==============================
// Ruta para obtener archivos por alumno
// ==============================

if (preg_match('/\/archivos\/alumno\/(\d+)$/', $uri, $matches) && $method === 'GET') {
    $controller = new ArchivoController();
    $controller->archivosPorAlumno($matches[1]);
    exit;
}
 */
// ==============================
// Ruteo para entidad Alumnos (/alumnos y /alumnos/{id})
// ==============================

if (preg_match('/\/alumnos(\/(\d+))?$/', $uri, $matches)) {
    $mensajes = require __DIR__ . '/../vistas/mensajes.php';
    $controller = new AlumnoController();
    $id = $matches[2] ?? null;

    switch ($method) {
        case 'GET':
            $controller->handleGet($id); // Obtener todos los alumnos o un alumno específico
            break;
        case 'POST':
            $controller->handlePost(); // Crear un nuevo alumno
            break;
        case 'PUT':
            if ($id) {
                $controller->handlePut($id); // Actualizar un alumno existente
            } else {
                VistaJson::error($mensajes['Id_requerido'],400);
            }
            break;
        case 'DELETE':
            if ($id) {
                $controller->handleDelete($id); // Eliminar un alumno por ID
            } else {
                VistaJson::error($mensajes['Id_requerido'],400);
            }
            break;
        default:
            VistaJson::error($mensajes['Metodo_no_permitido'],405);
    }
    exit;
}

// ==============================
// Ruteo para entidad Materias (/materias y /materias/{id})
// ==============================
if (preg_match('/\/materias(\/(\d+))?$/', $uri, $matches)) {
    $mensajes = require __DIR__ . '/../vistas/mensajes.php';
    $controller = new MateriaController();
    $id = $matches[2] ?? null;

    switch ($method) {
        case 'GET':
            $controller->handleGet($id); // Obtener todas las materias o una materia específica
            break;
        case 'POST':
            $controller->handlePost(); // Crear una nueva materia
            break;
        case 'PUT':
            if ($id) {
                $controller->handlePut($id); // Actualizar una materia existente
            } else {
                VistaJson::error($mensajes['Id_requerido'],400);
            }
            break;
        case 'DELETE':
            if ($id) {
                $controller->handleDelete($id); // Eliminar una materia por ID
            } else {
                VistaJson::error($mensajes['Id_requerido'],400);
            }
            break;
        default:
            VistaJson::error($mensajes['Metodo_no_permitido'],405);
    }
    exit;
}

// ==============================
// Ruteo para entidad Calificaciones (/calificaciones y /calificaciones/{id})
// ==============================
if (preg_match('/\/calificaciones(\/(\d+))?$/', $uri, $matches)) {
    $mensajes = require __DIR__ . '/../vistas/mensajes.php';
    $controller = new CalificacionController();
    $id = $matches[2] ?? null;

    switch ($method) {
        case 'GET':
            $controller->handleGet($id); // Obtener todas las calificaciones o una calificación específica
            break;
        case 'POST':
            $controller->handlePost(); // Crear una nueva calificación
            break;
        case 'PUT':
            if ($id) {
                $controller->handlePut($id); // Actualizar una calificación existente
            } else {
                VistaJson::error($mensajes['Id_requerido'],400);
            }
            break;
        case 'DELETE':
            if ($id) {
                $controller->handleDelete($id); // Eliminar una calificación por ID
            } else {
                VistaJson::error($mensajes['Id_requerido'],400);
            }
            break;
        default:
            VistaJson::error($mensajes['Metodo_no_permitido'],405);
    }
    exit;
}

// ==============================
// Ruteo para entidad Profesores (/profesores y /profesores/{id})
// ==============================
/* if (preg_match('/\/profesores(\/(\d+))?$/', $uri, $matches)) {
    $mensajes = require __DIR__ . '/../vistas/mensajes.php';
    $controller = new ProfesorController();
    $id = $matches[2] ?? null;

    switch ($method) {
        case 'GET':
            $controller->handleGet($id); // Obtener todos los profesores o un profesor específico
            break;
        case 'POST':
            $controller->handlePost();  // Crear un nuevo profesor
            break;
        case 'PUT':
            if ($id) {
                $controller->handlePut($id); // Actualizar un profesor existente
            } else {
                VistaJson::error($mensajes['Id_requerido'],400);
            }
            break;
        case 'DELETE':
            if ($id) {
                $controller->handleDelete($id); // Eliminar un profesor por ID
            } else {
                VistaJson::error($mensajes['Id_requerido'],400);
            }
            break;
        default:
            VistaJson::error($mensajes['Metodo_no_permitido'],405);
    }
    exit;
} */

// ==============================
// Ruteo para entidad Usuarios (/usuarios y /usuarios/{id})
// ==============================
if (preg_match('/\/usuarios(\/(\d+))?$/', $uri, $matches)) {
    $mensajes = require __DIR__ . '/../vistas/mensajes.php';
    $controller = new UsuarioController();
    $id = $matches[2] ?? null;

    switch ($method) {
        case 'GET':
            $controller->handleGet($id); // Obtener todos los usuarios o un usuario específico
            break;
        case 'POST':
            $controller->handlePost(); // Crear un nuevo usuario
            break;
        case 'PUT':
            if ($id) {
                $controller->handlePut($id); // Actualizar un usuario existente
            } else {
                VistaJson::error($mensajes['Id_requerido'],400);
            }
            break;
        case 'DELETE':
            if ($id) {
                $controller->handleDelete($id); // Eliminar un usuario por ID
            } else {
                VistaJson::error($mensajes['Id_requerido'],400);
            }
            break;
        default:
            VistaJson::error($mensajes['Metodo_no_permitido'],405);
    }
    exit;
}

// ==============================
// Ruteo para entidad Grupos (/grupos y /grupos/{id})
// ==============================
if (preg_match('/\/grupos(\/(\d+))?$/', $uri, $matches)) {
    $mensajes = require __DIR__ . '/../vistas/mensajes.php';
    $controller = new GrupoController();
    $id = $matches[2] ?? null;

    switch ($method) {
        case 'GET':
            $controller->handleGet($id); // Obtener todos los grupos o un grupo específico
            break;
        case 'POST':
            $controller->handlePost(); // Crear un nuevo grupo
            break;
        case 'PUT':
            if ($id) {
                $controller->handlePut($id); // Actualizar un grupo existente   
            } else {
                VistaJson::error($mensajes['Id_requerido'],400);
            }
            break;
        case 'DELETE':
            if ($id) {
                $controller->handleDelete($id); // Eliminar un grupo por ID
            } else {
                VistaJson::error($mensajes['Id_requerido'],400);
            }
            break;
        default:
            VistaJson::error($mensajes['Metodo_no_permitido'],405);
    }
    exit;
}

if (preg_match('/\/archivos\/descargar\/(.+)$/', $uri, $matches) && $method === 'GET') {
    $controller = new ArchivoController();
    $controller->descargar($matches[1]);
    exit;
}
// Ruteo para entidad Archivos (/archivos, /archivos/{id}, /archivos/alumno/{id_alumno}, /archivos/{id}/descargar)
if (preg_match('/\/archivos(\/(\d+))?(\/descargar)?$/', $uri, $matches)) {
    $controller = new ArchivoController();
    $id = $matches[2] ?? null;

    switch ($method) {
        case 'GET':
            if (preg_match('/\/archivos\/alumno\/(\d+)$/', $uri, $alumnoMatch)) {
                $controller->archivosPorAlumno($alumnoMatch[1]); // Archivos por alumno
            } elseif ($id) {
                $controller->handleGet($id); // Obtener info de archivo por ID
            } else {
                $controller->handleGet(); // Listar todos los archivos
            }
            break;
        case 'POST':
            $controller->subir(); // Subir archivo
            break;
        case 'DELETE':
            if ($id) {
                $controller->handleDelete($id); // Eliminar archivo
            } else {
                VistaJson::error('ID de archivo requerido', 400);
            }
            break;
        default:
            VistaJson::error('Método no permitido', 405);
    }
    exit;
}

// Si no coincide ninguna ruta
$mensajes = require __DIR__ . '/../vistas/mensajes.php';
VistaJson::error($mensajes['Ruta_no_encontrada'],404);