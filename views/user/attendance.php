<?php $pageTitle = 'My Attendance'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <h1>My Attendance</h1>
    <form method="get" class="filters">
        <input type="hidden" name="page" value="user/attendance">
        <label>From: <input type="date" name="from" value="<?php echo e($from ?? date('Y-m-01')); ?>"></label>
        <label>To: <input type="date" name="to" value="<?php echo e($to ?? date('Y-m-t')); ?>"></label>
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Workout</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($records as $r): ?>
                <tr>
                    <td><?php echo e($r['check_in_date']); ?></td>
                    <td><?php echo e(substr($r['check_in_time'], 0, 5)); ?></td>
                    <td><?php echo e($r['workout_name'] ?? 'General'); ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($records)): ?>
                <tr><td colspan="3">No attendance records in this period.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
