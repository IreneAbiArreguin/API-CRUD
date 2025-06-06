<?php
// Controlador Archivo: Gestiona las peticiones HTTP para la entidad archivos

require_once __DIR__ . '/../models/Archivo.php';

class ArchivoController
{
    // Maneja POST /archivos/subir
    public function subir()
    {
        session_start();
        $mensajes = require __DIR__ . '/../../vistas/mensajes.php';
        if (!isset($_SESSION['usuario_id'])) {
            VistaJson::error($mensajes['no_autenticado'], 401);
            return;
        }

        if (!isset($_FILES['archivo'])) {
            VistaJson::error($mensajes['no_archivo_recibido'], 400);
            return;
        }

        $archivo = $_FILES['archivo'];
        $nombre = basename($archivo['name']);
        //$ruta_destino = __DIR__ . '/../../archivos/' . $nombre;

        // Validar tipo de archivo permitido (solo imágenes y PDF)
        $tipos_permitidos = ['image/jpeg', 'image/png', 'application/pdf'];
        if (!in_array($archivo['type'], $tipos_permitidos)) {
            VistaJson::error($mensajes['tipo_archivo_no_permitido'], 400);
            return;
        }

        // Validar tamaño máximo (5MB)
        $tamano_maximo = 5 * 1024 * 1024; // 5MB
        if ($archivo['size'] > $tamano_maximo) {
            VistaJson::error($mensajes['archivo_excede_tamano_maximo'], 400);
            return;
        }

        $categoria = 0;
        if (in_array($archivo['type'], ['image/jpeg', 'image/png'])) {
            $categoria = 1;
        } elseif ($archivo['type'] === 'application/pdf') {
            $categoria = 2;
        }

        // Generar nombre único para evitar sobrescribir archivos
        $extension = pathinfo($nombre, PATHINFO_EXTENSION); // jpg
        $nombre_base = pathinfo($nombre, PATHINFO_FILENAME); // foto
        $nombre_unico = $nombre_base . uniqid() . '.' . $extension;

        $ruta_destino = __DIR__ . '/../../archivos/' . $nombre_unico;

        if (move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
            $id_alumno = isset($_POST['id_alumno']) ? $_POST['id_alumno'] : null;
            Archivo::registrar($nombre_unico, $_SESSION['usuario_id'], $categoria, $id_alumno);
            VistaJson::exito($mensajes['archivo_subido'], ['nombre' => $nombre_unico]);
        } else {
            VistaJson::error($mensajes['archivo_no_subido'], 500);
        }

    }

    // Maneja GET /archivos/descargar/{nombre}
    public function descargar($nombre)
    {
        session_start();
        $mensajes = require __DIR__ . '/../../vistas/mensajes.php';
        if (!isset($_SESSION['usuario_id'])) {
            VistaJson::error($mensajes['no_autenticado'], 401);
            return;
        }

        $ruta = __DIR__ . '/../../archivos/' . $nombre;

        if (!file_exists($ruta)) {
            VistaJson::error($mensajes['archivo_no_encontrado'], 404);
            return;
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $nombre . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($ruta));
        readfile($ruta);
        exit;
    }

    // Maneja GET /archivos/{nombre}
    public function handleGet($id=null)
    {
        session_start();
        $mensajes = require __DIR__ . '/../../vistas/mensajes.php';
        if (!isset($_SESSION['usuario_id'])) {
            VistaJson::error($mensajes['no_autenticado'], 401);
            return;
        }

        if ($id) {
            $archivo = Archivo::getArchivosById($id);
        } else {
            $archivo = Archivo::getArchivos();
        }

        if ($archivo) {
            VistaJson::respuesta($archivo);
        } else {
            VistaJson::error($mensajes['archivo_no_encontrado'], 404);
        }
    }

    // Maneja DELETE /archivos/{nombre}
    public function handleDelete($id)
    {
        session_start();
        $mensajes = require __DIR__ . '/../../vistas/mensajes.php';
        if (!isset($_SESSION['usuario_id'])) {
            VistaJson::error($mensajes['no_autenticado'], 401);
            return;
        }

        if (Archivo::delete($id)) {
            VistaJson::exito($mensajes['archivo_eliminado'], ['id' => $id]);
        } else {
            VistaJson::error($mensajes['archivo_no_eliminado'], 400);
        }
    }

    // Maneja GET /archivos/all/{id}
    // Se usa para mostrar los archivos subidos por el usuario
    public function handleGetAll()
    {
        session_start();
        $mensajes = require __DIR__ . '/../../vistas/mensajes.php';
        if (!isset($_SESSION['usuario_id'])) {
            VistaJson::error($mensajes['no_autenticado'], 401);
            return;
        }

        $archivos = Archivo::getAllByUser($_SESSION['usuario_id']);
        VistaJson::respuesta($archivos);
    }

        // Obtiene todos los archivos relacionados con un alumno
    public function archivosPorAlumno($id_alumno)
    {
        session_start();
        $mensajes = require __DIR__ . '/../../vistas/mensajes.php';
        if (!isset($_SESSION['usuario_id'])) {
            VistaJson::error($mensajes['no_autenticado'], 401);
            return;
        }

        $archivos = Archivo::getByAlumno($id_alumno);
        VistaJson::respuesta($archivos);
    }
}