<?php $pageTitle = 'Attendance'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <div class="page-header">
        <h1>Attendance</h1>
        <a href="index.php?page=admin/log-attendance" class="btn btn-primary">Log Attendance</a>
    </div>
    <form method="get" class="filters">
        <input type="hidden" name="page" value="admin/attendance">
        <label>Date: <input type="date" name="date" value="<?php echo e($date ?? date('Y-m-d')); ?>"></label>
        <button type="submit" class="btn btn-primary">View</button>
    </form>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Member</th>
                    <th>Workout</th>
                    <th>Check-in Time</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($records as $r): ?>
                <tr>
                    <td><?php echo e($r['user_name']); ?></td>
                    <td><?php echo e($r['workout_name'] ?? 'General'); ?></td>
                    <td><?php echo e($r['check_in_time']); ?></td>
                    <td><?php echo e($r['notes'] ?? '-'); ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($records)): ?>
                <tr><td colspan="4">No attendance records for this date.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
