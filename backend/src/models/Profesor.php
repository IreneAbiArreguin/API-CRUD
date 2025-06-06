<?php
// Modelo Profesor: Encapsula la lógica de acceso a datos para la entidad profesores

require_once '/var/www/database/connection.php';

class Profesor
{
    // Obtiene todos los profesores de la base de datos
    public static function getAll()
    {
        $db = Database::connect();
        $stmt = $db->query("SELECT * FROM profesores");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtiene un profesor por su ID
    public static function getById($id)
    {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM profesores WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crea un nuevo profesor
    public static function create($data)
    {
        $db = Database::connect();
        $stmt = $db->prepare("INSERT INTO profesores (id_usuario, nombre, apellido, especialidad) VALUES (?, ?, ?, ?)");
        return $stmt->execute([
            $data['id_usuario'],
            $data['nombre'],
            $data['apellido'],
            $data['especialidad']
        ]);
    }

    // Actualiza un profesor existente
    public static function update($id, $data)
    {
        $db = Database::connect();
        $stmt = $db->prepare("UPDATE profesores SET id_usuario = ?, nombre = ?, apellido = ?, especialidad = ? WHERE id = ?");
        return $stmt->execute([
            $data['id_usuario'],
            $data['nombre'],
            $data['apellido'],
            $data['especialidad'],
            $id
        ]);
    }

    // Elimina un profesor por su ID
    public static function delete($id)
    {
        $db = Database::connect();
        $stmt = $db->prepare("DELETE FROM profesores WHERE id = ?");
        return $stmt->execute([$id]);
    }
}