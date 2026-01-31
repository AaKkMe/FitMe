<?php
/**
 * Auth Controller - Login, Signup, Logout
 */
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function login() {
        if (isLoggedIn()) {
            redirect(getDashboardUrl());
        }
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            if (empty($email) || empty($password)) {
                $error = 'Email and password are required.';
            } else {
                $user = $this->userModel->findByEmail($email);
                if ($user && $this->userModel->verifyPassword($user, $password)) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['role'] = $user['role'];
                    redirect(getDashboardUrl());
                } else {
                    $error = 'Invalid email or password.';
                }
            }
        }
        include __DIR__ . '/../views/auth/login.php';
    }

    public function signup() {
        if (isLoggedIn()) {
            redirect(getDashboardUrl());
        }
        $error = '';
        $success = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';
            if (empty($name) || empty($email) || empty($password)) {
                $error = 'All fields are required.';
            } elseif (strlen($password) < 6) {
                $error = 'Password must be at least 6 characters.';
            } elseif ($password !== $confirm) {
                $error = 'Passwords do not match.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Invalid email format.';
            } elseif ($this->userModel->emailExists($email)) {
                $error = 'Email already registered.';
            } else {
                $this->userModel->create($name, $email, $password, 'user');
                $success = 'Account created! You can now log in.';
            }
        }
        include __DIR__ . '/../views/auth/signup.php';
    }

    public function logout() {
        session_destroy();
        redirect('index.php');
    }

    public function unauthorized() {
        include __DIR__ . '/../views/auth/unauthorized.php';
    }
}

function getDashboardUrl() {
    $role = $_SESSION['role'] ?? 'user';
    if ($role === 'admin') return 'index.php?page=admin/dashboard';
    if ($role === 'trainer') return 'index.php?page=trainer/dashboard';
    return 'index.php?page=user/dashboard';
}
