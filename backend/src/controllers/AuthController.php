<?php
// Controlador Auth: Maneja la autenticación de usuarios

require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../../vistas/VistaJson.php';

class AuthController
{
    // Maneja POST /login
    public function login()
    {
        session_start();
        $mensajes = require __DIR__ . '/../../vistas/mensajes.php';
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['nombre_usuario']) && isset($data['contrasena'])) {
            $usuario = Usuario::autenticar($data['nombre_usuario'], $data['contrasena']);
            if ($usuario) {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_rol'] = $usuario['rol'];
                // Guardar datos adicionales según el rol
                $_SESSION['datos_usuario'] = Usuario::getById($usuario['id']);
                VistaJson::exito($mensajes['autenticacion_exitosa'], [
                    'usuario' => $usuario['nombre_usuario'],
                    'rol' => $usuario['rol'],
                    'datos_usuario' => $_SESSION['datos_usuario']
                ]);
            } else {
                VistaJson::error($mensajes['credenciales_incorrectas'], 401);
            }
        } else {
            VistaJson::error($mensajes['no_autenticado'], 400);
        }
    }

    // Maneja POST /logout
    public function logout()
    {
        session_start();
        $mensajes = require __DIR__ . '/../../vistas/mensajes.php';
        session_destroy();
        VistaJson::exito($mensajes['sesion_cerrada']);
    }
}
?>
