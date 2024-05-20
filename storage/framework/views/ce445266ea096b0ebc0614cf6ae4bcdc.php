<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    Bobo
</head>
<body>
    <div id="app">
        <!-- Your Vue component will be rendered here -->
        <example-component></example-component>
    </div>

    <script src="<?php echo e(mix('js/app.js')); ?>"></script>
</body>
</html>
<?php /**PATH /home/samsonrollolocal/SeniorLink/resources/views/layouts/app.blade.php ENDPATH**/ ?>