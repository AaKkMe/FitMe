<?php $pageTitle = 'Sign Up'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="auth-container">
    <div class="auth-box">
        <h1>Create account</h1>
        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?php echo e($error); ?></div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?php echo e($success); ?></div>
        <?php endif; ?>
        <form method="post" action="index.php?page=signup" id="signup-form">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" required placeholder="Your name" value="<?php echo e($_POST['name'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required placeholder="you@example.com" value="<?php echo e($_POST['email'] ?? ''); ?>">
                <span class="field-hint" id="email-hint"></span>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required minlength="6" placeholder="Min 6 characters">
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required placeholder="Repeat password">
                <span class="field-hint" id="confirm-hint"></span>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Create Account</button>
        </form>
        <p class="auth-switch">Already have an account? <a href="index.php?page=login">Log in</a></p>
    </div>
</div>

<?php
$extraScript = '<script>
document.getElementById("signup-form").addEventListener("input", function() {
    var p = document.getElementById("password").value;
    var c = document.getElementById("confirm_password").value;
    var h = document.getElementById("confirm-hint");
    if (c && p !== c) h.textContent = "Passwords do not match"; else h.textContent = "";
});
document.getElementById("email").addEventListener("blur", function() {
    var email = this.value.trim();
    var hint = document.getElementById("email-hint");
    if (email.length < 5) { hint.textContent = ""; return; }
    fetch("index.php?page=api/check-email&email=" + encodeURIComponent(email))
        .then(r => r.json())
        .then(d => { hint.textContent = d.exists ? "Email already registered" : ""; hint.className = "field-hint " + (d.exists ? "error" : ""); });
});
</script>';
?>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
