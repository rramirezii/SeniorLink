<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SeniorLink - Login</title>
</head>
<body>
    <h1>Enter Your Password</h1>
    <form method="POST" action="<?php echo e(route('test_login')); ?>">
        <?php echo csrf_field(); ?>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Login</button>
    </form>
</body>
</html>
<?php /**PATH /home/samsonrollolocal/SeniorLink/resources/views/password_login.blade.php ENDPATH**/ ?>