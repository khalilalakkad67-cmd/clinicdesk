<?php if (isset($_SESSION["flash"])): ?>
    <div class="alert alert-info">
        <?php echo htmlspecialchars($_SESSION["flash"]["message"]); ?>
    </div>
    <?php unset($_SESSION["flash"]); ?>
<?php endif; ?>