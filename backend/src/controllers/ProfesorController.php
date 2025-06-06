<?php
// Controlador Profesor: Gestiona las peticiones HTTP para la entidad profesores

require_once __DIR__ . '/../models/Profesor.php';

class ProfesorController
{
    // Maneja GET /profesores y GET /profesores/{id}
    public function handleGet($id = null)
    {
        session_start();
        $mensajes = require __DIR__ . '/../../vistas/mensajes.php';
        if (!isset($_SESSION['usuario_id'])) {
            VistaJson::error($mensajes['no_autenticado'], 401);
            return;
        }
        
        header('Content-Type: application/json');
        if ($id) {
            $profesor = Profesor::getById($id);
            if ($profesor) {
                VistaJson::respuesta($profesor);
            } else {
                VistaJson::error($mensajes['profesor_no_encontrado'], 404);
            }
        } else {
            $profesores = Profesor::getAll();
            VistaJson::respuesta($profesores);
        }
    }

    // Maneja POST /profesores
    public function handlePost()
    {
        session_start();
        $mensajes = require __DIR__ . '/../../vistas/mensajes.php';
        $data = json_decode(file_get_contents('php://input'), true);
        if (Profesor::create($data)) {
            VistaJson::exito($mensajes['profesor_creado'], [], 201);
        } else {
            VistaJson::error($mensajes['profesor_no_creado'], 400);
        }
    }

    // Maneja PUT /profesores/{id}
    public function handlePut($id)
    {
        session_start();
        $mensajes = require __DIR__ . '/../../vistas/mensajes.php';
        $data = json_decode(file_get_contents('php://input'), true);
        if (Profesor::update($id, $data)) {
            VistaJson::exito($mensajes['profesor_actualizado']);
        } else {
            VistaJson::error($mensajes['profesor_no_actualizado'], 400);
        }
    }

    // Maneja DELETE /profesores/{id}
    public function handleDelete($id)
    {
        session_start();
        $mensajes = require __DIR__ . '/../../vistas/mensajes.php';
        if (Profesor::delete($id)) {
            VistaJson::exito($mensajes['profesor_eliminado']);
        } else {
            VistaJson::error($mensajes['profesor_no_eliminado'], 400);
        }
    }
}