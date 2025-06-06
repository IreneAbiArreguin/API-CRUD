<?php
// Modelo Alumno: Encapsula la lógica de acceso a datos para la entidad grupos

require_once '/var/www/database/connection.php';

class Grupo
{
    // Obtiene todos los grupos de la base de datos
    public static function getAll()
    {
        $db = Database::connect();
        $stmt = $db->query("SELECT * FROM grupos");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtiene un grupo por su ID
    public static function getById($id)
    {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM grupos WHERE id_grupo = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crea un nuevo grupo
    public static function create($data)
    {
        $db = Database::connect();
        $stmt = $db->prepare("INSERT INTO grupos (nombre) VALUES (?, ?)");
        return $stmt->execute([
            $data['nombre']
        ]);
    }

    // Actualiza un grupo existente
    public static function update($id, $data)
    {
        $db = Database::connect();
        $stmt = $db->prepare("UPDATE grupos SET nombre = ? WHERE id_grupo = ?");
        return $stmt->execute([
            $data['nombre'],
            $id
        ]);
    }

    // Elimina un grupo por su ID
    public static function delete($id)
    {
        $db = Database::connect();
        $stmt = $db->prepare("DELETE FROM grupos WHERE id_grupo = ?");
        return $stmt->execute([$id]);
    }
}