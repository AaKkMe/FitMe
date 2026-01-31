<?php $pageTitle = 'Users'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <div class="page-header">
        <h1>Users</h1>
        <div class="actions">
            <a href="index.php?page=admin/user-add" class="btn btn-primary">Add User</a>
        </div>
    </div>
    
    <form method="get" class="filters">
        <input type="hidden" name="page" value="admin/users">
        <input type="text" name="search" placeholder="Search..." value="<?php echo e($search ?? ''); ?>">
        <select name="role">
            <option value="">All Roles</option>
            <option value="admin" <?php echo ($role ?? '') === 'admin' ? 'selected' : ''; ?>>Admin</option>
            <option value="trainer" <?php echo ($role ?? '') === 'trainer' ? 'selected' : ''; ?>>Trainer</option>
            <option value="user" <?php echo ($role ?? '') === 'user' ? 'selected' : ''; ?>>Member</option>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Membership</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                <tr>
                    <td><?php echo e($u['name']); ?></td>
                    <td><?php echo e($u['email']); ?></td>
                    <td><span class="badge"><?php echo e($u['role']); ?></span></td>
                    <td><?php echo e($u['membership_expires'] ?? '-'); ?></td>
                    <td class="table-actions">
                        <a href="index.php?page=admin/user-edit&id=<?php echo (int)$u['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                        <?php if ($u['id'] != getCurrentUser()): ?>
                        <div class="kebab-menu">
                            <button type="button" class="kebab-trigger" onclick="toggleKebab(this)" aria-label="More actions">⋯</button>
                            <div class="kebab-dropdown">
                                <form method="post" action="index.php?page=admin/user-delete" onsubmit="return confirm('Delete this user?');">
                                    <input type="hidden" name="csrf_token" value="<?php echo e(generateCSRFToken()); ?>">
                                    <input type="hidden" name="id" value="<?php echo (int)$u['id']; ?>">
                                    <button type="submit" class="danger">Delete</button>
                                </form>
                            </div>
                        </div>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
