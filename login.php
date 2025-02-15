<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $conn = new mysqli('localhost', 'ubcq5myob5oyh', '8gktvc2di2wt', 'dbgc0tpjy1sw9n');

    if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

    $result = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header('Location: dashboard.php');
            exit();
        } else echo "Invalid password.";
    } else echo "No user found with that email.";

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
        form { display: inline-block; }
        input { margin: 10px 0; padding: 10px; width: 200px; }
    </style>
</head>
<body>
    <h2>Login</h2>
    <form method="post">
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>
