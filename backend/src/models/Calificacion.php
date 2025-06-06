<?php
// Modelo Calificacion: Encapsula la lógica de acceso a datos para la entidad calificaciones

require_once '/var/www/database/connection.php';

class Calificacion
{
    // Obtiene todas las calificaciones de la base de datos
    public static function getAll()
    {
        $db = Database::connect();
        $stmt = $db->query("SELECT * FROM calificaciones");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtiene una calificación por su ID
    public static function getById($id)
    {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM calificaciones WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crea una nueva calificación
    public static function create($data)
    {
        $db = Database::connect();
        $stmt = $db->prepare("INSERT INTO calificaciones (id_alumno, id_materia, id_profesor, calificacion, fecha) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['id_alumno'],
            $data['id_materia'],
            $data['id_profesor'],
            $data['calificacion'],
            $data['fecha']
        ]);
    }

    // Actualiza una calificación existente
    public static function update($id, $data)
    {
        $db = Database::connect();
        $stmt = $db->prepare("UPDATE calificaciones SET id_alumno = ?, id_materia = ?, id_profesor = ?, calificacion = ?, fecha = ? WHERE id = ?");
        return $stmt->execute([
            $data['id_alumno'],
            $data['id_materia'],
            $data['id_profesor'],
            $data['calificacion'],
            $data['fecha'],
            $id
        ]);
    }

    // Elimina una calificación por su ID
    public static function delete($id)
    {
        $db = Database::connect();
        $stmt = $db->prepare("DELETE FROM calificaciones WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Obtiene un reporte de calificaciones con información de alumno, materia y profesor
    public static function getReporte()
    {
        $db = Database::connect();
        $sql = "SELECT 
                    c.id,
                    a.nombre AS nombre_alumno,
                    m.nombre AS nombre_materia,
                    p.nombres AS nombre_profesor,
                    c.calificacion,
                    c.fecha
                FROM calificaciones c
                INNER JOIN alumnos a ON c.id_alumno = a.id
                INNER JOIN materias m ON c.id_materia = m.id
                INNER JOIN usuarios p ON c.id_profesor = p.id";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}