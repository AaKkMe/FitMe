<?php $pageTitle = 'Workout Registrations'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <div class="page-header">
        <h1>Registrations: <?php echo e($workout['name']); ?></h1>
        <a href="index.php?page=trainer/workouts" class="btn btn-primary">Back</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Member</th>
                    <th>Email</th>
                    <th>Registered</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registrations as $r): ?>
                <tr>
                    <td><?php echo e($r['name']); ?></td>
                    <td><?php echo e($r['email']); ?></td>
                    <td><?php echo e($r['registered_at']); ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($registrations)): ?>
                <tr><td colspan="3">No registrations yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
