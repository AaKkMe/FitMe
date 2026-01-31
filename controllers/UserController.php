<?php
/**
 * User Controller - Member panel: view workouts, register, view attendance
 */
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Workout.php';
require_once __DIR__ . '/../models/Attendance.php';

class UserController {
    private $workoutModel;
    private $attendanceModel;
    private $userId;

    public function __construct() {
        requireRole('user');
        $this->userId = getCurrentUser();
        $this->workoutModel = new Workout();
        $this->attendanceModel = new Attendance();
    }

    public function dashboard() {
        $workouts = $this->workoutModel->getActiveForUser($this->userId);
        $recentAttendance = $this->attendanceModel->getByUser($this->userId, null, null, 10);
        $startOfMonth = date('Y-m-01');
        $endOfMonth = date('Y-m-t');
        $stats = $this->attendanceModel->getStatsByUser($this->userId, $startOfMonth, $endOfMonth);
        include __DIR__ . '/../views/user/dashboard.php';
    }

    public function workouts() {
        $workouts = $this->workoutModel->getActiveForUser($this->userId);
        include __DIR__ . '/../views/user/workouts.php';
    }

    public function registerWorkout() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && validateCSRF($_POST['csrf_token'] ?? '')) {
            $workoutId = (int)($_POST['workout_id'] ?? 0);
            if ($workoutId) {
                $this->workoutModel->registerUser($this->userId, $workoutId);
            }
        }
        redirect('index.php?page=user/workouts');
    }

    public function unregisterWorkout() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && validateCSRF($_POST['csrf_token'] ?? '')) {
            $workoutId = (int)($_POST['workout_id'] ?? 0);
            if ($workoutId) {
                $this->workoutModel->unregisterUser($this->userId, $workoutId);
            }
        }
        redirect('index.php?page=user/workouts');
    }

    public function attendance() {
        $from = $_GET['from'] ?? date('Y-m-01');
        $to = $_GET['to'] ?? date('Y-m-t');
        $records = $this->attendanceModel->getByUser($this->userId, $from, $to);
        include __DIR__ . '/../views/user/attendance.php';
    }
}
