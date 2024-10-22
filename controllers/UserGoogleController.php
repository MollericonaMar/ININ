<?php
require_once 'config/Database.php';

class GoogleAuth {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function findOrCreateUser($googleUserData) {
        $googleId = $googleUserData['id'];
        $email = $googleUserData['email'];
        $nombre = $googleUserData['given_name'];
        $apellidos = $googleUserData['family_name'];
        $imagen = $googleUserData['picture'];

        // Paso 1: Verificar si el usuario ya existe en la tabla `usuarios`
        $query = "SELECT * FROM usuarios_google WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $usuarioId = $user['id'];

            // Paso 2: Verificar si ya está registrado en `usuarios_google`
            $googleQuery = "SELECT * FROM usuarios_google WHERE google_id = :google_id";
            $stmt = $this->db->prepare($googleQuery);
            $stmt->bindParam(':google_id', $googleId);
            $stmt->execute();
            $googleUser = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$googleUser) {
                // Registrar al usuario en `usuarios_google`
                $insertGoogleUser = "INSERT INTO usuarios_google (usuario_id, google_id, email, nombre, apellidos, imagen)
                                     VALUES (:usuario_id, :google_id, :email, :nombre, :apellidos, :imagen)";
                $stmt = $this->db->prepare($insertGoogleUser);
                $stmt->bindParam(':usuario_id', $usuarioId);
                $stmt->bindParam(':google_id', $googleId);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':nombre', $nombre);
                $stmt->bindParam(':apellidos', $apellidos);
                $stmt->bindParam(':imagen', $imagen);
                $stmt->execute();
            }

            return $user;  // Usuario ya existente y autenticado
        } else {
            // Paso 3: Si no existe, crear un nuevo usuario en `usuarios`
            $insertUser = "INSERT INTO usuarios (nombre, apellidos, email, password, rol, imagen)
                           VALUES (:nombre, :apellidos, :email, :password, 'usuario', :imagen)";
            $stmt = $this->db->prepare($insertUser);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellidos', $apellidos);
            $stmt->bindParam(':email', $email);
            $stmt->bindValue(':password', null);  // Password vacío porque es login con Google
            $stmt->bindParam(':imagen', $imagen);
            $stmt->execute();

            $usuarioId = $this->db->lastInsertId();

            // Paso 4: Registrar en `usuarios_google`
            $insertGoogleUser = "INSERT INTO usuarios_google (usuario_id, google_id, email, nombre, apellidos, imagen)
                                 VALUES (:usuario_id, :google_id, :email, :nombre, :apellidos, :imagen)";
            $stmt = $this->db->prepare($insertGoogleUser);
            $stmt->bindParam(':usuario_id', $usuarioId);
            $stmt->bindParam(':google_id', $googleId);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellidos', $apellidos);
            $stmt->bindParam(':imagen', $imagen);
            $stmt->execute();

            return ['id' => $usuarioId, 'nombre' => $nombre, 'email' => $email];  // Nuevo usuario registrado
        }
    }
}
