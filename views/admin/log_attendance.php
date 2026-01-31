<?php $pageTitle = 'Log Attendance'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="container">
    <div class="page-header">
        <h1>Log Attendance</h1>
        <div class="actions">
            <a href="index.php?page=admin/attendance" class="btn btn-primary">View Attendance</a>
        </div>
    </div>
    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?php echo e($error); ?></div>
    <?php endif; ?>
    <div class="card" style="max-width: 480px;">
        <form method="post" action="index.php?page=admin/log-attendance">
            <input type="hidden" name="csrf_token" value="<?php echo e(generateCSRFToken()); ?>">
            <div class="form-group autocomplete-wrap">
                <label for="user_search">Search Member</label>
                <input type="text" id="user_search" placeholder="Type to search..." autocomplete="off">
                <input type="hidden" name="user_id" id="user_id">
                <div id="user-results" class="autocomplete-results" style="display:none;"></div>
            </div>
            <div class="form-group">
                <label for="workout_id">Workout (optional)</label>
                <select id="workout_id" name="workout_id">
                    <option value="">-- General check-in --</option>
                    <?php foreach ($workouts as $w): ?>
                        <option value="<?php echo (int)$w['id']; ?>"><?php echo e($w['name']); ?> (<?php echo e($w['schedule_day']); ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" name="date" id="date" value="<?php echo e($date ?? date('Y-m-d')); ?>" required>
            </div>
            <div class="form-group">
                <label for="time">Time</label>
                <input type="time" name="time" id="time" value="<?php echo date('H:i'); ?>" required>
            </div>
            <div class="form-group">
                <label for="notes">Notes</label>
                <input type="text" name="notes" id="notes">
            </div>
            <button type="submit" class="btn btn-primary">Log Attendance</button>
        </form>
    </div>
</div>
<script>
(function() {
    var searchEl = document.getElementById("user_search");
    var resultsEl = document.getElementById("user-results");
    var hiddenEl = document.getElementById("user_id");
    var timer;
    searchEl.addEventListener("input", function() {
        clearTimeout(timer);
        var q = this.value.trim();
        if (q.length < 2) { resultsEl.style.display = "none"; hiddenEl.value = ""; return; }
        timer = setTimeout(function() {
            fetch("index.php?page=api/search-users&q=" + encodeURIComponent(q))
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    resultsEl.innerHTML = "";
                    if (data.length === 0) {
                        resultsEl.innerHTML = "<div style='padding:0.5rem'>No members found</div>";
                    } else {
                        data.forEach(function(u) {
                            var d = document.createElement("div");
                            d.textContent = u.name + " (" + u.email + ")";
                            d.dataset.id = u.id;
                            d.dataset.name = u.name;
                            d.addEventListener("click", function() {
                                hiddenEl.value = this.dataset.id;
                                searchEl.value = this.dataset.name;
                                resultsEl.style.display = "none";
                            });
                            resultsEl.appendChild(d);
                        });
                    }
                    resultsEl.style.display = "block";
                });
        }, 200);
    });
    document.addEventListener("click", function() { resultsEl.style.display = "none"; });
    searchEl.addEventListener("click", function(e) { e.stopPropagation(); });
    resultsEl.addEventListener("click", function(e) { e.stopPropagation(); });
})();
</script>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
