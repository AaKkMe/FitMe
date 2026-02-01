<?php $pageTitle = 'Login'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="auth-container">
    <div class="auth-box">
        <h1>Welcome back</h1>
        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?php echo e($error); ?></div>
        <?php endif; ?>
        <form method="post" action="index.php?page=login">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required placeholder="you@example.com" value="<?php echo e($_POST['email'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Enter password">
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Continue</button>
        </form>
        <p class="auth-switch">Don't have an account? <a href="index.php?page=signup">Create one</a></p>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
