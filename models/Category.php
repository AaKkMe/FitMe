<?php
/**
 * Workout Category Model
 */
require_once __DIR__ . '/../config/database.php';

class Category {
    private $pdo;

    public function __construct() {
        $this->pdo = getDBConnection();
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM workout_categories ORDER BY name");
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM workout_categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function searchByName($term, $limit = 10) {
        $stmt = $this->pdo->prepare("SELECT id, name FROM workout_categories WHERE name LIKE ? ORDER BY name LIMIT ?");
        $stmt->execute(["%$term%", $limit]);
        return $stmt->fetchAll();
    }
}
