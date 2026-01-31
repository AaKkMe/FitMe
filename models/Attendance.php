<?php
/**
 * Attendance Model - Log and retrieve attendance
 */
require_once __DIR__ . '/../config/database.php';

class Attendance {
    private $pdo;

    public function __construct() {
        $this->pdo = getDBConnection();
    }

    public function log($userId, $workoutId, $date, $time, $notes = null, $loggedBy = null) {
        $stmt = $this->pdo->prepare("
            INSERT INTO attendance (user_id, workout_id, check_in_date, check_in_time, notes, logged_by)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$userId, $workoutId ?: null, $date, $time, $notes, $loggedBy]);
    }

    public function getByUser($userId, $fromDate = null, $toDate = null, $limit = 100) {
        $sql = "SELECT a.*, w.name as workout_name
                FROM attendance a
                LEFT JOIN workouts w ON a.workout_id = w.id
                WHERE a.user_id = ?";
        $params = [$userId];
        if ($fromDate) {
            $sql .= " AND a.check_in_date >= ?";
            $params[] = $fromDate;
        }
        if ($toDate) {
            $sql .= " AND a.check_in_date <= ?";
            $params[] = $toDate;
        }
        $sql .= " ORDER BY a.check_in_date DESC, a.check_in_time DESC LIMIT ?";
        $params[] = $limit;
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getByDate($date) {
        $stmt = $this->pdo->prepare("
            SELECT a.*, u.name as user_name, u.email, w.name as workout_name
            FROM attendance a
            JOIN users u ON a.user_id = u.id
            LEFT JOIN workouts w ON a.workout_id = w.id
            WHERE a.check_in_date = ?
            ORDER BY a.check_in_time DESC
        ");
        $stmt->execute([$date]);
        return $stmt->fetchAll();
    }

    public function getStatsByUser($userId, $fromDate, $toDate) {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) as total_visits
            FROM attendance
            WHERE user_id = ? AND check_in_date BETWEEN ? AND ?
        ");
        $stmt->execute([$userId, $fromDate, $toDate]);
        return $stmt->fetch();
    }

    public function alreadyLogged($userId, $date) {
        $stmt = $this->pdo->prepare("SELECT id FROM attendance WHERE user_id = ? AND check_in_date = ?");
        $stmt->execute([$userId, $date]);
        return $stmt->fetch() !== false;
    }

    public function getRecentActivity($limit = 12) {
        $stmt = $this->pdo->prepare("
            SELECT a.*, u.name as user_name, w.name as workout_name
            FROM attendance a
            JOIN users u ON a.user_id = u.id
            LEFT JOIN workouts w ON a.workout_id = w.id
            ORDER BY a.check_in_date DESC, a.check_in_time DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    public function getTodayByHour() {
        $stmt = $this->pdo->prepare("
            SELECT HOUR(check_in_time) as hour, COUNT(*) as cnt
            FROM attendance
            WHERE check_in_date = CURDATE()
            GROUP BY HOUR(check_in_time)
            ORDER BY hour
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
