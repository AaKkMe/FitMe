    </main>
    <footer class="main-footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> FitMe Gym Management System</p>
        </div>
    </footer>
    <script src="<?php echo htmlspecialchars(getBaseUrl()); ?>assets/js/app.js"></script>
    <?php if (isset($extraScript)) echo $extraScript; ?>
</body>
</html>
