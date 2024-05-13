<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SeniorLink - Login</title>
</head>
<body>
    <h1>Enter Your Password</h1>
    <form method="POST" action="{{ route('test_login') }}">
        @csrf
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Login</button>
    </form>
</body>
</html>
