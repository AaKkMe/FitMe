<?php
/**
 * Trainer Controller - Manage workouts, view registrations, log attendance
 */
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Workout.php';
require_once __DIR__ . '/../models/Attendance.php';
require_once __DIR__ . '/../models/Category.php';

class TrainerController {
    private $workoutModel;
    private $attendanceModel;
    private $categoryModel;
    private $userId;

    public function __construct() {
        requireRole(['admin', 'trainer']);
        $this->userId = getCurrentUser();
        $this->workoutModel = new Workout();
        $this->attendanceModel = new Attendance();
        $this->categoryModel = new Category();
    }

    public function dashboard() {
        $trainerId = getCurrentUserRole() === 'admin' ? null : $this->userId;
        $workouts = $trainerId ? $this->workoutModel->getByTrainer($trainerId) : $this->workoutModel->getAll(['is_active' => 1]);
        include __DIR__ . '/../views/trainer/dashboard.php';
    }

    public function myWorkouts() {
        $workouts = $this->workoutModel->getByTrainer($this->userId);
        include __DIR__ . '/../views/trainer/workouts.php';
    }

    public function workoutAdd() {
        $categories = $this->categoryModel->getAll();
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && validateCSRF($_POST['csrf_token'] ?? '')) {
            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'category_id' => $_POST['category_id'] ?: null,
                'trainer_id' => $this->userId,
                'schedule_day' => $_POST['schedule_day'] ?? '',
                'schedule_time' => $_POST['schedule_time'] ?? '09:00',
                'duration_minutes' => (int)($_POST['duration_minutes'] ?? 60),
                'max_participants' => (int)($_POST['max_participants'] ?? 20),
                'is_active' => 1
            ];
            if (empty($data['name']) || empty($data['schedule_day'])) {
                $error = 'Name and schedule day are required.';
            } else {
                $this->workoutModel->create($data);
                redirect('index.php?page=trainer/workouts');
            }
        }
        include __DIR__ . '/../views/trainer/workout_form.php';
    }

    public function workoutEdit() {
        $id = (int)($_GET['id'] ?? 0);
        $workout = $this->workoutModel->findById($id);
        if (!$workout || ($workout['trainer_id'] != $this->userId && getCurrentUserRole() !== 'admin')) {
            redirect('index.php?page=trainer/workouts');
        }
        $categories = $this->categoryModel->getAll();
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && validateCSRF($_POST['csrf_token'] ?? '')) {
            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'category_id' => $_POST['category_id'] ?: null,
                'schedule_day' => $_POST['schedule_day'] ?? '',
                'schedule_time' => $_POST['schedule_time'] ?? '09:00',
                'duration_minutes' => (int)($_POST['duration_minutes'] ?? 60),
                'max_participants' => (int)($_POST['max_participants'] ?? 20),
            ];
            $this->workoutModel->update($id, $data);
            redirect('index.php?page=trainer/workouts');
        }
        include __DIR__ . '/../views/trainer/workout_form.php';
    }

    public function workoutRegistrations() {
        $id = (int)($_GET['id'] ?? 0);
        $workout = $this->workoutModel->findById($id);
        if (!$workout || ($workout['trainer_id'] != $this->userId && getCurrentUserRole() !== 'admin')) {
            redirect('index.php?page=trainer/dashboard');
        }
        $registrations = $this->workoutModel->getRegistrations($id);
        include __DIR__ . '/../views/trainer/workout_registrations.php';
    }

    public function logAttendance() {
        $date = $_GET['date'] ?? date('Y-m-d');
        $workoutId = $_GET['workout_id'] ?? null;
        $members = (new User())->getMembers();
        $workouts = $this->workoutModel->getByTrainer($this->userId);
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && validateCSRF($_POST['csrf_token'] ?? '')) {
            $userId = (int)($_POST['user_id'] ?? 0);
            $workoutId = !empty($_POST['workout_id']) ? (int)$_POST['workout_id'] : null;
            $date = $_POST['date'] ?? date('Y-m-d');
            $time = $_POST['time'] ?? date('H:i:s');
            if (strlen($time) === 5) $time .= ':00';
            if ($userId && !$this->attendanceModel->alreadyLogged($userId, $date)) {
                $this->attendanceModel->log($userId, $workoutId, $date, $time, null, $this->userId);
                redirect('index.php?page=trainer/log-attendance&date=' . $date);
            } elseif ($userId) {
                $error = 'User already logged for this date.';
            }
        }
        include __DIR__ . '/../views/trainer/log_attendance.php';
    }
}
