<?php $pageTitle = 'Workout Management'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <div class="page-header">
        <h1>Workouts</h1>
        <div class="actions">
            <a href="index.php?page=admin/workout-add" class="btn btn-primary">Add Workout</a>
        </div>
    </div>
    <form method="get" class="filters">
        <input type="hidden" name="page" value="admin/workouts">
        <input type="text" name="search" placeholder="Search workouts" value="<?php echo e($filters['search'] ?? ''); ?>">
        <select name="category_id">
            <option value="">All Categories</option>
            <?php foreach ($categories as $c): ?>
                <option value="<?php echo (int)$c['id']; ?>" <?php echo (($filters['category_id'] ?? '') == $c['id']) ? 'selected' : ''; ?>><?php echo e($c['name']); ?></option>
            <?php endforeach; ?>
        </select>
        <select name="trainer_id">
            <option value="">All Trainers</option>
            <?php foreach ($trainers as $t): ?>
                <option value="<?php echo (int)$t['id']; ?>" <?php echo (($filters['trainer_id'] ?? '') == $t['id']) ? 'selected' : ''; ?>><?php echo e($t['name']); ?></option>
            <?php endforeach; ?>
        </select>
        <select name="schedule_day">
            <option value="">All Days</option>
            <?php foreach (['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $d): ?>
                <option value="<?php echo e($d); ?>" <?php echo (($filters['schedule_day'] ?? '') === $d) ? 'selected' : ''; ?>><?php echo e($d); ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Trainer</th>
                    <th>Schedule</th>
                    <th>Duration</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($workouts as $w): ?>
                <tr>
                    <td><?php echo e($w['name']); ?></td>
                    <td><?php echo e($w['category_name'] ?? '-'); ?></td>
                    <td><?php echo e($w['trainer_name'] ?? '-'); ?></td>
                    <td><?php echo e($w['schedule_day']); ?> <?php echo e(substr($w['schedule_time'], 0, 5)); ?></td>
                    <td><?php echo (int)$w['duration_minutes']; ?> min</td>
                    <td><?php echo $w['is_active'] ? '<span class="badge badge-success">Active</span>' : '<span class="badge">Inactive</span>'; ?></td>
                    <td class="table-actions">
                        <a href="index.php?page=admin/workout-registrations&id=<?php echo (int)$w['id']; ?>" class="btn btn-sm btn-primary">Registrations</a>
                        <div class="kebab-menu">
                            <button type="button" class="kebab-trigger" onclick="toggleKebab(this)" aria-label="More actions">⋯</button>
                            <div class="kebab-dropdown">
                                <a href="index.php?page=admin/workout-edit&id=<?php echo (int)$w['id']; ?>">Edit</a>
                                <form method="post" action="index.php?page=admin/workout-delete" onsubmit="return confirm('Delete this workout?');">
                                    <input type="hidden" name="csrf_token" value="<?php echo e(generateCSRFToken()); ?>">
                                    <input type="hidden" name="id" value="<?php echo (int)$w['id']; ?>">
                                    <button type="submit" class="danger">Delete</button>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
