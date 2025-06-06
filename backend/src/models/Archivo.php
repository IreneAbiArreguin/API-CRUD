<?php
// Modelo Archivo: Gestiona el registro de archivos subidos

require_once '/var/www/database/connection.php';

class Archivo
{
    // Guarda el nombre del archivo y el usuario que lo subió
    public static function registrar($nombre, $usuario_id, $categoria, $id_alumno = null)
    {
        $db = Database::connect();
        $stmt = $db->prepare("INSERT INTO archivos (nombre, usuario_id, id_alumno, Categoria, fecha_subida) VALUES (?, ?, ?, ?, NOW())");
        return $stmt->execute([$nombre, $usuario_id, $id_alumno, $categoria]);
    }

    // Obtiene información de un archivo por su nombre
    public static function getByNombre($nombre)
    {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM archivos WHERE nombre = ?");
        $stmt->execute([$nombre]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

        // Obtiene información de los archivos
    public static function getArchivos()
    {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT id, nombre, fecha_subida, categoria FROM archivos");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Devuelve un array de resultados
    }

    public static function getArchivosById($id)
    {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT id, nombre, fecha_subida, categoria FROM archivos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }



    // Elimina un archivo por su id
    public static function delete($id)
    {
        $db = Database::connect();
        $stmt = $db->prepare("DELETE FROM archivos WHERE id = ?");
        return $stmt->execute([$id]);
    }


    // Obtiene todos los archivos subidos por un usuario
    public static function getAllByUser($usuario_id)
    {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM archivos WHERE usuario_id = ?");
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtiene todos los archivos relacionados con un alumno
    public static function getByAlumno($id_alumno)
    {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM archivos WHERE id_alumno = ?");
        $stmt->execute([$id_alumno]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}