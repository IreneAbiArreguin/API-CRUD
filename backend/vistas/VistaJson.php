<?php

// VistaJson: Centraliza las respuestas JSON y los mensajes personalizados

class VistaJson
{
    public static function respuesta($datos, $codigo = 200)
    {
        http_response_code($codigo);
        header('Content-Type: application/json');
        echo json_encode($datos);
    }

    public static function error($mensaje, $codigo = 400)
    {
        self::respuesta(['error' => $mensaje], $codigo);
    }

    public static function exito($mensaje, $datos = [], $codigo = 200)
    {
        $respuesta = ['mensaje' => $mensaje];
        if (!empty($datos)) {
            $respuesta['datos'] = $datos;
        }
        self::respuesta($respuesta, $codigo);
    }
}