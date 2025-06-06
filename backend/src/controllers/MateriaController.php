<?php
// Controlador Materia: Gestiona las peticiones HTTP para la entidad materias

require_once __DIR__ . '/../models/Materia.php';

class MateriaController
{
    // Maneja GET /materias y GET /materias/{id}
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
            $materia = Materia::getById($id);
            if ($materia) {
                VistaJson::respuesta($materia);
            } else {
                VistaJson::error($mensajes['materia_no_encontrada'], 404);
            }
        } else {
            $materias = Materia::getAll();
            VistaJson::respuesta($materias);
        }
    }

    // Maneja POST /materias
    public function handlePost()
    {
        session_start();
        $mensajes = require __DIR__ . '/../../vistas/mensajes.php';
        if (!isset($_SESSION['usuario_id'])) {
            VistaJson::error($mensajes['no_autenticado'], 401);
            return;
        }
        $data = json_decode(file_get_contents('php://input'), true);
        if (Materia::create($data)) {
            VistaJson::exito($mensajes['materia_creada'], [], 201);
        } else {
            VistaJson::error($mensajes['materia_no_creada'],400);
        }

    }

    // Maneja PUT /materias/{id}
    public function handlePut($id)
    {
        session_start();
        $mensajes = require __DIR__ . '/../../vistas/mensajes.php';
        if (!isset($_SESSION['usuario_id'])) {
            VistaJson::error($mensajes['no_autenticado'], 401);
            return;
        }
        $data = json_decode(file_get_contents('php://input'), true);
        if (Materia::update($id, $data)) {
            VistaJson::exito($mensajes['materia_actualizada']);
        } else {
            VistaJson::error($mensajes['materia_no_actualizada'], 400);
        }
    }

    // Maneja DELETE /materias/{id}
    public function handleDelete($id)
    {
        session_start();
        $mensajes = require __DIR__ . '/../../vistas/mensajes.php';
        if (!isset($_SESSION['usuario_id'])) {
            VistaJson::error($mensajes['no_autenticado'], 401);
            return;
        }
        
        if (Materia::delete($id)) {
            VistaJson::exito($mensajes['materia_eliminada']);
        } else {
            VistaJson::error($mensajes['materia_no_eliminada'], 400);
        }
    }
}