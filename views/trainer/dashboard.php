<?php $pageTitle = 'Dashboard'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <div class="dashboard-welcome">
        <h1>Welcome, <?php echo e($_SESSION['user_name'] ?? 'Trainer'); ?></h1>
        <p>Manage your classes and log attendance.</p>
    </div>
    
    <div class="dashboard-layout">
        <div class="dashboard-main">
            <div class="stats-row">
                <div class="stat-card stat-card-sm">
                    <span class="stat-number"><?php echo e(count($workouts)); ?></span>
                    <span class="stat-label">Your workouts</span>
                </div>
            </div>
            
            <div class="card card-featured">
                <h3>Log Attendance</h3>
                <p>Record member check-ins for your classes.</p>
                <a href="index.php?page=trainer/log-attendance" class="btn btn-primary" style="margin-top: 8px;">Log Now</a>
            </div>
            
            <div class="quick-actions">
                <a href="index.php?page=trainer/workouts" class="action-link">
                    <span class="action-icon">💪</span>
                    <span>My Workouts</span>
                </a>
                <a href="index.php?page=trainer/log-attendance" class="action-link">
                    <span class="action-icon">📋</span>
                    <span>Log Attendance</span>
                </a>
            </div>
            
            <div class="section-header">
                <h2>My Workouts</h2>
                <a href="index.php?page=trainer/workouts" class="btn btn-primary">View All</a>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Schedule</th>
                            <th>Participants</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($workouts, 0, 5) as $w): ?>
                        <tr>
                            <td><?php echo e($w['name']); ?></td>
                            <td><?php echo e($w['schedule_day']); ?> <?php echo e(substr($w['schedule_time'], 0, 5)); ?></td>
                            <td><?php echo (int)($w['registered_count'] ?? 0); ?> / <?php echo (int)$w['max_participants']; ?></td>
                            <td class="table-actions">
                                <a href="index.php?page=trainer/workout-registrations&id=<?php echo (int)$w['id']; ?>" class="btn btn-sm btn-primary">View</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($workouts)): ?>
                        <tr><td colspan="4">No workouts yet. <a href="index.php?page=trainer/workout-add">Add one</a></td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
