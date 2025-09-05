<?php
require_once 'database.php';

class User {
    private $conn;
    private $table = 'usersDetails';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Check if user exists by username or email
    public function exists($username, $email = null) {
        $sql = "SELECT id FROM {$this->table} WHERE username = :username";
        $params = [':username' => $username];

        if ($email) {
            $sql .= " OR email = :email";
            $params[':email'] = $email;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    // Register new user with SHA-512 hashing
    public function register($username, $email, $password, $profile_picture = null, $age = null) {
        if ($this->exists($username, $email)) {
            return false;
        }

        $hashedPassword = hash("sha512", $password);

        $sql = "INSERT INTO {$this->table} 
                (username, email, password, profile_picture, age) 
                VALUES (:username, :email, :password, :profile_picture, :age)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $hashedPassword,
            ':profile_picture' => $profile_picture,
            ':age' => $age
        ]);
    }

    // User login with SHA-512 verification
    public function login($email, $password) {
        $hashedInput = hash("sha512", $password);
        $sql = "SELECT * FROM {$this->table} WHERE email = :email AND password = :password";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':email' => $email,
            ':password' => $hashedInput
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get all users
    public function getAll() {
        $sql = "SELECT id, username, email, profile_picture, age, created_at 
                FROM {$this->table}";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update user information
    public function update($id, $username, $email, $password = null, $profile_picture = null, $age = null) {
        $params = [
            ':username' => $username,
            ':email' => $email,
            ':id' => $id,
            ':age' => $age
        ];

        $sql = "UPDATE {$this->table} SET username = :username, email = :email, age = :age";

        if ($password !== null) {
            $hashedPassword = hash("sha512", $password);
            $sql .= ", password = :password";
            $params[':password'] = $hashedPassword;
        }

        if ($profile_picture !== null) {
            $sql .= ", profile_picture = :profile_picture";
            $params[':profile_picture'] = $profile_picture;
        }

        $sql .= " WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }

    // Delete user account
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Get single user by ID
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}