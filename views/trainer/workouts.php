<?php $pageTitle = 'My Workouts'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <div class="page-header">
        <h1>My Workouts</h1>
        <a href="index.php?page=trainer/workout-add" class="btn btn-primary">Add Workout</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Schedule</th>
                    <th>Duration</th>
                    <th>Participants</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($workouts as $w): ?>
                <tr>
                    <td><?php echo e($w['name']); ?></td>
                    <td><?php echo e($w['category_name'] ?? '-'); ?></td>
                    <td><?php echo e($w['schedule_day']); ?> <?php echo e(substr($w['schedule_time'], 0, 5)); ?></td>
                    <td><?php echo (int)$w['duration_minutes']; ?> min</td>
                    <td><?php echo (int)($w['registered_count'] ?? 0); ?> / <?php echo (int)$w['max_participants']; ?></td>
                    <td class="table-actions">
                        <a href="index.php?page=trainer/workout-registrations&id=<?php echo (int)$w['id']; ?>" class="btn btn-sm btn-primary">Registrations</a>
                        <div class="kebab-menu">
                            <button type="button" class="kebab-trigger" onclick="toggleKebab(this)" aria-label="More actions">⋯</button>
                            <div class="kebab-dropdown">
                                <a href="index.php?page=trainer/workout-edit&id=<?php echo (int)$w['id']; ?>">Edit</a>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($workouts)): ?>
                <tr><td colspan="6">No workouts yet. Add your first workout!</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
