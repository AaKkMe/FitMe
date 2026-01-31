<?php
/**
 * Admin Controller - User management, workout management, attendance
 */
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Workout.php';
require_once __DIR__ . '/../models/Attendance.php';
require_once __DIR__ . '/../models/Category.php';

class AdminController {
    private $userModel;
    private $workoutModel;
    private $attendanceModel;
    private $categoryModel;

    public function __construct() {
        requireRole('admin');
        $this->userModel = new User();
        $this->workoutModel = new Workout();
        $this->attendanceModel = new Attendance();
        $this->categoryModel = new Category();
    }

    public function dashboard() {
        $userCount = count($this->userModel->getAll('user'));
        $workouts = $this->workoutModel->getAll(['is_active' => 1]);
        $todayAttendance = $this->attendanceModel->getByDate(date('Y-m-d'));
        $recentActivity = $this->attendanceModel->getRecentActivity(10);
        $todayByHour = $this->attendanceModel->getTodayByHour();
        include __DIR__ . '/../views/admin/dashboard.php';
    }

    public function users() {
        $search = trim($_GET['search'] ?? '');
        $role = $_GET['role'] ?? null;
        $users = $this->userModel->getAll($role, $search);
        include __DIR__ . '/../views/admin/users.php';
    }

    public function userAdd() {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && validateCSRF($_POST['csrf_token'] ?? '')) {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? 'user';
            $phone = trim($_POST['phone'] ?? '');
            $membership = $_POST['membership_expires'] ?: null;
            if (empty($name) || empty($email) || empty($password)) {
                $error = 'Name, email and password are required.';
            } elseif ($this->userModel->emailExists($email)) {
                $error = 'Email already exists.';
            } else {
                $this->userModel->create($name, $email, $password, $role, $phone, $membership);
                redirect('index.php?page=admin/users');
            }
        }
        include __DIR__ . '/../views/admin/user_form.php';
    }

    public function userEdit() {
        $id = (int)($_GET['id'] ?? 0);
        $user = $this->userModel->findById($id);
        if (!$user) redirect('index.php?page=admin/users');
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && validateCSRF($_POST['csrf_token'] ?? '')) {
            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'role' => $_POST['role'] ?? 'user',
                'phone' => trim($_POST['phone'] ?? ''),
                'membership_expires' => $_POST['membership_expires'] ?: null
            ];
            if (!empty($_POST['password'])) $data['password'] = $_POST['password'];
            if ($this->userModel->emailExists($data['email'], $id)) {
                $error = 'Email already exists.';
            } else {
                $this->userModel->update($id, $data);
                redirect('index.php?page=admin/users');
            }
        }
        include __DIR__ . '/../views/admin/user_form.php';
    }

    public function userDelete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && validateCSRF($_POST['csrf_token'] ?? '')) {
            $id = (int)($_POST['id'] ?? 0);
            if ($id && $id != getCurrentUser()) {
                $this->userModel->delete($id);
            }
        }
        redirect('index.php?page=admin/users');
    }

    public function workouts() {
        $filters = [
            'search' => trim($_GET['search'] ?? ''),
            'category_id' => $_GET['category_id'] ?? null,
            'trainer_id' => $_GET['trainer_id'] ?? null,
            'schedule_day' => $_GET['schedule_day'] ?? null
        ];
        $workouts = $this->workoutModel->getAll($filters);
        $categories = $this->categoryModel->getAll();
        $trainers = $this->userModel->getTrainers();
        include __DIR__ . '/../views/admin/workouts.php';
    }

    public function workoutAdd() {
        $categories = $this->categoryModel->getAll();
        $trainers = $this->userModel->getTrainers();
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && validateCSRF($_POST['csrf_token'] ?? '')) {
            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'category_id' => $_POST['category_id'] ?: null,
                'trainer_id' => $_POST['trainer_id'] ?: null,
                'schedule_day' => $_POST['schedule_day'] ?? '',
                'schedule_time' => $_POST['schedule_time'] ?? '09:00',
                'duration_minutes' => (int)($_POST['duration_minutes'] ?? 60),
                'max_participants' => (int)($_POST['max_participants'] ?? 20),
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];
            if (empty($data['name']) || empty($data['schedule_day'])) {
                $error = 'Name and schedule day are required.';
            } else {
                $this->workoutModel->create($data);
                redirect('index.php?page=admin/workouts');
            }
        }
        include __DIR__ . '/../views/admin/workout_form.php';
    }

    public function workoutEdit() {
        $id = (int)($_GET['id'] ?? 0);
        $workout = $this->workoutModel->findById($id);
        if (!$workout) redirect('index.php?page=admin/workouts');
        $categories = $this->categoryModel->getAll();
        $trainers = $this->userModel->getTrainers();
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && validateCSRF($_POST['csrf_token'] ?? '')) {
            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'category_id' => $_POST['category_id'] ?: null,
                'trainer_id' => $_POST['trainer_id'] ?: null,
                'schedule_day' => $_POST['schedule_day'] ?? '',
                'schedule_time' => $_POST['schedule_time'] ?? '09:00',
                'duration_minutes' => (int)($_POST['duration_minutes'] ?? 60),
                'max_participants' => (int)($_POST['max_participants'] ?? 20),
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];
            $this->workoutModel->update($id, $data);
            redirect('index.php?page=admin/workouts');
        }
        include __DIR__ . '/../views/admin/workout_form.php';
    }

    public function workoutDelete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && validateCSRF($_POST['csrf_token'] ?? '')) {
            $id = (int)($_POST['id'] ?? 0);
            $this->workoutModel->delete($id);
        }
        redirect('index.php?page=admin/workouts');
    }

    public function attendance() {
        $date = $_GET['date'] ?? date('Y-m-d');
        $records = $this->attendanceModel->getByDate($date);
        include __DIR__ . '/../views/admin/attendance.php';
    }

    public function logAttendance() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && validateCSRF($_POST['csrf_token'] ?? '')) {
            $userId = (int)($_POST['user_id'] ?? 0);
            $workoutId = !empty($_POST['workout_id']) ? (int)$_POST['workout_id'] : null;
            $date = $_POST['date'] ?? date('Y-m-d');
            $time = $_POST['time'] ?? date('H:i:s');
            if (strlen($time) === 5) $time .= ':00';
            $notes = trim($_POST['notes'] ?? '');
            if ($userId) {
                if (!$this->attendanceModel->alreadyLogged($userId, $date)) {
                    $this->attendanceModel->log($userId, $workoutId, $date, $time, $notes ?: null, getCurrentUser());
                }
            }
            redirect('index.php?page=admin/attendance&date=' . urlencode($date));
        }
        $date = $_GET['date'] ?? date('Y-m-d');
        $members = $this->userModel->getMembers();
        $workouts = $this->workoutModel->getAll(['is_active' => 1]);
        include __DIR__ . '/../views/admin/log_attendance.php';
    }

    public function workoutRegistrations() {
        $id = (int)($_GET['id'] ?? 0);
        $workout = $this->workoutModel->findById($id);
        if (!$workout) redirect('index.php?page=admin/workouts');
        $registrations = $this->workoutModel->getRegistrations($id);
        include __DIR__ . '/../views/admin/workout_registrations.php';
    }
}
