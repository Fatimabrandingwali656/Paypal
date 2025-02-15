<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$conn = new mysqli('localhost', 'ubcq5myob5oyh', '8gktvc2di2wt', 'dbgc0tpjy1sw9n');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sender_id = $_SESSION['user_id'];
    $receiver_email = $_POST['receiver_email'];
    $amount = (float) $_POST['amount'];

    // Fetch sender details
    $sender = $conn->query("SELECT * FROM users WHERE id=$sender_id")->fetch_assoc();

    if ($sender['balance'] < $amount) {
        echo "Insufficient balance. <a href='dashboard.php'>Go Back</a>";
        exit();
    }

    // Fetch receiver details
    $receiver = $conn->query("SELECT * FROM users WHERE email='$receiver_email'")->fetch_assoc();
    if (!$receiver) {
        echo "Recipient not found. <a href='dashboard.php'>Go Back</a>";
        exit();
    }

    $receiver_id = $receiver['id'];

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Deduct from sender
        $conn->query("UPDATE users SET balance = balance - $amount WHERE id=$sender_id");

        // Add to receiver
        $conn->query("UPDATE users SET balance = balance + $amount WHERE id=$receiver_id");

        // Record transaction
        $conn->query("INSERT INTO transactions (sender_id, receiver_id, amount, timestamp) 
                      VALUES ($sender_id, $receiver_id, $amount, NOW())");

        // Commit transaction
        $conn->commit();
        echo "Payment of $$amount sent successfully to $receiver_email. <a href='dashboard.php'>Go Back</a>";
    } catch (Exception $e) {
        $conn->rollback();
        echo "Payment failed. Please try again. <a href='dashboard.php'>Go Back</a>";
    }
}

$conn->close();
?>
