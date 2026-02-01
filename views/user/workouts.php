<?php $pageTitle = 'Workouts'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <h1>Available Workouts</h1>
    <div class="card-grid">
        <?php foreach ($workouts as $w): ?>
        <div class="card">
            <h3><?php echo e($w['name']); ?></h3>
            <p><strong><?php echo e($w['category_name'] ?? 'General'); ?></strong> &bull; <?php echo e($w['schedule_day']); ?> <?php echo e(substr($w['schedule_time'], 0, 5)); ?></p>
            <p><?php echo e($w['description'] ?: 'No description.'); ?></p>
            <p><?php echo (int)($w['registered_count'] ?? 0); ?> / <?php echo (int)$w['max_participants']; ?> registered</p>
            <p>Trainer: <?php echo e($w['trainer_name'] ?? 'TBA'); ?></p>
            <?php if ($w['is_registered'] ?? false): ?>
                <form method="post" action="index.php?page=user/unregister-workout" style="display:inline">
                    <input type="hidden" name="csrf_token" value="<?php echo e(generateCSRFToken()); ?>">
                    <input type="hidden" name="workout_id" value="<?php echo (int)$w['id']; ?>">
                    <button type="submit" class="btn btn-danger">Unregister</button>
                </form>
            <?php else: ?>
                <form method="post" action="index.php?page=user/register-workout" style="display:inline">
                    <input type="hidden" name="csrf_token" value="<?php echo e(generateCSRFToken()); ?>">
                    <input type="hidden" name="workout_id" value="<?php echo (int)$w['id']; ?>">
                    <button type="submit" class="btn btn-primary">Register</button>
                </form>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php if (empty($workouts)): ?>
    <p>No workouts available at the moment.</p>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
