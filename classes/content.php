<?php
require_once 'database.php';

class Content {
    private $conn;
    private $table = 'movies';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create new movie content
    public function create($user_id, $title, $description, $poster = null, $year = null, $director = null, $genre = null) {
        // Convert empty user_id to NULL
        $user_id = empty($user_id) ? null : $user_id;

        $sql = "INSERT INTO {$this->table} 
                (user_id, title, description, poster, year, director, genre) 
                VALUES (:user_id, :title, :description, :poster, :year, :director, :genre)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':user_id' => $user_id,
            ':title' => $title,
            ':description' => $description,
            ':poster' => $poster,
            ':year' => $year,
            ':director' => $director,
            ':genre' => $genre
        ]);
    }

    // Get single movie by ID
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get all movies sorted by creation date
    public function readAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update existing movie
    public function update($id, $title, $description, $poster = null, $year = null, $director = null, $genre = null) {
        $params = [
            ':title' => $title,
            ':description' => $description,
            ':id' => $id,
            ':year' => $year,
            ':director' => $director,
            ':genre' => $genre
        ];

        $sql = "UPDATE {$this->table} SET 
                title = :title, 
                description = :description,
                year = :year,
                director = :director,
                genre = :genre";

        if ($poster !== null) {
            $sql .= ", poster = :poster";
            $params[':poster'] = $poster;
        }

        $sql .= " WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }

    // Delete movie by ID
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}