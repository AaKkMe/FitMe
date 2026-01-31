<?php
/**
 * Workout Model - CRUD for workouts
 */
require_once __DIR__ . '/../config/database.php';

class Workout {
    private $pdo;

    public function __construct() {
        $this->pdo = getDBConnection();
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO workouts (name, description, category_id, trainer_id, schedule_day, schedule_time, duration_minutes, max_participants, is_active)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['name'],
            $data['description'] ?? '',
            $data['category_id'] ?? null,
            $data['trainer_id'] ?? null,
            $data['schedule_day'],
            $data['schedule_time'],
            $data['duration_minutes'] ?? 60,
            $data['max_participants'] ?? 20,
            $data['is_active'] ?? 1
        ]);
        return $this->pdo->lastInsertId();
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("
            SELECT w.*, u.name as trainer_name, wc.name as category_name
            FROM workouts w
            LEFT JOIN users u ON w.trainer_id = u.id
            LEFT JOIN workout_categories wc ON w.category_id = wc.id
            WHERE w.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getAll($filters = [], $limit = 100, $offset = 0) {
        $sql = "SELECT w.*, u.name as trainer_name, wc.name as category_name
                FROM workouts w
                LEFT JOIN users u ON w.trainer_id = u.id
                LEFT JOIN workout_categories wc ON w.category_id = wc.id
                WHERE 1=1";
        $params = [];
        
        if (!empty($filters['category_id'])) {
            $sql .= " AND w.category_id = ?";
            $params[] = $filters['category_id'];
        }
        if (!empty($filters['trainer_id'])) {
            $sql .= " AND w.trainer_id = ?";
            $params[] = $filters['trainer_id'];
        }
        if (!empty($filters['schedule_day'])) {
            $sql .= " AND w.schedule_day = ?";
            $params[] = $filters['schedule_day'];
        }
        if (isset($filters['is_active'])) {
            $sql .= " AND w.is_active = ?";
            $params[] = $filters['is_active'];
        }
        if (!empty($filters['search'])) {
            $sql .= " AND (w.name LIKE ? OR w.description LIKE ?)";
            $params[] = "%{$filters['search']}%";
            $params[] = "%{$filters['search']}%";
        }
        $sql .= " ORDER BY w.schedule_day, w.schedule_time LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getActiveForUser($userId) {
        $stmt = $this->pdo->prepare("
            SELECT w.*, u.name as trainer_name, wc.name as category_name,
                   (SELECT COUNT(*) FROM workout_registrations wr WHERE wr.workout_id = w.id) as registered_count
            FROM workouts w
            LEFT JOIN users u ON w.trainer_id = u.id
            LEFT JOIN workout_categories wc ON w.category_id = wc.id
            WHERE w.is_active = 1
            ORDER BY w.schedule_day, w.schedule_time
        ");
        $stmt->execute();
        $workouts = $stmt->fetchAll();
        foreach ($workouts as &$w) {
            $w['is_registered'] = $this->isUserRegistered($userId, $w['id']);
        }
        return $workouts;
    }

    public function getByTrainer($trainerId) {
        $stmt = $this->pdo->prepare("
            SELECT w.*, wc.name as category_name,
                   (SELECT COUNT(*) FROM workout_registrations wr WHERE wr.workout_id = w.id) as registered_count
            FROM workouts w
            LEFT JOIN workout_categories wc ON w.category_id = wc.id
            WHERE w.trainer_id = ?
            ORDER BY w.schedule_day, w.schedule_time
        ");
        $stmt->execute([$trainerId]);
        return $stmt->fetchAll();
    }

    public function update($id, $data) {
        $allowed = ['name', 'description', 'category_id', 'trainer_id', 'schedule_day', 'schedule_time', 'duration_minutes', 'max_participants', 'is_active'];
        $fields = [];
        $params = [];
        foreach ($allowed as $f) {
            if (array_key_exists($f, $data)) {
                $fields[] = "$f = ?";
                $params[] = $data[$f];
            }
        }
        if (empty($fields)) return false;
        $params[] = $id;
        $stmt = $this->pdo->prepare("UPDATE workouts SET " . implode(', ', $fields) . " WHERE id = ?");
        return $stmt->execute($params);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM workouts WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function registerUser($userId, $workoutId) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO workout_registrations (user_id, workout_id) VALUES (?, ?)");
            $stmt->execute([$userId, $workoutId]);
            return true;
        } catch (PDOException $e) {
            return false; // Already registered
        }
    }

    public function unregisterUser($userId, $workoutId) {
        $stmt = $this->pdo->prepare("DELETE FROM workout_registrations WHERE user_id = ? AND workout_id = ?");
        return $stmt->execute([$userId, $workoutId]);
    }

    public function isUserRegistered($userId, $workoutId) {
        $stmt = $this->pdo->prepare("SELECT id FROM workout_registrations WHERE user_id = ? AND workout_id = ?");
        $stmt->execute([$userId, $workoutId]);
        return $stmt->fetch() !== false;
    }

    public function getRegistrations($workoutId) {
        $stmt = $this->pdo->prepare("
            SELECT u.id, u.name, u.email, wr.registered_at
            FROM workout_registrations wr
            JOIN users u ON wr.user_id = u.id
            WHERE wr.workout_id = ?
            ORDER BY wr.registered_at
        ");
        $stmt->execute([$workoutId]);
        return $stmt->fetchAll();
    }
}
