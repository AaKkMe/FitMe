<?php $pageTitle = isset($workout) ? 'Edit Workout' : 'Add Workout'; ?>
<?php $isEdit = isset($workout); ?>
<?php $w = $workout ?? []; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <div class="page-header">
        <h1><?php echo $isEdit ? 'Edit Workout' : 'Add Workout'; ?></h1>
        <a href="index.php?page=admin/workouts" class="btn btn-primary">Back</a>
    </div>
    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?php echo e($error); ?></div>
    <?php endif; ?>
    <div class="card" style="max-width: 520px;">
        <form method="post" action="index.php?page=admin/<?php echo $isEdit ? 'workout-edit&id=' . (int)$w['id'] : 'workout-add'; ?>">
            <input type="hidden" name="csrf_token" value="<?php echo e(generateCSRFToken()); ?>">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required value="<?php echo e($w['name'] ?? $_POST['name'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="3"><?php echo e($w['description'] ?? $_POST['description'] ?? ''); ?></textarea>
            </div>
            <div class="form-group">
                <label for="category_id">Category</label>
                <select id="category_id" name="category_id">
                    <option value="">-- Select --</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?php echo (int)$c['id']; ?>" <?php echo (($w['category_id'] ?? '') == $c['id']) ? 'selected' : ''; ?>><?php echo e($c['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="trainer_id">Trainer</label>
                <select id="trainer_id" name="trainer_id">
                    <option value="">-- Select --</option>
                    <?php foreach ($trainers as $t): ?>
                        <option value="<?php echo (int)$t['id']; ?>" <?php echo (($w['trainer_id'] ?? '') == $t['id']) ? 'selected' : ''; ?>><?php echo e($t['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="schedule_day">Day</label>
                <select id="schedule_day" name="schedule_day" required>
                    <?php foreach (['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $d): ?>
                        <option value="<?php echo e($d); ?>" <?php echo (($w['schedule_day'] ?? '') === $d) ? 'selected' : ''; ?>><?php echo e($d); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="schedule_time">Time</label>
                <input type="time" id="schedule_time" name="schedule_time" value="<?php echo e(substr($w['schedule_time'] ?? '09:00', 0, 5)); ?>">
            </div>
            <div class="form-group">
                <label for="duration_minutes">Duration (minutes)</label>
                <input type="number" id="duration_minutes" name="duration_minutes" value="<?php echo (int)($w['duration_minutes'] ?? 60); ?>" min="15">
            </div>
            <div class="form-group">
                <label for="max_participants">Max Participants</label>
                <input type="number" id="max_participants" name="max_participants" value="<?php echo (int)($w['max_participants'] ?? 20); ?>">
            </div>
            <?php if (!$isEdit || getCurrentUserRole() === 'admin'): ?>
            <div class="form-group">
                <label><input type="checkbox" name="is_active" value="1" <?php echo (($w['is_active'] ?? 1)) ? 'checked' : ''; ?>> Active</label>
            </div>
            <?php endif; ?>
            <button type="submit" class="btn btn-primary"><?php echo $isEdit ? 'Update' : 'Create'; ?></button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
