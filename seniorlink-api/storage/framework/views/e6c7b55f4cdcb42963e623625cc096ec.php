<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Birthdate Selector</title>
</head>
<body>
    <h1>Enter Your Birthdate</h1>
    <form method="POST" action="<?php echo e(route('login')); ?>">
        <?php echo csrf_field(); ?>
        <label for="birthdate">Birthdate:</label>
        <input type="date" id="birthdate" name="birthdate" required>
        <button type="submit">Login</button>
    </form>
</body>
</html>
<?php /**PATH /home/samsonrollolocal/SeniorLink/resources/views/birthdate_selector.blade.php ENDPATH**/ ?>