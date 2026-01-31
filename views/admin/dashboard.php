<?php $pageTitle = 'Dashboard'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <div class="dashboard-welcome">
        <h1>Welcome, <?php echo e($_SESSION['user_name'] ?? 'Admin'); ?></h1>
        <p>Here's what's happening at the gym today.</p>
    </div>

    <div class="dashboard-layout">
        <div class="dashboard-main">
            <div class="stats-row">
                <div class="stat-card stat-card-sm">
                    <span class="stat-number"><?php echo e($userCount); ?></span>
                    <span class="stat-label">Members</span>
                </div>
                <div class="stat-card stat-card-sm">
                    <span class="stat-number"><?php echo e(count($workouts)); ?></span>
                    <span class="stat-label">Active Workouts</span>
                </div>
            </div>

            <div class="card card-featured">
                <h3>Today's Check-ins</h3>
                <div class="stat-number"><?php echo count($todayAttendance); ?></div>
                <?php if (!empty($todayByHour)): ?>
                <div class="mini-chart">
                    <?php 
                    $maxCnt = max(array_column($todayByHour, 'cnt'));
                    foreach ($todayByHour as $h): 
                        $pct = $maxCnt > 0 ? ($h['cnt'] / $maxCnt) * 100 : 0;
                    ?>
                    <div class="mini-chart-bar" title="<?php echo (int)$h['hour']; ?>:00 — <?php echo (int)$h['cnt']; ?> check-ins" style="height: <?php echo max(20, $pct); ?>%"></div>
                    <?php endforeach; ?>
                </div>
                <p class="chart-legend">By hour</p>
                <?php endif; ?>
                <?php if (!empty($todayAttendance)): ?>
                <div class="recent-checkins">
                    <?php foreach (array_slice($todayAttendance, 0, 3) as $a): ?>
                    <div class="activity-item">
                        <span class="activity-name"><?php echo e($a['user_name']); ?></span>
                        <span class="activity-meta"><?php echo e(substr($a['check_in_time'], 0, 5)); ?> · <?php echo e($a['workout_name'] ?? 'General'); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                <a href="index.php?page=admin/attendance" class="btn btn-primary" style="margin-top: 16px;">View all</a>
            </div>

            <div class="quick-actions">
                <a href="index.php?page=admin/users" class="action-link">
                    <span class="action-icon">👥</span>
                    <span>Manage Users</span>
                </a>
                <a href="index.php?page=admin/workouts" class="action-link">
                    <span class="action-icon">💪</span>
                    <span>Workouts</span>
                </a>
                <a href="index.php?page=admin/attendance" class="action-link">
                    <span class="action-icon">📋</span>
                    <span>Attendance</span>
                </a>
            </div>
        </div>

        <aside class="dashboard-sidebar">
            <div class="card">
                <h3>Recent Activity</h3>
                <div class="activity-feed">
                    <?php if (empty($recentActivity)): ?>
                    <p class="empty-state">No recent check-ins</p>
                    <?php else: ?>
                    <?php foreach ($recentActivity as $a): ?>
                    <div class="activity-item">
                        <span class="activity-name"><?php echo e($a['user_name']); ?></span>
                        <span class="activity-meta"><?php echo e($a['check_in_date']); ?> · <?php echo e(substr($a['check_in_time'], 0, 5)); ?></span>
                        <span class="activity-context"><?php echo e($a['workout_name'] ?? 'General'); ?></span>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </aside>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
