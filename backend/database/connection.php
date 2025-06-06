<?php
class Database {
    private static $host = 'db'; // nombre del servicio en docker-compose o 'localhost' si es local
    private static $db_name = 'control_academico';
    private static $username = 'usuario';
    private static $password = 'usuario123';
    public static $conn;

    public static function connect() {
        if (!self::$conn) {
            try {
                self::$conn = new PDO(
                    "mysql:host=" . self::$host . ";dbname=" . self::$db_name . ";charset=utf8",
                    self::$username,
                    self::$password
                );
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Error de conexión: " . $e->getMessage());
            }
        }
        return self::$conn;
    }
}