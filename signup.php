<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $initial_balance = 1000.00; // Set the initial balance to $1000

    // Connect to the database
    $conn = new mysqli('localhost', 'ubcq5myob5oyh', '8gktvc2di2wt', 'dbgc0tpjy1sw9n');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert user with the initial balance
    $sql = "INSERT INTO users (email, password, balance) VALUES ('$email', '$password', $initial_balance)";
    if ($conn->query($sql)) {
        echo "Account created successfully with a starting balance of $1000. <a href='login.php'>Login</a>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
        form { display: inline-block; }
        input { margin: 10px 0; padding: 10px; width: 200px; }
    </style>
</head>
<body>
    <h2>Create an Account</h2>
    <form method="post">
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Sign Up</button>
    </form>
</body>
</html>
