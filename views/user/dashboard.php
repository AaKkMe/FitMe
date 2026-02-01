<?php $pageTitle = 'Dashboard'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <div class="dashboard-welcome">
        <h1>Welcome back, <?php echo e($_SESSION['user_name'] ?? 'Member'); ?></h1>
        <p>Track your workouts and attendance.</p>
    </div>
    
    <div class="dashboard-layout">
        <div class="dashboard-main">
            <div class="stats-row">
                <div class="stat-card stat-card-sm">
                    <span class="stat-number"><?php echo (int)($stats['total_visits'] ?? 0); ?></span>
                    <span class="stat-label">This month's check-ins</span>
                </div>
                <div class="stat-card stat-card-sm">
                    <span class="stat-number"><?php echo e(count($workouts)); ?></span>
                    <span class="stat-label">Available workouts</span>
                </div>
            </div>
            
            <div class="card card-featured">
                <h3>Recent Activity</h3>
                <?php if (empty($recentAttendance)): ?>
                <p class="empty-state">No attendance records yet.</p>
                <?php else: ?>
                <div class="activity-feed">
                    <?php foreach ($recentAttendance as $a): ?>
                    <div class="activity-item">
                        <span class="activity-name"><?php echo e($a['workout_name'] ?? 'General'); ?></span>
                        <span class="activity-meta"><?php echo e($a['check_in_date']); ?> · <?php echo e(substr($a['check_in_time'], 0, 5)); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                <a href="index.php?page=user/attendance" class="btn btn-primary" style="margin-top: 16px;">View all</a>
            </div>
            
            <div class="quick-actions">
                <a href="index.php?page=user/workouts" class="action-link">
                    <span class="action-icon">💪</span>
                    <span>Browse Workouts</span>
                </a>
                <a href="index.php?page=user/attendance" class="action-link">
                    <span class="action-icon">📋</span>
                    <span>My Attendance</span>
                </a>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
