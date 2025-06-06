<?php
// Modelo Alumno: Encapsula la lógica de acceso a datos para la entidad alumnos

require_once '/var/www/database/connection.php';

class Alumno
{
    // Obtiene todos los alumnos de la base de datos
    public static function getAll()
    {
        $db = Database::connect();
        $stmt = $db->query("SELECT * FROM alumnos");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtiene un alumno por su ID
    public static function getById($id)
    {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM alumnos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crea un nuevo alumno
    public static function create($data)
    {
        $db = Database::connect();
        $stmt = $db->prepare("INSERT INTO alumnos (nombre, apellido, email, telefono, fecha_nacimiento, id_grupo) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['nombre'],
            $data['apellido'],
            $data['email'],
            $data['telefono'],
            $data['fecha_nacimiento'],
            $data['id_grupo']
        ]);
    }

    // Actualiza un alumno existente
    public static function update($id, $data)
    {
        $db = Database::connect();
        $stmt = $db->prepare("UPDATE alumnos SET nombre = ?, apellido = ?, email = ?, telefono = ?, fecha_nacimiento = ?, id_grupo = ? WHERE id = ?");
        return $stmt->execute([
            $data['nombre'],
            $data['apellido'],
            $data['email'],
            $data['telefono'],
            $data['fecha_nacimiento'],
            $data['id_grupo'],  
            $id
        ]);
    }

    // Elimina un alumno por su ID
    public static function delete($id)
    {
        $db = Database::connect();
        $stmt = $db->prepare("DELETE FROM alumnos WHERE id = ?");
        return $stmt->execute([$id]);
    }


    public static function getReporte()
    {
        $db = Database::connect();
        $sql = "SELECT 
                    a.id,
                    a.nombre AS nombre_alumno,
                    a.apellido AS apellido_alumno,
                    a.fecha_nacimiento as fecha_nacimiento_alumno,
                    a.email AS email_alumno,
                    a.telefono AS telefono_alumno,
                    g.nombre AS nombre_grupo
                FROM alumnos a
                INNER JOIN grupos g ON g.id_grupo = a.id_grupo";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}