<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SeniorLink - Login</title>
</head>
<body>
    <form method="POST" action="<?php echo e(route('login')); ?>">
        <?php echo csrf_field(); ?>
        <label for="username">User ID:</label>
        <input type="text" id="username" name="username" required>
        <button type="submit">Continue</button>
    </form>
</body>
</html>
<?php /**PATH /home/samsonrollolocal/SeniorLink/resources/views/login.blade.php ENDPATH**/ ?>