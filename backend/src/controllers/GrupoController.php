<?php
// Controlador Grupo: Gestiona las peticiones HTTP para la entidad grupos   

require_once __DIR__ . '/../models/Grupo.php';


class GrupoController
{

    // Maneja GET /grupos y GET /grupos/{id}
    public function handleGet($id = null)
    {
        session_start();
        $mensajes = require __DIR__ . '/../../vistas/mensajes.php';
        error_log(print_r($_SESSION, true));
        if (!isset($_SESSION['usuario_id'])) {
            VistaJson::error($mensajes['no_autenticado'], 401);
            return;
        }

        $mensajes = require __DIR__ . '/../../vistas/mensajes.php';
        header('Content-Type: application/json');
        if ($id) {
            $grupo = Grupo::getById($id);
            if ($grupo) {
                VistaJson::respuesta($grupo);
            } else {
                VistaJson::error($mensajes['grupo_no_encontrado'], 404);
            }
        } else {
            $grupos = Grupo::getAll();
            VistaJson::respuesta($grupos);
        }
    }

    // Maneja POST /grupos
    public function handlePost()
    {
        session_start();
        $mensajes = require __DIR__ . '/../../vistas/mensajes.php';
        if (!isset($_SESSION['usuario_id'])) {
            VistaJson::error($mensajes['no_autenticado'], 401);
            return;
        }
        $data = json_decode(file_get_contents('php://input'), true);
        if (Grupo::create($data)) {
            VistaJson::exito($mensajes['grupo_creada']);
        } else {
            VistaJson::error($mensajes['grupo_no_creada'], 400);
        }
    }

    // Maneja PUT /grupos/{id}
    public function handlePut($id)
    {
        session_start();
        $mensajes = require __DIR__ . '/../../vistas/mensajes.php';
        if (!isset($_SESSION['usuario_id'])) {
            VistaJson::error($mensajes['no_autenticado'], 401);
            return;
        }
        $data = json_decode(file_get_contents('php://input'), true);
        if (Grupo::update($id, $data)) {
            VistaJson::exito($mensajes['grupo_actualizada']);
        } else {
            VistaJson::error($mensajes['grupo_no_actualizada'], 400);
        }
    }

    // Maneja DELETE /grupos/{id}
    public function handleDelete($id)
    {
        session_start();
        $mensajes = require __DIR__ . '/../../vistas/mensajes.php';
        if (!isset($_SESSION['usuario_id'])) {
            VistaJson::error($mensajes['no_autenticado'], 401);
            return;
        }
        if (Grupo::delete($id)) {
            VistaJson::exito($mensajes['grupo_eliminada']);
        } else {
            VistaJson::error($mensajes['grupo_no_eliminada'], 400);
        }
    }
}