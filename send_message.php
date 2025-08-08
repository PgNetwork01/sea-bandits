<?php
session_start();
include('includes/db.php'); // Include your database connection file

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sender = $_SESSION['username'];
    $receiver = $_POST['receiver'];
    $message = $_POST['message'];

    // Validate input
    if (empty($receiver) || empty($message)) {
        echo "Receiver or message is empty.";
        exit();
    }

    // Insert message into the database
    $sql = "INSERT INTO messages (sender, receiver, message, sent_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $sender, $receiver, $message);

    if ($stmt->execute()) {
        header("Location: chat.php?username=" . urlencode($receiver));
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
