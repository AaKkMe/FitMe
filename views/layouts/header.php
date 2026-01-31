<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($pageTitle ?? 'FitMe'); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo htmlspecialchars(getBaseUrl()); ?>assets/css/style.css">
</head>
<body>
    <header class="main-header">
        <div class="container">
            <a href="<?php echo isLoggedIn() ? 'index.php?page=' . (getCurrentUserRole() === 'admin' ? 'admin/dashboard' : (getCurrentUserRole() === 'trainer' ? 'trainer/dashboard' : 'user/dashboard')) : 'index.php'; ?>" class="logo">
                <span>FitMe</span>
            </a>
            <nav class="main-nav">
                <?php if (isLoggedIn()): ?>
                    <?php if (getCurrentUserRole() === 'admin'): ?>
                        <a href="index.php?page=admin/dashboard">Dashboard</a>
                        <a href="index.php?page=admin/users">Users</a>
                        <a href="index.php?page=admin/workouts">Workouts</a>
                        <a href="index.php?page=admin/attendance">Attendance</a>
                    <?php elseif (getCurrentUserRole() === 'trainer'): ?>
                        <a href="index.php?page=trainer/dashboard">Dashboard</a>
                        <a href="index.php?page=trainer/workouts">My Workouts</a>
                        <a href="index.php?page=trainer/log-attendance">Log Attendance</a>
                    <?php else: ?>
                        <a href="index.php?page=user/dashboard">Dashboard</a>
                        <a href="index.php?page=user/workouts">Workouts</a>
                        <a href="index.php?page=user/attendance">My Attendance</a>
                    <?php endif; ?>
                    <span class="user-name"><?php echo e($_SESSION['user_name'] ?? ''); ?></span>
                    <a href="index.php?page=logout">Logout</a>
                <?php else: ?>
                    <a href="index.php?page=login">Login</a>
                    <a href="index.php?page=signup">Sign Up</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <main class="main-content">
