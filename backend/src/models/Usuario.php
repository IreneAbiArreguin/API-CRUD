<?php
// Modelo Usuario: Encapsula la lógica de acceso a datos para la entidad usuarios

require_once '/var/www/database/connection.php';

class Usuario
{

        // Autentica un usuario por nombre_usuario y contraseña
    public static function autenticar($nombre_usuario, $contrasena)
    {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE nombre_usuario = ?");
        $stmt->execute([$nombre_usuario]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
      //  print_r($usuario);
        $hash = password_hash($contrasena, PASSWORD_DEFAULT);
      //  echo $hash;

        if ($usuario && password_verify($contrasena, $usuario['password_hash'])) {
            return $usuario;
        }
        return false;
    }

    // Obtiene todos los usuarios de la base de datos
    public static function getAll()
    {
        $db = Database::connect();
        $stmt = $db->query("SELECT * FROM usuarios");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtiene un usuario por su ID
    public static function getById($id)
    {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crea un nuevo usuario
    public static function create($data)
    {
        $db = Database::connect();
        $hash = password_hash($data['contrasena'], PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO usuarios (nombre_usuario, password_hash, rol, email, nombres, apellidos, id_especialidad) VALUES (?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['nombre_usuario'],
            $hash,
            $data['rol'],
            $data['correo'],
            $data['nombres'],
            $data['apellidos'],
            $data['id_especialidad']
        ]);
    }

    // Actualiza un usuario existente
    public static function update($id, $data)
    {
        $db = Database::connect();
        $hash = password_hash($data['contrasena'], PASSWORD_DEFAULT);
        $stmt = $db->prepare("UPDATE usuarios SET nombre_usuario = ?, password_hash = ?, rol = ?, email = ?, nombres = ?, apellidos = ?, id_especialidad = ? WHERE id = ?");
        return $stmt->execute([
            $data['nombre_usuario'],
            $hash,
            $data['rol'],
            $data['correo'],
            $data['nombres'],
            $data['apellidos'],
            $data['id_especialidad'],
            $id
        ]);
    }

    // Elimina un usuario por su ID
    public static function delete($id)
    {
        $db = Database::connect();
        $stmt = $db->prepare("DELETE FROM usuarios WHERE id = ?");
        return $stmt->execute([$id]);
    }
}