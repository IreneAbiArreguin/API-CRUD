<?php
// Controlador Usuario: Gestiona las peticiones HTTP para la entidad usuarios

require_once __DIR__ . '/../models/Usuario.php';

class UsuarioController
{
    // Maneja GET /usuarios y GET /usuarios/{id}
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
            $usuario = Usuario::getById($id);
            if ($usuario) {
                VistaJson::respuesta($usuario);
            } else {
                VistaJson::error($mensajes['usuario_no_encontrado'], 404);
            }
        } else {
            $usuarios = Usuario::getAll();
            VistaJson::respuesta($usuarios);
        }
    }

    // Maneja POST /usuarios
    public function handlePost()
    {

        $mensajes = require __DIR__ . '/../../vistas/mensajes.php';
        $data = json_decode(file_get_contents('php://input'), true);
        if (Usuario::create($data)) {
            VistaJson::exito($mensajes['usuario_creado'], [], 201);
        } else {
            VistaJson::error($mensajes['usuario_no_creado'], 400);
        }
    }

    // Maneja PUT /usuarios/{id}
    public function handlePut($id)
    {
        session_start();
        $mensajes = require __DIR__ . '/../../vistas/mensajes.php';
        if (!isset($_SESSION['usuario_id'])) {
            VistaJson::error($mensajes['no_autenticado'], 401);
            return;
        }
        $data = json_decode(file_get_contents('php://input'), true);
        if (Usuario::update($id, $data)) {
            VistaJson::exito($mensajes['usuario_actualizado']);
        } else {
            VistaJson::error($mensajes['usuario_no_actualizado'], 400);
        }
    }

    // Maneja DELETE /usuarios/{id}
    public function handleDelete($id)
    {
        session_start();
        $mensajes = require __DIR__ . '/../../vistas/mensajes.php';
        if (!isset($_SESSION['usuario_id'])) {
            VistaJson::error($mensajes['no_autenticado'], 401);
            return;
        }
        if (Usuario::delete($id)) {
            VistaJson::exito($mensajes['usuario_eliminado']);
        } else {
            VistaJson::error($mensajes['usuario_no_eliminado'], 400);
        }
    }
}