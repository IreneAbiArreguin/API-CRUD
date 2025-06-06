<?php
// Modelo Materia: Encapsula la lógica de acceso a datos para la entidad materias

require_once '/var/www/database/connection.php';

class Materia
{
    // Obtiene todas las materias de la base de datos
    public static function getAll()
    {
        $db = Database::connect();
        $stmt = $db->query("SELECT * FROM materias");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtiene una materia por su ID
    public static function getById($id)
    {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM materias WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crea una nueva materia
    public static function create($data)
    {
        $db = Database::connect();
        $stmt = $db->prepare("INSERT INTO materias (nombre, descripcion) VALUES (?, ?)");
        return $stmt->execute([
            $data['nombre'],
            $data['descripcion']
        ]);
    }

    // Actualiza una materia existente
    public static function update($id, $data)
    {
        $db = Database::connect();
        $stmt = $db->prepare("UPDATE materias SET nombre = ?, descripcion = ? WHERE id = ?");
        return $stmt->execute([
            $data['nombre'],
            $data['descripcion'],
            $id
        ]);
    }

    // Elimina una materia por su ID
    public static function delete($id)
    {
        $db = Database::connect();
        $stmt = $db->prepare("DELETE FROM materias WHERE id = ?");
        return $stmt->execute([$id]);
    }
}