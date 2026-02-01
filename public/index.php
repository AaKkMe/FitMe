<?php
/**
 * FitMe Gym - Main entry point / Router
 * Plain PHP, no frameworks
 */
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../includes/functions.php';

$page = $_GET['page'] ?? 'home';

// Route mapping
$routes = [
    'home' => ['AuthController', 'login'],
    'login' => ['AuthController', 'login'],
    'signup' => ['AuthController', 'signup'],
    'logout' => ['AuthController', 'logout'],
    'unauthorized' => ['AuthController', 'unauthorized'],

    'admin/dashboard' => ['AdminController', 'dashboard'],
    'admin/users' => ['AdminController', 'users'],
    'admin/user-add' => ['AdminController', 'userAdd'],
    'admin/user-edit' => ['AdminController', 'userEdit'],
    'admin/user-delete' => ['AdminController', 'userDelete'],
    'admin/workouts' => ['AdminController', 'workouts'],
    'admin/workout-add' => ['AdminController', 'workoutAdd'],
    'admin/workout-edit' => ['AdminController', 'workoutEdit'],
    'admin/workout-delete' => ['AdminController', 'workoutDelete'],
    'admin/workout-registrations' => ['AdminController', 'workoutRegistrations'],
    'admin/attendance' => ['AdminController', 'attendance'],
    'admin/log-attendance' => ['AdminController', 'logAttendance'],

    'trainer/dashboard' => ['TrainerController', 'dashboard'],
    'trainer/workouts' => ['TrainerController', 'myWorkouts'],
    'trainer/workout-add' => ['TrainerController', 'workoutAdd'],
    'trainer/workout-edit' => ['TrainerController', 'workoutEdit'],
    'trainer/workout-registrations' => ['TrainerController', 'workoutRegistrations'],
    'trainer/log-attendance' => ['TrainerController', 'logAttendance'],

    'user/dashboard' => ['UserController', 'dashboard'],
    'user/workouts' => ['UserController', 'workouts'],
    'user/register-workout' => ['UserController', 'registerWorkout'],
    'user/unregister-workout' => ['UserController', 'unregisterWorkout'],
    'user/attendance' => ['UserController', 'attendance'],

    'api/check-email' => ['ApiController', 'checkEmail'],
    'api/search-users' => ['ApiController', 'searchUsers'],
    'api/search-workouts' => ['ApiController', 'searchWorkouts'],
];

if (isset($routes[$page])) {
    list($controller, $method) = $routes[$page];
    require_once __DIR__ . '/../controllers/' . $controller . '.php';
    $ctrl = new $controller();
    $ctrl->$method();
} else {
    header("HTTP/1.0 404 Not Found");
    include __DIR__ . '/../views/404.php';
}
