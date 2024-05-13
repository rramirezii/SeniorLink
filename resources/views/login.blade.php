<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <label for="username">User ID:</label>
        <input type="text" id="username" name="username" required>
        <button type="submit">Continue</button>
    </form>
</body>
</html>
