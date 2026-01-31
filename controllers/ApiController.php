<?php
/**
 * API Controller - Ajax endpoints (email validation, autocomplete)
 */
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Workout.php';
require_once __DIR__ . '/../models/Category.php';

class ApiController {

    public function checkEmail() {
        $email = trim($_GET['email'] ?? '');
        $excludeId = (int)($_GET['exclude_id'] ?? 0);
        $exists = false;
        if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $userModel = new User();
            $exists = $userModel->emailExists($email, $excludeId ?: null);
        }
        jsonResponse(['exists' => $exists]);
    }

    public function searchUsers() {
        requireLogin();
        requireRole(['admin', 'trainer']);
        $q = trim($_GET['q'] ?? '');
        $limit = min(15, (int)($_GET['limit'] ?? 10));
        $results = [];
        if (strlen($q) >= 2) {
            $userModel = new User();
            $users = $userModel->getAll(null, $q, $limit, 0);
            foreach ($users as $u) {
                $results[] = ['id' => $u['id'], 'name' => $u['name'], 'email' => $u['email']];
            }
        }
        jsonResponse($results);
    }

    public function searchWorkouts() {
        requireLogin();
        $q = trim($_GET['q'] ?? '');
        $limit = min(15, (int)($_GET['limit'] ?? 10));
        $results = [];
        if (strlen($q) >= 2) {
            $workoutModel = new Workout();
            $workouts = $workoutModel->getAll(['search' => $q], $limit, 0);
            foreach ($workouts as $w) {
                $results[] = [
                    'id' => $w['id'],
                    'name' => $w['name'],
                    'schedule' => $w['schedule_day'] . ' ' . substr($w['schedule_time'], 0, 5)
                ];
            }
        }
        jsonResponse($results);
    }
}
