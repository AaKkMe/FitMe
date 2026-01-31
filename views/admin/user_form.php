<?php $pageTitle = isset($user) ? 'Edit User' : 'Add User'; ?>
<?php $isEdit = isset($user); ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <div class="page-header">
        <h1><?php echo $isEdit ? 'Edit User' : 'Add User'; ?></h1>
        <a href="index.php?page=admin/users" class="btn btn-primary">Back</a>
    </div>
    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?php echo e($error); ?></div>
    <?php endif; ?>
    <div class="card" style="max-width: 480px;">
        <form method="post" action="index.php?page=admin/<?php echo $isEdit ? 'user-edit&id=' . (int)$user['id'] : 'user-add'; ?>">
            <input type="hidden" name="csrf_token" value="<?php echo e(generateCSRFToken()); ?>">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required value="<?php echo e($user['name'] ?? $_POST['name'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required value="<?php echo e($user['email'] ?? $_POST['email'] ?? ''); ?>" <?php echo $isEdit ? '' : ''; ?>>
                <span class="field-hint" id="email-hint"></span>
            </div>
            <div class="form-group">
                <label for="password">Password <?php echo $isEdit ? '(leave blank to keep)' : ''; ?></label>
                <input type="password" id="password" name="password" <?php echo $isEdit ? '' : 'required'; ?> minlength="6">
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <select id="role" name="role">
                    <option value="user" <?php echo ($user['role'] ?? '') === 'user' ? 'selected' : ''; ?>>Member</option>
                    <option value="trainer" <?php echo ($user['role'] ?? '') === 'trainer' ? 'selected' : ''; ?>>Trainer</option>
                    <option value="admin" <?php echo ($user['role'] ?? '') === 'admin' ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone" value="<?php echo e($user['phone'] ?? $_POST['phone'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="membership_expires">Membership Expires</label>
                <input type="date" id="membership_expires" name="membership_expires" value="<?php echo e($user['membership_expires'] ?? $_POST['membership_expires'] ?? ''); ?>">
            </div>
            <button type="submit" class="btn btn-primary"><?php echo $isEdit ? 'Update' : 'Create'; ?></button>
        </form>
    </div>
</div>
<?php if ($isEdit): ?>
<script>
document.getElementById("email").addEventListener("blur", function() {
    var email = this.value.trim();
    var hint = document.getElementById("email-hint");
    var excludeId = <?php echo (int)($user['id'] ?? 0); ?>;
    if (email.length < 5) { hint.textContent = ""; return; }
    fetch("index.php?page=api/check-email&email=" + encodeURIComponent(email) + "&exclude_id=" + excludeId)
        .then(r => r.json())
        .then(d => { hint.textContent = d.exists ? "Email already in use" : ""; hint.className = "field-hint " + (d.exists ? "error" : ""); });
});
</script>
<?php else: ?>
<script>
document.getElementById("email").addEventListener("blur", function() {
    var email = this.value.trim();
    var hint = document.getElementById("email-hint");
    if (email.length < 5) { hint.textContent = ""; return; }
    fetch("index.php?page=api/check-email&email=" + encodeURIComponent(email))
        .then(r => r.json())
        .then(d => { hint.textContent = d.exists ? "Email already in use" : ""; hint.className = "field-hint " + (d.exists ? "error" : ""); });
});
</script>
<?php endif; ?>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
