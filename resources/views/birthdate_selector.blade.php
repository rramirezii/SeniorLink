<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SeniorLink - Login</title>
</head>
<body>
    <h1>Enter Your Birthdate</h1>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <label for="birthdate">Birthdate:</label>
        <input type="date" id="birthdate" name="birthdate" required>
        <button type="submit">Login</button>
    </form>
</body>
</html>
